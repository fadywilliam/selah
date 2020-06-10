<?php

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

/*Route::get('/', function () {	
    return view('welcome');
});*/
Route::get('/', 'WebsiteController@index');
// Route::get('/terms', 'WebsiteController@terms');


Auth::routes();

Route::post('insert','UserController@insert');
Route::post('update','UserController@update');
Route::get('saleofferdelete','SaleoffersController@delete');
Route::post('add','MypropertyController@add');
Route::post('edit','MypropertyController@edit');
Route::get('delete','MypropertyController@delete');
Route::get('deletemoredetails','MypropertyController@deletemoredetails');
Route::post('insert_addmoredetails','MypropertyController@insert_addmoredetails');
Route::post('update_addmoredetails','MypropertyController@update_addmoredetails');

Route::post('addinvoice','InvoiceController@addinvoice');
Route::post('update_invoice','InvoiceController@update_invoice');

// Route::post('insert_saleofferdetails','MypropertyController@insert_saleofferdetails');
// Route::post('update_saleofferdetails','MypropertyController@update_saleofferdetails');

Route::post('insert_saleofferdetails','SaleoffersController@insert_saleofferdetails');

Route::post('update_saleofferdetails','SaleoffersController@update_saleofferdetails');
Route::get('autocomplete', 'MypropertyController@autocomplete')->name('autocomplete');
Route::post('sendmessage', 'ChatController@sendmessage')->name('sendmessage');