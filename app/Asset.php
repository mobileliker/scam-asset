<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function consumer()
    {
        return $this->belongsTo('App\User', 'consumer_id', 'id');
    }

    public function handler()
    {
        return $this->belongsTo('App\User', 'handler_id', 'id');
    }
}
