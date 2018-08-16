<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cafe extends Model
{
    public $primaryKey='id';//主键

    protected $table = 'cafes';//表名

    protected $guarded = [];//黑名单
}
