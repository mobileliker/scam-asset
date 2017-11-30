<?php

/**
 * 土壤采集迁移表
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/24
 * @description :
 * (1)基本功能；（2017/11/24)
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('database.connections.mysql.prefix');
        Schema::create('soils', function (Blueprint $table) use ($prefix) {
            $table->increments('id');
            $table->date('input_date')->comment('进馆日期');
            $table->string('name')->comment('名称');
            $table->string('ename')->nullable()->comment('英文名称');
            $table->string('region')->nullable()->comment('地区');
            $table->string('serial')->nullable()->comment('编号');
            $table->string('origin')->nullable()->comment('采集地点');
            $table->string('location')->nullable()->comment('经纬度');
            $table->double('altitude')->nullable()->comment('海拔');
            $table->string('terrain')->nullable()->comment('地形');
            $table->string('gradient')->nullable()->comment('坡度');
            $table->string('matrix')->nullable()->comment('母质');
            $table->string('vegetation')->nullable()->comment('植被');
            $table->string('use_status')->nullable()->comment('利用状况');
            $table->string('depth')->nullable()->comment('土层深度');
            $table->string('collecter')->nullable()->comment('采集人');
            $table->text('description')->nullable()->comment('描述');
            $table->string('memo')->nullable()->comment('备注');

            $table->integer('keeper_id')->unsigned()->nullable()->comment('保管人');
            $table->integer('user_id')->unsigned()->comment('最近编辑人');
            $table->integer('asset_id')->unsigned()->nullable()->comment('是否固定资产');

            $table->tinyInteger('status')->default(1)->comment('状态'); //状态，0为禁用，1为启用
            $table->softDeletes();
            $table->timestamps();

            if (config('app.debug')) {
              $table->foreign('keeper_id', $prefix . 'soil_keeper_foreign')->references('id')->on('users');
              $table->foreign('user_id', $prefix . 'soil_user_id_foreign')->references('id')->on('users');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('soils');
    }
}
