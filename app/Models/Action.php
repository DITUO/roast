<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    const STATUS_PENDING = 0;   // 待审核
    const STATUS_APPROVED = 1;  // 已通过
    const STATUS_DENIED = 2;    // 已拒绝

    /**
     * 该动作所属咖啡店
     */
    public function cafe(){
        return $this->belongsTo(Cafe::class,'cafe_id','id');
    }

    /**
     * 该动作是前端谁操作的
     */
    public function by(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    /**
     * 该动作后台是谁操作的
     */
    public function processedBy(){
        return $this->belongsTo(user::class,'processed_by','id');
    }
}
