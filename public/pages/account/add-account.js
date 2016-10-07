'use strict';

angular.module('bungomedia.controllers')
	.controller('addAccountController', ['$scope', '$modalInstance', 'accounts', 'existing_accounts', function($scope, $modalInstance, accounts, existing_accounts) {
		$scope.ok = function() {
			$modalInstance.close($scope.getSelected());
		};
		$scope.cancel = function() {
			$modalInstance.dismiss('cancel');
		};

		var init = function() {
			_.each(accounts, function(account, index) {
				accounts[index].is_selected = $scope.exist(account.account_id);
			});
			$scope.accounts = accounts;
		};

		$scope.exist = function (account_id) {
			return typeof _.findWhere(existing_accounts, {fb_account_id: account_id}) !== 'undefined';
		};

		$scope.getSelected = function() {
			return _.chain($scope.accounts)
				.filter( function(account) { return account.is_selected; } )
				.value();
 		};
 
		init();
	}]);