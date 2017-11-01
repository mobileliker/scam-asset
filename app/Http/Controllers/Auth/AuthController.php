<?php

/**
 * @version 2.0
 * @author: wuzhihui
 * @date: 2017/7/3
 * @description:
 * 
 */

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AuthController extends Controller
{
    public function info(Request $request){
        $user = Auth::user();
        if($user != null){
            return $user;
        }else{
            abort(401);
        }
    }
}
