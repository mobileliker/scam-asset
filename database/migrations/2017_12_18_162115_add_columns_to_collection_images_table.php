<?php

/**
 * 增加字段到图片表
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2017/12/18
 * @description :
 * (1)基本功能；（2017/12/18）
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToCollectionImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collection_images', function ($table) {
            $table->string('target')->after('collectible_id')->nullable()->comment('标签');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collection_images', function ($table){
            $table->dropColumn('target');
        });
    }
}
