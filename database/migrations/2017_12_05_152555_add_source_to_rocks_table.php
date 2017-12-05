<?php

/**
 * 增加来源到岩石表
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/5
 * @description :
 * (1)基本功能；（2017/12/5）
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSourceToRocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rocks', function ($table) {
          $table->string('source')->after('description')->nullable()->comment('来源');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rocks', function ($table){
          $table->dropColumn('source');
        });
    }
}
