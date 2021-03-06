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
 * （6）修复导入时编号的错误； （2017/9/20）
 * （7）修复Storage大小写的错误；（2017/9/20）
 *
 * @version : 2.0.2
 * @author ： wuzhihui
 * @date : 2017/12/1
 * @description:
 * (1)添加日志记录；（2017/12/1）
 * （2）格式化代码；（2017/12/1）
 * (3)添加拍摄清单函数；（2017/12/5）
 * (4)补充权限控制；（2017/12/14）
 * （5）更改权限控制；（2017/12/14）
 *
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/1/12
 * @description :
 * （1)采用新的导入格式；（2018/1/12）
 * （2）新增storage、origin字段，去除display、category字段；（2018/1/12）
 * （3）新增p_category和category字段；（2018/1/22）
 * （4）修复农具导入是编号为XXXX-1、XXX-2无法导入的错误；（2018/1/22）
 * （5）修复首页按父分类排序的错误；（2018/1/22）
 * （6）新增导入时导入旧编号；（2018/1/23）
 */

namespace App\Http\Controllers\Api;

use App\CollectionImage;
use App\Events\FarmEvent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use IQuery;
use App\Farm;
use Log;
use Auth;
use Excel;
use App\Alog;

//use App\Traits\AttachmentTraits;

class FarmController extends Controller
{
    protected $model = Farm::class;

    public function __construct()
    {
        $this->middleware('ability:Farm|Method-Collection-Farm-Index,true')->only('index');
        $this->middleware('ability:Farm|Method-Collection-Farm-Store,true')->only('store');
        $this->middleware('ability:Farm|Method-Collection-Farm-Show,true')->only('show');
        $this->middleware('ability:Farm|Method-Collection-Farm-Edit,true')->only('edit');
        $this->middleware('ability:Farm|Method-Collection-Farm-Update,true')->only('update');
        $this->middleware('ability:Farm|Method-Collection-Farm-destroy,true')->only('destroy');
        $this->middleware('ability:Farm|Method-Collection-Farm-BatchDelete,true')->only('batchDelete');
        $this->middleware('ability:Farm|Method-Collection-Farm-Import,true')->only('import');
        $this->middleware('ability:Farm|Method-Collection-Farm-ShowImage,true')->only('showImage');
        $this->middleware('ability:Farm|Method-Collection-Farm-SaveImage,true')->only('saveImage');
        $this->middleware('ability:Farm|Method-Collection-Farm-DeleteImage,true')->only('deleteImage');
        $this->middleware('ability:Farm|Method-Collection-Farm-Relate,true')->only('relate');
        $this->middleware('ability:Farm|Method-Collection-Farm-CameraList,true')->only('cameraList');
    }


    /**
     * 新增保存和更新保存
     * @param Request $request
     * @param int $id
     * @return Farm|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function storeOrUpdate(Request $request, $id = -1)
    {
        $this->validate($request, [
            'input_date' => 'required|date',
            'p_category' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'number' => 'required|integer|min:0',
            'source' => 'nullable|string|max:255',
            'origin' => 'nullable|string|max:255',
            'storage' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'size' => 'nullable|string|max:255',
            'serial' => 'required|string|max:255',
            'memo' => 'nullable|string|max:2000',
//            'display' => 'required|string|max:255',
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

        $farm->setRawAttributes($request->only(['p_category','category', 'name', 'number', 'input_date', 'source', 'description', 'size', 'serial', 'memo', 'keeper_id', 'asset_id', 'storage', 'origin']));
        $farm->user_id = Auth::id();
        if ($id != -1) $farm->id = $id;

        if ($farm->save()) {
            event(new FarmEvent(Alog::getOperate($id), $request->getClientIp(), $farm)); //添加日志记录
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
            ->select('farms.id', 'farms.input_date', 'farms.p_category', 'farms.category', 'farms.name', 'farms.serial', 'farms.source', 'farms.keeper_id', 'keepers.name as keeper', 'farms.user_id', 'users.name as user', 'farms.storage', 'farms.origin');

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
            'p_category' => 'farms.p_category',
            'category' => 'farms.category',
            'name' => 'farms.name',
            'serial' => 'farms.serial',
            'origin' => 'farms.origin',
            'source' => 'farms.source',
            'keeper' => 'keepers.name',
            'user' => 'users.name',
        ];

        $text_params = [
            'name' => 'farms.name',
            'serial' => 'farms.serial',
            'source' => 'farms.source',
            'origin' => 'farms.origin',
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
    public function destroy(Request $request, $id)
    {
        $farm = Farm::findOrFail($id);
        if ($farm->delete()) {
            event(new FarmEvent('destroy', $request->getClientIp(), $farm));
            return $farm;
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


            if ($request->version == null || $request->version == '') {

                $sheet = $reader->getSheet(0);
                $sheet_array = $sheet->toArray();

                foreach ($sheet_array as $row => $cells) {
                    if ($row == 0) continue; //忽略标题行
                    if ($cells[8] == '') continue; //编号不存在则忽略
//                foreach($cells as $col => $cell){
//                    Log::info($cell);
//                }
                    //$index = $cells[0];
                    $category = $cells[1];
                    $name = $cells[2];
                    $number = $cells[3];
                    $input_date = str_replace('.', '-', $cells[4]);
                    $source = $cells[5];
                    $description = $cells[6];
                    $size = $cells[7];
                    $serial = $cells[8];
                    //$image1 = $cells[9];
                    //$image2 = $cells[10];
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

                        event(new FarmEvent('import', $request->getClientIp(), $farm)); //添加导入日记记录

                        $serial = 'A0' . (intval(substr($serial, 1)) + 1);
                    }

                }
            } else {
                //Log::info('farm import v2');

                for ($i = 1; $i <= 4; $i++) {

                    $sheet = $reader->getSheet($i);
                    $sheet_array = $sheet->toArray();

                    foreach ($sheet_array as $row => $cells) {
                        if ($row == 0) continue; //忽略标题行

                        $index = $cells[0];
                        $batchNumber = $cells[1];
                        $input_date = str_replace('.', '-', $cells[2]);
                        $p_category = $cells[3];
                        $category = $cells[4];
                        $name = $cells[5];
                        $oldSerial = $cells[6];
                        $serial = $cells[7];
                        $number = $cells[8];
                        $size = $cells[9];
                        if ($cells[10] != null && $cells[10] != '') $origin = $cells[10];
                        $source = $cells[11];
                        $price = $cells[12];
                        $storage = $cells[13];

                        if ($serial == null || $serial == '') continue; //忽略无编号农具

                        $serialArr = explode('、', trim($serial));
                        if (count($serialArr) > 1) {
                            $serial_start = intval(substr($serialArr[0], 1, 7));
                            $serial_end = intval(substr($serialArr[0], 1, 7));
                        } else {
                            $serialArr2 = explode('-', $serialArr[0]);
                            $serial_start = intval(substr(trim($serialArr2[0]), 1, 7));
                            $serial_end = intval(substr(trim($serialArr2[count($serialArr2) - 1]), 1, 7));
                        }

                        while ($serial_start <= $serial_end) {

                            $farm = Farm::where('serial', '=', substr($serial, 0, 1) . str_pad($serial_start, 7, '0', STR_PAD_LEFT))->first();
                            if ($farm == null) $farm = new Farm;
                            else if ($request->type == 'ignore') continue;
                            $farm->odd_serial = $oldSerial;
                            $farm->serial = substr($serial, 0, 1) . str_pad($serial_start, 7, '0', STR_PAD_LEFT);
                            $farm->p_category = $p_category;
                            $farm->category = $category;
                            $farm->name = $name;
                            $farm->input_date = $input_date;
                            $farm->source = $source;
                            $farm->origin = $origin;
                            $farm->number = $number;
                            //$farm->description = $description;
                            $farm->size = $size;
                            $farm->storage = $storage;
                            //$farm->memo = $memo;
                            //$farm->display = $display;
                            $farm->keeper_id = $user->id;
                            $farm->user_id = $user->id;
                            $farm->save();
                            //Log::info($farm);

                            event(new FarmEvent('import', $request->getClientIp(), $farm)); //添加导入日记记录

                            $serial_start = $serial_start + 1;
                        }
                    }
                }

            }

        });

        return response()->json(['res' => 'success']);

        //TODO 添加数据校验功能

    }

    /**
     * 保存一张图片
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function saveImage(Request $request, $id)
    {
        $farm = Farm::findOrFail($id);

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
            $collectionImage->collectible_type = Farm::class;
            $collectionImage->collectible_id = $farm->id;
            if ($collectionImage->save()) {
                $collectionImage->collectible;
                event(new FarmEvent('saveImage', $request->getClientIp(), $collectionImage)); //添加日记事件

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
    public
    function showImage(Request $request, $id)
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
    public
    function deleteImage(Request $request, $farm_id, $id)
    {
        $image = Farm::findOrFail($farm_id)->images()->where('id', '=', $id)->firstOrFail();

        if ($image->delete()) {
            $image->collectible;
            event(new FarmEvent('deleteImage', $request->getClientIp(), $image)); //添加日记事件
            return response()->json([
                'res' => true,
            ]);
        } else {
            abort(500, '删除失败');
        }
    }

    public
    function relate(Request $request, $id)
    {
        $lists = Farm::leftJoin('users as keepers', 'farms.keeper_id', '=', 'keepers.id')
            ->leftJoin('users', 'farms.user_id', '=', 'users.id')
            ->select('farms.id', 'farms.input_date','farms.p_category', 'farms.category', 'farms.name', 'farms.serial', 'farms.source', 'farms.keeper_id', 'keepers.name as keeper', 'farms.user_id', 'users.name as user', 'farms.storage', 'farms.origin', 'farms.number')
            ->where('farms.id', '!=', $id);

        if ($request->query_text != null && $request->query_text != '') {
            $lists = $lists->where('farms.name', 'like', '%' . $request->query_text . '%');
        }

        $lists = $lists->orderBy('id', 'desc')->take(50)->get();

        return $lists;
    }

//生成拍摄清单函数
    public
    function cameraList()
    {
        $post_time = Date('YmdHis');
        $filePath = resource_path('assets/template/camera-list.xls');
        $distPath = storage_path('excel/exports/' . $post_time . '.xls');
        copy($filePath, $distPath);

        Excel::load($distPath, function ($reader) {
            $lists = Farm::leftJoin('collection_images', function ($join) {
                $join->on('farms.id', '=', 'collection_images.collectible_id')->whereNull('collection_images.deleted_at')->where('collectible_type', '=', Farm::class);
            })->whereNull('collection_images.id')->select('farms.category', 'farms.name', 'farms.serial', 'farms.number'/*, 'farms.storage'*/)->get();

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
