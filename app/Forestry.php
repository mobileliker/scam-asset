<?php

/**
 * 林业model类
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2018/3/27
 * @description :
 * (1)基本功能；（2018/3/27）
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Forestry extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function keeper()
    {
        return $this->belongsTo('App\User', 'keeper_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function asset()
    {
        return $this->belongsto('App\Asset', 'asset_id', 'id');
    }

    public function images()
    {
        return $this->morphMany('App\CollectionImage', 'collectible');
    }
}
