<?php
/**
 * CSS imports
 *
 * This file contains PHP.
 *
 * @link        https://github.com/dotherightthing/wpdtrt-maps
 * @since       0.1.0
 *
 * @package     Wpdtrt_Maps
 * @subpackage  Wpdtrt_Maps/app
 */

if ( !function_exists( 'wpdtrt_maps_css_backend' ) ) {

  /**
   * Attach CSS for Settings > DTRT Maps
   *
   * @since       0.1.0
   */
  function wpdtrt_maps_css_backend() {

    wp_enqueue_style( 'wpdtrt_maps_css_backend',
      WPDTRT_MAPS_URL . 'css/wpdtrt-maps-admin.css',
      array(),
      WPDTRT_MAPS_VERSION
      //'all'
    );
  }

  add_action( 'admin_head', 'wpdtrt_maps_css_backend' );

}

if ( !function_exists( 'wpdtrt_maps_css_frontend' ) ) {

  /**
   * Attach CSS for front-end widgets and shortcodes
   *
   * @since       0.1.0
   */
  function wpdtrt_maps_css_frontend() {

    wp_enqueue_style( 'wpdtrt_maps_css_frontend',
      WPDTRT_MAPS_URL . 'css/wpdtrt-maps.css',
      array(),
      WPDTRT_MAPS_VERSION
      //'all'
    );

  }

  add_action( 'wp_enqueue_scripts', 'wpdtrt_maps_css_frontend' );

}

/**
 * Load CSS
 *
 * wp_enqueue_scripts + enqueue_style can't be used here
 * as SRI integrity metadata isn't currently supported by WP
 * Subresource Integrity Hashes "defines a mechanism by which user agents may verify
 *  that a fetched resource has been delivered without unexpected manipulation."
 * @see https://www.w3.org/TR/SRI/
 * @see https://core.trac.wordpress.org/ticket/22249
 */

if ( ! function_exists('wpdtrt_map_css_leaflet') ) {

  //add_action( 'wp_enqueue_scripts', 'wpdtrt_map_css_leaflet' );
  add_action( 'wp_head', 'wpdtrt_map_css_leaflet' );

  function wpdtrt_map_css_leaflet() {

    $acf_map = get_field('wpdtrt_acf_page_map');

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

}

?>
