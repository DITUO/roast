<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City;

class CitiesController extends Controller
{
    /**
     * 获取所有城市
     */
    public function getCities(){
        $cities = City::all();
        return response()->json($cities);
    }

    /**
     *获取指定城市信息 
     */
    public function getCity($id){
        $city = City::where('id','=',$id)
                ->with(['cafes'=>function($query){
                    $query->with('company');
                }])
                ->first();
        if($city != null){
            return response()->json($city);
        }else{
            return response()->json(null,404);
        }
    }
}
