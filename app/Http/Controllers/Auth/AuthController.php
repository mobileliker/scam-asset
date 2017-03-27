<?php

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
