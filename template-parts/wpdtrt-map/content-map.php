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
$enlargement_link_text = null;
$unique_id = null;

// access to plugin
$plugin = null;

// Options: display $args + widget $instance settings + access to plugin
$options = get_query_var( 'options' );

// Overwrite variables from array values
// @link http://kb.network.dan/php/wordpress/extract/
extract( $options, EXTR_IF_EXISTS );

$plugin_options = $plugin->get_plugin_options();

/**
 * ACF's Google Map field type supports geocoding
 * so entering an address there will generate
 * latitide and longitude
 *
 * @see https://stackoverflow.com/questions/27186167/set-view-for-an-array-of-addressesno-coordinates-using-leaflet-js
 * @see https://www.advancedcustomfields.com/resources/google-map/
 */
$acf_map = $plugin->get_acf_map();

if ( $acf_map ) {
	$address = $acf_map['address'];
	$coordinates = $acf_map['lat'] . ',' . $acf_map['lng'];
}

// https://www.mapbox.com/studio/account/tokens/
$mapbox_api_token = $plugin_options['mapbox_api_token']['value'];

// load the data
// $plugin->get_api_data();
// $foo = $plugin->get_api_data_bar();

// WordPress widget options (not output with shortcode)
echo $before_widget;
echo $before_title . $title . $after_title;
?>

<?php if ( $acf_map ): ?>
	<div class="wpdtrt-map">
		<div id="wpdtrt-map-<?php echo $unique_id; ?>" class="wpdtrt-map-embed"></div>
			<?php if ( $enlargement_link_text !== '' ): ?>
			<p class="wpdtrt-map-link">
		  		<a href="//maps.google.com/maps/place/<?php echo $coordinates; ?>"><?php echo $enlargement_link_text; ?></a>
			</p>
			<?php endif; ?>
		</div>
	</div>
	<script>
		var wpdtrt_map_<?php echo $unique_id; ?> = L.map('wpdtrt-map-<?php echo $unique_id; ?>', {
			zoomControl: false
		}).setView([<?php echo $coordinates; ?>], 16);
		
		L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
			attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
			// maxZoom: 18,
			id: 'mapbox.streets',
			accessToken: '<?php echo $mapbox_api_token; ?>'
		}).addTo(wpdtrt_map_<?php echo $unique_id; ?>);

		var marker = L.marker([<?php echo $coordinates; ?>]).addTo(wpdtrt_map_<?php echo $unique_id; ?>);

		// http://leafletjs.com/examples/choropleth/
		var legend = L.control({ position: 'topleft' });
		legend.onAdd = function (map) {
			// tagname:div, classname:info legend
			var div = L.DomUtil.create('div', 'wpdtrt-map-legend'); // .wpdtrt-map-legend
				div.innerHTML = '<?php echo $address; ?>';
			return div;
		};

		legend.addTo(wpdtrt_map_<?php echo $unique_id; ?>);
		// https://www.mapbox.com/mapbox.js/example/v1.0.0/change-zoom-control-location/
		new L.Control.Zoom({ position: 'bottomleft' }).addTo(wpdtrt_map_<?php echo $unique_id; ?>);
	</script>
<?php endif; ?>

<?php
// output widget customisations (not output with shortcode)
echo $after_widget;
?>
