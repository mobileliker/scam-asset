<?php

/**
 * 藏品图片模型
 * @version : 0.1
 * @author : wuzhihui
 * @date :2017/9/15
 * @description :
 * （1）基本功能；（2017/9/15）
 * （2）修正了关联函数的拼写错误；（2017/12/1）
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectionImage extends Model
{
    public function collectible()
    {
        return $this->morphTo();
    }
}
