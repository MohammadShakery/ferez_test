<?php

namespace App\Http\Controllers\Industrial;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandCategoryStoreRequest;
use App\Http\Requests\Admin\BrandCategoryUpdateRequest;
use App\Models\Brand;
use App\Models\brandCategory;

class BrandCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Brand $brand)
    {
        return response([
            'status' => true ,
            'brand_categories' => brandCategory::query()->where('brand_id',$brand->id)->get()
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandCategoryStoreRequest $request)
    {
        $brand_category = brandCategory::query()->create([$request->all()]);

        return response([
            'status' => true ,
            'brand_category' => $brand_category
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(brandCategory $brandCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(brandCategory $brandCategory)
    {
        return response([
            'status' => true ,
            'brand_category' => $brandCategory
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandCategoryUpdateRequest $request, brandCategory $brandCategory)
    {
        $brandCategory->update($request->all());

        return response([
            'status' => true ,
            'brand_category' => $brandCategory
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(brandCategory $brandCategory)
    {
        try {
            $brandCategory->delete();
            return response([
                'status' => true ,
                'message' => 'دسته بندی بندی برند مورد نظر شما با موفقیت حذف گردید'
            ],200);
        }catch (\mysqli_sql_exception $exception)
        {
            return response([
                'status' => false ,
                'message' => 'امکان حذف دسته بندی برند مورد نظر شما وجود ندارد'
            ],200);
        }
    }
}
