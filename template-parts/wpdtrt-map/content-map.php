<?php
/**
 * File: template-parts/wpdtrt-map/content.php
 *
 * Template to display plugin output in shortcodes and widgets.
 *
 * Since:
 *   0.8.13 - DTRT WordPress Plugin Boilerplate Generator
 */

// Predeclare variables
//
// Internal WordPress arguments available to widgets
// This allows us to use the same template for shortcodes and front-end widgets.
$before_widget = null; // register_sidebar.
$before_title  = null; // register_sidebar.
$title         = null;
$after_title   = null; // register_sidebar.
$after_widget  = null; // register_sidebar.

// shortcode options.
$enlargement_link_text = null;
$unique_id             = null;
$zoom_level            = null;

// access to plugin.
$plugin = null;

// Options: display $args + widget $instance settings + access to plugin.
$options = get_query_var( 'options' );

// Overwrite variables from array values
//
// See:
// <http://kb.network.dan/php/wordpress/extract/>.
extract( $options, EXTR_IF_EXISTS );

$plugin_options = $plugin->get_plugin_options();

/**
 * ACF's Google Map field type supports geocoding
 * so entering an address there will generate
 * latitide and longitude.
 *
 * See:
 * - <https://stackoverflow.com/questions/27186167/set-view-for-an-array-of-addressesno-coordinates-using-leaflet-js>
 * - <https://www.advancedcustomfields.com/resources/google-map/>
 */
$acf_map                 = $plugin->get_acf_map();
$mapbox_api_token        = '';
$mapbox_account_username = '';
$mapbox_style_id         = '';
$mapbox_marker_colour    = '';

if ( $acf_map ) {
	// TODO: address is currently just a placeholder.
	$address            = $acf_map['address'];
	$coordinates        = $acf_map['lat'] . ',' . $acf_map['lng'];
	$static_coordinates = $acf_map['lng'] . ',' . $acf_map['lat'];
}

if ( array_key_exists( 'value', $plugin_options['mapbox_api_token'] ) ) {
	$mapbox_api_token = $plugin_options['mapbox_api_token']['value'];
}

if ( array_key_exists( 'value', $plugin_options['mapbox_account_username'] ) ) {
	$mapbox_account_username = $plugin_options['mapbox_account_username']['value'];
}

if ( array_key_exists( 'value', $plugin_options['mapbox_style_id'] ) ) {
	$mapbox_style_id = $plugin_options['mapbox_style_id']['value'];
}

if ( array_key_exists( 'value', $plugin_options['mapbox_marker_colour'] ) ) {
	$mapbox_marker_colour = $plugin_options['mapbox_marker_colour']['value'];
}

// load the data
// $plugin->get_api_data();
// $foo = $plugin->get_api_data_bar();
//
// WordPress widget options (not output with shortcode).
echo $before_widget;
echo $before_title . $title . $after_title;

?>

<?php if ( $acf_map && isset( $mapbox_api_token ) && ( '' !== $mapbox_api_token ) ) : ?>
	<div class="wpdtrt-map">
		<div id="wpdtrt-map-<?php echo $unique_id; ?>" class="wpdtrt-map-embed">
			<noscript>
				<img
					alt="Map showing the co-ordinates <?php echo $static_coordinates; ?>. "
					src="https://api.mapbox.com/styles/v1/<?php echo $mapbox_account_username; ?>/<?php echo $mapbox_style_id; ?>/static/<?php echo $static_coordinates; ?>,<?php echo $zoom_level; ?>,0,0/300x300@2x?access_token=<?php echo $mapbox_api_token; ?>"
				>
			</noscript>
		</div>
		<?php if ( '' !== $enlargement_link_text ) : ?>
		<p class="wpdtrt-map-link">
			<a href="//maps.google.com/maps/place/<?php echo $coordinates; ?>"><?php echo $enlargement_link_text; ?></a>
		</p>
		<?php endif; ?>
	</div>
	<script>		
		// Remove noscript fallback as mapbox expects an empty container
		document.getElementById('wpdtrt-map-<?php echo $unique_id; ?>').innerHTML = '';

		// Embed JS map
		// TODO: add legend, e.g. wpdtrt-gallery-location value
		mapboxgl.accessToken = '<?php echo $mapbox_api_token; ?>';
		var map = new mapboxgl.Map({
			container: 'wpdtrt-map-<?php echo $unique_id; ?>',
			style: 'mapbox://styles/<?php echo $mapbox_account_username; ?>/<?php echo $mapbox_style_id; ?>', // stylesheet location
			center: [<?php echo $static_coordinates; ?>], // starting position [lng, lat]
			zoom: <?php echo $zoom_level; ?> // starting zoom
		});

		var marker = new mapboxgl.Marker({
				color: '<?php echo $mapbox_marker_colour; ?>',
			})
			.setLngLat([<?php echo $static_coordinates; ?>])
			.addTo(map);

		map.addControl(new mapboxgl.FullscreenControl());
	</script>
<?php endif; ?>

<?php
// output widget customisations (not output with shortcode).
echo $after_widget;
?>
