<?php

/**
 * 岩石管理事件监听器
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/1
 * @description :
 * (1)基本功能；（2017/12/1）
 */

namespace App\Listeners;

use App\Events\RockEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Listeners\ModelEventListener;

class RockEventListener extends ModelEventListener
{
    protected $module = '岩石';
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
     * @param  RockEvent  $event
     * @return void
     */
    public function handle(RockEvent $event)
    {
        parent::handle($event);
    }
}
