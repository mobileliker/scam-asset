<?php

/**
 * 土壤管理事件监听器
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/1
 * @description :
 * (1)基本功能；（2017/12/1）
 */

namespace App\Listeners;

use App\Events\SoilEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SoilEventListener extends ModelEventListener
{
    protected $module = '土壤';
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
     * @param  SoilEvent  $event
     * @return void
     */
    public function handle(SoilEvent $event)
    {
        parent::handle($event);
    }
}
