<?php

/*
 * @version: 1.0 后台配置管理控制器
 * @author: wuzhihui
 * @date: 2016/9/30
 * @description:
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Redirect;

use App\Info;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return "admin.info.index";

        //$infos = Info::orderBy('created_at', 'desc')->paginate(10);
        //return view(config('app.theme', 'zxck').'.admin.info.index')->withInfos($infos);

        $request->flash();

        $infos = Info::whereRaw('1 = 1');

        //文本查询
        $query_text=$request->input('query_text');
        if($query_text != null && $request != ''){
            $texts=  explode(' ', $query_text);
            foreach($texts as $text)
            {
                $infos = $infos->where('key', 'like', '%'.$text.'%');
            }

        }

        $infos = $infos->paginate(10);
        return view(config('app.theme', 'zxck').'.admin.info.index')->withInfos($infos);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return 'admin.info.create';
        return view(config('app.theme', 'zxck').'.admin.info.create');
    }


    public function storeOrUpdate(Request $request,$id = 0)
    {
        $this->validate($request, [
            'key' => 'required|string|max:255',
            'value' => 'required|string|max:1000',
        ]);


        $key = $request->input('key');
        $value = $request->input('value');

        if($id == 0){
            $info = new Info;
        }else{
            $info = Info::find($id);
        }

        $info->key = $key;
        $info->value = $value;

        if($info->save()){
            return Redirect::to('admin/info');
        }else{
            return Redirect::back()->withErrors('保存失败');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->storeOrUpdate($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $info = Info::find($id);
        return view(config('app.theme', 'zxck').'.admin.info.edit')->withInfo($info);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->storeOrUpdate($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $info = Info::find($id);
        if($info->delete()){
            return Redirect::back();
        }else{
            return Redirect::back()->withErrors('删除失败');
        }
    }

    public function batch_delete(Request $request)
    {

        $ids = $request->input('ids');
        if(Info::destroy($ids) > 0){
            return 'true';
        }else{
            return 'false';
        }
    }

    
    /*public function check(Request $request)
    {
        $id = $request->input('id');
        $field = $request->input('field');
        $value = $request->input('value');
        if($id == null){
            $category = Info::where($field , $value)->first();
        }else{
            $category=Info::where($field, $value)->where('id', '!=', $id)->first();
        }
        if($category != null) return "false";
        else return "true";
    }*/
}
