<?php
/**
 * Plugin Name:  DTRT Map
 * Plugin URI:   https://github.com/dotherightthing/wpdtrt-map
 * Description:  Embed an interactive map.
 * Version:      0.3.0
 * Author:       Dan Smith
 * Author URI:   https://profiles.wordpress.org/dotherightthingnz
 * License:      GPLv2 or later
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  wpdtrt-map
 * Domain Path:  /languages
 */

require_once plugin_dir_path( __FILE__ ) . "vendor/autoload.php";

/**
 * Constants
 * WordPress makes use of the following constants when determining the path to the content and plugin directories.
 * These should not be used directly by plugins or themes, but are listed here for completeness.
 * WP_CONTENT_DIR  // no trailing slash, full paths only
 * WP_CONTENT_URL  // full url
 * WP_PLUGIN_DIR  // full path, no trailing slash
 * WP_PLUGIN_URL  // full url, no trailing slash
 *
 * WordPress provides several functions for easily determining where a given file or directory lives.
 * Always use these functions in your plugins instead of hard-coding references to the wp-content directory
 * or using the WordPress internal constants.
 * plugins_url()
 * plugin_dir_url()
 * plugin_dir_path()
 * plugin_basename()
 *
 * @link https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Constants
 * @link https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Plugins
 */

/**
  * Determine the correct path to the autoloader
  * @see https://github.com/dotherightthing/wpdtrt-plugin/issues/51
  */
if( ! defined( 'WPDTRT_PLUGIN_CHILD' ) ) {
  define( 'WPDTRT_PLUGIN_CHILD', true );
}

if( ! defined( 'WPDTRT_MAP_VERSION' ) ) {
/**
 * Plugin version.
 *
 * WP provides get_plugin_data(), but it only works within WP Admin,
 * so we define a constant instead.
 *
 * @example $plugin_data = get_plugin_data( __FILE__ ); $plugin_version = $plugin_data['Version'];
 * @link https://wordpress.stackexchange.com/questions/18268/i-want-to-get-a-plugin-version-number-dynamically
 *
 * @version   0.0.1
 * @since     0.7.5
 */
  define( 'WPDTRT_MAP_VERSION', '0.3.0' );
}

if( ! defined( 'WPDTRT_MAP_PATH' ) ) {
/**
 * Plugin directory filesystem path.
 *
 * @param string $file
 * @return The filesystem directory path (with trailing slash)
 *
 * @link https://developer.wordpress.org/reference/functions/plugin_dir_path/
 * @link https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
 *
 * @version   0.0.1
 * @since     0.7.5
 */
  define( 'WPDTRT_MAP_PATH', plugin_dir_path( __FILE__ ) );
}

if( ! defined( 'WPDTRT_MAP_URL' ) ) {
/**
 * Plugin directory URL path.
 *
 * @param string $file
 * @return The URL (with trailing slash)
 *
 * @link https://codex.wordpress.org/Function_Reference/plugin_dir_url
 * @link https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything
 *
 * @version   0.0.1
 * @since     0.7.5
 */
  define( 'WPDTRT_MAP_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Enable ACF 5 early access
 * Requires at least version ACF 4.4.12 to work
 */
if( ! defined( 'ACF_EARLY_ACCESS' ) ) {
  define('ACF_EARLY_ACCESS', '5');
}

/**
 * Include plugin logic
 *
 * @version   0.0.1
 * @since     0.7.5
 */

  // base class
  // redundant, but includes the composer-generated autoload file if not already included
  require_once(WPDTRT_MAP_PATH . 'vendor/dotherightthing/wpdtrt-plugin/index.php');

  // classes without composer.json files are loaded via Bower
  //require_once(WPDTRT_MAP_PATH . 'vendor/name/file.php');

  // sub classes
  require_once(WPDTRT_MAP_PATH . 'src/class-wpdtrt-map-plugin.php');
  require_once(WPDTRT_MAP_PATH . 'src/class-wpdtrt-map-widgets.php');

  // log & trace helpers
  $debug = new DoTheRightThing\WPDebug\Debug;

  /**
   * Plugin initialisaton
   *
   * We call init before widget_init so that the plugin object properties are available to it.
   * If widget_init is not working when called via init with priority 1, try changing the priority of init to 0.
   * init: Typically used by plugins to initialize. The current user is already authenticated by this time.
   * └─ widgets_init: Used to register sidebars. Fired at 'init' priority 1 (and so before 'init' actions with priority ≥ 1!)
   *
   * @see https://wp-mix.com/wordpress-widget_init-not-working/
   * @see https://codex.wordpress.org/Plugin_API/Action_Reference
   * @todo Add a constructor function to WPDTRT_Map_Plugin, to explain the options array
   */
  function wpdtrt_map_init() {
    // pass object reference between classes via global
    // because the object does not exist until the WordPress init action has fired
    global $wpdtrt_map_plugin;

    /**
     * Admin settings
     * For array syntax, please view the field documentation:
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-checkbox.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-number.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-password.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-select.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-text.php
     */
    $plugin_options = array(
      // Google Maps is used to render the ACF location picker
      'google_javascript_maps_api_key' => array(
        'type' => 'password',
        'label' => __('Google JavaScript Maps API Key', 'wpdtrt-map'),
        'size' => 50,
        'tip' => __('https://developers.google.com/maps/documentation/javascript/get-api-key', 'wpdtrt-map')
      ),
      // Mapbox is used to render the map embed
      'mapbox_api_token' => array(
        'type' => 'password',
        'label' => __('Mapbox API Token', 'wpdtrt-map'),
        'size' => 120,
        'tip' => __('https://www.mapbox.com/studio/account/tokens/', 'wpdtrt-map')
      )
    );

    /**
     * All options available to Widgets and Shortcodes
     * For array syntax, please view the field documentation:
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-checkbox.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-number.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-password.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-select.php
     * @see https://github.com/dotherightthing/wpdtrt-plugin/blob/master/views/form-element-text.php
     */
    $instance_options = array(
      'unique_id' => array(
        'type' => 'number',
        'size' => 3,
        'label' => __('Unique ID', 'wpdtrt-map'),
        'tip' => __('To prevent conflicts between maps on the same page', 'wpdtrt-map')
      ),
      'enlargement_link_text' => array(
        'type' => 'text',
        'size' => 30,
        'label' => __('Enlargement link text', 'wpdtrt-map'),
        'tip' => __('e.g. View larger map', 'wpdtrt-map')
      )
    );

    $wpdtrt_map_plugin = new WPDTRT_Map_Plugin(
      array(
        'url' => WPDTRT_MAP_URL,
        'prefix' => 'wpdtrt_map',
        'slug' => 'wpdtrt-map',
        'menu_title' => __('Map', 'wpdtrt-map'),
        'developer_prefix' => '',
        'path' => WPDTRT_MAP_PATH,
        'messages' => array(
          'loading' => __('Loading latest data...', 'wpdtrt-map'),
          'success' => __('settings successfully updated', 'wpdtrt-map'),
          'insufficient_permissions' => __('Sorry, you do not have sufficient permissions to access this page.', 'wpdtrt-map'),
          'options_form_title' => __('General Settings', 'wpdtrt-map'),
          'options_form_description' => __('Please enter your preferences.', 'wpdtrt-map'),
          'no_options_form_description' => __('There aren\'t currently any options.', 'wpdtrt-map'),
          'options_form_submit' => __('Save Changes', 'wpdtrt-map'),
          'noscript_warning' => __('Please enable JavaScript', 'wpdtrt-map'),
          'demo_sample_title' => __('Demo sample', 'wpdtrt-map'),
          'demo_data_title' => __('Demo data', 'wpdtrt-map'),
          'demo_shortcode_title' => __('Demo shortcode', 'wpdtrt-map'),
          'demo_data_description' => __('This demo was generated from the following data', 'wpdtrt-map'),
          'demo_date_last_updated' => __('Data last updated', 'wpdtrt-map'),
          'demo_data_length' => __('results', 'wpdtrt-map'),
          'demo_data_displayed_length' => __('results displayed', 'wpdtrt-map'),
          'mapbox_api_token_warning' => __('Please set Mapbox API Token in Settings > Map.', 'wpdtrt-map')
        ),
        'plugin_options' => $plugin_options,
        'instance_options' => $instance_options,
        'version' => WPDTRT_MAP_VERSION,
        'plugin_dependencies' => array(
          array(
            'name' => 'Advanced Custom Fields',
            'slug' => 'advanced-custom-fields',
            'required' => true,
            'is_callable' => array( 'acf_field_google_map', 'render_field' )
          )
        ),
        'demo_shortcode_params' => array(
          'name' => 'wpdtrt_map_shortcode',
          'unique_id' => 1,
          'enlargement_link_text' => 'View larger version',
          'number' => 1,
          'mock_acf_map' => array(
            'address' => __('Seatoun School & Community Emergency Hub Burnham Street, Seatoun, Wellington, New Zealand', 'wpdtrt-map'),
            'lat' => '-41.3264776',
            'lng' => '174.83712689999993'
          )
        )
      )
    );
  }

  add_action( 'init', 'wpdtrt_map_init', 0 );

  /**
   * Register a WordPress widget, passing in an instance of our custom widget class
   * The plugin does not require registration, but widgets and shortcodes do.
   * Note: widget_init fires before init, unless init has a priority of 0
   *
   * @uses        ../../../../wp-includes/widgets.php
   * @see         https://codex.wordpress.org/Function_Reference/register_widget#Example
   * @see         https://wp-mix.com/wordpress-widget_init-not-working/
   * @see         https://codex.wordpress.org/Plugin_API/Action_Reference
   * @uses        https://github.com/dotherightthing/wpdtrt/tree/master/library/sidebars.php
   *
   * @version     0.0.1
   * @since       0.7.5
   * @todo        Add form field parameters to the options array
   * @todo        Investigate the 'classname' option
   */
  function wpdtrt_map_widget_init() {

    global $wpdtrt_map_plugin;

    $wpdtrt_map_widget = new WPDTRT_Map_Widget(
      array(
        'name' => 'wpdtrt_map_widget',
        'title' => __('Map Widget', 'wpdtrt-map'),
        'description' => __('Embed an interactive map.', 'wpdtrt-map'),
        'plugin' => $wpdtrt_map_plugin,
        'template' => 'map',
        'selected_instance_options' => array(
          'unique_id',
          'enlargement_link_text'
        )
      )
    );

    register_widget( $wpdtrt_map_widget );
  }

  add_action( 'widgets_init', 'wpdtrt_map_widget_init' );

  /**
   * Register Shortcode
   */
  function wpdtrt_map_shortcode_init() {

    global $wpdtrt_map_plugin;

    $wpdtrt_map_shortcode = new DoTheRightThing\WPPlugin\Shortcode(
      array(
        'name' => 'wpdtrt_map_shortcode',
        'plugin' => $wpdtrt_map_plugin,
        'template' => 'map',
        'selected_instance_options' => array(
          'unique_id',
          'enlargement_link_text'
        )
      )
    );
  }

  add_action( 'init', 'wpdtrt_map_shortcode_init', 100 );

  /**
   * Register functions to be run when the plugin is activated.
   *
   * @see https://codex.wordpress.org/Function_Reference/register_activation_hook
   *
   * @version   0.0.1
   * @since     0.7.5
   */
  function wpdtrt_map_activate() {
    //wpdtrt_map_rewrite_rules();
    flush_rewrite_rules();
  }

  register_activation_hook(__FILE__, 'wpdtrt_map_activate');

  /**
   * Register functions to be run when the plugin is deactivated.
   *
   * (WordPress 2.0+)
   *
   * @see https://codex.wordpress.org/Function_Reference/register_deactivation_hook
   *
   * @version   0.0.1
   * @since     0.7.5
   */
  function wpdtrt_map_deactivate() {
    flush_rewrite_rules();
  }

  register_deactivation_hook(__FILE__, 'wpdtrt_map_deactivate');

?>
