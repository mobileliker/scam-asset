<?php

/**
 * @version 2.0
 * @author： wuzhihui
 * @date： 2017/7/3
 * @description:
 * （1）添加批量删除和验证的方法；（2017/7/3）
 *
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/1
 * @description:
 * （1）添加批量删除的日志事件；（2017/12/1）
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Http\Request;
use ReflectionClass;
use DB;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $model = ''; //控制器对应的model类

    /**
     * 批量删除函数
     * @param Request $request ids数组
     * @return \Illuminate\Http\JsonResponse 批量删除的结果
     */
    protected function batchDelete(Request $request)
    {
        $class = new ReflectionClass($this->model);
        $model = $class->newInstanceArgs();

        $ids = $request->input('ids');

        DB::beginTransaction();
        $res = $model->destroy($ids);
        if ($res == count($ids)) {
            DB::commit();

            $models = explode('\\', $this->model);
            $eventModelName = 'App\\Events\\' . $models[count($models) - 1] . 'Event';
            $eventClass = new ReflectionClass($eventModelName);
            //\Log::info($eventModelName);
            event($eventClass->newInstance('batchDelete', $request->getClientIp(), $ids));

            return response()->json([
                'res' => 'true'
            ]);
        } else {
            DB::rollBack();
            return response()->json([
                'res' => 'false'
            ]);
        }
    }


    /**
     * 验证某一字段的值是否重复
     * @param Request $request
     * @param $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function check(Request $request)
    {
        $class = new ReflectionClass($this->model);
        $model = $class->newInstanceArgs();

        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $res = $model->where($field, $value);
        if ($id != null && $id != "") {
            $res = $res->where('id', '!=', $id);
        }
        $res = $res->first();
        if ($res != null) return response()->json([
            'res' => 'false'
        ]);
        else return response()->json([
            'res' => 'true'
        ]);
    }
}
