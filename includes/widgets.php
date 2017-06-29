<?php

namespace ots;

function enqueue_widget_scripts() {

    wp_enqueue_style( 'ots-widget-css', asset( 'css/widget.css' ), null, VERSION );

}

add_action( 'wp_enqueue_scripts', 'ots\enqueue_widget_scripts' );


function register_widgets() {

    register_widget( TeamWidget::class );

}

add_action( 'widgets_init', 'ots\register_widgets' );