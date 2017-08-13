<?php
/**
 * Template partial for the public front-end
 *
 * This file contains PHP, and HTML.
 *
 * @link        https://github.com/dotherightthing/wpdtrt-maps
 * @since       0.1.0
 *
 * @package     Wpdtrt_Maps
 * @subpackage  Wpdtrt_Maps/views
 */
?>

<?php
  // output widget customisations (not output with shortcode)
  echo $before_widget;
  echo $before_title . $title . $after_title;
?>

<div class="wpdtrt-map">
  <div id="wpdtrt-map-'<?php echo $id; ?>" class="wpdtrt-map-embed"></div>
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
