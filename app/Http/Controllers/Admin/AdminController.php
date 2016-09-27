<?php

namespace App\Http\Controllers\Admin;

/*
功能：后台首页的Controller类
作者：wuzhihui
时间：2016/8/16
*/


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    //
    public function index()
    {
        return view('zxck.admin.index');
    }
}
