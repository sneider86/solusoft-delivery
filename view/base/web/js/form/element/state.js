/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Checkout/js/model/default-post-code-resolver'
], function (_, registry, Select, defaultPostCodeResolver) {
    'use strict';

    return Select.extend({
        defaults: {
            skipValidation: false,
            elementTmpl: 'Solusoft_Delivery/form/element/select',
        },

        /**
         * {@inheritdoc}
         */
        initialize: function () {
            var option;
            this._super();
            return this;
        },

        /**
         * Method called every time country selector's value gets changed.
         * Updates all validations and requirements for certain country.
         * @param {String} value - Selected country ID.
         */
        onUpdate: function (value) {
            console.log(value);
        },

        /**
         * Hide select and corresponding text input field if region must not be shown for selected country.
         *
         * @private
         * @param {Object}option
         */
        hideRegion: function (option) {
           
        }
    });
});
