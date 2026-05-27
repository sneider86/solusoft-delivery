define([
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/full-screen-loader',
    'mage/url',
    'uiRegistry'
], function ($, quote, fullScreenLoader, urlBuilder, registry) {
    'use strict';

    return function (Component) {

        return Component.extend({

            initialize: function () {
                this._super();
                var self = this;

                // registry.get(function (component) {
                //     console.log(component.name);
                // });

                if (window.regionSubscriberInitialized) {
                    return this;
                }

                window.regionSubscriberInitialized = true;
                var address = quote.shippingAddress();
                if (address && address.regionId) {
                    self.loadCityList(address.regionId);
                }
                
                quote.shippingAddress.subscribe(function (address) {
                    if (address && address.regionId) {
                        self.loadCityList(address.regionId);
                    }
                });

                return this;
            },

            loadCityList: function(regionId)
            {
                fullScreenLoader.startLoader();
                var uriBase = urlBuilder.build("delivery/ajax/city/");
                $.ajax({
                    url: uriBase,
                    data: {
                        regionId: regionId
                    },
                    async: true,
                    type: 'POST',
                    success: (response) => {
                        if (response) {
                            var cityCombobox = registry.get(
                                'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.state'
                            );
                            if (cityCombobox!==undefined) {
                                cityCombobox.options(response.options);
                            }
                            var cityField = registry.get(
                                'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.city'
                            );
                            cityField.value('');
                        }
                    },
                    error: () => {
                        
                    },
                    complete: () => {
                        fullScreenLoader.stopLoader();
                    }
                });
            }
        });
    };
});