<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->date('post_date'); //入账日期
            $table->integer('number')->unique(); //单据号
            $table->string('name'); //藏品名称
            $table->string('serial');//藏品编号
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
            $table->string('purchase_number'); //申购单号
            $table->date('purchase_date'); //购置日期
            $table->string('card'); //经费卡号
            $table->decimal('price','20','2'); //单价
            $table->integer('amount')->default(1); //数量
            $table->decimal('sum','20','2'); //金额
            $table->string('entry'); //录入
            $table->string('consumer'); //领用
            $table->string('handler'); //经手
            $table->text('memo')->nullable(); //备注
            $table->integer('user_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
