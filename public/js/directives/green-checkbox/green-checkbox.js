'use strict';

angular.module('bungomedia.controllers')
    .directive('greenCheckbox', function() {
        return {
            restrict: 'E',
            template: '<span ng-class="{class: class}" ng-click="toggle($event)"><i class="fa" ng-class="{\'fa-check-square\': isChecked, \'fa-square\': !isChecked}"></i> {{label}}</span>',
            replace: true,
            require: 'ngModel',
            scope: {
                label: '@',
                class: '@'
            },
            link: function ($scope, $element, $attrs, $model) {
                $model.$formatters.unshift(function(value) {
                    $scope.isChecked = value == true;
                    return value;
                });

                $scope.toggle = function(event) {
                    $scope.isChecked = !$scope.isChecked;
                    $model.$setViewValue($scope.isChecked);
                    event.preventDefault();
                    event.stopPropagation();
                }
            }
        }
    });

