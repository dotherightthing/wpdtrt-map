# DTRT Map

[![GitHub release](https://img.shields.io/github/release/dotherightthing/wpdtrt-map.svg)](https://github.com/dotherightthing/wpdtrt-map/releases) [![Build Status](https://github.com/dotherightthing/wpdtrt-map/workflows/Build%20and%20release%20if%20tagged/badge.svg)](https://github.com/dotherightthing/wpdtrt-map/actions?query=workflow%3A%22Build+and+release+if+tagged%22) [![GitHub issues](https://img.shields.io/github/issues/dotherightthing/wpdtrt-map.svg)](https://github.com/dotherightthing/wpdtrt-map/issues) [![GitHub wiki](https://img.shields.io/badge/documentation-wiki-lightgrey.svg)](https://github.com/dotherightthing/wpdtrt-map/wiki)

Embed an interactive map.

## Setup and Maintenance

Please read [DTRT WordPress Plugin Boilerplate: Workflows](https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Workflows).

## WordPress Installation

Please read the [WordPress readme.txt](readme.txt).

## WordPress Usage

### Embed a map

#### A) Specify a map location

1. On a *Page*,
2. Locate the *DTRT Map* metabox
3. Search for a location
4. *Publish*/*Update*

#### B) Use the provided shortcode to embed a map

```php
<!-- within the editor -->
[wpdtrt_map_shortcode option="value"]
```

```php
// in a PHP template, as a template tag
<?php
    echo do_shortcode( '[wpdtrt_map_shortcode option="value"]' );
?>
```

#### C) Shortcode options

1. `unique_id="1"` (default) - prevents conflicts between maps on the same page
2. `enlargement_link_text="View Larger Map"` (default) - optionally display a link to the map at the bottom of the embed
3. `zoom_level="4"` (default) - zooms into the map

## Dependencies

1. The geocoded map location is selected using the free version of [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/)
