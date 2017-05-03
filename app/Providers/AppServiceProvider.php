<?php

/**
 * @version : 2.0 添加SQL的观察者
 * @author : wuzhihui
 * @date : 2017/4/17
 * @description:
 *
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Observers\AssetObserver , App\Observers\UserObserver;
use App\Asset, App\User;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        //DB::listen(function ($query) {
        //    // $query->sql
        //    // $query->bindings
        //    // $query->time
        //    Log::info($query->sql);
        //});

        //注册观察者
        //Asset::observe(AssetObserver::class);
        //User::observe(UserObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
