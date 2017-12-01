<?php
/**
 * 通用的Model的Event的Listener
 * @version ： 0.2
 * @author　: wuzhihui linminghuan
 * @date : 2017/8/9
 * @description :
 * （1）基本功能；（2017/8/9）
 * （2）添加status和batchDelete的记录日志方法；（2017/9/7 linminghuan）
 *
 * @version: 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/1
 * @description :
 * (1) 修改了错误的命名，并添加了新的方法；（2017/12/1）
 */

namespace App\Listeners;

use App\Alog;
use App\Events\ModelEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class ModelEventListener
{
    protected $module; //模块名
    protected $name; //名称
    protected $value; //值

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    private function handleStore(ModelEvent $event)
    {
        $dataArr = $event->getData()->toArray();
        return Alog::log($this->module . '模块', Alog::OPERATE_CREATE, '添加' . $this->name . '为 ' . $dataArr[$this->value] . ' 的' . $this->module . '成功', $event->getIp());
    }

    private function handleUpdate(ModelEvent $event)
    {
        $dataArr = $event->getData()->toArray();
        return Alog::log($this->module . '模块', Alog::OPERATE_UPDATE, '修改' . $this->name . '为 ' . $dataArr[$this->value] . ' 的' . $this->module . '成功', $event->getIp());
    }

    private function handleDestroy(ModelEvent $event)
    {
        $dataArr = $event->getData()->toArray();
        return Alog::log($this->module . '模块', Alog::OPERATE_DESTROY, '删除' . $this->name . '为 ' . $dataArr[$this->value] . ' 的' . $this->module . '成功', $event->getIp());
    }

    private function handleStatus(ModelEvent $event)
    {
        $dataArr = $event->getData()->toArray();
        if ($dataArr['status'] == 0) {
            $statusT = '关闭或禁用';
            $operate = Alog::OPERATE_STATUS_OFF;
        } else {
            $statusT = '打开或启用';
            $operate = Alog::OPERATE_STATUS_ON;
        }
        $logText = $statusT . $this->name . '为' . $dataArr[$this->value] . '的' . $this->module . '成功';
        return Alog::log($this->module . '模块', $operate, $logText, $event->getIp());
    }

    public function handleBatchDelete(ModelEvent $event)
    {
        $data = $event->getData();
        $ids = implode(',', $data);
        return Alog::log($this->module . '模块', Alog::OPERATE_BATCHDELETE, '批量删除id为' . $ids . '的' . $this->module . '成功', $event->getIp());
    }

    public function handleImport(ModelEvent $event)
    {
        $dataArr = $event->getData()->toArray();
        return Alog::log($this->module . '模块', Alog::OPERATE_IMPORT, '导入' . $this->name . '为 ' . $dataArr[$this->value] . ' 的' . $this->module . '成功', $event->getIp());
    }

    public function handleSaveImage(ModelEvent $event)
    {
        $dataArr = $event->getData()->toArray();
        //\Log::info($dataAttr);
        return Alog::log($this->module . '模块', Alog::OPERATE_SAVEIMAGE, '添加' . $this->name . '为 ' . $dataArr['collectible'][$this->value] . ' 的' . $this->module . '图片: ' . $dataArr['path'] . ' 成功', $event->getIp());
    }

    public function handleDeleteImage(ModelEvent $event)
    {
        $dataArr = $event->getData()->toArray();
        //\Log::info($dataAttr);
        return Alog::log($this->module . '模块', Alog::OPERATE_SAVEIMAGE, '删除' . $this->name . '为 ' . $dataArr['collectible'][$this->value] . ' 的' . $this->module . '图片: ' . $dataArr['path'] . ' 成功', $event->getIp());
    }

    /**
     * Handle the event.
     *
     * @param  php $event
     * @return void
     */
    public function handle(ModelEvent $event)
    {
        if ($event->getOperate() == 'store') {
            $this->handleStore($event);
        } else if ($event->getOperate() == 'update') {
            $this->handleUpdate($event);
        } else if ($event->getOperate() == 'destroy') {
            $this->handleDestroy($event);
        } else if ($event->getOperate() == 'status') {
            $this->handleStatus($event);
        } else if ($event->getOperate() == 'batchDelete') {
            $this->handleBatchDelete($event);
        } else if($event->getOperate() == 'import') {
            $this->handleImport($event);
        } else if($event->getOperate() == 'saveImage') {
            $this->handleSaveImage($event);
        } else if($event->getOperate() == 'deleteImage') {
            $this->handleDeleteImage($event);
        }
    }
}