<?php

/*
 * @version: 1.0 后台配置管理模型
 * @author: wuzhihui
 * @date: 2016/9/30
 * @description:
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Info extends Model
{
	use SoftDeletes;
    protected $dates = ['deleted_at'];  //开启deleted_at字段
    //protected $table = config('app.db_prex', 'zxck_') . 'infos'; //绑定表
    protected $table = 'zxck_infos'; //绑定表
}
