/**
 * @file js/frontend.js
 * @summary Front-end scripting for public pages.
 * @description PHP variables are provided in wpdtrt_map_config.
 * @since 0.8.13
 */

/* global jQuery, wpdtrt_map_config */
/* eslint-disable camelcase, no-unused-vars */

/**
 * Object: wpdtrtMapUi
 */
const wpdtrtMapUi = {

    /**
     * @function init
     * @summary Initialise front-end scripting.
     */
    init: () => {
        console.log('wpdtrtMapUi.init'); // eslint-disable-line no-console
    }
};

jQuery(document).ready(($) => {
    const config = wpdtrt_map_config; // eslint-disable-line
    wpdtrtMapUi.init();
});
