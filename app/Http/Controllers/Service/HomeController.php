<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Network;
use App\Models\Post;
use App\Models\Price;
use App\Models\Requirement;
use App\Models\Slider;
use App\Models\specialSale;
use App\Models\User;
use App\Models\Comment;
use App\Models\Violation;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function home(Request $request)
    {

        if(Cache::has('home'))
        {
            $data_array = (array)json_decode(Cache::get('home'));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $users = User::all();
        $brands = Brand::all();
        $requirement = Requirement::all();
        $special_sales = specialSale::all();
        $data = array(
            'status'                      => true ,
            'slides'                      => Slider::query()->where('location','main')->orderByDesc('priority')->get() ,
            'categories'                  => Category::query()->where('parent_id',0)->get() ,
            'special_sales'               => specialSale::query()->orderByDesc('created_at')->with('images')->limit(10)->get() ,
            'requirements'                => Requirement::query()->orderByDesc('created_at')->with('category')->limit(10)->get()  ,
            'new_brands'                  => Brand::query()->orderByDesc('created_at')->limit(10)->get() ,
            'popular_brands'              => Brand::query()->orderByDesc('view')->limit(10)->get() ,
            'news'                        => Alert::query()->orderByDesc('created_at')->limit(5)->get() ,
            'price'                       => Price::query()->where('category_price_id',1)->get() ,
            'nu_of_users'                 => count($users) ,
            'nu_of_brands'                => count($brands) ,
            'nu_of_Requirements'          => count($requirement) ,
            'nu_of_special_sales'         => count($special_sales) ,
            'request_of_brand'            => Brand::query()->where('status',0)->get() ,
            'Comment_pending_confirmation'=> Comment::query()->where('status',0)->get() ,
            'Visits'                      => Visit::all()
        );
        
        Cache::put('home',json_encode($data),now()->addSeconds(30));
        return response($data,200);
    }

    public function getUser(Request $request)
    {
        return User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->firstOrFail();
    }




}
