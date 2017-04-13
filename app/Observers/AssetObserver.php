<?php

/**
 * @version 1.0 Asset的观察者
 * @date: 2017/4/13
 * @author: wuzhihui
 * @description:
 *
 */

namespace App\Observers;

use App\Asset, App\Alog;

class AssetObserver
{
    public function created(Asset $asset)
    {
        Alog::log('Asset', Alog::OPERATE_CREATE, $asset->toJson()); //记录日志
    }

    public function deleted(Asset $asset)
    {
        Alog::log('Asset', Alog::OPERATE_DELETE, $asset->toJson()); //记录日志
    }

    public function updated(Asset $asset)
    {
        Alog::log('Asset', Alog::OPERATE_UPDATE, $asset->toJson()); //记录日志
    }

}