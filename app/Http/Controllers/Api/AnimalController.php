<?php

/**
 * 动物管理控制器
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/30
 * @description :
 * (1) 基本功能；(2017/11/30)
 * (2) 添加日志记录；(2017/12/1)
 * (3) 格式化代码：(2017/12/1)
 * (4) 新增权限管理的内容；(2017/12/14)
 * (5) 更改权限控制；(2017/12/14)
 *
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/4/10
 * @description :
 * (1) 修改了导入功能；(2018/4/10)
 * (2) 新增了门属性；(2018/4/10)
 * (3) 新增南海海洋标本的导入支持；(2018/5/7)
 * (4) 修改拍摄清单功能的错误；(2018/5/7)
 * (5) 修改导入功能以适应新的excel;(2019/5/5)
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use IQuery;
use Log;
use App\Animal;
use Excel;
use Auth;
use App\CollectionImage;
use App\Events\AnimalEvent;
use App\Alog;

class AnimalController extends Controller
{
    protected $model = Animal::class;

    public function __construct()
    {
        $this->middleware('ability:Animal|Method-Collection-Animal-Import,true')->only('import');
        $this->middleware('ability:Animal|Method-Collection-Animal-Index,true')->only('index');
        $this->middleware('ability:Animal|Method-Collection-Animal-Store,true')->only('store');
        $this->middleware('ability:Animal|Method-Collection-Animal-Show,true')->only('show');
        $this->middleware('ability:Animal|Method-Collection-Animal-Edit,true')->only('edit');
        $this->middleware('ability:Animal|Method-Collection-Animal-Update,true')->only('update');
        $this->middleware('ability:Animal|Method-Collection-Animal-Destroy,true')->only('destroy');
        $this->middleware('ability:Animal|Method-Collection-Animal-BatchDelete,true')->only('batchDelete');
        $this->middleware('ability:Animal|Method-Collection-Animal-ShowImage,true')->only('showImage');
        $this->middleware('ability:Animal|Method-Collection-Animal-SaveImage,true')->only('saveImage');
        $this->middleware('ability:Animal|Method-Collection-Animal-DeleteImage,true')->only('deleteImage');
        $this->middleware('ability:Animal|Method-Collection-Animal-Relate,true')->only('relate');
        $this->middleware('ability:Animal|Method-Collection-Animal-CameraList,true')->only('cameraList');
    }

    /**
     * 新增和编辑保存的处理函数
     * @param Request $request
     * @param int $id
     * @return Animal|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function storeOrUpdate(Request $request, $id = -1)
    {
        $this->validate($request, [
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'storage' => 'nullable|string|max:255',
            'number' => 'nullable|integer|min:1',
            'size' => 'nullable|string|max:255',

            'phylum' => 'nullable|string|max:255',
            'clazz' => 'nullable|string|max:255',
            'order' => 'nullable|string|max:255',
            'family' => 'nullable|string|max:255',
            'genus' => 'nullable|string|max:255',
            'latin' => 'nullable|string|max:255',

            'level_1989' => 'nullable|string|max:255',
            'level_2015' => 'nullable|string|max:255',
            'level_CITES' => 'nullable|string|max:255',

            'description' => 'nullable|string|max:2000',
            'range' => 'nullable|string|max:255',
            'habitat' => 'nullable|string|max:255',
            'batch' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'memo' => 'nullable|string|max:2000',

            'keeper_id' => 'required|integer|exists:users,id,deleted_at,NULL',
            'asset_id' => 'nullable|exists:assets,serial,deleted_at,NULL'
        ]);

        if ($id == -1) {
            $obj = new Animal;
        } else {
            $obj = Animal::findOrFail($id);
        }

        $obj->setRawAttributes($request->only(['serial', 'category', 'input_date', 'name', 'storage', 'size', 'number', 'phylum', 'clazz', 'order', 'family', 'genus', 'latin', 'level_1989', 'level_2015', 'level_CITES', 'description', 'range', 'habitat', 'batch', 'source', 'memo', 'keeper_id', 'asset_id']));
        $obj->user_id = Auth::id();
        if ($id != -1) $obj->id = $id;

        if ($obj->save()) {
            event(new AnimalEvent(Alog::getOperate($id), $request->getClientIp(), $obj)); //添加日志记录
            return $obj;
        } else {
            abort(500, '保存失败');
        }
    }

    /**
     * 导入Excel文件功能
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

            for($i = 0; $i < 2; $i++) {
                $sheet = $reader->getSheet($i);
                $sheet_array = $sheet->toArray();
                foreach ($sheet_array as $row => $cells) {
                    if ($row == 0) continue; //忽略标题行和表头
                    if ($cells[5] == '') continue; //编号不存在则忽略
                    if($cells[3] == '') continue; //入库日期不存在则忽略

                    $index = $cells[0]; //总序号
                    $batch = $cells[1]; //批次
                    $batchIndex = $cells[2]; //批次序号
                    $input_date = $cells[3]; //入账日期
                    $name = $cells[4]; //藏品名称
                    $serial = $cells[5]; //藏品编号
                    $latin = $cells[6]; //拉丁名
                    $phylum = $cells[7]; //门
                    $clazz = $cells[8]; //纲
                    $order = $cells[9]; //目
                    $family = $cells[10]; //科
                    $genus = $cells[11]; //属
                    $level_1989 = $cells[12]; //保护等级
                    //$level_2015 = $cells[X]; //暂时去除了
                    $level_CITES = $cells[13]; //CIETS2017
                    $range = $cells[14]; //分布范围
                    $habitat = $cells[15]; //生境
                    $description = $cells[16]; //描述
                    $size_length = $cells[17]; //长
                    $size_width = $cells[18]; //宽
                    $size_height = $cells[19]; //高
                    $size = $size_length . ' * ' . $size_width . ' * ' . $size_height; //尺寸

                    $category = $cells[23]; //分类
                    $storage = $cells[26]; //保存地点

                    $source = $cells[24]; //来源
                    if(isset($cells[34])) $memo = $cells[34]; //备注
                    else $memo = null;

                    $obj = Animal::where('serial', '=', $serial)->first();
                    if ($obj == null) {
                        $obj = new Animal;
                        $obj->serial = $serial;
                    } else if ($request->type == 'ignore') contiue;

                    $obj->category = $category;
                    if($obj->category == null) $obj->category = "";
                    $obj->input_date = $input_date;
                    $obj->name = $name;
                    $obj->storage = $storage;
                    $obj->size = $size;
                    $obj->phylum = $phylum;
                    $obj->clazz = $clazz;
                    $obj->order = $order;
                    $obj->family = $family;
                    $obj->genus = $genus;
                    $obj->latin = $latin;
                    $obj->level_1989 = $level_1989;
                    //$obj->level_2015 = $level_2015;
                    $obj->level_CITES = $level_CITES;
                    $obj->description = $description;
                    $obj->range = $range;
                    $obj->habitat = $habitat;
                    $obj->batch = $batch;
                    $obj->source = $source;
                    $obj->memo = $memo;

                    $obj->keeper_id = $user->id;
                    $obj->user_id = $user->id;

                    $obj->save();
                    event(new AnimalEvent('import', $request->getClientIp(), $obj)); //添加导入日记记录
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
        $lists = Animal::leftJoin('users as keepers', 'animals.keeper_id', '=', 'keepers.id')
            ->leftJoin('users', 'animals.user_id', '=', 'users.id')
            ->select('animals.id', 'animals.input_date', 'animals.category', 'animals.name', 'animals.latin', 'animals.serial', 'animals.source', 'animals.keeper_id', 'keepers.name as keeper', 'animals.user_id', 'users.name as user');

        if ($request->input_date_start != null && $request->input_date_start != '') {
            $lists = $lists->where('animals.input_date', '>=', $request->input_date_start)->where('animals.input_date', '<=', $request->input_date_end);
        }

        if ($request->keeper_id != null && $request->keeper_id != '') {
            $lists = $lists->where('animals.keeper_id', '=', $request->keeper_id);
        }

        if ($request->user_id != null && $request->user_id != '') {
            $lists = $lists->where('animals.user_id', '=', $request->user_id);
        }

        if ($request->is_asset != null && $request->is_asset != '') {
            if ($request->is_asset == 0) $lists = $lists->whereNull('animals.asset_id');
            else $lists = $lists->whereNotNull('animals.asset_id');
        }

        if ($request->category != null && $request->category != '') {
            $lists = $lists->where('animals.category', '=', $request->category);
        }


        $order_params = [
            'id' => 'animals.id',
            'input_date' => 'animals.input_date',
            'category' => 'animals.category',
            'name' => 'animals.name',
            'latin' => 'animals.latin',
            'serial' => 'animals.serial',
            'source' => 'animals.source',
            'keeper' => 'keepers.name',
            'user' => 'users.name',
        ];

        $text_params = [
            'name' => 'animals.name',
            'latin' => 'animals.latin',
            'serial' => 'animals.serial',
        ];

        $lists = IQuery::ofDefault($lists, $request, $order_params, $text_params, 'animals');

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
        return Animal::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Animal::findOrFail($id);
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
        $obj = Animal::findOrFail($id);
        if ($obj->delete()) {
            event(new AnimalEvent('destroy', $request->getClientIp(), $obj));
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
        return Animal::findOrFail($id)->images()->select('id', 'thumb as url', 'path', 'cover', 'updated_at as time')->get();
    }

    /**
     * 保存一张图片
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImage(Request $request, $id)
    {
        $animal = Animal::findOrFail($id);

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
            $collectionImage->collectible_type = Animal::class;
            $collectionImage->collectible_id = $animal->id;
            if ($collectionImage->save()) {
                //Log::info($path);
                $collectionImage->collectible;
                event(new AnimalEvent('saveImage', $request->getClientIp(), $collectionImage)); //添加日记事件

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
    public function deleteImage(Request $request, $animal_id, $id)
    {
        $image = Animal::findOrFail($animal_id)->images()->where('id', '=', $id)->firstOrFail();

        if ($image->delete()) {
            $image->collectible;
            event(new AnimalEvent('deleteImage', $request->getClientIp(), $image)); //添加日记事件
            return response()->json([
                'res' => true,
            ]);
        } else {
            abort(500, '删除失败');
        }
    }

    /**
     * 相关动物
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function relate(Request $request, $id)
    {
        $lists = Animal::leftJoin('users as keepers', 'animals.keeper_id', '=', 'keepers.id')
            ->leftJoin('users', 'animals.user_id', '=', 'users.id')
            ->select('animals.id', 'animals.input_date', 'animals.category', 'animals.name', 'animals.serial', 'animals.latin', 'animals.source', 'animals.keeper_id', 'keepers.name as keeper', 'animals.user_id', 'users.name as user')
            ->where('animals.id', '!=', $id);

        if ($request->query_text != null && $request->query_text != '') {
            $lists = $lists->where('animals.name', 'like', '%' . $request->query_text . '%');
        }

        $lists = $lists->orderBy('id', 'desc')->take(50)->get();

        return $lists;
    }


//生成拍摄清单函数
    public function cameraList()
    {
        $post_time = Date('YmdHis');
        $filePath = resource_path('assets/template/camera-list.xls');
        $distPath = storage_path('excel/exports/' . $post_time . '.xls');
        copy($filePath, $distPath);

        Excel::load($distPath, function ($reader) {
            $lists = Animal::leftJoin('collection_images', function ($join) {
                $join->on('animals.id', '=', 'collection_images.collectible_id')->whereNull('collection_images.deleted_at')->where('collectible_type', '=', Animal::class);
            })->whereNull('collection_images.id')->select('animals.category', 'animals.name', 'animals.serial', 'animals.number'/*, 'animals.storage'*/)->get();

            $sheet = $reader->getActiveSheet();

            $post_date = Date('Y-m-d');
            $sheet->setCellValue('G2', $post_date);

            foreach ($lists as $index => $obj) {
                $sheet->setCellValue('A' . ($index + 4), ($index + 1));
                $sheet->setCellValue('B' . ($index + 4), $obj->category);
                $sheet->setCellValue('C' . ($index + 4), $obj->name);
                $sheet->setCellValue('D' . ($index + 4), $obj->number);
                $sheet->setCellValue('E' . ($index + 4), $obj->serial);
                $sheet->setCellValue('F' . ($index + 4), $obj->storage);
            }
        })->export('xls');
    }
}
