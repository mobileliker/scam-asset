<?php

/**
 * 附件表填充器
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/2/28
 * @description :
 * (1)初始功能；（2018/2/28）
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('database.connections.mysql.prefix');
        Schema::create('attachments', function (Blueprint $table) use ($prefix){
            $table->increments('id');
            $table->string('name')->comment('文件名');
            $table->string('path')->comment('路径');
            $table->integer('user_id')->unsigned()->comment('上传人');
            $table->tinyInteger('status')->default(1)->comment('状态'); //状态，0为禁用，1为启用
            $table->timestamps();

            if(config('app.debug')) {
                $table->foreign('user_id', $prefix . 'attachments_user_id_foreign')->references('id')->on('users');
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
        Schema::dropIfExists('attachments');
    }
}
