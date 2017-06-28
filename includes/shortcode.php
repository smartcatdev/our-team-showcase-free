<?php

namespace ots;


function enqueue_scripts() {
    //TODO only enqueue on shortcode page
    wp_enqueue_style( 'ots-css', asset( 'css/style.css' ), null, VERSION );
    wp_enqueue_script( 'ots-js', asset( 'js/script.js' ), array( 'jquery' ), VERSION );

}

add_action( 'wp_enqueue_scripts', 'ots\enqueue_scripts' );


function do_shortcode_output( $attributes = array() ) {

    $defaults = array(
        'group'           => '',
        'template'        => get_option( Options::TEMPLATE ),
        'single_template' => get_option( Options::S_TEMPLATE )
    );

    $args = shortcode_atts( $defaults, $attributes );
    $include = template_path( $args['template'] . '.php' );

    ob_start();
    extract( $args );

    // Dynamically pull in the template file
    include_once apply_filters( 'ots_template_include', $include, $attributes );

    return ob_get_clean();

}

add_shortcode( 'our-team', 'ots\do_shortcode_output' );
