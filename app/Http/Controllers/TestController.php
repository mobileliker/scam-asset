<?php

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
}
