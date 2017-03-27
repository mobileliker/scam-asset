<?php

/*
 * @version: 1.0 后台管理首页
 * @author: wuzhihui
 * @date: 2016/9/30
 * @description:
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Redirect;
use Log;

class AdminController extends Controller
{
    //
    public function index()
    {
        return Redirect::to('admin/vue');
        //return Redirect::to('admin/asset');
        //return view(config('app.theme').'.admin.index');
    }

    public function vue()
    {
        return view('admin-vue');
    }

    public function image(Request $request){
        //Log::info('image test');
        $file = $request->file('file');
        if($file != null && $file -> isValid()){
            //$mimeType = $file -> getMimeType();
            $entension = $file -> getClientOriginalExtension();
            $pic_name = md5(date('ymdhis').$file->getClientOriginalName()).'.'.$entension;
            $path = $file -> move('storage/upload/image/', $pic_name);
            $path = studly_case(str_replace("\\","/",ucfirst($path)));
            //Log::info($path);
            return response()->json([
                'name' => $pic_name,
                'url' => ''.$path
            ]);
            //return url($path);
            //return response()->file($path);
        }else{
            return "error";
        }
    }
}
