<?php

/**
 * @version 2.0
 * @author: wuzhihui
 * @date: 2017/7/3
 * @description:
 * 
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;
    
    public function parent_category()
    {
    	return $this->belongsTo('App\Category', 'pid', 'id');
    }

    public function sub_categories()
    {
    	return $this->hasMany('App\Category', 'pid', 'id');
    }

    public static function categories($serial)
    {
        return Category::where('serial', '=', $serial)->first()->sub_categories;
    }
}
