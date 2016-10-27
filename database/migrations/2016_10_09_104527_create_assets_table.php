<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->date('post_date'); //入账日期
            //$table->integer('number')->unique(); //单据号
            $table->tinyInteger('type');//类型
            $table->string('category_number'); //分类
            $table->string('name'); //藏品名称
            $table->string('serial')->nullable();//藏品编号
            $table->string('course');//经费科目
            $table->string('model'); //型号
            $table->string('size'); //规格
            $table->string('consumer_company')->default('scam'); //领用单位
            $table->string('factory'); //厂家
            $table->string('provider'); //供应商
            $table->string('country')->default('中国'); //国别
            $table->string('storage_location'); //存放地点
            $table->string('application'); //使用方向
            $table->string('invoice'); //发票号
            $table->string('purchase_number')->nullable(); //申购单号
            $table->date('purchase_date'); //购置日期
            $table->string('card'); //经费卡号
            $table->decimal('price','20','2'); //单价
            $table->integer('amount')->default(1); //数量
            $table->decimal('sum','20','2'); //金额
            $table->string('entry'); //录入
            $table->integer('consumer_id')->unsigned(); //领用
            $table->integer('handler_id')->unsigned(); //经手
            $table->text('memo')->nullable(); //备注
            $table->integer('user_id')->unsigned();
            $table->string('image')->nullable(); //图片
            $table->tinyInteger('status')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });


        Schema::table('assets', function ($table) {
            $table->foreign('consumer_id')->references('id')->on('users');
            $table->foreign('handler_id')->references('id')->on('users');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assets');
    }
}
