<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Modify3FarmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farms', function ($table) {
            $table->text('size')->nullable()->comment('尺寸')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farms', function ($table) {
            $table->string('size')->nullable()->comment('尺寸')->change();
        });
    }
}
