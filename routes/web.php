<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

/******************test**************************/
Route::group(['prefix' => 'test'], function() {
	//Route::get('iqrcode', 'TestController@iqrcode');
});
Route::group(['prefix' => 'html'], function() {
	//Route::get('auth/login', function() {
	//	return view(config('app.theme').'.auth.login');
	//});
});
/******************end-test**********************/

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');


//user admin
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth'],function() {
	Route::get('/', 'AdminController@index');

	//check
	Route::post('util/check/{model}', 'UtilController@check');
	Route::post('util/batch-delete/{model}', 'UtilController@batch_delete');

	//asset
	Route::put('asset/import', 'AssetController@import');
	Route::get('asset/export', 'AssetController@batch_export');
	Route::get('asset/generate', 'AssetController@generate');
	Route::get('asset/{id}/export', 'AssetController@export');
	Route::get('asset/{id}/qrcode', 'AssetController@qrcode');
	Route::resource('asset', 'AssetController');

	//Invoice
	Route::get('invoice', 'InvoiceController@index');
	Route::get('invoice/{id}/export', 'InvoiceController@export');
});

//admin admin
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth','can:admin']],function() {

	//info
	//Route::post('info/batch-delete', 'InfoController@batch_delete');
	//Route::post('info/check', 'InfoController@check');
	Route::resource('info', 'InfoController');

	//user
	//Route::post('user/batch-delete', 'UserController@batch_delete');
	Route::put('user/{id}/settings', 'UserController@settings');
	Route::resource('user', 'UserController');

	//alog
	Route::get('alog', 'AlogController@index');

	//category
	Route::resource('category', 'CategoryController');
});

Route::group([/*'namespace' => 'Home'*/], function() {
	Route::get('s', 'HomeController@search');
});