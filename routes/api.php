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
    /* Route::get('/user', function( Request $request ){
        return $request->user();
    }); */

    Route::get('/userTest', function( Request $request ){
        $data = [
            'text' => 'just a test'
        ];
        return response()->json($data)
        ->header('Access-Control-Allow-Origin','http://120.79.20.43')
        ->header('Access-Control-Allow-Credentials', 'true')
        ->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    });

    //Route::get('/cafes', 'API\CafesController@getCafes');//列表

    Route::post('/cafes', 'API\CafesController@postNewCafe');//添加

    //Route::get('/cafes/{id}', 'API\CafesController@getCafe');//详情

    //Route::get('/brew-methods', 'API\BrewMethodsController@getBrewMethods');//获取所有的咖啡冲泡方法

    Route::post('/cafes/{id}/like', 'API\CafesController@postLikeCafe');// 喜欢咖啡店

    Route::delete('/cafes/{id}/like', 'API\CafesController@deleteLikeCafe');// 取消喜欢咖啡店

    Route::post('/cafes/{id}/tags','API\CafesController@postAddTags');// 用户为某个咖啡店添加标签

    Route::delete('/cafes/{id}/tags/{tagID}','API\CafesController@deleteAddTags');// 用户删除某个咖啡店的标签

    //Route::get('/tags','API\TagsController@getTags');// 根据输入词提供标签补全功能

    Route::put('/user','API\UsersController@putUpdateUser');//更新用户个人信息

    Route::get('/cafes/{id}/edit','API\CafesController@getCafeEditData');// 获取待编辑的咖啡店数据
    Route::put('/cafes/{id}','API\CafesController@putEditCafe');// 更新咖啡店信息
    Route::delete('/cafes/{id}','API\CafesController@deleteCafe');// 删除指定的咖啡店
});

// 公有路由，无需登录即可访问
Route::group(['prefix' => 'v1'], function(){
    Route::get('/cafes', 'API\CafesController@getCafes');//咖啡店列表
    Route::get('/cafes/{id}', 'API\CafesController@getCafe');//咖啡店详情
    Route::get('/brew-methods', 'API\BrewMethodsController@getBrewMethods');//获取所有的咖啡冲泡方法
    Route::get('/tags', 'API\TagsController@getTags');// 获取所有标签
    Route::get('/user', 'API\UsersController@getUser');// 获取用户信息
    Route::get('/cities', 'API\CitiesController@getCities');// 获取所有城市
    Route::get('/cities/{id}', 'API\CitiesController@getCity');// 获取指定城市
    Route::get('/companies/search', 'API\CompaniesController@getCompanySearch');//公司搜索路由
});

// 管理后台，需要登录，且有后台管理权限
Route::group(['prefix'=>'v1/admin','middleware'=>['auth:api','owner']],function(){
    Route::get('/actions','API\Admin\ActionsController@getActions');//审核列表
    Route::put('/actions/{action}/approve', 'API\Admin\ActionsController@putApproveAction');//通过
    Route::put('/actions/{action}/deny', 'API\Admin\ActionsController@putDenyAction');//不通过
});
