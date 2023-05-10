<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.kri8it.com
 * @since      1.0.0
 *
 * @package    Nextlevel_Carpro
 * @subpackage Nextlevel_Carpro/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Nextlevel_Carpro
 * @subpackage Nextlevel_Carpro/public
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class Nextlevel_Carpro_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nextlevel_Carpro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nextlevel_Carpro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nextlevel-carpro-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nextlevel_Carpro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nextlevel_Carpro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */


		wp_enqueue_script('jquery/datepicker/js', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', array( 'jquery' ), false, false);

		wp_enqueue_style('jquery/datepicker/css', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', null, false, false);

		wp_enqueue_script('bootstrap/js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', array(), false, false);

		wp_enqueue_style( 'bootstrap/css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', null, false, false);

		wp_enqueue_style('confirm/css', '//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css');

    	wp_enqueue_script('confirm/js', '//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js', array('jquery'));



    	wp_enqueue_style('telephone/css', plugin_dir_url( __FILE__ ) . 'css/intlTelInput.css');

    	wp_enqueue_script('telephone/js', plugin_dir_url( __FILE__ ) . 'js/intlTelInput.js', array('jquery'));

    	$_REQUIREMENT = '';

    	if(!wp_script_is('enqueued', 'eael-google-map-api')):

	    	wp_enqueue_script('google/maps', 'https://maps.googleapis.com/maps/api/js?key='.get_field('google_map_api_key','option').'&libraries=places', array( 'jquery'), false, false);
	    	$_REQUIREMENT = 'google/maps';

	    else:

	    	$_REQUIREMENT = 'eael-google-map-api';

	    endif;

    	wp_enqueue_script( 'google/html', plugin_dir_url( __FILE__ ) . 'js/htmlmarker.js', array( 'jquery',$_REQUIREMENT ), $this->version, false );


    	wp_enqueue_script('select2/js', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js', array( 'jquery'), false, false);

    	wp_enqueue_style('select2/css', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css');
    	
    	
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nextlevel-carpro-public.js', array( 'jquery', 'jquery/datepicker/js', 'confirm/js', $_REQUIREMENT, 'google/html', 'select2/js', 'telephone/js' ), $this->version, false );

		if(get_option('WPLANG') == ''):
			$_LANGUAGE = 'en_US';
		else:
			$_LANGUAGE = get_option('WPLANG');
		endif;

		$_ARRAY_OF_ARGS = array(
			'ajax_url' 					=> get_bloginfo('url').'/wp-admin/admin-ajax.php',
			'booking_lead_time' 		=> get_field('booking_lead_days','option'),
			'booking_default' 			=> get_field('booking_default_day','option'),
			'booking_minimum' 			=> get_field('booking_minimum_length','option'),
			'booking_maximum' 			=> get_field('booking_maximum_length','option'),
			'single_map'				=> get_field('single_map_height','option'),
			'multiple_map'				=> get_field('multiple_map_height','option'),
			'map_marker'				=> get_field('map_marker_image','option'),
			'telephoneutil'         	=> plugin_dir_url( __FILE__ ) . 'js/utils.js',
			'booking_session_title' 	=> get_field('booking_session_expired_title','option'),
			'booking_session_text' 		=> get_field('booking_session_expired_text','option'),
			'booking_session_button' 	=> get_field('booking_session_button_text','option'),
			'booking_session_link' 		=> get_field('booking_session_button_link','option'),
			'booking_session_time' 		=> (int)get_field('booking_session_homepage_timeout','option')*1000,
			'utility_queried_object_id' => get_queried_object_id()
		);

		if(get_field('enable_timer', 'option')):
			$_ARRAY_OF_ARGS['enable_timer'] = 'yes';
		else:
			$_ARRAY_OF_ARGS['enable_timer'] = 'no';
		endif;

		if(get_field('timer_length', 'option')):
			$_ARRAY_OF_ARGS['booking_timer_minutes'] = (int)get_field('timer_length', 'option');
		else:
			$_ARRAY_OF_ARGS['booking_timer_minutes'] = 0;
		endif;

		if(isset(WC()->session)):
			
			if(CARPRO_HELPERS::IS_SEARCH_RESULTS()):
				$_ARRAY_OF_ARGS['is_search'] = 'yes';

				if(WC()->session->get('carpro_out_branch')):
					$_ARRAY_OF_ARGS['search_carpro_out_branch'] = WC()->session->get('carpro_out_branch'); 
				endif;

				if(WC()->session->get('carpro_in_branch')):
					$_ARRAY_OF_ARGS['search_carpro_in_branch'] = WC()->session->get('carpro_in_branch');
				endif;

				if(WC()->session->get('carpro_out_date')):
					$_DATE = implode("-", array_reverse(explode("/", WC()->session->get('carpro_out_date'))));
					$_ARRAY_OF_ARGS['search_carpro_out_date'] = wp_date($_DATE);
				endif;

				if(WC()->session->get('carpro_in_date')):
					$_DATE = implode("-", array_reverse(explode("/", WC()->session->get('carpro_in_date'))));
					$_ARRAY_OF_ARGS['search_carpro_in_date'] = wp_date($_DATE);
				endif;

				if(WC()->session->get('carpro_out_time')):
					$_ARRAY_OF_ARGS['search_carpro_out_time'] = WC()->session->get('carpro_out_time');
				endif;

				if(WC()->session->get('carpro_in_time')):
					$_ARRAY_OF_ARGS['search_carpro_in_time'] = WC()->session->get('carpro_in_time');
				endif;

			endif;
			
		endif;


		wp_localize_script( $this->plugin_name, 'carpro_params', $_ARRAY_OF_ARGS );

	}










	/* AFTER THEME: REMOVE GENERIC ACTIONS */
	public function after_setup_theme(){

		add_filter('woocommerce_cart_item_removed_notice_type', '__return_null');

		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
		
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );

		add_action('woocommerce_single_product_summary', 'the_content', 20);

		remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );

	}










	/* WOOCOMMERCE TEMPLATE ACTION  */
	public function woocommerce_before_shop_loop_item(){

		echo '<div class="container"><div class="row">';

	}










	/* WOOCOMMERCE TEMPLATE ACTION */
	public function woocommerce_before_shop_loop_item_title_start(){

		echo '<div class="col-lg-4 col-12">';

	}










	/* WOOCOMMERCE TEMPLATE ACTION */
	public function woocommerce_before_shop_loop_item_title_image(){
		global $post, $product;

		$_CLASS = '';

		if(get_field('transmission', $post) == 'automatic'):
			$_CLASS = 'nomarginbottom';
		endif;

		echo '<div class="vehicle_group_block '.$_CLASS.'">GROUP <div class="vehicle_group_sku">'.$product->get_sku().'</div> <div class="mobileonly"><h2>'.$product->get_name().'</h2></div></div>';

		if(get_field('transmission', $post) == 'automatic'):
			echo '<div class="vehicle_group_transmission">Automatic</div>';
		endif;

		echo '<img class="vehicle_image" src="'.get_field('image', $post).'" alt="'.$post->post_title.'" />';

		$_TEXT = CARPRO_HELPERS::VEHICLE_TEXT($product->get_sku());
		
		if($_TEXT):
			echo '<div class="vehicle_group_text">'.$_TEXT.'</div>';
		endif;
	}










	/* WOOCOMMERCE TEMPLATE ACTION */
	public function woocommerce_before_shop_loop_item_title_end(){

		echo '</div>';
		echo '<div class="col-lg-8 col-12">';

	}










	/* WOOCOMMERCE TEMPLATE ACTION */
	public function woocommerce_after_shop_loop_item_title(){

		if(!is_shop() && !is_product_category() && !is_singular('product')):
			CARPRO_HELPERS::VEHICLE_RATE_OPTIONS();
		endif;

	}










	/* WOOCOMMERCE TEMPLATE ACTION */
	public function woocommerce_after_shop_loop_item(){

		echo '</div></div></div>';

	}










	/* GENERIC: RETURN FALSE */
	public function generic_false_function(){
		return false;
	}










	/* SHORTCODE SETUP */
	public function shortcodes(){


		add_shortcode('carpro_search_form', array($this, 'carpro_search_form'));

		add_shortcode('carpro_search_results', array($this, 'carpro_search_results'));

		add_shortcode('carpro_branch_map', array($this, 'carpro_branch_map'));

		add_shortcode('carpro_booking_links', array($this, 'carpro_booking_links'));

		add_shortcode('carpro_timer', array($this, 'carpro_timer'));

		/* Backwards compat */
		add_shortcode('nextlevel_search_form', array($this, 'carpro_search_form'));

		add_shortcode('nextlevel_search_results', array($this, 'carpro_search_results'));

		add_shortcode('nextlevel_branch_map', array($this, 'carpro_branch_map'));

		add_shortcode('nextlevel_booking_links', array($this, 'carpro_booking_links'));

		add_shortcode('nextlevel_timer', array($this, 'carpro_timer'));

	}










	/* SEARCH FORM SHORT CODE */
	public function carpro_search_form($_ATTS){

		$_BRANCHES = CARPRO_HELPERS::BRANCH_SELECT();

		$_BRANCH_PICK = false;
		$_BRANCH_DROP = false;
		$_DATE_PICK = false;
		$_DATE_DROP = false;

		if(is_singular('branch')):
			global $post;
			$_BRANCH_PICK = get_field('carpro_branch_code', $post);
			$_BRANCH_DROP = get_field('carpro_branch_code', $post);
		endif;

		if(CARPRO_HELPERS::IS_SEARCH_RESULTS()):

			if(WC()->session->get('carpro_out_branch')):

				$_BRANCH_PICK 	= WC()->session->get('carpro_out_branch');
				$_BRANCH_DROP 	= WC()->session->get('carpro_in_branch');

			endif;

		endif;

		

		ob_start();
		

		?>
		<div id="carpro_search_form_container">
				
			<form id="carpro_search_form" autocomplete="off">
				
				<div class="container-fluid">

					<div class="row">

						<div class="col-xl-5  col-md-12">

							<div class="row">

								<div id="SEARCHFORMLOCATIONCONTAINER" class="col-xl-8  col-md-6">
									<div><label id="SEARCHFORMLABEL" data-pick="Pick-up Location" data-drop="Pick-up & Drop-off Location">Pick-up & Drop-off Location</label></div>
										<select data-minimum-results-for-search="Infinity" name="OutBranch" id="carproOutBranch">
										
											<?php foreach($_BRANCHES as $_PROVINCE => $_BRANCH): ?>
												<optgroup label="<?php echo $_PROVINCE; ?>">
												<?php foreach($_BRANCH as $_CODE => $_TITLE): ?>
													<option class="branch_list_item" <?php selected($_CODE, $_BRANCH_PICK); ?> value="<?php echo $_CODE; ?>"><?php echo $_TITLE; ?></option>
												<?php endforeach; ?>
												</optgroup>
											<?php endforeach; ?>

										</select>							
								</div>

								<div id="SEARCHFORMLOCATIONDROPOFF" class="col-xl-4  col-md-6">
									<div class="relative"><label id="SEARCHFORMDROPLABEL">Drop-off Location <span id="SEARCHFORMCLOSECHECKBOX"> X </span></label> </div>
									<div id="SEARCHFORMRELATIVEBOX">
									<select data-minimum-results-for-search="Infinity" name="InBranch" id="carproInBranch">
										
										<?php foreach($_BRANCHES as $_PROVINCE => $_BRANCH): ?>
											<optgroup label="<?php echo $_PROVINCE; ?>">
											<?php foreach($_BRANCH as $_CODE => $_TITLE): ?>
												<option class="branch_list_item" <?php selected($_CODE, $_BRANCH_DROP); ?>  value="<?php echo $_CODE; ?>"><?php echo $_TITLE; ?></option>
											<?php endforeach; ?>
											</optgroup>
										<?php endforeach; ?>

									</select>

									<div id="SEARCHFORMLOCATIONCHECKBOX" class="active">
										<div class="location_change_box">
											<input id="carpro_different_location" type="checkbox" checked="checked" name="carpro_different_location" autocomplete="off" />
											<label id="SEARCHFORMOPENBOX" class="location_label">Return At Pick-up location</label>
										</div>
									</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xl-3 col-md-6 lg-m-top md-m-top">
							<div><label>Pick-up Date</label></div>
							<div class="row">
							<div class="col-6"><input type="text" name="OutDate" id="carproOutDate"autocomplete="off" /></div>
							<div class="col-6"><select data-minimum-results-for-search="Infinity" name="OutTime" id="carproOutTime"></select></div>
							</div>
						</div>

						<div class="col-xl-3 col-md-6 lg-m-top md-m-top">
							<div><label>Drop-off Date</label></div>
							<div class="row">
							<div class="col-6"><input type="text" name="InDate" id="carproInDate"autocomplete="off" /></div>
							<div class="col-6"><select data-minimum-results-for-search="Infinity" name="InTime" id="carproInTime"></select></div>
							</div>
						</div>

						<div id="SEARCHFORMACTION" class="col-xl-1">
							<a id="carproPerformSearch">Search</a>
						</div>

					</div>

				</div>

			</form>

		</div>


		<?php

		return ob_get_clean();

	}










	/* SEARCH FORM SHORT CODE */
	public function carpro_search_results($_ATTS){

		if(get_field('carpro_debug_session', 'option') && current_user_can('administrator')):
			echo '<pre>';
			print_r(WC()->session);
			echo '</pre>';
		endif;

		ob_start();

		if(isset(WC()->session) && WC()->session->get('carpro_includes') && count(WC()->session->get('carpro_includes')) > 0):
			$_PRODUCT_ARGS = array(
				'post_type' => 'product',
				'posts_per_page' => '-1',
				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key' => 'vehicle_code',
						'value' => WC()->session->get('carpro_includes'),
						'compare' => 'IN'
					)
				),
				'orderby' => 'menu_order',
				'order' => 'ASC'
			);


			$_CUSTOM_BRANCH_SORT = CARPRO_HELPERS::CUSTOM_BRANCH_SORT();

			if(is_array($_CUSTOM_BRANCH_SORT) && count($_CUSTOM_BRANCH_SORT) > 0):
				$_PRODUCT_ARGS['post__in'] = $_CUSTOM_BRANCH_SORT;
				$_PRODUCT_ARGS['orderby'] = 'post__in';
				unset($_PRODUCT_ARGS['order']);			
			endif;

			$_PRODUCTS = get_posts($_PRODUCT_ARGS);

			if(count($_PRODUCTS) > 0):

				?>
				<div id="CARPROSEARCHRESULTS">
				<div class="col-12 carprotimercontainer margin-bottom-30">
					<?php echo do_shortcode('[carpro_timer]'); ?>
				</div>

				<?php

				global $post;

				woocommerce_product_loop_start();				
				foreach($_PRODUCTS as $post):

					$_SKU = get_post_meta($post->ID, '_sku', true);

					foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

						if($_CODE == $_SKU && isset($_DATA['vehicle']['rates']) && count($_DATA['vehicle']['rates']) > 0):

							setup_postdata($post);
							wc_get_template_part( 'content', 'product' );

						endif;

					endforeach;

				endforeach;
				woocommerce_product_loop_end();

				?> </div> <?php
			else:


				echo '<p class="carpro_error">No Availability found for selection</p>';

			endif;

		else:
				echo '<p class="carpro_info">Use the form above to perform a search.</p>';

		endif;


		return ob_get_clean();

	}










	/* MAPS */
	public function carpro_branch_map(){


		$_MARKERS = array();

		if(is_singular('branch')):
			$_ID = 'single_map_branch';
			global $post;

			$_MARKERS[] = $post;

			$_ZOOM = get_field('single_map_zoom', 'option');

		else:

			$_ID = 'global_map_branch';

			$_MARKERS = get_posts(
				array(
					'post_type' => 'branch',
					'posts_per_page' => '-1',
					'meta_key' => 'enabled',
					'meta_value' => 1,
				)
			);

			$_ZOOM = get_field('multiple_map_zoom', 'option');



		endif;


		ob_start();

		?>

			<div class="carpro_map" id="<?php echo $_ID; ?>" data-zoom="<?php echo $_ZOOM; ?>">
				<?php foreach($_MARKERS as $_MARKER): ?>

					<?php 
						$_LAT = get_field('latitude', $_MARKER); 
						$_LNG = get_field('longitude', $_MARKER); 
						$_TITLE = $_MARKER->post_title;
					?>

					<div class="marker" 
						data-lat="<?php echo $_LAT; ?>" 
						data-lng="<?php echo $_LNG; ?>" 
						data-title="<?php echo $_TITLE; ?>">
						
					</div>


				<?php endforeach; ?>
			</div>

		<?php


		return ob_get_clean();


	}










	/* SEARCH BAR LINKS*/
	public function carpro_booking_links(){

		$_ARRAY_OF_LINKS = array();

		//$_ARRAY_OF_LINKS[] = '<span class="top_bar_item" id="topBarTimer"></span>';

		if(WC()->session->get('carpro_availability')):

			$_ARRAY_OF_LINKS[] = '<a class="top_bar_item top_search" href="'.get_field('search_results_page', 'option').'">Search Results</a>';

		endif;

		if(WC()->session->get('carpro_selected_vehicle')):

			$_ARRAY_OF_LINKS[] = '<a class="top_bar_item top_finalise" href="'.wc_get_checkout_url().'">Finalise Booking</a>';

		endif;


		if(count($_ARRAY_OF_LINKS) > 1):

			ob_start();

			?>

			<div id="carpro_links">
				
				<?php echo implode("<span class='top_bar_item sep'> | </span>", $_ARRAY_OF_LINKS); ?>

			</div>

			<?php

			return ob_get_clean();

		endif;

	}









	public function carpro_timer(){

		if(get_field('enable_timer', 'option')):

			ob_start();
			?>
			<div id="carproTimer">You have <span id="carproTimerText"></span> left to complete your booking.</div>
			<?php

			return ob_get_clean();

		endif;

	}










	/* FILTER PRICE BASED ON AVAILABILITY */
	public function woocommerce_get_price_html($_PRICE, $_PRODUCT){

		if(!is_shop() && !is_product_category() && !is_singular('product')):
			$_SKU = $_PRODUCT->get_sku();
		
			if(isset(WC()->session) && WC()->session->get('carpro_selected_code') == $_SKU):

				return $_PRICE;

			elseif(isset(WC()->session) && WC()->session->get('carpro_availability')):

				/* INITIAL PRICE */			

				foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

					if(trim($_SKU) == trim($_CODE)):

						return $_PRICE;

					endif;

				endforeach;

			endif;
		endif;

		return false;

	}








	/* FILTER/SHOW PRICE BASED ON SEARCH CRITERIA */
	public function woocommerce_product_get_price($_PRICE, $_PRODUCT){

		$_SKU = $_PRODUCT->get_sku();

		if(!is_shop() && !is_product_category() && !is_singular('product')):
	
			if(isset(WC()->session) && WC()->session->get('carpro_selected_sku') == $_SKU):

				$_RATE = WC()->session->get('carpro_selected_rate');
				$_PRICE = $_RATE['total'];
				return $_PRICE;

			elseif(isset(WC()->session) && WC()->session->get('carpro_availability')):

				/* INITIAL PRICE */			

				foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

					if(trim($_SKU) == trim($_CODE)):

						$_FIRST_RATE = reset($_DATA['vehicle']['rates']);

						if(isset($_FIRST_RATE['rates']) && is_array($_FIRST_RATE['rates'])):
							$_THE_RATE = reset($_FIRST_RATE['rates']);

							$_PRICE = $_THE_RATE['total'];
							return $_PRICE;
						endif;

					endif;

				endforeach;

			endif;

		endif;

		return false;

	}








	/* PURCHASABLE FUNCTION */
	public function woocommerce_is_purchasable($_PURCHASE, $_PRODUCT){

		$_SKU = $_PRODUCT->get_sku();

		if(!is_shop() && !is_product_category() && !is_singular('product')):
			if(isset(WC()->session) && WC()->session->get('carpro_selected_sku') == $_SKU):
				return true;

			elseif(isset(WC()->session) && WC()->session->get('carpro_availability')):

				foreach(WC()->session->get('carpro_availability') as $_CODE => $_DATA):

					if(trim($_SKU) == trim($_CODE)):

						return true;

					endif;

				endforeach;

			endif;

		endif;

		return false;


	}










	/* ADD EXTRAS AND NOTES AT END OF CHECKOUT */
	//public function woocommerce_before_order_notes($_CHECKOUT){
	public function carpro_extra_option_fields($_CHECKOUT){

		$_CART_ITEMS = WC()->cart->get_cart();
		$_FIRST = reset($_CART_ITEMS);
		$_SKU = $_FIRST['data']->get_sku();

		$_DESCRIPTIONS = get_field('extra_tooltips', 'option');


		if(isset(WC()->session) && WC()->session->get('carpro_available_extras_once')):
			$_ONCE = WC()->session->get('carpro_available_extras_once');
			echo '<div class="mobilecheckoutblock" id="checkout_extras_once_off"><h3>' . __('Once Off Extras') . '</h2>';

				foreach($_ONCE as $_KEY => $_DATA):

					$_DESCRIPTION = false;

					foreach($_DESCRIPTIONS as $_DESC):

						if($_DESC['code'] == $_KEY):
							$_DESCRIPTION = $_DESC['text'];
						endif;

					endforeach;


					if(isset($_DATA['perday'])):
						$_VALUE_TO_USE = $_DATA['perday'];
					else:
						$_VALUE_TO_USE = $_DATA['total'];
					endif;


					if($_DESCRIPTION):

					 woocommerce_form_field( 'carpro_extra_'.$_KEY, array(
				        'type'          => 'checkbox',
				        'class'         => array('carpro-extra-checkbox form-row-wide'),
				        'label'         => __($_DATA['title'].' <small>Charged at '.wc_price($_VALUE_TO_USE).'</small>'),
				        'description'   => $_DESCRIPTION
				        ), CARPRO_HELPERS::ISEXTRASELECTED($_KEY));

					else:


						woocommerce_form_field( 'carpro_extra_'.$_KEY, array(
				        'type'          => 'checkbox',
				        'class'         => array('carpro-extra-checkbox form-row-wide'),
				        'label'         => __($_DATA['title'].' <small>Charged at '.wc_price($_VALUE_TO_USE).'</small>')
				        ), CARPRO_HELPERS::ISEXTRASELECTED($_KEY));


					endif;

				endforeach;

			echo '</div>';

		endif;


		if(isset(WC()->session) && WC()->session->get('carpro_available_extras_daily')):
			$_DAILY = WC()->session->get('carpro_available_extras_daily');
			echo '<div class="mobilecheckoutblock"  id="checkout_extras_daily"><h3>' . __('Per Day Extras') . '</h2>';

			foreach($_DAILY as $_KEY => $_DATA):

					$_DESCRIPTION = false;

					foreach($_DESCRIPTIONS as $_DESC):

						if($_DESC['code'] == $_KEY):
							$_DESCRIPTION = $_DESC['text'];
						endif;

					endforeach;

					if(isset($_DATA['perday'])):
						$_VALUE_TO_USE = $_DATA['perday'];
					else:
						$_VALUE_TO_USE = $_DATA['total'];
					endif;

					if($_DESCRIPTION):

					 woocommerce_form_field( 'carpro_extra_'.$_KEY, array(
				        'type'          => 'checkbox',
				        'class'         => array('carpro-extra-checkbox form-row-wide'),
				        'label'         => __($_DATA['title'].' <small>Charged at '.wc_price($_VALUE_TO_USE).' per day</small>'),
				        'description'   => $_DESCRIPTION
				        ), CARPRO_HELPERS::ISEXTRASELECTED($_KEY));

					else:

						 woocommerce_form_field( 'carpro_extra_'.$_KEY, array(
				        'type'          => 'checkbox',
				        'class'         => array('carpro-extra-checkbox form-row-wide'),
				        'label'         => __($_DATA['title'].' <small>Charged at '.wc_price($_VALUE_TO_USE).' per day</small>')
				        ), CARPRO_HELPERS::ISEXTRASELECTED($_KEY));

					endif;

				endforeach;
			echo '</div>';
		endif;

	}










	/* ADD EXTRA SECTIONS */
	public function carpro_extra_sections(){


		$_SECTIONS = get_field('checkout_extra_sections', 'option');
		if(is_array($_SECTIONS) && count($_SECTIONS) > 0):
			foreach($_SECTIONS as $_S):
				echo '<div class="mobilecheckoutblock"  id="checkout_extras_'.str_replace("-", "_", sanitize_title($_S['section'])).'"><h3>' . __($_S['section']) . '</h2>';

				foreach($_S['items'] as $_I):
					?>
					<div class="carpro-extra-text form-row-wide form-row">
						<div class="woocommerce-input-wrapper">
							<?php if($_I['above_content']): ?>
								<div class="carpro_extra_above"><small><?php echo $_I['above_content']; ?></small></div>
							<?php endif; ?>
							<label class="carpro_extra_label">
								<?php echo $_I['title']; ?> <?php if($_I['cost']): ?><small>Charged at <?php echo wc_price($_I['cost']); ?></small> <?php endif; ?>
							</label>
							<?php if($_I['below_content']): ?>
								<div class="carpro-description description" aria-hidden="true"><?php echo $_I['below_content']; ?></div>
							<?php endif; ?>
						</div>
					</div>
					<?php
				endforeach;

				echo '</div>';
			endforeach;
		endif;


	}










	/* ADD EXTRAS AND NOTES AT END OF CHECKOUT */
	public function carpro_extra_detail_fields($_CHECKOUT){


		echo '<div class="mobilecheckoutblock"  id="checkout_express"><h3>' . __('For an express collection, please complete below') . '</h2>';

			woocommerce_form_field( 'license_number', array(
		    	'type'          => 'text',
		    	'class'         => array('carpro-license-number form-row-wide'),
		    	'label'         => __('License Number'),
		    ), $_CHECKOUT->get_value( 'license_number' ));

			woocommerce_form_field( 'license_expiry', array(
		    	'type'          => 'text',
		    	'class'         => array('carpro-license-expiry form-row-wide'),
		    	'label'         => __('License Expiry'),
		    ), $_CHECKOUT->get_value( 'license_expiry' ));

		echo '</div>';

		echo '<div class="mobilecheckoutblock"  id="checkout_travel_information"><h3>' . __('Travel Information') . '</h2>';

			woocommerce_form_field( 'arrival_flight_number', array(
		    	'type'          => 'text',
		    	'class'         => array('arrival-flight-number form-row-wide'),
		    	'label'         => __('Arrival Flight Number'),
		    ), $_CHECKOUT->get_value( 'arrival_flight_number' ));

		echo '</div>';

	}










	/* ADD EXTRAS AND NOTES AT END OF CHECKOUT */
	//public function woocommerce_before_order_notes($_CHECKOUT){
	public function carpro_extra_payment_fields($_CHECKOUT){

		if(get_field('enable_payment_type', 'option')):

			$_PAYMENT_TYPES = get_field('payment_types', 'option');

			if(is_array($_PAYMENT_TYPES) && count($_PAYMENT_TYPES) > 0):

				$_BILLING_ARRAY = array();

				foreach($_PAYMENT_TYPES as $_PT):
					$_BILLING_ARRAY[$_PT['percentage'].'::'.$_PT['label']] = $_PT['label'];
				endforeach;

				echo '<div class="mobilecheckoutblock"  id="checkout_payment"><h3>' . __('Payment Type') . '</h2>';

					woocommerce_form_field( 'payment_type', array(
				    	'type'          => 'select',
				    	'required'  	=> true,
				    	'options'		=> $_BILLING_ARRAY,
				    	'class'         => array('payment-type form-row-wide'),
				    	'label'         => __('Payment Type'),
				    ), WC()->session->get('carpro_deposit_type'));

				echo '</div>';

			endif;
		endif;

	}










	/* EDIT THE ADD TO CART LINK FOR OUR OWN DEVICES */
	public function woocommerce_loop_add_to_cart_link($_LINK, $_PRODUCT, $_ARGS){
		
		$_ID = $_PRODUCT->get_id();
		$_SKU = $_PRODUCT->get_sku();

		if(!is_shop() && !is_product_category() && !is_singular('product')):
			$_CODE = CARPRO_HELPERS::VEHICLE_CODE($_SKU);
			$_RATE = CARPRO_HELPERS::VEHICLE_FIRST_RATE($_SKU);

			if($_RATE):
				$_LINK = '<a class="carpro_add_to_cart" data-sku="'.$_SKU.'" data-id="'.$_ID.'" data-vehicle="'.$_CODE.'" data-km="'.$_RATE['km'].'" data-code="'.$_RATE['code'].'" data-owf="'.$_RATE['owf'].'">Book Vehicle</a>';
			endif;
		endif;

		return $_LINK;


	}










	/* EDIT THE ADD TO CART LINK FOR OUR OWN DEVICES */
	public function woocommerce_account_orders_columns($_LINKS){
		
		$_LINKS = array(
			'order-number'  => __( 'Booking', 'woocommerce' ),
			'order-date'    => __( 'Date', 'woocommerce' ),
			'order-total'   => __( 'Rental Sum', 'woocommerce' ),
			'order-actions' => __( 'Actions', 'woocommerce' ),
		);

		return $_LINKS;


	}










	/* RETURN TO SHOP TEXT */
	public function woocommerce_return_to_shop_text($_TEXT){

		$_TEXT = 'Back to Search Results';

		return $_TEXT;
	}










	/* REMOVE CART ITEM */
	public function woocommerce_remove_cart_item($_KEY, $_CART){
		
		CARPRO_HELPERS::CLEAR_CART();

	}










	/* FOOTER FUNCTION FOR LOADER */
	public function wp_footer(){
		?>

		<style type="text/css">

	      #single_map_branch{ min-height: <?php the_field('single_map_size', 'option'); ?>px }
	      #global_map_branch{ min-height: <?php the_field('multiple_map_size', 'option'); ?>px }

	      #carproTimer{
				background-color: <?php the_field('timer_background_colour', 'option'); ?>;			
		  }
		</style>

		
	

		<?php


		if(is_plugin_active('elementor-pro/elementor-pro.php')):

			?>

			<style type="text/css">
				#carproLoader{
					background-color: <?php the_field('overlay_background_colour', 'option'); ?>;		
				}

				.jconfirm-bg{
					background-color: <?php the_field('overlay_background_colour', 'option') ?> !important;
					opacity: 1 !important; 		
				}

				
			</style>

			<div id="carproLoader">
				<div class="carproLoaderText">
					<i class="fa-solid fa-car"></i>
					<span>LOADING</span>
				</div>
			</div>

			<?php

		else:

			?>

			<style type="text/css">
				#carproLoader{
					background-color: <?php the_field('overlay_background_colour', 'option'); ?>;
					background-image:url( <?php the_field('overlay_loading_gif', 'option'); ?>);				
				}

				.jconfirm-bg{
					background-color: <?php the_field('overlay_background_colour', 'option') ?> !important;
					opacity: 1 !important; 		
				}
			</style>

			<div id="carproLoader"></div>

			<?php


		endif;
	}










	/* BEFORE CHECKOUT FORM - CURRENTLY DEBUG */
	public function woocommerce_before_checkout_form(){

		if(get_field('carpro_debug_session', 'option') && current_user_can('administrator')):
			echo '<pre>';
			print_r(WC()->session);
			echo '</pre>';
		endif;

	}










	/* SHOW ITEM META FOR PRODUCT - SELECTED KM ETC */
	public function woocommerce_get_item_data($item_data, $cart_item){

		$_DAYS = WC()->session->get('carpro_days');

		if(WC()->session->get('carpro_out_branch')):
			$_OUT_BRANCH = CARPRO_HELPERS::GET_BRANCH_FROM_CODE(WC()->session->get('carpro_out_branch'));
			$item_data['out-branch'] = array('name' => 'Pick-up Branch', 'display'=> $_OUT_BRANCH->post_title);
		endif;

		if(WC()->session->get('carpro_out_date')):
			$_OUT_DATE = WC()->session->get('carpro_out_date');
			$_OUT_DATE = date('d F Y', strtotime(str_replace("/", "-", $_OUT_DATE)));
			$item_data['out-date'] = array('name' => 'Pick-up Date', 'display'=> $_OUT_DATE);
		endif;

		if(WC()->session->get('carpro_out_time')):
			$item_data['out-time'] = array('name' => 'Pick-up Time', 'display'=> WC()->session->get('carpro_out_time'));
		endif;

		if(WC()->session->get('carpro_in_branch')):
			$_IN_BRANCH = CARPRO_HELPERS::GET_BRANCH_FROM_CODE(WC()->session->get('carpro_in_branch'));
			$item_data['in-branch'] = array('name' => 'Drop-off Branch', 'display'=> $_IN_BRANCH->post_title);
		endif;

		if(WC()->session->get('carpro_in_date')):
			$_IN_DATE = WC()->session->get('carpro_in_date');
			$_IN_DATE = date('d F Y', strtotime(str_replace("/", "-", $_IN_DATE)));
			$item_data['in-date'] = array('name' => 'Drop-off Date', 'display'=> $_IN_DATE);
		endif;

		if(WC()->session->get('carpro_in_time')):
			$item_data['in-time'] = array('name' => 'Drop-off Time', 'display'=> WC()->session->get('carpro_in_time'));
		endif;

		if(WC()->session->get('carpro_selected_km')):
			$item_data['km-option'] = array('name' => 'KM Option', 'display'=> WC()->session->get('carpro_selected_km').' kms');
		endif;

		if(WC()->session->get('carpro_selected_rate')):
			$_RATE = WC()->session->get('carpro_selected_rate');

			$item_data['cover-option'] = array('name' => 'Cover Option', 'display'=> $_RATE['title']);
			$item_data['cover-deposit'] = array('name' => 'Cover Deposit', 'display'=> wc_price($_RATE['deposit']));
			$item_data['cover-liability'] = array('name' => 'Cover Liability', 'display'=> wc_price($_RATE['liability']));
		endif;

		if(WC()->session->get('carpro_deposit_percentage')):
			//$_DEP = WC()->session->get('carpro_deposit_percentage');
			//$item_data['deposit'] = array('name' => 'Deposit', 'display'=> $_DEP);
		endif;

		if(WC()->session->get('carpro_days')):
			$item_data['days'] = array('name' => 'Days', 'display'=> WC()->session->get('carpro_days'));
		endif;

		if(WC()->session->get('carpro_selected_rate')):

			$_RATE = WC()->session->get('carpro_selected_rate');

			$item_data['daily-rate'] = array('name' => 'Daily Rate', 'display'=> wc_price($_RATE['pd']));

			if(get_field('booking_includes_items','option')):

				$_INCLUDES = get_field('booking_includes_items','option'); 
				$_INCLUDES = implode("<br/>", array_column($_INCLUDES, 'text'));

				$item_data['includes'] = array('name' => 'Daily Rate Includes', 'display'=> $_INCLUDES);

			endif;

		endif;

		

		return $item_data;
	}















	/* ADDS EXTRAS ETC TO CHECKOUT */
	public function woocommerce_cart_calculate_fees(){

		if ( is_admin() && ! defined( 'DOING_AJAX' ) || ! is_checkout() ):
			return;
		endif;

		// Only trigger this logic once.
		if ( did_action( 'woocommerce_cart_calculate_fees' ) >= 2 ):
			return;
		endif;

		$_FEE_TOTAL = 0;

		if ( isset( $_POST['post_data'] ) ):

			/* FIRST WE NEED TO GROUP TOGETHER ALL THE SELECTED EXTRAS */
			$_ONCE = false;
			$_DAILY = false;

			$_SELECTED_ONCE = array();
			$_SELECTED_DAILY = array();
			WC()->session->__unset('carpro_selected_daily_extras');
			WC()->session->__unset('carpro_selected_once_extras');

			if(isset(WC()->session) && WC()->session->get('carpro_available_extras_once')):
				$_ONCE = WC()->session->get('carpro_available_extras_once');
			endif; 

			if(isset(WC()->session) && WC()->session->get('carpro_available_extras_daily')):
				$_DAILY = WC()->session->get('carpro_available_extras_daily');
			endif; 

			parse_str( $_POST['post_data'], $_CO_FIELDS );

			foreach($_CO_FIELDS as $_KEY => $_VALUE):

				if(strstr($_KEY, 'carpro_extra') && (int)$_VALUE == 1):

					$_EXTRA_CODE = str_replace("carpro_extra_", "", $_KEY);
					
					if($_ONCE):

						foreach($_ONCE as $_CC => $_VALUES):

							if(trim($_CC) == trim($_EXTRA_CODE)):
								$_SELECTED_ONCE[$_CC] = $_VALUES;
							endif;

						endforeach;

						if(count($_SELECTED_ONCE) > 0):
							WC()->session->set('carpro_selected_once_extras', $_SELECTED_ONCE);
						endif;

					endif;

					if($_DAILY):						

						foreach($_DAILY as $_CC => $_VALUES):

							if(trim($_CC) == trim($_EXTRA_CODE)):

								$_SELECTED_DAILY[$_CC] = $_VALUES;

							endif;

						endforeach;

						if(count($_SELECTED_DAILY) > 0):
							WC()->session->set('carpro_selected_daily_extras', $_SELECTED_DAILY);
						endif;
					endif;

				endif;

			endforeach;	

		endif;

		if(WC()->session->get('carpro_selected_once_extras') && count(WC()->session->get('carpro_selected_once_extras')) > 0):

			foreach(WC()->session->get('carpro_selected_once_extras') as $_CC => $_VALUES):

				if(isset($_VALUES['perday'])):
					$_AMT = $_VALUES['perday'];
				else:
					$_AMT = $_VALUES['total'];
				endif;

				WC()->cart->add_fee( $_VALUES['title'], $_AMT, true, '' );
				$_FEE_TOTAL += $_AMT;
			endforeach;

		endif;

		if(isset(WC()->session) && WC()->session->get('carpro_selected_daily_extras') && 
			count(WC()->session->get('carpro_selected_daily_extras')) > 0):
			$_DAYS = WC()->session->get('carpro_days');

			foreach(WC()->session->get('carpro_selected_daily_extras') as $_CC => $_VALUES):

				if(isset($_VALUES['perday'])):
					$_AMT = (float)$_VALUES['perday']*(float)$_DAYS;
				else:
					$_AMT = (float)$_VALUES['total']*(float)$_DAYS;
				endif;

				WC()->cart->add_fee( $_VALUES['title'], $_AMT, true, '' );
				$_FEE_TOTAL += $_AMT;
			endforeach;

		endif;


		if(isset(WC()->session) && WC()->session->get('carpro_fees')):

			$_FEES = WC()->session->get('carpro_fees');

			foreach($_FEES as $_CODE => $_DATA):
				WC()->cart->add_fee( $_DATA['title'], $_DATA['amt'], true, '' );
				$_FEE_TOTAL += $_DATA['amt'];
			endforeach;

		endif;


		if(isset(WC()->session) && WC()->session->get('carpro_one_way_fee')):

			$_OWF = (float)WC()->session->get('carpro_one_way_fee');
			if($_OWF > 0):
				WC()->cart->add_fee( 'One Way Fee', $_OWF, true, '' );
				$_FEE_TOTAL+=$_OWF;
			endif;

		endif;

		$_CART_TOTAL = WC()->cart->cart_contents_total;
		$_CART_TOTAL += $_FEE_TOTAL;
		$_ADD = false;
		$_DEP_PERC 		= false;
		$_DEP_VAL 		= false;
		$_DEP_TITLE 	= false;
		
		if ( isset( $_POST['post_data'] ) ):
			parse_str( $_POST['post_data'], $_CO_FIELDS );

			if(isset($_CO_FIELDS['payment_type'])):	

				$_DATA = explode("::", $_CO_FIELDS['payment_type']);
				WC()->session->set('carpro_deposit_type', (float)$_DATA[0]);
				WC()->session->set('carpro_deposit_title', $_DATA[1]);
				WC()->session->set('carpro_deposit_percentage', $_DATA[0].'%');

				$_DEP_PERC 		= $_DATA[0].'%';
				$_DEP_VAL 		= $_DATA[0];
				$_DEP_TITLE 	= $_DATA[1];

				$_ADD = true;
				
			endif; 

		else:

			if(WC()->session->get('carpro_deposit_percentage')):

				$_DEP_PERC 		= (int)WC()->session->get('carpro_deposit_percentage');
				$_DEP_VAL 		= (float)WC()->session->get('carpro_deposit_type');
				$_DEP_TITLE 	= WC()->session->get('carpro_deposit_title');
				$_ADD = true;

			endif;

		endif;

		if($_ADD):

			$_PERCENT = 100 - $_DEP_VAL;

			$_AMT = 0;
			$_AMT = (($_PERCENT / 100) * $_CART_TOTAL);
			$_DAMT = $_CART_TOTAL-$_AMT;
			$_AMT = number_format($_AMT, 2, ".", "");

			$_DAMT = number_format((float)$_DAMT, 2, ".", "");

			WC()->session->set('carpro_deposit_amount', $_DAMT);
			WC()->cart->add_fee( $_DEP_TITLE, $_AMT*-1, true, '' );

			/*
			switch($_DEP):

				case "50%":
					$_TITLE = '50% Deposit';
					$_PERCENT = 50;
					$_AMT = (($_PERCENT / 100) * $_CART_TOTAL);
					$_DAMT = $_CART_TOTAL-$_AMT;
					$_AMT = number_format($_AMT, 2, ".", "");
				break;

				case "25%":
					$_TITLE = '25% Deposit';
					$_PERCENT = 75;
					$_AMT = (($_PERCENT / 100) * $_CART_TOTAL);
					$_DAMT = $_CART_TOTAL-$_AMT;
					$_AMT = number_format($_AMT, 2, ".", "");
				break;

				case "0%":
					$_TITLE = '0% Deposit';
					$_PERCENT = 100;
					$_AMT = (($_PERCENT / 100) * $_CART_TOTAL);
					$_DAMT = $_CART_TOTAL-$_AMT;
					$_AMT = number_format($_AMT, 2, ".", "");
				break;

				default:
					$_AMT = 0;
					$_OUT = 0;
					$_DAMT = 0;
				break;

			endswitch;

			WC()->session->set('carpro_deposit_amount', $_DAMT);

			if($_AMT > 0):
				WC()->cart->add_fee( $_TITLE, $_AMT*-1, true, '' );
			endif;
			*/
		endif;
		
		
		
	}










	/* UPDATE PRODUCT ITEMS */
	public function woocommerce_checkout_create_order_line_item($item, $cart_item_key, $values, $order){

		if(WC()->session->get('carpro_out_branch')):
			$_OUT_BRANCH = CARPRO_HELPERS::GET_BRANCH_FROM_CODE(WC()->session->get('carpro_out_branch'));
			$item->update_meta_data( 'Pick-up Branch', $_OUT_BRANCH->post_title);
		endif;

		if(WC()->session->get('carpro_out_date')):
			$_OUT_DATE = WC()->session->get('carpro_out_date');
			$_OUT_DATE = date('d F Y', strtotime(str_replace("/", "-", $_OUT_DATE)));
			$item->update_meta_data( 'Pick-up Date', $_OUT_DATE);
		endif;

		if(WC()->session->get('carpro_out_time')):
			$item->update_meta_data( 'Pick-up Time', WC()->session->get('carpro_out_time'));
		endif;

		if(WC()->session->get('carpro_in_branch')):
			$_IN_BRANCH = CARPRO_HELPERS::GET_BRANCH_FROM_CODE(WC()->session->get('carpro_in_branch'));
			$item->update_meta_data( 'Drop-off Branch', $_IN_BRANCH->post_title);
		endif;

		if(WC()->session->get('carpro_in_date')):
			$_IN_DATE = WC()->session->get('carpro_in_date');
			$_IN_DATE = date('d F Y', strtotime(str_replace("/", "-", $_IN_DATE)));
			$item->update_meta_data( 'Drop-off Date', $_IN_DATE);
		endif;

		if(WC()->session->get('carpro_in_time')):
			$item->update_meta_data( 'Drop-off Time', WC()->session->get('carpro_in_time'));
		endif;

		if(WC()->session->get('carpro_selected_km')):
		 	$item->update_meta_data( 'KM Option', WC()->session->get('carpro_selected_km').' kms' );
		endif;

		if(WC()->session->get('carpro_selected_rate')):
			$_RATE = WC()->session->get('carpro_selected_rate');
			$item->update_meta_data( 'Cover Option', $_RATE['title'] );
			$item->update_meta_data( 'Deposit', wc_price($_RATE['deposit']) );
			$item->update_meta_data( 'Liability', wc_price($_RATE['liability']) );
		endif;

		if(WC()->session->get('carpro_deposit_percentage')):
			//$_DEP = WC()->session->get('carpro_deposit_percentage');
			//$item->update_meta_data( 'Deposit', $_DEP);
		endif;
		
		if(WC()->session->get('carpro_days')):
			$item->update_meta_data( 'Days', WC()->session->get('carpro_days'));
		endif;

		if(WC()->session->get('carpro_selected_rate')):

			$_RATE = WC()->session->get('carpro_selected_rate');

			$item->update_meta_data( 'Daily Rate', wc_price($_RATE['pd']));

		endif;

	}










	/* WOOCOMMERCE TEMPLATE OVERRIDE */
	public function woocommerce_locate_template($template, $template_name, $template_path){

		$_PLUGIN_PATH = trailingslashit(trailingslashit(ABSPATH).'wp-content/plugins/nextlevel-carpro/woocommerce');

		$_NEW_FILE = $_PLUGIN_PATH.$template_name;

		if(file_exists($_NEW_FILE)):
			$template = $_NEW_FILE;
		endif;

		return $template;
	}










	/* WOOCOMMERCE TEMPLATE OVERRIDE */
	public function wc_get_template_part($template, $slug, $name){

		$_ORIGINAL = trailingslashit(ABSPATH).'wp-content/plugins/woocommerce/templates/';

		$_NEW = trailingslashit(ABSPATH).'wp-content/plugins/nextlevel-carpro/woocommerce/';

		$_NEW_FILE = str_replace($_ORIGINAL, $_NEW, $template);

		if(file_exists($_NEW_FILE)):
			$template = $_NEW_FILE;
		endif;

		return $template;
	}










	/* CLEAR SESSION  */
	public function woocommerce_thankyou($_ORDER_ID){
		CARPRO_HELPERS::CLEAR_CARPRO();

		$_GTM = get_field('datalayer', 'option');

		if($_GTM):

			$_ORDER = wc_get_order($_ORDER_ID);
			$_OBJ = get_post($_ORDER_ID);

			if(get_field('carpro_reservation_number', $_OBJ)):

				$_VEHICLES = $_ORDER->get_items();
				$_VEHICLE = reset($_VEHICLES);

				$_PRODUCT_ID = $_VEHICLE['product_id'];
				$_PROD = wc_get_product($_PRODUCT_ID);

				$_RESERVATION_NUMBER 	= get_field('carpro_reservation_number', $_OBJ);
				$_TOTAL 				= get_field('rental_amount', $_OBJ);
				$_VEHICLE_TYPE 			= $_PROD->get_sku();
				$_VEHICLE_NAME 			= $_PROD->get_name();
				$_PICKUP_LOCATION 		= get_field('carpro_out_branch', $_OBJ);

				if(!get_field('enable_dataLayer', 'option')):
					?>
						<!--
					<?php
				endif;

				?>

				<script>
					window.dataLayer = window.dataLayer || [];

					window.dataLayer.push({
					  event: 'purchase',
					  ecommerce: {
					    currency: 'ZAR',
					    value: <?php echo $_TOTAL; ?>,
					    tax: 0.00,
					    shipping: 0.00,
					    transaction_id: '<?php echo $_RESERVATION_NUMBER; ?>',
					    items: [{
					      item_name: '<?php echo $_VEHICLE_TYPE; ?>',
					      item_id: <?php echo $_PRODUCT_ID; ?>,
					      price: <?php echo $_TOTAL; ?>,
					      quantity: 1
					    }]
					  },
					  enhanced_conversion_data: {
					      email: '<?php echo $_ORDER->get_billing_email(); ?>',  
					      phone_number: '<?php echo $_ORDER->get_billing_phone(); ?>',
					      first_name: '<?php echo $_ORDER->get_billing_first_name(); ?>',
					      last_name: '<?php echo $_ORDER->get_billing_last_name(); ?>',
					      street: '<?php echo $_ORDER->get_shipping_address_1(); ?>, <?php echo $_ORDER->get_shipping_address_2(); ?>',
					      city: '<?php echo $_ORDER->get_shipping_city(); ?>',
					      region: '<?php echo $_ORDER->get_shipping_state(); ?>',
					      postal_code: '<?php echo $_ORDER->get_shipping_postcode(); ?>',
					      country: '<?php echo $_ORDER->get_shipping_country(); ?>'
					    }
					});
				</script>

				<?php

				if(!get_field('enable_dataLayer', 'option')):
					?>
						-->
					<?php
				endif;

			endif;

		endif;


		
	}










	/* CHECKOUT ADD FIELDS */
	public function woocommerce_checkout_fields($_FIELDS){

		$_FIELDS['billing']['billing_identification_type'] = array(
		    'label'     => __('Identification Type', 'woocommerce'),
		    'type'		=> 'select',
		    'required'  => true,
		    'class'     => array('form-row-wide', 'billing_id_number'),
		    'clear'     => true,
		    'priority'  => 25,
		    'options'   => array('id' => "ID Number", 'passport' => 'Passport Number')
		);

		$_FIELDS['billing']['billing_id_passport'] = array(
		    'label'     => __('ID/Passport Number', 'woocommerce'),
		    'type'		=> 'text',
		    'required'  => true,
		    'class'     => array('form-row-wide', 'billing_id_number'),
		    'clear'     => true,
		    'priority'  => 26
		);


		return $_FIELDS;



	}










	/* CHANGE CART RETURN TO SHOP REDIRECT */
	public function woocommerce_return_to_shop_redirect($_URL){

		$_URL = get_field('search_results_page', 'option');

		return $_URL;

	}










	/* DISABLE AUTOCOMPLETE GFORM  */
	public function gform_form_tag($_INPUT){
		return str_replace( '>', ' autocomplete="off"><input autocomplete="false" name="hidden" type="text" style="display:none;">', $_INPUT );
	}










	/* DISABLE AUTOCOMPLETE GFORM */
	public function gform_field_content($_INPUT){
		return preg_replace( '/<(input|textarea)/', '<${1} autocomplete="off" ', $_INPUT );
	}










	/* FILTER NAMES BASED ON RATES */
	public function the_title($_TITLE, $_ID){

		if(!is_admin() && WC()->session){

			if(WC()->session->get('carpro_availability')):

				$_PROD = get_post($_ID);

				if($_PROD->post_type=='product' && !is_admin()):

					$_PRODUCT = wc_get_product($_ID);

					$_SKU = $_PRODUCT->get_sku();

					$_VEH = CARPRO_HELPERS::VEHICLE_DATA($_SKU);

					if($_VEH):
						$_TITLE = $_VEH['title'];
					endif;

				endif;
			else:

				$_PROD = get_post($_ID);

				if($_PROD->post_type=='product' && !is_admin()):

					$_TITLE = get_field('fallback_name', $_PROD);

				endif;

			endif;

		}elseif(!is_admin()){

			$_PROD = get_post($_ID);

			if($_PROD->post_type=='product' && !is_admin()):

				$_TITLE = get_field('fallback_name', $_PROD);

			endif;
		}


		return $_TITLE;
	}










	/* FILTER NAMES BASED ON RATES */
	public function woocommerce_cart_item_name($_NAME, $_ITEM){
		$_SKU = $_ITEM['data']->get_sku();

		$_VEH = CARPRO_HELPERS::VEHICLE_DATA($_SKU);

		if($_VEH):
			$_NAME .= ' - '.$_DATA['title'];
		endif;

		return $_NAME;
	}










	/* FILTER NAMES BASED ON RATES */
	public function woocommerce_before_calculate_totals($_CART){

		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		foreach ( $_CART->get_cart() as $_KEY => $_ITEM ) {
			$_TITLE = $_ITEM['data']->get_name();
			$_SKU = $_ITEM['data']->get_sku();

			$_VEH = CARPRO_HELPERS::VEHICLE_DATA($_SKU);

			if($_VEH):
				$_TITLE .= ' - '.$_VEH['title'];
			endif;


			$_ITEM[ 'data' ]->set_name( $_TITLE );
		}

	}










	/* ADD CUSTOM CHECKOUT TEXT */
	public function carpro_before_payment(){
		if(get_field('checkout_text', 'option')):
			?>

			<div id="CARPROCHECKOUTTEXT">
				<?php the_field('checkout_text', 'option'); ?>
			</div>

			<?php
		endif;
	}










	/* RENAME ORDERS ON ACCOUNT PAGE */
	public function woocommerce_account_menu_items($_MENU){

		$_MENU['orders'] = __('Bookings', 'woocommerce');
		return $_MENU;
	}










	/* REMOVE MODEL PERMALINK ON CHECKOUT */
	public function woocommerce_order_item_permalink($_PERMALINK, $_ITEM, $_ORDER){
		return false;
	}





}
