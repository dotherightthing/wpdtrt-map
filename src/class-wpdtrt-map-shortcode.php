<?php
/**
 * Shortcode sub class.
 *
 * @package wpdtrt_map
 * @version 0.1.0
 * @since   0.8.3 DTRT WordPress Plugin Boilerplate Generator
 */

/**
 * Extend the base class to inherit boilerplate functionality.
 * Adds application-specific methods.
 *
 * @since   1.0.0
 * @version 1.0.0
 */
class WPDTRT_Map_Shortcode extends DoTheRightThing\WPDTRT_Plugin_Boilerplate\r_1_5_6\Shortcode {

	/**
	 * Supplement shortcode initialisation.
	 *
	 * @param     array $options Shortcode options.
	 * @since     1.0.0
	 * @version   1.1.0
	 */
	public function __construct( $options ) {

		// edit here.
		parent::__construct( $options );
	}

	/**
	 * ====== WordPress Integration ======
	 */

	/**
	 * Supplement shortcode's WordPress setup.
	 * Note: Default priority is 10. A higher priority runs later.
	 *
	 * @see https://codex.wordpress.org/Plugin_API/Action_Reference Action order
	 */
	protected function wp_setup() {

		// edit here.
		parent::wp_setup();
	}

	/**
	 * ====== Getters and Setters ======
	 */

	/**
	 * ===== Renderers =====
	 */

	/**
	 * ===== Filters =====
	 */

	/**
	 * ===== Helpers =====
	 */
}
