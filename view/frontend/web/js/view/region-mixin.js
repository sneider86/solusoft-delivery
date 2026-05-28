define([
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/full-screen-loader',
    'mage/url',
    'uiRegistry'
], function ($, quote, fullScreenLoader, urlBuilder, registry) {
    'use strict';

    var shippingRegionFieldPath = 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.region_id',
        shippingCityFieldPath = 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.state',
        shippingAddressFieldPath = 'checkout.steps.shipping-step.shippingAddress.shipping-address-fieldset.city';

    return function (Component) {

        return Component.extend({

            initialize: function () {
                this._super();
                var self = this;

                if (this._solusoftDeliveryRegionSubscriberInitialized) {
                    return this;
                }

                this._solusoftDeliveryRegionSubscriberInitialized = true;

                quote.shippingAddress.subscribe(function (address) {
                    if (address && address.regionId) {
                        self.loadCityList(address.regionId);
                    }
                });

                registry.async(shippingRegionFieldPath)(function (regionField) {
                    var initialRegionId = regionField.value();

                    if (initialRegionId) {
                        self.loadCityList(initialRegionId);
                    }

                    regionField.on('value', function (regionId) {
                        if (regionId) {
                            self.loadCityList(regionId);
                        } else {
                            self.resetCityField();
                        }
                    });
                });

                return this;
            },

            resetCityField: function () {
                var cityCombobox = registry.get(shippingCityFieldPath),
                    cityField = registry.get(shippingAddressFieldPath);

                if (cityCombobox !== undefined) {
                    if (typeof cityCombobox.setOptions === 'function') {
                        cityCombobox.setOptions([]);
                    } else if (typeof cityCombobox.options === 'function') {
                        cityCombobox.options([]);
                    }
                }

                if (cityField !== undefined) {
                    cityField.value('');
                }
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
                            var cityCombobox = registry.get(shippingCityFieldPath);
                            if (cityCombobox!==undefined) {
                                if (typeof cityCombobox.setOptions === 'function') {
                                    cityCombobox.setOptions(response.options);
                                } else if (typeof cityCombobox.options === 'function') {
                                    cityCombobox.options(response.options);
                                }
                            }
                            var cityField = registry.get(shippingAddressFieldPath);
                            if (cityField !== undefined) {
                                cityField.value('');
                            }
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
