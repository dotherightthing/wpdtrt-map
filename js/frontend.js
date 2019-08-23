/**
 * File: frontend.js
 *
 * DTRT Map
 *
 * Front-end scripting for public pages.
 *
 * PHP variables are provided in `wpdtrt_map_config`.
 *
 * Since:
 *   DTRT WordPress Plugin Boilerplate Generator 0.8.3
 *
 * @package WPDTRT_Map
 */

/* eslint-env browser */
/* global jQuery, wpdtrt_map_config */
/* eslint-disable no-unused-vars */

// eslint-disable-next-line camelcase
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

jQuery( document ).ready( ( $ ) => {
  // eslint-disable camelcase
  const config = wpdtrt_map_config;
  wpdtrt_map_ui.init();
  // eslint-enable camelcase
} );
