<?php

/**
 * 林业资源管理控制器
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/3/27
 * @description:
 * (1)基本功能；（2018/3/27）
 * (2)修改拍摄清单的Xls路径；（2018/4/8）
 * (3)新增G05导入支持；
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use IQuery;
use Log;
use App\Forestry;
use Excel;
use Auth;
use App\CollectionImage;
use App\Events\ForestryEvent;
use App\Alog;

class ForestryController extends Controller
{
    protected $model = Forestry::class;

    public function __construct()
    {
        $this->middleware('ability:Forestry|Method-Collection-Forestry-Import,true')->only('import');
        $this->middleware('ability:Forestry|Method-Collection-Forestry-Index,true')->only('index');
        $this->middleware('ability:Forestry|Method-Collection-Forestry-Store,true')->only('store');
        $this->middleware('ability:Forestry|Method-Collection-Forestry-Show,true')->only('show');
        $this->middleware('ability:Forestry|Method-Collection-Forestry-Edit,true')->only('edit');
        $this->middleware('ability:Forestry|Method-Collection-Forestry-Update,true')->only('update');
        $this->middleware('ability:Forestry|Method-Collection-Forestry-Destroy,true')->only('destroy');
        $this->middleware('ability:Forestry|Method-Collection-Forestry-BatchDelete,true')->only('batchDelete');
        $this->middleware('ability:Forestry|Method-Collection-Forestry-ShowImage,true')->only('showImage');
        $this->middleware('ability:Forestry|Method-Collection-Forestry-SaveImage,true')->only('saveImage');
        $this->middleware('ability:Forestry|Method-Collection-Forestry-DeleteImage,true')->only('deleteImage');
        $this->middleware('ability:Forestry|Method-Collection-Forestry-Relate,true')->only('relate');
        $this->middleware('ability:Forestry|Method-Collection-Forestry-CameraList,true')->only('cameraList');
    }

    /**
     * 新增保存和更新保存
     * @param Request $request
     * @param int $id
     * @return Forestry|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
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
            $obj = new Forestry;
        } else {
            $obj = Forestry::findOrFail($id);
        }

        $obj->setRawAttributes($request->only(['input_date', 'category', 'name', 'latin', 'family', 'genus', 'number', 'origin', 'source', 'description', 'size', 'type', 'serial', 'memo', 'keeper_id', 'asset_id', 'storage']));
        $obj->user_id = Auth::id();
        if ($id != -1) $obj->id = $id;

        if ($obj->save()) {
            event(new ForestryEvent(Alog::getOperate($id), $request->getClientIp(), $obj)); //添加日志记录
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
            $categories[4] = 'G05古木';

            for ($i = 0; $i < 5; $i++) {
                $sheet = $reader->getSheet($i);
                $sheet_array = $sheet->toArray();
                foreach ($sheet_array as $row => $cells) {
                    if ($row == 0 || $row == 1) continue; //忽略标题行和表头
                    if ($cells[2] == '') continue; //编号不存在则忽略

                    if ($i == 0 || $i == 3 || $i == 4) {
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
                        $obj = Forestry::where('serial', '=', $prefix . $serial)->first();
                        if ($obj == null) $obj = new Forestry;
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
                        event(new ForestryEvent('import', $request->getClientIp(), $obj)); //添加导入日记记录

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
        $lists = Forestry::leftJoin('users as keepers', 'forestries.keeper_id', '=', 'keepers.id')
            ->leftJoin('users', 'forestries.user_id', '=', 'users.id')
            ->select('forestries.id', 'forestries.input_date', 'forestries.category', 'forestries.name', 'forestries.latin', 'forestries.serial', 'forestries.source', 'forestries.keeper_id', 'keepers.name as keeper', 'forestries.user_id', 'users.name as user', 'forestries.updated_at');

        if ($request->input_date_start != null && $request->input_date_start != '') {
            $lists = $lists->where('forestries.input_date', '>=', $request->input_date_start)->where('forestries.input_date', '<=', $request->input_date_end);
        }

        if ($request->keeper_id != null && $request->keeper_id != '') {
            $lists = $lists->where('forestries.keeper_id', '=', $request->keeper_id);
        }

        if ($request->user_id != null && $request->user_id != '') {
            $lists = $lists->where('forestries.user_id', '=', $request->user_id);
        }

        if ($request->is_asset != null && $request->is_asset != '') {
            if ($request->is_asset == 0) $lists = $lists->whereNull('forestries.asset_id');
            else $lists = $lists->whereNotNull('forestries.asset_id');
        }

        if ($request->category != null && $request->category != '') {
            $lists = $lists->where('forestries.category', '=', $request->category);
        }


        $order_params = [
            'id' => 'forestries.id',
            'input_date' => 'forestries.input_date',
            'category' => 'forestries.category',
            'name' => 'forestries.name',
            'latin' => 'forestries.latin',
            'serial' => 'forestries.serial',
            'source' => 'forestries.source',
            'keeper' => 'keepers.name',
            'user' => 'users.name',
            'updated_at' => 'forestries.updated_at',
        ];

        $text_params = [
            'name' => 'forestries.name',
            'latin' => 'forestries.latin',
            'serial' => 'forestries.serial',
        ];

        $lists = IQuery::ofDefault($lists, $request, $order_params, $text_params, 'forestries');

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
        return Forestry::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Forestry::findOrFail($id);
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
        $obj = Forestry::findOrFail($id);
        if ($obj->delete()) {
            event(new ForestryEvent('destroy', $request->getClientIp(), $obj));
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
        return Forestry::findOrFail($id)->images()->select('id', 'thumb as url', 'path', 'cover', 'updated_at as time')->get();
    }

    /**
     * 保存一张图片
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImage(Request $request, $id)
    {
        $Forestry = Forestry::findOrFail($id);

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
            $collectionImage->collectible_type = Forestry::class;
            $collectionImage->collectible_id = $Forestry->id;
            if ($collectionImage->save()) {
                //Log::info($path);
                $collectionImage->collectible;
                event(new ForestryEvent('saveImage', $request->getClientIp(), $collectionImage)); //添加日记事件
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
    public function deleteImage(Request $request, $Forestry_id, $id)
    {
        $image = Forestry::findOrFail($Forestry_id)->images()->where('id', '=', $id)->firstOrFail();

        if ($image->delete()) {
            $image->collectible;
            event(new ForestryEvent('deleteImage', $request->getClientIp(), $image)); //添加日记事件
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
        $lists = Forestry::leftJoin('users as keepers', 'forestries.keeper_id', '=', 'keepers.id')
            ->leftJoin('users', 'forestries.user_id', '=', 'users.id')
            ->select('forestries.id', 'forestries.input_date', 'forestries.category', 'forestries.name', 'forestries.serial', 'forestries.latin', 'forestries.source', 'forestries.keeper_id', 'keepers.name as keeper', 'forestries.user_id', 'users.name as user')
            ->where('forestries.id', '!=', $id);

        if ($request->query_text != null && $request->query_text != '') {
            $lists = $lists->where('forestries.name', 'like', '%' . $request->query_text . '%');
        }

        $lists = $lists->orderBy('id', 'desc')->take(50)->get();

        return $lists;
    }

    /**
     * 拍摄清单
     * @param Request $request
     */
    public function cameraList(Request $request)
    {
        $post_time = Date('YmdHis');
        $filePath = resource_path('assets/template/camera-list.xls');
        $distPath = storage_path('excel/exports/camera-list/forestry/' . $post_time . '.xls');
        copy($filePath, $distPath);

        Excel::load($distPath, function ($reader) {
            $lists = Forestry::leftJoin('collection_images', function ($join) {
                $join->on('forestries.id', '=', 'collection_images.collectible_id')->whereNull('collection_images.deleted_at')->where('collectible_type', '=', Forestry::class);
            })->whereNull('collection_images.id')->select('forestries.name', 'forestries.serial', 'forestries.storage', 'forestries.category')->get();

            $sheet = $reader->getActiveSheet();

            $post_date = Date('Y-m-d');
            $sheet->setCellValue('G2', $post_date);

            foreach ($lists as $index => $obj) {
                $sheet->setCellValue('A' . ($index + 4), ($index + 1));
                $sheet->setCellValue('B' . ($index + 4), $obj->category);
                $sheet->setCellValue('C' . ($index + 4), $obj->name);
                $sheet->setCellValue('D' . ($index + 4), '1');
                $sheet->setCellValue('E' . ($index + 4), $obj->serial);
                $sheet->setCellValue('F' . ($index + 4), $obj->storage);
            }

        })->export('xls');
    }
}
