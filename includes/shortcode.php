<?php

namespace ots;

/**
 * Enqueue shortcode scripts.
 *
 * @since 4.0.0
 */
function enqueue_scripts() {

    if( apply_filters( 'ots_load_default_scripts', true ) ) {


        wp_enqueue_style( "ots-grid-css", asset( "css/grid.css" ), null, VERSION );
        wp_enqueue_style( "ots-grid-circles-css", asset( "css/grid-circles.css" ), null, VERSION );
        wp_enqueue_style( "ots-grid-circles-2-css", asset( "css/grid-circles-2.css" ), null, VERSION );
        wp_enqueue_style( "ots-css", asset( "css/global.css" ), null, VERSION );

    }

    wp_enqueue_script( 'ots-js', asset( 'js/script.js' ), array( 'jquery' ), VERSION );

}

add_action( 'wp_enqueue_scripts', 'ots\enqueue_scripts' );


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
    $args['members'] = get_members_in_order( $args['group'] );

    // Start the buffer
    ob_start();
    extract( $args );

    $template = apply_filters( 'ots_template_include', $args['template'] );

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


function include_template( $template ) {

    $file = template_path( map_template( $template ) );

    if( $file ) {
        $template = $file;
    }

    return $template;

}

add_filter( 'ots_template_include', 'ots\include_template', 1 );


/**
 * Print dynamic styles in the page header.
 *
 * @since 4.0.0
 */
function print_dynamic_styles() { ?>

    <style id="ots-dynamic-styles">

        #sc_our_team a,
        .sc_team_single_member .articles a {
            color: <?php esc_html_e( get_option( Options::MAIN_COLOR ) ) ?>;
        }

        .grid#sc_our_team .sc_team_member .sc_team_member_name,
        .grid#sc_our_team .sc_team_member .sc_team_member_jobtitle,
        .grid_circles#sc_our_team .sc_team_member .sc_team_member_jobtitle,
        .grid_circles#sc_our_team .sc_team_member .sc_team_member_name,
        #sc_our_team .sc_team_member .icons span {
            background: <?php esc_html_e( get_option( Options::MAIN_COLOR ) ) ?>;
        }

        #sc_our_team .sc_team_member {
            padding: <?php esc_html_e( get_option( Options::MARGIN ) ); ?>px;
        }

    </style>

<?php }

add_action( 'wp_print_styles', 'ots\print_dynamic_styles' );
