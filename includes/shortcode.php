<?php

namespace ots;

/**
 * Enqueue shortcode scripts.
 *
 * @since 4.0.0
 */
function enqueue_shortcode_scripts() {

    wp_enqueue_style( 'ots-css', asset( 'css/global.css' ), null, VERSION );
    wp_enqueue_style( 'ots-grid-css', asset( 'css/grid.css' ), null, VERSION );
    wp_enqueue_style( 'ots-grid-circles-css', asset( 'css/grid-circles.css' ), null, VERSION );
    wp_enqueue_style( 'ots-grid-circles-2-css', asset( 'css/grid-circles-2.css' ), null, VERSION );

    wp_enqueue_script( 'ots-js', asset( 'js/script.js' ), array( 'jquery' ), VERSION );

}

add_action( 'ots_page_redirect', 'ots\enqueue_shortcode_scripts' );


/**
 * Render the shortcode content and supply default attributes.
 *
 * @param array $attributes
 * @return string
 * @since 4.0.0
 */
function do_shortcode_output( $attributes = array() ) {

    $defaults = array(
        'group'           => '',
        'template'        => get_option( Options::TEMPLATE ),
        'single_template' => get_option( Options::SINGLE_TEMPLATE )
    );

    $args = shortcode_atts( apply_filters( 'ots_default_shortcode_atts', $defaults ), $attributes );

    // Cache the post query
    $args['members'] = get_members_in_order( null, $args['group'] );

    // See if the template belongs to this plugin
    $file = template_path( map_template( $args['template'] ) );


    // Start the buffer
    ob_start();
    extract( $args );

    $template = apply_filters( 'ots_template_include', $file ? $file : $args['template'] );

    do_action( 'ots_before_team_members', $args );


    // If the template file doesn't exist, fallback to the default
    if( file_exists( $template ) ) {
        include_once $template;
    } else {
        include_once template_path( map_template( Defaults::TEMPLATE ) );
    }


    // Hook onto for output inside shortcode after the template as rendered
    do_action( 'ots_after_team_members', $args );

    return apply_filters( 'ots_shortcode_output', ob_get_clean(), $args );

}

add_shortcode( 'our-team', 'ots\do_shortcode_output' );


function page_redirect() {

    if( is_shortcode_page() ) {

        // Cut down on calls to has_shortcode()
        do_action( 'ots_page_redirect' );

    }

}

add_action( 'template_redirect', 'ots\page_redirect' );


function is_shortcode_page() {

    global $post;

    return has_shortcode( $post->post_content, 'our-team' );

}


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
            padding: <?php esc_html_e( get_option( Options::MARGIN ) ); ?>px;
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