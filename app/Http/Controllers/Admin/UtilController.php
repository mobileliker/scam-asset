<?php

/*
 * @version: 1.0 后台工具类控制器
 * @author: wuzhihui
 * @date: 2016/9/30
 * @description:
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use DB;

class UtilController extends Controller
{
    //用于验证某一字段的值是否重复
    public function check(Request $request, $model)
    {
    	$table_name = config('theme', 'zxck_').str_plural($model);
    	$id = $request->input('id');
        $field = $request->input('field');
        $value = $request->input('value');
        if($id == null){
        	$res = DB::table($table_name)->where($field, $value)->first();
        }else{
            $res = DB::table($table_name)->where($field, $value)->where('id', '!=', $id)->first();
        }
        if($res != null) return "false";
        else return "true";
    }
}
