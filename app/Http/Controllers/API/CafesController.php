<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cafe;
use App\Models\CafePhoto;
use App\Models\Tag;
use Auth;
use App\Http\Requests\StoreCafeRequest;
use App\Utilities\GaodeMaps;
use App\Utilities\Tagger;
use Carbon\Carbon;
use DB;
use App\Services\CafeService;

class CafesController extends Controller
{
    //
    public function getCafes(){
        $cafes = Cafe::with('brewMethods')
                       ->with(['tags'=>function($query){
                           $query->select('name');
                       }])
                       ->with('company')
                       ->withCount('userLike')
                       ->withCount('likes')
                       ->get();
        return response()->json($cafes)
                            ->header('Access-Control-Allow-Origin','http://120.79.20.43')
                            ->header('Access-Control-Allow-Credentials', 'true')
                            ->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    }

    public function getCafe($id){
        $cafe = Cafe::where('id',$id)
                ->with('brewMethods')
                ->withCount('userLike')
                ->with('tags')
                ->with(['company'=>function($query){
                    $query->withCount('cafes');
                }])
                ->withCount('likes')
                ->first();
        return response()->json($cafe)
                        ->header('Access-Control-Allow-Origin','http://120.79.20.43')
                        ->header('Access-Control-Allow-Credentials', 'true')
                        ->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    }

    public function postNewCafe(StoreCafeRequest $request){
        $cafeService = new CafeService();
        $cafe = $cafeService->addCafe($request->all(), Auth::user()->id);
    
        $company = Company::where('id', '=', $cafe->company_id)
                   ->with('cafes')
                   ->first();
        return response()->json($addedCafes,201)
                        ->header('Access-Control-Allow-Origin','http://120.79.20.43')
                        ->header('Access-Control-Allow-Credentials', 'true')
                        ->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    }

    /**
     * 用户添加喜欢的咖啡店
     */
    public function postLikeCafe($cafeID){
        $cafe = Cafe::where('id',$cafeID)->first();
        $cafe->likes()->attach(Auth::user()->id,['created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
        return response()->json(['cafe_liked'=>true],201)
                        ->header('Access-Control-Allow-Origin','http://120.79.20.43')
                        ->header('Access-Control-Allow-Credentials', 'true')
                        ->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    }

    /**
     * 用户删除喜欢的咖啡店
     */
    public function deleteLikeCafe($cafeID){
        $cafe = Cafe::where('id',$cafeID)->first();
        $cafe->likes()->detach(Auth::user()->id);
        return response(null, 204)
                        ->header('Access-Control-Allow-Origin','http://120.79.20.43')
                        ->header('Access-Control-Allow-Credentials', 'true')
                        ->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    }

    /**
     * 用户给咖啡店添加标签
     */
    public function postAddTags(Request $request,$cafeID){
        $tags = $request->input('tags');
        $cafe = Cafe::find($cafeID);

        //添加标签
        Tagger::tagCafe($cafe,$tags,Auth::user()->id);
        $cafe = Cafe::where('id',$cafeID)
                ->with('brewMethods')
                ->with('userLike')
                ->with('tags')
                ->first();
        return response()->json($cafe,201);
    }

    /**
     * 用户删除咖啡店的标签
     */
    public function deletePostTags($cafeID,$tagID){
        DB::table('cafes_users_tags')->where('cafe_id', $cafeID)->where('tag_id', $tagID)->where('user_id', Auth::user()->id)->delete();
        return response(null, 204);
    }
}
