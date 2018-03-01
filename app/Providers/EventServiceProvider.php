<?php

/**
 * 事件容器
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/30
 * @description :
 * (1)添加日志事件；（2017/11/30）
 *
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/3/1
 * @description :
 * （1）添加附件管理的日志记录；（2018/3/1）
 */

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //'App\Events\SomeEvent' => [
        //    'App\Listeners\EventListener',
        //],
        'App\Events\AuthEvent' => [ //用户登录、用户退出事件
            'App\Listeners\AuthEventListener'
        ],
        'App\Events\FarmEvent' => [ //农具管理的事件
            'App\Listeners\FarmEventListener'
        ],
        'App\Events\RockEvent' => [ //岩石管理的事件
            'App\Listeners\RockEventListener'
        ],
        'App\Events\PlantEvent' => [ //植物管理的事件
            'App\Listeners\PlantEventListener'
        ],
        'App\Events\AnimalEvent' => [ //动物管理的事件
            'App\Listeners\AnimalEventListener'
        ],
        'App\Events\SoilEvent' => [ //土壤管理的事件
            'App\Listeners\SoilEventListener'
        ],
        'App\Events\SoilBigEvent' => [ //土壤段面管理的事件
            'App\Listeners\SoilBigEventListener'
        ],
        'App\Events\SoilSmallEvent' => [ //土壤纸盒管理的事件
            'App\Listeners\SoilSmallEventListener'
        ],
        'App\Events\AttachmentEvent' => [ //附件管理
            'App\Listeners\AttachmentEventListener'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
