<?php
/**
 * JS imports
 *
 * This file contains PHP.
 *
 * @link        https://github.com/dotherightthing/wpdtrt-maps
 * @see         https://codex.wordpress.org/AJAX_in_Plugins
 * @since       0.1.0
 *
 * @package     Wpdtrt_Maps
 * @subpackage  Wpdtrt_Maps/app
 */

if ( !function_exists( 'wpdtrt_maps_frontend_js' ) ) {

  /**
   * Attach JS for front-end widgets and shortcodes
   *    Generate a configuration object which the JavaScript can access.
   *    When an Ajax command is submitted, pass it to our function via the Admin Ajax page.
   *
   * @since       0.1.0
   * @see         https://codex.wordpress.org/AJAX_in_Plugins
   * @see         https://codex.wordpress.org/Function_Reference/wp_localize_script
   */
  function wpdtrt_maps_frontend_js() {

    wp_enqueue_script( 'wpdtrt_maps_frontend_js',
      WPDTRT_MAPS_URL . 'js/wpdtrt-maps.js',
      array('jquery'),
      WPDTRT_MAPS_VERSION,
      true
    );

    wp_localize_script( 'wpdtrt_maps_frontend_js',
      'wpdtrt_maps_config',
      array(
        'ajax_url' => admin_url( 'admin-ajax.php' ) // wpdtrt_maps_config.ajax_url
      )
    );

  }

  add_action( 'wp_enqueue_scripts', 'wpdtrt_maps_frontend_js' );

}

/**
 * Load JS
 *
 * wp_enqueue_scripts + wp_enqueue_script can't be used here
 * as SRI integrity metadata isn't currently supported by WP
 * Subresource Integrity Hashes "defines a mechanism by which user agents may verify
 *  that a fetched resource has been delivered without unexpected manipulation."
 * @see https://www.w3.org/TR/SRI/
 * @see https://core.trac.wordpress.org/ticket/22249
 * @see https://www.advancedcustomfields.com/resources/google-map/
 */

if ( ! function_exists('wpdtrt_map_js_leaflet') ) {

  //add_action( 'wp_enqueue_scripts', 'wpdtrt_map_js_leaflet' );
  add_action( 'wp_footer', 'wpdtrt_map_js_leaflet' );

  function wpdtrt_map_js_leaflet() {

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

    // https://www.mapbox.com/studio/account/tokens/
    $mapbox_api_token = 'pk.eyJ1IjoiZG90aGVyaWdodHRoaW5nbnoiLCJhIjoiY2o2NmhuMXN5MGI5ZDJ4cWF2MTJkcWhlcSJ9.D8h1TOJEY25i8B8ruDmvjg';

    //wpdtrt_log( $acf_map );
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

}

?>
