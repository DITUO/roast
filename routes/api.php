<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function(){
    Route::get('/user', function( Request $request ){
        return $request->user();
    });

    Route::get('/userTest', function( Request $request ){
        $data = [
            'text' => 'just a test'
        ];
        return response()->json($data)
        ->header('Access-Control-Allow-Origin','http://120.79.20.43')
        ->header('Access-Control-Allow-Credentials', 'true')
        ->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    });

    Route::get('/cafes', 'API\CafesController@getCafes');//列表

    Route::post('/cafes', 'API\CafesController@postNewCafe');//添加

    Route::get('/cafes/{id}', 'API\CafesController@getCafe');//详情

    Route::get('/brew-methods', 'API\BrewMethodsController@getBrewMethods');//获取所有的咖啡冲泡方法
});
