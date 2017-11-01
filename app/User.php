<?php

/**
 * @version 2.0
 * @author: wuzhihui
 * @date: 2017/7/3
 * @description:
 * （1）添加软删除（2017/7/3）
 * （2）添加Passport的trait（2017/7/4）
 * （3）添加Farm的关联函数；（2017/7/6）
 */

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait; //角色权限的trait
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait; // 角色权限的特性
    use SoftDeletes { SoftDeletes::restore insteadof EntrustUserTrait; } //软删除,restore与EntrustUserTrait冲突
    use HasApiTokens; //Api授权的trait

    const TYPE_ADMIN = 1;
    const TYPE_USER = 2;
    const TYPE = [
        self::TYPE_ADMIN => '管理员',
        self::TYPE_USER => '用户',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['deleted_at'];

    public function keepFarms()
    {
        return $this->hasMany('App\Farm', 'keeper_id', 'id');
    }

    public function farms()
    {
        return $this->hasMany('App\Farm', 'user_id', 'id');
    }
}
