/**
 * @file cypress/plugins/index.js
 * @summary Custom plugins for UI testing
 * @since 0.8.13
 */

/* eslint-env node */
/* globals Promise */

const normalizeWhitespace = require('normalize-html-whitespace');
const TenonNode = require('tenon-node');

// export a function
module.exports = (on) => {
    // configure plugins here
    on('task', {
        /**
         * @summary Lint a URL in Tenon
         * @param {string} url - Fully qualified URL
         * @returns {object} Tenon response object
         *
         * @see tenonCommands: https://github.com/poorgeek/tenon-selenium-example/blob/master/test/helpers/tenonCommands.js
         * @see tenon-node: https://www.npmjs.com/package/tenon-node
         * @see Cypress.io Front End Unit Tests: https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Testing-&-Debugging#d-cypressio-front-end-unit-tests
         */
        tenonAnalyzeUrl(url) {
            const tenonApi = new TenonNode({
                key: process.env.TENON_API_KEY
            });

            return new Promise((resolve, reject) => {
                tenonApi.analyze(url, (err, tenonResult) => {
                    if (err) {
                        reject(err);
                    }

                    if (tenonResult.status > 400) {
                        reject(tenonResult.info);
                    } else {
                        resolve(tenonResult);
                    }
                });
            });
        },

        /**
         * @summary Lint an HTML fragment in Tenon
         * @param {string} selectorHtml - HTML fragment
         * @returns Tenon response object
         *
         * @see tenonCommands: https://github.com/poorgeek/tenon-selenium-example/blob/master/test/helpers/tenonCommands.js
         * @see tenon-node: https://www.npmjs.com/package/tenon-node
         * @see ypress.io Front End Unit Tests: https://github.com/dotherightthing/wpdtrt-plugin-boilerplate/wiki/Testing-&-Debugging#d-cypressio-front-end-unit-tests
         */
        tenonAnalyzeHtml(selectorHtml) {
            const html = normalizeWhitespace(selectorHtml); // strip whitespace between html tags
            const tenonApi = new TenonNode({
                key: process.env.TENON_API_KEY
            });

            return new Promise((resolve, reject) => {
                tenonApi.analyze(html, { fragment: '1' }, (err, tenonResult) => {
                    if (err) {
                        reject(err); // this error is really cryptic, so always check that the api key is correct!
                    }

                    if (tenonResult.status > 400) {
                        console.log(tenonResult.info); // eslint-disable-line no-console
                        reject(tenonResult.info);
                    } else {
                        resolve(tenonResult);
                    }
                });
            });
        }
    });
};
