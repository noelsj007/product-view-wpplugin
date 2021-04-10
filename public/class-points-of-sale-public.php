<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://lucianotonet.com
 * @since      1.0.0
 *
 * @package    Points_Of_Sale
 * @subpackage Points_Of_Sale/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Points_Of_Sale
 * @subpackage Points_Of_Sale/public
 * @author     Luciano Tonet <contato@lucianotonet.com>
 */
class Points_Of_Sale_Public {

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
	 * @var      string    $plugin_name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
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
		 * defined in Points_Of_Sale_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Points_Of_Sale_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name.'_normalize', plugin_dir_url( __FILE__ ) . 'css/normalize.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/points-of-sale-public.css', array(), $this->version . date('dmYHis'), 'all' );
		wp_enqueue_style( $this->plugin_name.'_fonts', plugin_dir_url( __FILE__ ) . 'fonts/stylesheet.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name.'_selectric', plugin_dir_url( __FILE__ ) . 'css/jquery.selectric.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Points_Of_Sale_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Points_Of_Sale_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name.'_selectric', plugin_dir_url( __FILE__ ) . 'js/jquery.selectric.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'_gmaps', 'https://maps.googleapis.com/maps/api/js?v=3.exp', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name.'_infobox', plugin_dir_url( __FILE__ ) . 'js/infobox.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/points-of-sale-public.js', array( $this->plugin_name.'_infobox' ), $this->version . date('dmYHis'), true );

		wp_localize_script( $this->plugin_name, 'pos_data', $this->pos_localize_script() );

	}


	public function register_points_of_sale_shortcode(){
		add_shortcode( 'points_of_sale', array( $this, 'points_of_sale_shortcode' ) );	
	}


	// Add Shortcode
	public function points_of_sale_shortcode( $atts ) {	

		global $wpdb;
			
		// Attributes
		extract( shortcode_atts(
			array(				    
				'style'  => 'full_width',
				'width'  => '960',
				'height' => '300',
			), $atts )
		);
		

		/**
		 * 
		 *	FILTERS
		 *
		 **/
		$response = array();

		// PDVS
		$pdvs = 	"SELECT 
					        pm1.meta_value as _pos_state, 
					        pm2.meta_value as _pos_city,
					        pm3.meta_value as _pos_neighborhood, 
					        pm4.meta_value as _pos_street, 
					        pm5.meta_value as _pos_number, 
					        pm6.meta_value as _pos_postal_code, 
					        pm7.meta_value as _pos_more_info, 
					        pm8.meta_value as _pos_latitude, 
				        	pm9.meta_value as _pos_longitude

					FROM ".$wpdb->base_prefix."posts 

					LEFT JOIN ".$wpdb->base_prefix."postmeta AS pm1 ON (".$wpdb->base_prefix."posts.ID = pm1.post_id AND pm1.meta_key='_pos_state') 
					LEFT JOIN ".$wpdb->base_prefix."postmeta AS pm2 ON (".$wpdb->base_prefix."posts.ID = pm2.post_id AND pm2.meta_key='_pos_city')
					LEFT JOIN ".$wpdb->base_prefix."postmeta AS pm3 ON (".$wpdb->base_prefix."posts.ID = pm3.post_id AND pm3.meta_key='_pos_neighborhood')				
					LEFT JOIN ".$wpdb->base_prefix."postmeta AS pm4 ON (".$wpdb->base_prefix."posts.ID = pm4.post_id AND pm4.meta_key='_pos_street')	
					LEFT JOIN ".$wpdb->base_prefix."postmeta AS pm5 ON (".$wpdb->base_prefix."posts.ID = pm5.post_id AND pm5.meta_key='_pos_number')	
					LEFT JOIN ".$wpdb->base_prefix."postmeta AS pm6 ON (".$wpdb->base_prefix."posts.ID = pm6.post_id AND pm6.meta_key='_pos_postal_code')	
					LEFT JOIN ".$wpdb->base_prefix."postmeta AS pm7 ON (".$wpdb->base_prefix."posts.ID = pm7.post_id AND pm7.meta_key='_pos_more_info')	
			        LEFT JOIN ".$wpdb->base_prefix."postmeta AS pm8 ON (".$wpdb->base_prefix."posts.ID = pm8.post_id AND pm8.meta_key='_pos_latitude')
		        	LEFT JOIN ".$wpdb->base_prefix."postmeta AS pm9 ON (".$wpdb->base_prefix."posts.ID = pm9.post_id AND pm9.meta_key='_pos_longitude')

					WHERE ".$wpdb->base_prefix."posts.post_type   	= 'point_of_sale' 
					AND ".$wpdb->base_prefix."posts.post_status 	= 'publish' 					
					
					ORDER BY _pos_city ASC";


		$pdvs = $wpdb->get_results( $pdvs, ARRAY_A );

		$total_stores = count($pdvs);
	
		$states = $this->group_this_by( $pdvs, '_pos_state', '_pos_city', '_pos_neighborhood' );

		//turn on output buffering to capture script output
		ob_start();
		//include the specified file
		include_once(plugin_dir_path( __FILE__ ) . 'partials/points-of-sale-public-display.php');			
		//assign the file output to $content variable and clean buffer
		$content = ob_get_clean();
		//return the $content
		//return is important for the output to appear at the correct position
		//in the content
		return $content;	
									
	}

	
	public function group_this_by($array, $key1, $key2, $key3) {
	    $return = array();
	    foreach($array as $val) {    	
	    	$return[ $val[$key1] ][ 'cities' ][ $val[$key2] ][ 'neighborhoods' ][ $val[$key3] ]['coordinates'] 	= $val['_pos_latitude'].','.$val['_pos_longitude'];                		
	    	$return[ $val[$key1] ][ 'cities' ][ $val[$key2] ][ 'neighborhoods' ][ $val[$key3] ]['total-stores'] = "";
	        $return[ $val[$key1] ][ 'cities' ][ $val[$key2] ][ 'neighborhoods' ][ $val[$key3] ]['name']  		= $val[$key3];                
	        $return[ $val[$key1] ][ 'cities' ][ $val[$key2] ][ 'total-stores' ]									= "";                 
	        $return[ $val[$key1] ][ 'cities' ][ $val[$key2] ][ 'name' ]											= $val[$key2];         
	        $return[ $val[$key1] ][ 'total-stores' ] 								   							= "";
	    	$return[ $val[$key1] ][ 'name' ] 								   									= $val[$key1];
	    }
	    return $return;
	}


	public function pos_localize_script(){
		
		$data = array(
					'ajaxurl' 	=> admin_url( 'admin-ajax.php' ),
					'assetsurl' => plugin_dir_url( __FILE__ ),
				);
		return $data;

	}

}