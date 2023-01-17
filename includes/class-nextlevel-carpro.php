<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.kri8it.com
 * @since      1.0.0
 *
 * @package    Nextlevel_Carpro
 * @subpackage Nextlevel_Carpro/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.1
 * @package    Nextlevel_Carpro
 * @subpackage Nextlevel_Carpro/includes
 * @author     Hilton Moore <hilton@kri8it.com>
 */
class Nextlevel_Carpro {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Nextlevel_Carpro_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'NEXTLEVEL_CARPRO_VERSION' ) ) {
			$this->version = NEXTLEVEL_CARPRO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'nextlevel-carpro';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Nextlevel_Carpro_Loader. Orchestrates the hooks of the plugin.
	 * - Nextlevel_Carpro_i18n. Defines internationalization functionality.
	 * - Nextlevel_Carpro_Admin. Defines all hooks for the admin area.
	 * - Nextlevel_Carpro_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nextlevel-carpro-log.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nextlevel-carpro-class.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nextlevel-carpro-helpers.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nextlevel-carpro-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-nextlevel-carpro-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-nextlevel-carpro-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-nextlevel-carpro-public.php';

		$this->loader = new Nextlevel_Carpro_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Nextlevel_Carpro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Nextlevel_Carpro_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Nextlevel_Carpro_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'parse_query', $plugin_admin, 'parse_query');

		$this->loader->add_action( 'wp_head', $plugin_admin, 'wp_head');

		$this->loader->add_filter( 'body_class', $plugin_admin, 'body_class', 99, 1);

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu');

		$this->loader->add_filter( 'http_request_timeout', $plugin_admin, 'http_request_timeout');

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles', 999);
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts', 999);
		$this->loader->add_filter( 'cron_schedules', $plugin_admin, 'cron_schedules', 999,1);	

		$this->loader->add_action( 'init', $plugin_admin, 'custom_fields' );
		$this->loader->add_action( 'init', $plugin_admin, 'post_types' );
		$this->loader->add_action( 'init', $plugin_admin, 'setup_cron_schedules', 999);	


		$this->loader->add_action( 'carpro_clean_logs_action', $plugin_admin, 'carpro_clean_logs_action');
		$this->loader->add_action( 'carpro_sync_branch_action', $plugin_admin, 'carpro_sync_branch_action');
		$this->loader->add_action( 'carpro_sync_vehicle_action', $plugin_admin, 'carpro_sync_vehicle_action');
		$this->loader->add_action( 'carpro_sync_publicholiday_action', $plugin_admin, 'carpro_sync_publicholiday_action');

		$this->loader->add_filter( 'manage_publicholiday_posts_columns', $plugin_admin, 'manage_publicholiday_posts_columns');
		$this->loader->add_action( 'manage_publicholiday_posts_custom_column', $plugin_admin, 'manage_publicholiday_posts_custom_column', 10, 2);
		$this->loader->add_filter( 'manage_shop_order_posts_columns', $plugin_admin, 'manage_shop_order_posts_columns', 99999, 1);
		$this->loader->add_action( 'manage_shop_order_posts_custom_column', $plugin_admin, 'manage_shop_order_posts_custom_column', 10, 2);
		
		$this->loader->add_action('wp_ajax_carpro_ajax_branch_times', $plugin_admin, 'carpro_ajax_branch_times');
		$this->loader->add_action('wp_ajax_nopriv_carpro_ajax_branch_times', $plugin_admin, 'carpro_ajax_branch_times');

		$this->loader->add_action('wp_ajax_carpro_ajax_do_search', $plugin_admin, 'carpro_ajax_do_search');
		$this->loader->add_action('wp_ajax_nopriv_carpro_ajax_do_search', $plugin_admin, 'carpro_ajax_do_search');

		$this->loader->add_action('wp_ajax_carpro_ajax_do_add_to_cart', $plugin_admin, 'carpro_ajax_do_add_to_cart');
		$this->loader->add_action('wp_ajax_nopriv_carpro_ajax_do_add_to_cart', $plugin_admin, 'carpro_ajax_do_add_to_cart');

		$this->loader->add_action('wp_ajax_carpro_ajax_reset_search', $plugin_admin, 'carpro_ajax_reset_search');
		$this->loader->add_action('wp_ajax_nopriv_carpro_ajax_reset_search', $plugin_admin, 'carpro_ajax_reset_search');

		$this->loader->add_action('wp_ajax_carpro_ajax_branch_restricted_dates', $plugin_admin, 'carpro_ajax_branch_restricted_dates');
		$this->loader->add_action('wp_ajax_nopriv_carpro_ajax_branch_restricted_dates', $plugin_admin, 'carpro_ajax_branch_restricted_dates');

		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_admin, 'woocommerce_checkout_update_order_meta', 999, 1);
		$this->loader->add_action( 'woocommerce_thankyou_order_id', $plugin_admin, 'woocommerce_thankyou_order_id', 10, 1);
		$this->loader->add_action( 'woocommerce_email', $plugin_admin, 'woocommerce_email', 999, 1);

		$this->loader->add_filter( 'woocommerce_payment_complete_order_status', $plugin_admin, 'woocommerce_payment_complete_order_status', 999, 1);
		$this->loader->add_action( 'woocommerce_order_status_completed', $plugin_admin, 'woocommerce_order_status_completed', 999, 1);
		$this->loader->add_action( 'woocommerce_order_status_cancelled', $plugin_admin, 'woocommerce_order_status_cancelled', 999, 1);
		$this->loader->add_action( 'woocommerce_email_order_details', $plugin_admin, 'woocommerce_email_order_details', 2, 2);
		$this->loader->add_action( 'woocommerce_email_order_meta', $plugin_admin, 'woocommerce_email_order_meta', 999, 2);
		$this->loader->add_filter( 'woocommerce_order_number', $plugin_admin, 'woocommerce_order_number', 999, 1);


		$this->loader->add_action( 'wp_logout', $plugin_admin, 'wp_logout', 999, 1);

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Nextlevel_Carpro_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'shortcodes' );

		$this->loader->add_filter( 'woocommerce_product_get_price', $plugin_public, 'woocommerce_product_get_price', 999, 2 );
		$this->loader->add_filter( 'woocommerce_get_price_html', $plugin_public, 'woocommerce_get_price_html', 999, 2 );
		$this->loader->add_filter( 'woocommerce_is_purchasable', $plugin_public, 'woocommerce_is_purchasable', 999, 2 );

		$this->loader->add_action( 'carpro_extra_option_fields', $plugin_public, 'carpro_extra_option_fields', 999, 1 );
		$this->loader->add_action( 'carpro_extra_sections', $plugin_public, 'carpro_extra_sections', 999 );

		$this->loader->add_action( 'carpro_extra_detail_fields', $plugin_public, 'carpro_extra_detail_fields', 999, 1 );

		$this->loader->add_action( 'carpro_extra_payment_fields', $plugin_public, 'carpro_extra_payment_fields', 999, 1 );

		$this->loader->add_action( 'after_setup_theme', $plugin_public, 'after_setup_theme', 999 );

		$this->loader->add_filter( 'woocommerce_enable_order_notes_field', $plugin_public, 'generic_false_function', 999 );

		$this->loader->add_filter( 'woocommerce_return_to_shop_text', $plugin_public, 'woocommerce_return_to_shop_text', 999, 1 );

		$this->loader->add_filter( 'woocommerce_loop_add_to_cart_link', $plugin_public, 'woocommerce_loop_add_to_cart_link', 999, 3 );

		$this->loader->add_filter( 'woocommerce_account_orders_columns', $plugin_public, 'woocommerce_account_orders_columns', 999, 1 );

		$this->loader->add_filter( 'woocommerce_remove_cart_item', $plugin_public, 'woocommerce_remove_cart_item', 999, 2 );

		$this->loader->add_action( 'wp_footer', $plugin_public, 'wp_footer', 999 );

		$this->loader->add_filter( 'woocommerce_locate_template', $plugin_public, 'woocommerce_locate_template', 999, 3);

		$this->loader->add_filter( 'wc_get_template_part', $plugin_public, 'wc_get_template_part', 999, 3);

		$this->loader->add_filter( 'woocommerce_get_item_data', $plugin_public, 'woocommerce_get_item_data', 999, 2);

		$this->loader->add_action( 'woocommerce_cart_calculate_fees', $plugin_public, 'woocommerce_cart_calculate_fees', 999);

		$this->loader->add_action( 'woocommerce_before_thankyou', $plugin_public, 'woocommerce_before_checkout_form', 999);
		$this->loader->add_action( 'woocommerce_before_checkout_form', $plugin_public, 'woocommerce_before_checkout_form', 999);
		$this->loader->add_action( 'woocommerce_checkout_create_order_line_item', $plugin_public, 'woocommerce_checkout_create_order_line_item', 999, 4);

		$this->loader->add_filter( 'woocommerce_checkout_fields', $plugin_public, 'woocommerce_checkout_fields', 999, 1);

		$this->loader->add_action( 'woocommerce_thankyou', $plugin_public, 'woocommerce_thankyou', 999, 1);

		$this->loader->add_filter( 'woocommerce_return_to_shop_redirect', $plugin_public, 'woocommerce_return_to_shop_redirect', 999, 1);

		$this->loader->add_action( 'carpro_before_payment', $plugin_public, 'carpro_before_payment', 999, 1);

		$this->loader->add_filter( 'gform_form_tag', $plugin_public, 'gform_form_tag', 999, 1);
		$this->loader->add_filter( 'gform_field_content', $plugin_public, 'gform_field_content', 999, 1);

		$this->loader->add_filter( 'woocommerce_checkout_cart_item_quantity', $plugin_public, 'generic_false_function', 999 );

		$this->loader->add_filter( 'woocommerce_order_item_quantity_html', $plugin_public, 'generic_false_function', 999 );

		$this->loader->add_filter( 'the_title', $plugin_public, 'the_title', 999, 2);
		//$this->loader->add_filter( 'woocommerce_cart_item_name', $plugin_public, 'woocommerce_cart_item_name', 999, 2);
		$this->loader->add_action( 'woocommerce_before_calculate_totals', $plugin_public, 'woocommerce_before_calculate_totals', 999, 1);


		$this->loader->add_filter('woocommerce_account_menu_items', $plugin_public, 'woocommerce_account_menu_items', 999, 1);


		$this->loader->add_filter('woocommerce_order_item_permalink', $plugin_public, 'woocommerce_order_item_permalink', 999, 3);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Nextlevel_Carpro_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
