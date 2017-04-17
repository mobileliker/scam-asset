<?php

/**
 * @version 1.0 Asset的观察者
 * @date: 2017/4/13
 * @author: wuzhihui
 * @description:
 *
 */

namespace App\Observers;

use App\User, App\Alog;

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

}