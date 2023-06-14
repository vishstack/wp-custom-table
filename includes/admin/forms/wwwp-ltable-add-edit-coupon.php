<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Add/Edit Coupon
 *
 * Handle Add / Edit Coupon
 * 
 * @package WP List Table (Custom Table)
 * @since 1.0.0
 */

	global $wwwp_ltable_model, $errmsg, $wpdb,$error;
	
	$model = $wwwp_ltable_model;
	
	$coupon_lable = '';
	
	$coupon_btn = '';
	
	$disc_available_arr = array();
	
	/*<!-- .begining of wrap -->*/	
	echo '<div class="wrap">';
		if(isset($_GET['action']) && !empty($_GET['action']) && $_GET['action']=='edit' && !empty($_GET['disc_id'])) { // update coupon detail
		
			$coupon_lable = esc_html__('Edit Coupon', 'wwwpltable');
			
			$coupon_btn = esc_html__('Update', 'wwwpltable');
			
			echo '<h2>'.$coupon_lable;
			
			$Id = $_GET['disc_id'];
			
			$args_edit = array (
									'disc_id' => $Id,
								);
			if($error != true) { //if error is not occured then fill data from database which is stored
				$method = $model->wwwp_ltable_get_coupon_by_id($args_edit);
				if(isset($method['disc_available']) && !empty($method['disc_available'])) {
					$method['disc_available'] = explode(',',$method['disc_available']);
				}	
			} else {
				$method = $_POST;			
			}
			
		} else { // insert coupon detail
			
			$coupon_lable = esc_html__('Add Coupon', 'wwwpltable');
			
			$coupon_btn = esc_html__('Save', 'wwwpltable');
			
			echo '<h2>'.$coupon_lable;
			
			if( !empty($_POST) ) {
				$method = $_POST;			
			}
		}
		
		if(empty($method['disc_available'])) { //initial value to disc available to prevent notices
			$method['disc_available'] = array();
		}	
?>
	<!--//href list page name blank-->
		<a class="add-new-h2" href="admin.php?page=wwwp_ltable_coupon_list"><?php echo esc_html__('Back to List','wwwpltable') ?></a>
	</h2>

	<!-- beginning of the coupon meta box -->

		<div id="wwwp-ltable-coupon" class="post-box-container">
		
			<div class="metabox-holder">	
		
				<div class="meta-box-sortables ui-sortable">
		
					<div id="coupon" class="postbox">	
		
						<div class="handlediv" title="<?php echo esc_html__( 'Click to toggle', 'wwwpltable' ) ?>"><br /></div>
		
							<!-- coupon box title -->				
							<h3 class="hndle">				
								<span style="vertical-align: top;"><?php echo $coupon_lable ?></span>				
							</h3>
		
							<div class="inside">
						
							<form action="" method="POST" id="wwwp-ltable-add-edit-form">
								<input type="hidden" name="page" value="wwwp_ltable_add_coupon" />										
								<div id="wwwp-ltable-require-message"><strong>(</strong> <span class="wwwp-ltable-require">*</span> <strong>)<?php echo esc_html__( 'Required fields', 'wwwpltable' ) ?></strong>
								</div>
								
								<table class="form-table wwwp-ltable-coupon-box"> 
									<tbody>
							
										<tr>
											<th scope="row">
												<label>
													<strong><?php echo esc_html__( 'Title:', 'wwwpltable' ) ?></strong>
													<span class="wwwp-ltable-require"> * </span>
												</label>
											</th>
											<td width="35%">
												<input type="text" id="wwwp-ltable-coupon-title" name="disc_title" value="<?php 
													if(isset($method['disc_title'])) { 
														echo $model->wwwp_ltable_escape_attr($method['disc_title']); 
													} 
													?>" size="63" />
												<br /><span class="description"><?php echo esc_html__( 'Enter the Coupon title.', 'wwwpltable' ) ?></span>
											</td>
											<td class="wwwp-ltable-coupon-error">
												<?php
												if(isset($errmsg['disc_title']) && !empty($errmsg['disc_title'])) {
													echo '<div>'.$errmsg['disc_title'].'</div>';
												}
												?>
											</td>
										</tr>
												
										<tr>
											<th scope="row">
												<label>
													<strong><?php echo esc_html__( 'Description:', 'wwwpltable' ) ?></strong>
													<span class="wwwp-ltable-require"> * </span>
												</label>
											</th>
											<td  width="35%">
												<textarea id="wwwp-ltable-coupon-desc" name="disc_content" rows="4" cols="60"><?php
												if(isset($method['disc_content'])) { //check coupon content
													echo $model->wwwp_ltable_escape_attr($method['disc_content']);
												}
												?></textarea><br />
												<span class="description"><?php echo esc_html__( 'Enter the Coupon description.', 'wwwpltable' ) ?></span>
											</td>
											<td class="wwwp-ltable-coupon-error">
												<?php
												if(isset($errmsg['disc_content']) && !empty($errmsg['disc_content'])) { //check coupon content
													echo '<div>'.$errmsg['disc_content'].'</div>';
												}
												?>
											</td>
										</tr>
								
									
										<tr>
											<th scope="row">
												<label>
													<strong><?php echo esc_html__( 'Category:', 'wwwpltable' ) ?></strong>
													<span class="wwwp-ltable-require"> * </span>
												</label>
											</th>
											<td  width="35%"><select id="disc_cat" name="disc_cat">
											<?php
												$cat_arr = array(
																''			=>	esc_html__( '--select--', 'wwwpltable' ),
																'Mobile'	=>	esc_html__( 'Mobile', 'wwwpltable' ),
																'Computer'	=>	esc_html__( 'Computer', 'wwwpltable' )
															);
												foreach ($cat_arr as $key => $value) {
													echo '<option value="'.$key.'" '.selected( isset($method['disc_cat']) ? $method['disc_cat'] : '', $key, false ).'>'.$value.'</option>';
												}
											?>										
											</select><br />
												<span class="description"><?php echo esc_html__( 'Select the Coupon category.', 'wwwpltable' ) ?></span>
											</td>
											<td class="wwwp-ltable-coupon-error">
												<?php
												if(isset($errmsg['disc_cat']) && !empty($errmsg['disc_cat'])) { //check coupon category
													echo '<div>'.$errmsg['disc_cat'].'</div>';
												}
												?>										
											</td>
										</tr>
								
										<tr>
											<th scope="row">
												<label><strong><?php echo esc_html__( 'Availability:', 'wwwpltable' ) ?></strong></label>
											</th>
											<td class="wwwp-ltable-avail-chk" width="35%">
												<input type="checkbox" name="disc_available[]" value="Client"<?php echo checked( in_array('Client', $method['disc_available']), true, false ) ?>/>
												<label><?php echo esc_html__( 'Client', 'wwwpltable' ) ?></label>
												
												<input type="checkbox" name="disc_available[]" value="Distributor"<?php echo checked( in_array('Distributor', $method['disc_available']), true, false ) ?>/>
												<label><?php echo esc_html__( 'Distributor', 'wwwpltable' ) ?></label>
												<br />
												<span class="description"><?php echo esc_html__( 'Choose the Coupon availability.', 'wwwpltable' ) ?></span>
											</td>
											<td class="wwwp-ltable-coupon-error">
											</td>
										</tr>										
								
										<tr>
											<th scope="row">
												<label><strong><?php echo esc_html__( 'Featured Coupon:', 'wwwpltable' ) ?></strong></label>
											</th>
											<td width="35%"><input type="checkbox" id="wwwp-ltable-featured-coupon" name="disc_featured_coupon" value="1"<?php echo checked( isset($method['disc_featured_coupon']) ? $method['disc_featured_coupon'] : '', '1', false ) ?>/><br />
												<span class="description"><?php echo esc_html__( 'Enter the featured Coupon.', 'wwwpltable' ) ?></span>
											</td>
											<td class="wwwp-ltable-coupon-error">
											</td>
										 </tr>
								
										<tr>
											<td colspan="3">
												<input type="submit" class="button-primary margin_button" name="wwwp_ltable_coupon_save" id="wwwp-ltable-save" value="<?php	echo $coupon_btn ?>" />
											</td>
										</tr>
										
									</tbody>
								</table>
								
							</form>							
					
						</div><!-- .inside -->
			
					</div><!-- #coupon -->
		
				</div><!-- .meta-box-sortables ui-sortable -->
		
			</div><!-- .metabox-holder -->
		
		</div><!-- #wps-coupon-general -->
		
	<!-- end of the coupon meta box -->
	
	</div><!-- . end of wrap -->