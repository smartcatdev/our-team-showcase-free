<?php

namespace ots;


function do_migration() {

    $options = get_option( 'smartcat_team_options', false );

    if ( $options ) {

        // Map out all non-boolean values
        $map = array(
            'template'        => Options::TEMPLATE,
            'margin'          => Options::MARGIN,
            'single_template' => Options::SINGLE_TEMPLATE,
            'slug'            => Options::REWRITE_SLUG
        );

        foreach( $map as $old => $new ) {
            update_option( $new, $options[ $old ] );
        }


        // Map out all boolean values
        $checkboxes = array(
            'social'          => Options::SHOW_SOCIAL,
            'name'            => Options::DISPLAY_NAME,
            'title'           => Options::DISPLAY_TITLE,
        );

        foreach ( $checkboxes as $old => $new ) {
            update_option( $new, $options[ $old ] == 'yes' ? 'on' : '' );
        }

        // Add hash to main color
        update_option( Options::MAIN_COLOR, '#' . $options['text_color'] );

        // If the value was -1 set it to 'on' to display all
        update_option( Options::GRID_COLUMNS, $options['columns'] < 0 ? 'on' : $options['columns'] );
        update_option( Options::DISPLAY_LIMIT, $options['member_count'] < 0 ? 'on' : $options['member_count'] );

        // If show social icons was set to open a new tab, change it to a boolean
        update_option( Options::SOCIAL_LINK_ACTION, $options['social_link'] === 'new' ? 'on' : '' );

        // Delete the old options array
        delete_option( 'smartcat_team_options' );

    }

}

add_action( 'init', 'ots\do_migration' );
