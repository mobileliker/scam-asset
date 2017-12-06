<?php

/**
 * 土壤model类
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/29
 * @description :
 * （1）基本功能；(2017/11/29)
 * (2)添加段面标本和纸盒标本的关联函数；（2017/12/6）
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Soil extends Model
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

    //关联段面标本
    public function soilBigs()
    {
      return $this->hasMany('App\SoilBig', 'soil_id', 'id');
    }

    //关联纸盒标本
    public function soilSmalls()
    {
      return $this->hasMany('App\SoilSmall', 'soil_id', 'id');
    }
}
