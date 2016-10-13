<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial');
            $table->string('name');
            $table->string('value');
            $table->integer('pid')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('categories', function($table) {
            $table->unique(['serial', 'deleted_at'], 'categories_serial_unique');
            $table->foreign('pid')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
