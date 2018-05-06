
=== DTRT Map ===
Contributors: dotherightthingnz
Donate link: http://dotherightthing.co.nz
Tags: map
Requires at least: 4.9.5
Tested up to: 4.9.5
Requires PHP: 5.6.30
Stable tag: 0.3.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Embed an interactive map.

== Description ==

Embed an interactive map.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wpdtrt-map` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the Settings->DTRT Map screen to configure the plugin

== Frequently Asked Questions ==

= How do I embed a map? =

A) Specify a map location:

1. On a *Page*,
2. Locate the *DTRT Map* metabox
3. Search for a location
4. *Publish*/*Update*

B) Use the provided shortcode to embed a map:

```
<!-- within the editor -->
[wpdtrt_map_shortcode option="value"]

// in a PHP template, as a template tag
<?php echo do_shortcode( '[wpdtrt_map_shortcode option="value"]' ); ?>
```

C) Shortcode options:

1. `unique_id="1"` (default) - a unique ID to prevent embed conflicts
2. `enlargement_link_text="View Larger Map"` (default) - optionally display a link to the map at the bottom of the embed

== Screenshots ==

1. The caption for ./images/screenshot-1.(png|jpg|jpeg|gif)
2. The caption for ./images/screenshot-2.(png|jpg|jpeg|gif)

== Changelog ==

= 0.3.2 =
* Include release number in wpdtrt-plugin namespaces
* Update wpdtrt-plugin to 1.4.6

= 0.3.1 =
* Update wpdtrt-plugin to 1.3.6

= 0.3.0 =
* Migrate to wpdtrt-plugin format
* Output demo map shortcode on settings page
* Make $acf_map retrieval DRY, support mocked shortcode data
* Move dynamic Leaflet embed code into the shortcode template
* Update Leaflet from 1.2.0 to 1.3.1
* Add help text to Unique ID field
* Use shortcode name in demo shortcode
* Fix missing map embed ID
* Fix Google Maps API key type
* Output a warning instead of the map, if the Mapbox API token has not been set
* Fix name of ACF map field
* Retrieve value when using an option
* Fix bad plugin name in translation context
* Fix missing variables in map template
* Use password fields rather than text fields
* Migrate Bower & NPM to Yarn 
* Update Node from 6.11.2 to 8.11.1 
* Add messages required by shortcode demo 
* Add SCSS partials for project-specific extends and variables 
* Document dependencies
* Use ACF action to load field config
* Use latest beta version of ACF 5
* Regenerate ACF field groups
* Change tag badge to release badge
* Update wpdtrt-plugin to 1.3.2

= 0.2.4 =
* Bump version

= 0.2.3 =
* Fix name of ACF field, to resolve missing Leaflet stylesheet
* Fold bower_components into vendor folder
* Commit dependencies

= 0.2.2 =
* Update the plugin version

= 0.2.1 =
* Commit TGM files

= 0.2.0 =
* Remove redundant localization
* Reduce width of map link, to reveal copyright information
* Stack map link below map on mobile, to reveal copyright information

= 0.1.0 =
* Initial version

== Upgrade Notice ==

= 0.1.0 =
* Initial release
