'use strict';

angular.module('bungomedia.controllers')
	.config(['$stateProvider', function($stateProvider) {
		$stateProvider
			.state('accounts', {
				url: '/accounts',
				templateUrl: '/pages/account/accounts.html',
				controller: 'accountsController',
				resolve: {
					accounts: ['Account', '$q', function(Account, $q) {
						var deferred = $q.defer();

						Account.query().$promise
							.then(function(accounts) {
								deferred.resolve(accounts);
							}, function(err) {
								deferred.resolve([]);
							});

						return deferred.promise;
					}]
				}
			});
	}])
	.controller('accountsController', ['$rootScope', '$scope', '$compile', '$modal', '$q', 'Auth', 'Utils', 'Facebook', 'Account', 'accounts', function($rootScope, $scope, $compile, $modal, $q, Auth, Utils, Facebook, Account, accounts) {
		
		$rootScope.loadCurrentUser(true);

		var init = function() {
			$scope.accounts = accounts;
			$scope.createDataTable();
			$scope.restructureTable();			
		};

		$scope.createDataTable = function() {
			$scope.datatable = $('#table').DataTable({
				columnDefs: [
					{
						render: function ( data, type, row ) {
							var index = _.findIndex($scope.accounts, function(account) { return account.fb_account_id == row[0] });
							if($scope.accounts[index].is_selected === true || $scope.accounts[index].is_selected === "1" ) $scope.accounts[index].is_selected = true;
							else $scope.accounts[index].is_selected = false;
	            return '<input type="checkbox" ui-switch class="js_switch-primary" data-account-id="' + row[0] + '" ng-model="accounts[' + index + '].is_selected"/>';
	          },
	          targets: 8
	        }
				]
			});
		};

		$scope.restructureTable = function() {
			// clear
			$scope.datatable.clear();

			// add rows
			_.each($scope.accounts, function(account, index) {
				$scope.datatable.row.add([
					account.fb_account_id,
					account.name,
					account.metrics.length > 0 ? account.metrics[0] : 0,
					account.roi + '%',
					account.transactions,
					account.metrics.length > 1 ? account.metrics[1] : 0,
					'$' + account.revenue,
					'$' + (account.metrics.length >2 ? account.metrics[2] : 0),
					account.is_selected
				]);

				$scope.$watch('accounts[' + index + '].is_selected', function(newVal, oldVal) {
					if((typeof newVal === 'undefined') || (typeof oldVal === 'undefined') || newVal == oldVal) return;
					$rootScope.showWaiting();
					account.$update()
						.then(function() {
							$rootScope.hideWaiting();
						}, function(err) {
							$rootScope.onAPIError(err);
						});
				}, true);
			});

			// redraw
			$scope.datatable.draw();

			var $switches = angular.element('input[type=checkbox]');
			$compile($switches)($scope);
		};

		$scope.reload = function() {
			$rootScope.showWaiting();
			Account.query().$promise
				.then(function(accounts) {
					$rootScope.hideWaiting();
					$scope.accounts = accounts;

					$scope.restructureTable();
				}, function(err) {
					$rootScope.hideWaiting();
					$scope.accounts = [];
					$rootScope.onAPIError(err);
				});
		};

		$scope.onAddAccount = function() {
			Facebook.login(function(user) {
				if (user.status == 'connected') {
					Facebook.api(
							'/me/adaccounts?fields=name,account_id', function(response) {
								console.log(response);
								var modal = $modal.open({
									animation: true,
									templateUrl: '/pages/account/add-account.html',
									controller: 'addAccountController',
									size: 'lg',
									resolve: {
										accounts: function() {
											return response.data;
										},
										existing_accounts: function() {
											return $scope.accounts;
										}
									}
								});

								modal.result
									.then(function(accounts) {
										var $promises = [];
										_.each(accounts, function(account) {
											$promises.push( 
												(new Account({
													fb_account_id: account.account_id,
													fb_token: user.authResponse.accessToken
												}))
													.$save()
													.then(function() {
														return true;
													}, function(err) {
														return false;
													})
											);
										});

										$rootScope.showWaiting();
										$q.all($promises)
											.then(function() {
												$rootScope.hideWaiting();
												$scope.reload();
											});
									}, function() {
									});
							}
						);
				}
			}, {scope: 'email, user_friends, ads_management, ads_read, read_insights, manage_pages, public_profile'});
		};

		init();
	}]);