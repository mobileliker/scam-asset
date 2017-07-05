<?php

/**
 * @version 2.0
 * @author : wuzhihui
 * @date: 2017/7/4
 * @description:
 * (1)将所有功能接口转移到API.php；（2017/7/4）
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
    Route::put('user/settings', 'UserController@settings'); //用户设置
    Route::get('/', 'Indexcontroller@index'); //首页统计

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

        Route::get('', 'AssetController@index');
        Route::post('', 'AssetController@store');
        Route::get('{id}/edit', 'AssetController@edit');
        Route::put('{id}', 'AssetController@update');
        Route::delete('{id}', 'AssetController@destroy');
    });

    //单据管理模块
    Route::group(['prefix' => 'invoice'], function () {
        //Route::get('', 'InvoiceController@index');
        //Route::get('{id}/export', 'InvoiceController@export');
    });

    //用户管理模块
    Route::group(['prefix' => 'user'], function () {
        Route::post('batch-delete', 'UserController@batchDelete'); //批量删除
        Route::post('check', 'UserController@check'); //验证
        Route::get('', 'UserController@index');
        Route::post('', 'UserController@store');
        Route::get('{id}', 'UserController@show');
        Route::get('{id}/edit', 'UserController@edit');
        Route::put('{id}', 'UserController@update');
        Route::delete('{id}', 'UserController@destroy');
    });


    //操作日志模块
    Route::group(['prefix' => 'alog'], function () {
        Route::get('', 'AlogController@index');
    });

});
