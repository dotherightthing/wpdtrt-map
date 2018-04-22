
=== DTRT Map ===
Contributors: dotherightthingnz
Donate link: http://dotherightthing.co.nz
Tags: map
Requires at least: 4.9.5
Tested up to: 4.9.5
Requires PHP: 5.6.30
Stable tag: 0.3.0
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
2. Locate the *DTRT Maps* metabox
3. Search for a location
4. *Publish*/*Update*

B) Use the provided shortcode to embed a map:

```
<!-- within the editor -->
[wpdtrt_maps option="value"]

// in a PHP template, as a template tag
<?php echo do_shortcode( '[wpdtrt_maps option="value"]' ); ?>
```

C) Shortcode options:

1. `id="1"` (default) - the map ID
2. `link_text="View Larger Map"` (default) - optionally display a link to the map at the bottom of the embed

== Screenshots ==

1. The caption for ./images/screenshot-1.(png|jpg|jpeg|gif)
2. The caption for ./images/screenshot-2.(png|jpg|jpeg|gif)

== Changelog ==

= 0.3.0 =
* Migrate to wpdtrt-plugin format

= 0.0.1 =
* Initial version

== Upgrade Notice ==

= 0.0.1 =
* Initial release
