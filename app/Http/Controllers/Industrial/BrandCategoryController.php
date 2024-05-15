<?php

namespace App\Http\Controllers\Industrial;

use App\Http\Controllers\Controller;
use App\Http\Requests\Industrial\BrandCategoryStoreRequest;
use App\Models\Brand;
use App\Models\brandCategory;
use App\Models\User;
use Illuminate\Http\Request;


class BrandCategoryController extends Controller
{
    protected $user;
    protected $brand;
    public function __construct(Request $request)
    {
        $this->user = $this->getUser($request);
        $this->brand = Brand::query()->where('user_id',$this->user->id)->first();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response([
            'status' => true ,
            'brand_categories' => brandCategory::query()->where('brand_id',$this->brand->id)->get()
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
        $brand_category = brandCategory::query()->create([
            'name' => $request->get('name') ,
            'brand_id' => $this->brand->id
        ]);

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
        if($brandCategory->brand_id != $this->brand->id )
        {
            return  response([
                'status' => false,
                'message' => 'شما دسترسی به این برند کتگوری ندارید'
            ],200);
        }
        return response([
            'status' => true ,
            'brand_category' => $brandCategory
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandCategoryStoreRequest $request, brandCategory $brandCategory)
    {
        if($brandCategory->brand_id != $this->brand->id )
        {
            return  response([
                'status' => false,
                'message' => 'شما دسترسی به این برند کتگوری ندارید'
            ],200);
        }
        $brandCategory->update([$request->get('name')]);

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
        if($brandCategory->brand_id != $this->brand->id )
        {
            return  response([
                'status' => false,
                'message' => 'شما دسترسی به این برند کتگوری ندارید'
            ],200);
        }
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

    public function getUser(Request $request)
    {
        return User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->firstOrFail();
    }
}
