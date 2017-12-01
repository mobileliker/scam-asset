<?php

/**
 * @version 2.0
 * @author : wuzhihui
 * @date: 2017/7/4
 * @description:
 * （1）将所有功能接口转移到API.php；（2017/7/4）
 * （2）添加农具管理的接口；（2017/7/10）
 * （3）添加上传附件的接口；（2017/7/14）
 * （4）添加农具图片管理的相关接口（2017/9/15）
 * (5) 修复delete无法使用的错误；（2017/9/30）
 * (6) 整理api.php，使用resource+only替代逐条撰写；(2017/9/30)
 * （7）添加岩石管理的相关接口；（2017/10/18）
 *
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/27
 * @description :
 * (1)添加土壤相关的接口；（2017/11/27）
 * (2)添加动物相关的接口；（2017/11/30）
 * （3）新增了获取所有日志模块的接口：（2017/12/1）
 */

//use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['namespace' => 'Api', 'middleware' => 'auth:api'], function () {

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
        });
        Route::resource('farm', 'FarmController', ['only' => ['index', 'store', 'edit', 'update', 'show', 'destroy']]);

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
