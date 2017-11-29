<?php

/**
 * 土壤纸盒标本迁移表
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/27
 * @description :
 * (1)基本功能：（2017/11/27）
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoilSmallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $prefix = config('database.connections.mysql.prefix');
      Schema::create('soil_smalls', function (Blueprint $table) use ($prefix) {
          $table->increments('id');
          $table->integer('soil_id')->unsigned()->comment('土壤编号');
          $table->string('serial')->nullable()->comment('编号');
          $table->string('storage')->nullable()->comment('存放地点');

          $table->tinyInteger('status')->default(1)->comment('状态'); //状态，0为禁用，1为启用
          $table->softDeletes();
          $table->timestamps();

          if(config('app.debug')) {
            $table->foreign('soil_id', $prefix . 'soil_smalls_soil_id_foreign')->references('id')->on('soils');
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
        Schema::dropIfExists('soil_smalls');
    }
}
