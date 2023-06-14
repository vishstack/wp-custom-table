<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Plugin Model Class
 *
 * Handles generic functionailties
 *
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */
 class Wwwp_Ltable_Model {
 	 	
 	//class constructor
	public function __construct()	{		

	}
	
	/**
	 * Discount Coupon
	 *
	 * Get all the coupons data from the database table
	 *
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function wwwp_ltable_get_coupons( $args=array() ) {
	
		global $wpdb;

		$sql = "SELECT * FROM ".WWWP_LTABLE_TABLE." WHERE 1=1";
		
		if(isset($args['search_title']) && !empty($args['search_title'])) {
			$sql .= " AND disc_title like '%" . $args['search_title'] . "%'";
		}
		if(isset($args['orderby']) && !empty($args['orderby'])) {
			$sql .= " ORDER BY " . $args['orderby'];
		}
		if(isset($args['order']) && !empty($args['order'])) {
			$sql .= " " . $args['order'];
		}
		if(isset($args['offset'])) {
			$sql .= " LIMIT " . $args['offset'];
		}
		if(isset($args['posts_per_page']) && !empty($args['posts_per_page'])) {
			$sql .= " , " . $args['posts_per_page'];
		}

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		$data_res['data']	= $result;

		//Get Total count of Items
		$data_res['total'] = $wpdb->get_var( "SELECT COUNT(*) FROM ".WWWP_LTABLE_TABLE." WHERE 1=1" );
		
		return $data_res;
	}
	
	
	/**
	 * Discount Coupon
	 *
	 * Get coupon data by id from the database table
	 *
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function wwwp_ltable_get_coupon_by_id( $args=array() ) {
	
		global $wpdb;
		
		$sql = "SELECT * FROM ".WWWP_LTABLE_TABLE." WHERE 1=1";
		
		if(isset($args['disc_id']) && !empty($args['disc_id'])) {
			$sql .= " AND disc_id = " . $args['disc_id'];
		}
		$result = $wpdb->get_row( $sql, 'ARRAY_A' );
		return $result;
	}
		
	/**
	 * Escape Tags & Slashes
	 *
	 * Handles escapping the slashes and tags
	 *
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function wwwp_ltable_escape_attr($data){
		return esc_attr(stripslashes($data));
	}
	
	/**
	 * Strip Slashes From Array
	 *
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function wwwp_ltable_escape_slashes_deep($data = array(), $flag=false, $limited = false){
		
		if( $flag != true ) {
			
			$data = $this->wwwp_ltable_nohtml_kses($data);
			
		} else {
			
			if( $limited == true ) {
				$data = wp_kses_post( $data );
			}
			
		}
		$data = stripslashes_deep($data);
		return $data;
	}
	
	
	/**
	 * Strip Html Tags 
	 * 
	 * It will sanitize text input (strip html tags, and escape characters)
	 * 
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function wwwp_ltable_nohtml_kses($data = array()) {
		
		
		if ( is_array($data) ) {
			
			$data = array_map(array($this,'wwwp_ltable_nohtml_kses'), $data);
			
		} elseif ( is_string( $data ) ) {
			
			$data = wp_filter_nohtml_kses($data);
		}
		
		return $data;
	}	
	
	/**
	 * Bulk Deletion
	 *
	 * Does handle deleting coupons from the
	 * database table.
	 *
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function wwwp_ltable_bulk_delete( $args = array() ) { 
   
   		global $wpdb;
		
		if(isset($args['disc_id']) && !empty($args['disc_id'])) {
		
			$sql='DELETE FROM '.WWWP_LTABLE_TABLE.' WHERE disc_id = "'.$args['disc_id'].'"';
			$wpdb->query( $sql );
			
		}
	}
		
 }
