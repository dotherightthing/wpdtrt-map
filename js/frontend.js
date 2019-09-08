/**
 * File: js/frontend.js
 *
 * Front-end scripting for public pages.
 *
 * Note:
 * - PHP variables are provided in wpdtrt_map_config.
 *
 * Since:
 *   0.8.13 - DTRT WordPress Plugin Boilerplate Generator
 */

/* global jQuery, wpdtrt_map_config */
/* eslint-disable camelcase */

/**
 * Object: wpdtrt_map_ui
 */
const wpdtrt_map_ui = {

  /**
   * Method: init
   *
   * Initialise front-end scripting.
   */
  // init: () => {}
};

// http://stackoverflow.com/a/28771425
document.addEventListener( 'touchstart', () => {
  // nada, this is just a hack to make :focus state render on touch
}, false );

jQuery( document ).ready( ( $ ) => { // eslint-disable-line no-unused-vars
  const config = wpdtrt_map_config; // eslint-disable-line no-unused-vars
  wpdtrt_map_ui.init();
} );
