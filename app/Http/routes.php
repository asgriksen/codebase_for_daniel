<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['prefix' => 'admin', 'middleware' => 'auth', 'namespace' => 'Admin'], function()
{
    Route::resource('users', 'UserController');
});

Route::group(['prefix' => 'api'], function()
{
    /** User related **/
    Route::post 		('users/auth', 'AuthController@auth');

    Route::group(['middleware' => 'auth'], function() {

        /** Authentication */
        Route::get 		('users/logout', 'AuthController@logout');
        Route::post 	("users/online", 'AuthController@online');
        Route::get 	    ("users/me", 'AuthController@info');
        Route::post     ("users/me", 'AuthController@update');

        /** Account **/
        Route::get 		('accounts', 'AccountController@index');
        Route::get 		('accounts/{id}', 'AccountController@get');
        Route::post 	('accounts', 'AccountController@create');
        Route::put 		('accounts/{id}', 'AccountController@update');
        Route::delete 	('accounts/{id}', 'AccountController@destroy');

        /** Ad Creative */
        Route::resource('ad-creatives', 'AdCreativeController');

        /** Ads */
        Route::resource('ads', 'AdController', ['except' => ['create', 'edit']]);

        /** Ad Sets **/
        Route::resource('ad-sets', 'AdSetController', ['except' => ['create', 'edit']]);

        /** Campaign **/
        Route::get 		('campaigns', 'CampaignController@index');
        Route::get 		('campaigns/{id}', 'CampaignController@get');
        Route::post 	('campaigns', 'CampaignController@create');
        Route::put 		('campaigns/{id}', 'CampaignController@update');
        Route::delete 	('campaigns/{id}', 'CampaignController@destroy');

        /** AdWizard **/
        Route::post     ('adwizards', 'AdWizardController@create');

        /** Carts */
        Route::resource('carts', 'CartController');

        /** Cart Categories */
        Route::get('carts/{carts}/categories', ['as' => 'carts.show.categories.index', 'uses' => 'CartController@categories']);

        /** Conditions */
        Route::resource('conditions', 'ConditionController', ['only' => ['index','show']]);

        // Find Products in Api2Cart
        Route::get('carts/{carts}/find-products', 'CartController@findProducts');

        // Import products from Api2Cart
        Route::get('carts/{carts}/import-products', 'CartController@importProducts');

        // List Products in Api2Cart
        Route::get('carts/{carts}/list-products', 'CartController@listProducts');

        // Notifications
        Route::resource('notifications', 'NotificationController');

        /** Product */
        Route::resource('products', 'ProductController');

        /** Product Category */
        Route::resource('cart-categories', 'CartCategoryController', ['only' => ['index', 'show']]);

        // Rules
        Route::resource('rules', 'RuleController');

        // Rule Applicaitons
        Route::resource('rule-applications', 'RuleApplicationController');

        // Tasks
        Route::resource('tasks', 'TaskController');

        // User Preferences
        Route::resource('user-preferences', 'UserPreferenceController');

        // UTM Codes
        Route::resource('utm-codes', 'UtmCodeController');

        // File upload
        Route::get('upload', 'FileController@upload');
        Route::post('upload', 'FileController@upload');
        Route::get('files/{name}', 'FileController@get');
    });

    Route::group(['namespace' => 'Etsy', 'prefix' => 'etsy'], function()
    {
        get('login', ['as' => 'api.etsy.auth.login', 'uses' => 'AuthController@login']);
    });

    Route::group(['namespace' => 'Etsy', 'prefix' => 'etsy'], function()
    {
        // Log into Etsy.
        get('auth/login',  ['as' => 'api.etsy.auth.login',  'uses' => 'AuthController@login']);
    });

    Route::group(['namespace' => 'Google', 'prefix' => 'google'], function()
    {
        // Log into Google.
        get('auth/login',  ['as' => 'api.google.auth.login',  'uses' => 'AuthController@login']);

        // Log out of Google.
        get('auth/logout', ['as' => 'api.google.auth.logout', 'uses' => 'AuthController@logout']);

        // Google log in status.
        get('auth/login-status', ['as' => 'api.google.auth.login-status', 'uses' => 'AuthController@loginStatus']);

        resource('accounts', 'AccountController');
    });
});


Route::get  ('login', 'IndexController@login');
Route::get  ('logout', 'IndexController@logout');
Route::get  ('/', 'IndexController@index');