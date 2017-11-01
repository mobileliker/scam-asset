<?php

/**
 * Farms表迁移表
 * @version: 2.0
 * @author: wuzhihui
 * @date: 2017/7/6
 * @description:
 *
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('database.connections.mysql.prefix');

        Schema::create('farms', function (Blueprint $table) use ($prefix) {
            $table->increments('id');
            $table->string('category')->nullable()->comment('分类');
            $table->string('name')->comment('名称');
            $table->tinyInteger('number')->unsigned()->default(1)->comment('数量'); //预留不启用
            $table->date('input_date')->comment('进馆时间');
            $table->string('source')->nullable()->comment('来源');
            $table->text('description')->nullable()->comment('描述');
            $table->string('size')->nullable()->comment('尺寸');
            $table->string('serial')->comment('编号');
            $table->text('memo')->nullable()->comment('备注栏');
            $table->string('display')->nullable()->comment('展示区域');
            $table->tinyInteger('status')->default(1)->comment('状态'); //状态，0为禁用，1为启用
            $table->integer('keeper_id')->unsigned()->nullable()->comment('保管人');
            $table->integer('user_id')->unsigned()->comment('最近编辑人');
            $table->integer('asset_id')->unsigned()->nullable()->comment('是否固定资产');
            $table->softDeletes();
            $table->timestamps();

            if (config('app.debug')) {
                $table->foreign('keeper_id', $prefix . 'farms_keeper_foreign')->references('id')->on('users');
                $table->foreign('user_id', $prefix . 'farms_user_id_foreign')->references('id')->on('users');
                $table->foreign('asset_id', $prefix . 'farams_asset_id_foreign')->references('id')->on('users');
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
        Schema::dropIfExists('farms');
    }
}
