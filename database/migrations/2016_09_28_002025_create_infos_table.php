<?php

/*
 * @version: 1.0 后台配置管理迁移表
 * @author: wuzhihui
 * @date: 2016/9/30
 * @description:
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key'); //主键
            $table->text('value'); //值
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('infos', function ($table) {
            $table->unique(['key', 'deleted_at'],'infos_key_unique');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infos');
    }
}
