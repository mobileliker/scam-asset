<?php

/**
 * 迁移plants表的数据到forestries表
 * @version : 2.0.3
 * @ : wuzhihui
 * @date : 2018/3/27
 * @description :
 * (1) 基本功能；（2018/3/27）
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Plant, App\Forestry, App\CollectionImage;

class RemovalPlantToForestryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $plants = Plant::where('serial', 'like', 'G%')->get();

        foreach($plants as $plant) {
            $forestry = Forestry::where('serial', '=', $plant->serial)->first();
            if($forestry == null) {
                $forestry = new Forestry;
                $forestry->serial = $plant->serial;
            }
            $forestry->category = $plant->category;
            $forestry->input_date = $plant->input_date;
            $forestry->family = $plant->family;
            $forestry->genus = $plant->genus;
            $forestry->name = $plant->name;
            $forestry->latin = $plant->latin;
            $forestry->number = $plant->number;
            $forestry->size = $plant->size;
            $forestry->type = $plant->type;
            $forestry->origin = $plant->origin;
            $forestry->source = $plant->source;
            $forestry->storage = $plant->storage;
            $forestry->description = $plant->description;
            $forestry->memo = $plant->memo;
            $forestry->keeper_id = $plant->keeper_id;
            $forestry->user_id = $plant->user_id;
            $forestry->asset_id = $plant->asset_id;
            $forestry->status = $plant->status;
            $forestry->save();

            $collectionImages = $plant->images;
            foreach($collectionImages as $collectionImage) {
                $path = $collectionImage->path;
                $newPath = str_replace('/plant', '/forestry', $path);
                rename(public_path($path), public_path($newPath));
                $collectionImage->collectible_type = Forestry::class;
                $collectionImage->collectible_id = $forestry->id;
                $collectionImage->path = $newPath;
                $collectionImage->thumb = $newPath;
                $collectionImage->save();
            }

            $plant->delete();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
