<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Cafe;
use App\Models\Company;

class CafePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 如果用户是管理员或者超级管理员则可以添加咖啡店
     *
     */
    public function create(User $user,Company $company){
        if($user->permission == 2 || $user->permission == 3){
            return true;
        }elseif($company != null && $user->companiesOwned->contains($company->id)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 如果用户是管理员,超级管理员或者拥有该咖啡店所属公司则可以更新咖啡店信息
     */
    public function update(User $user,Cafe $cafe){
        if($user->permission == 2 || $user->permission == 3){
            return true;
        }elseif($user->companiesOwned->contains($company->id)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 如果用户是管理员，超级管理员或者拥有该咖啡店所属公司则可以删除该咖啡店
     */
    public function delete(User $user,Cafe $cafe){
        if($user->permission == 2 || $user->permission == 3){
            return true;
        }elseif($user->companiesOwned->contains($company->id)){
            return true;
        }else{
            return false;
        }
    }
}
