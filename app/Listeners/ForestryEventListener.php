<?php

/**
 * 林业资源管理事件监听器
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/3/27
 * @description :
 * (1)基本功能；（2018/3/27）
 */

namespace App\Listeners;

use App\Events\ForestryEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForestryEventListener extends ModelEventListener
{
    protected $module = '植物';
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
     * @param  ForestryEvent  $event
     * @return void
     */
    public function handle(ForestryEvent $event)
    {
        parent::handle($event);
    }
}
