<?php

/**
 * 动物管理控制器
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/30
 * @description :
 * (1)基本功能；（2017/11/30）
 * (2)添加日志记录；（2017/12/1）
 * (3)格式化代码：（2017/12/1）
 * (4)新增权限管理的内容；（2017/12/14）
 * （5）更改权限控制；（2017/12/14）
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

        $obj->setRawAttributes($request->only(['serial', 'category', 'input_date', 'name', 'storage', 'size', 'number', 'clazz', 'order', 'family', 'genus', 'latin', 'level_1989', 'level_2015', 'level_CITES', 'description', 'range', 'habitat', 'batch', 'source', 'memo', 'keeper_id', 'asset_id']));
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

            $sheet = $reader->getSheet(0);
            $sheet_array = $sheet->toArray();
            foreach ($sheet_array as $row => $cells) {
                if ($row == 0 || $row == 1) continue; //忽略标题行和表头
                if ($cells[3] == '') continue; //编号不存在则忽略

                $index = $cells[0];
                $input_date = $cells[1];
                $name = $cells[2];
                $serial = $cells[3];
                $category = $cells[4];
                $size = $cells[5];
                $storage = $cells[6];
                $clazz = $cells[7];
                $order = $cells[8];
                $family = $cells[9];
                $genus = $cells[10];
                $latin = $cells[11];
                $level_1989 = $cells[12];
                $level_2015 = $cells[13];
                $level_CITES = $cells[14];
                $description = $cells[15];
                $range = $cells[16];
                $habitat = $cells[17];
                $batch = $cells[18];
                $source = $cells[19];
                $memo = $cells[20];

                $obj = Animal::where('serial', '=', $serial)->first();
                if ($obj == null) {
                    $obj = new Animal;
                    $obj->serial = $serial;
                } else if ($request->type == 'ignore') contiue;

                $obj->category = $category;
                $obj->input_date = $input_date;
                $obj->name = $name;
                $obj->storage = $storage;
                $obj->size = $size;
                $obj->clazz = $clazz;
                $obj->order = $order;
                $obj->family = $family;
                $obj->genus = $genus;
                $obj->latin = $latin;
                $obj->level_1989 = $level_1989;
                $obj->level_2015 = $level_2015;
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
}
