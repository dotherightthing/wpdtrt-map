<?php
/**
 * Plugin sub class.
 *
 * @package wpdtrt_map
 * @version 0.1.0
 * @since   0.8.3 DTRT WordPress Plugin Boilerplate Generator
 */

/**
 * Extend the base class to inherit boilerplate functionality.
 * Adds application-specific methods.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
class WPDTRT_Map_Plugin extends DoTheRightThing\WPDTRT_Plugin_Boilerplate\r_1_5_6\Plugin {

	/**
	 * Supplement plugin initialisation.
	 *
	 * @param     array $options Plugin options.
	 * @since     1.0.0
	 * @version   1.1.0
	 */
	public function __construct( $options ) {

		// edit here.
		parent::__construct( $options );
	}

	/**
	 * ====== WordPress Integration ======
	 */

	/**
	 * Supplement plugin's WordPress setup.
	 * Note: Default priority is 10. A higher priority runs later.
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference Action order
	 */
	protected function wp_setup() {

		parent::wp_setup();

		// add actions and filters here.
		add_action( 'wp_head', [ $this, 'render_css_head' ] );
		add_action( 'wp_head', [ $this, 'render_js_head' ] );
		add_action( 'admin_head', [ $this, 'render_css_head' ] );
		add_action( 'admin_head', [ $this, 'render_js_head' ] );
		add_action( 'acf/init', [ $this, 'set_acf_field_groups' ] );
		add_filter( 'acf/fields/google_map/api', [ $this, 'set_acf_google_map_api_key' ] );
	}

	/**
	 * ====== Getters and Setters ======
	 */

	/**
	 * Get the real acf_map field, or the mock_acf_map for the settings page
	 *
	 * @return $acf_map
	 *
	 * @todo limit output to settings page (https://github.com/dotherightthing/wpdtrt-plugin/issues/91)
	 */
	public function get_acf_map() {

		// the map location _was_ 'picked' using the ACF Map field.
		// $acf_map = get_field('wpdtrt_map_acf_google_map_location').
		// the post object.
		global $post;

		// it can also be mocked using the demo_shortcode_params.
		$demo_shortcode_options = $this->get_demo_shortcode_params();

		// get geotag from image.
		$featured_image_latlng = $this->get_featured_image_latlng( $post );

		if ( 2 === count( $featured_image_latlng ) ) {
			$acf_map = array(
				'address' => __( 'Test 1', 'wpdtrt-map' ),
				'lat'     => $featured_image_latlng['latitude'],
				'lng'     => $featured_image_latlng['longitude'],
			);
		} else {
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
	 * Get the latitude and longitude from a post's/page's featured image.
	 * to obtain a historical forecast for this location.
	 *
	 * @since       0.1.0
	 * @version     1.0.0
	 * @param       object $post Post object.
	 * @return      array ('latitude', 'longitude')
	 *
	 * @uses https://github.com/dotherightthing/wpdtrt-exif
	 * @see  https://github.com/dotherightthing/wpdtrt-weather
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
	 * Register API key with ACF renderer
	 *
	 * @param object $api ACF Google Map API field object.
	 * @return $api
	 *
	 * @see https://www.advancedcustomfields.com/resources/google-map/
	 *
	 * @example
	 *  add_filter('acf/fields/google_map/api', [$this, 'set_acf_google_map_api_key']);
	 */
	public function set_acf_google_map_api_key( $api ) {

		$plugin_options = $this->get_plugin_options();

		$api['key'] = $plugin_options['google_javascript_maps_api_key']['value'];

		return $api;
	}

	/**
	 * Register backend field groups with ACF renderer
	 *  Custom Fields > Tools > Export Field Groups > Generate export code
	 *  Note: ACF admin menu is toggled on/off in wpdtrt/library/acf.php.
	 *
	 * @todo Add support for dynamic custom post types (#8)
	 */
	public function set_acf_field_groups() {

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
	 * ===== Renderers =====
	 */

	/**
	 * Load CSS in page head
	 *
	 * Functions wp_enqueue_scripts + enqueue_style can't be used here
	 * as SRI integrity metadata isn't currently supported by WP
	 * Subresource Integrity Hashes "defines a mechanism by which user agents may verify
	 *  that a fetched resource has been delivered without unexpected manipulation."
	 *
	 * @see https://www.w3.org/TR/SRI/
	 * @see https://core.trac.wordpress.org/ticket/22249
	 * @see https://github.com/dotherightthing/wpdtrt-plugin/issues/78
	 *
	 * @example
	 *  add_action( 'wp_head', [$this, 'render_css_head'] );
	 */
	public function render_css_head() {

		$acf_map = $this->get_acf_map();

		if ( ! $acf_map ) {
			return;
		}

		// "Include Leaflet CSS file in the head section of your document:"
		$style  = '';
		$style .= '<link';
		$style .= ' rel="stylesheet"'; // phpcs:ignore
		$style .= ' href="https://unpkg.com/leaflet@1.3.1/dist/leaflet.css"';
		$style .= ' integrity="sha512-Rksm5RenBEKSKFjgI3a41vrjkw4EVPlJ3+OiI65vTjIdo9brlAacEuKOiQ5OFh7cOI1bkDwLqdLw3Zg0cRJAAQ=="';
		$style .= ' crossorigin=""';
		$style .= ' />';

		echo $style;
	}

	/**
	 * Load JS in page head
	 *
	 * Functions wp_enqueue_scripts + wp_enqueue_script can't be used here
	 * as SRI integrity metadata isn't currently supported by WP
	 * Subresource Integrity Hashes "defines a mechanism by which user agents may verify
	 *  that a fetched resource has been delivered without unexpected manipulation."
	 *
	 * @see https://www.w3.org/TR/SRI/
	 * @see https://core.trac.wordpress.org/ticket/22249
	 * @see https://www.advancedcustomfields.com/resources/google-map/
	 * @todo Can this resource be pulled without sending the SRI attributes, as jQuery also uses SRI now
	 */
	public function render_js_head() {

		$acf_map = $this->get_acf_map();

		if ( ! $acf_map ) {
			return;
		}

		$plugin_options = $this->get_plugin_options();
		$messages       = $this->get_messages();

		// https://www.mapbox.com/studio/account/tokens/.
		$mapbox_api_token         = $plugin_options['mapbox_api_token']['value'];
		$mapbox_api_token_warning = $messages['mapbox_api_token_warning'];
		$script                   = '';

		if ( isset( $mapbox_api_token ) && ( '' !== $mapbox_api_token ) ) {
			// "Include Leaflet JavaScript file after Leaflet’s CSS".
			// Note: actual embed code is in shortcode template content-map.php.
			$script .= '<script';
			$script .= ' src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js"';
			$script .= ' integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw=="';
			$script .= ' crossorigin=""';
			$script .= '>';
			$script .= '</script>';
		} else {
			$script .= '<script>';
			$script .= 'jQuery(window).on("load", function() {';
			$script .= 'jQuery(".wpdtrt-map-embed").append("<p>' . $mapbox_api_token_warning . '</p>");';
			$script .= '});';
			$script .= '</script>';
		}

		echo $script;
	}

	/**
	 * ===== Filters =====
	 */

	/**
	 * ===== Helpers =====
	 */
}
