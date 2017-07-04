<?php

namespace ots;

/**
 * Upgrades and migrates the plugin's settings.
 *
 * @since 4.0.0
 */
function do_migration() {

    $options = get_option( 'smartcat_team_options', false );

    if ( get_option( Options::PLUGIN_VERSION, 0 ) < VERSION ) {

        // Map out all non-boolean values
        $map = array(
            'margin'          => Options::MARGIN,
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

        switch( $options['template'] ) {

            case 'grid':
                update_option( Options::TEMPLATE, 'grid' );
                break;

            case 'grid_circles':
                update_option( Options::TEMPLATE, 'grid-circles' );
                break;

            case 'grid_circles2':
                update_option( Options::TEMPLATE, 'grid-circles-2' );
                break;

        }

        switch( $options['single_template'] ) {

            case 'standard':
                update_option( Options::SINGLE_TEMPLATE, 'default' );
                break;

            case 'disable':
                update_option( Options::SINGLE_TEMPLATE, 'disabled' );
                break;

        }

        // Add hash to main color
        update_option( Options::MAIN_COLOR, '#' . $options['text_color'] );

        // If the value was -1 set it to 'on' to display all
        update_option( Options::GRID_COLUMNS, $options['columns'] < 0 ? 'on' : $options['columns'] );
        update_option( Options::DISPLAY_LIMIT, $options['member_count'] < 0 ? 'on' : $options['member_count'] );

        // If show social icons was set to open a new tab, change it to a boolean
        update_option( Options::SOCIAL_LINK_ACTION, $options['social_link'] === 'new' ? 'on' : '' );

        // Update the plugin version
        update_option( Options::PLUGIN_VERSION, VERSION );

    }

}

add_action( 'admin_init', 'ots\do_migration' );
