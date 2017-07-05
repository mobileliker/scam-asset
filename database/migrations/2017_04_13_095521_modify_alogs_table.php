<?php

/**
 * @version 0.1 修改ip为可空
 * @author： wuzhihui
 * @date： 2017/4/13
 * @description:
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Alog;

class ModifyAlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('alogs', function($table){
            $table->string('ip')->nullable()->change(); //设置ip为可空
            $table->text('content')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Alog::whereNull('ip')->update(['ip' => '']);//将ip=null设置为空字符防止回滚失败

        Schema::table('alogs', function($table){
            $table->string('ip')->change(); //设置ip为不可空
            $table->string('content', 2000)->change();
        });
    }
}
