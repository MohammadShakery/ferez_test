<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryStoreRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'status' => true ,
            'categories' => Category::query()->where('parent_id',0)->orderBy('created_at')->get()
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
    public function store(CategoryStoreRequest $request)
    {
        $name = uniqid();
        $guessExtension = $request->file('image')->guessExtension();
        $file = $request->file('image')->storeAs('public/images/categories', $name.'.'.$guessExtension  );
        $path = Storage::url($file);
        $category = Category::query()->create($request->all());
        $category->icon = $path;
        $category->cdn_icon = (new \App\S3\ArvanS3)->sendFile($path);

        $category->save();
        return response([
            'status' => true ,
            'category' => $category ,
            'message' => 'دسته بندی مورد نظر شما با موفقیت ایجاد گردید'
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return response([
            'status' => true ,
            'category' => Category::query()->where('id',$category->id)->with('parent')->first()
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $category->update($request->all());
        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/categories', $name . '.' . $extension);
            $fileUrl = Storage::url($path);

            // Delete previous image if it exists
            if ($category->icon) {
                Storage::delete(parse_url($category->icon, PHP_URL_PATH));
            }
            if($category->cdn_icon != null)
            {
                (new \App\S3\ArvanS3)->deleteFile($category->cdn_icon);
            }
            $category->icon = $fileUrl;
            $category->cdn_icon = (new \App\S3\ArvanS3)->sendFile($fileUrl);
            $category->save();
        }


        return response([
            'status' => true,
            'category' => $category,
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category_clone = $category;
            $category->delete();
            if ($category_clone->icon) {
                Storage::delete(parse_url($category_clone->icon, PHP_URL_PATH));
            }
            if($category_clone->cdn_icon != null)
            {
                (new \App\S3\ArvanS3)->deleteFile($category_clone->cdn_icon);
            }
            return response([
                'status' => true ,
                'message' => 'دسته بندی مورد نظر شما با موفقیت حذف گردید'
            ],200);
        }catch (\mysqli_sql_exception $exception)
        {
            return response([
                'status' => false ,
                'message' => 'امکان حذف دسته بندی مورد نظر شما وجود ندارد'
            ],200);
        }
    }

    public function getSubCategories(Category $category)
    {
        return response([
            'categories' => Category::query()->where('parent_id',$category->id)->orderBy('name')->get()
        ],200);
    }
}
