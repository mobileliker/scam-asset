<?php

/**
 * 藏品图片迁移表
 * @version : 2.0
 * @author : wuzhihui
 * @date : 2017/9/6
 * @description :
 * （1）基本功能；（2017/9/6）
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('collectible_type')->comment('藏品类型');
            $table->integer('collectible_id')->comment('藏品id');
            $table->string('path')->comment('路径');
            $table->string('thumb')->comment('缩略图');
            $table->tinyInteger('cover')->comment('封面')->default(0); //0代表非封面，1代表封面
            $table->tinyInteger('status')->default(1)->comment('状态'); //状态，0为禁用，1为启用
            $table->softDeletes(); //软删除
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_images');
    }
}
