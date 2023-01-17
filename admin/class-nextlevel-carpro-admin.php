<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.kri8it.com
 * @since      1.0.0
 *
 * @package    Nextlevel_Carpro
 * @subpackage Nextlevel_Carpro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Nextlevel_Carpro
 * @subpackage Nextlevel_Carpro/admin
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class Nextlevel_Carpro_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nextlevel-carpro-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nextlevel-carpro-admin.js', array( 'jquery' ), $this->version, false );

	}










	/* MAKE SURE OUR ORDERING COMES THROUGH */
	public function parse_query($_QUERY){

		if($_QUERY->is_main_query()):

			if(is_post_type_archive('branch') || is_post_type_archive('product')):
				$_QUERY->set('posts_per_page', '-1');
				$_QUERY->set('orderby', 'menu_order');
				$_QUERY->set('order', 'ASC');
			endif;

		endif;

		return $_QUERY;

	}










	/* HEADER FUNCTIONS */
	public function wp_head(){

		$_GTM = get_field('datalayer', 'option');

		if($_GTM):

			if(!get_field('enable_dataLayer', 'option')):
				?>
					<!--
				<?php
			endif;

			?>

			<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $_GTM; ?>"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', '<?php echo $_GTM; ?>'); </script>

			<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $_GTM; ?>"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

			<?php


			if(!get_field('enable_dataLayer', 'option')):
				?>
					-->
				<?php
			endif;

		endif;

	}










	/* ADD BODY CLASS FOR IF A SEARCH IS ACTIVE */
	public function body_class($_CLASSES){

		if(!is_admin()):

			if(WC()->session->get('carpro_availability')):

				$_CLASSES[] = 'carproActive';

			endif;

		endif;

		return $_CLASSES;


	}









	/* HTTP TIMEOUT */
	public function http_request_timeout(){
		return 6000;
	}









	/* Add Additional Cron Timings */
	public function cron_schedules($schedules){

		
		$schedules['twohours'] = array(
	        'interval' => 7200,
	        'display'  => esc_html__( 'Every Two Hours' ),
	    );

		$schedules['fiveminutes'] = array(
	        'interval' => 300,
	        'display'  => esc_html__( 'Every Five Minutes' ),
	    );

		return $schedules;
		
	}










	/* Setup Cron Schedules */
	public function setup_cron_schedules(){

		$_INTERVAL = 'fiveminutes';

		if (! wp_next_scheduled( 'nextlevel_clean_logs_action')):

			wp_schedule_event(time(), 'daily', 'nextlevel_clean_logs_action');

		endif;

		if(get_field('carpro_sync_branches', 'option')):

			if (! wp_next_scheduled( 'carpro_sync_branch_action')):

				wp_schedule_event(time(), $_INTERVAL, 'carpro_sync_branch_action');

			endif;

		else:

			if (wp_next_scheduled( 'carpro_sync_branch_action')):

				wp_clear_scheduled_hook('carpro_sync_branch_action');
				
			endif;

		endif;


		if(get_field('carpro_sync_vehicles', 'option')):
		
			if (! wp_next_scheduled( 'carpro_sync_vehicle_action')):

				wp_schedule_event(time(), $_INTERVAL, 'carpro_sync_vehicle_action');

			endif;

		else:

			if (wp_next_scheduled( 'carpro_sync_vehicle_action')):

				wp_clear_scheduled_hook('carpro_sync_vehicle_action');
				
			endif;

		endif;
		
		if (! wp_next_scheduled( 'carpro_sync_publicholiday_action')):

			wp_schedule_event(time(), $_INTERVAL, 'carpro_sync_publicholiday_action');

		endif;

		
	}










	/* ACF Custom Fields */
	public function custom_fields(){

		if( function_exists('acf_add_options_page') ) {
  
		   $main_menu = acf_add_options_page(array(
		    'page_title'  => 'CARPRO',
		    'menu_title'  => 'CARPRO',
		    'icon_url' => 'dashicons-admin-site-alt'
		  ));

		}


		if( function_exists('acf_add_local_field_group') ):



			//BRANCHES
			acf_add_local_field_group(array(
			'key' => 'group_5ea95862013be',
			'title' => 'BRANCH CONTROL',
			'fields' => array(
				array(
					'key' => 'field_618d05b1de916',
					'label' => 'Enabled',
					'name' => 'enabled',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),
				array(
					'key' => 'field_5ea976de3428e',
					'label' => 'CarPro Branch Code',
					'name' => 'carpro_branch_code',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_617133ca24268',
					'label' => 'Contact Person',
					'name' => 'contact_person',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_617133d924269',
					'label' => 'Contact Email',
					'name' => 'contact_email',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_617133fc2426a',
					'label' => 'Contact Number',
					'name' => 'contact_number',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_617134062426b',
					'label' => 'Address',
					'name' => 'address',
					'type' => 'wysiwyg',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'tabs' => 'all',
					'toolbar' => 'full',
					'media_upload' => 0,
					'delay' => 0,
				),
				array(
					'key' => 'field_6171341e2426d',
					'label' => 'City',
					'name' => 'city',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_61efbae2ff36a',
					'label' => 'Longitude',
					'name' => 'longitude',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_617135902426f',
					'label' => 'Latitude',
					'name' => 'latitude',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_6171359e24270',
					'label' => 'Monday',
					'name' => 'monday',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_617135a524271',
					'label' => 'Tuesday',
					'name' => 'tuesday',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_617135ab24272',
					'label' => 'Wednesday',
					'name' => 'wednesday',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_617135b024273',
					'label' => 'Thursday',
					'name' => 'thursday',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_617135b424274',
					'label' => 'Friday',
					'name' => 'friday',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_617135b824275',
					'label' => 'Saturday',
					'name' => 'saturday',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_617135bd24276',
					'label' => 'Sunday',
					'name' => 'sunday',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_617135c324277',
					'label' => 'Public Holidays',
					'name' => 'public_holidays',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_61f2671190773',
					'label' => 'Minimum Driver Age',
					'name' => 'minimum_driver_age',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
				),
				array(
					'key' => 'field_61f2671f90774',
					'label' => 'Parking Available',
					'name' => 'parking_available',
					'type' => 'select',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'no' => 'No',
						'yes' => 'Yes',
					),
					'default_value' => false,
					'allow_null' => 0,
					'multiple' => 0,
					'ui' => 0,
					'return_format' => 'value',
					'ajax' => 0,
					'placeholder' => '',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'branch',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
		));











			// PUBLIC HOLIDAYS
			acf_add_local_field_group(array(
				'key' => 'group_6179298a0fa12',
				'title' => 'PUBLIC HOLIDAY CONTROL',
				'fields' => array(
					array(
						'key' => 'field_61792995df916',
						'label' => 'Country Code',
						'name' => 'country_code',
						'type' => 'text',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'maxlength' => '',
					),
					array(
						'key' => 'field_6179299cdf917',
						'label' => 'Date',
						'name' => 'date',
						'type' => 'date_picker',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'display_format' => 'Y-m-d',
						'return_format' => 'Y-m-d',
						'first_day' => 1,
					),
				),
				'location' => array(
					array(
						array(
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'publicholiday',
						),
					),
				),
				'menu_order' => 0,
				'position' => 'normal',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen' => '',
				'active' => true,
				'description' => '',
			));












			//VEHICLES
			//CHANGE THE POST TYPE TO 'product'
			if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_5ea9586e7b388',
	'title' => 'VEHICLE CONTROL',
	'fields' => array(
		array(
			'key' => 'field_5ea97c3d5d5d2',
			'label' => 'Vehicle Code',
			'name' => 'vehicle_code',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_63903bf5fd247',
			'label' => 'Fallback Name',
			'name' => 'fallback_name',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_5ea97c485d5d3',
			'label' => 'Enabled',
			'name' => 'enabled',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_619cc251579c3',
			'label' => 'Image',
			'name' => 'image',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5ea97c595d5d4',
			'label' => 'On Promotion',
			'name' => 'on_promotion',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5ea97c6c5d5d6',
			'label' => 'Transmission',
			'name' => 'transmission',
			'aria-label' => '',
			'type' => 'select',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'manual' => 'Manual',
				'automatic' => 'Automatic',
			),
			'default_value' => false,
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_5ea97caa5d5d9',
			'label' => 'Fuel',
			'name' => 'fuel',
			'aria-label' => '',
			'type' => 'select',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'petrol' => 'Petrol',
				'diesel' => 'Diesel',
			),
			'default_value' => false,
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_5ea97c635d5d5',
			'label' => 'Air Conditioning',
			'name' => 'air_conditioning',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5ea97c925d5d7',
			'label' => 'Airbags',
			'name' => 'airbags',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_5ea97cc35d5da',
			'label' => 'Doors',
			'name' => 'doors',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_5ea97ccc5d5db',
			'label' => 'Seats',
			'name' => 'seats',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_63c6a02aa84fd',
			'label' => 'Custom Includes/Excludes',
			'name' => 'custom_includesexcludes',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => 'Contract Fee text will still be added automatically so that the fee can stay updated automatically.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'product',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
));

endif;			









			if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_6177efe08ec88',
	'title' => 'CARPRO Settings',
	'fields' => array(
		array(
			'key' => 'field_61828fdc144f1',
			'label' => 'General',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_6177eff3e88b9',
			'label' => 'CARPRO NEXTLEVEL URL',
			'name' => 'carpro_nextlevel_url',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_6177effde88ba',
			'label' => 'CARPRO NEXTLEVEL KEY',
			'name' => 'carpro_nextlevel_key',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_6177f01ae88bb',
			'label' => 'CARPRO Sync Branches',
			'name' => 'carpro_sync_branches',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_6177f021e88bc',
			'label' => 'CARPRO Sync Vehicles',
			'name' => 'carpro_sync_vehicles',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_61b07f6655f88',
			'label' => 'CARPRO Debug Session',
			'name' => 'carpro_debug_session',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_62f3a103c9ab7',
			'label' => 'CARPRO Debug Rates',
			'name' => 'carpro_debug_rates',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_618d08ec5cc34',
			'label' => 'Pages',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_61a9c991755a0',
			'label' => 'Search Results Page',
			'name' => 'search_results_page',
			'aria-label' => '',
			'type' => 'page_link',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'post_type' => array(
				0 => 'page',
			),
			'taxonomy' => '',
			'allow_null' => 0,
			'allow_archives' => 0,
			'multiple' => 0,
		),
		array(
			'key' => 'field_61a9c9847559f',
			'label' => 'Bookings',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_62fb837a6d52f',
			'label' => 'Enable Timer',
			'name' => 'enable_timer',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_62fb83846d530',
			'label' => 'Timer Length',
			'name' => 'timer_length',
			'aria-label' => '',
			'type' => 'number',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_62fb837a6d52f',
						'operator' => '==',
						'value' => '1',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 15,
			'placeholder' => 15,
			'prepend' => '',
			'append' => '',
			'min' => '',
			'max' => '',
			'step' => '',
		),
		array(
			'key' => 'field_62fe2f0962374',
			'label' => 'Booking Lead Hours',
			'name' => 'booking_lead_hours',
			'aria-label' => '',
			'type' => 'number',
			'instructions' => 'Hours from NOW - same day - that a booking can happen',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => 1,
			'max' => '',
			'step' => '',
		),
		array(
			'key' => 'field_618d08f25cc35',
			'label' => 'Booking Lead Days',
			'name' => 'booking_lead_days',
			'aria-label' => '',
			'type' => 'number',
			'instructions' => 'Days from Today that a booking can happen',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => '',
			'max' => '',
			'step' => '',
		),
		array(
			'key' => 'field_62fe30458a18b',
			'label' => 'Booking Default Day',
			'name' => 'booking_default_day',
			'aria-label' => '',
			'type' => 'number',
			'instructions' => 'Default Day from Lead Day.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => '',
			'max' => '',
			'step' => '',
		),
		array(
			'key' => 'field_618d09035cc36',
			'label' => 'Booking Minimum Length',
			'name' => 'booking_minimum_length',
			'aria-label' => '',
			'type' => 'number',
			'instructions' => 'Minimum Length of a booking. ZERO will disable.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => '',
			'max' => '',
			'step' => '',
		),
		array(
			'key' => 'field_618d09105cc37',
			'label' => 'Booking Maximum Length',
			'name' => 'booking_maximum_length',
			'aria-label' => '',
			'type' => 'number',
			'instructions' => 'Maximum Length of a booking. ZERO will disable.',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => '',
			'max' => '',
			'step' => '',
		),
		array(
			'key' => 'field_62b1d4e625e53',
			'label' => 'Default Branch Time',
			'name' => 'default_branch_time',
			'aria-label' => '',
			'type' => 'time_picker',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'display_format' => 'H:i',
			'return_format' => 'H:i',
		),
		array(
			'key' => 'field_62fb760da8ee3',
			'label' => 'Checkout Text',
			'name' => 'checkout_text',
			'aria-label' => '',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 0,
			'delay' => 0,
		),
		array(
			'key' => 'field_62fb7472a382d',
			'label' => 'Enable Payment Type',
			'name' => 'enable_payment_type',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_6371f78528f87',
			'label' => 'Payment Types',
			'name' => 'payment_types',
			'aria-label' => '',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'table',
			'pagination' => 0,
			'min' => 0,
			'max' => 0,
			'collapsed' => '',
			'button_label' => 'Add Row',
			'rows_per_page' => 20,
			'sub_fields' => array(
				array(
					'key' => 'field_6371f79328f88',
					'label' => 'Percentage',
					'name' => 'percentage',
					'aria-label' => '',
					'type' => 'number',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'min' => '',
					'max' => '',
					'placeholder' => '',
					'step' => '',
					'prepend' => '',
					'append' => '',
					'parent_repeater' => 'field_6371f78528f87',
				),
				array(
					'key' => 'field_6371f86101af8',
					'label' => 'Label',
					'name' => 'label',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'maxlength' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'parent_repeater' => 'field_6371f78528f87',
				),
			),
		),
		array(
			'key' => 'field_62c5381528725',
			'label' => 'Vehicle Text',
			'name' => 'vehicle_text',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_62c537d228722',
			'label' => 'Vehicle Text Builder',
			'name' => 'vehicle_text_builder',
			'aria-label' => '',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => 'Add Row',
			'sub_fields' => array(
				array(
					'key' => 'field_62c537de28723',
					'label' => 'Code',
					'name' => 'code',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'parent_repeater' => 'field_62c537d228722',
				),
				array(
					'key' => 'field_62c5380b28724',
					'label' => 'Text',
					'name' => 'text',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'parent_repeater' => 'field_62c537d228722',
				),
			),
			'rows_per_page' => 20,
		),
		array(
			'key' => 'field_62f3a116c9ab8',
			'label' => 'Booking Includes Items',
			'name' => 'booking_includes_items',
			'aria-label' => '',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => 'Add Row',
			'sub_fields' => array(
				array(
					'key' => 'field_62f3a116c9aba',
					'label' => 'Text',
					'name' => 'text',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'parent_repeater' => 'field_62f3a116c9ab8',
				),
			),
			'rows_per_page' => 20,
		),
		array(
			'key' => 'field_62fa3f2d133f6',
			'label' => 'Extra Tooltips',
			'name' => 'extra_tooltips',
			'aria-label' => '',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'table',
			'button_label' => 'Add Row',
			'sub_fields' => array(
				array(
					'key' => 'field_62fa3f39133f8',
					'label' => 'Code',
					'name' => 'code',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'parent_repeater' => 'field_62fa3f2d133f6',
				),
				array(
					'key' => 'field_62fa3f2d133f7',
					'label' => 'Text',
					'name' => 'text',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'parent_repeater' => 'field_62fa3f2d133f6',
				),
			),
			'rows_per_page' => 20,
		),
		array(
			'key' => 'field_62fcbceca78d5',
			'label' => 'Checkout Extra Sections',
			'name' => 'checkout_extra_sections',
			'aria-label' => '',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => 0,
			'max' => 0,
			'layout' => 'block',
			'button_label' => 'Add Row',
			'sub_fields' => array(
				array(
					'key' => 'field_62fcbceca78d6',
					'label' => 'Section',
					'name' => 'section',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
					'parent_repeater' => 'field_62fcbceca78d5',
				),
				array(
					'key' => 'field_62fcbceca78d7',
					'label' => 'Items',
					'name' => 'items',
					'aria-label' => '',
					'type' => 'repeater',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'collapsed' => '',
					'min' => 0,
					'max' => 0,
					'layout' => 'table',
					'button_label' => 'Add Row',
					'sub_fields' => array(
						array(
							'key' => 'field_62fcbd0ca78d8',
							'label' => 'Title',
							'name' => 'title',
							'aria-label' => '',
							'type' => 'text',
							'instructions' => '',
							'required' => 1,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'maxlength' => '',
							'parent_repeater' => 'field_62fcbceca78d7',
						),
						array(
							'key' => 'field_62fcbe6c198d9',
							'label' => 'Cost',
							'name' => 'cost',
							'aria-label' => '',
							'type' => 'number',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder' => '',
							'prepend' => '',
							'append' => '',
							'min' => '',
							'max' => '',
							'step' => '',
							'parent_repeater' => 'field_62fcbceca78d7',
						),
						array(
							'key' => 'field_62fcbd15a78d9',
							'label' => 'Above Content',
							'name' => 'above_content',
							'aria-label' => '',
							'type' => 'wysiwyg',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 0,
							'delay' => 0,
							'parent_repeater' => 'field_62fcbceca78d7',
						),
						array(
							'key' => 'field_62fcbe80198da',
							'label' => 'Below Content',
							'name' => 'below_content',
							'aria-label' => '',
							'type' => 'wysiwyg',
							'instructions' => '',
							'required' => 0,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'tabs' => 'all',
							'toolbar' => 'full',
							'media_upload' => 1,
							'delay' => 0,
							'parent_repeater' => 'field_62fcbceca78d7',
						),
					),
					'rows_per_page' => 20,
					'parent_repeater' => 'field_62fcbceca78d5',
				),
			),
			'rows_per_page' => 20,
		),
		array(
			'key' => 'field_6192473b902b9',
			'label' => 'Styling',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_6192474c902ba',
			'label' => 'Overlay Background Colour',
			'name' => 'overlay_background_colour',
			'aria-label' => '',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'rgba(255,255,255,0.75)',
			'enable_opacity' => 1,
			'return_format' => 'string',
		),
		array(
			'key' => 'field_6192474c902bc',
			'label' => 'Timer Background Colour',
			'name' => 'timer_background_colour',
			'aria-label' => '',
			'type' => 'color_picker',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'rgba(0,0,0,0.75)',
			'enable_opacity' => 1,
			'return_format' => 'string',
		),
		array(
			'key' => 'field_6192477d902bd',
			'label' => 'Overlay Loading Gif',
			'name' => 'overlay_loading_gif',
			'aria-label' => '',
			'type' => 'image',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'url',
			'preview_size' => 'medium',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array(
			'key' => 'field_63988ad89faec',
			'label' => 'Booking Session Expired Title',
			'name' => 'booking_session_expired_title',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_63988aee9faed',
			'label' => 'Booking Session Expired Text',
			'name' => 'booking_session_expired_text',
			'aria-label' => '',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 1,
			'delay' => 0,
		),
		array(
			'key' => 'field_63988b119faef',
			'label' => 'Booking Session Button Text',
			'name' => 'booking_session_button_text',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_63988b549faf0',
			'label' => 'Booking Session Button Link',
			'name' => 'booking_session_button_link',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_639891f9cfdeb',
			'label' => 'Booking Session Homepage Timeout',
			'name' => 'booking_session_homepage_timeout',
			'aria-label' => '',
			'type' => 'number',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 15,
			'min' => '',
			'max' => '',
			'placeholder' => '',
			'step' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_61efcd4f57387',
			'label' => 'Map',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_61efcd5557388',
			'label' => 'Google Map API Key',
			'name' => 'google_map_api_key',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_61efcf7a44c0d',
			'label' => 'Single Map Zoom',
			'name' => 'single_map_zoom',
			'aria-label' => '',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 10,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => 1,
			'max' => 20,
			'step' => 1,
		),
		array(
			'key' => 'field_61efcfe444c0f',
			'label' => 'Single Map Size',
			'name' => 'single_map_size',
			'aria-label' => '',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 300,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => '',
			'max' => '',
			'step' => '',
		),
		array(
			'key' => 'field_61efcfcf44c0e',
			'label' => 'Multiple Map Zoom',
			'name' => 'multiple_map_zoom',
			'aria-label' => '',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 10,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => 1,
			'max' => 20,
			'step' => 1,
		),
		array(
			'key' => 'field_61efcff144c10',
			'label' => 'Multiple Map Size',
			'name' => 'multiple_map_size',
			'aria-label' => '',
			'type' => 'number',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 300,
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'min' => '',
			'max' => '',
			'step' => '',
		),
		array(
			'key' => 'field_61efd9697060a',
			'label' => 'Map Marker Image',
			'name' => 'map_marker_image',
			'aria-label' => '',
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'url',
			'preview_size' => 'medium',
			'library' => 'all',
			'min_width' => '',
			'min_height' => '',
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array(
			'key' => 'field_61fa33ff21a38',
			'label' => 'Tracking',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_61fa34ca74fc1',
			'label' => 'Enable DataLayer',
			'name' => 'enable_dataLayer',
			'aria-label' => '',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => '',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_61fa34e574fc2',
			'label' => 'DataLayer',
			'name' => 'datalayer',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_62c537a128721',
			'label' => 'Payments',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_62bac66d236c1',
			'label' => 'Custom Field Key For Credit Card Number',
			'name' => 'custom_field_key_for_credit_card_number',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_62bac68e236c3',
			'label' => 'Custom Field Key For Credit Card Expiry Month',
			'name' => 'custom_field_key_for_credit_card_expiry_month',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_62bac69f236c4',
			'label' => 'Custom Field Key For Credit Card Expiry Year',
			'name' => 'custom_field_key_for_credit_card_expiry_year',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_635946298ce46',
			'label' => 'Custom Field Key For Credit Card CVV',
			'name' => 'custom_field_key_for_credit_card_cvv',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_62bac680236c2',
			'label' => 'Custom Field Key For Credit Card Auth Code',
			'name' => 'custom_field_key_for_credit_card_auth_code',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_635a8846ac583',
			'label' => 'Custom Field Key For Credit Card Name',
			'name' => 'custom_field_key_for_credit_card_name',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_62fb75aeeb595',
			'label' => 'Emails',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_6356850f1783a',
			'label' => 'Booking Failure Email Recipients',
			'name' => 'booking_failure_email_recipients',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_63568ae108a26',
			'label' => 'Booking Failure Email Recipients BCC',
			'name' => 'booking_failure_email_recipients_bcc',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_6356852a1783b',
			'label' => 'Booking Failure Customer Email To Contact',
			'name' => 'booking_failure_customer_email_to_contact',
			'aria-label' => '',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'maxlength' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
		),
		array(
			'key' => 'field_62fb75b4eb596',
			'label' => 'Email Instructions',
			'name' => 'email_instructions',
			'aria-label' => '',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 0,
			'delay' => 0,
		),
		array(
			'key' => 'field_63062b4da12ac',
			'label' => 'Email After Details Table',
			'name' => 'email_after_details_table',
			'aria-label' => '',
			'type' => 'wysiwyg',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'tabs' => 'all',
			'toolbar' => 'full',
			'media_upload' => 1,
			'delay' => 0,
		),
		array(
			'key' => 'field_6393264e5fd0a',
			'label' => 'Limitations',
			'name' => '',
			'aria-label' => '',
			'type' => 'tab',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'placement' => 'top',
			'endpoint' => 0,
		),
		array(
			'key' => 'field_6393265f5fd0b',
			'label' => 'Limit Branches on Specific Pages',
			'name' => 'limit_branches_on_specific_pages',
			'aria-label' => '',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'table',
			'pagination' => 0,
			'min' => 0,
			'max' => 0,
			'collapsed' => '',
			'button_label' => 'Add Row',
			'rows_per_page' => 20,
			'sub_fields' => array(
				array(
					'key' => 'field_6393267d5fd0c',
					'label' => 'Page',
					'name' => 'page',
					'aria-label' => '',
					'type' => 'post_object',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'post_type' => '',
					'taxonomy' => '',
					'return_format' => 'id',
					'multiple' => 0,
					'allow_null' => 0,
					'ui' => 1,
					'parent_repeater' => 'field_6393265f5fd0b',
				),
				array(
					'key' => 'field_6393269f5fd0d',
					'label' => 'Branches',
					'name' => 'branches',
					'aria-label' => '',
					'type' => 'relationship',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'post_type' => array(
						0 => 'branch',
					),
					'taxonomy' => '',
					'filters' => '',
					'return_format' => 'id',
					'min' => '',
					'max' => '',
					'elements' => '',
					'parent_repeater' => 'field_6393265f5fd0b',
				),
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options-carpro',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'show_in_rest' => 0,
));

endif;			










	

		//ORDERS
		acf_add_local_field_group(array(
			'key' => 'group_619f508571575',
			'title' => 'ORDER INFORMATION',
			'fields' => array(
				array(
					'key' => 'field_619f50b90b3a3',
					'label' => 'Reservation Number',
					'name' => 'carpro_reservation_number',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_619f50df0b3a4',
					'label' => 'Selected Vehicle',
					'name' => 'carpro_selected_vehicle',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_619f50f70b3a5',
					'label' => 'Selected Code',
					'name' => 'carpro_selected_code',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_619f5255e89fc',
					'label' => 'Selected Rate No',
					'name' => 'carpro_selected_rate_no',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_619f526be89fd',
					'label' => 'Selected Rate SRNo',
					'name' => 'carpro_selected_rate_srno',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_619f51550b3ac',
					'label' => 'Selected KM',
					'name' => 'carpro_selected_km',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_619f510f0b3a6',
					'label' => 'Out Branch',
					'name' => 'carpro_out_branch',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_619f51180b3a7',
					'label' => 'Out Date',
					'name' => 'carpro_out_date',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_619f51220b3a8',
					'label' => 'Out Time',
					'name' => 'carpro_out_time',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_619f512b0b3a9',
					'label' => 'In Branch',
					'name' => 'carpro_in_branch',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_619f51330b3aa',
					'label' => 'In Date',
					'name' => 'carpro_in_date',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_619f513a0b3ab',
					'label' => 'In Time',
					'name' => 'carpro_in_time',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_619f8815dce59',
					'label' => 'Deposit Percentage',
					'name' => 'carpro_deposit_percentage',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_619f8821dce5a',
					'label' => 'Deposit Amount',
					'name' => 'carpro_deposit_amount',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_61a887860d124',
					'label' => 'Rental Amount',
					'name' => 'rental_amount',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'shop_order',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'side',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
		));

		endif;				
		
	}










	/* POST TYPES */
	public function post_types(){

		if(get_field('carpro_sync_branches', 'option')):
		
		$labels = array(
		    'name' => _x( 'Branches', 'post type general name', 'carpro' ),
		    'singular_name' => _x( 'Branch', 'post type singular name', 'carpro' ),
		    'add_new' => _x( 'Add New', 'slider', 'carpro' ),
		    'add_new_item' => __( 'Add Branch', 'carpro' ),
		    'edit_item' => __( 'Edit Branch', 'carpro' ),
		    'new_item' => __( 'New Branch', 'carpro' ),
		    'view_item' => __( 'View Branch', 'carpro' ),
		    'search_items' => __( 'Search Branches', 'carpro' ),
		    'not_found' =>  __( 'No Branches found', 'carpro' ),
		    'not_found_in_trash' => __( 'No Branches found in Trash', 'carpro' ), 
		    'parent_item_colon' => ''
		  );
		  
		  $rewrite = 'locations';
		  
		  $args = array(
		    'labels' => $labels,
		    'public' => true,
		    'publicly_queryable' => true,
		    'show_ui' => true, 
		    'query_var' => true,
		    'rewrite' => array( 'slug' => $rewrite ),
		    'capability_type' => 'post',
		    'hierarchical' => false,
		    'menu_position' => null, 
		    'menu_icon' => 'dashicons-admin-site-alt',
		    'has_archive' => true, 
		    'show_in_rest' => true,
		    'supports' => array('title', 'thumbnail', 'editor'),
    		'taxonomies' => array( 'province'), 
		  );
		      
		  register_post_type( 'branch', $args );

	      $labels = array(
		    'name' => _x( 'Province', 'taxonomy general name', 'carpro' ),
		    'singular_name' => _x( 'Province', 'taxonomy singular name','carpro' ),
		    'search_items' =>  __( 'Search Provinces', 'carpro' ),
		    'all_items' => __( 'All Provinces', 'carpro' ),
		    'parent_item' => __( 'Parent Province', 'carpro' ),
		    'parent_item_colon' => __( 'Parent Province:', 'carpro' ),
		    'edit_item' => __( 'Edit Province', 'carpro' ), 
		    'update_item' => __( 'Update Province', 'carpro' ),
		    'add_new_item' => __( 'Add New Province', 'carpro' ),
		    'new_item_name' => __( 'New Province Name', 'carpro' ),
		    'menu_name' => __( 'Provinces', 'carpro' )
		  );  
		  
		  $args = array(
		    'hierarchical' => true,
		    'show_admin_column' => true,
		    'labels' => $labels,
		    'show_ui' => true,
		    'query_var' => true,
		    'rewrite' => array( 'slug' => 'province', 'hierarchical' => true, 'with_front' => false )
		  );
		  
		  register_taxonomy( 'province', array( 'branch' ), $args );

		endif;


		$labels = array(
		    'name' => _x( 'Public Holidays', 'post type general name', 'carpro' ),
		    'singular_name' => _x( 'Public Holiday', 'post type singular name', 'carpro' ),
		    'add_new' => _x( 'Add New', 'slider', 'carpro' ),
		    'add_new_item' => __( 'Add Public Holiday', 'carpro' ),
		    'edit_item' => __( 'Edit Public Holiday', 'carpro' ),
		    'new_item' => __( 'New Public Holiday', 'carpro' ),
		    'view_item' => __( 'View Public Holiday', 'carpro' ),
		    'search_items' => __( 'Search Public Holidays', 'carpro' ),
		    'not_found' =>  __( 'No Public Holidays found', 'carpro' ),
		    'not_found_in_trash' => __( 'No Public Holidays found in Trash', 'carpro' ), 
		    'parent_item_colon' => ''
		  );
		  
		  $rewrite = 'publicholiday';
		  
		  $args = array(
		    'labels' => $labels,
		    'public' => false,
		    'publicly_queryable' => false,
		    'show_ui' => true, 
		    'query_var' => false,
		    'rewrite' => array( 'slug' => $rewrite ),
		    'capability_type' => 'post',
		    'hierarchical' => false,
		    'menu_position' => null, 
		    'menu_icon' => 'dashicons-star-filled',
		    'has_archive' => false, 
		    'show_in_rest' => true,
		    'supports' => array('title') 
		  );
		      
		  register_post_type( 'publicholiday', $args );


		  
		
	}










	/* SYNC BRANCH ACTION */
	public function carpro_sync_branch_action(){

		CARPRO::DOBRANCHES();	

	}










	/* SYNC VEHICLE ACTION */
	public function carpro_sync_vehicle_action(){

		CARPRO::DOVEHICLES();
		
	}










	/* SYNC PUBLIC HOLIDAY */
	public function carpro_sync_publicholiday_action(){

		CARPRO::DOPUBLICHOLIDAYS();
		
	}










	/* CLEAN LOGS */
	public function nextlevel_clean_logs_action(){

		CARPRO_LOG::clean();

	}










	/* PUBLIC HOLIDAY COLUMN HEADER MANAGEMENT */
	public function manage_publicholiday_posts_columns($columns) {
	    unset($columns['date']);
	    $columns['ph_date'] = 'Date';
	    $columns['ph_country'] = 'Country';
	    return $columns;
	}










	/* PUBLIC HOLIDAY EXTRA COLUMN DATA MANAGEMENT */
	public function manage_publicholiday_posts_custom_column($column, $post_id) {

		$_THE_PH = get_post($post_id);

		switch($column):

			case "ph_date":
				the_field('date', $_THE_PH);
			break;

			case "ph_country":	
				the_field('country_code', $_THE_PH);
			break;

		endswitch;

	}





	function manage_shop_order_posts_columns($columns) {
		
		$n_columns = array();
		foreach($columns as $key => $value):

			if($key=='order_date'):
		      $n_columns['order_reservation'] = 'Reservation';
		      $n_columns['order_vehicle_details'] = 'Vehicle Details';
		      $n_columns['order_out_details'] = 'Out Details';
		      $n_columns['order_in_details'] = 'In Details';
		    endif;

		    if($key=='order_total'):
		      $n_columns['order_sum'] = 'Rental Sum';
		    endif;

			$n_columns[$key] = $value;

		endforeach;

		 return $n_columns;

	}




	
	function manage_shop_order_posts_custom_column($column, $post_id) {

		$_THE_ORDER_POST = get_post($post_id);

		switch($column):

			case 'order_reservation':
				if(get_field('carpro_reservation_number', $_THE_ORDER_POST)):
					the_field('carpro_reservation_number', $_THE_ORDER_POST);
				elseif(get_post_meta($post_id, 'carpro_error', true)):
					echo '<span style="color:red">'.get_post_meta($post_id, 'carpro_error', true).'</span>';
				else:
					echo '-';
				endif;
			break;

			case 'order_vehicle_details':
				echo get_field('carpro_selected_vehicle', $_THE_ORDER_POST).' | '.get_field('carpro_selected_code', $_THE_ORDER_POST).' | '.get_field('carpro_selected_km', $_THE_ORDER_POST);
			break;

		    case 'order_out_details':
				echo get_field('carpro_out_branch', $_THE_ORDER_POST).' | '.get_field('carpro_out_date', $_THE_ORDER_POST).' | '.get_field('carpro_out_time', $_THE_ORDER_POST);
			break;

		    case 'order_in_details':
				echo get_field('carpro_in_branch', $_THE_ORDER_POST).' | '.get_field('carpro_in_date', $_THE_ORDER_POST).' | '.get_field('carpro_in_time', $_THE_ORDER_POST);
			break;

		    case 'order_sum':

		    	if(get_field('rental_amount', $_THE_ORDER_POST)):
		    		$_AMT = get_field('rental_amount', $_THE_ORDER_POST);
					echo wc_price($_AMT);
		    	else:
		    		echo '-';
		    	endif;
			break;

		endswitch;

	}










	/* EXTRA MENU ITEMS */
	public function admin_menu(){
		add_menu_page( 
			'CARPRO Logs' , 
			'CARPRO Logs', 
			'edit_posts', 
			'carpro-logs', 
			array($this, 'carpro_log_menu'), 
			'dashicons-privacy'
		);
	}










	/* LOG MENU ITEM FUNCTION */
	public function carpro_log_menu(){
		$_LOGS = CARPRO_LOG::fetch();

		?>


		<script type="text/javascript">
			jQuery(document).ready(function(){
			jQuery('#nextlevelLogSelect').on('change', function(){
				jQuery('#NEXTLEVELLogForm').submit();
			});
			});
		</script>

		<div class="wrap">
			<div class="nl-top-header">
			<h2 class="">CARPRO Logs</h2>

			<form id="CARPROLogForm" method="get">
			<input type="hidden" name="page" value="carpro-logs" />
			<select name="carpro-log-item" id="carproLogSelect">
				<option value="">Please Select</option>
				<?php foreach($_LOGS as $_LOG): ?>

					<?php $_DATE = str_replace('.txt', '', $_LOG); ?>

					<option <?php selected($_DATE, $_GET['carpro-log-item']); ?> value="<?php echo $_DATE; ?>"><?php echo wp_date('F d, Y', strtotime($_DATE)); ?></option>

				<?php endforeach; ?>
			</select>
			</form>
			</div>

			<?php if($_GET['carpro-log-item']): ?>

			<div class="nl-bottom-content">
				
				<?php $_FILE = CARPRO_LOG::link($_GET['carpro-log-item'].'.txt', true); ?>

				<iframe id="carproLogIframe" src="<?php echo $_FILE; ?>" />

			</div>

			<?php endif; ?>
		</div>

		<?php
	}










	/* AJAX: BRANCH TIMES */
	public function carpro_ajax_branch_times(){

		$_BRANCH = $_POST['branch'];
		$_DATE = $_POST['date'];
		$_TYPE = $_POST['type'];

		/* TURN INTO OPTION */
		$_DEFAULT_TIME = get_field('default_branch_time', 'option');

		switch($_TYPE):
			case "InTime":

				if(WC()->session->get('carpro_in_time') && WC()->session->get('carpro_in_time') != ''):
					$_DEFAULT_TIME = WC()->session->get('carpro_in_time');
				endif;

			break;

			case "OutTime":

				if(WC()->session->get('carpro_out_time') && WC()->session->get('carpro_out_time') != ''):
					$_DEFAULT_TIME = WC()->session->get('carpro_out_time');
				endif;

			break;
		endswitch;

		$_DAY = strtolower(date('l', strtotime($_DATE)));

		$_TIMES = CARPRO_HELPERS::BRANCH_TIMES_SELECT($_BRANCH, $_DATE, $_DAY);

		if($_TIMES):

			$_OUTPUT = '';

			foreach($_TIMES as $_VALUE => $_TIME):
				if($_VALUE == $_DEFAULT_TIME):
					$_OUTPUT.= '<option selected="selected" value="'.$_VALUE.'">'.$_TIME.'</option>';
				else:
					$_OUTPUT.= '<option value="'.$_VALUE.'">'.$_TIME.'</option>';
				endif;
			endforeach;

			echo $_OUTPUT;

		else:


		endif;

		exit;


	}










	/* AJAX: AVAILABILITY */
	public function carpro_ajax_do_search(){

		parse_str($_POST['data'],$_POSTED);

		if(!isset($_POSTED['InBranch'])):
			$_POSTED['InBranch'] = $_POSTED['OutBranch'];
		endif;

		$_PARAMS = array(
		  "OutBranch" => $_POSTED['OutBranch'],
		  "InBranch" => $_POSTED['InBranch'],
		  "OutDate" => wp_date('d/m/Y', strtotime($_POSTED['OutDate'])),
		  "OutTime" => $_POSTED['OutTime'],
		  "InDate" => wp_date('d/m/Y', strtotime($_POSTED['InDate'])),
		  "InTime" => $_POSTED['InTime']
		);

		CARPRO_HELPERS::CLEAR_CARPRO();

		CARPRO::DOAVAILABLILITY($_PARAMS);

		//$_URL = get_permalink( woocommerce_get_page_id( 'shop' ) );
		$_URL = get_field('search_results_page', 'option');

		echo $_URL;

		exit;


	}










	/* AJAX: ADD TO CART */
	public function carpro_ajax_do_add_to_cart(){

		WC()->session->__unset('carpro_selected_vehicle');
		WC()->session->__unset('carpro_selected_sku');
		WC()->session->__unset('carpro_selected_km');
		WC()->session->__unset('carpro_selected_code');
		WC()->session->__unset('carpro_one_way_fee');

		WC()->cart->empty_cart();

		$_VEHICLE = $_POST['vehicle'];
		$_ID = $_POST['id'];
		$_KM = $_POST['km'];
		$_CODE = $_POST['code'];
		$_SKU = $_POST['sku'];
		$_OWF = $_POST['owf'];

		WC()->cart->add_to_cart($_ID, 1);

		WC()->session->set('carpro_selected_vehicle', $_VEHICLE);
		WC()->session->set('carpro_selected_sku', $_SKU);
		WC()->session->set('carpro_selected_km', $_KM);
		WC()->session->set('carpro_selected_code', $_CODE);
		WC()->session->set('carpro_one_way_fee', $_OWF);

		foreach(WC()->session->get('carpro_availability') as $_VEH => $_DATA):

			if(trim($_VEHICLE) == trim($_DATA['vehicle']['international'])):

				$_RATES 	= $_DATA['vehicle']['rates'];
				$_FEES 		= $_DATA['vehicle']['fees'];
				$_EXTRA_D	= $_DATA['vehicle']['extras']['daily'];
				$_EXTRA_O 	= $_DATA['vehicle']['extras']['once'];

				foreach($_RATES as $_KM_ID => $_KM_RATES):

					if($_KM == $_KM_ID):

						$_THE_RATES = $_KM_RATES['rates'];
						
						foreach($_THE_RATES as $_KM_RATE):

							if($_KM_RATE['code'] == $_CODE):

								WC()->session->set('carpro_selected_rate', $_KM_RATE);
								$_EXTRAS = $_KM_RATES['extras'];
								$_EXTRA_D	= array_merge($_EXTRA_D, $_EXTRAS['daily']);
								$_EXTRA_O 	= array_merge($_EXTRA_O, $_EXTRAS['once']);
								WC()->session->set('carpro_available_extras_daily', $_EXTRA_D);
								WC()->session->set('carpro_available_extras_once', $_EXTRA_O);
								WC()->session->set('carpro_fees', $_FEES);

							endif;

						endforeach;

					endif;

				endforeach;

			endif;

		endforeach;

		echo wc_get_checkout_url();
		exit;

	}










	/* AJAX: RESET SEARCH */
	public function carpro_ajax_reset_search(){

		CARPRO_HELPERS::CLEAR_CARPRO();

		WC()->cart->empty_cart();

		echo get_home_url();
		exit;
	}










	/* AJAX: RESET SEARCH */
	public function carpro_ajax_branch_restricted_dates(){
		$_CODE = $_POST['branch'];

		$_BRANCH = CARPRO_HELPERS::GET_BRANCH_FROM_CODE($_CODE);

		$_DISALLOWED_DAYS_OF_WEEK = array();
		$_DISALLOWED_DATES_OF_YEAR = array();

		$_FIELDS = array('sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday');

		foreach($_FIELDS as $_KEY=> $_FIELD):

			$_DAY = get_field($_FIELD, $_BRANCH);
			if(strtolower($_DAY) == 'closed'):
				$_DISALLOWED_DAYS_OF_WEEK[] = $_KEY;
			elseif(strtolower($_DAY) == 'by appointment'):
				$_DISALLOWED_DAYS_OF_WEEK[] = $_KEY;
			endif;

		endforeach;

		$_PUB = get_field('public_holidays', $_BRANCH);

		$_LIMIT_PUB = false;

		if(strtolower($_PUB) == 'closed' || strtolower($_PUB) == 'by appointment'):

			$_LIMIT_PUB = true;

		endif;

		if($_LIMIT_PUB):

			$_PH = get_posts(
				array(
					'post_type' => 'publicholiday',
					'posts_per_page' => -1,
				)
			);

			foreach($_PH as $_P):

				$_DISALLOWED_DATES_OF_YEAR[] = get_field('date', $_P);
				$_DISALLOWED_NAMES_OF_YEAR[] = $_P->post_title;

			endforeach;

		endif;

		$_RETURN = array();

		if(count($_DISALLOWED_DAYS_OF_WEEK) > 0):
			$_RETURN['week'] = $_DISALLOWED_DAYS_OF_WEEK;
		endif;

		if(count($_DISALLOWED_DATES_OF_YEAR) > 0):
			$_RETURN['holidays'] 	= $_DISALLOWED_DATES_OF_YEAR;
			$_RETURN['names'] 		= $_DISALLOWED_NAMES_OF_YEAR;
		endif;

		echo json_encode($_RETURN);

		exit;

	}










	/* SAVE EXTRA INFORMATION TO ORDER */
	public function woocommerce_checkout_update_order_meta($_ORDER_ID){

		$_RATE = WC()->session->get('carpro_selected_rate');
		$_DAILY = WC()->session->get('carpro_selected_daily_extras');
		$_ONCE = WC()->session->get('carpro_selected_once_extras');

		update_post_meta($_ORDER_ID, 'carpro_selected_vehicle', WC()->session->get('carpro_selected_vehicle'));
		update_post_meta($_ORDER_ID, 'carpro_out_branch', WC()->session->get('carpro_out_branch'));
		update_post_meta($_ORDER_ID, 'carpro_out_date', WC()->session->get('carpro_out_date'));
		update_post_meta($_ORDER_ID, 'carpro_out_time', WC()->session->get('carpro_out_time'));
		update_post_meta($_ORDER_ID, 'carpro_in_branch', WC()->session->get('carpro_in_branch'));
		update_post_meta($_ORDER_ID, 'carpro_in_date', WC()->session->get('carpro_in_date'));
		update_post_meta($_ORDER_ID, 'carpro_in_time', WC()->session->get('carpro_in_time'));
		update_post_meta($_ORDER_ID, 'carpro_selected_km', WC()->session->get('carpro_selected_km'));
		update_post_meta($_ORDER_ID, 'carpro_selected_code', WC()->session->get('carpro_selected_code'));
		update_post_meta($_ORDER_ID, 'carpro_selected_rate', WC()->session->get('carpro_selected_rate'));
		update_post_meta($_ORDER_ID, 'carpro_selected_once_extras', WC()->session->get('carpro_selected_once_extras'));
		update_post_meta($_ORDER_ID, 'carpro_selected_daily_extras', WC()->session->get('carpro_selected_daily_extras'));
		update_post_meta($_ORDER_ID, 'carpro_fees', WC()->session->get('carpro_fees'));

		update_post_meta($_ORDER_ID, 'carpro_deposit_percentage', WC()->session->get('carpro_deposit_percentage'));
		update_post_meta($_ORDER_ID, 'carpro_deposit_amount', number_format(WC()->session->get('carpro_deposit_amount'), 2, ".", ""));

		if(isset($_POST['license_number'])):
			update_post_meta($_ORDER_ID, 'license_number', $_POST['license_number']);
		endif;

		if(isset($_POST['license_expiry'])):
			update_post_meta($_ORDER_ID, 'license_expiry', $_POST['license_expiry']);
		endif;

		if(isset($_POST['arrival_flight_number'])):
			update_post_meta($_ORDER_ID, 'arrival_flight_number', $_POST['arrival_flight_number']);
		endif;
		
		update_post_meta($_ORDER_ID, 'carpro_selected_rate_no', $_RATE['rateno']);
		update_post_meta($_ORDER_ID, 'carpro_selected_rate_srno', $_RATE['ratesrno']);

		$_E_D = array();
		if(is_array($_DAILY) && count($_DAILY) > 0):

			foreach($_DAILY as $_CODE => $_DATA):
				$_E_D[] = $_CODE;
			endforeach;

		endif;

		if(count($_E_D) > 0):
			update_post_meta($_ORDER_ID, 'carpro_selected_daily_extra_codes', implode(', ', $_E_D));
		endif;

		$_E_O = array();
		if(is_array($_ONCE) && count($_ONCE) > 0):

			foreach($_ONCE as $_CODE => $_DATA):
				$_E_O[] = $_CODE;
			endforeach;

		endif;

		if(count($_E_O) > 0):
			update_post_meta($_ORDER_ID, 'carpro_selected_once_extra_codes', implode(', ', $_E_O));
		endif;

		if(isset($_POST['phone_number_full'])):
			update_post_meta($_ORDER_ID, '_billing_phone', $_POST['phone_number_full']);
		endif;


	}










	/* SLEEP THANK YOU PAGE TO ALLOW ORDER NUMBER TO APPEAR */
	public function woocommerce_thankyou_order_id($_ORDER_ID){
		sleep(5);

		return $_ORDER_ID;
	}










	/* REMOVE PROCESSING NOTIFICATIONS */
	public function woocommerce_email($_EMAIL_CLASS){

		remove_action( 
			'woocommerce_order_status_pending_to_processing_notification', 
			array( $_EMAIL_CLASS->emails['WC_Email_Customer_Processing_Order'], 
				'trigger' 
			) 
		);

		remove_action( 
			'woocommerce_order_status_pending_to_completed_notification', 
			array( $_EMAIL_CLASS->emails['WC_Email_New_Order'], 
				'trigger' 
			) 
		);

		remove_action( 
			'woocommerce_order_status_pending_to_on-hold_notification', 
			array( $_EMAIL_CLASS->emails['WC_Email_Customer_Processing_Order'], 
				'trigger' 
			) 
		);

		remove_action( 'woocommerce_order_status_completed_notification', 
			array( $_EMAIL_CLASS->emails['WC_Email_Customer_Completed_Order'], 
				'trigger' 
			) 
		);

	}










	/* MARK ORDER AUTO COMPLETED */
	public function woocommerce_payment_complete_order_status($_STATUS){

		return 'completed';

	}










	/* ON COMPLETE - DO RESERVATION AND CLEAR SESSION */
	public function woocommerce_order_status_completed($_ORDER_ID){
		
		CARPRO_HELPERS::CLEAR_CARPRO();
		CARPRO::DORESERVATION($_ORDER_ID);
		do_action('k8_adumo_enterprise_clear_card_details');

	}










	/* ON CANCEL - CLEAR SESSION AND PAYMENT DETAILS */
	public function woocommerce_order_status_cancelled($_ORDER_ID){
		
		CARPRO_HELPERS::CLEAR_CARPRO();

	}










	/* FILTER ORDER NUMBER TO CARPRO RESERVATION */
	public function woocommerce_order_number($_ID){


		if(get_post_meta($_ID, 'carpro_reservation_number', true)):
			$_ID = get_post_meta($_ID, 'carpro_reservation_number', true);
		endif;


		return $_ID;


	}










	/* ADD DETAILS TO ORDER EMAIL */
	public function woocommerce_email_order_details($_ORDER, $_ADMIN){

		if(get_field('email_instructions', 'option') && !$_ADMIN):

			the_field('email_instructions', 'option');

		endif;	

		if(get_post_meta($_ORDER->get_id(), 'carpro_error', true)):

			$_CAR_PRO_ERROR = get_post_meta($_ORDER->get_id(), 'carpro_error', true);

			$_EMAIL = get_option('booking_failure_customer_email_to_contact','option');

			echo '<p>Our apologies, there seems to have been an issue with our reservation system. kindly EMAIL <a href="mailto:'.$_EMAIL.'">'.$_EMAIL.'</a> with <strong>REFERENCE: '.$_ORDER->get_id().'</strong> and <strong>ERROR: '.$_CAR_PRO_ERROR.'</strong></p>';

		endif;	



	}










	/* ADD DETAILS TO ORDER EMAIL */
	public function woocommerce_email_order_meta($_ORDER, $_ADMIN){


		if(get_field('email_after_details_table', 'option') && !$_ADMIN):

			the_field('email_after_details_table', 'option');

		endif;


	}









	public function wp_logout($_USER_ID){
		CARPRO_HELPERS::CLEAR_CARPRO();
	}


}
