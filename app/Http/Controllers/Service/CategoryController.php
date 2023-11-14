<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if(Cache::has('categories_parent'))
        {
            $data_array = (array)json_decode(Cache::get('categories_parent'));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $data =array(
            'status' => true ,
            'categories' => Category::query()->where('parent_id',0)->get());
        Cache::put('categories_parent',json_encode($data),now()->addSeconds(300));
        return response($data,200);
    }

    public function getSubCategories(Category $category)
    {
        if(Cache::has('subCategory_'.$category->id))
        {
            $data_array = (array)json_decode(Cache::get('subCategory_'.$category->id));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $data =array(
            'status' => true ,
            'categories' => Category::query()->where('id',$category->id)->with('children')->firstOrFail());
        Cache::put('subCategory_'.$category->id,json_encode($data),now()->addSeconds(300));
        return response($data,200);

    }

    public function show(Category $category)
    {
        if(Cache::has('Category_'.$category->id))
        {
            $data_array = (array)json_decode(Cache::get('Category_'.$category->id));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $data =array(
            'status' => true ,
            'category' => $category);
        Cache::put('Category_'.$category->id,json_encode($data),now()->addSeconds(300));
        return response($data,200);

    }
}
