<?php
/**
 * Generate a shortcode, to embed the widget inside a content area.
 *
 * This file contains PHP.
 *
 * @link        https://github.com/dotherightthing/wpdtrt-maps
 * @link        https://generatewp.com/shortcodes/
 * @since       0.1.0
 *
 * @example     [wpdtrt_maps number="4" enlargement="yes"]
 * @example     do_shortcode( '[wpdtrt_maps number="4" enlargement="yes"]' );
 *
 * @package     Wpdtrt_Maps
 * @subpackage  Wpdtrt_Maps/app
 */

if ( !function_exists( 'wpdtrt_maps_shortcode' ) ) {

  /**
   * add_shortcode
   * @param       string $tag
   *    Shortcode tag to be searched in post content.
   * @param       callable $func
   *    Hook to run when shortcode is found.
   *
   * @since       0.1.0
   * @uses        ../../../../wp-includes/shortcodes.php
   * @see         https://codex.wordpress.org/Function_Reference/add_shortcode
   * @see         http://php.net/manual/en/function.ob-start.php
   * @see         http://php.net/manual/en/function.ob-get-clean.php
   */
  function wpdtrt_maps_shortcode( $atts, $content = null ) {

    // the ACF location picker
    $acf_map = get_field('wpdtrt_maps_acf_google_map');

    // if there is no location picker, then we can't display a map location
    if ( ! $acf_map ) {
      return;
    }
    else {
      $coordinates = $acf_map['lat'] . ', ' . $acf_map['lng'];
    }

    // post object to get info about the post in which the shortcode appears
    global $post;

    // predeclare variables
    $before_widget = null;
    $before_title = null;
    $title = null;
    $after_title = null;
    $after_widget = null;
    $id = null;
    $link_text = null;
    $shortcode = 'wpdtrt_maps';

    /**
     * Combine user attributes with known attributes and fill in defaults when needed.
     * @see https://developer.wordpress.org/reference/functions/shortcode_atts/
     */
    $atts = shortcode_atts(
      array(
        'id' => '1',
        'link_text' => 'View Larger Map',
      ),
      $atts,
      $shortcode
    );

    // only overwrite predeclared variables
    extract( $atts, EXTR_IF_EXISTS );

    /**
     * ob_start — Turn on output buffering
     * This stores the HTML template in the buffer
     * so that it can be output into the content
     * rather than at the top of the page.
     */
    ob_start();

    require(WPDTRT_MAPS_PATH . 'templates/wpdtrt-maps-front-end.php');

    /**
     * ob_get_clean — Get current buffer contents and delete current output buffer
     */
    return ob_get_clean();
  }

  add_shortcode( 'wpdtrt_maps', 'wpdtrt_maps_shortcode' );

}

?>
