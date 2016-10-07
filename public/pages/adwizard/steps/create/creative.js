'use strict';

angular.module('bungomedia.controllers')
    .controller('adWizardCreateCreativeController', ['$rootScope', '$scope', '$q', '$modal', 'Auth', 'Utils', 'Cart', 'Category', 'DialogService', 'Account', 'Facebook', 'REVENUE_FILTER', function ($rootScope, $scope, $q, $modal, Auth, Utils, Cart, Category, DialogService, Account, Facebook, REVENUE_FILTER) {
        $scope.REVENUE_FILTER = REVENUE_FILTER;
        $scope.onError = function (err) {
            $rootScope.onAPIError(err);
            $rootScope.hideWaiting();
        };

        var init = function () {
            $rootScope.showWaiting();

            $scope.carts = [];
            $scope.topCategories = [];
            $scope.topCategory = null;

            $scope.filters = {
                cart: 0,
                topCategoryId: 0,
                categoryId: 0,
                convAndRev: 'day' // day/week/month
            };

            $scope.newFeed = {
                name: '',
                url: ''
            };

            $scope.currentAd = _.last($scope.adData.multi_products_ads.ads);

            var facebookPagesDeferred = $q.defer();
            Facebook.api(
                '/me/accounts', function (response) {
                    var pages = response.data;
                    facebookPagesDeferred.resolve(_.map(pages, function (page) {
                        return {id: page.id, name: page.name, picture: page.picture.data.url};
                    }));
                }, {access_token: $scope.account.fb_token, fields: 'id,picture,name'}
            );

            $q.all({carts: Cart.query().$promise, facebookPages: facebookPagesDeferred.promise})
                .then(function (results) {
                    $scope.carts = results.carts;
                    $scope.facebookPages = results.facebookPages;
                    $rootScope.hideWaiting();
                }, $scope.onError);

        };

        $scope.cartSelected = function () {
            $scope.filters.topCategoryId = 0;
            $scope.filters.categoryId = 0;
            $scope.topCategory = null;
            $scope.topCategories = [];
            if ($scope.filters.cart) {
                $rootScope.showWaiting();
                $q.all({
                    topCategories: Cart.categories({id: $scope.filters.cart})
                }).then(function (results) {
                    $scope.topCategories = results.topCategories;
                    $rootScope.hideWaiting();
                }, $scope.onError);
            }
        };

        $scope.topCategorySelected = function (refresh) {
            if (refresh) {
                $scope.filters.categoryId = 0;
                $scope.topCategory = null;
            }
            if ($scope.filters.topCategoryId) {
                if (refresh) {
                    $scope.filters.categoryId = $scope.filters.topCategoryId;
                }
                $rootScope.showWaiting();

                $q.all({
                    topCategory: Category.get({
                        id: $scope.filters.topCategoryId,
                        convAndRev: $scope.filters.convAndRev
                    }).$promise
                }).then(function (results) {
                    $scope.topCategory = results.topCategory;
                    $rootScope.hideWaiting();
                }, $scope.onError);
            }
        }

        $scope.$watch('filters.cart', function () {
            $scope.cartSelected();
        }, true);

        $scope.$watch('filters.topCategoryId', function () {
            $scope.topCategorySelected(true);
        }, true);

        $scope.$watch('filters.convAndRev', function () {
            $scope.topCategorySelected();
        }, true);

        $scope.saveFeed = function () {
            if ($scope.newFeed.name && $scope.newFeed.url) {
                $rootScope.showWaiting();
                new Cart({
                    name: $scope.newFeed.name,
                    ApiPath: $scope.newFeed.url,
                    user_id: $rootScope.currentUser.id
                })
                    .$save()
                    .$promise
                    .then(function () {
                        $scope.newFeed = {};
                        $scope.showAddFeed = false;
                        Cart.query().$promise
                            .then(function (carts) {
                                $scope.carts = carts;
                                $rootScope.hideWaiting();
                            }, $scope.onError);
                    }, $scope.onError);
            }
        };

        $scope.updateFeedData = function () {
            $scope.cartSelected();
        };

        $scope.deleteFeed = function () {
            $rootScope.showWaiting();

            Cart.delete({id: $scope.filters.cart})
                .$promise
                .then(function () {
                    $scope.filters.cart = 0;
                    $scope.cartSelected();
                    $rootScope.hideWaiting();
                }, $scope.onError);
        };

        $scope.chooseProductClicked = function () {
            var deferred = $q.defer();
            DialogService.openChooseProductDialog($scope.filters.categoryId)
                .then(function (selectedProducts) {
                    $scope.adData.right_ads.ads = $scope.adData.right_ads.concat(selectedProducts);
                    deferred.resolve(selectedProducts);
                }, function () {
                    deferred.reject();
                });
            return deferred.promise;
        };

        $scope.createAdClicked = function (type) {
            if (type === 'right') {
                $scope.adData.right_ads.ads.push({});
            } else if (type === 'news') {
                $scope.adData.news_ads.ads.push({});
            } else if (type === 'multiple') {
                $scope.adData.multi_products_ads.ads.push({products: [{}]});
                $scope.currentAd = _.last($scope.adData.multi_products_ads.ads);
            }
        };

        $scope.adSelected = function (ad) {
            $scope.currentAd = ad;
        };

        $scope.facebookPageSelected = function (ad, page) {
            ad.name = page.name;
            ad.profile_image_url = page.picture;
            ad.description = page.about;
        };

        $scope.newProductClicked= function (ad) {
            ad.products.push({});
        };

        init();
    }]);