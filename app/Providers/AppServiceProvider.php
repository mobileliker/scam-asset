<?php

/**
 * @version : 2.0 添加SQL的观察者
 * @author : wuzhihui
 * @date : 2017/4/17
 * @description:
 * （1）添加农具观察器；（2017/7/14）
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Observers\AssetObserver , App\Observers\UserObserver;
use App\Asset, App\User;
use App\Observers\FarmObserver;
use App\Farm;


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
        Asset::observe(AssetObserver::class);
        User::observe(UserObserver::class);
        Farm::observe(FarmObserver::class);
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
