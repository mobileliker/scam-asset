<?php

/**
 * 岩石管理控制器
 * @version : 2.0
 * @author : wuzhihui
 * @date : 2017/10/18
 * @description :
 * (1) 完成基本功能；（2017/10/18）
 * （2）添加图片相关的功能；（2017/11/1）
 *
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/1
 * @description :
 * (1)添加日志记录；（2017/12/1）
 * (2)index函数添加最后编辑时间；（2017/12/5）
 * (3)import函数新增source字段；（2017/12/5）
 * (4)storeOrUpdate函数新增size、storage和source字段；（2017/12/5）
 * (5)修复relate函数编辑人获取的错误，并添加最后编辑时间字段；（2017/12/5）
 * (6)新增导入功能函数；（2017/12/6）
 * (7)新增权限控制；（2017/12/14）
 * （8）更改权限控制；（2017/12/14）
 */

namespace App\Http\Controllers\Api;

use App\Rock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use IQuery;
use Auth;
use Log;
use Excel;
use App\CollectionImage;
use App\Events\RockEvent;
use App\Alog;

class RockController extends Controller
{
    protected $model = Rock::class;

    public function __construct()
    {
        $this->middleware('ability:Rock|Method-Collection-Rock-Import,true')->only('import');
        $this->middleware('ability:Rock|Method-Collection-Rock-Index,true')->only('index');
        $this->middleware('ability:Rock|Method-Collection-Rock-Store,true')->only('store');
        $this->middleware('ability:Rock|Method-Collection-Rock-Show,true')->only('show');
        $this->middleware('ability:Rock|Method-Collection-Rock-Edit,true')->only('edit');
        $this->middleware('ability:Rock|Method-Collection-Rock-Update,true')->only('update');
        $this->middleware('ability:Rock|Method-Collection-Rock-Destroy,true')->only('destroy');
        $this->middleware('ability:Rock|Method-Collection-Rock-BatchDelete,true')->only('batchDelete');
        $this->middleware('ability:Rock|Method-Collection-Rock-ShowImage,true')->only('showImage');
        $this->middleware('ability:Rock|Method-Collection-Rock-SaveImage,true')->only('saveImage');
        $this->middleware('ability:Rock|Method-Collection-Rock-DeleteImage,true')->only('deleteImage');
        $this->middleware('ability:Rock|Method-Collection-Rock-Relate,true')->only('relate');
        $this->middleware('ability:Rock|Method-Collection-Rock-CameraList,true')->only('cameraList');
    }

    /**
     * 新增保存和更新保存
     * @param Request $request
     * @param int $id
     * @return Rock|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function storeOrUpdate(Request $request, $id = -1)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|integer|min:0',
            'ename' => 'nullable|string|max:255',
            'input_date' => 'required|date',
            'serial' => 'required|string|max:255',
            'classification' => 'nullable|string|max:255',
            'feature' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'storage' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
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

        $rock->setRawAttributes($request->only(['name', 'category_id', 'ename', 'input_date', 'serial', 'classification', 'feature', 'origin', 'description', 'keeper_id', 'asset_id', 'memo', 'size', 'storage', 'source']));
        $rock->user_id = Auth::id();

        if ($id != -1) $rock->id = $id;

        if ($rock->save()) {
            event(new RockEvent(Alog::getOperate($id), $request->getClientIp(), $rock)); //添加日志记录
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
            ->select('rocks.id', 'rocks.input_date', 'rocks.category_id', 'rocks.category as category', 'rocks.name', 'rocks.ename', 'rocks.serial', 'rocks.keeper_id', 'keepers.name as keeper', 'rocks.user_id', 'users.name as user', 'rocks.updated_at as updated_at');


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

        if ($request->category != null && $request->category != '') {
            $lists = $lists->where('rocks.category', '=', $request->category);
        }

        $order_params = [
            'id' => 'rocks.id',
            'input_date' => 'rocks.input_date',
            'category' => 'rocks.category',
            'name' => 'rocks.name',
            'ename' => 'rocks.ename',
            'serial' => 'rocks.serial',
            'keeper' => 'keepers.name',
            'user' => 'users.name',
            'updated_at' => 'rocks.updated_at'
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
        //return Rock::findOrFail($id);
        return Rock::leftJoin('users as keeper', 'keeper.id', '=', 'rocks.keeper_id')
            ->leftJoin('users', 'users.id', '=', 'rocks.user_id')
            ->select('rocks.*', 'keeper.name as keeper_name', 'users.name as user_name')->findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Rock::findOrFail($id);
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
        $obj = Rock::findOrFail($id);
        if ($obj->delete()) {
            event(new RockEvent('destroy', $request->getClientIp(), $obj));
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
     * 岩石导入功能
     *
     */
    public function import(Request $request)
    {
        //Log::info('import');
        $this->validate($request, [
            'file' => 'required',
            'type' => 'required|in:ignore,cover',
        ]);

        $user = Auth::user();

        $path = $request->file;

        Excel::load($path, function ($reader) use ($user, $request) {

            // $categories = [
            //     1 => '岩石',
            //     2 => '矿物',
            //     3 => '化石',
            // ];
            $categories = array();
            $categories[0] = '岩石';
            $categories[1] = '矿物';
            $categories[2] = '化石';
            $categories[3] = '土壤标本采集工具';
            $categories[4] = '岩矿标本采集工具';


            for ($i = 1; $i <= 5; $i++) {

                $sheet = $reader->getSheet($i);
                $sheet_array = $sheet->toArray();
                foreach ($sheet_array as $row => $cells) {
                    //\Log::info('$i='.$i.',$row='.$row);

                    if ($row == 0 || $row == 1) continue; //忽略标题行和表头
                    if ($cells[4] == '') continue; //编号不存在则忽略

                    // foreach($cells as $col => $cell) {
                    //     Log::info($col . ' : ' .$cell);
                    // }

                    $input_date = $cells[1];
                    $name = $cells[2];
                    $ename = $cells[3];
                    $serial = $cells[4];
                    $classification = $cells[5];
                    $feature = $cells[6];
                    //$category_name = $cells[7];
                    $size = $cells[8];
                    $storage = $cells[9];
                    $origin = $cells[10];
                    $description = $cells[11];
                    $memo = $cells[12];
                    $source = $cells[13];

                    $serials = explode('-', $serial);
                    //Log::info($serials);
                    //Log::info(count($serials));

                    if (count($serials) > 1) {
                        $serial = intval(substr(trim($serials[0]), 2));
                        $serial_end = intval(substr(trim($serials[1]), 2));
                    } else {
                        $serial = intval(substr(trim($serial), 2));
                        $serial_end = $serial;
                    }

                    while ($serial <= $serial_end) {
                        $rock = Rock::where('serial', '=', 'C0' . $serial)->first();
                        if ($rock == null) $rock = new Rock;
                        else if ($request->type == 'ignore') continue;

                        //Log::info($i .'~' . $cells[0] . '~' . $input_date . '~' . $name);
                        $input_dates = explode('-', $input_date);
                        $rock->input_date = '20' . $input_dates[2] . '-' . $input_dates[0] . '-' . $input_dates[1];
                        $rock->name = $name;
                        $rock->ename = $ename;
                        $rock->serial = 'C0' . $serial;
                        $rock->classification = $classification;
                        $rock->feature = $feature;
                        $rock->size = $size;
                        $rock->storage = $storage;
                        $rock->origin = $origin;
                        $rock->description = $description;
                        $rock->memo = $memo;
                        $rock->source = $source;
                        $rock->keeper_id = $user->id;
                        $rock->user_id = $user->id;
                        $rock->category = $categories[$i - 1];
                        $rock->save();
                        event(new RockEvent('import', $request->getClientIp(), $rock)); //添加导入日记记录

                        $serial = $serial + 1;
                    }
                }
            }

        });
    }

    /**
     * 显示一张图片
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function showImage(Request $request, $id)
    {
        return Rock::findOrFail($id)->images()->select('id', 'thumb as url', 'path', 'cover', 'updated_at as time')->get();
    }

    /**
     * 保存一张图片
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveImage(Request $request, $id)
    {
        $rock = Rock::findOrFail($id);

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
            $collectionImage->collectible_type = Rock::class;
            $collectionImage->collectible_id = $rock->id;
            if ($collectionImage->save()) {
                //Log::info($path);
                $collectionImage->collectible;
                event(new RockEvent('saveImage', $request->getClientIp(), $collectionImage)); //添加日记事件
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
     * 删除一张图片
     * @param Request $request
     * @param $rock_id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage(Request $request, $rock_id, $id)
    {
        $image = Rock::findOrFail($rock_id)->images()->where('id', '=', $id)->firstOrFail();

        if ($image->delete()) {
            $image->collectible;
            event(new RockEvent('deleteImage', $request->getClientIp(), $image)); //添加日记事件
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
        $lists = Rock::leftJoin('users as keepers', 'rocks.keeper_id', '=', 'keepers.id')
            ->leftJoin('users', 'rocks.user_id', '=', 'users.id')
            ->select('rocks.id', 'rocks.input_date', 'rocks.category', 'rocks.name', 'rocks.serial', 'rocks.ename', 'rocks.keeper_id', 'keepers.name as keeper', 'rocks.user_id', 'users.name as user', 'rocks.updated_at')
            ->where('rocks.id', '!=', $id);

        if ($request->query_text != null && $request->query_text != '') {
            $lists = $lists->where('rocks.name', 'like', '%' . $request->query_text . '%');
        }

        $lists = $lists->orderBy('id', 'desc')->take(50)->get();

        return $lists;
    }

    /**
     * 生成拍摄清单函数
     */
    public function cameraList()
    {
        $post_time = Date('YmdHis');
        $filePath = resource_path('assets/template/camera-list.xls');
        $distPath = storage_path('excel/exports/camera-list/rock/' . $post_time . '.xls');
        copy($filePath, $distPath);

        Excel::load($distPath, function ($reader) {
            $lists = Rock::leftJoin('collection_images', function ($join) {
                $join->on('rocks.id', '=', 'collection_images.collectible_id')->whereNull('collection_images.deleted_at')->where('collectible_type', '=', Rock::class);
            })->whereNull('collection_images.id')->select('rocks.category', 'rocks.name', 'rocks.serial', /*'rocks.number', */
                'rocks.storage')->get();

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
