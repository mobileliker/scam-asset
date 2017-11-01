<?php

/*
 * @version: 1.0 后台工具类控制器
 * @author: wuzhihui
 * @date: 2016/9/30
 * @description:
 * （1）将批量删除和验证函数设置为已过时（2017/7/3）
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use ReflectionClass;
use DB, Log;

class UtilController extends Controller
{
    //用于验证某一字段的值是否重复
    /**
     * @deprecated
     */
    public function check(Request $request, $model)
    {
        $table_name = str_plural(str_replace("-","_",ucfirst($model)));
        $id = $request->input('id');
        $field = $request->input('field');
        $value = $request->input('value');
        $res = DB::table($table_name)->whereNull('deleted_at')->where($field, $value);
        if($id != null && $id != ""){
            $res = $res->where('id', '!=', $id);
        }
        $res = $res->first();
        if($res != null) return response()->json([
            'res' => 'false'
        ]);
        else return response()->json([
            'res' => 'true'
        ]);
    }

    //批量删除
    /**
     * @deprecated
     */
    public function batchDelete(Request $request, $model)
    {
        $ids = $request->input('ids');

        $model = studly_case(str_replace("-","_",ucfirst($model)));
        $class = new ReflectionClass('App\\'.ucfirst($model));//建立这个类的反射类
        $model  = $class->newInstanceArgs();//相当于实例化类

        //DB::transaction(function () use ($model, $ids){
        DB::beginTransaction();
        $res = $model->destroy($ids);
        if($res == count($ids)){
            DB::commit();
            return response()->json([
                'res' => 'true'
            ]);
        }else{
            DB::rollBack();
            return response()->json([
                'res' => 'false'
            ]);
        }
        //});
    }

    public function status($model, $id)
    {
        $model = studly_case(str_replace("-","_",ucfirst($model)));
        $class = new ReflectionClass('App\\'.ucfirst($model));//建立这个类的反射类
        $model  = $class->newInstanceArgs();//相当于实例化类

        $model = $model->findOrFail($id);
        $model->status = 1 - $model->status;
        if($model->save()){
            return response()->json([
                'res' => $model->status
            ]);
        }else{
            abort(500);
        }
    }
}