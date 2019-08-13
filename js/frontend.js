/**
 * @file DTRT WP Map frontend.js
 * @summary
 *     Front-end scripting for public pages
 *     PHP variables are provided in `wpdtrt_map_config`.
 * @version 0.1.0
 * @since   0.8.3 DTRT WordPress Plugin Boilerplate Generator
 */

/* eslint-env browser */
/* global document, jQuery, wpdtrt_map_config */
/* eslint-disable no-unused-vars */

/**
 * @namespace wpdtrt_map_ui
 */
const wpdtrt_map_ui = {

    /**
     * Initialise front-end scripting
     * @since 0.1.0
     */
    init: () => {
        "use strict";

        console.log("wpdtrt_map_ui.init");
    }
}

jQuery(document).ready( ($) => {

    "use strict";

    const config = wpdtrt_map_config;
    wpdtrt_map_ui.init();
});
