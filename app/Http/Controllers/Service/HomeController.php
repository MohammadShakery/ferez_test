<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Network;
use App\Models\Requirement;
use App\Models\Slider;
use App\Models\specialSale;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $user = $this->getUser($request);
        $price = array(
            'usd' => 52000 ,
            'euro' => 56000 ,
            'gold' => 2387000 ,
            'sekkeh_tamam' => 30000000 ,
        );
        return response([
            'status' => true ,
            'slides' => Slider::query()->where('location','main')->orderByDesc('priority')->get() ,
            'categories' => Category::query()->where('parent_id',0)->get() ,
            'networks' => Network::query()->where('user_id',$user->id)->get() ,
            'special_sales' => specialSale::query()->orderByDesc('created_at')->with('images')->limit(10)->get() ,
            'requirements' => Requirement::query()->orderByDesc('created_at')->with('category')->limit(10)->get()  ,
            'new_brands' => Brand::query()->orderByDesc('created_at')->limit(10)->get() ,
            'popular_brands' => Brand::query()->orderByDesc('view')->limit(10)->get() ,
            'news' => Alert::query()->orderByDesc('created_at')->limit(5)->get() ,
            'price' => $price
        ],200);
    }

    public function getUser(Request $request)
    {
        return User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->firstOrFail();
    }
}
