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

/**
 * @version: 2.0 使用API作为前缀，并添加权限控制中间件
 * @author： wuzhihui
 * @date： 2017/4/25
 * @description:
 *
 */

/*****************************************用户测试的路由***************************************************************/
Route::group(['prefix' => 'test'], function() {
    //Route::get('iqrcode', 'TestController@iqrcode');
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


Route::group(['prefix' => 'api', 'namespace' => 'Api', 'middleware' => 'auth'], function() {
    //通用接口
    Route::post('util/batch-delete/{model}', 'UtilController@batchDelete'); //批量删除
    Route::delete('util/batch-delete/{model}', 'UtilController@batchDelete');//批量删除
    Route::post('util/check/{model}', 'UtilController@check'); //验证
    Route::post('image/update', 'AdminController@image'); //异步上传图片

    //基础信息接口
    Route::get('user/menu', 'UserController@menu'); //获取用户菜单
    Route::get('user/all', 'UserController@all'); //获取所有用户
    Route::get('category/{serial}', 'CategoryController@serial'); //获取分类列表

    //资产管理模块
    Route::group(['prefix' => 'asset'], function() {
        Route::get('', 'AssetController@index');
        Route::post('', 'AssetController@store');
        Route::get('{id}', 'AssetController@show');
        Route::get('{id}/edit', 'AssetController@edit');
        Route::put('{id}', 'AssetController@update');
        Route::delete('{id}', 'AssetController@destroy');

        //Route::put('import', 'AssetController@import'); //导入固定资产的数据
        //Route::get('generate', 'AssetController@generate'); //批量导出所有的单据
        //Route::get('{id}/qrcode', 'AssetController@qrcode'); //生成二维码
        Route::get('export', 'AssetController@batchExport'); //批量导出所有固定资产
        Route::get('{id}/export', 'AssetController@export'); //导出单据
    });

    //单据管理模块
    Route::group(['prefix' => 'invoice'], function() {
        //Route::get('', 'InvoiceController@index');
        //Route::get('{id}/export', 'InvoiceController@export');
    });

    //用户管理模块
    Route::group(['prefix' => 'user'], function() {
        Route::get('', 'UserController@index');
        Route::post('', 'UserController@store');
        Route::get('{id}', 'UserController@show');
        Route::get('{id}/edit', 'UserController@edit');
        Route::put('{id}', 'UserController@update');
        Route::delete('{id}', 'UserController@destroy');

        //Route::put('user/{id}/settings', 'UserController@settings');
    });

    //操作日志模块
    Route::group(['prefix' => 'alog'], function() {
        Route::get('', 'AlogController@index');
    });
});

//Route::group([/*'namespace' => 'Home'*/], function() {
//    Route::get('s', 'HomeController@search');
//});
