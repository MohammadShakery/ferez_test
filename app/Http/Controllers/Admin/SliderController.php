<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SliderStoreRequest;
use App\Http\Requests\Admin\SliderUpdateRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'status' => true ,
            'sliders' => Slider::query()->orderByDesc('priority')->get()
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
    public function store(SliderStoreRequest $request)
    {
        $name = uniqid();
        $guessExtension = $request->file('image')->guessExtension();
        $file = $request->file('image')->storeAs('public/images/sliders', $name.'.'.$guessExtension  );
        $path = Storage::url($file);
        $slider = Slider::query()->create($request->all());
        $slider->image = $path;
        $slider->save();
        return response([
            'status' => true ,
            'slider' => $slider ,
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
    public function edit(Slider $slider)
    {
        return response([
            'status' => true ,
            'slider' => $slider
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SliderUpdateRequest $request, Slider $slider)
    {
        $slider->update($request->all());
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/sliders', $name . '.' . $extension);
            $fileUrl = Storage::url($path);

            # Delete previous image if it exists
            if ($slider->image) {
                Storage::delete(parse_url($slider->image, PHP_URL_PATH));
            }

            $slider->image = $fileUrl;
            $slider->save();
        }

        return response([
            'status' => true ,
            'slider' => $slider
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        try {
            $slider_clone = $slider;
            $slider->delete();
            if ($slider_clone->image) {
                Storage::delete(parse_url($slider_clone->image, PHP_URL_PATH));
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

    public function getWithLocation(string $location)
    {
        return response([
            'status' => true ,
            'sliders' => Slider::query()->where('location',$location)->orderByDesc('priority')->get()
        ],200);
    }
}
