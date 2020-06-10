<?php

use Illuminate\Routing\Router;

Store::routes();

Route::group([
    'prefix'        => configstore('store.route.prefix'),
    'namespace'     => configstore('store.route.namespace'),
    'middleware'    => configstore('store.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('store.home');
});