<?php

/**
 * @version 2.0
 * @author: wuzhihui
 * @date: 2017/7/3
 * @description:
 * 
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use IQrcode;

class TestController extends Controller
{
    public function iqrcode()
    {
        //return "test iqrocde";
        $path = IQrcode::generate('http://www.baidu.com');
        return response()->file($path);
    }

    public function postSubmit()
    {
        return 'test postSubmit';
    }
}
