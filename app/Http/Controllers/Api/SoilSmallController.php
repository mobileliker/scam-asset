<?php

/**
 * 土壤纸盒管理控制器
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/29
 * @description :
 * (1)基本功能；（2017/11/29）
 * (2)添加日志记录；（2017/12/1）
 */

namespace App\Http\Controllers\Api;

use App\Events\SoilEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\SoilSmall;
use IQuery;
use App\Soil;
use App\CollectionImage;
use App\Events\SoilSmallEvent;

class SoilSmallController extends Controller
{
    public function storeOrUpdate(Request $request, $soil_id, $id = -1)
    {
        $this->validate($request, [
            'serial' => 'nullable|string|max:255',
            'storage' => 'nullable|string|max:255'
        ]);


        if ($id == -1) {
            Soil::findOrFail($soil_id);
            $soilSmall = new SoilSmall;
            $soilSmall->soil_id = $soil_id;
        } else {
            $soilSmall = SoilSmall::where('soil_id', '=', $soil_id)->findOrFail($id);
        }

        $soilSmall->serial = $request->serial;
        $soilSmall->storage = $request->storage;

        if ($soilSmall->save()) {
            event(new SoilEvent(Alog::getOperate($id), $request->getClientIp(), $soilSmall)); //添加日志记录
            return $soilSmall;
        } else {
            abort(500, '保存失败');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $soil_id)
    {
        $lists = SoilSmall::where('soil_id', '=', $soil_id)->orderBy('id', 'desc')->get();

        // $order_params = [
        //   'id' => 'soil_Smalls.id',
        //   'serial' => 'soil_Smalls.serial',
        //   'storage' => 'soil_Smalls.storage',
        // ];
        //
        // $text_params = [
        //   'serial' => 'soil_Smalls.serial',
        // ];
        //
        // $lists = IQuery::ofDefault($lists, $request, $order_params, $text_params, 'soil_Smalls');
        //
        return $lists;
    }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $soil_id)
    {
        return $this->storeOrUpdate($request, $soil_id);
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($soil_id, $id)
    {
        $soilSmall = SoilSmall::where('soil_id', '=', $soil_id)->findOrFail($id);
        return $soilSmall;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $soil_id, $id)
    {
        return $this->storeOrUpdate($request, $soil_id, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $soil_id, $id)
    {
        $soilSmall = SoilSmall::where('soil_id', '=', $soil_id)->findOrFail($id);
        if ($soilSmall->delete()) {
            event(new SoilSmallEvent('destroy', $request->getClientIp(), $soilSmall));
            return $soilSmall;
        } else {
            abort(500, '删除失败');
        }
    }


    /**
     * 显示一张图片
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function showImage(Request $request, $soil_id, $id)
    {
        return SoilSmall::where('soil_id', '=', $soil_id)->findOrFail($id)->images()->select('id', 'thumb as url', 'path', 'cover', 'updated_at as time')->get();
    }

    /**
     * 保存一张图片
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImage(Request $request, $soil_id, $id)
    {
        $soil = SoilSmall::where('soil_id', '=', $soil_id)->findOrFail($id);

        $file = $request->file('file');
        if ($file != null && $file->isValid()) {
            //$mimeType = $file -> getMimeType();
            $entension = $file->getClientOriginalExtension();
            //$pic_name = md5(date('ymdhis') . $file->getClientOriginalName()) . '.' . $entension;
            $pic_name = date('Ymdhis') . substr(md5(date('ymdhis') . $file->getClientOriginalName()), 0, 4) . '.' . $entension;
            $path = $file->move('storage/upload/image', $pic_name);
            $path = studly_case(str_replace("\\", "/", ucfirst($path)));

            $collectionImage = new CollectionImage;
            $collectionImage->path = $path;
            $collectionImage->thumb = $path;
            $collectionImage->collectible_type = SoilSmall::class;
            $collectionImage->collectible_id = $soil->id;
            if ($collectionImage->save()) {
                //Log::info($path);
                $collectionImage->collectible;
                event(new SoilSmallEvent('saveImage', $request->getClientIp(), $collectionImage)); //添加日记事件
                return response()->json([
                    'name' => $pic_name,
                    'url' => '' . $path,
                    'id' => $collectionImage->id
                ]);
                //return url($path);
                //return response()->file($path);
            } else {
                abort(500, '保存失败');
            }


        } else {
            abort(500, '上传失败');
        }
    }


    /**
     * 删除一张图片
     * @param Request $request
     * @param $rock_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage(Request $request, $soil_id, $soilSmall_id, $id)
    {
        $image = SoilSmall::where('soil_id', '=', $soil_id)->findOrFail($soilSmall_id)->images()->where('id', '=', $id)->firstOrFail();

        if ($image->delete()) {
            event(new SoilSmallEvent('deleteImage', $request->getClientIp(), $image)); //添加日记事件
            return response()->json([
                'res' => true,
            ]);
        } else {
            abort(500, '删除失败');
        }
    }
}
