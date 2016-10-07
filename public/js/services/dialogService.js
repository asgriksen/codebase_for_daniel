'use strict';


angular.module('bungomedia.controllers')
    .factory('DialogService', ['$modal', '$q', function ($modal, $q) {
        var DialogService = {
            openTableColumnPreferenceDialog: function (availableColumns, currentColumns) {
                var deferred = $q.defer();

                var modalInstance = $modal.open({
                    templateUrl: 'table-column-preference.html',
                    controller: 'tableColumnPreferenceDialogController',
                    size: 'lg',
                    resolve: {
                        availableColumns: function () {
                            return availableColumns;
                        },
                        currentColumns: function () {
                            return currentColumns;
                        }
                    }
                });

                modalInstance.result.then(function (selectedColumns) {
                    deferred.resolve(selectedColumns);
                }, function () {
                    deferred.reject();
                });

                return deferred.promise;
            },
            openChooseProductDialog: function (categoryId) {
                var deferred = $q.defer();

                var modalInstance = $modal.open({
                    templateUrl: 'choose-product.html',
                    controller: 'chooseProductDialogController',
                    size: 'lg',
                    resolve: {
                        categoryId: function () {
                            return categoryId;
                        }
                    }
                });

                modalInstance.result.then(function (selectedProducts) {
                    deferred.resolve(selectedProducts);
                }, function () {
                    deferred.reject();
                });

                return deferred.promise;
            }
        };

        return DialogService;
    }])

    .controller('tableColumnPreferenceDialogController', ['$scope', '$modal', '$modalInstance', 'availableColumns', 'currentColumns',
        function ($scope, $modal, $modalInstance, availableColumns, currentColumns) {
            var init = function () {
                $scope.availableColumns = availableColumns;
                $scope.currentColumns = currentColumns;
            };

            $scope.ok = function () {
                $modalInstance.close($scope.currentColumns);
            };

            $scope.cancel = function () {
                $modalInstance.dismiss('cancel');
            };

            init();
        }])

    .controller('chooseProductDialogController', ['$scope', '$rootScope', '$modal', '$modalInstance', 'Product', 'REVENUE_FILTER', 'categoryId',
        function ($scope, $rootScope, $modal, $modalInstance, Product, REVENUE_FILTER, categoryId) {
            $scope.loadProducts = function () {
                $rootScope.showWaiting();
                Product.query()
                    .$promise
                    .then(function (products) {
                        $scope.products = products;
                        $rootScope.hideWaiting();
                    }, function (err) {
                        $rootScope.onAPIError(err);
                        $rootScope.hideWaiting();
                    });
            };

            var init = function () {
                $scope.REVENUE_FILTER = REVENUE_FILTER;
                $scope.revenueFilter = 'day';
                $scope.selectedProducts = [];
            };

            $scope.$watch('revenueFilter', function () {
                $scope.loadProducts();
            }, true);

            $scope.ok = function () {
                $modalInstance.close($scope.selectedProducts);
            };

            $scope.cancel = function () {
                $modalInstance.dismiss('cancel');
            };

            init();
        }]);