<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Auth;

class Owner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null){
        if(Auth::user()->permisssion < User::ROLE_SHOP_OWNER){
            abort(403,'Unauthorized action.');
        }
        return $next($request);
    }
}
