<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cafe extends Model
{
    public $primaryKey='id';//主键

    protected $table = 'cafes';//表名

    protected $guarded = [];//黑名单

    //定义与 BrewMethod 模型间的多对多关联
    public function brewMethods()
    {
        return $this->belongsToMany(BrewMethod::class, 'cafes_brew_methods', 'cafe_id', 'brew_method_id');
    }
}
