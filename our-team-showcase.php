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


/**
 * Load the plugin'js text domain.
 *
 * @since 4.0.0
 */
function load_text_domain() {

    load_plugin_textdomain( 'ots', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}

add_action( 'plugins_loaded', 'ots\load_text_domain' );


/**
 * Includes required files and initializes the plugin.
 *
 * @since 4.0.0
 */
function init() {

    include_once dirname( __FILE__ ) . '/upgrade.php';
    include_once dirname( __FILE__ ) . '/includes/functions.php';
    include_once dirname( __FILE__ ) . '/includes/helpers.php';
    include_once dirname( __FILE__ ) . '/includes/custom-post-type.php';
    include_once dirname( __FILE__ ) . '/includes/admin-settings.php';
    include_once dirname( __FILE__ ) . '/includes/reorder-members.php';
    include_once dirname( __FILE__ ) . '/includes/shortcode.php';
    include_once dirname( __FILE__ ) . '/includes/TeamWidget.php';
    include_once dirname( __FILE__ ) . '/includes/widgets.php';

    do_action( 'ots_loaded' );

}

add_action( 'plugins_loaded', 'ots\init' );


/**
 * Runs on plugin activation.
 *
 * @since 4.0.0
 */
function activate() {

    init();

    register_team_member_post_type();
    register_team_member_position_taxonomy();

    flush_rewrite_rules();

}

register_activation_hook( __FILE__, 'ots\activate' );


/**
 * Runs on plugin deactivation.
 *
 * @since 4.0.0
 */
function deactivate() {

    init();

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


/**
 * Get the URL of an asset from the assets folder.
 *
 * @param string $path
 * @return string
 * @since 4.0.0
 */
function asset( $path = '' ) {
    return trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/' . ltrim( $path, '/' );
}


/**
 * Get the path of a template file.
 *
 * @param  string      $template The file name in the format of file.php.
 * @return bool|string           False if the file does not exist, the path if it does.
 */
function template_path( $template ) {

    $file = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'templates/' . ltrim( $template, '/' );

    if( file_exists( $file ) ) {
        return $file;
    }

    return false;

}
