<?php

/**
 * 农具管理的控制类
 * @version: 2.0
 * @author: wuzhihui
 * @date: 2017/7/10
 * @description:
 * （1）完成农具管理的基本功能；（2017/7/10）
 * （2）农具导入功能；（2017/7/14）
 * （3）修改固定资产编号对应Serial字段；(2017/9/5)
 * （4）添加图片管理的相关接口；（2017/9/15）
 * （5）添加获取相关农具的接口； （2017/9/19）
 */

namespace App\Http\Controllers\Api;

use App\CollectionImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use IQuery;
use App\Farm;
use Log;
use Auth;
use Excel;

//use App\Traits\AttachmentTraits;

class FarmController extends Controller
{
//    use AttachmentTraits;
//    private $attachementPath = 'collection/farm';

    protected $model = Farm::class;

    public function __construct()
    {
        $this->middleware('ability:Farm|Method-Collection-Farm-Index,true')->only('index');
        $this->middleware('ability:Farm|Method-Collection-Farm-Store,true')->only('store');
        $this->middleware('ability:Farm|Method-Collection-Farm-Edit,true')->only('edit');
        $this->middleware('ability:Farm|Method-Collection-Farm-Update,true')->only('update');
        $this->middleware('ability:Farm|Method-Collection-Farm-destroy,true')->only('destroy');
        $this->middleware('ability:Farm|Method-Collection-Farm-BatchDelete,true')->only('batchDelete');
        $this->middleware('ability:Farm|Method-Collection-Farm-Import,true')->only('import');
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
            'asset_id' => 'nullable|exists:assets,serial,deleted_at,NULL'
        ]);
//        Log::info($request->all());
//        return $request->all();

        if ($id == -1) {
            $farm = new Farm;
        } else {
            $farm = Farm::findOrFail($id);
        }

        $farm->setRawAttributes($request->only(['category', 'name', 'number', 'input_date', 'source', 'description', 'size', 'serial', 'memo', 'display', 'keeper_id', 'asset_id']));
        $farm->user_id = Auth::id();
        if ($id != -1) $farm->id = $id;

        if ($farm->save()) {
            return $farm;
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
        //Log::info($request->all());
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
        return Farm::findOrFail($id);
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
        if ($farm->delete()) {
            return $farm;
        } else {
            abort(500, '删除失败');
        }
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchDelete(Request $request)
    {
        return parent::batchDelete($request);
    }

    /**
     * 农具导入功能
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function import(Request $request)
    {
        //return $request->all();

        $this->validate($request, [
            'file' => 'required',
            'type' => 'required|in:ignore,cover',
        ]);

        $user = Auth::user();

        $path = $request->file;
        //Log::info($path);

        Excel::load($path, function ($reader) use ($user, $request) {
            $sheet = $reader->getSheet(0);
            $sheet_array = $sheet->toArray();
            foreach ($sheet_array as $row => $cells) {
                if ($row == 0) continue; //忽略标题行
                if ($cells[8] == '') continue; //编号不存在则忽略
//                foreach($cells as $col => $cell){
//                    Log::info($cell);
//                }
                $category = $cells[1];
                $name = $cells[2];
                $number = $cells[3];
                $input_date = str_replace('.', '-', $cells[4]);
                $source = $cells[5];
                $description = $cells[6];
                $size = $cells[7];
                $serial = $cells[8];
                $memo = $cells[11];
                $display = $cells[12];
                if ($number > 1) $serial = trim(explode('-', $serial)[0]);
//                Log::info(
//                    'row : ' . $row . ','
//                    . 'category : ' . $category . ','
//                    . 'name : ' . $name . ','
//                    . 'number : ' . $number . ','
//                    . 'input_date : ' . $input_date . ','
//                    . 'source : ' . $source . ','
//                    . 'description : ' . $description . ','
//                    . 'size : ' . $size . ','
//                    . 'serial : ' . $serial . ','
//                    . 'memo : ' . $memo . ','
//                    . 'display : ' . $display
//                );

                while ($number--) {
                    $farm = Farm::where('serial', '=', $serial)->first();
                    if ($farm == null) $farm = new Farm;
                    else if ($request->type == 'ignore') continue;
                    $farm->serial = $serial;
                    $farm->category = $category;
                    $farm->name = $name;
                    $farm->input_date = $input_date;
                    $farm->source = $source;
                    $farm->description = $description;
                    $farm->size = $size;
                    $farm->memo = $memo;
                    $farm->display = $display;
                    $farm->keeper_id = $user->id;
                    $farm->user_id = $user->id;
                    $farm->save();
                    //Log::info($farm);

                    $serial = 'A' . (intval(substr($serial, 1)) + 1);
                }

            }
        });

        return response()->json([
            'res' => 'success'
        ]);

        //TODO 添加数据校验功能

    }

    /**
     * 保存一张图片
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImage(Request $request, $id)
    {
        $farm = Farm::findOrFail($id);

        $file = $request->file('file');
        if ($file != null && $file->isValid()) {
            //$mimeType = $file -> getMimeType();
            $entension = $file->getClientOriginalExtension();
            //$pic_name = md5(date('ymdhis') . $file->getClientOriginalName()) . '.' . $entension;
            $pic_name = date('Ymdhis') . substr(md5(date('ymdhis') . $file->getClientOriginalName()), 0, 4) . '.' . $entension;
            $path = $file->move('Storage/upload/image', $pic_name);
            $path = studly_case(str_replace("\\", "/", ucfirst($path)));

            $collectionImage = new CollectionImage;
            $collectionImage->path = $path;
            $collectionImage->thumb = $path;
            $collectionImage->collectible_type = Farm::class;
            $collectionImage->collectible_id = $farm->id;
            if ($collectionImage->save()) {
                //Log::info($path);
                return response()->json([
                    'name' => $pic_name,
                    'url' => '' . $path
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
     * 显示一张图片
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function showImage(Request $request, $id)
    {
        return Farm::findOrFail($id)->images()->select('id', 'thumb as url', 'path', 'cover', 'updated_at as time')->get();
    }

    /**
     * 删除一张图片
     * @param Request $request
     * @param $farm_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage(Request $request, $farm_id, $id)
    {
        $image = Farm::findOrFail($farm_id)->images()->where('id', '=', $id)->firstOrFail();

        if ($image->delete()) {
            return response()->json([
                'res' => true,
            ]);
        } else {
            abort(500, '删除失败');
        }
    }

    public function relate(Request $request, $id)
    {
        $lists = Farm::leftJoin('users as keepers', 'farms.keeper_id', '=', 'keepers.id')
            ->leftJoin('users', 'farms.user_id', '=', 'users.id')
            ->select('farms.id', 'farms.input_date', 'farms.category', 'farms.name', 'farms.serial', 'farms.source', 'farms.keeper_id', 'keepers.name as keeper', 'farms.user_id', 'users.name as user')
            ->where('farms.id', '!=', $id);

        if ($request->query_text != null && $request->query_text != '') {
            $lists = $lists->where('farms.name', 'like', '%' . $request->query_text . '%');
        }

        $lists = $lists->orderBy('id', 'desc')->take(50)->get();

        return $lists;
    }
}
