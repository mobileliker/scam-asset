<?php

/**
 * Auth事件
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/30
 * @description :
 * （1）完成基本功能；（2017/11/30）
 */

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AuthEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $operate;
    private $ip;

    public function getOperate()
    {
        return $this->operate;
    }

    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($operate, $ip)
    {
        $this->operate = $operate;
        $this->ip = $ip;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
