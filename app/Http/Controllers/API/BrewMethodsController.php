<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BrewMethod;

class BrewMethodsController extends Controller
{
    /**
     * 获取所有冲泡方法以及拥有该冲泡方法的咖啡店
     */
    public function getBrewMethods(){
        $brewMethods = BrewMethod::withCount('cafes')->get();//with 关联 count统计
        return response()->json($brewMethods)
                            ->header('Access-Control-Allow-Origin','http://120.79.20.43')
                            ->header('Access-Control-Allow-Credentials', 'true')
                            ->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    }
}
