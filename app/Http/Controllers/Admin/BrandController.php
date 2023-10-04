<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandStoreRequest;
use App\Http\Requests\Admin\UpdateStoreRequest;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'brands' => Brand::all() ,
            'status' => true
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandStoreRequest $request)
    {
        $category = Category::query()->where('id',$request->get('category_id'))->first();
        if ($category->parent_id == 0)
        {
            return  response([
                'status' => false ,
                'message' => 'امکان انتخاب دسته بندی اصلی برای ایجاد برند وجود ندارد'
            ],200);
        }
        $name = uniqid();
        $guessExtension = $request->file('image')->guessExtension();
        $file = $request->file('image')->storeAs('public/images/brands', $name.'.'.$guessExtension  );
        $path = Storage::url($file);
        $brand = Brand::query()->create($request->all());
        $brand->image = $path;
        $brand->save();
        return response([
            'status' => true ,
            'brand' => $brand ,
            'message' => 'برند مورد نظر شما با موفقیت ایجاد گردید'
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return response([
            'status' => true ,
            'brand' => $brand
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStoreRequest $request, Brand $brand)
    {
        $brand->update($request->all());
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/brands', $name . '.' . $extension);
            $fileUrl = Storage::url($path);

            # Delete previous image if it exists
            if ($brand->image) {
                Storage::delete(parse_url($brand->image, PHP_URL_PATH));
            }

            $brand->image = $fileUrl;
            $brand->save();
        }

        return response([
            'status' => true ,
            'brand' => $brand
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        try {
            $brand_clone = $brand;
            $brand->delete();
            if ($brand_clone->image) {
                Storage::delete(parse_url($brand_clone->image, PHP_URL_PATH));
            }
            return response([
                'status' => true ,
                'message' => 'برند مورد نظر شما با موفقیت حذف گردید'
            ],200);
        }catch (\mysqli_sql_exception $exception)
        {
            return response([
                'status' => false ,
                'message' => 'امکان حذف برند مورد نظر شما وجود ندارد'
            ],200);
        }
    }

    public function getBrandFromCategory(Category $category)
    {
        return response([
            'status' => true ,
            'brands' => Brand::query()->where('category_id',$category->id)->get()
        ],200);
    }
}
