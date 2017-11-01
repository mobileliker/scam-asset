<?php

/**
 * 藏品图片模型
 * @version : 0.1
 * @author : wuzhihui
 * @date :2017/9/15
 * @description :
 * （1）基本功能；（2017/9/15）
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectionImage extends Model
{
    public function commentable()
    {
        return $this->morphTo();
    }
}
