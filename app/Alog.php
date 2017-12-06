<?php

/**
 * Alog的Model类
 * @version 2.0
 * @author: wuzhihui
 * @date: 2017/7/3
 * @description:
 *
 * @version 2.2
 * @author : wuzhihui
 * @description:
 * (1)优化日志的显示；（2017/11/30）
 *
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/12/1
 * @description :
 * (1)添加获取保存或者更新的操作字符串的静态方法、并添加新的操作类型；（2017/12/1）
 * (2)新增使用后台批量导入数据时的日志记录；（2017/12/6）
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Auth, date;

class Alog extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];  //开启deleted_at字段
    protected $fillable = ['log_time', 'user_id', 'module', 'operate', 'content', 'ip'];

    const OPERATE_CREATE = 0;
    const OPERATE_DESTROY = 1;
    const OPERATE_UPDATE = 2;
    const OPERATE_QUERY = 3;
    const OPERATE_SHOW = 4;
    const OPERATE_LOGIN = 5;
    const OPERATE_LOGOUT = 6;
    const OPERATE_BATCHDELETE = 7;
    const OPERATE_IMPORT = 8;
    const OPERATE_SAVEIMAGE = 9;
    const OPERATE_DELETEIMAGE = 10;

    const OPERATE = [
        self::OPERATE_CREATE => '新增',
        self::OPERATE_DESTROY => '删除',
        self::OPERATE_UPDATE => '修改',
        self::OPERATE_QUERY => '查询',
        self::OPERATE_SHOW => '查看',
        self::OPERATE_LOGIN => '登录',
        self::OPERATE_LOGOUT => '退出',
        self::OPERATE_BATCHDELETE => '批量删除',
        self::OPERATE_IMPORT => '导入',
        self::OPERATE_SAVEIMAGE => '保存图片',
        self::OPERATE_DELETEIMAGE => '删除图片',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public static function log($module, $operate, $content, $ip = null)
    {
        $user = Auth::user();
        if($user != null){
          return Alog::create([
              'log_time' => date('Y-m-d H:i:s', time()),
              'user_id' => $user->id,
              'module' => $module,
              'operate' => $operate,
              'content' => '用户 ' . $user->name . ' : ' . $content,
              'ip' => $ip,
          ]);
        } else { //用户未登录，则是后台直接导入数据
          $user = User::where('name', '=', 'batch-user')->first();
          return Alog::create([
              'log_time' => date('Y-m-d H:i:s', time()),
              'user_id' => $user->id,
              'module' => $module,
              'operate' => $operate,
              'content' => '用户 ' . $user->name . ' : ' . $content,
              'ip' => $ip,
          ]);

        }
    }

    /**
     * 获取保存或者更新的操作字符串
     * @param $id
     * @return string
     */
    public static function getOperate($id)
    {
        if ($id == -1) return 'store';
        else return 'update';
    }


}
