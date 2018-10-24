<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class Cafe extends Model
{
    use SoftDeletes; 

    public $primaryKey='id';//主键

    protected $table = 'cafes';//表名

    protected $guarded = [];//黑名单

    //定义与 BrewMethod 模型间的多对多关联
    public function brewMethods()
    {
        return $this->belongsToMany(BrewMethod::class, 'cafes_brew_methods', 'cafe_id', 'brew_method_id');
    }

    // 关联分店
    public function children()
    {
        return $this->hasMany(Cafe::class, 'parent', 'id');
    }

    // 归属总店
    public function parent()
    {
        return $this->hasOne(Cafe::class, 'id', 'parent');
    }

    /**
     * 与 User 间的多对多关系  喜欢
     */
    public function likes(){
        return $this->belongsToMany(User::class,'users_cafes_likes','cafe_id','user_id');
    }

    /**
     * 用户喜欢
     */
    public function userLike(){
        \Log::info(Auth::user());
        return $this->belongsToMany(User::class,'users_cafes_likes','cafe_id','user_id')->where('user_id',auth::auth()->id());
    }

    /**
     * 咖啡店和标签之间的多对多关联
     */
    public function tags(){
        return $this->belongsToMany(Tag::class,'cafes_users_tags','cafe_id','tag_id');
    }

    /**
     * 咖啡店图片
     */
    public function photos(){
        return $this->hasMany(CafePhoto::class,'id','cafe_id');
    }

    /**
     * 归属公司
     */ 
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
