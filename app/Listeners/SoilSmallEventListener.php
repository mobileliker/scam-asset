<?php

/**
 * 土壤纸盒管理事件监听器
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/1
 * @description :
 * (1)基本功能；（2017/12/1）
 * (2)修改日志详情的显示内容；（2017/12/12）
 */

namespace App\Listeners;

use App\Events\SoilSmallEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SoilSmallEventListener extends ModelEventListener
{
    protected $module = '土壤纸盒';
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
     * @param  SoilSmallEvent  $event
     * @return void
     */
    public function handle(SoilSmallEvent $event)
    {
        parent::handle($event);
    }
}
