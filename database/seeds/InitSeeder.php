<?php

/**
 * @version 2.0 系统初始化数据的填充器
 * @author: wuzhihui
 * @date: 2017/4/24
 * @description:
 * (1) 创建系统的初始角色 & 功能
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
        $commonMethodPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Method-Common', 'display_name' => '通用功能模块', 'pid' => $methodPermissionCategory->id]);
        $assetMethodPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Method-Asset', 'display_name' => '固定资产模块', 'pid' => $methodPermissionCategory->id]);
        $userMethodPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Method-User', 'display_name' => '用户管理模块', 'pid' => $methodPermissionCategory->id]);
        $alogMethodPermissionCategory = PermissionCategory::firstOrCreate(['name' => 'Method-Alog', 'display_name' => '日志模块', 'pid' => $methodPermissionCategory->id]);

        $commonSettingsMethodPermission = Permission::firstOrCreate(['name' => 'Method-Common-Settings', 'display_name' => '通用功能-Settings', 'resource' => '/api/user/{id}/settings' , 'permission_category_id' => $commonMethodPermissionCategory->id]);
        $commonMenuMethodPermission = Permission::firstOrCreate(['name' => 'Method-Common-Menu', 'display_name' => '通用功能-Menu','resource' => '/api/user/menu', 'permission_category_id' => $commonMethodPermissionCategory->id]);
        $commonImageMethodPermission = Permission::firstOrCreate(['name' => 'Method-Common-Image', 'display_name' => '通用功能-Image','resource' => '/api/image/update', 'permission_category_id' => $commonMethodPermissionCategory->id]);
        $commonIndexMethodPermission = Permission::firstOrCreate(['name' => 'Method-Common-Index', 'display_name' => '通用功能-Index','resource' => '/api', 'permission_category_id' => $commonMethodPermissionCategory->id]);

        $assetIndexMethodPermission = Permission::firstOrCreate(['name' => 'Method-Asset-Index', 'display_name' => '资产管理-Index','resource' => '/api/asset' ,'permission_category_id' => $assetMethodPermissionCategory->id]);
        $assetStoreMethodPermission = Permission::firstOrCreate(['name' => 'Method-Asset-Store', 'display_name' => '资产管理-Store', 'resource' => '/api/asset', 'permission_category_id' => $assetMethodPermissionCategory->id]);
        $assetEditMethodPermission = Permission::firstOrCreate(['name' => 'Method-Asset-Edit', 'display_name' => '资产管理-Edit','resource' => '/api/asset/{id}/edit' ,'permission_category_id' => $assetMethodPermissionCategory->id]);
        $assetUpdateMethodPermission = Permission::firstOrCreate(['name' => 'Method-Asset-Update', 'display_name' => '资产管理-Update', 'resource' => '/api/asset/{id}', 'permission_category_id' => $assetMethodPermissionCategory->id]);
        $assetDestroyMethodPermission = Permission::firstOrCreate(['name' => 'Method-Asset-Destroy', 'display_name' => '资产管理-Destroy', 'resource' => '/api/asset/{id}', 'permission_category_id' => $assetMethodPermissionCategory->id]);
        $assetExportMethodPermission = Permission::firstOrCreate(['name' => 'Method-Asset-Export', 'display_name' => '资产管理-Export','resource' => '/api/asset/{id}/export' ,'permission_category_id' => $assetMethodPermissionCategory->id]);
        $assetBatchExportMethodPermission = Permission::firstOrCreate(['name' => 'Method-Asset-BatchExport', 'display_name' => '资产管理-BatchExport', 'resource' => '/api/asset/export', 'permission_category_id' => $assetMethodPermissionCategory->id]);
        $assetBatchDeleteMethodPermission = Permission::firstOrCreate(['name' => 'Method-Asset-BatchDelete', 'display_name' => '资产管理-BatchDelete', 'resource' => '/api/asset/batch-delete', 'permission_category_id' => $assetMethodPermissionCategory->id]);
        $assetUserAllMethodPermission = Permission::firstOrCreate(['name' => 'Method-Asset-UserAll', 'display_name' => '资产管理-UserAll', 'resource' => '/api/user/all', 'permission_category_id' => $assetMethodPermissionCategory->id]);

        $userIndexMethodPermission = Permission::firstOrCreate(['name' => 'Method-User-Index', 'display_name' => '用户管理-Index', 'resource' => '/api/user', 'permission_category_id' => $userMethodPermissionCategory->id ]);
        $userStoreMethodPermission = Permission::firstOrCreate(['name' => 'Method-User-Store', 'display_name' => '用户管理-Store', 'resource' => '/api/user', 'permission_category_id' => $userMethodPermissionCategory->id]);
        $userEditMethodPermission = Permission::firstOrCreate(['name' => 'Method-User-Edit', 'display_name' => '用户管理-Edit', 'resource' => '/api/user/{id}/edit', 'permission_category_id' => $userMethodPermissionCategory->id]);
        $userUpdateMethodPermission = Permission::firstOrCreate(['name' => 'Method-User-Update', 'display_name' => '用户管理-Update', 'resource' => '/api/user/{id}', 'permission_category_id' => $userMethodPermissionCategory->id]);
        $userDestroyMethodPermission = Permission::firstOrCreate(['name' => 'Method-User-Destroy', 'display_name' => '用户管理-Destroy', 'resource' => '/api/user/{id}', 'permission_category_id' => $userMethodPermissionCategory->id]);
        $userBatchDeleteMethodPermission = Permission::firstOrCreate(['name' => 'Method-User-BatchDelete', 'display_name' => '用户管理-BatchDelete', 'resource' => '/api/user/batch-delete', 'permission_category_id' => $userMethodPermissionCategory->id]);
        $userCheckMethodPermission = Permission::firstOrCreate(['name' => 'Method-User-Check', 'display_name' => '用户管理-Check', 'resource' => '/api/user/check', 'permission_category_id' => $userMethodPermissionCategory->id]);
        $userRoleMethodPermission = Permission::firstOrCreate(['name' => 'Method-User-Role', 'display_name' => '用户管理-Role', 'resource' => '/api/role/all', 'permission_category_id' => $userMethodPermissionCategory->id]);

        $alogIndexMethodPermission = Permission::firstOrCreate(['name' => 'Method-Alog-Index', 'display_name' => '日志操作-Index', 'resource' => '/api/alog', 'permission_category_id' => $alogMethodPermissionCategory->id]);

        //普通用户角色
        $userRole = Role::firstOrCreate(['name' => 'User', 'display_name' => '单位用户']);
        $userRole->perms()->sync(array($assetPermission->id, $assetIndexMethodPermission->id));

        //管理员用户角色
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'display_name' => '管理员']);
        $adminRole->perms()->sync(array($assetPermission->id, $userPermission->id, $alogPermission->id));

        //通用功能角色
        $commonRole = Role::firstOrCreate(['name' => 'Common', 'display_name' => '通用功能角色']);
        $commonRole->perms()->sync(array($commonSettingsMethodPermission->id, $commonMenuMethodPermission->id, $commonImageMethodPermission->id, $commonIndexMethodPermission->id));

        //固定资产管理角色
        $assetRole = Role::firstOrCreate(['name' => 'Asset', 'display_name' => '固定资产管理角色']);
        $assetRole->perms()->sync(array($assetIndexMethodPermission->id, $assetStoreMethodPermission->id, $assetEditMethodPermission->id, $assetUpdateMethodPermission->id, $assetDestroyMethodPermission->id, $assetExportMethodPermission->id, $assetBatchExportMethodPermission->id, $assetBatchDeleteMethodPermission->id, $assetUserAllMethodPermission->id));

        //用户管理角色
        $userMethodRole = Role::firstOrCreate(['name' => 'UserMethod', 'display_name' => '用户管理角色']);
        $userMethodRole->perms()->sync(array($userIndexMethodPermission->id, $userStoreMethodPermission->id, $userEditMethodPermission->id, $userUpdateMethodPermission->id, $userDestroyMethodPermission->id, $userBatchDeleteMethodPermission->id, $userCheckMethodPermission->id, $userRoleMethodPermission->id));

        //日志操作角色
        $alogMethodRole = Role::firstOrCreate(['name' => 'AlogMethod', 'display_name' => '日志操作角色']);
        $alogMethodRole->perms()->sync(array($alogIndexMethodPermission->id));

        //新建普通用户
        $user = User::firstOrNew(['name' => 'user', 'email' => 'user@scama.com']);
        $user->password = bcrypt('123456');
        $user->save();
        if(!$user->hasRole($userRole->name)) $user->attachRole($userRole);
        if(!$user->hasRole($commonRole->name)) $user->attachRole($commonRole);
        if(!$user->hasRole($assetRole->name)) $user->attachRole($assetRole);

        //新建管理员用户
        $adminUser = User::firstOrNew(['name' => 'admin', 'email' => 'admin@scama.com']);
        $adminUser->password = bcrypt('123456');
        $adminUser->save();
        if(!$adminUser->hasRole($adminRole->name)) $adminUser->attachRole($adminRole);
        if(!$adminUser->hasRole($commonRole->name)) $adminUser->attachRole($commonRole);
        if(!$adminUser->hasRole($assetRole->name)) $adminUser->attachRole($assetRole);
        if(!$adminUser->hasRole($userMethodRole->name)) $adminUser->attachRole($userMethodRole);
        if(!$adminUser->hasRole($alogMethodRole->name)) $adminUser->attachRole($alogMethodRole);

    }
}
