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
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Method:POST,GET');
        $cafes = Cafe::all();
        return response()->json($cafes);
    }

    public function getCafe($id){
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Method:POST,GET');
        $cafe = Cafe::where('id',$id)->first();
        return response()->json($cafe);
    }

    public function getUser(){
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Method:POST,GET');
        $user = Auth::user();
        $user = $user->toArray();

        return response()->json($user,201);
    }

    public function postNewCafe(){
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Method:POST,GET');
        $cafe = new Cafe();

        $cafe->name = Request::get('name');
        $cafe->address = Request::get('address');
        $cafe->city = Request::get('city');
        $cafe->state = Request::get('state');
        $cafe->zip = Request::get('zip');

        $cafe->save();

        return response()->json($cafe,201);
    }
}
