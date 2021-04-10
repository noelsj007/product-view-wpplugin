<?php
/**
 * Fired during plugin update.
 *
 * This class defines all code necessary to run during the plugin's update.
 *
 * @since      1.0.0
 * @package    Points_Of_Sale
 * @subpackage Points_Of_Sale/includes
 * @author     Luciano Tonet <contato@lucianotonet.com>
 */
class Points_Of_Sale_Updater {

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

		$this->load_dependencies();
		// $this->update();
	
	}

	/**
	 * Load the required dependencies for update via GitHub.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for updates via GitHub		 
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/WordPress-GitHub-Plugin-Updater-master/updater.php';

	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function update() {
		
		if (is_admin()) { // note the use of is_admin() to double check that this is happening in the admin
		    $config = array(
		        'slug' 					=> plugin_basename(__FILE__), // this is the slug of your plugin
		        'proper_folder_name' 	=> 'points-of-sale', // this is the name of the folder your plugin lives in
		        'api_url' 				=> 'https://api.github.com/repos/tonetlds/Points-of-Sale', // the github API url of your github repo
		        'raw_url' 				=> 'https://raw.github.com/tonetlds/Points-of-Sale/master', // the github raw url of your github repo
		        'github_url' 			=> 'https://github.com/tonetlds/Points-of-Sale', // the github url of your github repo
		        'zip_url' 				=> 'https://github.com/tonetlds/Points-of-Sale/zipball/master', // the zip url of the github repo
		        'sslverify' 			=> false, // wether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
		        'requires' 				=> '3.0', // which version of WordPress does your plugin require?
		        'tested' 				=> '3.3', // which version of WordPress is your plugin tested up to?
		        'readme' 				=> 'README.MD' // which file to use as the readme for the version number
		    );
		    new WPGitHubUpdater($config);
		}		

	}

}
