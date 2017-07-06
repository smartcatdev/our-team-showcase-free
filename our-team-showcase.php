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
 * Load the plugin's text domain.
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
    include_once dirname( __FILE__ ) . '/includes/team_member-post-type.php';
    include_once dirname( __FILE__ ) . '/includes/admin-settings.php';
    include_once dirname( __FILE__ ) . '/includes/reorder-members.php';
    include_once dirname( __FILE__ ) . '/includes/shortcode.php';
    include_once dirname( __FILE__ ) . '/includes/TeamWidget.php';
    include_once dirname( __FILE__ ) . '/includes/TeamMember.php';
    include_once dirname( __FILE__ ) . '/includes/widgets.php';
    include_once dirname( __FILE__ ) . '/includes/extension-licensing.php';

    $license_page_args = array(
        'parent_slug' => 'edit.php?post_type=team_member',
        'page_title'  => __( 'Our Team Showcase Licenses', 'ots' ),
        'menu_title'  => __( 'Licenses', 'ots' ),
        'capability'  => 'manage_options',
        'menu_slug'   => 'ots-licenses'
    );

    $registration = new \SC_License_Manager( 'ots', 'submenu', $license_page_args );


    // All done
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

    $template = trim( $template, '/' );
    $template = rtrim( $template, '.php' );

    $base = trailingslashit( apply_filters( 'ots_template_path', dirname( __FILE__ ) . '/templates', $template ) );

    $file = $base . $template . '.php';

    if( file_exists( $file ) ) {
        return $file;
    }

    return false;

}

/**
 * Add action links to plugins page.
 *
 * @param $links
 * @return array
 * @since 4.0.0
 */
function plugin_action_links( $links ) {

    $upgrade  = array( 'upgrade'  => '<a href="#">' . __( 'Go Pro', 'ots' ) . '</a>' );
    $settings = array( 'settings' => '<a href="' . admin_url( 'edit.php?post_type=team_member&page=ots-settings' ) . '">' . __( 'Settings', 'ots' ) . '</a>' );

    $links = array_merge( $settings, $links );

    if( apply_filters( 'ots_enable_pro_preview', true ) ) {
        $links = array_merge( $upgrade, $links );
    }

    return $links;

}

add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ots\plugin_action_links' );
