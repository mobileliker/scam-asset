<?php

/**
 * 林业迁移表
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/3/27
 * @description :
 * (1)基本功能；（2018/3/27）
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForestriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('database.connections.mysql.prefix');
        Schema::create('forestries', function (Blueprint $table) use ($prefix){
            $table->increments('id');
            $table->string('category')->default('其他')->comment('种类');
            $table->date('input_date')->comment('进馆时间');
            $table->string('serial')->nullable()->comment('编号');
            $table->string('family')->nullable()->comment('科');
            $table->string('genus')->nullable()->comment('属');
            $table->string('name')->comment('名称');
            $table->string('latin')->nullable()->comment('拉丁名');
            $table->integer('number')->unsigned()->default(1)->comment('数量');
            $table->string('size')->nullable()->comment('尺寸');
            $table->string('type')->nullable()->comment('类型');
            $table->string('origin')->nullable()->comment('产地');
            $table->string('source')->nullable()->comment('来源');
            $table->string('storage')->nullable()->comment('存放地点');
            $table->text('description')->nullable()->comment('描述');
            $table->text('memo')->nullable()->comment('备注');

            $table->integer('keeper_id')->unsigned()->nullable()->comment('保管人');
            $table->integer('user_id')->unsigned()->comment('最近编辑人');
            $table->integer('asset_id')->unsigned()->nullable()->comment('是否固定资产');

            $table->tinyInteger('status')->default(1)->comment('状态'); //状态，0为禁用，1为启用
            $table->softDeletes();
            $table->timestamps();

            if (config('app.debug')) {
                $table->foreign('keeper_id', $prefix . 'forestries_keeper_foreign')->references('id')->on('users');
                $table->foreign('user_id', $prefix . 'forestries_user_id_foreign')->references('id')->on('users');
                $table->foreign('asset_id', $prefix . 'forestries_asset_id_foreign')->references('id')->on('users');
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
        Schema::dropIfExists('forestries');
    }
}
