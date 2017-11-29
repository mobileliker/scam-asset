<?php

/**
 * 土壤管理控制器
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/27
 * @description :
 * (1)基本功能； （2017/11/27）
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use IQuery;
use Log;
use App\Soil, App\SoilBig, App\SoilSmall;
use Excel;
use Auth;
use App\CollectionImage;

class SoilController extends Controller
{
    protected $model = Soil::class;

    public function storeOrUpdate(Request $request, $id = -1)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'ename' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'serial' => 'required|string|max:255',
            'origin' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'altitude' => 'nullable|numeric',
            'terrain' => 'nullable|string|max:255',
            'gradient' => 'nullable|string|max:255',
            'matrix' => 'nullable|string|max:255',
            'vegetation' => 'nullable|string|max:255',
            'use_status' => 'nullable|string|max:255',
            'depth' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'collecter' => 'nullable|string|max:255',
            'memo' => 'nullable|string|max:2000',
            'keeper_id' => 'required|integer|exists:users,id,deleted_at,NULL',
            'asset_id' => 'nullable|exists:assets,serial,deleted_at,NULL'
        ]);

        if($id == -1) {
          $obj = new Soil;
        } else {
          $obj = Soil::findOrFail($id);
        }

        $obj->setRawAttributes($request->only(['input_date', 'name', 'ename', 'region', 'serial', 'origin', 'location', 'altitude', 'terrain', 'gradient', 'matrix', 'vegetation', 'use_status', 'depth', 'description', 'collecter', 'memo', 'keeper_id', 'asset_id']));
        $obj->user_id = Auth::id();
        if($id != -1) $obj->id = $id;

        if($obj->save()) {
          return $obj;
        } else {
          abort(500, '保存失败');
        }
    }

    public function import(Request $request)
    {
      $this->validate($request, [
        'file' => 'required',
        'type' => 'required|in:ignore,cover',
      ]);

        $user = Auth::user();

        $path = $request->file;

        Excel::load($path, function($reader) use ($user, $request) {
          $sheet = $reader->getSheet(0);
          $sheet_array = $sheet->toArray();
          foreach($sheet_array as $row=> $cells) {
            if ($row == 0) continue; //忽略标题行和表头
            if($cells[5] == '') continue; //编号不存在则忽略

            $index = $cells[0];
            $input_date = $cells[1];
            $name = $cells[2];
            $ename = $cells[3];
            $region = $cells[4];
            $serial = $cells[5];
            $big_count = $cells[6];
            $big_serial = $cells[7];
            $big_storage = $cells[8];
            $small_count = $cells[9];
            $small_serial = $cells[10];
            $small_storage = $cells[11];
            $origin_serial = $cells[12];
            $origin = $cells[13];
            $location = $cells[14];
            $altitude = $cells[15];
            $terrain = $cells[16];
            $gradient = $cells[17];
            $matrix = $cells[18];
            $vegetation = $cells[19];
            $use_status = $cells[20];
            $depth = $cells[21];
            $description = $cells[22];
            $collecter = $cells[23];
            $memo = $cells[24];

            $obj = Soil::where('serial', '=', $serial)->first();
            if($obj == null) $obj = new Soil;
            else if($request->type == 'ignore') continue;

            $input_dates = explode('-', $input_date);
            $obj->input_date = '20' . $input_dates[2] . '-' . $input_dates[0] . '-' . $input_dates[1];

            $obj->name = $name;
            $obj->ename = $ename;
            $obj->region = $region;
            $obj->serial = $serial;
            $obj->origin = $region;
            $obj->location = $location;
            $obj->altitude = $altitude;
            $obj->terrain = $terrain;
            $obj->gradient = $gradient;
            $obj->matrix = $matrix;
            $obj->vegetation = $vegetation;
            $obj->use_status = $use_status;
            $obj->depth = $depth;
            $obj->collecter = $collecter;
            $obj->description = $description;
            $obj->memo = $memo;

            $obj->keeper_id = $user->id;
            $obj->user_id = $user->id;

            $obj->save();

            if($big_count > 0) {
              // $soilBig = SoilBig::firstOrNew([
              //   'soil_id' => $obj->id,
              //   'serial' => $big_serial,
              // ]);
              $soilBig = SoilBig::where('soil_id', '=', $obj->id)->where('serial', '=', $big_serial)->first();
              if($soilBig == null) {
                $soilBig = new SoilBig;
                $soilBig->soil_id = $obj->id;
                $soilBig->serial = $big_serial;
              }
              $soilBig->storage = $big_storage;
              $soilBig->save();
            }

            if($small_count > 0) {
              if($small_count == 1) {
                $prefix = substr(trim($small_serial), 0, 2);
                $serial = intval(substr($small_serial, 2));
              } else {
                $serials = explode('-', $small_serial);
                $prefix = substr(trim($serials[0]), 0, 2);
                $serial = intval(substr(trim($serials[0]), 2));
              }

              while($small_count --) {
                /*$soilSmall = SoilSmall::firstOrNew([
                  'soil_id' => $obj->id,
                  'serial' => $prefix . $serial,
                ]);*/
                $soilSmall = SoilSmall::where('soil_id', '=', $obj->id)->where('serial', '=', $prefix . $serial)->first();
                if($soilSmall == null) {
                  $soilSmall = new SoilSmall;
                  $soilSmall->soil_id = $obj->id;
                  $soilSmall->serial = $prefix . $serial;
                }
                $soilSmall->storage = $small_storage;
                $soilSmall->save();
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
        $lists = Soil::leftJoin('users as keepers', 'soils.keeper_id', '=', 'keepers.id')
            ->leftJoin('users', 'soils.user_id', '=', 'users.id')
            ->select('soils.id', 'soils.input_date', 'soils.name', 'soils.ename', 'soils.serial', 'soils.origin', 'soils.keeper_id', 'keepers.name as keeper', 'soils.user_id', 'users.name as user');

        if ($request->input_date_start != null && $request->input_date_start != '') {
            $lists = $lists->where('soils.input_date', '>=', $request->input_date_start)->where('soils.input_date', '<=', $request->input_date_end);
        }

        if ($request->keeper_id != null && $request->keeper_id != '') {
            $lists = $lists->where('soils.keeper_id', '=', $request->keeper_id);
        }

        if ($request->user_id != null && $request->user_id != '') {
            $lists = $lists->where('soils.user_id', '=', $request->user_id);
        }

        if ($request->is_asset != null && $request->is_asset != '') {
            if ($request->is_asset == 0) $lists = $lists->whereNull('soils.asset_id');
            else $lists = $lists->whereNotNull('soils.asset_id');
        }

        $order_params = [
            'id' => 'soils.id',
            'input_date' => 'soils.input_date',
            'name' => 'soils.name',
            'ename' => 'soils.ename',
            'serial' => 'soils.serial',
            'origin' => 'soils.origin',
            'keeper' => 'keepers.name',
            'user' => 'users.name',
        ];

        $text_params = [
            'name' => 'soils.name',
            'ename' => 'soils.ename',
            'serial' => 'soils.serial',
        ];

        $lists = IQuery::ofDefault($lists, $request, $order_params, $text_params, 'soils');

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
        return Soil::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        return Soil::findOrFail($id);
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
    public function destroy($id)
    {
      $obj = Soil::findOrFail($id);
      if($obj->delete()) {
        return $obj;
      } else {
        abort(500, ‘删除失败’);
      }
    }

    public function batchDelete(Request $request)
    {
        return parent::batchDelete($request); // TODO: Change the autogenerated stub
    }


        /**
         * 显示一张图片
         * @param Request $request
         * @param $id
         * @return mixed
         */
        public function showImage(Request $request, $id)
        {
            return Soil::findOrFail($id)->images()->select('id', 'thumb as url', 'path', 'cover', 'updated_at as time')->get();
        }


        /**
         * 保存一张图片
         * @param Request $request
         * @param $id
         * @return \Illuminate\Http\JsonResponse
         */
        public function saveImage(Request $request, $id)
        {
            $soil = Soil::findOrFail($id);

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
                $collectionImage->collectible_type = Soil::class;
                $collectionImage->collectible_id = $soil->id;
                if ($collectionImage->save()) {
                    //Log::info($path);
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
        public function deleteImage(Request $request, $soil_id, $id)
        {
            $image = Soil::findOrFail($soil_id)->images()->where('id', '=', $id)->firstOrFail();

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
            $lists = Soil::leftJoin('users as keepers', 'soils.keeper_id', '=', 'keepers.id')
                ->leftJoin('users', 'soils.user_id', '=', 'users.id')
                ->select('soils.id', 'soils.input_date', 'soils.name', 'soils.serial', 'soils.ename', 'soils.origin', 'soils.keeper_id', 'keepers.name as keeper', 'soils.user_id', 'users.name as user')
                ->where('soils.id', '!=', $id);

            if ($request->query_text != null && $request->query_text != '') {
                $lists = $lists->where('soils.name', 'like', '%' . $request->query_text . '%');
            }

            $lists = $lists->orderBy('id', 'desc')->take(50)->get();

            return $lists;
        }
}
