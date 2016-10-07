'use strict';

angular.module('bungomedia')
    .config(['$controllerProvider', function ($controllerProvider) {
        // this option might be handy for migrating old apps, but please don't use it
        // in new ones!
        $controllerProvider.allowGlobals();

        window.isMobile = {
            Android: function () {
                return navigator.userAgent.match(/Android/i) ? true : false;
            },
            BlackBerry: function () {
                return navigator.userAgent.match(/BlackBerry/i) ? true : false;
            },
            iOS: function () {
                return navigator.userAgent.match(/iPhone|iPad|iPod/i) ? true : false;
            },
            Opera: function () {
                return navigator.userAgent.match(/Opera Mini/i) ? true : false;
            },
            Windows: function () {
                return (navigator.userAgent.match(/IEMobile/i) ? true : false) || (navigator.userAgent.match(/WPDesktop/i) ? true : false);
            },
            any: function () {
                return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
            }
        };
    }])

    //Setting HTML5 Location Mode
    .config(['$locationProvider',
        function ($locationProvider) {
            $locationProvider.hashPrefix("!");
        }
    ])
    .config(['$httpProvider', function ($httpProvider) {
        //initialize get if not there
        if (!$httpProvider.defaults.headers.get) {
            $httpProvider.defaults.headers.get = {};
        }

        // Answer edited to include suggestions from comments
        // because previous version of code introduced browser-related errors

        //disable IE ajax request caching
        $httpProvider.defaults.headers.get['If-Modified-Since'] = 'Mon, 26 Jul 1997 05:00:00 GMT';
        // extra
        $httpProvider.defaults.headers.get['Cache-Control'] = 'no-cache';
        $httpProvider.defaults.headers.get.Pragma = 'no-cache';
    }])
    .config(function (FacebookProvider) {
        // Set your appId through the setAppId method or
        // use the shortcut in the initialize method directly.
        FacebookProvider.init({
            appId: FACEBOOK_API_KEY,
            version: 'v2.5'
        });
    })

    /* Setup global settings */
    .factory('settings', ['$rootScope', function ($rootScope) {
    }])

    /* Setup Rounting For All Pages */
    .config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
        // Redirect any unmatched url
        $urlRouterProvider.otherwise("/");
    }])

    .run(['$rootScope', '$state', '$stateParams', '$timeout', 'editableOptions', function ($rootScope, $state, $stateParams, $timeout, editableOptions) {
        $rootScope.$state = $state;
        $rootScope.$stateParams = $stateParams;
        editableOptions.theme = 'bs3';
        // $rootScope.$on('$stateChangeSuccess', function(event, toState) {

        //   event.targetScope.$on('$viewContentLoaded', function () {
        var animate = function() {
            if(angular.element('#wrap').length == 0) {
                $timeout(animate, 200);
            }
            else {
                angular.element('html, body, #content').animate({scrollTop: 0}, 200);

                setTimeout(function () {
                    angular.element('#wrap').css('visibility', 'visible');

                    if (!angular.element('.dropdown').hasClass('open')) {
                        angular.element('.dropdown').find('>ul').slideUp();
                    }
                }, 200);
            }
        };
        $timeout(animate);

        // });

        // });
    }])

    .config(['uiSelectConfig', function (uiSelectConfig) {
        uiSelectConfig.theme = 'bootstrap';
    }])
    .config(['flowFactoryProvider', function (flowFactoryProvider) {
        flowFactoryProvider.defaults = {
            target: '/api/upload',
            permanentErrors:[404, 500, 501]
        };
    }]).config(function(uiSelectConfig) {
        uiSelectConfig.theme = 'bootstrap';
    });
