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

$daynumber    = '';
$map_location = '';

// Check for ACF function & tour diary page.
if ( ( function_exists( 'get_field' ) ) && ( wpdtrt_dbth_post_type_is( 'tourdiaries' ) ) ) {
	$map_location = get_field( 'acf_location' );

	if ( shortcode_exists( 'wpdtrt_tourdates_shortcode_daynumber' ) ) {
		$daynumber = trim( do_shortcode( '[wpdtrt_tourdates_shortcode_daynumber]' ) );
	}
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
		mapboxgl.accessToken = '<?php echo $mapbox_api_token; ?>';
		var map = new mapboxgl.Map({
			container: 'wpdtrt-map-<?php echo $unique_id; ?>',
			style: 'mapbox://styles/<?php echo $mapbox_account_username; ?>/<?php echo $mapbox_style_id; ?>', // stylesheet location
			center: [<?php echo $static_coordinates; ?>], // starting position [lng, lat]
			zoom: <?php echo $zoom_level; ?> // starting zoom
		});

		// https://geojson.org/
		var places = {
			'type': 'FeatureCollection',
			'features': [
				{
					'type': 'Feature',
					'properties': {
						'name': 'Day #<?php echo $daynumber; ?>',
						'description': '<?php echo $map_location; ?>'
					},
					'geometry': {
						'type': 'Point',
						'coordinates': [<?php echo $static_coordinates; ?>], // [lng, lat]
					}
				}
			]
		};

		var marker = new mapboxgl.Marker({
				color: '<?php echo $mapbox_marker_colour; ?>',
			})
			.setLngLat([<?php echo $static_coordinates; ?>])
			.addTo(map);

		// see https://docs.mapbox.com/mapbox-gl-js/example/variable-label-placement/.
		map.on('load', () => {
			// Add a GeoJSON source containing place coordinates and information.
			map.addSource('places', {
				'type': 'geojson',
				'data': places
			});

			map.addLayer({
				'id': 'poi-labels',
				'type': 'symbol',
				'source': 'places',
				'layout': {
					'text-field': [
						'format',
						['upcase', ['get', 'name']], // feature.name
						{ 'font-scale': 1 },
						'\n',
						{},
						['get', 'description'], // feature.description
						{ 'font-scale': 0.8 }
					],
					// allow high priority labels to shift between the listed positions to stay on the map
					'text-variable-anchor': ['top'],
					// set the distance of the text from its text-variable-anchor
					'text-radial-offset': 0.5,
					// set the justification of the label (`auto` = anchor position)
					'text-justify': 'auto'
				},
				paint: {
					'text-color': '#000000',
					'text-halo-color': '#ffffff',
					'text-halo-width': 1.25
				}
			});

			map.addControl(new mapboxgl.FullscreenControl());
		});
	</script>
<?php endif; ?>

<?php
// output widget customisations (not output with shortcode).
echo $after_widget;
?>
