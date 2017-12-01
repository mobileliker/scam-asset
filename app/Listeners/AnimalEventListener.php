<?php

/**
 * 动物管理事件监听器
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/1
 * @description :
 * (1)基本功能；（2017/12/1）
 */

namespace App\Listeners;

use App\Events\AnimalEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnimalEventListener extends ModelEventListener
{
    protected $module = '动物';
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
     * @param  AnimalEvent  $event
     * @return void
     */
    public function handle(AnimalEvent $event)
    {
        parent::handle($event);
    }
}
