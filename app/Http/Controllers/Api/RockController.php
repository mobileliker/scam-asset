<?php

/**
 * 岩石管理控制器
 * @version : 2.0
 * @author : wuzhihui
 * @date : 2017/10/18
 * @description :
 * (1) 完成基本功能；（2017/10/18）
 */

namespace App\Http\Controllers\Api;

use App\Rock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use IQuery;
use Auth;

class RockController extends Controller
{
    protected $model = Rock::class;

    public function storeOrUpdate(Request $request, $id = -1)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|integer|min:0',
            'ename' => 'nullable|string|max:255',
            'input_date' => 'required|date',
            'serial' => 'required|string|max:255',
            'classification' => 'nullable|integer|min:0',
            'feature' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'keeper_id' => 'required|integer|exists:users,id,deleted_at,NULL',
            'asset_id' => 'nullable|exists:assets,serial,deleted_at,NULL',
            'memo' => 'nullable|string|max:2000',
        ]);

        if ($id == -1) {
            $rock = new Rock;
        } else {
            $rock = Rock::findOrFail($id);
        }

        $rock->setRawAttributes($request->only(['name', 'category_id', 'ename', 'input_date', 'serial', 'classification', 'feature', 'origin', 'description', 'keeper_id', 'asset_id', 'memo']));
        $rock->user_id = Auth::id();

        if($id != -1) $rock->id = $id;

        if($rock->save()) {
            return $rock;
        } else {
            abort(500, '保存失败');
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lists = Rock::leftJoin('users as keepers', 'rocks.keeper_id', '=', 'keepers.id')
            ->leftJoin('users', 'rocks.user_id', '=', 'users.id')
            ->leftJoin('rock_categories', 'rock_categories.id', '=', 'rocks.category_id')
            ->select('rocks.id', 'rocks.input_date', 'rocks.category_id', 'rock_categories.name as category_name', 'rocks.name', 'rocks.ename', 'rocks.serial', 'rocks.keeper_id', 'keepers.name as keeper', 'rocks.user_id', 'users.name as user');



        if ($request->keeper_id != null && $request->keeper_id != '') {
            $lists = $lists->where('rocks.keeper_id', '=', $request->keeper_id);
        }

        if ($request->user_id != null && $request->user_id != '') {
            $lists = $lists->where('rocks.user_id', '=', $request->user_id);
        }

        if ($request->is_asset != null && $request->is_asset != '') {
            if ($request->is_asset == 0) $lists = $lists->whereNull('rocks.asset_id');
            else $lists = $lists->whereNotNull('rocks.asset_id');
        }

        $order_params = [
            'id' => 'rocks.id',
            'input_date' => 'rocks.input_date',
            'category_name' => 'rock_categories.name',
            'name' => 'rocks.name',
            'ename' => 'rocks.ename',
            'serial' => 'rocks.serial',
            'keeper' => 'keepers.name',
            'user' => 'users.name',
        ];

        $text_params = [
            'name' => 'rocks.name',
            'serial' => 'rocks.serial',
        ];

        $lists = IQuery::ofDefault($lists, $request, $order_params, $text_params, 'rocks');

        return $lists;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

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
        return Rock::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Rock::findOrFail($id);
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
        $rock = Rock::findOrFail($id);
        if($rock->delete()) {
            return $rock;
        } else {
            abort(500, '删除失败');
        }
    }

    public function batchDelete(Request $request)
    {
        return parent::batchDelete($request);
    }
}
