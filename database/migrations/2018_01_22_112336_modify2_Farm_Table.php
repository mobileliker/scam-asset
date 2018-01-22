<?php

/**
 * 新增父分类到farms表
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/1/22
 * @description :
 * （1）基本功能；（2018/1/22）
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Modify2FarmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farms', function ($table) {
            $table->string('p_category')->after('id')->nullable()->comment('父分类');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farms', function ($table){
            $table->dropColumn('p_category');
        });
    }
}
