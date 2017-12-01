<?php

/**
 * 农具管理事件监听器
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/1
 * @description :
 * (1)基本功能；（2017/12/1）
 */

namespace App\Listeners;

use App\Events\FarmEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FarmEventListener extends ModelEventListener
{
    protected $module = '农具';
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
     * @param  FarmEvent  $event
     * @return void
     */
    public function handle(FarmEvent $event)
    {
        parent::handle($event);
    }
}
