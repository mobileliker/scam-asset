<?php
/**
 * @version : 2.0
 * @author: wuzhihui
 * @date: 2017/6/16
 * @description:
 * （1）添加权限控制的中间件；
 *
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/1
 * @description:
 * （1）添加了获取所有模块的函数；（2017/12/1）
 * （2）index函数新增user_id的查询；（2017/12/13）
 * （3）更改权限控制；（2017/12/14）
 */
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Alog;
use IQuery;

class AlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('ability:Alog|Index,true')->only('index');
    }
    
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

        if($request->user_id != null && $request->user_id != ''){
            $alogs = $alogs->where('user_id', '=', $request->user_id);
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

    /**
     * 获取所有的模块名称
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function allModule()
    {
        //TODO 将之改为缓存的方式，减少数据库访问次数
        return Alog::select('module as label', 'module as value')->distinct()->get();
    }
}
