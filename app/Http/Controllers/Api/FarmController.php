<?php

/**
 * 农具管理的控制类
 * @version: 2.0
 * @author: wuzhihui
 * @date: 2017/7/10
 * @description:
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use IQuery;
use App\Farm;
use Log;
use Auth;

class FarmController extends Controller
{
    protected $model = Farm::class;

    public function __construct()
    {
        $this->middleware('ability:Farm|Method-Collection-Farm-Index,true')->only('index');
        $this->middleware('ability:Farm|Method-Collection-Farm-Store,true')->only('store');
        $this->middleware('ability:Farm|Method-Collection-Farm-Edit,true')->only('edit');
        $this->middleware('ability:Farm|Method-Collection-Farm-Update,true')->only('update');
        $this->middleware('ability:Farm|Method-Collection-Farm-destroy,true')->only('destroy');
        $this->middleware('ability:Farm|Method-Collection-Farm-BatchDelete,true')->only('batchDelete');
    }


    public function storeOrUpdate(Request $request, $id = -1)
    {
        $this->validate($request, [
            'input_date' => 'required|date',
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'number' => 'required|integer|min:0',
            'source' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'size' => 'nullable|string|max:255',
            'serial' => 'required|string|max:255',
            'memo' => 'nullable|string|max:2000',
            'display' => 'required|string|max:255',
            'keeper_id' => 'required|integer|exists:users,id,deleted_at,NULL',
            'asset_id' => 'nullable|exists:assets,id,deleted_at,NULL'
        ]);
//        Log::info($request->all());
//        return $request->all();

        if($id == -1){
            $farm = new Farm;
        }else{
            $farm = Farm::findOrFail($id);
        }

        $farm->setRawAttributes($request->only(['category', 'name', 'number', 'input_date', 'source', 'description', 'size', 'serial', 'memo', 'display', 'keeper_id', 'asset_id']));
        $farm->user_id = Auth::id();
        if($id != -1) $farm->id = $id;

        if($farm->save()){
            return $farm;
        }else{
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
        //Log::info($request->all());

        $lists = Farm::leftJoin('users as keepers', 'farms.keeper_id', '=', 'keepers.id')
            ->leftJoin('users', 'farms.user_id', '=', 'users.id')
            ->select('farms.id', 'farms.input_date', 'farms.category', 'farms.name', 'farms.serial', 'farms.source', 'farms.keeper_id', 'keepers.name as keeper', 'farms.user_id', 'users.name as user');

        if ($request->category != null && $request->category != '') {
            $lists = $lists->where('farms.category', '=', $request->category);
        }

        if ($request->keeper_id != null && $request->keeper_id != '') {
            $lists = $lists->where('farms.keeper_id', '=', $request->keeper_id);
        }

        if ($request->user_id != null && $request->user_id != '') {
            $lists = $lists->where('farms.user_id', '=', $request->user_id);
        }

        if ($request->is_asset != null && $request->is_asset != '') {
            if ($request->is_asset == 0) $lists = $lists->whereNull('farms.asset_id');
            else $lists = $lists->whereNotNull('farms.asset_id');
        }

        if ($request->input_date_start != null && $request->input_date_start != '') {
            $lists = $lists->where('farms.input_date', '>=', $request->input_date_start)->where('farms.input_date', '<=', $request->input_date_end);
        }

        $order_params = [
            'id' => 'farms.id',
            'input_date' => 'farms.input_date',
            'category' => 'farms.category',
            'name' => 'farms.name',
            'serial' => 'farms.serial',
            'source' => 'farms.source',
            'keeper' => 'keepers.name',
            'user' => 'users.name',
        ];

        $text_params = [
            'name' => 'farms.name',
            'serial' => 'farms.serial',
            'source' => 'farms.source',
        ];

        $lists = IQuery::ofDefault($lists, $request, $order_params, $text_params, 'farms');

        return $lists;
    }

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->storeOrUpdate($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Farm::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->storeOrUpdate($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $farm = Farm::findOrFail($id);
        if($farm->delete()){
            return $farm;
        }else{
            abort(500, '删除失败');
        }
    }

    public function batchDelete(Request $request)
    {
        return parent::batchDelete($request);
    }
}
