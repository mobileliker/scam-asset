<?php

/**
 * 土壤段面Model类
 * @version : 2.0.2
 * @author : wuzhihui
 * @date : 2017/11/27
 * @description :
 * (1)基本功能：（2017/11/27）
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoilBig extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function soil()
    {
      return $this->belongsTo('App\Soil', 'soil_id', 'id');
    }

    public function images()
    {
        return $this->morphMany('App\CollectionImage', 'collectible');
    }
}
