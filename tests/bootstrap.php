<?php
/**
 * PHPUnit bootstrap file
 *
 * @package WPDTRT_Map
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	throw new Exception( "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested, and any dependencies.
 */
function _manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/wpdtrt-map.php'; // Access static methods of plugin class.
	$composer_json = dirname( dirname( __FILE__ ) ) . '/composer.json';
	$composer_dependencies = WPDTRT_Map_Plugin::get_wp_composer_dependencies( $composer_json );
	$composer_dependencies_to_require = WPDTRT_Map_Plugin::get_wp_composer_dependencies_wpunit( $composer_dependencies );
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
