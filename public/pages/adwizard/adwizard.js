'use strict';

angular.module('bungomedia.controllers')
    .config(['$stateProvider', function ($stateProvider) {
        $stateProvider
            .state('adwizard', {
                url: '/adwizard/:step',
                templateUrl: '/pages/adwizard/adwizard.html',
                controller: 'adWizardController',
                resolve: {
                    currentStep: ['$stateParams', function ($stateParams) {	// goal, create, finish
                        var currentStep = $stateParams.step;
                        if (currentStep && currentStep !== '') return currentStep;
                        else return 'goal';
                    }],
                    accounts: ['Account', '$q', function (Account, $q) {
                        var deferred = $q.defer();

                        Account.query().$promise
                            .then(function (accounts) {
                                deferred.resolve(accounts);
                            }, function (err) {
                                deferred.resolve([]);
                            });

                        return deferred.promise;
                    }]
                }
            });
    }])
    .controller('adWizardController', ['$rootScope', '$scope', '$q', 'Auth', 'Utils', 'Facebook', 'AdWidzard', 'currentStep', 'accounts', function ($rootScope, $scope, $q, Auth, Utils, Facebook, AdWidzard, currentStep, accounts) {

        $rootScope.loadCurrentUser(true);

        $rootScope.clearMessage();

        var STEPS = ['goal', 'create', 'finish'];

        var init = function () {
            $scope.currentStep = currentStep;

            $scope.defaultData = {
                schedule: {
                    day_of_week: {
                        day: 1,
                        start: '12:00 PM',
                        end: '12:00 PM'
                    }
                }
            };

            $scope.adData = {
                // Right Hand Side Ads
                right_ads: {
                    show_desktop: true,
                    ads: [{
                        name: null,
                        description: null,
                        link: null,
                        image_url: null
                    }]
                },
                //News Feed Ads
                news_ads: {
                    show_mobile: true,
                    show_desktop: true,
                    ads: [{
                        profile_image_url: null,
                        name: null,
                        sponsored: null,
                        description: null,
                        link: null,
                        image_url: null
                    }]
                },
                //Multiple Products Ads
                multi_products_ads: {
                    show_mobile: true,
                    show_desktop: true,
                    ads: [{
                        name: null,
                        description: null,
                        profile_image_url: null,
                        see_more: {
                            url: null,
                            display_url: null
                        },
                        order_by_performance: false,
                        products: [{
                            headline: null,
                            description: null,
                            destination_url: null,
                            image_url: null,
                            call_to_action: null
                        }]
                    }]
                }
            };

            $scope.targetingData = {
                locations: {
                    includes: [],
                    excludes: []
                },
                connections: {
                    type: 'ALL',
                    includes: [],
                    excludes: [],
                    friends: []
                },
                gender: 'ALL'
            };

            $scope.campaignData = {
                conversion_pixel: 'Checkouts',
                campaign_name: null,
                adset_name_type: 'AUTO',
                adset_name: null,
                adset_prefix: null,
                adset_budget: 0,
                adset_budget_type: 'DAILY',
                schedule: {
                    type: 'START_NOW',
                    start_date: null,
                    end_date: null,
                    days_of_week: [angular.copy($scope.defaultData.schedule.day_of_week)],
                    opened: {
                        start_date: false,
                        end_date: false
                    }
                }
            };

            $scope.account = _.first(accounts);

            var adAccountsDefer = $q.defer();
            Facebook.api(
                '/me/adaccounts', function (response) {
                    adAccountsDefer.resolve(response.data);
                }, {access_token: $scope.account.fb_token}
            );
            $scope.adAccountsPromise = adAccountsDefer.promise;
        };

        $scope.proceedNextStep = function () {
            var stepIndex = STEPS.indexOf($scope.currentStep);

            if (stepIndex == STEPS.length - 1) {

            }
            else {
                stepIndex++;
                $scope.currentStep = STEPS[stepIndex];
            }
        };

        $scope.finish = function () {
            var adData = angular.copy($scope.adData);
            adData.multi_products_ads.ads = _.map($scope.adData.multi_products_ads.ads, function (ad) {
                ad.products = _.map(ad.products, function (product) {
                    return _.omit(product, 'image');
                });
                return ad;
            });
            AdWidzard.save({
                targeting_data: $scope.targetingData,
                campaign_data: $scope.campaignData,
                ad_data: adData
            });
        };

        init();
    }]);