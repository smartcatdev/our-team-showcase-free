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
