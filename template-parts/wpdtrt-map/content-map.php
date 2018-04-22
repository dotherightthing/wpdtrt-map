<?php
/**
 * Template to display plugin output in shortcodes and widgets
 *
 * @package   DTRT Map
 * @version   0.0.1
 * @since     0.7.5
 */

// Predeclare variables

// Internal WordPress arguments available to widgets
// This allows us to use the same template for shortcodes and front-end widgets
$before_widget = null; // register_sidebar
$before_title = null; // register_sidebar
$title = null;
$after_title = null; // register_sidebar
$after_widget = null; // register_sidebar

// shortcode options
// $foo = null;

// access to plugin
$plugin = null;

// Options: display $args + widget $instance settings + access to plugin
$options = get_query_var( 'options' );

// Overwrite variables from array values
// @link http://kb.network.dan/php/wordpress/extract/
extract( $options, EXTR_IF_EXISTS );

// load the data
// $plugin->get_api_data();
// $foo = $plugin->get_api_data_bar();

// WordPress widget options (not output with shortcode)
echo $before_widget;
echo $before_title . $title . $after_title;
?>

<div class="wpdtrt-map">
	<div id="wpdtrt-map-<?php echo $id; ?>" class="wpdtrt-map-embed"></div>
		<?php if ( $link_text !== '' ): ?>
		<p class="wpdtrt-map-link">
	  		<a href="//maps.google.com/maps/place/<?php echo $coordinates; ?>"><?php echo $link_text; ?></a>
		</p>
		<?php endif; ?>
	</div>
</div>

<?php
// output widget customisations (not output with shortcode)
echo $after_widget;
?>
