<?php

namespace ots;

/**
 * Register the plugin's widgets.
 *
 * @since 4.0.0
 */
function register_widgets() {

    register_widget( TeamWidget::class );

}

add_action( 'widgets_init', 'ots\register_widgets' );


function enqueue_widget_scripts() {

    if( apply_filters( 'ots_load_default_widget_scripts', true ) ) {
        wp_enqueue_style( 'ots-widget-css', asset( 'css/widgets.css' ), null, VERSION );
    }

}

add_action( 'wp_enqueue_scripts', 'ots\enqueue_widget_scripts' );
