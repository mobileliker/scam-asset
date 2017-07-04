<?php

/**
 * @version: 2.0 使用API作为前缀，并添加权限控制中间件
 * @author： wuzhihui
 * @date： 2017/4/25
 * @description:
 *（1）添加批量删除接口到各个模块，并去除原有的通用接口 （2017/7/3）
 *（2）转移功能接口到API.php；（2017/7/4）
 */

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

/*****************************************用户测试的路由***************************************************************/
Route::group(['prefix' => 'test'], function() {
    //Route::get('iqrcode', 'TestController@iqrcode');
    //Route::post('post-submit', 'TestController@postSubmit');
    //Route::get('batch-delete', 'Api\AssetController@batchDelete');
});
Route::group(['prefix' => 'html'], function() {
    //Route::get('auth/login', function() {
    //	return view(config('app.theme').'.auth.login');
    //});
});
/***********************************************************************************************t**********************/


/****************************************************Auth模块的路由****************************************************/
//Auth::routes();
// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
$this->post('login', 'Auth\LoginController@login');
$this->post('logout', 'Auth\LoginController@logout')->name('logout');
$this->get('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes... 去除了注册模块
//$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
//$this->post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
$this->post('password/reset', 'Auth\ResetPasswordController@reset');

//用户信息
$this->get('auth/info', 'Auth\AuthController@info');  //返回用户信息

/**********************************************************************************************************************/


Route::get('/', 'HomeController@index');  //Vue前端框架的入口


//Route::group([/*'namespace' => 'Home'*/], function() {
//    Route::get('s', 'HomeController@search');
//});
