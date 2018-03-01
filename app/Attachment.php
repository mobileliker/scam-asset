<?php

/**
 * 附件Model
 * @version : 2.0.3
 * @author : wuzhihui
 * @date : 2018/3/1
 * @description :
 * (1)基础功能；（2018/3/1）
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    //所属用户
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
