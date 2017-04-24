<?php

/**
 * @version 2.0 系统初始化数据的填充器
 * @author: wuzhihui
 * @date: 2017/4/24
 * @description:
 * (1) 创建系统的初始角色
 */

use Illuminate\Database\Seeder;
use App\User, App\Role, App\Permission, App\PermissionCategory;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menuPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Menu', 'display_name' => '菜单项']);
        $assetPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Menu-Asset', 'display_name' => '固定资产管理', 'pid' => $menuPermissionCategory->id]);
        $systemPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Menu-System', 'display_name' => '系统管理', 'pid' => $menuPermissionCategory->id]);

        $methodPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Method', 'display_name' => '功能']);

        $assetPermision = Permission::firstOrCreate(['name' => 'Menu-Asset-Asset', 'display_name' => '资产管理','resource' => '/asset', 'permission_category_id' => $assetPermissionCategory->id]);
        $userPermission = Permission::firstOrCreate(['name' => 'Menu-System-User', 'display_name' => '用户管理','resource' => 'user', 'permission_category_id' => $systemPermissionCategory->id]);
        $alogPermission = Permission::firstOrCreate(['name' => 'Menu-System-Alog', 'display_name' => '操作日志', 'resource' => 'alog','permission_category_id' => $systemPermissionCategory->id]);

        $userRole = Role::firstOrCreate(['name' => 'User', 'display_name' => '单位用户']);
        $userRole->attachPermission($assetPermision);

        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'display_name' => '管理员']);
        $adminRole->attachPermission($assetPermision);
        $adminRole->attachPermission($userPermission);
        $adminRole->attachPermission($alogPermission);

        $user = User::firstOrCreate(['name' => 'admin', 'email' => 'admin@scama.com', 'password' => bcrypt('123456')]);
        $user->attachRole($adminRole);
    }
}
