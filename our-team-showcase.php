<?php
/*
 * Plugin Name: Our Team Showcase
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: A brief description of the Plugin.
 * Version: 4.0.0
 * Author: Smartcat
 * Author URI: https://smartcatdesign.net
 * License: GPL2
*/

namespace ots;

/**
 * Include constants and Options definitions
 */
include_once dirname( __FILE__ ) . '/constants.php';
include_once dirname( __FILE__ ) . '/upgrade.php';
include_once dirname( __FILE__ ) . '/includes/functions.php';
include_once dirname( __FILE__ ) . '/includes/helpers.php';
include_once dirname( __FILE__ ) . '/includes/custom-post-type.php';
include_once dirname( __FILE__ ) . '/includes/admin-settings.php';
include_once dirname( __FILE__ ) . '/includes/reorder-members.php';
include_once dirname( __FILE__ ) . '/includes/shortcode.php';


/**
 * Load the plugin'js text domain.
 *
 * @since 4.0.0
 */
function load_text_domain() {

    load_plugin_textdomain( 'ots', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}

add_action( 'plugins_loaded', 'ots\load_text_domain' );


function activate() {

    register_team_member_post_type();
    register_team_member_position_taxonomy();

    flush_rewrite_rules();

}

register_activation_hook( __FILE__, 'ots\activate' );


function deactivate() {

    unregister_setting( 'ots-team-view', Options::TEMPLATE );
    unregister_setting( 'ots-team-view', Options::REWRITE_SLUG );
    unregister_setting( 'ots-team-view', Options::GRID_COLUMNS );
    unregister_setting( 'ots-team-view', Options::MARGIN );
    unregister_setting( 'ots-team-view', Options::SHOW_SOCIAL );
    unregister_setting( 'ots-team-view', Options::SOCIAL_LINK_ACTION );
    unregister_setting( 'ots-team-view', Options::DISPLAY_NAME );
    unregister_setting( 'ots-team-view', Options::DISPLAY_TITLE );
    unregister_setting( 'ots-team-view', Options::DISPLAY_LIMIT );
    unregister_setting( 'ots-team-view', Options::MAIN_COLOR );
    unregister_setting( 'ots-single-member-view', Options::SINGLE_TEMPLATE );


    unregister_post_type( 'team_member' );
    unregister_taxonomy( 'team_member_position' );

}

register_deactivation_hook( __FILE__, 'ots\deactivate' );


function asset( $path = '' ) {
    return trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/' . ltrim( $path, '/' );
}

function template_path( $template ) {

    $file = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'templates/' . ltrim( $template, '/' );

    if( file_exists( $file ) ) {
        return $file;
    }

    return false;

}
