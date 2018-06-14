<?php
/*
 * Plugin Name: Our Team Showcase
 * Plugin URI: https://smartcatdesign.net/downloads/our-team-showcase/
 * Description: Display your team members in a very attractive way as a widget or page with a shortcode [our-team] or a widget
 * Version: 4.4.1
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
    include_once dirname( __FILE__ ) . '/includes/team-member.php';
    include_once dirname( __FILE__ ) . '/includes/tools.php';
    include_once dirname( __FILE__ ) . '/includes/admin-settings.php';
    include_once dirname( __FILE__ ) . '/includes/documentation.php';
    include_once dirname( __FILE__ ) . '/includes/add-ons.php';
    include_once dirname( __FILE__ ) . '/includes/reorder-members.php';
    include_once dirname( __FILE__ ) . '/includes/team-view.php';
    include_once dirname( __FILE__ ) . '/includes/TeamMainWidget.php';
    include_once dirname( __FILE__ ) . '/includes/TeamSidebarWidget.php';
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


function register_scripts() {

	// We need this on all pages
	wp_enqueue_style( 'ots-common',     asset( 'css/common.css' ),    null, VERSION );

	wp_register_style( 'ots-team-view', asset( 'css/team-view.css' ), null, VERSION );
	wp_register_style( 'ots-widget',    asset( 'css/widgets.css' ),   null, VERSION );
	wp_register_style( 'ots-single',    asset( 'css/single.css'  ),   null, VERSION );

	wp_register_script( 'ots', asset( 'js/script.js' ), array( 'jquery' ), VERSION );

}

add_action( 'init', 'ots\register_scripts' );


function enqueue_customizer_scripts() {


	wp_enqueue_script( 'ots-customizer', asset( 'admin/js/customizer.js' ), array( 'jquery', 'customize-controls' ), false );

}

add_action( 'customize_controls_enqueue_scripts', 'ots\enqueue_customizer_scripts' );


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
function asset( $path = '', $url = true ) {

    if( $url ) {
        $file = trailingslashit( plugin_dir_url( __FILE__ ) );
    } else {
        $file =  trailingslashit( plugin_dir_path( __FILE__ ) );
    }

    return $file . 'assets/' . ltrim( $path, '/' );

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

    $base = trailingslashit( dirname( __FILE__ ) . '/templates' );

    $file = $base . $template . '.php';
    
    // Check if override exists in the theme first
    if( file_exists( get_theme_override( $template . '.php' ) ) ) {
        return get_theme_override( $template . '.php' );
    }

    // then check if selected template exists in the plugin
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

    $upgrade  = array(
        'upgrade'  => '<a target="_blank" href="https://smartcatdesign.net/downloads/our-team-showcase/">' . __( 'Go Pro', 'ots' ) . '</a>'
    );

    $settings = array(
        'settings' => '<a href="' . admin_url( 'edit.php?post_type=team_member&page=ots-settings' ) . '">' . __( 'Settings', 'ots' ) . '</a>'
    );

    $links = array_merge( $settings, $links );

    if( apply_filters( 'ots_enable_pro_preview', true ) ) {
        $links = array_merge( $upgrade, $links );
    }

    return $links;

}

add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ots\plugin_action_links' );
