<?php

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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return view('home');
        return Redirect::to('admin');
    }

    public function search(Request $request)
    {
        $code = $request->input('c');
        $asset =  Asset::where('serial', '=', $code)->first();
        dd($asset);
    }
}
