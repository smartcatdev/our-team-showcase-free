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

namespace our_team_showcase;

/**
 * Include constants and Options definitions
 */
include_once dirname( __FILE__ ) . 'constants.php';


/**
 * Load the plugin's text domain.
 *
 * @since 4.0.0
 */
function load_text_domain() {

    load_plugin_textdomain( 'ots', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}

add_action( 'plugins_loaded', 'our_team_showcase\load_text_domain' );
