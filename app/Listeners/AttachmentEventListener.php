<?php

/**
 * 附件模块事件监听器
 * @version ： 2.0.3
 * @author : wuzhihui
 * @date : 2018/3/1
 * @description :
 * (1)基础功能；（2018/3/1）
 */

namespace App\Listeners;

use App\Events\AttachmentEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Listeners\ModelEventListener;

class AttachmentEventListener extends ModelEventListener
{
    protected $module = '附件';
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
     * @param  AttachmentEvent  $event
     * @return void
     */
    public function handle(AttachmentEvent $event)
    {
        parent::handle($event);
    }
}
