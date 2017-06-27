<?php

namespace ots;


function do_migration() {

    $options = get_option( 'smartcat_team_options', false );

    if ( $options ) {

        // Map out all non-boolean values
        $map = array(
            'template'        => Options::TEMPLATE,
            'member_count'    => Options::DISPLAY_LIMIT,
            'text_color'      => Options::MAIN_COLOR,
            'margin'          => Options::MARGIN,
            'social_link'     => Options::SOCIAL_LINK_ACTION,
            'single_template' => Options::S_TEMPLATE,
            'slug'            => Options::REWRITE_SLUG
        );

        foreach( $map as $old => $new ) {
            update_option( $new, $options[ $old ] );
        }


        // Map out all boolean values
        $checkboxes = array(
            'social'          => Options::SHOW_SOCIAL,
            'single_social'   => Options::S_SHOW_SOCIAL,
            'name'            => Options::DISPLAY_NAME,
            'title'           => Options::DISPLAY_TITLE,
        );

        foreach ( $checkboxes as $old => $new ) {
            update_option( $new, $options[ $old ] == 'yes' ? 'on' : '' );
        }


        // If the value was -1 set it to 'on' to display all
        update_option( Options::GRID_COLUMNS, $options['columns'] < 0 ? 'on' : $options['columns'] );


        // Delete the old options array
        delete_option( 'smartcat_team_options' );

    }

}

add_action( 'init', 'ots\do_migration' );
