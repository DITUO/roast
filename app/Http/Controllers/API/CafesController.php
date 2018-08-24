<?php

namespace App\Http\Controllers\API;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Cafe;
use Auth;

class CafesController extends Controller
{
    //
    public function getCafes(){
        $cafes = Cafe::all();
        return response()->json($cafes)
                            ->header('Access-Control-Allow-Origin:*')
                            ->header('Access-Control-Allow-Method:POST,GET');
    }

    public function getCafe($id){
        $cafe = Cafe::where('id',$id)->first();
        return response()->json($cafe)
                            ->header('Access-Control-Allow-Origin:*')
                            ->header('Access-Control-Allow-Method:POST,GET');
    }

    public function postNewCafe(){
        $cafe = new Cafe();

        $cafe->name = Request::get('name');
        $cafe->address = Request::get('address');
        $cafe->city = Request::get('city');
        $cafe->state = Request::get('state');
        $cafe->zip = Request::get('zip');

        $cafe->save();

        return response()->json($cafe,201)
                            ->header('Access-Control-Allow-Origin:*')
                            ->header('Access-Control-Allow-Method:POST,GET');
    }
}
