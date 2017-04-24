<?php
/*
 * @version 1.0 permission category migration
 * @author: wuzhihui
 * @date: 2016/10/6
 *
 * @version 1.1 解决foreign可能重复的问题&&加入注释&&加入status
 * @author: wuzhihui
 * @date: 2017/3/9
 *
 * @version 2.0
 * @author: wuzhihui
 * @date: 2017/4/24
 * @description:
 * (1)修改entrust的permissions加入permission_category_id
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreatePermissionCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_categories', function (Blueprint $table) {
            $prefix = config('database.connections.mysql.prefix');
            $table->increments('id');
            $table->integer('pid')->unsigned()->nullable()->comment('父id'); //父id
            $table->string('name')->comment('名称'); //名称
            $table->string('display_name')->comment('显示名字'); //显示名字
            $table->text('description')->nullable()->comment('描述'); //描述
            $table->text('memo')->nullable()->comment('备注'); //备注
            $table->tinyInteger('status')->default(1)->comment('状态'); //状态，0为禁用，1为启用
            $table->softDeletes(); //软删除
            $table->timestamps();

            $table->foreign('pid', $prefix.'permission_categories_pid_foreign')->references('id')->on('permission_categories');
        });

        Schema::table('permissions', function($table) {
            $prefix = config('database.connections.mysql.prefix');
            $table->integer('permission_category_id')->unsigned()->after('id')->comment('权限分类id');
            $table->string('resource')->after('display_name')->comment('资源');
            $table->string('method')->after('resource')->default('GET')->comment('方法');

            $table->foreign('permission_category_id', $prefix.'permissions_permission_category_id_foreign')->references('id')->on('permission_categories');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function($table) {
            $prefix = config('database.connections.mysql.prefix');
            $table->dropForeign($prefix.'permissions_permission_category_id_foreign');

            $table->dropColumn('method');
            $table->dropColumn('resource');
            $table->dropColumn('permission_category_id');
        });

        Schema::drop('permission_categories');
    }
}