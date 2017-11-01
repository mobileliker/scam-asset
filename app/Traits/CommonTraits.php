<?php
/**
 * @version 1.0
 * @author： linminghuan wuzhihui
 * @date： 2017/8/16
 * @description:
 * （1）添加改变状态、批量删除和验证的方法；（2017/8/16）
 * （2）添加文件上传方法；（2017/8/17）
 * （3）修改文件上传方法；（2017/8/29）
 * （4）添加更通用的方法；（2017/8/29 wuzhihui)
 * （5）添加一个保存默认文件存储目录字段，并按日期分文件夹存储文件；（2017/8/31 linminghuan）
 * （6）删除commonUpload的冗余代码,修改错误；（2017/8/31 linminghuan）
 * （7）去除多余的函数；（2017/9/6 wuzhihui）
 */
namespace App\Traits;
use Illuminate\Http\Request;
use App\Events\UtilEvent;
use App\Alog;
use ReflectionClass;
use DB;
use Log;
use Auth;

trait CommonTraits
{
    //获取Model的类名
    private function getModelName()
    {
        if (isset($this->model)) {
            return $this->model;
        } else {
            $constrollerName = (new ReflectionClass(self::class))->getShortName();
            $modelName = 'App\\' . substr($constrollerName, 0, strlen('Controller') * -1);
            return $modelName;
        }
    }

    protected function check(Request $request, ...$ids)
    {
        //Log::info('CommonTraits::check2');
        //Log::info($ids);
        //Log::info($this->prefixs);
        $modelName = $this->getModelName();
        //Log::info($modelName);
        $class = new ReflectionClass($modelName);
        $model = $class->newInstanceArgs();
        $id = $request->id;
        $field = $request->field;
        $value = $request->value;
        $res = $model->where($field, $value);
        if ($id != null && $id != "") {
            $res = $res->where('id', '!=', $id);
        }
        //添加额外前缀
        if (isset($this->prefixs)) {
            foreach ($this->prefixs as $key => $prefix) {
                $res = $res->where($prefix, '=', $ids[$key]);
            }
        }
        //数据范围
        if(isset($this->dataRange)){
            if($this->dataRange == 'company') {
                $res = $res->where('company_id', '=', Auth::user()->company_id);
            }else if($this->dataRange == 'user') {
                $res = $res->where('user_id', '=', Auth::user()->id);
            }
        }
        $res = $res->first();
        if ($res != null) return response()->json([
            'res' => 'false'
        ]);
        else return response()->json([
            'res' => 'true'
        ]);
        //return response()->json([
        //    'res' => 'false'
        //]);
    }
    protected function batchDelete(Request $request, ...$pids)
    {
        $class = new ReflectionClass($this->getModelName());
        $model = $class->newInstanceArgs();
        $ids = $request->input('ids');
        DB::beginTransaction();
        //TODO 暂时注释掉，带解决批量删除时无法加入提交的问题
//        if (isset($this->prefixs)) {
//            foreach ($this->prefixs as $key => $prefix) {
//                $model = $model->where($prefix, '=', $pids[$key]);
//            }
//        }
        //数据范围
//        if(isset($this->dataRange)){
//            if($this->dataRange == 'company') {
//                $model = $model->where('company_id', '=', Auth::user()->company_id);
//            }else if($this->dataRange == 'user') {
//                $model = $model->where('user_id', '=', Auth::user()->id);
//            }
//        }
        $res = $model->destroy($ids);
        if ($res == count($ids)) {
            DB::commit();
//            $ids_str = '';
//            foreach($ids as $key => $id){
//                if($key != 0) $ids_str = $ids_str . ' , ';
//                $ids_str = $ids_str . $id;
//            }
//            Alog::log($module , Alog::OPERATE_BATCHDELETE, '批量删除id为【' . $ids_str . '】成功', $request->getClientIp());
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
    public function status(Request $request, ...$pids)
    {
        //TODO 添加日记记录
        $class = new ReflectionClass($this->getModelName());//建立这个类的反射类
        $model = $class->newInstanceArgs();//相当于实例化类
        if (isset($this->prefixs)) {
            foreach ($this->prefixs as $key => $prefix) {
                $model = $model->where($prefix, '=', $pids[$key]);
            }
        }
        //数据范围
        if(isset($this->dataRange)){
            if($this->dataRange == 'company') {
                $model = $model->where('company_id', '=', Auth::user()->company_id);
            }else if($this->dataRange == 'user') {
                $model = $model->where('user_id', '=', Auth::user()->id);
            }
        }
        $model = $model->findOrFail($pids[count($pids) - 1]);
        $model->status = 1 - $model->status;
        if ($model->save()) {
            return response()->json([
                'res' => $model->status
            ]);
        } else {
            abort(500);
        }
    }
}