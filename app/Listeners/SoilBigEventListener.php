<?php

/**
 * 土壤段面管理事件监听器
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/1
 * @description :
 * (1)基本功能；（2017/12/1）
 * (2)修改日志详情的显示内容；（2017/12/12）
 */

namespace App\Listeners;

use App\Events\SoilBigEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SoilBigEventListener extends ModelEventListener
{
    protected $module = '土壤段面';
    protected $name = '编号';
    protected $value = 'serial';

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
