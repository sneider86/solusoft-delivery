define([
    'jquery'
], function ($) {
    'use strict';

    return function (config, element) {
        var form = $(element),
            cityInput = form.find(config.cityInputSelector),
            regionInput = form.find(config.regionInputSelector),
            countryInput = form.find(config.countryInputSelector),
            citySelect,
            initialCityValue,
            originalCityId,
            cityInputClasses,
            loadSequence = 0;

        if (!cityInput.length || !regionInput.length) {
            return;
        }

        if (cityInput.data('solusoft-city-selector-initialized')) {
            return;
        }

        cityInput.data('solusoft-city-selector-initialized', true);

        originalCityId = cityInput.attr('id') || 'city';
        initialCityValue = $.trim(cityInput.val() || '');
        cityInputClasses = cityInput.attr('class') || '';

        cityInput.attr('id', originalCityId + '_value');
        cityInput.attr('type', 'hidden');
        cityInput.addClass('solusoft-city-value');

        citySelect = $('<select />', {
            id: originalCityId,
            'class': normalizeSelectClasses(cityInputClasses),
            title: cityInput.attr('title') || ''
        });

        cityInput.after(citySelect);

        function normalizeSelectClasses(classNames) {
            var normalizedClasses = (classNames || '')
                .replace(/\binput-text\b/g, '')
                .replace(/\brequired-entry\b/g, '')
                .replace(/\s+/g, ' ')
                .trim();

            return 'select validate-select required-entry ' + normalizedClasses;
        }

        function normalizeValue(value) {
            return $.trim((value || '').toString()).toLowerCase();
        }

        function sanitizeCityValue(value) {
            return $.trim((value || '').toString())
                .replace(/[^A-Za-z0-9\s\-\u00C0-\u024F']/g, '')
                .replace(/\s+/g, ' ');
        }

        function getSelectedCity(options, cityValue) {
            var selectedValue = '';

            if (!cityValue) {
                return selectedValue;
            }

            $.each(options || [], function (index, option) {
                var optionValue = option.label || option.value || '';

                if (normalizeValue(sanitizeCityValue(optionValue)) === normalizeValue(sanitizeCityValue(cityValue))) {
                    selectedValue = optionValue;
                    return false;
                }

                return true;
            });

            return selectedValue;
        }

        function setHiddenCityValue(value) {
            cityInput.val(sanitizeCityValue(value || ''));
        }

        function renderPlaceholder(placeholderText, disabled) {
            citySelect.empty().append($('<option />', {
                value: '',
                text: placeholderText
            }));
            citySelect.prop('disabled', !!disabled);
        }

        function renderOptions(options, preferredCityValue) {
            var selectedValue = getSelectedCity(options, preferredCityValue),
                placeholderText = config.placeholderText || '';

            citySelect.empty().append($('<option />', {
                value: '',
                text: placeholderText
            }));

            $.each(options || [], function (index, option) {
                var optionLabel = option.label || option.value || '',
                    optionValue = option.label || option.value || '';

                if (!optionValue) {
                    return true;
                }

                citySelect.append($('<option />', {
                    value: optionValue,
                    text: optionLabel
                }));

                return true;
            });

            citySelect.prop('disabled', !(options && options.length));
            citySelect.val(selectedValue);
            setHiddenCityValue(selectedValue);
        }

        function loadCities(regionId, preferredCityValue) {
            var requestId = ++loadSequence;

            if (!regionId) {
                renderPlaceholder(config.placeholderText || '', true);
                setHiddenCityValue('');
                return $.Deferred().resolve().promise();
            }

            renderPlaceholder(config.loadingText || config.placeholderText || '', true);

            return $.ajax({
                url: config.endpointUrl,
                type: 'POST',
                data: {
                    regionId: regionId
                }
            }).done(function (response) {
                var options = response && $.isArray(response.options) ? response.options : [];

                if (requestId !== loadSequence) {
                    return;
                }

                if (!options.length) {
                    renderPlaceholder(config.emptyText || config.placeholderText || '', true);
                    setHiddenCityValue('');
                    return;
                }

                renderOptions(options, preferredCityValue);
            }).fail(function () {
                if (requestId !== loadSequence) {
                    return;
                }

                renderPlaceholder(config.emptyText || config.placeholderText || '', true);
                setHiddenCityValue('');
            });
        }

        function syncCities() {
            var regionId = regionInput.val() || '',
                preferredCityValue = cityInput.val() || initialCityValue;

            loadCities(regionId, preferredCityValue);
            initialCityValue = '';
        }

        citySelect.on('change', function () {
            setHiddenCityValue($(this).val());
        });

        regionInput.on('change', function () {
            setHiddenCityValue('');
            syncCities();
        });

        if (countryInput.length) {
            countryInput.on('change', function () {
                setHiddenCityValue('');
                setTimeout(syncCities, 0);
            });
        }

        syncCities();
    };
});
