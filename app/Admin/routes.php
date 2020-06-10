<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
});


Route::group(['middleware' => 'auth'], function()
{
    // swiching language
    Route::get('/language/{locale}', 'LocalizationController@localization')->name('localization');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    // Newsletters routes
    Route::resource('newsletters','NewsletterController');
    Route::get('/api/newsletters','NewsletterController@ajax')->name('newsletterApi');

    //My Account Routes
    Route::get('/home', 'HomeController@index')->name('home');

});