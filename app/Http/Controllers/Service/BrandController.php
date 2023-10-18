<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        return response([
            'status' => true ,
            'brands' => Brand::query()->orderByDesc('priority')->get()
        ],200);
    }

    public function show(Brand $brand)
    {
        return response([
            'status' => true ,
            'brand' => Brand::query()->where('id',$brand->id)->with(['brandCategory' => function($query){
                $query->with('products');
            },'comments'])->first()
        ],200);
    }

    public function brandFromCategory(Category $category)
    {
        if(Cache::has('brandFromCategory_'.$category->id))
        {
            $data_array = json_decode(Cache::get('brandFromCategory_'.$category->id));
            $data_array[] = ['cache' => true];
            return response($data_array,200);
        }
        $data = [
            'status'         => true ,
            'brands'         => Category::query()->where('id',$category->id)->with('brands')->first() ,
            'new_brands'     => Category::query()->where('id',$category->id)->with('newBrands')->first(),
            'popular_brands' => Category::query()->where('id',$category->id)->with('popularBrands')->first(),
        ];
        Cache::put('brandFromCategory_'.$category->id,json_encode($data),now()->addSeconds(300));
        return response($data,200);
    }




}
