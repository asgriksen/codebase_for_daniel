'use strict';

angular.module('bungomedia.system')
    .filter('nl2br', function ($sce) {
        return function (msg, is_xhtml) {
            var xhtml = is_xhtml || true;
            var breakTag = (xhtml) ? '<br />' : '<br>';
            var text = (msg + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
            return $sce.trustAsHtml(text);
        };
    })
    .filter('plural', function () {
        return function (input, noun) {
            if (!input) {
                return 'No ' + noun;
            } else if (input == 1) {
                return input + ' ' + noun;
            }
            return input + ' ' + noun + '(s)';
        };
    })
    .filter('money', function () {
        return function (input, currency) {
            if (!currency) {
                currency = 'USD';
            }
            return input + ' ' + currency;
        };
    })
    .filter('titleCase', function () {
        return function (input) {
            return input
                .replace(/\w\S*/g, function (txt) {
                    return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
                });
        };
    }).filter('location', function() {
        return function (input) {
            var locationParts = [];
            if (typeof input !== 'undefined') {
                switch (input.type) {
                    case 'country':
                        locationParts.push(input.name);
                        break;
                    case 'region':
                        locationParts.push(input.name);
                        locationParts.push(input.country_name);
                        break;
                    case 'city':
                        locationParts.push(input.name);
                        locationParts.push(input.country_name);
                        break;
                    case 'zip':
                        locationParts.push(input.primary_city);
                        locationParts.push(input.region);
                        locationParts.push(input.country_name);
                        locationParts.push(input.name);
                        break;
                }
            }
            return locationParts.join(', ');
        };
    });