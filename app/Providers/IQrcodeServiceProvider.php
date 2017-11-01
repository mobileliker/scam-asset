<?php

/**
 * @version 2.0
 * @author: wuzhihui
 * @date: 2017/7/3
 * @description:
 * 
 */

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Utils\IQrcode;

class IQrcodeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('iqrcode',function(){
            return new IQrcode; //仅单例
        });
    }
}
