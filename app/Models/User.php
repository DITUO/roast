<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

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

    /**
     * 与Cafe 间的多对多关联 喜欢
     */
    public function likes(){
        return $this->belongsToMany(Cafe::class,'users_cafes_likes','user_id','cafe_id');
    }

    /**
     * 上传的咖啡店图片
     */
    public function cafePhotos(){
        return $this->hasMany(CafePhoto::class,'id','cafe_id');
    }
}
