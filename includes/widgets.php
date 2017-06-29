<?php

namespace ots;


function register_widgets() {

    register_widget( TeamWidget::class );

}

add_action( 'widgets_init', 'ots\register_widgets' );