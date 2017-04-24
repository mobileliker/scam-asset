<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PermissionCategory, App\Permission;
use Auth;

class UserController extends Controller
{
    public function menu()
    {
        $user = Auth::user();

        $menuPermissionCategories = PermissionCategory::join('permission_categories as p', function($join) {
            $join->on('p.id', '=', 'permission_categories.pid')->whereNull('p.deleted_at')->where('p.name', '=', 'Menu');
        })->select('permission_categories.*')->get();

        $res = array();
        foreach($menuPermissionCategories as $permissionCategory){
            $permissions = Permission::where('permissions.permission_category_id', '=', $permissionCategory->id)->get();

            $allow_permissions = array();
            foreach($permissions as $permission){
                if($user->can($permission->name)){
                    $allow_permissions[] = $permission;
                }
            }
            if(count($allow_permissions) > 0){
                $permissionCategory->data = $allow_permissions;
                $res[] = $permissionCategory;
            }

        }
        return $res;
















        return $menuPermissionCategories;
    }
}
