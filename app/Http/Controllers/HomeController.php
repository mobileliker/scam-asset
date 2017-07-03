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
use Redirect;
use App\Asset;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin-vue');
    }

    public function search(Request $request)
    {
        $code = $request->input('c');
        $asset =  Asset::where('serial', '=', $code)->first();
        //dd($asset);
        return view(config('app.theme').'.home.asset.index')->withAsset($asset);
    }
}
