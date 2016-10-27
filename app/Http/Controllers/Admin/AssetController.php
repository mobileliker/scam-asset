<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Asset, App\Alog, App\Invoice;

use Auth, Redirect;
use Excel;
use IQuery;
use App\User;

class AssetController extends Controller
{
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


        $request->flash();

        $assets = Asset::whereRaw('1 = 1');

        //文本查询
        $query_text=$request->input('query_text');
        if($query_text != null && $request != ''){
            $texts=  explode(' ', $query_text);
            foreach($texts as $text)
            {
                $assets = $assets->where('name', 'like', '%'.$text.'%');
            }

        }

        $post_date_start=$request->input('post_date_start');
        if($post_date_start != null && $post_date_start != ''){
            $assets = $assets->where('post_date', '>=', $post_date_start);
        }

        $post_date_end = $request->input('post_date_end');
        if($post_date_end != null && $post_date_end != ''){
            $assets = $assets->where('post_date', '<=', $post_date_end);
        }

        $type = $request->input('type');
        if($type != null && $type != ''){
            $assets = $assets->where('type', '=', $type);
        }


        IQuery::ofOrder($assets, $request);


        $assets = $assets->paginate(10);

        if($assets == null || count($assets) == 0){
            return view(config('app.theme').'.admin.asset.index')->withAssets($assets)->with('status', '查询结果为空');
        }else{
            return view(config('app.theme').'.admin.asset.index')->withAssets($assets);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view(config('app.theme').'.admin.asset.create');
    }

    public function storeOrUpdate(Request $request, $id = -1)
    {
        //validate
        $this->validate($request, [
            'post_date' => 'required|date',
            'name' => 'required|string|max:255',
            'type' => 'required',
            'category_number' => 'required',
            'serial' => 'string|max:255',
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
            'purchase_number' => 'string|max:255',
            'purchase_date' => 'required|date',
            'card' => 'required|string|max:255',
            'price' => 'required|numeric',
            'amount' => 'required|integer|min:0',
            'sum' => 'required|numeric|min:0',
            'entry' => 'required|string|max:255',
            'consumer_id' => 'required|integer|exists:users,id',
            'handler_id' => 'required|integer|exists:users,id',
            'memo' => 'string|max:2000',
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

        if(-1 == $id){
            $asset = new Asset;
        }else{
            $asset = Asset::find($id);
        }

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

        if($asset->save()){
            if($id == -1) $operate = Alog::OPERATE_CREATE;
            else $operate = Alog::OPERATE_UPDATE;
            Alog::log('Asset', $operate, $asset->name, $request->getClientIp());
            return Redirect::to('admin/asset')->with('status', '保存成功');
        }else{
            return Redirect::back()->withInput()->withErrors();
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->storeOrUpdate($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $asset = Asset::find($id);
        return view(config('app.theme').'.admin.asset.edit')->withAsset($asset);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->storeOrUpdate($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $asset = Asset::find($id);
        if($asset->delete()){
            Alog::log('Category', Alog::OPERATE_DELETE, $asset->name, $request->getClientIp());
            return Redirect::back()->with('status', '删除成功');
        }else{
            return Redirect::back()->withErrors();
        }
    }

    public function export($id)
    {
        //return "admin.asset.export";
        $asset = Asset::find($id);

        $c_invoice = Invoice::where('number', '>', $asset->type * 10000000)->where('number', '<=', $asset->type * 10000000 + 9999999)->orderBy('number', 'desc')->first();
        if($c_invoice != null && count($c_invoice) > 0 ) $number = $c_invoice->number + 1;
        else $number = $asset->type * 10000000 + 1;

        $invoice = Invoice::generate($asset, $number);
        $invoice->export();

    }

    public function import(Request $request)
    {
        /*$this->validate($request, [
            'type' => 'required',
            'file' => 'required'
        ]);*/

        $type = $request->input('type');
        $file = $request->file('file');
        //Log::info($file);
        if($file != null && $file -> isValid()) {
            $mimeType = $file->getMimeType();
            $entension = $file->getClientOriginalExtension();
            $file_name = md5(date('ymdhis') . $file->getClientOriginalName()) . '.' . $entension;
            $path = $file->move('storage/tmp', $file_name);
            //Log::info($path);
            Excel::load($path, function ($reader) use ($type) {
                $results = $reader->getSheet(0);
                $results_array = $results->toArray();
                foreach($results_array as $key=>$cells) {
                    //Log::info($key);
                    if ($key == 0) continue; // ignore head
                    $asset = Asset::where('serial', '=', $cells[3])->where('serial', '!=', null)->first();
                    if($type == 'override' || $asset == null) $asset = new Asset;
                    $asset->post_date = $cells[1];
                    $asset->name = $cells[2];
                    $asset->serial = $cells[3];
                    if(strpos($cells[3],'E') == 0) $asset->type = Asset::TYPE_E;
                    $asset->price = $cells[4];
                    $asset->invoice = $cells[5];
                    $asset->course = $cells[6];
                    $asset->model = $cells[7];
                    $asset->factory = $cells[8];
                    $asset->provider = $cells[9];
                    $asset->country = $cells[10];
                    $asset->storage_location = $cells[11];
                    $asset->application = $cells[12];
                    $asset->purchase_number = $cells[13];
                    $asset->purchase_date = $cells[14];
                    $asset->consumer_company = $cells[15];
                    $asset->amount = $cells[16];
                    $asset->sum = $cells[17];
                    $asset->consumer_id = User::where('name', '=', $cells[18])->first()->id;
                    $asset->entry = $cells[19];
                    $asset->handler_id = User::where('name', '=', $cells[20])->first()->id;
                    $asset->user_id = Auth::user()->id;
                    $asset->size = "";
                    $asset->card = "9700-32010097";
                    $asset->save();
                }
            });

            return "ture";
        }



    }

    public function batch_export()
    {
        //return "admin.asset.batch_export";


        Excel::create('固定资产', function($excel) {

            $excel->sheet('固定资产', function($sheet){
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

                foreach($assets as $key=>$asset){
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
}
