<?php

/**
 * 土壤段面管理事件监听器
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/1
 * @description :
 * (1)基本功能；（2017/12/1）
 */

namespace App\Listeners;

use App\Events\SoilBigEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SoilBigEventListener extends ModelEventListener
{
    protected $module = '土壤段面';
    protected $name = '名称';
    protected $value = 'name';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SoilBigEvent  $event
     * @return void
     */
    public function handle(SoilBigEvent $event)
    {
        parent::handle($event);
    }
}
