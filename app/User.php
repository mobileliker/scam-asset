<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait; //角色权限的trait

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait; // 角色权限的特性

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
}
