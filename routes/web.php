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

//Auth::routes();
// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');
$this->get('logout', 'Auth\LoginController@logout')->name('logout');
// Registration Routes...
//$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//$this->post('register', 'Auth\RegisterController@register');
// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

$this->get('auth/info', 'Auth\AuthController@info');


Route::get('/home', 'HomeController@index');


//user admin

Route::get('admin/vue', 'Admin\AdminController@vue');
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth'],function() {

    Route::post('image/update', 'AdminController@image');
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

    //Category
    Route::get('category/{serial}', 'CategoryController@serial');
});

//admin admin
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => ['auth','can:admin']],function() {

    //info
    //Route::post('info/batch-delete', 'InfoController@batch_delete');
    //Route::post('info/check', 'InfoController@check');
    Route::resource('info', 'InfoController');

    //user
    //Route::post('user/batch-delete', 'UserController@batch_delete');
    Route::get('user/all', 'UserController@all');
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
