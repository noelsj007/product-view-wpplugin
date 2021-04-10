<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://lucianotonet.com
 * @since      1.0.0
 *
 * @package    Points_Of_Sale
 * @subpackage Points_Of_Sale/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Points_Of_Sale
 * @subpackage Points_Of_Sale/includes
 * @author     Luciano Tonet <contato@lucianotonet.com>
 */
class Points_Of_Sale {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Points_Of_Sale_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'points-of-sale';
		$this->version = '1.0.0';

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
	 * - Points_Of_Sale_Loader. Orchestrates the hooks of the plugin.
	 * - Points_Of_Sale_i18n. Defines internationalization functionality.
	 * - Points_Of_Sale_Admin. Defines all hooks for the dashboard.
	 * - Points_Of_Sale_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {


		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-points-of-sale-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-points-of-sale-i18n.php';
		
		/**
		 * The class responsible for defining all meta boxes		 
		 */
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/meta-box/meta-box.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/TGM-Plugin-Activation/class-tgm-plugin-activation.php';		

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-points-of-sale-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-points-of-sale-public.php';		


		$this->loader = new Points_Of_Sale_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Points_Of_Sale_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Points_Of_Sale_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Points_Of_Sale_Admin( $this->get_plugin_name(), $this->get_version() );


		$this->loader->add_action( 'wp_ajax_getlocations', $plugin_admin, 'getlocations_callback' );
		$this->loader->add_action( 'wp_ajax_nopriv_getlocations', $plugin_admin, 'getlocations_callback' );

		
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        
		// Hook into the 'init' action
		$this->loader->add_action( 'init', $plugin_admin, 'pos_register_cpt' );        			
		
		$this->loader->add_action( 'tgmpa_register', $plugin_admin, 'pos_register_required_plugins' );
		
		$this->loader->add_filter( 'rwmb_meta_boxes', $plugin_admin, 'pos_register_meta_boxes' );

		$this->loader->add_action( 'admin_init', $this, 'pos_register_poslocationpicker_field' );
          
	}

	
	/**
	 * The class responsible for defining all actions that occur in the public-facing
	 * side of the site.
	 */
	public function pos_register_poslocationpicker_field(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-poslocationpicker-field.php';
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Points_Of_Sale_Public( $this->get_plugin_name(), $this->get_version() );

		// return $_SERVER['REQUEST_METHOD'];

		//$this->loader->add_action( 'wp_ajax_nopriv_get_locations', $plugin_public, 'points_of_sale_request_handler' );
		// add_action( 'wp_ajax_getlocations', $plugin_public, 'pos_locations_response' );


		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );		

		$this->loader->add_action( 'init',  $plugin_public, 'register_points_of_sale_shortcode' );

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
	 * @return    Points_Of_Sale_Loader    Orchestrates the hooks of the plugin.
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
