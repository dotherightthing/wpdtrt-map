<?php
/**
 * Plugin sub class.
 *
 * @package     wpdtrt_map
 * @version 	0.0.1
 * @since       0.7.5
 */

/**
 * Plugin sub class.
 *
 * Extends the base class to inherit boilerplate functionality.
 * Adds application-specific methods.
 *
 * @version 	0.0.1
 * @since       0.7.5
 */
class WPDTRT_Map_Plugin extends DoTheRightThing\WPPlugin\Plugin {

    /**
     * Hook the plugin in to WordPress
     * This constructor automatically initialises the object's properties
     * when it is instantiated,
     * using new WPDTRT_Weather_Plugin
     *
     * @param     array $settings Plugin options
     *
	 * @version 	0.0.1
     * @since       0.7.5
     */
    function __construct( $settings ) {

    	// add any initialisation specific to wpdtrt-map here

		// Instantiate the parent object
		parent::__construct( $settings );
    }

    //// START WORDPRESS INTEGRATION \\\\

    /**
     * Initialise plugin options ONCE.
     *
     * @param array $default_options
     *
     * @version     0.0.1
     * @since       0.7.5
     */
    protected function wp_setup() {

    	parent::wp_setup();

		// add actions and filters here
        add_action( 'wp_head', [$this, 'render_css_head'] );
        add_action( 'wp_head', [$this, 'render_js_head'] );
        add_action( 'acf/init', [$this, 'set_acf_field_groups'] );
        add_filter( 'acf/fields/google_map/api', [$this, 'set_acf_google_map_api_key'] );
    }

    //// END WORDPRESS INTEGRATION \\\\

    //// START SETTERS AND GETTERS \\\\

    /**
     * Register API key with ACF renderer
     *
     * @param $api
     * @return $api
     *
     * @see https://www.advancedcustomfields.com/resources/google-map/
     *
     * @example
     *  add_filter('acf/fields/google_map/api', [$this, 'set_acf_google_map_api_key']);
     */
    public function set_acf_google_map_api_key( $api ) {

        $plugin_options = $this->get_plugin_options();

        $api['key'] = $plugin_options['google_static_maps_api_key'];

        return $api;
    }

    /**
     * Register backend field groups with ACF renderer
     *  Custom Fields > Tools > Export Field Groups > Generate export code
     */
    public function set_acf_field_groups() {

        if( function_exists('acf_add_local_field_group') ):

            acf_add_local_field_group(array(
                'key' => 'group_5add0cee51f23',
                'title' => 'DTRT Map',
                'fields' => array(
                    array(
                        'key' => 'field_5add0cf2900cd',
                        'label' => 'Map location',
                        'name' => 'wpdtrt_map_acf_google_map_location',
                        'type' => 'google_map',
                        'instructions' => '',
                        'required' => 0,
                        'conditional_logic' => 0,
                        'wrapper' => array(
                            'width' => '',
                            'class' => '',
                            'id' => '',
                        ),
                        'center_lat' => '',
                        'center_lng' => '',
                        'zoom' => 16,
                        'height' => 500,
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'post',
                        ),
                    ),
                    array(
                        array(
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'page',
                        ),
                    ),
                ),
                'menu_order' => 0,
                'position' => 'normal',
                'style' => 'default',
                'label_placement' => 'top',
                'instruction_placement' => 'label',
                'hide_on_screen' => '',
                'active' => 1,
                'description' => '',
            ));

        endif;
    }

    //// END SETTERS AND GETTERS \\\\

    //// START RENDERERS \\\\

    /**
     * Load CSS in page head
     *
     * wp_enqueue_scripts + enqueue_style can't be used here
     * as SRI integrity metadata isn't currently supported by WP
     * Subresource Integrity Hashes "defines a mechanism by which user agents may verify
     *  that a fetched resource has been delivered without unexpected manipulation."
     * @see https://www.w3.org/TR/SRI/
     * @see https://core.trac.wordpress.org/ticket/22249
     * @see https://github.com/dotherightthing/wpdtrt-plugin/issues/78
     *
     * @example
     *  add_action( 'wp_head', [$this, 'render_css_head'] );
     */
    public function render_css_head() {

        $acf_map = get_field('wpdtrt_map_acf_google_map_location');

        if ( ! $acf_map ) {
            return;
        }

        // "Include Leaflet CSS file in the head section of your document:"
        $style = '';
        $style .= '<link';
        $style .= ' rel="stylesheet"';
        $style .= ' href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css"';
        $style .= ' integrity="sha512-M2wvCLH6DSRazYeZRIm1JnYyh22purTM+FDB5CsyxtQJYeKq83arPe5wgbNmcFXGqiSH2XR8dT/fJISVA1r/zQ=="';
        $style .= ' crossorigin=""';
        //$style .= ' ver=""';
        $style .= ' />';

        echo $style;
    }

    /**
     * Load JS in page head
     *
     * wp_enqueue_scripts + wp_enqueue_script can't be used here
     * as SRI integrity metadata isn't currently supported by WP
     * Subresource Integrity Hashes "defines a mechanism by which user agents may verify
     *  that a fetched resource has been delivered without unexpected manipulation."
     * @see https://www.w3.org/TR/SRI/
     * @see https://core.trac.wordpress.org/ticket/22249
     * @see https://www.advancedcustomfields.com/resources/google-map/
     * @todo Can this resource be pulled without sending the SRI attributes, as jQuery also uses SRI now
     */
    public function render_js_head() {

        /**
         * ACF's Google Map field type supports geocoding
         * so entering an address there will generate
         * latitide and longitude
         * @see https://stackoverflow.com/questions/27186167/set-view-for-an-array-of-addressesno-coordinates-using-leaflet-js
         * @see https://www.advancedcustomfields.com/resources/google-map/
         */
        $acf_map = get_field('wpdtrt_maps_acf_google_map');

        if ( ! $acf_map ) {
            return;
        }

        $plugin_options = $this->get_plugin_options();

        // https://www.mapbox.com/studio/account/tokens/
        $mapbox_api_token = $plugin_options['mapbox_api_token'];

        $coordinates = $acf_map['lat'] . ', ' . $acf_map['lng'];
        $address = $acf_map['address'];

        // "Include Leaflet JavaScript file after Leaflet’s CSS"
        $script = '';
        $script .= '<script';
        $script .= ' src="https://unpkg.com/leaflet@1.2.0/dist/leaflet.js"';
        $script .= ' integrity="sha512-lInM/apFSqyy1o6s89K4iQUKg6ppXEgsVxT35HbzUupEVRh2Eu9Wdl4tHj7dZO0s1uvplcYGmt3498TtHq+log=="';
        $script .= ' crossorigin=""';
        $script .= '>';
        $script .= '</script>';
        $script .= '<script>';
        $script .= 'var mymap = L.map("wpdtrt-map-1", { zoomControl: false }).setView([' . $coordinates . '], 16);';
        $script .= 'L.tileLayer("https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}", {';
        $script .= 'attribution: "Map data &copy; <a href=\"http://openstreetmap.org\">OpenStreetMap</a> contributors, <a href=\"http://creativecommons.org/licenses/by-sa/2.0/\">CC-BY-SA</a>, Imagery © <a href=\"http://mapbox.com\">Mapbox</a>",';
        //$script .= 'maxZoom: 18,';
        $script .= 'id: "mapbox.streets",';
        $script .= 'accessToken: "' . $mapbox_api_token . '"';
        $script .= '}).addTo(mymap);';
        $script .= 'var marker = L.marker([' . $coordinates . ']).addTo(mymap);';
        // http://leafletjs.com/examples/choropleth/
        $script .= 'var legend = L.control({ position: "topleft" });';
        $script .= 'legend.onAdd = function (map) {';
        // tagname:div, classname:info legend
          $script .= 'var div = L.DomUtil.create("div", "wpdtrt-map-legend");';
          $script .= 'div.innerHTML = "' . $address . '";';
          $script .= 'return div;';
          $script .= '};';
          $script .= 'legend.addTo(mymap);';
        // https://www.mapbox.com/mapbox.js/example/v1.0.0/change-zoom-control-location/
        $script .= 'new L.Control.Zoom({ position: "bottomleft" }).addTo(mymap);';
        $script .= '</script>';

        echo $script;
    }

    //// END RENDERERS \\\\

    //// START FILTERS \\\\
    //// END FILTERS \\\\

    //// START HELPERS \\\\
    //// END HELPERS \\\\
}

?>