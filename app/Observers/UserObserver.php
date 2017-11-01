<?php

/**
 * @version 1.0 Asset的观察者
 * @date: 2017/4/13
 * @author: wuzhihui
 * @description:
 * （1）添加deleting事件，用于删除关联数据；（2017/7/6）
 */

namespace App\Observers;

use App\User, App\Alog;
use DB;

class UserObserver
{
    public function created(User $user)
    {
        Alog::log('User', Alog::OPERATE_CREATE, $user->toJson()); //记录日志
    }

    public function deleted(User $user)
    {
        Alog::log('User', Alog::OPERATE_DELETE, $user->toJson()); //记录日志
    }

    public function updated(User $user)
    {
        Alog::log('User', Alog::OPERATE_UPDATE, $user->toJson()); //记录日志
    }
    
    public function deleting(User $user)
    {
        DB::table('role_user')->where('user_id', '=', $user->id)->delete(); //删除管理的角色相关数据
    }
}