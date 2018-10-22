<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CafePhoto extends Model
{
    protected $table = 'cafes_photos';

    /**
     * 图片和咖啡店
     */
    public function cafe(){
        return $this->belongsTo(Cafe::class,'cafe_id','id');
    }

    /**
     * 图片和用户
     */
    public function user(){
        return $this->belongsTo(User::class,'uploaded_by','id');
    }
}
