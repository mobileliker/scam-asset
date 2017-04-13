<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Observers\AssetObserver;
use App\Asset;


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
