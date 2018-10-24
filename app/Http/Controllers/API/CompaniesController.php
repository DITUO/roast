<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;

class CompaniesController extends Controller
{
    /**
     * 公司搜索
     */
    public function getCompanySearch(Request $request){
        $term = $request->input('search');

        $companies = Company::where('name','LIKE','%'.$term.'%')
                     ->withCount('cafes')
                     ->get();
        return response()->json(['companies'=>$companies]);
    }
}
