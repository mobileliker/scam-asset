<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RockCategory extends Model
{
    public function rocks()
    {
        return $this->hasMany('App\Rock', 'category_id', 'id');
    }

    public function pRockCategory()
    {
        return $this->belongsTo('App\RockCategory', 'pid', 'id');
    }

    public function sRockCategories()
    {
        return $this->hasMany('App\RockCategory', 'pid', 'id');
    }
}
