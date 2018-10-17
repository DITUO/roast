<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;

class UsersController extends Controller
{
    public function getUser(){
        return Auth::guard('api')->user();
    }
}
