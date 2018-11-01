<?php

namespace App\Http\Controllers\API\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Action;
use Auth;
use App\Models\User;

class ActionsController extends Controller
{
    /**
     * 审核列表
     */
    public function getActions(){
        if(Auth::user()->permission >= User::ROLE_ADMIN){
            //如果是后台管理员则返回所有未审核动作
            $actions = Action::with('cafe')
                            ->with('company')
                            ->where('status',Action::STATUS_PENDING)
                            ->with('by')
                            ->get();
        }else{
            //如果不是后台管理员返回该用户的待审核操作
            $actions = Action::with('cafe')
                            ->with('company')
                            ->whereIn('company_id',Auth::user()->companiesOwned()->pluck('id')->toArray())
                            ->where('status',Action::STATUS_PENDING)
                            ->with('by')
                            ->get();
        }

        return response()->json($actions);
    }

    /**
     * 通过
     */
    public function putApproveAction(Action $action){
        if(Auth::user()->cant('approve',$action)){
            abort(403,'没有通过审核的权限');
        }

        $cafeService = new CafeService();
        $actionService = new ActionService();
        //根据操作类型分类处理
        switch($action->type){
            case 'cafe-added':
                //拿到咖啡店数据
                $newActionData = json_decode($action->content,true);
                //执行操作
                $cafeService->addCafe($newActionData,$action->user_id);
                //操作完成后通过审核
                $actionService->approveAction($action,Auth::user()->id);
                break;
            case 'cafe-updated':
                // 拿到咖啡店数据
                $actionData = json_decode($action->content, true);

                // 获取更新后数据
                $updatedActionData = $actionData['after'];
                // 执行变更
                $cafeService->editCafe($action->cafe_id, $updatedActionData, $action->user_id);

                // 通过这条审核
                $actionService->approveAction($action, Auth::user()->id);
                break;
            case 'cafe-deleted':
                // 获取要删除的咖啡店数据
                $cafe = $cafe = Cafe::where('id', '=', $action->cafe_id)->first();
                // 执行变更
                $cafe->delete();
    
                // 通过这条审核
                $actionService->approveAction($action, Auth::user()->id);
                break;
        }

        return response()->json('', 204);
    }

    /**
     * 不通过
     */
    public function putDenyAction(Action $action){
        if (Auth::user()->cant('deny', $action)) {
            abort(403, '该用户没有拒绝审核权限');
        }
    
        // 拒绝这条变更请求
        $actionService = new ActionService();
        $actionService->denyAction($action, Auth::user()->id);
    
        // 返回响应
        return response()->json('', 204);
    }
}
