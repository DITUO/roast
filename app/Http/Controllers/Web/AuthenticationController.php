<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Socialite;

class AuthenticationController extends Controller
{
    //
    public function getSocialRedirect($account){
        try{
            return Socialite::with($account)->redirect();
        }catch(\InvalidArgumentException $e){
            return redirect('/');
        }
    }

    public function getSocialCallback($account){
        //从第三方OAuth回调获取用户信息
        $socialUser = Socialite::with($account)->user();
        //在本地users表中查询该用户是否已存在
        $user = User::where('provider_id',$socialUser->id)->where('Provider',$account)->first();
        if($user == null){
            //用户不存在添加至user表
            $newUser = new User();

            $newUser->name = $socialUser->getName();
            $newUser->email = $socialUser->getEmail() == '' ? '' : $socialUser->getEmail();
            $newUser->avatar = $socialUser->getAvatar();
            $newUser->password    = '';
            $newUser->provider    = $account;
            $newUser->provider_id = $socialUser->getId();

            $newUser->save();
            $user = $newUser;
        }

        //手动登录该用户
        Auth::guards('api')->login($user);
        

        /* $user = User::find(1);//用第一个用户登录
        Auth::login($user); */

        return redirect('/#/home');
    }
}
