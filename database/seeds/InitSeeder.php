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

        //菜单权限分类以及权限
        $menuPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Menu', 'display_name' => '菜单项']);
        $assetPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Menu-Asset', 'display_name' => '固定资产管理', 'pid' => $menuPermissionCategory->id]);
        $systemPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Menu-System', 'display_name' => '系统管理', 'pid' => $menuPermissionCategory->id]);

        $assetPermission = Permission::firstOrCreate(['name' => 'Menu-Asset-Asset', 'display_name' => '资产管理','resource' => '/asset', 'permission_category_id' => $assetPermissionCategory->id]);
        $userPermission = Permission::firstOrCreate(['name' => 'Menu-System-User', 'display_name' => '用户管理','resource' => 'user', 'permission_category_id' => $systemPermissionCategory->id]);
        $alogPermission = Permission::firstOrCreate(['name' => 'Menu-System-Alog', 'display_name' => '操作日志', 'resource' => 'alog','permission_category_id' => $systemPermissionCategory->id]);

        //功能权限分类以及权限
        $methodPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Method', 'display_name' => '功能']);
        $assetMethodPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Method-Asset', 'display_name' => '固定资产模块', 'pid' => $methodPermissionCategory->id]);
        $userMethodPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Method-User', 'display_name' => '用户管理模块', 'pid' => $methodPermissionCategory->id]);
        $alogMethodPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Method-Alog', 'display_name' => '日志模块', 'pid' => $methodPermissionCategory->id]);

        $assetIndexMethodPermission = Permission::firstOrCreate(['name' => 'Method-Asset-Index', 'display_name' => '资产管理-Index','resource' => '/api/asset' ,'permission_category_id' => $assetMethodPermissionCategory->id]);
        $assetEditMethodPermission = Permission::firstOrCreate(['name' => 'Method-Asset-Edit', 'display_name' => '资产管理-Edit','resource' => '/api/asset/{id}' ,'permission_category_id' => $assetMethodPermissionCategory->id]);

        //普通用户角色
        $userRole = Role::firstOrCreate(['name' => 'User', 'display_name' => '单位用户']);
        $userRole->perms()->sync(array($assetPermission->id, $assetIndexMethodPermission->id));

        //管理员用户角色
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'display_name' => '管理员']);
        $adminRole->perms()->sync(array($assetPermission->id, $userPermission->id, $alogPermission->id, $assetIndexMethodPermission->id));

        //新建普通用户
        $user = User::firstOrNew(['name' => 'user', 'email' => 'user@scama.com']);
        $user->password = bcrypt('123456');
        $user->save();
        if(!$user->hasRole($userRole->name)) $user->attachRole($userRole);

        //新建管理员用户
        $adminUser = User::firstOrNew(['name' => 'admin', 'email' => 'admin@scama.com']);
        $adminUser->password = bcrypt('123456');
        $adminUser->save();
        if(!$adminUser->hasRole($adminRole->name)) $adminUser->attachRole($adminRole);

    }
}
