<?php

/**
 * 增加字段到土壤表
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2017/12/18
 * @description :
 * (1)基本功能；（2017/12/18）
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToSoilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('soils', function ($table) {
            $table->string('frozen')->after('depth')->nullable()->comment('冻土深度');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('soils', function ($table){
            $table->dropColumn('frozen');
        });
    }
}
