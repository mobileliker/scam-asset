<?php

/**
 * @version 2.0
 * @author: wuzhihui
 * @date: 2017/7/3
 * @description:
 *（1）去除原有的管理员、普通用户的授权代码;（2017/7/3）
 */

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

//        Gate::define('admin', function ($user) {
//            return ($user->type == User::TYPE_ADMIN);
//        });
    }
}
