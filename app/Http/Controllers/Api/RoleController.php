<?php

/**
 * @version : 2.0
 * @author: wuzhihui
 * @date: 2017/6/19
 * @description:
 * （1）添加获取所有角色；
 * （2）添加权限控制；（2017/7/5）
 *
 * @version: 2.0.2
 * @author: wuzhihui
 * @date: 2017/12/14
 * @description:
 * （1）更改权限控制；（2017/12/14）
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Role;

class RoleController extends Controller
{
    public function __construct()
    {
//        $this->middleware('ability:UserMethod|Method-user-Role,true')->only('all');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function index()
//    {
//        //
//    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function create()
//    {
//        //
//    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
//    public function store(Request $request)
//    {
//        //
//    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function show($id)
//    {
//        //
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function edit($id)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function update(Request $request, $id)
//    {
//        //
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
//    public function destroy($id)
//    {
//        //
//    }

    /**
     * 获取所有角色
     * @link /api/role/all
     * @param 无
     * @return 所有角色数组
     */
    public function all()
    {
        $roles = Role::select('id as value', 'display_name as label')->get();
        return response()->json($roles);
    }


}
