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

use ReflectionClass;
use DB;

class UtilController extends Controller
{
    //用于验证某一字段的值是否重复
    public function check(Request $request, $model)
    {
    	$table_name = str_plural($model);
    	$id = $request->input('id');
        $field = $request->input('field');
        $value = $request->input('value');
        $res = DB::table($table_name)->whereNull('deleted_at')->where($field, $value);
        if($id != null){
        	$res = $res->where('id', '!=', $id);
        }
        $res = $res->first();
        if($res != null) return "false";
        else return "true";
    }

    //批量删除
    public function batch_delete(Request $request, $model)
    {

        $ids = $request->input('ids');
        $class = new ReflectionClass('App\\'.ucfirst($model));//建立这个类的反射类  
        $model  = $class->newInstanceArgs();//相当于实例化类
        if($model->destroy($ids) > 0){
            return 'true';
        }else{
            return 'false';
        }
    }
}
