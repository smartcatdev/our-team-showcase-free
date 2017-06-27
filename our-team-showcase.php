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
 * Pull in all of the plugin'js include files
 *
 * @since 4.0.0
 */
function include_plugin_files() {

    include_once dirname(__FILE__) . '/includes/helpers.php';
    include_once dirname(__FILE__) . '/includes/admin-settings.php';
    include_once dirname(__FILE__) . '/includes/custom-post-type.php';
    include_once dirname(__FILE__) . '/upgrade.php';

}

add_action( 'plugins_loaded', 'ots\include_plugin_files' );


function asset( $path = '' ) {
    return trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/' . ltrim( $path, '/' );
}