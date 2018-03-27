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
 * (3)新增了获取所有日志模块的接口：（2017/12/1）
 * (4)临时注释掉API接口测试；（2017/12/4）
 * (5)恢复被注释的API接口，并修改为post和get方法代替delete和put方法；（2017/12/5）
 * (6)修改藏品管理部分的使用post和get方法代替delete和put方法；（2017/12/5）
 * (7)新增土壤标本的新的显示图片的功能；（2017/12/12）
 * (8)修改用户管理部分、用户设置的使用post和get方法代替delete和put方法；（2017/12/14）
 *
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/3/1
 * @description :
 * (1)添加附件模块的相关接口；（2018/3/1）
 * （2）添加林业资源管理模块的相关接口：（2018/3/27）
 *
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
    //Route::put('user/settings', 'UserController@settings'); //用户设置
    Route::post('user/settings', 'UserController@settings'); //用户设置
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
            //Route::put('{id}', 'FarmController@update');
            Route::get('{id}', 'FarmController@show');
            //Route::delete('{id}', 'FarmController@destroy');

            Route::post('{id}/update', 'FarmController@update');
            Route::get('{id}/delete', 'FarmController@destroy');
        });
        //Route::resource('farm', 'FarmController', ['only' => ['index', 'store', 'edit', 'update', 'show', 'destroy']]);

        //岩石模块
        Route::group(['prefix' => 'rock'], function () {

            Route::get('{id}/image', 'RockController@showImage');
            Route::post('{id}/image', 'RockController@saveImage');
            Route::get('{id}/relate', 'RockController@relate');
            //Route::delete('/{rock_id}/image/{id}', 'RockController@deleteImage');
            Route::get('/{rock_id}/image/{id}/delete', 'RockController@deleteImage');

            Route::post('import', 'RockController@import');
            Route::post('batch-delete', 'RockController@batchDelete');

            Route::get('', 'RockController@index');
            Route::post('', 'RockController@store');
            Route::get('{id}/edit', 'RockController@edit');
            //Route::put('{id}', 'RockController@update');
            Route::get('{id}', 'RockController@show');
            //Route::delete('{id}', 'RockController@destroy');

            Route::post('{id}/update', 'RockController@update');
            Route::get('{id}/delete', 'RockController@destroy');
        });
        //Route::resource('rock', 'RockController', ['only' => ['index', 'store', 'edit', 'update', 'show', 'destroy']]);

        //植物模块
        Route::group(['prefix' => 'plant'], function () {
            Route::get('{id}/image', 'PlantController@showImage');
            Route::post('{id}/image', 'PlantController@saveImage');
            Route::get('{id}/relate', 'PlantController@relate');
            //Route::delete('{plant_id}/image/{id}', 'PlantController@deleteImage');
            Route::get('{plant_id}/image/{id}/delete', 'PlantController@deleteImage');

            Route::post('import', 'PlantController@import');
            Route::post('batch-delete', 'PlantController@batchDelete');

            Route::get('', 'PlantController@index');
            Route::post('', 'PlantController@store');
            Route::get('{id}/edit', 'PlantController@edit');
            //Route::put('{id}', 'PlantController@update');
            Route::get('{id}', 'PlantController@show');
            //Route::delete('{id}', 'PlantController@destroy');

            Route::post('{id}/update', 'PlantController@update');
            Route::get('{id}/delete', 'PlantController@destroy');
        });
        //Route::resource('plant', 'PlantController', ['only' => ['index', 'store', 'edit', 'update', 'show', 'destroy']]);


        //植物模块
        Route::group(['prefix' => 'forestry'], function () {
            Route::get('{id}/image', 'ForestryController@showImage');
            Route::post('{id}/image', 'ForestryController@saveImage');
            Route::get('{id}/relate', 'ForestryController@relate');
            //Route::delete('{plant_id}/image/{id}', 'PlantController@deleteImage');
            Route::get('{plant_id}/image/{id}/delete', 'ForestryController@deleteImage');

            Route::post('import', 'ForestryController@import');
            Route::post('batch-delete', 'ForestryController@batchDelete');

            Route::get('', 'ForestryController@index');
            Route::post('', 'ForestryController@store');
            Route::get('{id}/edit', 'ForestryController@edit');
            //Route::put('{id}', 'ForestryController@update');
            Route::get('{id}', 'ForestryController@show');
            //Route::delete('{id}', 'ForestryController@destroy');

            Route::post('{id}/update', 'ForestryController@update');
            Route::get('{id}/delete', 'ForestryController@destroy');
        });
        //Route::resource('plant', 'ForestryController', ['only' => ['index', 'store', 'edit', 'update', 'show', 'destroy']]);

        //土壤模块
        Route::group(['prefix' => 'soil'], function () {
            Route::get('{id}/image', 'SoilController@showImage');
            Route::get('{id}/image2', 'SoilController@showImage2'); //同时显示纸盒和段面标本的图片
            Route::post('{id}/image', 'SoilController@saveImage');
            Route::get('{id}/relate', 'SoilController@relate');
            //Route::delete('{soil_id}/image/{id}', 'soilController@deleteImage');
            Route::get('{soil_id}/image/{id}/delete', 'soilController@deleteImage');

            Route::post('import', 'SoilController@import');
            Route::post('batch-delete', 'SoilController@batchDelete');

            Route::get('', 'SoilController@index');
            Route::post('', 'SoilController@store');
            Route::get('{id}/edit', 'SoilController@edit');
            //Route::put('{id}', 'SoilController@update');
            Route::get('{id}', 'SoilController@show');
            //Route::delete('{id}', 'SoilController@destroy');

            Route::post('{id}/update', 'SoilController@update');
            Route::get('{id}/delete', 'SoilController@destroy');

        });
        //Route::resource('soil', 'SoilController', ['only' => ['index', 'store', 'edit', 'update', 'show', 'destroy']]);

        //土壤段面标本管理
        Route::get('soil/{soil_id}/soil-big/{id}/image', 'SoilBigController@showImage');
        Route::post('soil/{soil_id}/soil-big/{id}/image', 'SoilBigController@saveImage');
        //Route::delete('soil/{soil_id}/soil-big/{soilBig_id}/image/{id}', 'soilBigController@deleteImage');
        Route::delete('soil/{soil_id}/soil-big/{soilBig_id}/image/{id}/delete', 'soilBigController@deleteImage');

        //Route::resource('soil/{soil_id}/soil-big', 'SoilBigController', ['only' => ['index', 'store', 'edit', 'update', 'destroy']]);
        Route::get('soil/{soil_id}/soil-big', 'SoilBigController@index');
        Route::post('soil/{soil_id}/soil-big', 'SoilBigController@store');
        Route::get('soil/{soil_id}/soil-big/{id}/edit', 'SoilBigController@edit');
        //Route::put('soil/{soil_id}/soil-big/{id}', 'SoilBigController@update');
        Route::get('soil/{soil_id}/soil-big/{id}', 'SoilBigController@show');
        //Route::delete('soil/{soil_id}/soil-big/{id}', 'SoilBigController@destroy');

        Route::post('soil/{soil_id}/soil-big/{id}/update', 'SoilBigController@update');
        Route::get('soil/{soil_id}/soil-big/{id}/delete', 'SoilBigController@destroy');


        //土壤段面标本管理
        Route::get('soil/{soil_id}/soil-small/{id}/image', 'SoilSmallController@showImage');
        Route::post('soil/{soil_id}/soil-small/{id}/image', 'SoilSmallController@saveImage');
        //Route::delete('soil/{soil_id}/soil-small/{soilSmall_id}/image/{id}', 'soilSmallController@deleteImage');
        Route::get('soil/{soil_id}/soil-small/{soilSmall_id}/image/{id}/delete', 'soilSmallController@deleteImage');

        //Route::resource('soil/{soil_id}/soil-small', 'SoilSmallController', ['only' => ['index', 'store', 'edit', 'update', 'destroy']]);
        Route::get('soil/{soil_id}/soil-small', 'SoilSmallController@index');
        Route::post('soil/{soil_id}/soil-small', 'SoilSmallController@store');
        Route::get('soil/{soil_id}/soil-small/{id}/edit', 'SoilSmallController@edit');
        //Route::put('soil/{soil_id}/soil-small/{id}', 'SoilSmallController@update');
        Route::get('soil/{soil_id}/soil-small/{id}', 'SoilSmallController@show');
        //Route::delete('soil/{soil_id}/soil-small/{id}', 'SoilSmallController@destroy');

        Route::post('soil/{soil_id}/soil-small/{id}/update', 'SoilSmallController@update');
        Route::get('soil/{soil_id}/soil-small/{id}/delete', 'SoilSmallController@destroy');


        //动物模块
        Route::group(['prefix' => 'animal'], function () {
            Route::get('{id}/image', 'AnimalController@showImage');
            Route::post('{id}/image', 'AnimalController@saveImage');
            Route::get('{id}/relate', 'AnimalController@relate');
            Route::delete('{animal_id}/image/{id}', 'AnimalController@deleteImage');

            Route::post('import', 'AnimalController@import');
            Route::post('batch-delete', 'AnimalController@batchDelete');

            Route::get('', 'AnimalController@index');
            Route::post('', 'AnimalController@store');
            Route::get('{id}/edit', 'AnimalController@edit');
            //Route::put('{id}', 'AnimalController@update');
            Route::get('{id}', 'AnimalController@show');
            //Route::delete('{id}', 'AnimalController@destroy');

            Route::post('{id}/update', 'AnimalController@update');
            Route::get('{id}/delete', 'AnimalController@destroy');
        });
        //Route::resource('animal', 'AnimalController', ['only' => ['index', 'store', 'edit', 'update', 'show', 'destroy']]);


    });


    //用户管理模块
    Route::group(['prefix' => 'user'], function () {
        Route::post('batch-delete', 'UserController@batchDelete'); //批量删除
        Route::post('check', 'UserController@check'); //验证

        Route::get('', 'UserController@index');
        Route::post('', 'UserController@store');
        Route::get('{id}', 'UserController@show');
        Route::get('{id}/edit', 'UserController@edit');
        //Route::put('{id}', 'UserController@update');
        //Route::delete('{id}', 'UserController@destroy');

        Route::post('{id}/update', 'UserController@update');
        Route::get('{id}/delete', 'UserController@destroy');
    });
    Route::resource('user', 'UserController', ['only' => ['index', 'store', 'show', 'edit', 'update', 'destroy']]);


    //操作日志模块
    Route::group(['prefix' => 'alog'], function () {
        Route::get('all-module', 'AlogController@allModule');
        Route::get('', 'AlogController@index');
    });

    //附件模块
    Route::group(['prefix' => 'attachment'], function() {
        Route::post('batch-delete', 'AttachmentController@batchDelete'); //批量删除
        Route::get('', 'AttachmentController@index');
        Route::get('{id}/delete', 'AttachmentController@destroy');
    });
});
