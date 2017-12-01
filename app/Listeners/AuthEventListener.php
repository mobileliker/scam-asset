<?php

/**
 * AuthEvent的Listener
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/30
 * @description :
 * （1）完成基本功能；（2017/11/30）
 */

namespace App\Listeners;

use App\Events\AuthEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Alog;

class AuthEventListener
{
    private $module = '登录模块';

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
     * Auth事件登录处理
     * @param AuthEvent $event
     * @return mixed
     */
    private function handleLogin(AuthEvent $event)
    {
        return Alog::log($this->module, Alog::OPERATE_LOGIN, 'V2-登录成功', $event->getIp());
    }
    /**
     * Auth事件登出处理
     * @param AuthEvent $event
     * @return mixed
     */
    private function handleLogout(AuthEvent $event)
    {
        return Alog::log($this->module, Alog::OPERATE_LOGOUT, 'V2-退出成功', $event->getIp());
    }

    /**
     * Handle the event.
     *
     * @param  AuthEvent  $event
     * @return void
     */
    public function handle(AuthEvent $event)
    {
        if ($event->getOperate() == 'login') {
            return $this->handleLogin($event);
        }else if($event->getOperate() == 'logout'){
            return $this->handleLogout($event);
        }
    }
}
