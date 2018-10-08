<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrewMethod extends Model
{
    public $primaryKey='id';//主键

    protected $table = 'brew_methods';//表名

    protected $guarded = [];//黑名单

    // 定义与 Cafe 模型间的多对多关联
    public function cafes()
    {
        return $this->belongsToMany(Cafe::class, 'cafes_brew_methods', 'brew_method_id', 'cafe_id');
    }
}
