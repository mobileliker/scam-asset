<?php

/**
 * 岩石类别迁移表
 * @version : 0.2
 * @author : wuzhihui
 * @date : 2017/9/21
 * @description :
 * （1）基本功能；（2017/9/21）
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRockCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rock_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->unsigned()->nullable()->comment('父分类');
            $table->string('serial')->comment('编号');
            $table->string('name')->comment('名称');
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
        Schema::dropIfExists('rock_categories');
    }
}
