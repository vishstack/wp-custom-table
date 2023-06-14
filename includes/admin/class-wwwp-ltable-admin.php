<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Pages Class
 *
 * Handles generic Admin functionailties
 *
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */
class Wwwp_Ltable_Admin_Pages {

	public $model, $scripts;

	public function __construct()	{		

		global $wwwp_ltable_model, $wwwp_ltable_scripts;
		$this->model = $wwwp_ltable_model;
		$this->scripts = $wwwp_ltable_scripts;
	}

	/**
	 * Creat menu page
	 *
	 * Adding required menu pages and submenu pages
	 * to manage the plugin functionality
	 * 
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function wwwp_ltable_add_menu_page() {
		 
		add_menu_page( esc_html__( 'WP List Table (Custom Table)', 'wwwpltable' ), esc_html__( 'WP List Table (Custom Table)', 'wwwpltable' ), wwwpltablelevel,'wwwp_ltable_coupon_list', '' );
		
		$wwwp_ltable_coupon_list = add_submenu_page( 'wwwp_ltable_coupon_list', esc_html__( 'Coupons', 'wwwpltable' ), esc_html__( 'Coupons', 'wwwpltable' ), wwwpltablelevel,'wwwp_ltable_coupon_list', array($this, 'wwwp_ltable_coupon_list') );
							
		$wwwp_ltable_add_coupon = add_submenu_page( 'wwwp_ltable_coupon_list', esc_html__( 'Add Coupon', 'wwwpltable' ), esc_html__( 'Add Coupon', 'wwwpltable' ), wwwpltablelevel,'wwwp_ltable_add_coupon', array($this, 'wwwp_ltable_add_coupon') );	
	}

		
	/**
	 * Includes Save Coupon
	 *
	 * Including File for managing the save coupon functionality
	 * 
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function wwwp_ltable_admin_init() {
		
		include_once( WWWP_LTABLE_ADMIN . '/forms/wwwp-ltable-save-coupon.php');
		
	}

	/**
	 * Includes Coupon List
	 * 
	 * Including File for coupon listing
	 *
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function wwwp_ltable_coupon_list() {
		
		include_once( WWWP_LTABLE_ADMIN . '/forms/wwwp-ltable-coupon-list.php' );
		
	}

	/**
	 * Includes Add/Edit Coupon
	 * 
	 * Including File for Add / Edit functionality of coupons
	 *
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function wwwp_ltable_add_coupon() {
		
		include_once( WWWP_LTABLE_ADMIN . '/forms/wwwp-ltable-add-edit-coupon.php' );
		
	}
	
	/**
	 * Bulk Action
	 *
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function wwwp_ltable_process_bulk_action() {
		
		// check if action is not blank and if page is coupon listing page
		if(((isset( $_GET['action'] ) && $_GET['action'] == 'delete' ) || (isset( $_GET['action2'] ) && $_GET['action2'] == 'delete' )) && isset($_GET['page']) && $_GET['page'] == 'wwwp_ltable_coupon_list' ) { //check action and page
			
			// get redirect url
			$redirect_url = add_query_arg( array( 'page' => 'wwwp_ltable_coupon_list' ), admin_url( 'admin.php' ) );
			
			if(isset( $_GET['coupon'])) { 
				$action_on_id = $_GET['coupon'];
			} else {
				$action_on_id = array();
			}
			
			if( count( $action_on_id ) > 0 ) { //check if any checkbox is selected
					
					foreach ( $action_on_id as $disc_id ) {
						$args = array (
										'disc_id' => $disc_id
									);
						$this->model->wwwp_ltable_bulk_delete( $args );
					}
					
				$redirect_url = add_query_arg( array( 'message' => '3' ), $redirect_url );
				
				wp_redirect( $redirect_url ); 
				exit;
				
			} else {
				
				wp_redirect( $redirect_url ); 
				exit;
			}
				
		}
	}
	
		
	/**
	 * Adding Hooks
	 *
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function add_hooks() {
		
		add_action( 'admin_menu', array( $this, 'wwwp_ltable_add_menu_page' ) );
			
		add_action('admin_init', array($this, 'wwwp_ltable_admin_init'));
		
		add_action('admin_init', array($this, 'wwwp_ltable_process_bulk_action'));
	}

}