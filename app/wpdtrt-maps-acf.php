<?php
/**
 * ACF integration
 *
 * This file contains PHP.
 *
 * @link        https://github.com/dotherightthing/wpdtrt-maps
 * @since       0.1.0
 *
 * @package     Wpdtrt_Maps
 * @subpackage  Wpdtrt_Maps/app
 *
 * @see https://www.advancedcustomfields.com/resources/google-map/
 */

if ( ! function_exists('wpdtrt_maps_acf_google_map_api') ) {

  add_filter('acf/fields/google_map/api', 'wpdtrt_maps_acf_google_map_api');

  function wpdtrt_maps_acf_google_map_api( $api ){

    /**
     * Load the plugin data stored in the WP Options table
     * Retrieves an option value based on an option name.
     * @example get_option( string $option, mixed $default = false )
     */
    $wpdtrt_maps_options = get_option( 'wpdtrt_maps' );

    // if options have been stored, recover them
    if ( $wpdtrt_maps_options !== '' ) {

      $wpdtrt_maps_google_api_key = $wpdtrt_maps_options['wpdtrt_maps_google_api_key'];

    	$api['key'] = $wpdtrt_maps_google_api_key;
    }
    else {

    	$api['key'] = '';
    }

    return $api;

  }
}

/**
 * ACF - Export Field Groups to PHP
 * Custom Fields > Tools > Export Field Groups > Generatee export code
 */
if ( function_exists('acf_add_local_field_group') ) {

  acf_add_local_field_group(array (
    'key' => 'group_598fb16da2503',
    'title' => 'DTRT Maps',
    'fields' => array (
      array (
        'key' => 'field_598fb1807c625',
        'label' => 'Map location',
        'name' => 'wpdtrt_maps_acf_google_map',
        'type' => 'google_map',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array (
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
    'location' => array (
      array (
        array (
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

}

?>
