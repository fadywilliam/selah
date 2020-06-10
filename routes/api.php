<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::group(['middleware' => 'auth:api'], function(){

});
Route::post('update_profile', 'API\UserController@update_profile');
Route::post('details', 'API\UserController@details');
Route::post('verification_phone_code', 'API\UserController@verification_phone_code');
Route::post('change_password', 'API\UserController@change_password');
Route::post('change_email', 'API\UserController@change_email');
Route::post('forget_password', 'API\UserController@forget_password');
Route::post('reset_password', 'API\UserController@reset_password');
Route::post('resend_code_phone', 'API\UserController@resend_code_phone');
Route::post('addproperty', 'API\UserController@addproperty');
Route::get('myproperty', 'API\UserController@myproperty');
Route::get('myproperty_details/{id}', 'API\UserController@myproperty_details');
Route::get('myproperty_moredetails/{id}', 'API\UserController@myproperty_moredetails');
Route::get('renter_details', 'API\UserController@renter_details');
Route::get('saleoffers', 'API\UserController@saleoffers');
Route::get('myproperty_manager', 'API\UserController@myproperty_manager');
Route::get('chat', 'API\UserController@chat');
Route::post('send_message', 'API\UserController@send_message');