<?php

/**
 * 植物管理控制器
 * @version : 2.0
 * @author : wuzhihui
 * @date : 2017/11/22
 * @description :
 * (1)基本功能； （2017/11/22）
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use IQuery;
use Log;
use App\Plant;
use Excel;
use Auth;
use App\CollectionImage;

class PlantController extends Controller
{
    protected $model = Plant::class;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeOrUpdate(Request $request, $id = -1)
    {
        $this->validate($request, [
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'latin' => 'nullable|string|max:255',
            'family' => 'nullable|string|max:255',
            'genus' => 'nullable|string|max:255',
            'number' => 'required|integer|min:0',
            'origin' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'size' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'storage' => 'nullable|string|max:255',
            'serial' => 'required|string|max:255',
            'memo' => 'nullable|string|max:2000',
            'keeper_id' => 'required|integer|exists:users,id,deleted_at,NULL',
            'asset_id' => 'nullable|exists:assets,serial,deleted_at,NULL'
        ]);

        if ($id == -1) {
            $obj = new Plant;
        } else {
            $obj = Plant::findOrFail($id);
        }

        $obj->setRawAttributes($request->only(['input_date', 'category', 'name', 'latin', 'family', 'genus', 'number', 'origin', 'source', 'description', 'size', 'type', 'serial', 'memo', 'keeper_id', 'asset_id', 'storage']));
        $obj->user_id = Auth::id();
        if ($id != -1) $obj->id = $id;

        if ($obj->save()) {
            return $obj;
        } else {
            abort(500, '保存失败');
        }
    }

    public function index()
    {
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
        return Plant::findOrFail($id);
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
        //
    }
}
