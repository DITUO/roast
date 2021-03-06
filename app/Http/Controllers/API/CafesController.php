<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditCafeRequest;
use App\Http\Requests\StoreCafeRequest;
use App\Models\Cafe;
use App\Models\CafePhoto;
use App\Models\Company;
use App\Models\Tag;
use App\Services\CafeService;
use App\Services\ActionService;
use App\Utilities\GaodeMaps;
use App\Utilities\Tagger;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
        return response()->json($cafes);
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

        return response()->json($cafe);
    }

    public function postNewCafe(StoreCafeRequest $request){
        $companyID = $request->input('company_id');
        $company = Company::where('id',$companyID)->first();
        $company = $company == null ? new Company() : $company;

        $actionService = new ActionService();
        if(Auth::user()->can('create',[Cafe::class,$company])){
            //有权限
            $cafeService = new CafeService();
            $cafe = $cafeService->addCafe($request->all(), Auth::user()->id);

            $actionService->createApprovedAction(null,$cafe->company_id,'cafe-added',$request->all(),Auth::user()->id);
            
            $company = Company::where('id', '=', $cafe->company_id)
            ->with('cafes')
            ->first();
            return response()->json($company,201);
        }else{
            //无权限
            $actionService->createPendingAction(null,$request->get('company_id'),'cafe-added',$request->all(),Auth::user()->id);
            return response()->json(['cafe_add_pending' => $request->get('company_name')], 202);
        }
    }

    /**
     * 用户添加喜欢的咖啡店
     */
    public function postLikeCafe($cafeID){
        $cafe = Cafe::where('id',$cafeID)->first();
        $cafe->likes()->attach(Auth::user()->id,['created_at'=>Carbon::now(),'updated_at'=>Carbon::now()]);
        return response()->json(['cafe_liked'=>true],201);
    }

    /**
     * 用户删除喜欢的咖啡店
     */
    public function deleteLikeCafe($cafeID){
        $cafe = Cafe::where('id',$cafeID)->first();
        $cafe->likes()->detach(Auth::user()->id);
        return response(null, 204);
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

    /**
     * 获取咖啡店编辑表单数据
     */
    public function getCafeEditData($id){
        $cafe = Cafe::where('id',$id)
                ->with('brewMethods')
                ->withCount('userLike')
                ->with(['company'=>function($query){
                    $query->withCount('cafes');
                }])
                ->first();
        return response()->json($cafe);
    }

    /**
     * 更新咖啡店数据
     */
    public function putEditCafe($id,Request $request){
        $cafe = Cafe::where('id', '=', $id)->with('brewMethods')->first();
        if(!$cafe){
            abort(404);
        }

        //保存咖啡店修改之前的数据
        $content['before'] = $cafe;
        $content['after'] = $request->all();

        $actionService = new ActionService();
        if(Auth::user()->can('update',$cafe)){
            //有权限
            $actionService->createApprovedAction($cafe->id,$cafe->company_id,'cafe-updated',$content,Auth::user()->id);

            $cafeService = new CafeService();
            $updatedCafe = $cafeService->editCafe($cafe->id, $request->all(), Auth::user()->id);
        
            $company = Company::where('id', '=', $updatedCafe->company_id)
                ->with('cafes')
                ->first();
        
            return response()->json($company, 200);
        }else{
            //无权限
            $actionService->createPendingAction($cafe->id,$cafe->company_id,'cafe-updated',$content,Auth::user()->id);
            return response()->json(['cafe_updates_pending' => $request->get('company_name')], 202);
        }
    }

    /**
     * 删除咖啡店信息
     */
    public function deleteCafe($id){
        $cafe = Cafe::where('id', '=', $id)->first();
        if(!$cafe){
            abort(404);
        }
        $actionService = new ActionService();
        if(Auth::user()->can('delete',$cafe)){
            //有权限
            $actionService->createApprovedAction($cafe->id, $cafe->company_id, 'cafe-deleted', '', Auth::user()->id);
            
            $cafe->delete();
            return response()->json(['message' => '删除成功'], 204);
        }else{
            //无权限
            $actionService->createPendingAction($cafe->id, $cafe->company_id, 'cafe-deleted', '', Auth::user()->id);
            return response()->json(['cafe_delete_pending' => $cafe->company->name], 202);
        }
    }
}
