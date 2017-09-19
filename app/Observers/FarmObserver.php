<?php

/**
 * 农具Model观察器
 * @version ：2.0
 * @author : wuzhihui
 * @date : 2017/7/14
 * @description :
 * （1）完成基本功能；
 */

namespace App\Observers;

use App\Farm, App\Alog;
use DB;

class FarmObserver
{
    public function created(Farm $farm)
    {
        Alog::log('Farm', Alog::OPERATE_CREATE, $farm->toJson()); //记录日志
    }

    public function deleted(Farm $farm)
    {
        Alog::log('Farm', Alog::OPERATE_DELETE, $farm->toJson()); //记录日志
    }

    public function updated(Farm $farm)
    {
        Alog::log('Farm', Alog::OPERATE_UPDATE, $farm->toJson()); //记录日志
    }
}