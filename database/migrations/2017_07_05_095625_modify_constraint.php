<?php

/**
 * @version 2.0 设置为约束仅在debug模式使用，并重名名外键添加前缀
 * @author: wuzhihui
 * @date: 2017/7/5
 * @description:
 *
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $prefix = config('database.connections.mysql.prefix');

        Schema::table('alogs', function ($table) use ($prefix) {
            $table->dropForeign('alogs_user_id_foreign');
            $table->dropIndex('alogs_user_id_foreign');
            if (config('app.debug')) $table->foreign('user_id', $prefix . 'alogs_user_id_foreign')->references('id')->on('users');
        });

        Schema::table('assets', function ($table) use ($prefix) {
            $table->dropForeign('assets_consumer_id_foreign');
            $table->dropIndex('assets_consumer_id_foreign');
            if (config('app.debug')) $table->foreign('consumer_id', $prefix . 'assets_consumer_id_foreign')->references('id')->on('users');

            $table->dropForeign('assets_handler_id_foreign');
            $table->dropIndex('assets_handler_id_foreign');
            if (config('app.debug')) $table->foreign('handler_id', $prefix . 'assets_handler_id_foreign')->references('id')->on('users');

            $table->dropForeign('assets_user_id_foreign');
            $table->dropIndex('assets_user_id_foreign');
            if (config('app.debug')) $table->foreign('user_id', $prefix . 'assets_user_id_foreign')->references('id')->on('users');
        });

        Schema::table('categories', function ($table) use ($prefix) {
            $table->dropForeign('categories_pid_foreign');
            $table->dropIndex('categories_pid_foreign');
            if (config('app.debug')) $table->foreign('pid', $prefix . 'categories_pid_foreign')->references('id')->on('categories');

            $table->dropUnique('categories_serial_unique');
        });

        Schema::table('infos', function ($table) use ($prefix) {
            $table->dropUnique('infos_key_unique');
        });

        Schema::table('invoices', function ($table) use ($prefix) {
            $table->dropUnique('invoices_number_unique');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     * 注意：删除外键的时候要顺便把索引一并删除掉，添加外键时会检测索引是否存在，存在则不另行创建
     */
    public function down()
    {
        $prefix = config('database.connections.mysql.prefix');

        Schema::table('invoices', function ($table) use ($prefix) {
            $table->unique('number');
        });

        Schema::table('infos', function ($table) use ($prefix) {
            $table->unique(['key', 'deleted_at'], 'infos_key_unique');
        });

        Schema::table('categories', function ($table) use ($prefix) {
            $table->unique(['serial', 'deleted_at'], 'categories_serial_unique');

            if (config('app.debug')) {
                $table->dropForeign($prefix . 'categories_pid_foreign');
                $table->dropIndex($prefix . 'categories_pid_foreign');
            }
            $table->foreign('pid')->references('id')->on('categories');
        });

        Schema::table('assets', function ($table) use ($prefix) {
            if (config('app.debug')) {
                $table->dropForeign($prefix . 'assets_user_id_foreign');
                $table->dropIndex($prefix . 'assets_user_id_foreign');
            }
            $table->foreign('user_id')->references('id')->on('users');

            if (config('app.debug')) {
                $table->dropForeign($prefix . 'assets_handler_id_foreign');
                $table->dropIndex($prefix . 'assets_handler_id_foreign');
            }
            $table->foreign('handler_id')->references('id')->on('users');

            if (config('app.debug')) {
                $table->dropForeign($prefix . 'assets_consumer_id_foreign');
                $table->dropIndex($prefix . 'assets_consumer_id_foreign');
            }
            $table->foreign('consumer_id')->references('id')->on('users');
        });

        Schema::table('alogs', function ($table) use ($prefix) {
            if (config('app.debug')) {
                $table->dropForeign($prefix . 'alogs_user_id_foreign');
                $table->dropIndex($prefix . 'alogs_user_id_foreign');
            }

            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
