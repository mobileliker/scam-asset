<?php

/**
 * 土壤段面管理控制器
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/29
 * @description :
 * (1)基本功能；（2017/11/29）
 * (2)添加日志记录；（2017/12/1）
 * (3)添加权限控制；（2017/12/14）
 * （4）更改权限控制；（2017/12/14）
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\SoilBig;
use IQuery;
use App\Soil;
use App\CollectionImage;
use App\Events\SoilBigEvent;
use App\Alog;

class SoilBigController extends Controller
{
    protected $model = SoilBig::class;

    public function __construct()
    {
        $this->middleware('ability:SoilBig|Method-Collection-SoilBig-Import,true')->only('import');
        $this->middleware('ability:SoilBig|Method-Collection-SoilBig-Index,true')->only('index');
        $this->middleware('ability:SoilBig|Method-Collection-SoilBig-Store,true')->only('store');
        $this->middleware('ability:SoilBig|Method-Collection-SoilBig-Edit,true')->only('edit');
        $this->middleware('ability:SoilBig|Method-Collection-SoilBig-Update,true')->only('update');
        $this->middleware('ability:SoilBig|Method-Collection-SoilBig-Destroy,true')->only('destroy');
        $this->middleware('ability:SoilBig|Method-Collection-SoilBig-ShowImage,true')->only('showImage');
        $this->middleware('ability:SoilBig|Method-Collection-SoilBig-SaveImage,true')->only('saveImage');
        $this->middleware('ability:SoilBig|Method-Collection-SoilBig-DeleteImage,true')->only('deleteImage');

        //$this->middleware('ability:SoilBig|Method-Collection-SoilBig-Show,true')->only('show');
        //$this->middleware('ability:SoilBig|Method-Collection-SoilBig-BatchDelete,true')->only('batchDelete');
        //$this->middleware('ability:SoilBig|Method-Collection-SoilBig-Relate,true')->only('relate');
        //$this->middleware('ability:SoilBig|Method-Collection-SoilBig-CameraList,true')->only('cameraList');
    }

    /**
     * 新增保存和编辑保存
     * @param Request $request
     * @param $soil_id
     * @param int $id
     * @return SoilBig|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function storeOrUpdate(Request $request, $soil_id, $id = -1)
    {
        $this->validate($request, [
            'serial' => 'nullable|string|max:255',
            'storage' => 'nullable|string|max:255'
        ]);


        if ($id == -1) {
            Soil::findOrFail($soil_id);
            $soilBig = new SoilBig;
            $soilBig->soil_id = $soil_id;
        } else {
            $soilBig = SoilBig::where('soil_id', '=', $soil_id)->findOrFail($id);
        }

        $soilBig->serial = $request->serial;
        $soilBig->storage = $request->storage;

        if ($soilBig->save()) {
            event(new SoilBigEvent(Alog::getOperate($id), $request->getClientIp(), $soilBig)); //添加日志记录
            return $soilBig;
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
        $lists = SoilBig::where('soil_id', '=', $soil_id)->orderBy('id', 'desc')->get();

        // $order_params = [
        //   'id' => 'soil_bigs.id',
        //   'serial' => 'soil_bigs.serial',
        //   'storage' => 'soil_bigs.storage',
        // ];
        //
        // $text_params = [
        //   'serial' => 'soil_bigs.serial',
        // ];
        //
        // $lists = IQuery::ofDefault($lists, $request, $order_params, $text_params, 'soil_bigs');
        //
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $soil_id)
    {
        return $this->storeOrUpdate($request, $soil_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
        $soilBig = SoilBig::where('soil_id', '=', $soil_id)->findOrFail($id);
        return $soilBig;
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
        $soilBig = SoilBig::where('soil_id', '=', $soil_id)->findOrFail($id);
        if ($soilBig->delete()) {
            event(new SoilBigEvent('destroy', $request->getClientIp(), $soilBig));
            return $soilBig;
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
        return Soilbig::where('soil_id', '=', $soil_id)->findOrFail($id)->images()->select('id', 'thumb as url', 'path', 'cover', 'updated_at as time')->get();
    }

    /**
     * 保存一张图片
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImage(Request $request, $soil_id, $id)
    {
        $soil = SoilBig::where('soil_id', '=', $soil_id)->findOrFail($id);

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
            $collectionImage->collectible_type = SoilBig::class;
            $collectionImage->collectible_id = $soil->id;
            if ($collectionImage->save()) {
                //Log::info($path);
                $collectionImage->collectible;
                event(new SoilBigEvent('saveImage', $request->getClientIp(), $collectionImage)); //添加日记事件
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
    public function deleteImage(Request $request, $soil_id, $soilBig_id, $id)
    {
        $image = SoilBig::where('soil_id', '=', $soil_id)->findOrFail($soilBig_id)->images()->where('id', '=', $id)->firstOrFail();

        if ($image->delete()) {
            $image->collectible;
            event(new SoilBigEvent('deleteImage', $request->getClientIp(), $image)); //添加日记事件
            return response()->json([
                'res' => true,
            ]);
        } else {
            abort(500, '删除失败');
        }
    }
}
