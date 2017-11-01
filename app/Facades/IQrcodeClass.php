<?php

/**
 * @version 1.0
 * @author: wuzhihui
 * @date: 2017/7/3
 * @description:
 * 
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class IQrcodeClass extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'iqrcode';
    }
}