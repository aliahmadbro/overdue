<?php

use App\Http\Controllers\Backend\Admin\Dashboard\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'App\Http\Controllers\Auth\LoginController@index')->middleware('session_auth');

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');


    /**
     * App Routes
     */
        Route::get('user-privacy-policy', 'HomeController@UserPrivacyPolicy')->name('userPrivacyPolicy');
        Route::get('provider-privacy-policy', 'HomeController@ProviderPrivacyPolicy')->name('ProviderPrivacyPolicy');

    /**
     * AUTH ROUTES
     */
    Route::group(['namespace' => 'Auth', 'middleware' => 'session_auth'], function () {
        Route::get('login', 'LoginController@index')->name('login');
        Route::post('authenticate', 'LoginController@authenticate')->name('authenticate');
        Route::get('register', 'RegisterController@index')->name('register');
    });

    /**
     * ADMIN ROUTES
     */
    Route::group(['middleware' => ['auth', 'isAdmin'], 'namespace' => 'Admin'], function () {
        /**
         * DASHBOARD ROUTES
         */
        Route::group(['namespace' => 'Dashboard', 'prefix' => 'dashboard'], function () {
            Route::get('', 'DashboardController@index')->name('admin.dashboard');
        });

        /**
         * SERVICE ROUTES
         */
        Route::group(['namespace' => 'Services', 'prefix' => 'services'], function () {
            Route::get('', 'ServicesController@listing')->name('admin.services');
            Route::get('create', 'ServicesController@create')->name('admin.services.create');
            Route::get('delete/{id}', 'ServicesController@delete')->name('admin.services.delete');
            Route::get('edit/{id}', 'ServicesController@edit')->name('admin.services.edit');
            Route::post('save', 'ServicesController@save')->name('admin.services.save');
            Route::post('update', 'ServicesController@update')->name('admin.services.update');
        });

        /**
         * CATEGORY ROUTES
         */
        Route::group(['namespace' => 'Category', 'prefix' => 'category'], function () {
            Route::get('', 'CategoryController@listing')->name('admin.category');
            Route::get('create', 'CategoryController@create')->name('admin.category.create');
            Route::get('delete/{id}', 'CategoryController@delete')->name('admin.category.delete');
            Route::get('edit/{id}', 'CategoryController@edit')->name('admin.category.edit');
            Route::post('save', 'CategoryController@save')->name('admin.category.save');
            Route::post('update', 'CategoryController@update')->name('admin.category.update');
        });
        /**
         * CUSTOMER ROUTES
         */
        Route::group(['namespace' => 'Customer', 'prefix' => 'customer'], function () {
            Route::get('', 'CustomerController@listing')->name('admin.customer');
            Route::get('status/{id}/{status}', 'CustomerController@status')->name('admin.customer.status');
            Route::get('delete/{id}', 'CustomerController@delete')->name('admin.customer.delete');
            Route::get('edit/{id}', 'CustomerController@edit')->name('admin.customer.edit');
            Route::post('save', 'CustomerController@save')->name('admin.customer.save');
            Route::post('update', 'CustomerController@update')->name('admin.customer.update');
        });

                /**
         * PROVIDER SUPPORT ROUTES
         */
        Route::group(['namespace' => 'ProviderSupport', 'prefix' => 'providerSupport'], function () {
            Route::get('', 'ProviderSupportController@listing')->name('admin.providerSupport');
            Route::get('create', 'ProviderSupportController@create')->name('admin.providerSupport.create');
            Route::get('delete/{id}', 'ProviderSupportController@delete')->name('admin.providerSupport.delete');
            Route::get('edit/{id}', 'ProviderSupportController@edit')->name('admin.providerSupport.edit');
            Route::post('save', 'ProviderSupportController@save')->name('admin.providerSupport.save');
            Route::post('update', 'ProviderSupportController@update')->name('admin.providerSupport.update');
        });

                /**
         * USER SUPPORT ROUTES
         */
        Route::group(['namespace' => 'UserSupport', 'prefix' => 'userSupport'], function () {
            Route::get('', 'UserSupportController@listing')->name('admin.userSupport');
            Route::get('create', 'UserSupportController@create')->name('admin.userSupport.create');
            Route::get('delete/{id}', 'UserSupportController@delete')->name('admin.userSupport.delete');
            Route::get('edit/{id}', 'UserSupportController@edit')->name('admin.userSupport.edit');
            Route::post('save', 'UserSupportController@save')->name('admin.userSupport.save');
            Route::post('update', 'UserSupportController@update')->name('admin.userSupport.update');
        });


                        /**
         * App ROUTES
         */
        Route::group(['namespace' => 'App', 'prefix' => 'App'], function () {
            Route::get('provider-privacy-policy', 'AppController@create')->name('admin.app.create');
            Route::post('save', 'AppController@save')->name('admin.app.save');
            Route::get('user-privacy-policy', 'AppController@userCreate')->name('admin.app.userCreate');
            Route::post('userSave', 'AppController@userSave')->name('admin.app.userSave');
        });

        /**
         * USER ROUTES
         */
        Route::group(['namespace' => 'User', 'prefix' => 'user'], function () {
            Route::get('', 'UserController@index')->name('admin.user');
            Route::get('edit/{id}', 'UserController@edit')->name('admin.user.edit');
            Route::post('update', 'UserController@update')->name('admin.user.update');
            Route::get('delete/{id}', 'UserController@delete')->name('admin.user.delete');
        });

        /**
         * BANNER ROUTES
         */
        Route::group(['namespace' => 'Banner', 'prefix' => 'banner'], function () {
            Route::get('', 'BannerController@listing')->name('admin.banner');
            Route::get('create', 'BannerController@create')->name('admin.banner.create');
            Route::post('save', 'BannerController@save')->name('admin.banner.save');
            Route::get('edit/{id}', 'BannerController@edit')->name('admin.banner.edit');
            Route::post('update', 'BannerController@update')->name('admin.banner.update');
            Route::get('delete/{id}', 'BannerController@delete')->name('admin.banner.delete');
        });
    });
});
