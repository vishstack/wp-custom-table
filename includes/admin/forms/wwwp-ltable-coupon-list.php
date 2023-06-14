<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Coupon List Page
 *
 * The html markup for the coupon list
 * 
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */

if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
	
class Wwwp_Ltable_Coupon_List extends WP_List_Table {

	public $model, $per_page;
	
	public function __construct(){
	
        global $wwwp_ltable_model, $page;
                
        //Set parent defaults
        parent::__construct( array(
							            'singular'  => 'coupon',     //singular name of the listed records
							            'plural'    => 'coupons',    //plural name of the listed records
							            'ajax'      => false        //does this table support ajax?
							        ) );   
							        
		$this->model = $wwwp_ltable_model;
		$this->per_page	= apply_filters( 'wwwp_ltable_posts_per_page', 2 ); // Per page
		
    }
    
    /**
	 * Displaying Coupons
	 *
	 * Does prepare the data for displaying the coupons in the table.
	 *
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */	
	public function display_coupons() {

		// Taking parameter
		$orderby 	= isset( $_GET['orderby'] )	? urldecode( $_GET['orderby'] )		: 'disc_id';
		$order		= isset( $_GET['order'] )	? $_GET['order']                	: 'DESC';
		$search 	= isset( $_GET['s'] ) ? sanitize_text_field( trim($_GET['s']) ) : null;
		
		$args = array(
						'posts_per_page'	=> $this->per_page,
						'page'				=> isset( $_GET['paged'] ) ? $_GET['paged'] : null,
						'orderby'			=> $orderby,
						'order'				=> $order,
						'offset'  			=> ( $this->get_pagenum() - 1 ) * $this->per_page
					);

		// If search is not there then make search param
		$args['search_title'] = $search;

		//call function to retrive data from table
		$data = $this->model->wwwp_ltable_get_coupons( $args );
		
		return $data;
	}
	
	/**
	 * Mange column data
	 *
	 * Default Column for listing table
	 * Does to add the column to the listing page
	 * column name must be same as in function {get_columns} 
	 * 
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function column_default( $item, $column_name ){
	
        switch( $column_name ){
            case 'disc_title':
            case 'disc_content':
            case 'disc_cat':
            case 'disc_available':
				return ($item[ $column_name ]);
            case 'disc_update_date':
				return date_i18n( get_option('date_format'). ' '. get_option('time_format') ,strtotime($item[ $column_name ])); // getting date and time format from general settings
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }
	
    /*	this function name must be column_{database table filed name} where we display edit - delete link in listing
     *	for ex. field name 'disc_title' that means function name is 'column_disc_title'
     */
    /**
     * Manage Edit/Delete Link
     * 
     * Does to show the edit and delete link below the column cell
     * function name should be column_{database field name}
     * For ex. I want to put Edit/Delete link below the post title 
     * so i made its name is column_post_title
     * 
     * @package WP List Table (Custom Table)
 	 * @since 1.0.0
     */
    public function column_disc_title($item){
    
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&disc_id=%s">'.esc_html__('Edit', 'wwwpltable').'</a>','wwwp_ltable_add_coupon','edit',$item['disc_id']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&coupon[]=%s">'.esc_html__('Delete', 'wwwpltable').'</a>',$_REQUEST['page'],'delete',$item['disc_id']),
        );
        
        //Return the title contents	        
        return sprintf('%1$s %2$s',
            /*$1%s*/ $item['disc_title'],
            /*$2%s*/ $this->row_actions($actions)
        );
    }
    
    /**
     * Add Check boxes in Listing Table
     * 
     * Does to adding checkboxes for bulk action into the listing page table
     * 
     * Note: Dont change name cb, else checkall functionality wont work and design get distrubed.
     * 
     * @package WP List Table (Custom Table)
     * @since 1.0.0
     */
    public function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['disc_id']                //The value of the checkbox should be the record's id
        );
    }
    
    /**
     * Display Columns
     *
     * Handles to show the minimum columns into the table 
     * 
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
     */
	public function get_columns(){
	
        $columns = array(
	        					'cb'      				=> '<input type="checkbox" />', //Render a checkbox instead of text
					            'disc_title'			=> esc_html__( 'Title', 'wwwpltable' ),
					            'disc_content'			=> esc_html__( 'Description', 'wwwpltable' ),
					            'disc_cat'				=> esc_html__( 'Category', 'wwwpltable' ),
					            'disc_available'		=> esc_html__( 'Availibility', 'wwwpltable' ),
					            'disc_update_date'		=> esc_html__( 'Last Modified', 'wwwpltable' )
					        );
        return $columns;
    }
	
    /**
     * Sortable Columns
     *
     * Handles sortable column in list table 
     * it will automatically manage ascending and descending functionality of table
     * 
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
     */
	public function get_sortable_columns() {
	
        $sortable_columns = array(
							            'disc_title'		=> array( 'disc_title', true ),    
							            'disc_cat'			=> array( 'disc_cat', true ),
							            'disc_update_date'	=> array( 'disc_update_date', true )
							        );
        return $sortable_columns;
    }
	/**
	 * No items
	 * 
	 * Handles the message when no records available in table
	 * 
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
	 */
	public function no_items() {
		esc_html_e( 'No coupons found.', 'wwwpltable' );
	}
	
	/**
     * Bulk actions field
     *
     * Handles Bulk Action combo box values
     * 
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
     */
	public function get_bulk_actions() {
        $actions = array(
					            'delete'    => 'Delete'
					        );
        return $actions;
    }
    
    /**
     * Process Bulk actions
     *
     * Handles Process of bulk action which is call on bulk action
     * 
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
     */
	public function process_bulk_action() {
    
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            
        	wp_die(esc_html__( 'Items deleted (or they would be if we had items to delete)!', 'wwwpltable' ));
        } 
        
    }
	
    /**
     * Prepare Items 
     *
     * Does prepare all our data to show into the page
     * 
	 * @package WP List Table (Custom Table)
	 * @since 1.0.0
     */
	public function prepare_items() {
        
        // Get how many records per page to show
        $per_page	= $this->per_page;

        // Get All, Hidden, Sortable columns
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        
		// Get final column header
        $this->_column_headers = array($columns, $hidden, $sortable);

		// Proces bulk action
        $this->process_bulk_action();

		// Get Data of particular page
		$data_res 	= $this->display_coupons();
		$data 		= $data_res['data'];		

		// Get current page number
        $current_page = $this->get_pagenum();
        
		// Get total count
        $total_items = $data_res['total'];
        
        // Get page items
        $this->items = $data;
        
		// We also have to register our pagination options & calculations.
        $this->set_pagination_args( array(
									            'total_items' => $total_items,                  //WE have to calculate the total number of items
									            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
									            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
									        ) );
    }
    
}

//Create an instance of our package class...
$CouponListTable = new Wwwp_Ltable_Coupon_List();
	
//Fetch, prepare, sort, and filter our data...
$CouponListTable->prepare_items();

?>

<div class="wrap">
	
    <h2>
    	<?php esc_html_e( 'Coupons', 'wwwpltable' ); ?>
    	<a class="add-new-h2" href="admin.php?page=wwwp_ltable_add_coupon"><?php esc_html_e( 'Add New','wwwpltable' ); ?></a>
    </h2>
   	<?php 
   		$html = '';
		if(isset($_GET['message']) && !empty($_GET['message']) ) { //check message
			
			if( $_GET['message'] == '1' ) { //check message
				$html .= '<div class="updated settings-error" id="setting-error-settings_updated">
							<p><strong>'.esc_html__("Coupon saved successfully.",'wwwpltable').'</strong></p>
						</div>'; 
			} else if($_GET['message'] == '2') {//check message
				$html .= '<div class="updated" id="message">
							<p><strong>'.esc_html__("Coupon changed successfully.",'wwwpltable').'</strong></p>
						</div>'; 
			} else if($_GET['message'] == '3') {//check message
				$html .= '<div class="updated" id="message">
							<p><strong>'.esc_html__("Coupon deleted successfully.",'wwwpltable').'</strong></p>
						</div>'; 
			}
		}
		echo $html;
	?>
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="coupon-filter" method="get">
        
    	<!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
		
        <!-- Search Title -->
        <?php $CouponListTable->search_box( esc_html__( 'Search Title', 'wwwpltable' ), 'wwwp_ltable_search' ); ?>
        
        <!-- Now we can render the completed list table -->
        <?php $CouponListTable->display(); ?>
        
    </form>
	        
</div>