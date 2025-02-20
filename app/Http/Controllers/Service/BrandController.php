<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if(Cache::has('brands'))
        {
            $data_array = (array)json_decode(Cache::get('brands'));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $data =array(
            'status' => true ,
            'brands' => Brand::query()->where('status',true)->orderByDesc('priority')->get());
        Cache::put('brands',json_encode($data),now()->addSeconds(300));
        return response($data,200);

    }

    public function show(Brand $brand)
    {
        $this->addView($brand);
        if(Cache::has('brand'.$brand->id))
        {
            $data_array = (array)json_decode(Cache::get('brand'.$brand->id));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $data =array(
            'status' => true ,
            'brand' => Brand::query()->where('status',true)->where('id',$brand->id)->with(['brandCategory' => function($query){
                $query->with(['products' => function($query){
                    $query->with(['attributes']);
                }]);
            },'comments' => function($query){
                $query->with('user');
            } ,'category'])->firstOrFail());
        Cache::put('brand'.$brand->id,json_encode($data),now()->addSeconds(300));
        return response($data,200);
    }

    public function brandFromCategory(Category $category)
    {
        if(Cache::has('brandFromCategory_'.$category->id))
        {
            $data_array = (array)json_decode(Cache::get('brandFromCategory_'.$category->id));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $data =array(
            'status'         => true ,
            'brands'         => Category::query()->where('id',$category->id)->with('brands' )->first() ,
            'new_brands'     => Category::query()->where('id',$category->id)->with('newBrands')->first(),
            'popular_brands' => Category::query()->where('id',$category->id)->with('popularBrands')->first());
        Cache::put('brandFromCategory_'.$category->id,json_encode($data),now()->addSeconds(300));
        return response($data,200);
    }

    public function searchInBrands($slug)
    {
        return response([
            'status' => true ,
            'brands' => Brand::query()->where('name','like',"%{$slug}%")->paginate(10)
        ],200);
    }

    public function searchInProducts($slug)
    {
        return response([
            'status' => true ,
            'products' => Product::query()->with(['brand_category' => function($query){
                $query->with(['brand' => function($query2) {
                    $query2->select(['name','id']);
                }])->select(['id','brand_id']);
            }])->where('name','like',"%{$slug}%")->paginate(10)
        ],200);
    }

    public function addView(Brand $brand)
    {
        if(Cache::has('view_'.$brand->id))
        {
            $view = (Cache::get('view_'.$brand->id));
            Cache::put('view_'.$brand->id,$view+1);
        }
        else
        {
            Cache::put('view_'.$brand->id,1);
        }
    }


}
