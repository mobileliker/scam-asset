<?php

/*
 * @version: 1.0 后台管理首页
 * @author: wuzhihui
 * @date: 2016/9/30
 * @description:
 *
 * @version 2.0
 * @author: wuzhihui
 * @date: 2017/7/3
 * @description:
 * （1） 图片上传功能；
 * （2）添加权限控制；（2017/7/5）
 * （3）附件上传功能；（2107/7/14）
 * （4）修改图片上传错误返回为500错误；（2017/7/14）
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Redirect;
use Log;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('ability:Common|Method-Common-Image,true')->only('image');
        $this->middleware('ability:Common|Method-Common-File,true')->only('file');
    }

    /**
     * 图片上传功能
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|string
     */
    public function image(Request $request)
    {
        //Log::info('image test');
        $file = $request->file('file');
        if ($file != null && $file->isValid()) {
            //$mimeType = $file -> getMimeType();
            $entension = $file->getClientOriginalExtension();
            $pic_name = md5(date('ymdhis') . $file->getClientOriginalName()) . '.' . $entension;
            $path = $file->move('storage/upload/image/', $pic_name);
            $path = str_replace("\\", "/", ucfirst($path));
            //Log::info($path);
            return response()->json([
                'name' => $pic_name,
                'url' => '' . $path
            ]);
            //return url($path);
            //return response()->file($path);
        } else {
            abort(500, '上传失败');
        }
    }

    /**
     * 附件上传功能
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function file(Request $request)
    {
        $file = $request->file('file');
        if ($file != null && $file->isValid()) {
            $entension = $file->getClientOriginalExtension();
            $name = md5(date('ymdhis').$file->getClientOriginalName()) . '.' . $entension;
            $path = $file->move('storage/upload/attachment/', $name);
            $path = str_replace("\\", "/", ucfirst($path));
            return response()->json([
                'name' => $name,
                'url' => ''.$path,
            ]);
        }else{
            abort(500, '上传失败');
        }
    }
}
