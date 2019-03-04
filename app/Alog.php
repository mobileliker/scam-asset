<?php

/**
 * Log Model.
 * @version : 1.0
 * @author : wuzhihui
 * @date : 2019/3/4
 * @description :
 * (1) Update the static value of operate.(2019/3/4)
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

//	const OPERATE_CREATE = 0;
//	const OPERATE_DELETE = 1;
//	const OPERATE_UPDATE = 2;
//	const OPERATE_QUERY = 3;
//	const OPERATE_SHOW = 4;
//
//	const OPERATE = [
//		self::OPERATE_CREATE => '新增',
//		self::OPERATE_DELETE => '删除',
//		self::OPERATE_UPDATE => '修改',
//		self::OPERATE_QUERY => '查询',
//		self::OPERATE_SHOW => '查看',
//	];
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

    public static function log($module, $operate, $content, $ip)
    {
		return Alog::create([
			'log_time' => date('Y-m-d H:i:s',time()),
			'user_id' => Auth::user()->id,
			'module' => $module,
			'operate' => $operate,
			'content' => $content,
			'ip' => $ip,
		]);
    }
}
