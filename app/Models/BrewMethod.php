<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrewMethod extends Model
{
    public $primaryKey='id';//主键

    protected $table = 'brew_methods';//表名

    protected $guarded = [];//黑名单
}
