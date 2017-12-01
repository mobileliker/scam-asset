<?php
/**
 * 通用的Model的Event
 * @version : 0.2
 * @author : wuzhihui
 * @date : 2017/8/9
 * @description :
 * （1）基本功能；（2017/8/9）
 */

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ModelEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private $operate; //操作
    private $ip; //IP
    private $data; //数据

    /**
     * @return mixed
     */
    public function getOperate()
    {
        return $this->operate;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($operate, $ip, $data)
    {
        $this->operate = $operate;
        $this->ip = $ip;
        $this->data = $data;
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