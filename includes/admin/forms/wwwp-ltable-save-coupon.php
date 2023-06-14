<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Save Coupons
 *
 * Handle Coupon save and edit coupons
 * 
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */
	global $wwwp_ltable_model, $errmsg, $wpdb, $error;
	
	$model = $wwwp_ltable_model;
	
	$disc_available	= '';
	
	// save for coupon data
	if(isset($_POST['wwwp_ltable_coupon_save']) && !empty($_POST['wwwp_ltable_coupon_save'])) { //check button click

		$error = '';
		if(isset($_POST['disc_title']) && empty($_POST['disc_title'])) { //check coupon title
			
			$errmsg['disc_title'] = esc_html__('Please enter Coupon title.', 'wwwpltable');
			$error = true;
		}
		if(isset($_POST['disc_content']) && empty($_POST['disc_content'])) { //check coupon content
			
			$errmsg['disc_content'] = esc_html__('Please enter Coupon description.', 'wwwpltable');
			$error = true;
		}
		if(isset($_POST['disc_cat']) && empty($_POST['disc_cat'])) { //check coupon category
			
			$errmsg['disc_cat'] = esc_html__('Please select Coupon category.', 'wwwpltable');
			$error = true;
		}
		if(isset($_POST['disc_available']) && !empty($_POST['disc_available'])) { //check coupon availibility
			
			$disc_available = implode(',', $_POST['disc_available']);
			
		}
		
		if(isset($_GET['disc_id']) && !empty($_GET['disc_id']) && $error != true) { //check no error and coupon id is set
			
			$update_data = array(
											'disc_title'			=> isset($_POST['disc_title']) ? $_POST['disc_title'] : '',
											'disc_content'			=> isset($_POST['disc_content']) ? $_POST['disc_content'] : '',
											'disc_cat'				=> isset($_POST['disc_cat']) ? $_POST['disc_cat'] : '',
											'disc_available'		=> $disc_available,
											'disc_featured_coupon'	=> isset($_POST['disc_featured_coupon']) ? $_POST['disc_featured_coupon'] : '',
											'disc_update_date'		=> date("Y-m-d H:i:s")
								 );
			$where_args =  array( 'disc_id' => $_GET['disc_id'] );
			
			//update the data
			$wpdb->update(WWWP_LTABLE_TABLE,$model->wwwp_ltable_escape_slashes_deep($update_data),$where_args);
			
			// get redirect url
			$redirect_url = add_query_arg( array( 'page' => 'wwwp_ltable_coupon_list', 'message' => '2' ), admin_url( 'admin.php' ) );
			
			//redirect after updating data
			wp_redirect( $redirect_url );
			exit;
			
		} else {
		
			if($error != true) { //check error
			
				$args = array(
									'disc_title'			=> isset($_POST['disc_title']) ? $_POST['disc_title'] : '',
									'disc_content'			=> isset($_POST['disc_content']) ? $_POST['disc_content'] : '',
									'disc_cat'				=> isset($_POST['disc_cat']) ? $_POST['disc_cat'] : '',
									'disc_available'		=> $disc_available,
									'disc_featured_coupon'	=> isset($_POST['disc_featured_coupon']) ? $_POST['disc_featured_coupon'] : '',
									'disc_insert_date'		=> date("Y-m-d H:i:s"),
									'disc_update_date'		=> date("Y-m-d H:i:s")
								);
				
				//insert data
				$wpdb->insert(WWWP_LTABLE_TABLE,$model->wwwp_ltable_escape_slashes_deep($args));
				
				//get last inserted id
				$result = $wpdb->insert_id;
				
				//if record inserted successfully then redirect user
				if($result) { //check inserted coupon id
					
					// get redirect url
					$redirect_url = add_query_arg( array( 'page' => 'wwwp_ltable_coupon_list', 'message' => '1' ), admin_url( 'admin.php' ) );
					
					wp_redirect( $redirect_url );
					exit;
					
				}
			}
		}		
	}