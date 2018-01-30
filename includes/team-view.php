<?php

namespace ots;

/**
 * Enqueue short-code scripts.
 *
 * @since 4.0.0
 */
function enqueue_team_view_scripts() {

    wp_enqueue_style( 'ots-team-view' );
    wp_enqueue_script( 'ots' );

    // Plugins can hook on here to have their scripts loaded only on shortcode or widget pages
    do_action( 'ots_enqueue_scripts' );

}


/**
 * Render the short-code content and supply default attributes.
 *
 * @param array $attributes
 * @return string
 * @since 4.0.0
 */
function do_shortcode_output( $attributes = array() ) {

    $defaults = apply_filters( 'ots_default_shortcode_atts', array(
        'id'              => '',
        'group'           => '',
        'columns'         => get_option( Options::GRID_COLUMNS ),
        'limit'           => get_option( Options::DISPLAY_LIMIT ),
        'template'        => get_option( Options::TEMPLATE ),
        'single_template' => get_option( Options::SINGLE_TEMPLATE ),
    ) );

    return do_team_view_output( shortcode_atts( $defaults, $attributes, 'our-team' ) );

}

add_shortcode( 'our-team', 'ots\do_shortcode_output' );


function do_team_view_output( array $args = array() ) {

	$defaults = array(
		'id'              => '',
		'group'           => '',
		'limit'           => -1,
		'columns'         => Defaults::GRID_COLUMNS,
		'template'        => Defaults::TEMPLATE,
		'single_template' => Defaults::SINGLE_TEMPLATE
	);

	$args = wp_parse_args( $args, $defaults );

    // Allow for passing multiple groups
	if ( !empty( $args['group'] ) && !is_array( $args['group'] ) ) {
	    $args['group'] =  explode( ',', $args['group'] );
    }

	// Cache the post query
	$args['members'] = get_members_in_order( $args['limit'], $args['group'] );
	$args['guid'] = uniqid( 'ots-' );

	// See if the template belongs to this plugin
	$file = template_path( map_template( $args['template'] ) );


	// Start the buffer
	ob_start();
	extract( $args );

	echo '<div class="ots-team-view" id="' . esc_attr( $args['id'] ) . '" data-id="' . esc_attr( $args['guid'] ) . '">';

        $template = apply_filters( 'ots_template_include', $file ? $file : $args['template'] );

        do_action( 'ots_before_team_members', $args );


        // If the template file doesn't exist, fallback to the default
        if( file_exists( $template ) ) {

            include $template;

        } else {

            include template_path( map_template( Defaults::TEMPLATE ) );

        }


        // Hook onto for output inside shortcode after the template as rendered
        do_action( 'ots_after_team_members', $args );

	echo '</div>';


	return apply_filters( 'ots_team_view_output', ob_get_clean(), $args );

}


/**
 * Sets up a hook to be called when the current page or post is using the short-code.
 *
 * @since 4.0.0
 */
function page_redirect() {

    if( is_team_view_page() ) {

        enqueue_team_view_scripts();

    }

}

add_action( 'template_redirect', 'ots\page_redirect' );


/**
 * Checks if the current page uses the short-code.
 *
 * @return bool Whether or not the current page uses the short-code.
 * @since 4.0.0
 */
function is_team_view_page() {

    global $post;

    return is_a( $post, '\WP_Post' ) && has_shortcode( $post->post_content, 'our-team' );

}


/**
 * Print user configurable styles.
 *
 * @since 4.0.0
 */
function print_dynamic_styles() { ?>

    <!-- Global -->

    <style>

        .sc_team_single_member .sc_single_side .social span {
            background: <?php esc_html_e( get_option( Options::MAIN_COLOR ) ); ?>;
        }

    </style>

    <!-- Grid -->

    <style>

        .grid#sc_our_team .sc_team_member .sc_team_member_name,
        .grid#sc_our_team .sc_team_member .sc_team_member_jobtitle {
            background: <?php esc_html_e( get_option( Options::MAIN_COLOR ) ) ?>;
        }

        .grid#sc_our_team .sc_team_member {
            padding: <?php esc_html_e( get_option( Options::MARGIN ) ); ?>px !important;
        }

    </style>

    <!-- Grid Circles -->

    <style>

        .grid_circles#sc_our_team .sc_team_member .sc_team_member_jobtitle,
        .grid_circles#sc_our_team .sc_team_member .sc_team_member_name {
            background: <?php esc_html_e( get_option( Options::MAIN_COLOR ) ) ?>;
        }

        .grid_circles#sc_our_team .sc_team_member {
            margin: <?php esc_html_e( get_option( Options::MARGIN ) ); ?>px;
        }

    </style>

    <!-- Grid Circles 2 -->

    <style>

        .grid_circles2#sc_our_team .sc_team_member {
            margin: <?php esc_html_e( get_option( Options::MARGIN ) ); ?>px;
        }

    </style>

<?php }

add_action( 'wp_print_styles', 'ots\print_dynamic_styles' );