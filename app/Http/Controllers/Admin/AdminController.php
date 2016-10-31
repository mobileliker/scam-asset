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

class AdminController extends Controller
{
    //
    public function index()
    {
        return Redirect::to('admin/asset');
        //return view(config('app.theme').'.admin.index');
    }
}
