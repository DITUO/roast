<?php

namespace App\Http\Controllers\API;

use Request;
use App\Http\Controllers\Controller;
use App\Models\Cafe;
use Auth;
use App\Http\Requests\StoreCafeRequest;
use App\Utilities\GaodeMaps;

class CafesController extends Controller
{
    //
    public function getCafes(){
        $cafes = Cafe::with('brewMethods')->get();
        return response()->json($cafes)
                            ->header('Access-Control-Allow-Origin','http://120.79.20.43')
                            ->header('Access-Control-Allow-Credentials', 'true')
                            ->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    }

    public function getCafe($id){
        $cafe = Cafe::where('id',$id)->with('brewMethods')->first();
        return response()->json($cafe)
                        ->header('Access-Control-Allow-Origin','http://120.79.20.43')
                        ->header('Access-Control-Allow-Credentials', 'true')
                        ->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    }

    public function postNewCafe(StoreCafeRequest $request){
        $cafe = new Cafe();

        $cafe->name     = $request->input('name');
        $cafe->address  = $request->input('address');
        $cafe->city     = $request->input('city');
        $cafe->state    = $request->input('state');
        $cafe->zip      = $request->input('zip');
        $coordinates = GaodeMaps::geocodeAddress($cafe->address, $cafe->city, $cafe->state);
        $cafe->latitude = $coordinates['lat'];
        $cafe->longitude = $coordinates['lng'];

        $cafe->save();

        return response()->json($cafe,201)
                        ->header('Access-Control-Allow-Origin','http://120.79.20.43')
                        ->header('Access-Control-Allow-Credentials', 'true')
                        ->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    }
}
