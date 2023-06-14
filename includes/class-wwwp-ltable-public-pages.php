<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;	

/**
 * Plugin Public class for front end
 *
 * Handles public functionalities
 *
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */

class Wwwp_Ltable_Public_Pages{
	
	public $model;
	
	public function __construct(){
		
		global $wwwp_ltable_model;
		$this->model = $wwwp_ltable_model;
		
	}
	
	/**
	 * Adding hooks
	 *
	 * Loads the Javascript,CSS files and handles some
	 * AJAX call and some wordpress action
	 * 
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */	
	public function add_hooks() {
		
	}

}
