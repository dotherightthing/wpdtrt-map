<?php
/**
 * File: tests/test-wpdtrt-map.php
 *
 * Unit tests, using PHPUnit, wp-cli, WP_UnitTestCase.
 *
 * Note:
 * - The plugin is 'active' within a WP test environment
 *   so the plugin class has already been instantiated
 *   with the options set in wpdtrt-map.php
 * - Only function names prepended with test_ are run.
 * - $debug logs are output with the test output in Terminal
 * - A failed assertion may obscure other failed assertions in the same test.
 *
 * See:
 * - <https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Testing-&-Debugging#testing>
 *
 * Since:
 *   0.8.13 - DTRT WordPress Plugin Boilerplate Generator
 */

/**
 * Class: WPDTRT_MapTest
 *
 * WP_UnitTestCase unit tests for wpdtrt_map.
 */
class WPDTRT_MapTest extends WP_UnitTestCase {

	/**
	 * Group: Lifecycle Events
	 * _____________________________________
	 */

	/**
	 * Method: setUp
	 *
	 * SetUp,
	 * automatically called by PHPUnit before each test method is run.
	 */
	public function setUp() {
		// Make the factory objects available.
		parent::setUp();

		$this->post_id_1 = $this->create_post( array(
			'post_title'   => 'DTRT Map test',
			'post_content' => 'This is a simple test',
		));
	}

	/**
	 * Method: tearDown
	 *
	 * TearDown,
	 * automatically called by PHPUnit after each test method is run.
	 *
	 * See:
	 * - <https://codesymphony.co/writing-wordpress-plugin-unit-tests/#object-factories>
	 */
	public function tearDown() {

		parent::tearDown();

		wp_delete_post( $this->post_id_1, true );
	}

	/**
	 * Group: Helpers
	 * _____________________________________
	 */

	/**
	 * Method: assertEqualHtml
	 *
	 * Compare two HTML fragments.
	 *
	 * Parameters:
	 *   $expected - Expected value.
	 *   $actual - Actual value.
	 *   $error_message - Message to show when strings don't match.
	 *
	 * Uses:
	 *   <https://stackoverflow.com/a/26727310/6850747>
	 */
	protected function assertEqualHtml( string $expected, string $actual, string $error_message ) {
		$from = [ '/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s', '/> </s' ];
		$to   = [ '>', '<', '\\1', '><' ];
		$this->assertEquals(
			preg_replace( $from, $to, $expected ),
			preg_replace( $from, $to, $actual ),
			$error_message
		);
	}

	/**
	 * Method: create_post
	 *
	 * Create post.
	 *
	 * Parameters:
	 *   $options - Post options
	 *
	 * Returns:
	 *   $post_id - Post ID
	 *
	 * See:
	 * - <https://developer.wordpress.org/reference/functions/wp_insert_post/>
	 * - <https://wordpress.stackexchange.com/questions/37163/proper-formatting-of-post-date-for-wp-insert-post>
	 * - <https://codex.wordpress.org/Function_Reference/wp_update_post>
	 */
	public function create_post( $options ) {

		$post_title   = null;
		$post_date    = null;
		$post_content = null;

		extract( $options, EXTR_IF_EXISTS );

		$post_id = $this->factory->post->create([
			'post_title'   => $post_title,
			'post_date'    => $post_date,
			'post_content' => $post_content,
			'post_type'    => 'post',
			'post_status'  => 'publish',
		]);

		return $post_id;
	}

	/**
	 * Method: tenon
	 *
	 * Lint page state in Tenon.io (proof of concept).
	 *
	 * Parameters:
	 *   $url_or_src - Page URL or post-JS DOM source
	 *
	 * Returns:
	 *   $result - Tenon resultSet, or WP error
	 *
	 * TODO:
	 * - Waiting on Tenon Tunnel to expose WPUnit environment to Tenon API
	 *
	 * See:
	 * - <Tenon - Roadmap at 12/2015: https://blog.tenon.io/tenon-io-end-of-year-startup-experience-at-9-months-in-product-updates-and-more/>
	 * - <https://github.com/joedolson/access-monitor/blob/master/src/access-monitor.php>
	 * - <Tenon - Optional parameters/$args: https://tenon.io/documentation/understanding-request-parameters.php>
	 *
	 * Since:
	 *   1.7.15 - wpdtrt-gallery
	 */
	protected function tenon( string $url_or_src ) : array {

		$endpoint = 'https://tenon.io/api/';

		$args = array(
			'method'  => 'POST',
			'body'    => array(
				// Required parameter #1 is passed in by Github Actions CI.
				'key'       => getenv( 'TENON_AUTH' ),
				// Optional parameters:.
				'level'     => 'AA',
				'priority'  => 0,
				'certainty' => 100,
			),
			'headers' => '',
			'timeout' => 60,
		);

		// Required parameter #2.
		if ( preg_match( '/^http/', $url_or_src ) ) {
			$args['body']['url'] = $url_or_src;
		} else {
			$args['body']['src'] = $url_or_src;
			// TODO
			// this is a quick hack to get something working
			// in reality we will want to support full pages too.
			$args['body']['fragment'] = 1; // else 'no title' etc error.
		}

		$response = wp_remote_post(
			$endpoint,
			$args
		);

		// $body = wp_remote_retrieve_body( $response );.
		if ( is_wp_error( $response ) ) {
			$result = $response->errors;
		} else {
			/**
			 * Return the body, not the header
			 * true = convert to associative array
			 */
			$api_response = json_decode( $response['body'], true );

			$result = $api_response['resultSet'];
		}

		return $result;
	}

	/**
	 * Group: Tests
	 * _____________________________________
	 */

	/**
	 * Method: test_placeholder
	 *
	 * Demo test.
	 */
	public function test_placeholder() {

		$this->assertEquals(
			'abc123',
			'abc123',
			'Strings do not match'
		);
	}
}
