<?php

/**
 * 添加odd_serail字段
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/1/12
 * @description :
 * （1）基本功能；（2018/1/12）
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyFarmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farms', function ($table) {
            $table->string('origin')->after('source')->nullable()->comment('产地');
            $table->string('odd_serial')->after('serial')->nullable()->comment('旧编号');
            $table->string('storage')->after('display')->nullable()->comment('存放地点');
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
            $table->dropColumn('storage');
            $table->dropColumn('odd_serial');
            $table->dropColumn('origin');
        });
    }
}
