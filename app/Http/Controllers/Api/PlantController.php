<?php

/**
 * 植物管理控制器
 * @version : 2.0
 * @author : wuzhihui
 * @date : 2017/11/22
 * @description :
 * (1)基本功能； （2017/11/22）
 * (2)修改storeOrUpdate函数中latin写成ename的错误；（2017/11/29）
 *
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/1
 * @description :
 * (1)添加日志记录；（2017/12/1）
 * (2)index函数添加最后编辑时间;(2017/12/11)
 * (3)新增权限控制；（2017/12/14）
 * （4）更改权限控制；（2017/12/14）
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
use App\Events\PlantEvent;
use App\Alog;

class PlantController extends Controller
{
    protected $model = Plant::class;

    public function __construct()
    {
        $this->middleware('ability:Plant|Method-Collection-Plant-Import,true')->only('import');
        $this->middleware('ability:Plant|Method-Collection-Plant-Index,true')->only('index');
        $this->middleware('ability:Plant|Method-Collection-Plant-Store,true')->only('store');
        $this->middleware('ability:Plant|Method-Collection-Plant-Show,true')->only('show');
        $this->middleware('ability:Plant|Method-Collection-Plant-Edit,true')->only('edit');
        $this->middleware('ability:Plant|Method-Collection-Plant-Update,true')->only('update');
        $this->middleware('ability:Plant|Method-Collection-Plant-Destroy,true')->only('destroy');
        $this->middleware('ability:Plant|Method-Collection-Plant-BatchDelete,true')->only('batchDelete');
        $this->middleware('ability:Plant|Method-Collection-Plant-ShowImage,true')->only('showImage');
        $this->middleware('ability:Plant|Method-Collection-Plant-SaveImage,true')->only('saveImage');
        $this->middleware('ability:Plant|Method-Collection-Plant-DeleteImage,true')->only('deleteImage');
        $this->middleware('ability:Plant|Method-Collection-Plant-Relate,true')->only('relate');
    }

    /**
     * 新增保存和更新保存
     * @param Request $request
     * @param int $id
     * @return Plant|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
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
            event(new PlantEvent(Alog::getOperate($id), $request->getClientIp(), $obj)); //添加日志记录
            return $obj;
        } else {
            abort(500, '保存失败');
        }
    }

    /**
     * 导入Excel
     * @param Request $request
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            'file' => 'required',
            'type' => 'required|in:ignore,cover',
        ]);

        $user = Auth::user();

        $path = $request->file;

        Excel::load($path, function ($reader) use ($user, $request) {
            $categories = array();
            $categories[0] = 'G01木制品';
            $categories[1] = 'G02茎段';
            $categories[2] = 'G03卡片标本';
            $categories[3] = 'G04林业相关物品';

            for ($i = 0; $i < 4; $i++) {
                $sheet = $reader->getSheet($i);
                $sheet_array = $sheet->toArray();
                foreach ($sheet_array as $row => $cells) {
                    if ($row == 0 || $row == 1) continue; //忽略标题行和表头
                    if ($cells[2] == '') continue; //编号不存在则忽略

                    if ($i == 0 || $i == 3) {
                        //$index = $cells[0];
                        $input_date = $cells[1];
                        $serial = $cells[2];
                        $name = $cells[3];
                        $type = $cells[4];
                        //$number = $cells[5];
                        $size = $cells[6];
                        $origin = $cells[7];
                        $source = $cells[8];
                        $storage = $cells[9];
                        $description = $cells[10];
                        $memo = $cells[11];

                        $family = null;
                        $genus = null;
                        $latin = null;
                    } else {
                        //$index = $cells[0];
                        $input_date = $cells[1];
                        $serial = $cells[2];
                        $family = $cells[3];
                        $genus = $cells[4];
                        $name = $cells[5];
                        $latin = $cells[6];
                        //$number = $cells[7];
                        $size = $cells[8];
                        $type = $cells[9];
                        $origin = $cells[10];
                        $source = $cells[11];
                        $storage = $cells[12];
                        $description = $cells[13];
                        $memo = $cells[14];
                    }

                    $serials = explode('-', $serial);
                    if (count($serials) > 1) {
                        $prefix = substr(trim($serials[0]), 0, 2);
                        $serial = intval(substr(trim($serials[0]), 2));
                        $serial_end = intval(substr(trim($serials[1]), 2));
                    } else {
                        $prefix = substr(trim($serial), 0, 2);
                        $serial = intval(substr(trim($serial), 2));
                        $serial_end = $serial;
                    }

                    while ($serial <= $serial_end) {
                        $obj = Plant::where('serial', '=', $prefix . $serial)->first();
                        if ($obj == null) $obj = new Plant;
                        else if ($request->type == 'ignore') contiue;

                        $input_dates = explode('-', $input_date);
                        $obj->input_date = '20' . $input_dates[2] . '-' . $input_dates[0] . '-' . $input_dates[1];

                        $obj->serial = $prefix . $serial;
                        $obj->family = $family;
                        $obj->genus = $genus;
                        $obj->name = $name;
                        $obj->latin = $latin;
                        $obj->size = $size;
                        $obj->type = $type;
                        $obj->origin = $origin;
                        $obj->source = $source;
                        $obj->storage = $storage;
                        $obj->description = $description;
                        $obj->memo = $memo;

                        $obj->keeper_id = $user->id;
                        $obj->user_id = $user->id;
                        $obj->category = $categories[$i];

                        $obj->save();
                        event(new PlantEvent('import', $request->getClientIp(), $obj)); //添加导入日记记录

                        $serial = $serial + 1;
                    }
                }
            }
        });

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lists = Plant::leftJoin('users as keepers', 'plants.keeper_id', '=', 'keepers.id')
            ->leftJoin('users', 'plants.user_id', '=', 'users.id')
            ->select('plants.id', 'plants.input_date', 'plants.category', 'plants.name', 'plants.latin', 'plants.serial', 'plants.source', 'plants.keeper_id', 'keepers.name as keeper', 'plants.user_id', 'users.name as user', 'plants.updated_at');

        if ($request->input_date_start != null && $request->input_date_start != '') {
            $lists = $lists->where('plants.input_date', '>=', $request->input_date_start)->where('plants.input_date', '<=', $request->input_date_end);
        }

        if ($request->keeper_id != null && $request->keeper_id != '') {
            $lists = $lists->where('plants.keeper_id', '=', $request->keeper_id);
        }

        if ($request->user_id != null && $request->user_id != '') {
            $lists = $lists->where('plants.user_id', '=', $request->user_id);
        }

        if ($request->is_asset != null && $request->is_asset != '') {
            if ($request->is_asset == 0) $lists = $lists->whereNull('plants.asset_id');
            else $lists = $lists->whereNotNull('plants.asset_id');
        }

        if ($request->category != null && $request->category != '') {
            $lists = $lists->where('plants.category', '=', $request->category);
        }


        $order_params = [
            'id' => 'plants.id',
            'input_date' => 'plants.input_date',
            'category' => 'plants.category',
            'name' => 'plants.name',
            'latin' => 'plants.latin',
            'serial' => 'plants.serial',
            'source' => 'plants.source',
            'keeper' => 'keepers.name',
            'user' => 'users.name',
            'updated_at' => 'plants.updated_at',
        ];

        $text_params = [
            'name' => 'plants.name',
            'latin' => 'plants.latin',
            'serial' => 'plants.serial',
        ];

        $lists = IQuery::ofDefault($lists, $request, $order_params, $text_params, 'plants');

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
        return Plant::findOrFail($id);
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
    public function destroy(Request $request, $id)
    {
        $obj = Plant::findOrFail($id);
        if ($obj->delete()) {
            event(new PlantEvent('destroy', $request->getClientIp(), $obj));
            return $obj;
        } else {
            abort(500, '删除失败');
        }
    }

    /**
     * 批量删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchDelete(Request $request)
    {
        return parent::batchDelete($request);
    }

    /**
     * 显示一张图片
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function showImage(Request $request, $id)
    {
        return Plant::findOrFail($id)->images()->select('id', 'thumb as url', 'path', 'cover', 'updated_at as time')->get();
    }

    /**
     * 保存一张图片
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImage(Request $request, $id)
    {
        $plant = Plant::findOrFail($id);

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
            $collectionImage->collectible_type = Plant::class;
            $collectionImage->collectible_id = $plant->id;
            if ($collectionImage->save()) {
                //Log::info($path);
                $collectionImage->collectible;
                event(new PlantEvent('saveImage', $request->getClientIp(), $collectionImage)); //添加日记事件
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
    public function deleteImage(Request $request, $plant_id, $id)
    {
        $image = Plant::findOrFail($plant_id)->images()->where('id', '=', $id)->firstOrFail();

        if ($image->delete()) {
            $image->collectible;
            event(new PlantEvent('deleteImage', $request->getClientIp(), $image)); //添加日记事件
            return response()->json([
                'res' => true,
            ]);
        } else {
            abort(500, '删除失败');
        }
    }

    /**
     * 相关
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function relate(Request $request, $id)
    {
        $lists = Plant::leftJoin('users as keepers', 'plants.keeper_id', '=', 'keepers.id')
            ->leftJoin('users', 'plants.user_id', '=', 'users.id')
            ->select('plants.id', 'plants.input_date', 'plants.category', 'plants.name', 'plants.serial', 'plants.latin', 'plants.source', 'plants.keeper_id', 'keepers.name as keeper', 'plants.user_id', 'users.name as user')
            ->where('plants.id', '!=', $id);

        if ($request->query_text != null && $request->query_text != '') {
            $lists = $lists->where('plants.name', 'like', '%' . $request->query_text . '%');
        }

        $lists = $lists->orderBy('id', 'desc')->take(50)->get();

        return $lists;
    }
}
