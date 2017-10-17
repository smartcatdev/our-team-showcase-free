<?php

namespace ots;

/**
 * Upgrades and migrates the plugin's settings.
 *
 * @since 4.0.0
 */
function do_migration() {

    $options = get_option( 'smartcat_team_options', false );

    if ( $options && get_option( Options::PLUGIN_VERSION, 0 ) < '4.0.0' ) {

        // Map out all non-boolean values
        $map = array(
            'margin'          => Options::MARGIN,
            'slug'            => Options::REWRITE_SLUG,
            'template'        => Options::TEMPLATE,
            'single_template' => Options::SINGLE_TEMPLATE
        );

        foreach( $map as $old => $new ) {
            update_option( $new, $options[ $old ] );
        }


        // Map out all boolean values
        $checkboxes = array(
            'social'          => Options::SHOW_SOCIAL,
            'single_social'   => Options::SHOW_SINGLE_SOCIAL,
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
        update_option( Options::DISPLAY_LIMIT, $options['member_count'] < 0 ? 'all' : $options['member_count'] );

        // If show social icons was set to open a new tab, change it to a boolean
        update_option( Options::SOCIAL_LINK_ACTION, $options['social_link'] === 'new' ? 'on' : '' );

        // Update the plugin version
        update_option( Options::PLUGIN_VERSION, VERSION );

    }

}

add_action( 'admin_init', 'ots\do_migration' );
