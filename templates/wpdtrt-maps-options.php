<?php
/**
 * Template partial for Admin Options page
 *    WP Admin > Settings > DTRT Maps
 *
 * This file contains PHP, and HTML from the WordPress_Admin_Style plugin.
 *
 * @link        https://github.com/dotherightthing/wpdtrt-maps
 * @link        /wp-admin/admin.php?page=WordPress_Admin_Style#twocolumnlayout2
 * @since       0.1.0
 *
 * @package     Wpdtrt_Maps
 * @subpackage  Wpdtrt_Maps/views
 */
?>

<div class="wrap">

  <div id="icon-options-general" class="icon32"></div>
  <h1><?php esc_attr_e( 'DTRT Maps', 'wp_admin_style' ); ?></h1>

  <div id="poststuff">

    <div id="post-body" class="metabox-holder columns-1">

      <!-- main content -->
      <div id="post-body-content">

        <div class="meta-box-sortables ui-sortable">

          <div class="postbox">

            <h2>
              <span><?php esc_attr_e( 'Settings', 'wp_admin_style' ); ?></span>
            </h2>

            <div class="inside">

              <form name="wpdtrt_maps_data_form" method="post" action="">

                <input type="hidden" name="wpdtrt_maps_form_submitted" value="Y" />

                <table class="form-table">
                  <tr>
                    <th>
                      <label for="wpdtrt_maps_google_api_key">Google Maps API key:
                        <span class="wpdtrt-maps-tip">
                          <a href="https://developers.google.com/maps/documentation/javascript/get-api-key">Get a key</a>
                        </span>
                      </label>
                    </th>
                    <td>
                      <input type="password" name="wpdtrt_maps_google_api_key" id="wpdtrt_maps_google_api_key" value="<?php echo $wpdtrt_maps_google_api_key; ?>">
                    </td>
                  </tr>
                </table>

                <?php
                /**
                 * submit_button( string $text = null, string $type = 'primary', string $name = 'submit', bool $wrap = true, array|string $other_attributes = null )
                 */
                  submit_button(
                    $text = 'Save',
                    $type = 'primary',
                    $name = 'wpdtrt_maps_submit',
                    $wrap = true,
                    $other_attributes = null
                  );
                ?>

              </form>
            </div>
            <!-- .inside -->

          </div>
          <!-- .postbox -->

        </div>
        <!-- .meta-box-sortables .ui-sortable -->

      </div>
      <!-- post-body-content -->

    </div>
    <!-- #post-body .metabox-holder .columns-2 -->

    <br class="clear">
  </div>
  <!-- #poststuff -->

</div> <!-- .wrap -->
