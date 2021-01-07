<?php
/**
 * File: src/class-wpdtrt-map-shortcode.php
 *
 * Shortcode sub class.
 *
 * Since:
 *   0.8.13 - DTRT WordPress Plugin Boilerplate Generator
 */

/**
 * Class: WPDTRT_Map_Shortcode
 *
 * Extends the base class to inherit boilerplate functionality, adds application-specific methods.
 *
 * Since:
 *   0.8.13 - DTRT WordPress Plugin Boilerplate Generator
 */
class WPDTRT_Map_Shortcode extends DoTheRightThing\WPDTRT_Plugin_Boilerplate\r_1_7_7\Shortcode {

	/**
	 * Constructor: __construct
	 *
	 * Supplement shortcode initialisation.
	 *
	 * Parameters:
	 *   $options - Shortcode options
	 *
	 * Since:
	 *   0.8.13 - DTRT WordPress Plugin Boilerplate Generator
	 */
	public function __construct( $options ) { // phpcs:disable

		// edit here.
		parent::__construct( $options ); // phpcs:disable
	}

	/**
	 * Group: WordPress Integration
	 * _____________________________________
	 */

	/**
	 * Method: wp_setup
	 *
	 * Supplement shortcode's WordPress setup.
	 *
	 * Note:
	 * - Default priority is 10. A higher priority runs later.
	 *
	 * See:
	 * - <Action order: https://codex.wordpress.org/Plugin_API/Action_Reference>
	 *
	 * Since:
	 *   0.8.13 - DTRT WordPress Plugin Boilerplate Generator
	 */
	protected function wp_setup() {

		parent::wp_setup();

		// About: add actions and filters here.
	}

	/**
	 * Group: Getters and Setters
	 * _____________________________________
	 */

	/**
	 * Group: Renderers
	 * _____________________________________
	 */

	/**
	 * Group: Filters
	 * _____________________________________
	 */

	/**
	 * Group: Helpers
	 * _____________________________________
	 */
}
