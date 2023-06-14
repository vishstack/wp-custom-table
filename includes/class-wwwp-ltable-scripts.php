<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */
class Wwwp_Ltable_Scripts {
	
	public function __construct() {
		
		
	}
	
	/**
	 * Enqueue Scripts
	 * 
	 * Loads Javascript file for managing datepicker 
	 * and other functionality in backend
	 *
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function wwwp_ltable_admin_scripts( $hook_suffix ) {
		
		$pages_hook_suffix = array( 'wp-list-table-custom-table_page_wwwp_ltable_add_coupon' );
		
		//Check pages when you needed
		if( in_array( $hook_suffix, $pages_hook_suffix ) ) {}
	}
	
	
	/**
	 * Enqueue Scripts
	 * 
	 * Loads Javascript for managing 
	 * metaboxes in plugin settings page
	 * 
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function wwwp_ltable_admin_meta_scripts($hook_suffix) {
		
		// loads the required scripts for the meta boxes
		if ( $hook_suffix == 'wp-list-table-custom-table_page_wwwp_ltable_add_coupon' ) { //check hook suffix of page
			
			wp_enqueue_script( 'common' );

			wp_enqueue_script( 'wp-lists' );

			wp_enqueue_script( 'postbox' );
			
		}
		
	}
	
	/**
	 * Enqueue Styles
	 * 
	 * Loads CSS file for managing datepicker 
	 * and other functionality in backend
	 *
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function wwwp_ltable_admin_styles( $hook_suffix ) {
		
		$pages_hook_suffix = array( 'wp-list-table-custom-table_page_wwwp_ltable_add_coupon' );
		
		//Check pages when you needed
		if( in_array( $hook_suffix, $pages_hook_suffix ) ) {
			
			wp_enqueue_style('thickbox');
		
			// Register & Enqueue admin style
			wp_register_style('wwwp-ltable-admin-style',  WWWP_LTABLE_PLUGIN_URL.'includes/css/wwwp-ltable-admin.css', array(), WWWP_LTABLE_VERSION );
			wp_enqueue_style('wwwp-ltable-admin-style');
		}
	}
	
	/**
	 * Adding Hooks
	 *
	 * Adding hooks for the styles and scripts.
	 *
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		// add style for add coupop
		add_action( 'admin_enqueue_scripts', array( $this, 'wwwp_ltable_admin_styles' ) );
		
		// add js for add coupop
		add_action( 'admin_enqueue_scripts', array( $this, 'wwwp_ltable_admin_scripts' ) );
		
		add_action( 'admin_enqueue_scripts', array( $this, 'wwwp_ltable_admin_meta_scripts' ) );
		
	}
}
