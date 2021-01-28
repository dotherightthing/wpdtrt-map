<?php
/**
 * File: src/class-wpdtrt-map-plugin.php
 *
 * Plugin sub class.
 *
 * Since:
 *   0.8.13 - DTRT WordPress Plugin Boilerplate Generator
 */

/**
 * Class: WPDTRT_Map_Plugin
 *
 * Extends the base class to inherit boilerplate functionality, adds application-specific methods.
 *
 * Since:
 *   0.8.13 - DTRT WordPress Plugin Boilerplate Generator
 */
class WPDTRT_Map_Plugin extends DoTheRightThing\WPDTRT_Plugin_Boilerplate\r_1_7_13\Plugin {

	/**
	 * Constructor: __construct
	 *
	 * Supplement plugin initialisation.
	 *
	 * Parameters:
	 *   $options - Plugin options
	 *
	 * Since:
	 *   0.8.13 - DTRT WordPress Plugin Boilerplate Generator
	 */
	public function __construct( $options ) { // phpcs:disable

		// edit here.
		parent::__construct( $options );
	}

	/**
	 * Group: WordPress Integration
	 * _____________________________________
	 */

	/**
	 * Function: wp_setup
	 *
	 * Supplement plugin's WordPress setup.
	 *
	 * Note:
	 * - Default priority is 10. A higher priority runs later.
	 *
	 * See:
	 * - <Action order: https://codex.wordpress.org/Plugin_API/Action_Reference>
	 *
	 * Since:
	 *   0.8.13 - DTRT WordPress Plugin Boilerplate Generator
	 */
	protected function wp_setup() {

		parent::wp_setup();

		// About: add actions and filters here.
		add_action( 'wp_head', [ $this, 'render_css_head' ] );
		add_action( 'wp_head', [ $this, 'render_js_head' ] );
		add_action( 'admin_head', [ $this, 'render_css_head' ] );
		add_action( 'admin_head', [ $this, 'render_js_head' ] );
		add_action( 'acf/init', [ $this, 'set_acf_field_groups' ] );
		add_filter( 'acf/fields/google_map/api', [ $this, 'set_acf_google_map_api_key' ] );
	}

	/**
	 * Group: Getters and Setters
	 * _____________________________________
	 */

	/**
	 * Function: get_acf_map
	 *
	 * Get the real acf_map field, or the mock_acf_map for the settings page.
	 *
	 * Returns:
	 *   $acf_map - The field array
	 */
	public function get_acf_map() : array {

		// the post object.
		global $post;
		$acf_map = array();

		// Check for ACF function.
		if ( function_exists( 'get_field' ) ) {
			// the map location _was_ 'picked' using the ACF Map field.
			$acf_latlng = get_field( 'wpdtrt_map_acf_google_map_location' );
		}

		// get geotag from image.
		$featured_image_latlng = $this->get_featured_image_latlng( $post );

		if ( isset( $acf_latlng ) && is_array( $acf_latlng ) && ( count( $acf_latlng ) >= 3 ) ) {
			// use ACF map if a location was set using this.
			$acf_map = $acf_latlng;
		} elseif ( 2 === count( $featured_image_latlng ) ) {
			// else use featured image geotag if it exists.
			$acf_map = array(
				'address' => __( 'Test 1', 'wpdtrt-map' ),
				'lat'     => $featured_image_latlng['latitude'],
				'lng'     => $featured_image_latlng['longitude'],
			);
		} else {
			// else this is just a demo - TODO check if it is
			// it can also be mocked using the demo_shortcode_params.
			$demo_shortcode_options = $this->get_demo_shortcode_params();

			// shortcode demo on options page.
			if ( is_admin() && array_key_exists( 'mock_acf_map', $demo_shortcode_options ) ) {
				$mock_acf_map = $demo_shortcode_options['mock_acf_map'];
				$address      = $mock_acf_map['address'];
				$coordinates  = $mock_acf_map['lat'] . ',' . $mock_acf_map['lng'];

				if ( isset( $address ) && isset( $coordinates ) ) {
					$acf_map = $mock_acf_map;
				}
			}
		}

		return $acf_map;
	}

	/**
	 * Function: get_featured_image_latlng
	 *
	 * Get the latitude and longitude from a post's/page's featured image.
	 * to obtain a historical forecast for this location.
	 *
	 * Parameters:
	 *   (object) $post - Post object.
	 *
	 * Returns:
	 *   (array) - ['latitude', 'longitude']
	 *
	 * Uses:
	 *   <https://github.com/dotherightthing/wpdtrt-exif>
	 *
	 * See:
	 *   <https://github.com/dotherightthing/wpdtrt-weather>
	 */
	public function get_featured_image_latlng( $post ) {

		$lat_lng = array();

		if ( ! class_exists( 'WPDTRT_Exif_Plugin' ) ) {
			return $lat_lng;
		} elseif ( ! method_exists( 'WPDTRT_Exif_Plugin', 'get_attachment_metadata_gps' ) ) {
			return $lat_lng;
		} elseif ( ! isset( $post ) ) {
			return $lat_lng;
		}

		global $wpdtrt_exif_plugin; // created by wpdtrt-exif.php.

		$featured_image_id       = get_post_thumbnail_id( $post->ID );
		$attachment_metadata     = wp_get_attachment_metadata( $featured_image_id, false ); // core meta.
		$attachment_metadata_gps = $wpdtrt_exif_plugin->get_attachment_metadata_gps( $attachment_metadata, 'number', $post );

		if ( ! isset( $attachment_metadata_gps['latitude'], $attachment_metadata_gps['longitude'] ) ) {
			return array();
		}

		$lat_lng = array(
			'latitude'  => $attachment_metadata_gps['latitude'],
			'longitude' => $attachment_metadata_gps['longitude'],
		);

		return $lat_lng;
	}

	/**
	 * Function: get_acf_google_map_api_key
	 *
	 * Get the API key to use with the ACF renderer.
	 *
	 * Returns:
	 *   (string) $key - ACF Google Map API key
	 */
	public function get_acf_google_map_api_key() {
		$api_key            = '';
		$plugin_options     = $this->get_plugin_options();

		if ( array_key_exists( 'value', $plugin_options['google_javascript_maps_api_key'] ) ) {
			$api_key = $plugin_options['google_javascript_maps_api_key']['value'];
		}

		return $api_key;
	}

	/**
	 * Function: set_acf_google_map_api_key
	 *
	 * Register API key with ACF renderer.
	 *
	 * Parameters:
	 *   (object) $api - ACF Google Map API field object.
	 *
	 * Returns:
	 *   (object) $api - ACF Google Map API field object.
	 *
	 * See:
	 *   <https://www.advancedcustomfields.com/resources/acf-fields-google_map-api/>
	 *
	 * Example:
	 * --- PHP
	 * add_filter('acf/fields/google_map/api', [$this, 'set_acf_google_map_api_key']);
	 * ---
	 */
	public function set_acf_google_map_api_key( $api ) {
		$api_key = $this->get_acf_google_map_api_key();
		$api['key'] = $api_key;

		return $api;
	}

	/**
	 * Function: set_acf_field_groups
	 *
	 * Register backend field groups with ACF renderer
	 *
	 * Custom Fields > Tools > Export Field Groups > Generate export code
	 *
	 * Note: ACF admin menu is toggled on/off in wpdtrt/library/acf.php.
	 */
	public function set_acf_field_groups() {
		// For ACF Pro
		// see https://highrise.digital/blog/acf-and-the-google-maps-api-error/
		// unsure if this is required in latest ACF Pro
		// $api_key = $this->get_acf_google_map_api_key();
		// acf_update_setting('google_api_key', $api_key);

		if ( function_exists( 'acf_add_local_field_group' ) ) :
			acf_add_local_field_group(array(
				'key'                   => 'group_5add0cee51f23',
				'title'                 => 'DTRT Map',
				'fields'                => array(
					array(
						'key'               => 'field_5add0cf2900cd',
						'label'             => 'Map location',
						'name'              => 'wpdtrt_map_acf_google_map_location',
						'type'              => 'google_map',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'center_lat'        => '',
						'center_lng'        => '',
						'zoom'              => 16,
						'height'            => 500,
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'post',
						),
					),
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'page',
						),
					),
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'tourdiaries',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => 1,
				'description'           => '',
			));

			endif;
	}

	/**
	 * Group: Renderers
	 * _____________________________________
	 */

	/**
	 * Function: render_css_head
	 *
	 * Load CSS in page head
	 *
	 * Example:
	 * --- PHP
	 * add_action( 'wp_head', [$this, 'render_css_head'] );
	 * --
	 */
	public function render_css_head() {

		$acf_map = $this->get_acf_map();

		if ( ! $acf_map ) {
			return;
		}

		// "Include the JavaScript and CSS files in the <head> of your HTML file. "
		$style  = '';
		$style .= '<link';
		$style .= ' rel="stylesheet"'; // phpcs:ignore
		$style .= ' href="https://api.mapbox.com/mapbox-gl-js/v2.0.0/mapbox-gl.css"';
		$style .= ' />';

		echo $style;
	}

	/**
	 * Function: render_js_head
	 *
	 * Load JS in page head.
	 */
	public function render_js_head() {

		$acf_map = $this->get_acf_map();

		if ( ! $acf_map ) {
			return;
		}

		$plugin_options           = $this->get_plugin_options();
		$messages                 = $this->get_messages();
		$mapbox_api_token_warning = $messages['mapbox_api_token_warning'];
		$script                   = '';

		// https://www.mapbox.com/studio/account/tokens/.
		if ( array_key_exists( 'value', $plugin_options['mapbox_api_token'] ) ) {
			$mapbox_api_token = $plugin_options['mapbox_api_token']['value'];
		}

		if ( isset( $mapbox_api_token ) && ( '' !== $mapbox_api_token ) ) {
			// "Include the JavaScript and CSS files in the <head> of your HTML file."
			// Note: actual embed code is in shortcode template content-map.php.
			$script .= '<script';
			$script .= ' src="https://api.mapbox.com/mapbox-gl-js/v2.0.0/mapbox-gl.js"';
			$script .= ' id="wpdtrt-map-js"';
			$script .= '>';
			$script .= '</script>';
		} else {
			$script .= '<script id="wpdtrt-map-js">';
			$script .= 'console.warn( "wpdtrt-map: ' . $mapbox_api_token_warning . '" );';
			$script .= '</script>';
		}

		echo $script;
	}

	/**
	 * Group: Filters
	 * _____________________________________
	 */

	/**
	 * Group: Helpers
	 * _____________________________________
	 */
}
