<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class IQrcodeClass extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'iqrcode';
    }
}