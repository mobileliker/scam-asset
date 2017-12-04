<?php

/**
 * @version: 2.0 使用API作为前缀，并添加权限控制中间件
 * @author： wuzhihui
 * @date： 2017/4/25
 * @description:
 *（1）添加批量删除接口到各个模块，并去除原有的通用接口 （2017/7/3）
 *（2）转移功能接口到API.php；（2017/7/4）
 * (3)添加原API的接口；(2017/12/4)
 * (4)将农具模块的resource函数改为直接撰写：（2017/12/4）
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

Route::group(['prefix' => 'api', 'namespace' => 'Api', 'middleware' => 'auth'], function () {
    //资产管理模块
    Route::group(['prefix' => 'asset'], function () {
        Route::get('export', 'AssetController@batchExport'); //批量导出所有固定资产
        Route::get('{id}/export', 'AssetController@export'); //导出单据
    });


});

Route::group(['prefix' => 'api', 'namespace' => 'Api', 'middleware' => 'auth'], function () {

    //通用接口
    //Route::post('util/batch-delete/{model}', 'UtilController@batchDelete'); //批量删除
    //Route::delete('util/batch-delete/{model}', 'UtilController@batchDelete');//批量删除
    //Route::post('util/check/{model}', 'UtilController@check'); //验证
    Route::post('image/update', 'AdminController@image'); //异步上传图片
    Route::post('file/update', 'AdminController@file'); //异步上传附件
    Route::put('user/settings', 'UserController@settings'); //用户设置
    Route::get('/', 'IndexController@index'); //首页统计

    //基础信息接口
    Route::get('user/menu', 'UserController@menu'); //获取用户菜单
    Route::get('user/all', 'UserController@all'); //获取所有用户
    Route::get('category/{serial}', 'CategoryController@serial'); //获取分类列表
    Route::get('role/all', 'RoleController@all'); //获取所有角色

    //资产管理模块
    Route::group(['prefix' => 'asset'], function () {
        //Route::put('import', 'AssetController@import'); //导入固定资产的数据
        //Route::get('generate', 'AssetController@generate'); //批量导出所有的单据
        //Route::get('{id}/qrcode', 'AssetController@qrcode'); //生成二维码
        //Route::get('export', 'AssetController@batchExport'); //批量导出所有固定资产
       // Route::get('{id}/export', 'AssetController@export'); //导出单据
        Route::post('batch-delete', 'AssetController@batchDelete'); //批量删除
    });
    Route::resource('asset', 'AssetController', ['only' => [ 'index', 'store', 'update', 'destroy','edit']]);

    //单据管理模块
    Route::group(['prefix' => 'invoice'], function () {
        //Route::get('', 'InvoiceController@index');
        //Route::get('{id}/export', 'InvoiceController@export');
    });

    //藏品管理
    Route::group(['prefix' => 'collection'], function () {

        //农具管理模块
        Route::group(['prefix' => 'farm'], function () {
            Route::get('{id}/image', 'FarmController@showImage');
            Route::post('{id}/image', 'FarmController@saveImage');
            Route::get('{id}/relate', 'FarmController@relate');
            Route::delete('/{farm_id}/image/{id}', 'FarmController@deleteImage');

            Route::post('import', 'FarmController@import'); //导入数据
            Route::post('batch-delete', 'FarmController@batchDelete');

            Route::get('', 'FarmController@index');
            Route::post('', 'FarmController@store');
            Route::get('{id}/edit', 'FarmController@edit');
            Route::put('{id}', 'FarmController@update');
            Route::get('{id}', 'FarmController@show');
            Route::delete('{id}', 'FarmController@destroy');
        });
        //Route::resource('farm', 'FarmController', ['only' => ['index', 'store', 'edit', 'update', 'show', 'destroy']]);

        //岩石模块
        Route::group(['prefix' => 'rock'], function () {

            Route::get('{id}/image', 'RockController@showImage');
            Route::post('{id}/image', 'RockController@saveImage');
            Route::get('{id}/relate', 'RockController@relate');
            Route::delete('/{rock_id}/image/{id}', 'RockController@deleteImage');

            Route::post('import', 'RockController@import');
            Route::post('batch-delete', 'RockController@batchDelete');
        });
        Route::resource('rock', 'RockController', ['only' => ['index', 'store', 'edit', 'update', 'show', 'destroy']]);

        //植物模块
        Route::group(['prefix' => 'plant'], function () {
            Route::get('{id}/image', 'PlantController@showImage');
            Route::post('{id}/image', 'PlantController@saveImage');
            Route::get('{id}/relate', 'PlantController@relate');
            Route::delete('{plant_id}/image/{id}', 'PlantController@deleteImage');

            Route::post('import', 'PlantController@import');
            Route::post('batch-delete', 'PlantController@batchDelete');
        });
        Route::resource('plant', 'PlantController', ['only' => ['index', 'store', 'edit', 'update', 'show', 'destroy']]);

        //土壤模块
        Route::group(['prefix' => 'soil'], function () {
            Route::get('{id}/image', 'SoilController@showImage');
            Route::post('{id}/image', 'SoilController@saveImage');
            Route::get('{id}/relate', 'SoilController@relate');
            Route::delete('{soil_id}/image/{id}', 'soilController@deleteImage');

            Route::post('import', 'SoilController@import');
            Route::post('batch-delete', 'SoilController@batchDelete');

        });
        Route::resource('soil', 'SoilController', ['only' => ['index', 'store', 'edit', 'update', 'show', 'destroy']]);

        Route::get('soil/{soil_id}/soil-big/{id}/image', 'SoilBigController@showImage');
        Route::post('soil/{soil_id}/soil-big/{id}/image', 'SoilBigController@saveImage');
        Route::delete('soil/{soil_id}/soil-big/{soilBig_id}/image/{id}', 'soilBigController@deleteImage');
        Route::resource('soil/{soil_id}/soil-big', 'SoilBigController', ['only' => ['index', 'store', 'edit', 'update', 'destroy']]);

        Route::get('soil/{soil_id}/soil-small/{id}/image', 'SoilSmallController@showImage');
        Route::post('soil/{soil_id}/soil-small/{id}/image', 'SoilSmallController@saveImage');
        Route::delete('soil/{soil_id}/soil-small/{soilSmall_id}/image/{id}', 'soilSmallController@deleteImage');
        Route::resource('soil/{soil_id}/soil-small', 'SoilSmallController', ['only' => ['index', 'store', 'edit', 'update', 'destroy']]);


        //植物模块
        Route::group(['prefix' => 'animal'], function () {
            Route::get('{id}/image', 'AnimalController@showImage');
            Route::post('{id}/image', 'AnimalController@saveImage');
            Route::get('{id}/relate', 'AnimalController@relate');
            Route::delete('{animal_id}/image/{id}', 'AnimalController@deleteImage');

            Route::post('import', 'AnimalController@import');
            Route::post('batch-delete', 'AnimalController@batchDelete');
        });
        Route::resource('animal', 'AnimalController', ['only' => ['index', 'store', 'edit', 'update', 'show', 'destroy']]);


    });


    //用户管理模块
    Route::group(['prefix' => 'user'], function () {
        Route::post('batch-delete', 'UserController@batchDelete'); //批量删除
        Route::post('check', 'UserController@check'); //验证
    });
    Route::resource('user', 'UserController', ['only' => ['index', 'store', 'show', 'edit', 'update', 'destroy']]);


    //操作日志模块
    Route::group(['prefix' => 'alog'], function () {
        Route::get('all-module', 'AlogController@allModule');
        Route::get('', 'AlogController@index');
    });
});
