<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://lucianotonet.com
 * @since      1.0.0
 *
 * @package    Points_Of_Sale
 * @subpackage Points_Of_Sale/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Points_Of_Sale
 * @subpackage Points_Of_Sale/admin
 * @author     Luciano Tonet <contato@lucianotonet.com>
 */
class Points_Of_Sale_Admin {

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

	//TODO: Add a setting for this
	//Post types that can be used for locations - Add your post type here if you want to use them as locations
	public $post_types_to_use = array('point_of_sale');

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Points_Of_Sale_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Points_Of_Sale_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/points-of-sale-admin.css', array(), $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Points_Of_Sale_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Points_Of_Sale_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if ($hook != 'post.php' && $hook != 'post-new.php') {
			return;
		}

		//DEFINE LANGUAGE OF GOOGLE MAPS
		$google_map_language = 'pt';

		$languageString = '';
		if ($google_map_language != '' && $google_map_language != 'pt') {
			$languageString = '&language=' . $google_map_language;
		}

		// INCLUDE THE .JS
		if (is_ssl()) {
			wp_enqueue_script('pos_google_maps', 'https://maps.googleapis.com/maps/api/js?sensor=true&libraries=places' . $languageString);
		} else {
			wp_enqueue_script('pos_google_maps', 'http://maps.googleapis.com/maps/api/js?sensor=true&libraries=places' . $languageString);
		}

		wp_enqueue_script('pos_locationpicker', plugin_dir_url(__FILE__) . 'js/locationpicker.jquery.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/points-of-sale-admin.js', array('pos_google_maps', 'jquery'), $this->version, false);

		wp_localize_script($this->plugin_name, 'pos_locationpicker_data', $this->pos_localize_script());

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_locationpicker() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Points_Of_Sale_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Points_Of_Sale_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	}

	/**
	 * Register required plugins
	 * @return void
	 * @since  1.0
	 */
	public function pos_register_required_plugins() {
		$plugins = array(
			array(
				'name' => 'Meta Box',
				'slug' => 'meta-box',
				'required' => true,
				'force_activation' => false,
				'force_deactivation' => false,
			),
			// You can add more plugins here if you want
		);
		$config = array(
			'domain' => 'points_of_sale',
			'default_path' => '',
			'parent_menu_slug' => 'themes.php',
			'parent_url_slug' => 'themes.php',
			'menu' => 'install-required-plugins',
			'has_notices' => true,
			'is_automatic' => false,
			'message' => '',
			'strings' => array(
				'page_title' => __('Install Required Plugins', 'points-of-sale'),
				'menu_title' => __('Install Plugins', 'points-of-sale'),
				'installing' => __('Installing Plugin: %s', 'points-of-sale'),
				'oops' => __('Something went wrong with the plugin API.', 'points-of-sale'),
				'notice_can_install_required' => _n_noop('This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.'),
				'notice_can_install_recommended' => _n_noop('This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.'),
				'notice_cannot_install' => _n_noop('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.'),
				'notice_can_activate_required' => _n_noop('The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.'),
				'notice_can_activate_recommended' => _n_noop('The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.'),
				'notice_cannot_activate' => _n_noop('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.'),
				'notice_ask_to_update' => _n_noop('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.'),
				'notice_cannot_update' => _n_noop('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.'),
				'install_link' => _n_noop('Begin installing plugin', 'Begin installing plugins'),
				'activate_link' => _n_noop('Activate installed plugin', 'Activate installed plugins'),
				'return' => __('Return to Required Plugins Installer', 'points-of-sale'),
				'plugin_activated' => __('Plugin activated successfully.', 'points-of-sale'),
				'complete' => __('All plugins installed and activated successfully. %s', 'points-of-sale'),
				'nag_type' => 'updated',
			),
		);

		tgmpa($plugins, $config);
	}

	/***********************************
		     * 	POINTS OF SALE
		     *
	*/

	public function pos_register_cpt() {

		$labels = array(
			'name' => _x('Pontos de Venda', 'Post Type General Name', 'points_of_sale'),
			'singular_name' => _x('Ponto de Venda', 'Post Type Singular Name', 'points_of_sale'),
			'menu_name' => __('PDV\'s', 'points_of_sale'),
			'parent_item_colon' => __('Pai:', 'points_of_sale'),
			'all_items' => __('Todos os Pontos de Venda', 'points_of_sale'),
			'view_item' => __('Ver Ponto de Venda', 'points_of_sale'),
			'add_new_item' => __('Adicionar novo Ponto de Venda', 'points_of_sale'),
			'add_new' => __('Adicionar novo', 'points_of_sale'),
			'edit_item' => __('Editar Ponto de Venda', 'points_of_sale'),
			'update_item' => __('Atualizar Ponto de Venda', 'points_of_sale'),
			'search_items' => __('Buscar Ponto de Venda', 'points_of_sale'),
			'not_found' => __('Nada encontrado', 'points_of_sale'),
			'not_found_in_trash' => __('Nada encontrado no lixo', 'points_of_sale'),
		);
		$args = array(
			'label' => __('points_of_sale', 'points_of_sale'),
			'description' => __('Pontos de venda onde os seus clientes encontram os seus produtos', 'points_of_sale'),
			'labels' => $labels,
			'supports' => array('title', 'revisions'),
			'hierarchical' => true,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'show_in_admin_bar' => true,
			'menu_position' => 5,
			'menu_icon' => 'dashicons-store',
			'can_export' => true,
			'has_archive' => true,
			'exclude_from_search' => false,
			'publicly_queryable' => true,
			'capability_type' => 'post',
		);
		register_post_type('point_of_sale', $args);

	}

	/**
	 * Define the metabox and field configurations.
	 *
	 * @param  array $meta_boxes
	 * @return array
	 */
	public function pos_register_meta_boxes($meta_boxes) {

		// Start with an underscore to hide fields from custom fields list
		$prefix = '_pos_';

		/**
		 * Sample metabox to demonstrate each field type included
		 */
		$meta_boxes['pos_metabox'] = array(
			'id' => 'pos_details_metabox',
			'title' => __('Detalhes do Ponto de Venda', 'points_of_sale'),
			'pages' => $this->post_types_to_use, // Post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
			// 'cmb_styles' => true, // Enqueue the CMB stylesheet on the frontend
			'validation' => array(
				'rules' => array(
					$prefix . 'latitude' => array(
						'required' => true,
					),
					$prefix . 'longitude' => array(
						'required' => true,
					),
				),
				// Optional override of default error messages
				'messages' => array(
					$prefix . 'latitude' => array(
						'required' => __('É necessário informar a latitude do PDV.<br/>Posicione o pino corretamente no mapa para obter as coordenadas automaticamente.', 'points_of_sale'),
					),
					$prefix . 'longitude' => array(
						'required' => __('É necessário informar a longitude do PDV.<br/>Posicione o pino corretamente no mapa para obter as coordenadas automaticamente.', 'points_of_sale'),
					),
				),
			),
			'fields' => array(
				array(
					'id' => $prefix . 'location_picker',
					'type' => 'poslocationpicker'),
				array(
					'name' => __('Estado', 'points_of_sale'),
					'id' => $prefix . 'state',
					'type' => 'text'),
				array(
					'name' => __('Cidade', 'points_of_sale'),
					'id' => $prefix . 'city',
					'type' => 'text'),
				array(
					'name' => __('Bairro', 'points_of_sale'),
					'id' => $prefix . 'neighborhood',
					'type' => 'text'),
				array(
					'name' => __('Rua', 'points_of_sale'),
					'id' => $prefix . 'street',
					'type' => 'text'),
				array(
					'name' => __('Número', 'points_of_sale'),
					'id' => $prefix . 'number',
					'type' => 'text'),
				array(
					'name' => __('CEP', 'points_of_sale'),
					'id' => $prefix . 'postal_code',
					'type' => 'text'),

				array(
					'name' => __('E-mail', 'points_of_sale'),
					'id' => $prefix . 'email',
					'type' => 'text'),
				array(
					'name' => __('Telefone', 'points_of_sale'),
					'id' => $prefix . 'phone',
					'type' => 'text'),
				array(
					'name' => __('Marcador', 'points_of_sale'),
					'id' => $prefix . 'marker',
					'type' => 'image_advanced',
					'max_file_uploads' => 1),
				array(
					'name' => __('Latitude', 'points_of_sale'),
					'id' => $prefix . 'latitude',
					'type' => 'text'),
				array(
					'name' => __('Longitude', 'points_of_sale'),
					'id' => $prefix . 'longitude',
					'type' => 'text'),

			),
		);

		// Add other metaboxes as needed

		return $meta_boxes;
	}

	public function pos_localize_script() {

		global $post;

		//Get start position
		$defaultEditMapLocationLat = '-28.3043001';
		$defaultEditMapLocationLong = '-52.3880269';
		$defaultEditMapZoom = '16';
		// $defaultEditMapMarker 		= plugin_dir_path( __FILE__ ) ) . "assets/pos-marker.png";
		$defaultEditMapMarker = plugin_dir_url(dirname(__FILE__)) . "assets/pos-marker.png";

		$markers = rwmb_meta('_pos_marker', 'type=image_advanced', $post->ID);

		// echo "<pre>";
		// print_r( $markers );
		// echo "</pre>";
		// exit;

		if (!empty($markers) and is_array($markers)) {
			$marker = end($markers);
			$defaultEditMapMarker = $marker['full_url'];
		}

		if ($defaultEditMapLocationLat == '' || $defaultEditMapLocationLong == '') {
			$defaultEditMapLocationLat = '40.3';
			$defaultEditMapLocationLong = '-98.2';
		}

		if ($defaultEditMapZoom == '' || $defaultEditMapZoom == 'None') {
			$defaultEditMapZoom = 4;
		}

		if ($defaultEditMapMarker == '') {
			$defaultEditMapMarker = plugin_dir_path(__FILE__) . "assets/pos-marker.png";
		}

		$data = array(
			'defaultEditMapLocationLat' => $defaultEditMapLocationLat,
			'defaultEditMapLocationLong' => $defaultEditMapLocationLong,
			'defaultEditMapZoom' => $defaultEditMapZoom,
			'defaultEditMapMarker' => $defaultEditMapMarker,
		);

		return $data;

	}

	public function getlocations_callback() {

		// if( $_SERVER['REQUEST_METHOD'] == 'post' && isset($_POST['position']) ){

		global $wpdb;

		//return $_SERVER['REQUEST_METHOD'];
		// return json_encode('FEito');
		// exit;

		$position = explode(',', $_POST['position']);

		$lat = $position[0];
		$lng = $position[1];
		$radius = "1";

		$queryProximity = "SELECT *,

			        pm1.meta_value as _pos_state,
			        pm2.meta_value as _pos_city,
			        pm3.meta_value as _pos_neighborhood,
			        pm7.meta_value as _pos_latitude,
		        	pm8.meta_value as _pos_longitude,

		        	6371 * acos( cos( radians({$lat}) ) * cos( radians( pm7.meta_value ) ) * cos( radians ( pm8.meta_value ) - radians($lng) ) + sin( radians({$lat}) ) * sin( radians ( pm7.meta_value ) ) )  as 'distance'

					FROM " . $wpdb->base_prefix . "posts

					LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm1 ON (" . $wpdb->base_prefix . "posts.ID = pm1.post_id AND pm1.meta_key='_pos_state')
					LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm2 ON (" . $wpdb->base_prefix . "posts.ID = pm2.post_id AND pm2.meta_key='_pos_city')
					LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm3 ON (" . $wpdb->base_prefix . "posts.ID = pm3.post_id AND pm3.meta_key='_pos_neighborhood')
			        LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm7 ON (" . $wpdb->base_prefix . "posts.ID = pm7.post_id AND pm7.meta_key='_pos_latitude')
		        	LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm8 ON (" . $wpdb->base_prefix . "posts.ID = pm8.post_id AND pm8.meta_key='_pos_longitude')

					WHERE " . $wpdb->base_prefix . "posts.post_type = 'point_of_sale'
					AND " . $wpdb->base_prefix . "posts.post_status = 'publish'

					HAVING distance < {$radius}

					ORDER BY distance ASC";

		$city = $wpdb->get_results($queryProximity, ARRAY_A);

		$query = "SELECT *,

			        pm1.meta_value as _pos_state,
			        pm2.meta_value as _pos_city,
			        pm3.meta_value as _pos_neighborhood,
			        pm4.meta_value as _pos_street,
			        pm5.meta_value as _pos_number,
			        pm6.meta_value as _pos_postal_code,
			        pm7.meta_value as _pos_email,
			        pm8.meta_value as _pos_phone,
			        pm9.meta_value as _pos_marker,
			        pm10.meta_value as _pos_latitude,
		        	pm11.meta_value as _pos_longitude


					FROM " . $wpdb->base_prefix . "posts


					LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm1 ON (" . $wpdb->base_prefix . "posts.ID = pm1.post_id AND pm1.meta_key='_pos_state')
					LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm2 ON (" . $wpdb->base_prefix . "posts.ID = pm2.post_id AND pm2.meta_key='_pos_city')
					LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm3 ON (" . $wpdb->base_prefix . "posts.ID = pm3.post_id AND pm3.meta_key='_pos_neighborhood')
					LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm4 ON (" . $wpdb->base_prefix . "posts.ID = pm4.post_id AND pm4.meta_key='_pos_street')
			        LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm5 ON (" . $wpdb->base_prefix . "posts.ID = pm5.post_id AND pm5.meta_key='_pos_number')
			        LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm6 ON (" . $wpdb->base_prefix . "posts.ID = pm6.post_id AND pm6.meta_key='_pos_postal_code')
			        LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm7 ON (" . $wpdb->base_prefix . "posts.ID = pm7.post_id AND pm7.meta_key='_pos_email')
			        LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm8 ON (" . $wpdb->base_prefix . "posts.ID = pm8.post_id AND pm8.meta_key='_pos_phone')
			        LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm9 ON (" . $wpdb->base_prefix . "posts.ID = pm9.post_id AND pm9.meta_key='_pos_marker')
			        LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm10 ON (" . $wpdb->base_prefix . "posts.ID = pm10.post_id AND pm10.meta_key='_pos_latitude')
		        	LEFT JOIN " . $wpdb->base_prefix . "postmeta AS pm11 ON (" . $wpdb->base_prefix . "posts.ID = pm11.post_id AND pm11.meta_key='_pos_longitude')

					WHERE " . $wpdb->base_prefix . "posts.post_type = 'point_of_sale'";

		if (isset($city[0]['_pos_city']) AND !empty($city[0]['_pos_city'])) {
			if (!isset($_POST['last_clicked']) || $_POST['last_clicked'] == 'states') {
				$query .= " AND pm1.meta_value 		 = '{$city[0]['_pos_state']}'";
			} else {
				$query .= " AND pm2.meta_value 		 = '{$city[0]['_pos_city']}'";
			}
		}

		$query .= " AND " . $wpdb->base_prefix . "posts.post_status = 'publish'
					ORDER BY pm2.meta_value ASC";

		$results = $wpdb->get_results($query, OBJECT);

		if (count($results) > 0) {
			$response["success"] = true;
			foreach ($results as $result) {

				$content = @$result->_pos_street . ", " . @$result->_pos_number . "<br/>";
				$content .= @$result->_pos_city . " - " . @$result->_pos_state . "<br/>";
				$content .= @$result->_pos_postal_code;

				$markers_url = rwmb_meta('_pos_marker', 'type=image_advanced', $result->post_id);
				// KEP THE META_BOX PLUGIN UPDATED, PLEASE!!!

				if (is_array($markers_url)) {
					$icon_url = end($markers_url); // LAST ITEM
					$icon_url = $icon_url['full_url'];
				} else {
					$icon_url = '';
				}

				$more_info = array();
				if (isset($result->_pos_phone)) {
					$more_info[] = $result->_pos_phone;
				}
				if (isset($result->_pos_email)) {
					$more_info[] = $result->_pos_email;
				}
				$more_info = "<br/><small>" . implode(' | ', $more_info) . "</small>";

				$response['markers'][] = array(
					"name" => $result->post_title,
					"content" => $content,
					"more-info" => $more_info,
					"icon_url" => (!empty($icon_url)) ? $icon_url : '',
					"lat" => $result->_pos_latitude,
					"lng" => $result->_pos_longitude,
				);

			}
		} else {
			$response = array("success" => 'false', "message" => "Nada encontrado próximo ao endereço buscado.");
		}

		echo json_encode($response);

		wp_die(); // this is required to terminate immediately and return a proper response
	}

}