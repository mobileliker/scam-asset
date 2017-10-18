<?php

/**
 * 岩石迁移表
 * @version 0.2
 * @author :wuzhihui
 * @date : 2017/9/21
 * @description :
 * （1）基本功能；（2017/9/21）
 * （2）将分类有category改为classification；（2017/9/30）
 * （3）添加产地、备注、状态字段；（2017/10/18）
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('database.connections.mysql.prefix');
        Schema::create('rocks', function (Blueprint $table) use ($prefix){
            $table->increments('id');
            $table->integer('category_id')->unsigned()->nullable()->comment('类别');
            $table->string('name')->comment('名称');
            $table->string('ename')->nullable()->comment('英文名');
            $table->date('input_date')->comment('进馆时间');
            $table->string('serial')->nullable()->comment('编号');
            $table->string('classification')->nullable()->comment('分类');
            $table->string('feature')->nullable()->comment('特征');
            $table->string('origin')->nullable()->comment('产地');
            $table->text('description')->nullable()->comment('描述');
            $table->integer('keeper_id')->unsigned()->nullable()->comment('保管人');
            $table->integer('user_id')->unsigned()->comment('最近编辑人');
            $table->integer('asset_id')->unsigned()->nullable()->comment('是否固定资产');
            $table->text('memo')->nullable()->comment('备注');
            $table->tinyInteger('status')->default(1)->comment('状态'); //状态，0为禁用，1为启用
            $table->softDeletes();
            $table->timestamps();

            if (config('app.debug')) {
                $table->foreign('keeper_id', $prefix . 'rocks_keeper_foreign')->references('id')->on('users');
                $table->foreign('user_id', $prefix . 'rocks_user_id_foreign')->references('id')->on('users');
                $table->foreign('asset_id', $prefix . 'rocks_asset_id_foreign')->references('id')->on('users');
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
        Schema::dropIfExists('rocks');
    }
}
