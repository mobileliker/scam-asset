<?php

/**
 * @version 2.0 系统初始化数据的填充器
 * @author: wuzhihui
 * @date: 2017/4/24
 * @description:
 * （1）创建系统的初始角色 & 功能;
 * （2）添加农具的相关功能；（2017/7/7）
 * （3）添加附件上传功能和农具数据导入功能；（2017/7/14）
 * （4）添加岩石管理的功能菜单；（2017/9/21）
 *
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/24
 * @description :
 * (1)添加土壤管理的菜单权限；（2017/11/27）
 * (2)添加动物管理的菜单权限；（2017/11/30）
 * (3)新增批量导入用户；（2017/12/6）
 * (4)精简代码，并完成权限控制的导入；（2017/12/14）
 *
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/3/1
 * @description :
 * （1）添加附件管理的权限控制；（2018/3/1）
 * （2）添加林业资源管理的权限控制；（2018/3/27）
 */

use Illuminate\Database\Seeder;
use App\User, App\Role, App\Permission, App\PermissionCategory;
use Illuminate\Support\Facades\Log;

class InitSeeder extends Seeder
{

    private function generateCategories($categories, $pcategory)
    {
        $ccategories = array();
        foreach ($categories as $name => $display_name) {
            $pc = PermissionCategory::firstOrCreate([
                'name' => $pcategory->name . '-' . $name,
                'display_name' => $display_name,
                'pid' => $pcategory->id
            ]);
            $ccategories[$name] = $pc;
        }
        return $ccategories;
    }

//    private function generatePermissions($permission_infos, $categories)
//    {
//
//    }
//
    private function generateCollection($roleName, $menuP, $category, $prefix, $params = [])
    {
        $permission_infos = Array(
            'Index' => [
                'display_name' => '列表',
                'resource' => '/',
            ],
            'Store' => [
                'display_name' => '新增保存',
                'resource' => '/',
            ],
            'Show' => [
                'display_name' => '详情',
                'resource' => '/%',
            ],
            'Edit' => [
                'display_name' => '编辑',
                'resource' => '/%/edit',
            ],
            'Update' => [
                'display_name' => '更新保存',
                'resource' => '/%',
            ],
            'Destroy' => [
                'display_name' => '删除',
                'resource' => '/%',
            ],
            'BatchDelete' => [
                'display_name' => '批量删除',
                'resource' => '/batch-delete',
            ],
            'Import' => [
                'display_name' => '导入',
                'resource' => '/import',
            ],
            'ShowImage' => [
                'display_name' => '显示图片',
                'resource' => '/%/image',
            ],
            'DeleteImage' => [
                'display_name' => '删除图片',
                'resource' => '/%/image/%',
            ],
            'SaveImage' => [
                'display_name' => '保存图片',
                'resource' => '/%/image',
            ],
            'Relate' => [
                'display_name' => '相似藏品',
                'resource' => '/:id/relate',
            ],
            'CameraList' => [
                'display_name' => '拍摄清单',
                'resource' => '/camera-list',
            ],
        );

        if (isset($params['only'])) {
            $permission_infos = array_only($permission_infos, $params['only']);
        }
        if (isset($params['extra'])) {
            $permission_infos = array_merge($permission_infos, $params['extra']);
        }

        $pids = array($menuP->id);
        foreach ($permission_infos as $name => $info) {
            $permission = Permission::firstOrCreate([
                'name' => $category->name . '-' . $name,
                'display_name' => $info['display_name'],
                'resource' => $prefix . $info['resource'],
                'permission_category_id' => $category->id,
            ]);
            $pids[] = $permission->id;
        }
        //Log::info($pids);

        $role = Role::firstOrCreate(['name' => $roleName, 'display_name' => $category->display_name . '角色']);
        $role->perms()->sync($pids);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menuPC = PermissionCategory::firstOrCreate(['name' => 'Menu', 'display_name' => '菜单']);
        $methodPC = PermissionCategory::firstOrCreate(['name' => 'Method', 'display_name' => '功能']);

        //一级菜单
        $menu1s = array(
            'Asset' => '固定资产',
            'Collection' => '藏品管理',
            'System' => '系统管理'
        );
        $menu1pcs = $this->generateCategories($menu1s, $menuPC);

        //二级菜单
        $menu2s = array(
            'Asset' => array(
                'Asset' => [
                    'display_name' => '资产管理',
                    'resource' => '/asset',
                ]
            ),
            'Collection' => array(
                'Farm' => [
                    'display_name' => '农具管理',
                    'resource' => '/collection/farm',
                ],
                'Rock' => [
                    'display_name' => '岩石管理',
                    'resource' => '/collection/rock',
                ],
                'Plant' => [
                    'display_name' => '植物管理',
                    'resource' => '/collection/plant'
                ],
                'Forestry' => [
                    'display_name' => '林业资源管理',
                    'resource' => '/collection/forestry'
                ],
                'Animal' => [
                    'display_name' => '动物管理',
                    'resource' => '/collection/animal',
                ],
                'Soil' => [
                    'display_name' => '土壤管理',
                    'resource' => '/collection/soil',
                ]
            ),
            'System' => array(
                'Alog' => [
                    'display_name' => '日志管理',
                    'resource' => '/alog'
                ],
                'User' => [
                    'display_name' => '用户管理',
                    'resource' => '/user'
                ],
                'Attachment' => [
                    'display_name' => '附件管理',
                    'resource' => '/system/attachment'
                ]
            )
        );

        $menu2ps = array();
        foreach ($menu2s as $key => $permission_infos) {
            foreach ($permission_infos as $name => $info) {
                $menu2ps[$name] = Permission::firstOrCreate([
                    'name' => $menu1pcs[$key]->name . '-' . $name,
                    'display_name' => $info['display_name'],
                    'resource' => $info['resource'],
                    'permission_category_id' => $menu1pcs[$key]->id,
                ]);
            }
        }

        //日志功能
        $alogPC = PermissionCategory::firstOrCreate(['name' => 'Method-Alog', 'display_name' => '日志管理']);
        $params = [
            'only' => [
                'Index',
            ],
        ];
        $this->generateCollection('Alog', $menu2ps['Alog'], $alogPC, '/api/alog', $params);

        //用户功能
        $userPC = PermissionCategory::firstOrCreate(['name' => 'Method-User', 'display_name' => '用户管理']);
        $params = [
            'only' => [
                'Index',
                'Store',
                'Edit',
                'Update',
                'Destroy',
                'BatchDelete',
            ],
            'extra' => [
                'Check' => [
                    'display_name' => '验证',
                    'resource' => '/Check',
                ],
            ]
        ];
        $this->generateCollection('User', $menu2ps['User'], $userPC, '/api/user', $params);

        //附件管理
        $attachmentPC = PermissionCategory::firstOrCreate(['name' => 'Method-Attachment', 'display_name' => '附件管理']);
        $params = [
            'only' => [
                'Index',
                'Destroy',
                'BatchDelete'
            ]
        ];
        $this->generateCollection('Attachment', $menu2ps['Attachment'], $attachmentPC, '/api/attachment', $params);

        //固定资产
        $assetPC = PermissionCategory::firstOrCreate(['name' => 'Method-Asset', 'display_name' => '固定资产管理']);
        $params = [
            'only' => [
                'Index',
                'Store',
                'Edit',
                'Update',
                'Destroy',
                'BatchDelete',
            ],
            'extra' => [
                'Export' => [
                    'display_name' => '导出',
                    'resource' => '/%/export',
                ],
                'BatchExport' => [
                    'display_name' => '批量导出',
                    'resource' => '/export',
                ],
                'Import' => [
                    'display_name' => '导入',
                    'resource' => '/import',
                ],
                'BatchPrint' => [
                    'display_name' => '报增单',
                    'resource' => '/print',
                ],
            ]
        ];
        $this->generateCollection('Asset', $menu2ps['Asset'], $assetPC, '/api/asset', $params);

        //藏品功能
        $collectionPC = PermissionCategory::firstOrCreate(['name' => 'Method-Collection', 'display_name' => '藏品管理']);
        $method1s = array(
            'Farm' => '农具管理',
            'Rock' => '岩石管理',
            'Plant' => '植物管理',
            'Forestry' => '林业资源管理',
            'Animal' => '动物管理',
            'Soil' => '土壤管理',
            'SoilBig' => '土壤段面管理',
            'SoilSmall' => '土壤纸盒管理',
        );
        $method1pcs = $this->generateCategories($method1s, $collectionPC);

        //农具管理
        $this->generateCollection('Farm', $menu2ps['Farm'], $method1pcs['Farm'], '/api/collection/farm');

        //岩石管理
        $this->generateCollection('Rock', $menu2ps['Rock'], $method1pcs['Rock'], '/api/collection/rock');

        //植物管理
        $this->generateCollection('Plant', $menu2ps['Plant'], $method1pcs['Plant'], '/api/collection/plant');

        //林业资源管理
        $this->generateCollection('Forestry', $menu2ps['Forestry'], $method1pcs['Forestry'], '/api/collection/forestry');

        //动物管理
        $this->generateCollection('Animal', $menu2ps['Animal'], $method1pcs['Animal'], '/api/collection/animal');

        //土壤管理
        $params = [
            'extra' => [
                'ShowImage2' => [
                    'display_name' => '显示图片2',
                    'resource' => '/%/image',
                ],
            ]
        ];
        $this->generateCollection('Soil', $menu2ps['Soil'], $method1pcs['Soil'], '/api/collection/soil', $params);

        //土壤段面管理
        $this->generateCollection('SoilBig', $menu2ps['Soil'], $method1pcs['SoilBig'], '/api/collection/soil/%/soil-big');

        //土壤纸盒管理
        $this->generateCollection('SoilSmall', $menu2ps['Soil'], $method1pcs['SoilSmall'], '/api/collection/soil/%/soil-big');


        $adminUser = User::firstOrNew(['name' => 'admin', 'email' => 'admin@scama.com']);
        $adminUser->password = bcrypt('nbb123456NBB');
        $adminUser->save();
        $roles = \App\Role::all();
        $adminUser->roles()->sync($roles);

        //新建批量管理用户
        $batchUser = User::firstOrNew(['name' => 'batch-user', 'email' => 'batch@scama.com']);
        $batchUser->password = bcrypt('nbb123456NBB');
        $batchUser->save();
    }
}
