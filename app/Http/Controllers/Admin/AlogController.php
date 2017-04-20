<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Alog;
use IQuery;

class AlogController extends Controller
{
    //
    public function index(Request $request)
    {
    	//return "admin.alog.index";
    	
    	//$alogs = Alog::paginate(10);
    	//return view(config('app.theme').'.admin.alog.index')->withAlogs($alogs);


        //$request->flash();

        $alogs = Alog::whereRaw('1 = 1');

        $alogs = $alogs->join('users', function($join) {
           $join->on('users.id', '=', 'alogs.user_id');
        })->select('alogs.*', 'users.name as user_name');

        //文本查询
        $query_text=$request->input('query_text');
        if($query_text != null && $request != ''){
            $texts=  explode(' ', $query_text);
            foreach($texts as $text)
            {
                $alogs = $alogs->where('content', 'like', '%'.$text.'%');
            }

        }

        $module = $request->input('module');
        if($module != null && $module != ''){
        	$alogs = $alogs->where('module', $module);
        }

        $operate = $request->input('operate');
        if($operate != null && $operate != ''){
        	$alogs = $alogs->where('operate', $operate);
        }

        $alogs = IQuery::ofOrder($alogs, $request);

        
        if($request->paginate != null && $request->paginate != '')
            $alogs = $alogs->paginate($request->paginate);
        else
            $alogs = $alogs->paginate(10);

        foreach($alogs as $alog){
            $alog->operate = Alog::OPERATE[$alog->operate];
        }

        if($request->ajax()) return $alogs;

        if($alogs == null || count($alogs) == 0){
            return view(config('app.theme').'.admin.alog.index')->withAlogs($alogs)->with('status', '查询结果为空');
        }else{
            return view(config('app.theme').'.admin.alog.index')->withAlogs($alogs); 
        }
    }
}
