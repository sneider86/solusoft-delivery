/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'jquery',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'Magento_Checkout/js/model/default-post-code-resolver',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/select-shipping-address',
    'Magento_Checkout/js/model/shipping-rate-processor/new-address',
    'Magento_Checkout/js/model/shipping-service',
    'Magento_Checkout/js/model/shipping-rate-registry',
    'Magento_Checkout/js/checkout-data',
    'Magento_Checkout/js/model/address-converter'
], function (
        $,
        _,
        registry,
        Select,
        defaultPostCodeResolver,
        quote,
        selectShippingAddress,
        shippingRateProcessor,
        shippingService,
        rateRegistry,
        checkoutData,
        addressConverter
    ) {
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
            var selected = this.options().find(function (option) {
                return option.value == value;
            });
            if (selected) {
                // var cityField = registry.get(
                //     'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.city'
                // );
                // cityField.value(selected.label);
                var address = quote.shippingAddress();
                address.city = selected.label

                var checkoutProvider = registry.get('checkoutProvider');
                checkoutProvider.set('shippingAddress.city', selected.label);
                var shippingAddressData = checkoutProvider.get('shippingAddress');
                checkoutData.setShippingAddressFromData(shippingAddressData);

                rateRegistry.set(address.getCacheKey(), null);
                shippingService.setShippingRates([]);
            
                shippingRateProcessor.getRates(address);
            }
        }

    });
});
