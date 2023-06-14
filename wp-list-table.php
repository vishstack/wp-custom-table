<?php
/**
 * Plugin Name: WP List Table (Custom Table)
 * Plugin URI: https://www.wpwebelite.com/
 * Description: This plugin handles Add,Edit and Delete functinalities with <strong>Custom Table</strong> and Listing is managed via Wp_List_Table.
 * Version: 1.0.0
 * Author: WPWeb
 * Author URI: https://www.wpwebelite.com/
 * Text Domain: wwwpltable
 * Domain Path: languages
 * 
 * @package WP List Table (Custom Table)
 * @category Core
 * @author WPWeb
 */

/** 
 * Basic plugin definitions 
 * 
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $wpdb;

/**
 * Basic Plugin Definitions 
 * 
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */
if( !defined( 'WWWP_LTABLE_VERSION' ) ) {
	define( 'WWWP_LTABLE_VERSION', '1.0.0' ); //version of plugin
}
if( !defined( 'WWWP_LTABLE_DIR' ) ) {
	define( 'WWWP_LTABLE_DIR', dirname( __FILE__ ) ); // plugin dir
}
if( !defined( 'WWWP_LTABLE_ADMIN' ) ) {
	define( 'WWWP_LTABLE_ADMIN', WWWP_LTABLE_DIR . '/includes/admin' ); // plugin admin url
}
if( !defined( 'WWWP_LTABLE_TEXT_DOMAIN' )) {
	define( 'WWWP_LTABLE_TEXT_DOMAIN', 'wwwpltable' ); // text domain for languages
}
if( !defined( 'WWWP_LTABLE_PLUGIN_URL' ) ) {
	define( 'WWWP_LTABLE_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'wwwpltablelevel' )) {
	define( 'wwwpltablelevel' , 'manage_options' ); // this is capability in plugin
}
if( !defined( 'WWWP_LTABLE_TABLE' )) {
	define( 'WWWP_LTABLE_TABLE', $wpdb->prefix.'disc_coupons' ); 
}
if( !defined( 'WWWP_LTABLE_PLUGIN_BASENAME' ) ) {
	define( 'WWWP_LTABLE_PLUGIN_BASENAME', basename( WWWP_LTABLE_DIR ) ); //Plugin base name
}
/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */
function wwwp_ltable_load_textdomain() {
	
 	// Set filter for plugin's languages directory
	$wwwp_ltable_lang_dir	= dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$wwwp_ltable_lang_dir	= apply_filters( 'wwwp_ltable_languages_directory', $wwwp_ltable_lang_dir );
	
	// Traditional WordPress plugin locale filter
	$locale	= apply_filters( 'plugin_locale',  get_locale(), 'wwwpltable' );
	$mofile	= sprintf( '%1$s-%2$s.mo', 'wwwpltable', $locale );
	
	// Setup paths to current locale file
	$mofile_local	= $wwwp_ltable_lang_dir . $mofile;
	$mofile_global	= WP_LANG_DIR . '/' . WWWP_LTABLE_PLUGIN_BASENAME . '/' . $mofile;
	
	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/wp-list-table(custom-table) folder
		load_textdomain( 'wwwpltable', $mofile_global );
	} elseif ( file_exists( $mofile_local ) ) { // Look in local /wp-content/plugins/wp-list-table(custom-table)/languages/ folder
		load_textdomain( 'wwwpltable', $mofile_local );
	} else { // Load the default language files
		load_plugin_textdomain( 'wwwpltable', false, $wwwp_ltable_lang_dir );
	}
  
}
/**
 * Activation hook
 * 
 * Register plugin activation hook.
 * 
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wwwp_ltable_install' );

/**
 * Deactivation hook
 *
 * Register plugin deactivation hook.
 * 
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wwwp_ltable_uninstall' );

/**
 * Plugin Setup Activation hook call back 
 *
 * Initial setup of the plugin setting default options 
 * and database tables creations.
 * 
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */
function wwwp_ltable_install() {
	
	global $wpdb;
	
	// Required file for DB Delta query
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	
	//Creating Coupon's Table
	$sql = "CREATE TABLE ".WWWP_LTABLE_TABLE." (
					  `disc_id` int(11) NOT NULL AUTO_INCREMENT,
					  `disc_title` varchar(120) NOT NULL,
					  `disc_content` varchar(255) NOT NULL,
					  `disc_cat` varchar(120) NOT NULL,
					  `disc_available` varchar(255) NOT NULL,
					  `disc_featured_coupon` varchar(255) NOT NULL,
					  `disc_insert_date` datetime NOT NULL,
					  `disc_update_date` datetime NOT NULL,
					  PRIMARY KEY (`disc_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
	
	dbDelta( $sql );
		
}

/**
 * Plugin Setup (On Deactivation)
 *
 * Does the drop tables in the database and
 * delete  plugin options.
 *
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */
function wwwp_ltable_uninstall() {
	global $wpdb;
			
}
/**
 * Load Plugin
 * 
 * Handles to load plugin after
 * dependent plugin is loaded
 * successfully
 * 
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */
function wwwp_ltable_plugin_loaded() {
 
	// load first plugin text domain
	wwwp_ltable_load_textdomain();
}

//add action to load plugin
add_action( 'plugins_loaded', 'wwwp_ltable_plugin_loaded' );

/**
 * Initialize all global variables
 * 
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */
global $wwwp_ltable_model, $wwwp_ltable_scripts, $wwwp_ltable_public, $wwwp_ltable_admin;

/**
 * Includes
 *
 * Includes all the needed files for our plugin
 *
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */
//includes model class file
require_once ( WWWP_LTABLE_DIR .'/includes/class-wwwp-ltable-model.php');
$wwwp_ltable_model = new Wwwp_Ltable_Model();

//includes scripts class file
require_once ( WWWP_LTABLE_DIR .'/includes/class-wwwp-ltable-scripts.php');
$wwwp_ltable_scripts = new Wwwp_Ltable_Scripts();
$wwwp_ltable_scripts->add_hooks();

//includes public pages class file
require_once ( WWWP_LTABLE_DIR .'/includes/class-wwwp-ltable-public-pages.php' );
$wwwp_ltable_public = new Wwwp_Ltable_Public_Pages();
$wwwp_ltable_public->add_hooks();

//includes admin class file
require_once ( WWWP_LTABLE_ADMIN .'/class-wwwp-ltable-admin.php');
$wwwp_ltable_admin = new Wwwp_Ltable_Admin_Pages();
$wwwp_ltable_admin->add_hooks();
