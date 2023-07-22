<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

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
            'brand' => Brand::query()->where('id',$brand->id)->with('category')->first()
        ],200);
    }

    public function brandFromCategory(Category $category)
    {
        return response([
            'status'         => true ,
            'brands'         => Category::query()->where('id',$category->id)->with('brands')->first() ,
            'new_brands'     => Category::query()->where('id',$category->id)->with('newBrands')->first(),
            'popular_brands' => Category::query()->where('id',$category->id)->with('popularBrands')->first(),
        ],200);
    }




}
