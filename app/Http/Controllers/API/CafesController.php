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

class CafesController extends Controller
{
    //
    public function getCafes(){
        $cafes = Cafe::with('brewMethods')
                       ->with(['tags'=>function($query){
                           $query->select('name');
                       }])->get();
        return response()->json($cafes)
                            ->header('Access-Control-Allow-Origin','http://120.79.20.43')
                            ->header('Access-Control-Allow-Credentials', 'true')
                            ->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    }

    public function getCafe($id){
        $cafe = Cafe::where('id',$id)->with('brewMethods')->with('userLike')->with('tags')->first();
        return response()->json($cafe)
                        ->header('Access-Control-Allow-Origin','http://120.79.20.43')
                        ->header('Access-Control-Allow-Credentials', 'true')
                        ->header('Access-Control-Allow-Methods', 'POST, GET, PUT, DELETE, OPTIONS');
    }

    public function postNewCafe(StoreCafeRequest $request){
        // 已添加的咖啡店
        $addedCafes = [];
        // 所有位置信息
        $locations = json_decode($request->input('locations'),true);

        // 父节点（可理解为总店）
        $parentCafe = new Cafe();

        // 咖啡店名称
        $parentCafe->name = $request->input('name');
        // 分店位置名称
        $parentCafe->location_name = $locations[0]['name'] ?: '';
        // 分店地址
        $parentCafe->address = $locations[0]['address'];
        // 所在城市
        $parentCafe->city = $locations[0]['city'];
        // 所在省份
        $parentCafe->state = $locations[0]['state'];
        // 邮政编码
        $parentCafe->zip = $locations[0]['zip'];
        $coordinates = GaodeMaps::geocodeAddress($parentCafe->address, $parentCafe->city, $parentCafe->state);
        // 纬度
        $parentCafe->latitude = $coordinates['lat'];
        // 经度
        $parentCafe->longitude = $coordinates['lng'];
        // 咖啡烘焙师
        $parentCafe->roaster = $request->input('roaster') ? 1 : 0;
        // 咖啡店网址
        $parentCafe->website = $request->input('website');
        // 描述信息
        $parentCafe->description = $request->input('description') ?: '';
        // 添加者
        $parentCafe->added_by = $request->user()->id;
        $parentCafe->save();

        //保存图片
        $photo = $request->file('picture');
        if($photo && $photo->isValid()){
            $destinationPath = storage_path('app/public/photos/'.$parentCafe->id);

            //如果目标目录不存在，创建
            if(!file_exists($destinationPath)){
                mkdir($destinationPath);
            }

            //文件名
            $filename = time().'-'.$photo->getClientOriginalName();
            //保存文件到目标目录
            $photo->move($destinationPath,$filename);

            //数据库记录
            $cafePhoto = new CafePhoto();

            $cafePhoto->cafe_id = $parentCafe->id;
            $cafePhoto->uploaded_by = Auth::user()->id;
            $cafePhoto->file_url = $destinationPath.DIRECTORY_SEPARATOR.$filename;

            $cafePhoto->save();
        }

        // 冲泡方法
        $brewMethods = $locations[0]['methodsAvailable'];
        // 标签信息
        $tags = $locations[0]['tags'];
        // 保存与此咖啡店关联的所有冲泡方法（保存关联关系）
        $parentCafe->brewMethods()->sync($brewMethods);
        // 绑定咖啡店与标签
        Tagger::tagCafe($parentCafe, $tags, $request->user()->id);

        // 将当前咖啡店数据推送到已添加咖啡店数组
        array_push($addedCafes, $parentCafe->toArray());

        // 第一个索引的位置信息已经使用，从第 2 个位置开始
        if (count($locations) > 1) {
            // 从索引值 1 开始，以为第一个位置已经使用了
            for ($i = 1; $i < count($locations); $i++) {
                // 其它分店信息的获取和保存，与总店共用名称、网址、描述、烘焙师等信息，其他逻辑与总店一致
                $cafe = new Cafe();

                $cafe->parent = $parentCafe->id;
                $cafe->name = $request->input('name');
                $cafe->location_name = $locations[$i]['name'] ?: '';
                $cafe->address = $locations[$i]['address'];
                $cafe->city = $locations[$i]['city'];
                $cafe->state = $locations[$i]['state'];
                $cafe->zip = $locations[$i]['zip'];
                $coordinates = GaodeMaps::geocodeAddress($cafe->address, $cafe->city, $cafe->state);
                $cafe->latitude = $coordinates['lat'];
                $cafe->longitude = $coordinates['lng'];
                $cafe->roaster = $request->input('roaster') != '' ? 1 : 0;
                $cafe->website = $request->input('website');
                $cafe->description = $request->input('description') ?: '';
                $cafe->added_by = $request->user()->id;
                $cafe->save();

                $cafe->brewMethods()->sync($locations[$i]['methodsAvailable']);
                Tagger::tagCafe($cafe, $locations[$i]['tags'], $request->user()->id);

                array_push($addedCafes, $cafe->toArray());
            }
        }

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
