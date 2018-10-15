<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tag;

class TagsController extends Controller
{
    /**
     * 标签搜索
     */
    public function getTags(){
        $query = Request::input('search').'%';
        if($query == null || $query == ''){
            $tags = Tag::all();
        }else{
            $tags = Tag::where('name','like',$query)->get();
        }

        return response()->json($tags);
    }
}
