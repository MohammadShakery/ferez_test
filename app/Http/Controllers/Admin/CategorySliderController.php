<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategorySliderStoreRequest;
use App\Http\Requests\Admin\CategorySliderUpdateRequest;
use App\Http\Requests\Admin\SliderStoreRequest;
use App\Http\Requests\Admin\SliderUpdateRequest;
use App\Models\Category;
use App\Models\categorySlider;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategorySliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'status' => true ,
            'sliders' => categorySlider::query()->orderByDesc('priority')->get()
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
    public function store(CategorySliderStoreRequest $request)
    {
        $name = uniqid();
        $guessExtension = $request->file('image')->guessExtension();
        $file = $request->file('image')->storeAs('public/images/category_sliders', $name.'.'.$guessExtension  );
        $path = Storage::url($file);
        $categorySlider = categorySlider::query()->create($request->all());
        $categorySlider->image = $path;
        $categorySlider->cdn_image = (new \App\S3\ArvanS3)->sendFile($path);
        $categorySlider->save();
        return response([
            'status' => true ,
            'slider' => $categorySlider ,
            'message' => 'اسلاید مورد نظر شما با موفقیت ایجاد گردید'
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(categorySlider $categorySlider)
    {
        return response([
            'status' => true ,
            'slider' => $categorySlider
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategorySliderUpdateRequest $request, categorySlider $categorySlider)
    {
        $categorySlider->update($request->all());
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/category_sliders', $name . '.' . $extension);
            $fileUrl = Storage::url($path);

            # Delete previous image if it exists
            if ($categorySlider->image) {
                Storage::delete(parse_url($categorySlider->image, PHP_URL_PATH));
            }
            if($categorySlider->cdn_image != null)
            {
                (new \App\S3\ArvanS3)->deleteFile($categorySlider->cdn_image);
            }
            $categorySlider->image = $fileUrl;
            $categorySlider->cdn_image = (new \App\S3\ArvanS3)->sendFile($fileUrl);
            $categorySlider->save();
        }

        return response([
            'status' => true ,
            'slider' => $categorySlider
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(categorySlider $categorySlider)
    {
        try {
            $categorySlider_clone = $categorySlider;
            $categorySlider->delete();
            if ($categorySlider_clone->image) {
                Storage::delete(parse_url($categorySlider_clone->image, PHP_URL_PATH));
            }
            if($categorySlider_clone->cdn_image != null)
            {
                (new \App\S3\ArvanS3)->deleteFile($categorySlider_clone->cdn_image);
            }
            return response([
                'status' => true ,
                'message' => 'اسلاید مورد نظر شما با موفقیت حذف گردید'
            ],200);
        }catch (\mysqli_sql_exception $exception)
        {
            return response([
                'status' => false ,
                'message' => 'امکان حذف اسلاید مورد نظر شما وجود ندارد'
            ],200);
        }
    }

    public function getWithCategory_id(Category $category)
    {
        return response([
            'status' => true ,
            'sliders' => categorySlider::query()->where('category_id',$category->id)->orderByDesc('priority')->get()
        ],200);
    }
}
