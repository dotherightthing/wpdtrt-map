<?php
/**
 * DTRT Map
 *
 * @package     WPDTRT_Map
 * @author      Dan Smith
 * @copyright   2018 Do The Right Thing
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:  DTRT Map
 * Plugin URI:   https://github.com/dotherightthing/wpdtrt-map
 * Description:  Embed an interactive map.
 * Version:      0.4.4
 * Author:       Dan Smith
 * Author URI:   https://profiles.wordpress.org/&#39;dotherightthingnz
 * License:      GPLv2 or later
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  wpdtrt-map
 * Domain Path:  /languages
 */

/**
 * Group: Constants
 *
 * Note:
 * - WordPress makes use of the following constants when determining the path to the content and plugin directories.
 *   These should not be used directly by plugins or themes, but are listed here for completeness.
 * - WP_CONTENT_DIR  // no trailing slash, full paths only
 * - WP_CONTENT_URL  // full url
 * - WP_PLUGIN_DIR  // full path, no trailing slash
 * - WP_PLUGIN_URL  // full url, no trailing slash
 * - WordPress provides several functions for easily determining where a given file or directory lives.
 *   Always use these functions in your plugins instead of hard-coding references to the wp-content directory
 *   or using the WordPress internal constants.
 * - plugins_url()
 * - plugin_dir_url()
 * - plugin_dir_path()
 * - plugin_basename()
 *
 * See:
 * - <https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Constants>
 * - <https://codex.wordpress.org/Determining_Plugin_and_Content_Directories#Plugins>
 * _____________________________________
 */

if ( ! defined( 'WPDTRT_MAP_VERSION' ) ) {
	/**
	 * Constant: WPDTRT_MAP_VERSION
	 *
	 * Plugin version.
	 *
	 * Note:
	 * - WP provides get_plugin_data(), but it only works within WP Admin,
	 *   so we define a constant instead.
	 *
	 * See:
	 * - <https://wordpress.stackexchange.com/questions/18268/i-want-to-get-a-plugin-version-number-dynamically>
	 *
	 * Example:
	 * ---php
	 * $plugin_data = get_plugin_data( __FILE__ ); $plugin_version = $plugin_data['Version'];
	 * ---
	 */
	define( 'WPDTRT_MAP_VERSION', '0.4.4' );
}

if ( ! defined( 'WPDTRT_MAP_PATH' ) ) {
	/**
	 * Constant: WPDTRT_MAP_PATH
	 *
	 * Plugin directory filesystem path (with trailing slash).
	 *
	 * See:
	 * - <https://developer.wordpress.org/reference/functions/plugin_dir_path/>
	 * - <https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything>
	 */
	define( 'WPDTRT_MAP_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'WPDTRT_MAP_URL' ) ) {
	/**
	 * Constant: WPDTRT_MAP_URL
	 *
	 * Plugin directory URL path (with trailing slash).
	 *
	 * See:
	 * - <https://codex.wordpress.org/Function_Reference/plugin_dir_url>
	 * - <https://developer.wordpress.org/plugins/the-basics/best-practices/#prefix-everything>
	 */
	define( 'WPDTRT_MAP_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * Constant: WPDTRT_PLUGIN_CHILD
 *
 * Boolean, used to determine the correct path to the PSR-4 autoloader.
 *
 * See:
 * - <https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/51>
 */
if ( ! defined( 'WPDTRT_PLUGIN_CHILD' ) ) {
	define( 'WPDTRT_PLUGIN_CHILD', true );
}

/**
 * Constant: ACF_EARLY_ACCESS
 *
 * Enable ACF 5 early access.
 *
 * Note:
 * - Requires at least version ACF 4.4.12
 */
if ( ! defined( 'ACF_EARLY_ACCESS' ) ) {
	define( 'ACF_EARLY_ACCESS', '5' );
}

/**
 * Determine the correct path to the PSR-4 autoloader.
 *
 * See:
 * - <https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/104>
 * - <https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Options:-Adding-WordPress-plugin-dependencies>
 */
if ( defined( 'WPDTRT_MAP_TEST_DEPENDENCY' ) ) {
	$project_root_path = realpath( __DIR__ . '/../../..' ) . '/';
} else {
	$project_root_path = '';
}

require_once $project_root_path . 'vendor/autoload.php';

/**
 * Replace the TGMPA autoloader
 *
 * See:
 * - <https://github.com/dotherightthing/generator-wpdtrt-plugin-boilerplate#77>
 * - <https://github.com/dotherightthing/wpdtrt-plugin-boilerplate#136>
 */
if ( is_admin() ) {
	require_once $project_root_path . 'vendor/tgmpa/tgm-plugin-activation/class-tgm-plugin-activation.php';
}

// sub classes, not loaded via PSR-4.
// remove the includes you don't need, edit the files you do need.
require_once WPDTRT_MAP_PATH . 'src/class-wpdtrt-map-plugin.php';
require_once WPDTRT_MAP_PATH . 'src/class-wpdtrt-map-rewrite.php';
require_once WPDTRT_MAP_PATH . 'src/class-wpdtrt-map-shortcode.php';
require_once WPDTRT_MAP_PATH . 'src/class-wpdtrt-map-taxonomy.php';
require_once WPDTRT_MAP_PATH . 'src/class-wpdtrt-map-widget.php';

// log & trace helpers.
global $debug;
$debug = new DoTheRightThing\WPDebug\Debug();

/**
 * Group: WordPress Integration
 *
 * Comment out the actions you don't need.
 *
 * Notes:
 *  Default priority is 10. A higher priority runs later.
 *  register_activation_hook() is run before any of the provided hooks
 *
 * See:
 * - <https://developer.wordpress.org/plugins/hooks/actions/#priority>
 * - <https://codex.wordpress.org/Function_Reference/register_activation_hook>
 * _____________________________________
 */

register_activation_hook( dirname( __FILE__ ), 'wpdtrt_map_activate' );

add_action( 'init', 'wpdtrt_map_plugin_init', 0 );
add_action( 'init', 'wpdtrt_map_shortcode_init', 100 );
add_action( 'init', 'wpdtrt_map_taxonomy_init', 100 );
// add_action( 'widgets_init', 'wpdtrt_map_widget_init', 10 ); // see dotherightthing/wpdtrt-plugin-boilerplate#183.
register_deactivation_hook( dirname( __FILE__ ), 'wpdtrt_map_deactivate' );

/**
 * Group: Plugin config
 * _____________________________________
 */

/**
 * Function: wpdtrt_map_activate
 *
 * Register functions to be run when the plugin is activated.
 *
 * Note:
 * - See also Plugin::helper_flush_rewrite_rules()
 *
 * See:
 * - <https://codex.wordpress.org/Function_Reference/register_activation_hook>
 *
 * TODO:
 * - <https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/128>
 */
function wpdtrt_map_activate() {
	flush_rewrite_rules();
}

/**
 * Function: wpdtrt_map_deactivate
 *
 * Register functions to be run when the plugin is deactivated (WordPress 2.0+).
 *
 * Note:
 * - See also Plugin::helper_flush_rewrite_rules()
 *
 * See:
 * - <https://codex.wordpress.org/Function_Reference/register_deactivation_hook>
 *
 * TODO:
 * - <https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/issues/128>
 */
function wpdtrt_map_deactivate() {
	flush_rewrite_rules();
}

/**
 * Function: wpdtrt_map_plugin_init
 *
 * Plugin initialisaton.
 *
 * Note:
 * - We call init before widget_init so that the plugin object properties are available to it.
 * - If widget_init is not working when called via init with priority 1, try changing the priority of init to 0.
 * - init: Typically used by plugins to initialize. The current user is already authenticated by this time.
 * - widgets_init: Used to register sidebars. Fired at 'init' priority 1 (and so before 'init' actions with priority ≥ 1!)
 *
 * See:
 * - <https://wp-mix.com/wordpress-widget_init-not-working/>
 * - <https://codex.wordpress.org/Plugin_API/Action_Reference>
 *
 * TODO:
 * - Add a constructor function to WPDTRT_Map_Plugin, to explain the options array
 */
function wpdtrt_map_plugin_init() {
	// pass object reference between classes via global
	// because the object does not exist until the WordPress init action has fired.
	global $wpdtrt_map_plugin;

	/**
	 * Array: plugin_options
	 *
	 * Global options.
	 *
	 * See:
	 * - <Options - Adding global options: https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Options:-Adding-global-options>
	 */
	$plugin_options = array(
		// Google Maps is used to render the ACF location picker.
		// See https://www.advancedcustomfields.com/resources/acf-fields-google_map-api/.
		'google_javascript_maps_api_key' => array(
			'type'  => 'password',
			'label' => __( 'Google Cloud Platform API key', 'wpdtrt-map' ),
			'size'  => 50,
			'tip'   => __( 'https://console.cloud.google.com/apis/credentials, https://console.cloud.google.com/apis/library (Maps JavaScript API, Geocoding API and Places API)', 'wpdtrt-map' ),
		),
		// Mapbox is used to render the map embed.
		'mapbox_api_token'               => array(
			'type'  => 'password',
			'label' => __( 'Mapbox API Token', 'wpdtrt-map' ),
			'size'  => 120,
			'tip'   => __( 'https://account.mapbox.com/access-tokens', 'wpdtrt-map' ),
		),
		'mapbox_account_username'        => array(
			'type'  => 'password',
			'label' => __( 'Mapbox account username', 'wpdtrt-map' ),
			'size'  => 120,
			'tip'   => __( 'https://account.mapbox.com/', 'wpdtrt-map' ),
		),
		'mapbox_style_id'                => array(
			'type'  => 'password',
			'label' => __( 'Mapbox Style ID', 'wpdtrt-map' ),
			'size'  => 50,
			'tip'   => __( 'https://studio.mapbox.com/', 'wpdtrt-map' ),
		),
		'mapbox_marker_colour'           => array(
			'type'  => 'text',
			'label' => __( 'Mapbox Marker colour', 'wpdtrt-map' ),
			'size'  => 7,
			'tip'   => __( 'Use hex format', 'wpdtrt-map' ),
		),
	);

	/**
	 * Array: instance_options
	 *
	 * Shortcode or Widget options.
	 *
	 * See:
	 * - <Options - Adding shortcode or widget options: https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Options:-Adding-shortcode-or-widget-options>
	 */
	$instance_options = array(
		'unique_id'             => array(
			'type'    => 'number',
			'size'    => 3,
			'label'   => __( 'Unique ID', 'wpdtrt-map' ),
			'tip'     => __( 'To prevent conflicts between maps on the same page', 'wpdtrt-map' ),
			'default' => 1,
		),
		'enlargement_link_text' => array(
			'type'    => 'text',
			'size'    => 30,
			'label'   => __( 'Enlargement link text', 'wpdtrt-map' ),
			'tip'     => __( 'e.g. View larger map', 'wpdtrt-map' ),
			'default' => __( 'View on Google Maps', 'wpdtrt-map' ),
		),
		'zoom_level'            => array(
			'type'    => 'number',
			'size'    => 1,
			'label'   => __( 'Zoom level', 'wpdtrt-map' ),
			'tip'     => __( 'A larger value zooms in further', 'wpdtrt-map' ),
			'default' => 4,
		),
	);

	/**
	 * Array: ui_messages
	 *
	 * UI Messages.
	 */
	$ui_messages = array(
		'demo_data_description'       => __( 'This demo was generated from the following data', 'wpdtrt-map' ),
		'demo_data_displayed_length'  => __( 'results displayed', 'wpdtrt-map' ),
		'demo_data_length'            => __( 'results', 'wpdtrt-map' ),
		'demo_data_title'             => __( 'Demo data', 'wpdtrt-map' ),
		'demo_date_last_updated'      => __( 'Data last updated', 'wpdtrt-map' ),
		'demo_sample_title'           => __( 'Demo sample', 'wpdtrt-map' ),
		'demo_shortcode_title'        => __( 'Demo shortcode', 'wpdtrt-map' ),
		'insufficient_permissions'    => __( 'Sorry, you do not have sufficient permissions to access this page.', 'wpdtrt-map' ),
		'no_options_form_description' => __( 'There aren\'t currently any options.', 'wpdtrt-map' ),
		'noscript_warning'            => __( 'Please enable JavaScript', 'wpdtrt-map' ),
		'options_form_description'    => __( 'Please enter your preferences.', 'wpdtrt-map' ),
		'options_form_submit'         => __( 'Save Changes', 'wpdtrt-map' ),
		'options_form_title'          => __( 'General Settings', 'wpdtrt-map' ),
		'loading'                     => __( 'Loading latest data...', 'wpdtrt-map' ),
		'success'                     => __( 'settings successfully updated', 'wpdtrt-map' ),
		'mapbox_api_token_warning'    => __( 'Mapbox token not specified in plugin settings', 'wpdtrt-map' ),
	);

	/**
	 * Array: demo_shortcode_params
	 *
	 * Demo shortcode.
	 *
	 * See:
	 * - <Settings page - Adding a demo shortcode: https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Settings-page:-Adding-a-demo-shortcode>
	 */
	$demo_shortcode_params = array(
		'name'                  => 'wpdtrt_map_shortcode',
		'unique_id'             => 1,
		'enlargement_link_text' => 'View larger version',
		'number'                => 1,
		'mock_acf_map'          => array(
			'address' => __( 'Seatoun School & Community Emergency Hub Burnham Street, Seatoun, Wellington, New Zealand', 'wpdtrt-map' ),
			'lat'     => '-41.3264776',
			'lng'     => '174.83712689999993',
		),
		'zoom_level'            => 4,
	);

	/**
	 * Plugin configuration
	 */
	$wpdtrt_map_plugin = new WPDTRT_Map_Plugin(
		array(
			'path'                  => WPDTRT_MAP_PATH,
			'url'                   => WPDTRT_MAP_URL,
			'version'               => WPDTRT_MAP_VERSION,
			'prefix'                => 'wpdtrt_map',
			'slug'                  => 'wpdtrt-map',
			'menu_title'            => __( 'Map', 'wpdtrt-map' ),
			'settings_title'        => __( 'Settings', 'wpdtrt-map' ),
			'developer_prefix'      => 'DTRT',
			'messages'              => $ui_messages,
			'plugin_options'        => $plugin_options,
			'instance_options'      => $instance_options,
			'demo_shortcode_params' => $demo_shortcode_params,
		)
	);
}

/**
 * Group: Rewrite config
 */

/**
 * Function: wpdtrt_map_rewrite_init
 *
 * Register Rewrite.
 */
function wpdtrt_map_rewrite_init() {}

/**
 * Group: Shortcode config
 */

/**
 * Function: wpdtrt_map_shortcode_init
 *
 * Register Shortcode.
 */
function wpdtrt_map_shortcode_init() {

	global $wpdtrt_map_plugin;

	$wpdtrt_map_shortcode = new WPDTRT_Map_Shortcode(
		array(
			'name'                      => 'wpdtrt_map_shortcode',
			'plugin'                    => $wpdtrt_map_plugin,
			'template'                  => 'map',
			'selected_instance_options' => array(
				'unique_id',
				'enlargement_link_text',
				'zoom_level',
			),
		)
	);
}

/**
 * Group: Taxonomy config
 */

/**
 * Function: wpdtrt_map_taxonomy_init
 *
 * Register Taxonomy.
 *
 * Returns:
 *   object - Taxonomy/
 */
function wpdtrt_map_taxonomy_init() {}

/**
 * Group: Widget config
 */

/**
 * Function: wpdtrt_map_widget_init
 *
 * Register a WordPress widget, passing in an instance of our custom widget class.
 *
 * Note:
 * - The plugin does not require registration, but widgets and shortcodes do.
 * - widget_init fires before init, unless init has a priority of 0
 *
 * Uses:
 *   ../../../../wp-includes/widgets.php
 *   https://github.com/dotherightthing/wpdtrt/tree/master/library/sidebars.php
 *
 * See:
 * - <https://codex.wordpress.org/Function_Reference/register_widget#Example>
 * - <https://wp-mix.com/wordpress-widget_init-not-working/>
 * - <https://codex.wordpress.org/Plugin_API/Action_Reference>
 *
 * TODO:
 * - Add form field parameters to the options array
 * - Investigate the 'classname' option
 */
function wpdtrt_map_widget_init() {

	global $wpdtrt_map_plugin;

	$wpdtrt_map_widget = new WPDTRT_Map_Widget(
		array(
			'name'                      => 'wpdtrt_map_widget',
			'title'                     => __( 'DTRT Map Widget', 'wpdtrt-map' ),
			'description'               => __( 'Embed an interactive map.', 'wpdtrt-map' ),
			'plugin'                    => $wpdtrt_map_plugin,
			'template'                  => 'map',
			'selected_instance_options' => array(
				'unique_id',
				'enlargement_link_text',
				'zoom_level',
			),
		)
	);

	register_widget( $wpdtrt_map_widget );
}
