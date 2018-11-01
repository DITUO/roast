<?php

namespace App\Services;

use App\Models\Action;
use Illuminate\Support\Carbon;

class ActionService{
    /**
     * 创建一条待审核记录（没权限的用户，需要审核）
     */
    public function createPendingAction($cafeID,$companyID,$type,$content,$userId){
        $action = new Action();

        $action->cafe_id = $cafeID;
        $action->company_id = $companyID;
        $action->user_id = $userId;
        $action->status = Action::STATUS_PENDING;
        $action->type = $type;
        $action->content = json_encode($content);

        $action->save();
    }

    /**
     * 创建一条已操作记录（有权限的用户，不需要审核，直接记录）
     */
    public function createApprovedAction($cafeID,$companyID,$type,$content,$userId){
        $action = new Action();

        $action->cafe_id = $cafeID;
        $action->company_id = $companyID;
        $action->user_id = $userId;
        $action->status = Action::STATUS_APPROVED;
        $action->type = $type;
        $action->content = json_encode($content);
        $action->processed_by = $userId;
        $action->processed_on = Carbon::now();

        $action->save();
    }

    /**
     * 审核通过一条记录
     */
    public function approveAction($action,$processedBy){
        $action->status = Action::STATUS_APPROVED;
        $action->processed_by = $processedBy;
        $action->processed_on = Carbon::now();
        $action->save();
    }

    /**
     * 审核不通过一条记录
     */
    public function denyAction($action,$processedBy){
        $action->status = Action::STATUS_DENIED;
        $action->processed_by = $processedBy;
        $action->processed_on = Carbon::now();
        $action->save();
    }
}