<?php

/**
 * 添加Phylumn字段到animals表中
 * @version : 2.0.3
 * @author : wuzhihui
 * @description :
 * (1)基本功能；（2018/4/10）
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPhylumToAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animals', function ($table) {
            $table->string('phylum')->nullable()->after('serial')->comment('门');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animals', function ($table) {
            $table->dropColumn('phylum');
        });
    }
}
