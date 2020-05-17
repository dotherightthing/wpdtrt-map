
=== DTRT Map ===
Contributors: dotherightthingnz
Donate link: http://dotherightthing.co.nz
Tags: map, leafletjs, location, geotag
Requires at least: 4.9.5
Tested up to: 4.9.5
Requires PHP: 7.2.20
Stable tag: 0.4.0
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

See [WordPress Usage](README.md#wordpress-usage).

== Screenshots ==

1. The caption for ./images/screenshot-1.(png|jpg|jpeg|gif)
2. The caption for ./images/screenshot-2.(png|jpg|jpeg|gif)

== Changelog ==

= 0.4.0 =
* Merge to master branch

= 0.3.7 =
* Optimise breakpoints
* Update dependencies
* Linting fixes, expose config, output console log on init
* Replace gulp with wpdtrt-npm-scripts
* Update links to Google APIs (#14)
* Add id to script tag, output console warning if mapbox token not specified in wp-admin
* Add/improve Cypress tests
* Fix static map location
* Move shortcode option default logic to wpdtrt-plugin-boilerplate
* Add zoom level; add default settings to instance options, so that these can be output rather than the array describing the input field
* Fix ACF map reference
* Add missing warning message
* Output ACF map if specified, else use image geotag if it exists
* Sync with DTRT WordPress Plugin Boilerplate Generator 0.8.13 + DTRT WordPress Plugin Boilerplate 1.7.0
* Fix package name, migrate from PHPDoc to Natural Docs, update PHPUnit

= 0.3.6 =
* Use lowercase for Composer dependencies
* Fix missing leaflet token
* Suppress map legend, until there is something meaningful to display

= 0.3.5 =
* Regenerate plugin to sync with generator-wpdtrt-plugin-boilerplate 0.8.3 + wpdtrt-plugin-boilerplate 1.5.6
* Add Composer dependencies and repositories
* Update Composer & NPM dependencies
* Ignore 'NonEnqueuedStylesheet' error
* Use WPDTRT Exif to pull the lat and long values from the featured image, rather than requiring the author to choose these by using the ACF picker

= 0.3.4 =
* Update wpdtrt-plugin to 1.4.14
* ACF is just a dev (test) dependency
* Update documentation

= 0.3.3 =
* Fix path to autoloader when loaded as a test dependency

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
