<?php

/**
 * @version 2.0
 * @author: wuzhihui
 * @date: 2017/7/3
 * @description:
 * （1）批量删除方法迁移到把batchDelete；（2017/7/3）
 * （2）整理控制器的接口的顺序；（2017/7/5）
 * （3）优化查询功能，将已被删除的用户也显示出来；（2017/7/6）
 *
 * @version: 2.0.2
 * @author: wuzhihui
 * @date: 2017/12/14
 * @description:
 * （1）更改权限控制；（2017/12/14）
 *
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/4/9
 * @description :
 * （1）新增导入功能；（2018/4/9）
 * （2）新增报增单导出功能；（2018/4/9）
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Auth, Redirect;
use Excel;
use IQuery, IQrcode;
use App\Asset, App\Alog, App\Invoice, App\User;
use Log;

class AssetController extends Controller
{
    protected $model = Asset::class;

    public function __construct()
    {
        $this->middleware('ability:Asset|Method-Asset-Index,true')->only('index');
        $this->middleware('ability:Asset|Method-Asset-Store,true')->only('store');
        $this->middleware('ability:Asset|Method-Asset-Edit,true')->only('edit');
        $this->middleware('ability:Asset|Method-Asset-Update,true')->only('update');
        $this->middleware('ability:Asset|Method-Asset-Destroy,true')->only('destroy');
        $this->middleware('ability:Asset|Method-Asset-Export,true')->only('export');
        $this->middleware('ability:Asset|Method-Asset-BatchExport,true')->only('batchExport');
        $this->middleware('ability:Asset|Method-Asset-BatchDelete,true')->only('batchDelete');
        $this->middleware('ability:Asset|Method-Asset-Import,true')->only('import');
        $this->middleware('ability:Asset|Method-Asset-BatchPrint,true')->only('batchPrint');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return "admin.asset.index";

        //$assets = Asset::paginate(10);
        //return view(config('app.theme').'.admin.asset.index')->withAssets($assets);


        //$request->flash();

        $assets = Asset::whereRaw('1 = 1');

        //文本查询
        $query_text = $request->input('query_text');
        if ($query_text != null && $request != '') {
            $texts = explode(' ', $query_text);
            foreach ($texts as $text) {
                $assets = $assets->where('assets.name', 'like', '%' . $text . '%');
            }

        }

        $post_date_start = $request->input('post_date_start');
        if ($post_date_start != null && $post_date_start != '') {
            $assets = $assets->where('post_date', '>=', $post_date_start);
        }

        $post_date_end = $request->input('post_date_end');
        if ($post_date_end != null && $post_date_end != '') {
            $assets = $assets->where('post_date', '<=', $post_date_end);
        }

        $type = $request->input('type');
        if ($type != null && $type != '') {
            $assets = $assets->where('assets.type', '=', $type);
        }

        IQuery::ofOrder($assets, $request);

        $assets = $assets->join('users as consumers', function ($join) {
            $join->on('consumers.id', '=', 'assets.consumer_id');
        });
        $assets = $assets->join('users as handlers', function ($join) {
            $join->on('handlers.id', '=', 'assets.handler_id');
        });
        $assets = $assets->join('categories', function ($join) {
            $join->on('categories.value', '=', 'assets.category_number')->where('categories.serial', 'like', 'category%');
        });
        $assets->select('assets.*', 'consumers.name as consumer_name', 'handlers.name as handler_name', 'categories.name as category_name');

        if ($request->paginate != null && $request->paginate != '')
            $assets = $assets->paginate($request->paginate);
        else
            $assets = $assets->paginate(10);

        if ($request->ajax()) {
            foreach ($assets as $asset) {
                $asset->type = Asset::TYPE[$asset->type];
            }
            return $assets;
        }

        if ($assets == null || count($assets) == 0) {
            return view(config('app.theme') . '.admin.asset.index')->withAssets($assets)->with('status', '查询结果为空');
        } else {
            return view(config('app.theme') . '.admin.asset.index')->withAssets($assets);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
//    public function create()
//    {
//        //
//        return view(config('app.theme').'.admin.asset.create');
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
//    public function show($id)
//    {
//        //
//    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $asset = Asset::find($id);

        if ($request->ajax()) return $asset;

        return view(config('app.theme') . '.admin.asset.edit')->withAsset($asset);
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
        //$asset = Asset::find($id);
        //if($asset->delete()){
        //    Alog::log('Category', Alog::OPERATE_DELETE, $asset->name, $request->getClientIp());
        //    return Redirect::back()->with('status', '删除成功');
        //}else{
        //    return Redirect::back()->withErrors();
        //}
        $asset = Asset::findOrFail($id);
        if ($asset->delete()) {
            //Alog::log('Asset', Alog::OPERATE_DELETE, $asset->name, $request->getClientIp());
            return $asset;
        } else {
            abort(500);
        }
    }

    public function export($id)
    {
        //return "admin.asset.export";
        $asset = Asset::find($id);

        $c_invoice = Invoice::where('number', '>', $asset->type * 10000000)->where('number', '<=', $asset->type * 10000000 + 9999999)->orderBy('number', 'desc')->first();
        if ($c_invoice != null && count($c_invoice) > 0) $number = $c_invoice->number + 1;
        else $number = $asset->type * 10000000 + 1;

        $invoice = Invoice::generate($asset, $number);
        $invoice->export();


    }

//    public function generate()
//    {
//        $assets = Asset::all();
//
//        foreach($assets as $asset){
//            $c_invoice = Invoice::where('number', '>', $asset->type * 10000000)->where('number', '<=', $asset->type * 10000000 + 9999999)->orderBy('number', 'desc')->first();
//            if($c_invoice != null && count($c_invoice) > 0 ) $number = $c_invoice->number + 1;
//            else $number = $asset->type * 10000000 + 1;
//
//            $invoice = Invoice::generate($asset, $number);
//            $invoice->store();
//        }
//    }

//    public function import(Request $request)
//    {
//        /*$this->validate($request, [
//            'type' => 'required',
//            'file' => 'required'
//        ]);*/
//
//        $type = $request->input('type');
//        $file = $request->file('file');
//        //Log::info($file);
//        if($file != null && $file -> isValid()) {
//            $mimeType = $file->getMimeType();
//            $entension = $file->getClientOriginalExtension();
//            $file_name = md5(date('ymdhis') . $file->getClientOriginalName()) . '.' . $entension;
//            $path = $file->move('storage/tmp', $file_name);
//            //Log::info($path);
//            Excel::load($path, function ($reader) use ($type) {
//                $results = $reader->getSheet(0);
//                $results_array = $results->toArray();
//                foreach($results_array as $key=>$cells) {
//                    //Log::info($key);
//                    if ($key == 0) continue; // ignore head
//                    $asset = Asset::where('serial', '=', $cells[3])->where('serial', '!=', null)->first();
//                    if($type == 'override' || $asset == null) $asset = new Asset;
//                    $asset->post_date = $cells[1];
//                    $asset->name = $cells[2];
//                    $asset->serial = $cells[3];
//                    if(strpos($cells[3],'E') == 0) $asset->type = Asset::TYPE_E;
//                    $asset->price = $cells[4];
//                    $asset->invoice = $cells[5];
//                    $asset->course = $cells[6];
//                    $asset->model = $cells[7];
//                    $asset->factory = $cells[8];
//                    $asset->provider = $cells[9];
//                    $asset->country = $cells[10];
//                    $asset->storage_location = $cells[11];
//                    $asset->application = $cells[12];
//                    $asset->purchase_number = $cells[13];
//                    $asset->purchase_date = $cells[14];
//                    $asset->consumer_company = $cells[15];
//                    $asset->amount = 1;
//                    $asset->sum = $cells[4];
//                    $asset->consumer_id = User::where('name', '=', $cells[16])->first()->id;
//                    $asset->entry = $cells[17];
//                    $asset->handler_id = User::where('name', '=', $cells[18])->first()->id;
//                    $asset->user_id = Auth::user()->id;
//                    $asset->size = "";
//                    $asset->card = "9700-32010097";
//                    $asset->save();
//                }
//            });
//            return "ture";
//        }
//    }

    public function batchExport()
    {
        //return "admin.asset.batch_export";


        Excel::create('固定资产', function ($excel) {

            $excel->sheet('固定资产', function ($sheet) {
                //$assets = Asset::all()->toArray();
                //$sheet->fromArray($assets);
                $assets = Asset::all();

                $header = [
                    '序号',
                    '入账日期',
                    '藏品名称',
                    '藏品编号',
                    '单价',
                    '发票号',
                    '经费科目',
                    '型号',
                    '厂家',
                    '供应商',
                    '国别',
                    '存放地点',
                    '使用方向',
                    '申购单号',
                    '购置日期',
                    '领用单位',
                    '数量',
                    '金额(元)',
                    '保管人',
                    '录入',
                    '经手人',
                ];
                $sheet->appendRow($header);

                foreach ($assets as $key => $asset) {
                    $data = [
                        $key + 1,
                        $asset->post_date,
                        $asset->name,
                        $asset->serial,
                        $asset->price,
                        $asset->invoice,
                        $asset->course,
                        $asset->model,
                        $asset->factory,
                        $asset->provider,
                        $asset->country,
                        $asset->storage_location,
                        $asset->application,
                        $asset->purchase_number,
                        $asset->purchase_date,
                        $asset->consumer_company,
                        $asset->amount,
                        $asset->sum,
                        $asset->consumer->name,
                        $asset->entry,
                        $asset->handler->name,
                    ];
                    $sheet->appendRow($data);
                }

            });
        })->export('xls');

    }

//    public function qrcode($id)
//    {
//        $asset = Asset::find($id);
//        $link = url('s?c='.$asset->serial);
//        return IQrcode::generate($link);
//    }

    /**
     * 批量删除功能
     * @param Request $request
     */
    public function batchDelete(Request $request)
    {
        return parent::batchDelete($request);
    }

    public function storeOrUpdate(Request $request, $id = -1)
    {
        //validate
        $this->validate($request, [
            'post_date' => 'required|date',
            'name' => 'required|string|max:255',
            'type' => 'required',
            'category_number' => 'required',
            //'image' => 'image',
            'serial' => 'nullable|string|max:255',
            'course' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'size' => 'required|string|max:255',
            'consumer_company' => 'required|string|max:255',
            'factory' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'storage_location' => 'required|string|max:255',
            'application' => 'required|string|max:255',
            'invoice' => 'required|string|max:255',
            'purchase_number' => 'nullable|string|max:255',
            'purchase_date' => 'required|date',
            'card' => 'required|string|max:255',
            'price' => 'required|numeric',
            'amount' => 'required|integer|min:0',
            'sum' => 'required|numeric|min:0',
            'entry' => 'required|string|max:255',
            'consumer_id' => 'required|integer|exists:users,id',
            'handler_id' => 'required|integer|exists:users,id',
            'memo' => 'nullable|string|max:2000',
        ]);
        $post_date = $request->input('post_date');
        $type = $request->input('type');
        $category_number = $request->input('category_number');
        $name = $request->input('name');
        $serial = $request->input('serial');
        $course = $request->input('course');
        $model = $request->input('model');
        $size = $request->input('size');
        $consumer_company = $request->input('consumer_company');
        $factory = $request->input('factory');
        $provider = $request->input('provider');
        $country = $request->input('country');
        $storage_location = $request->input('storage_location');
        $application = $request->input('application');
        $invoice = $request->input('invoice');
        $purchase_number = $request->input('purchase_number');
        $purchase_date = $request->input('purchase_date');
        $card = $request->input('card');
        $price = $request->input('price');
        $amount = $request->input('amount');
        $sum = $request->input('sum');
        $entry = $request->input('entry');
        $consumer_id = $request->input('consumer_id');
        $handler_id = $request->input('handler_id');
        $memo = $request->input('memo');

        if (-1 == $id) {
            $asset = new Asset;
        } else {
            $asset = Asset::find($id);
        }

        /*$file = $request->file('image');
        if($file != null && $file -> isValid()){
            //$mimeType = $file -> getMimeType();
            $entension = $file -> getClientOriginalExtension();
            $pic_name = md5(date('ymdhis').$file->getClientOriginalName()).'.'.$entension;
            $path = $file -> move('storage/upload/image', $pic_name);
            $asset->image = $path;
        }*/
        $asset->image = $request->image;

        $asset->post_date = $post_date;
        $asset->type = $type;
        $asset->category_number = $category_number;
        $asset->name = $name;
        $asset->serial = $serial;
        $asset->course = $course;
        $asset->model = $model;
        $asset->size = $size;
        $asset->consumer_company = $consumer_company;
        $asset->factory = $factory;
        $asset->provider = $provider;
        $asset->country = $country;
        $asset->storage_location = $storage_location;
        $asset->application = $application;
        $asset->invoice = $invoice;
        $asset->purchase_number = $purchase_number;
        $asset->purchase_date = $purchase_date;
        $asset->card = $card;
        $asset->price = $price;
        $asset->amount = $amount;
        $asset->sum = $sum;
        $asset->entry = $entry;
        $asset->consumer_id = $consumer_id;
        $asset->handler_id = $handler_id;
        $asset->user_id = Auth::user()->id;
        $asset->memo = $memo;

        if ($asset->save()) {

            //$link = url('s?c='.$asset->serial);
            //IQrcode::generate2($link, $asset->serial);

//            if($id == -1) $operate = Alog::OPERATE_CREATE;
//            else $operate = Alog::OPERATE_UPDATE;
//            Alog::log('Asset', $operate, $asset->name, $request->getClientIp());

            return $asset;
            //return Redirect::to('admin/asset')->with('status', '保存成功');
        } else {
            abort(500);
            //return Redirect::back()->withInput()->withErrors();
        }
    }

    /**
     * 批量导入
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
                if ($row == 0 || $row == 1) continue; //忽略标题，共2行

                //$index = $cells[0];
                $post_date = $cells[1];
                $type_name = $cells[2];
                $name = $cells[3];
                $serial = $cells[4];
                $course = $cells[5];
                $model = $cells[6];
                $size = $cells[7];
                $consumer_company = $cells[8];
                $factory = $cells[9];
                $provider = $cells[10];
                $country = $cells[11];
                $storage_location = $cells[12];
                $application = $cells[13];
                $category_name = $cells[14];
                $invoice = $cells[15];
                $purchase_number = $cells[16];
                $purchase_date = $cells[17];
                $card = $cells[18];
                $price = $cells[19];
                $amount = $cells[20];
                $sum = $cells[21];
                $consumer_name = $cells[22];
                $entry = $cells[23];
                $handler_name = $cells[24];

                $asset = Asset::where('serial', '=', $serial)->first();
                if($asset == null) {
                    $asset = new Asset;
                } else if($request->type == 'ignore') {
                    continue;
                }

                $asset->post_date = $post_date;

                $asset->type = 1;
                foreach(Asset::TYPE as $key=>$type){
                    if($type_name == $type) $asset->type = $key;
                }

                $asset->name = $name;
                $asset->serial = $serial;
                $asset->course = $course;
                $asset->model = $model;
                $asset->size = $size;
                $asset->consumer_company = $consumer_company;
                $asset->factory = $factory;
                $asset->provider = $provider;
                $asset->country = $country;
                $asset->storage_location = $storage_location;
                $asset->application = $application;

                $category = \App\Category::where('serial', 'like', 'category-%')->where('name', 'like', $category_name)->first();
                if($category != null) {
                    $asset->category_number = $category->value;
                } else {
                    $asset->category_number = 4020000;
                }

                $asset->invoice = $invoice;
                $asset->purchase_number = $purchase_number;
                $asset->purchase_date = $purchase_date;
                $asset->card = $card;
                $asset->price = $price;
                $asset->amount = $amount;
                $asset->sum = $sum;

                $consumer = \App\User::where('name', '=', $consumer_name)->first();
                if($consumer != null) {
                    $asset->consumer_id = $consumer->id;
                }else {
                    $asset->consumer_id = $user->id;
                }

                $asset->entry = $entry;

                $handler = \App\User::where('name', '=', $handler_name)->first();
                if($handler != null) {
                    $asset->handler_id = $handler->id;
                }else {
                    $asset->handler_id = $user->id;
                }

                $asset->user_id = $user->id;

                $asset->save();
            }
        });
    }

    public function batchPrint(Request $request)
    {
        $assets = Asset::all();

        foreach($assets as $asset){
            $c_invoice = Invoice::where('number', '>', $asset->type * 10000000)->where('number', '<=', $asset->type * 10000000 + 9999999)->orderBy('number', 'desc')->first();
            if ($c_invoice != null && count($c_invoice) > 0) $number = $c_invoice->number + 1;
            else $number = $asset->type * 10000000 + 1;

            $invoice = Invoice::generate($asset, $number);

            $invoice->store();
        }

        $files = glob(storage_path('excel/exports/*.xls'));
        $path = storage_path('excel/exports/print.zip');
        $zipper = new \Chumper\Zipper\Zipper;
        $zipper->make($path)->add($files)->close();
        return response()->download($path);
    }
}
