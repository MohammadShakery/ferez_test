<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Http\Requests\Admin\UpdateStoreRequest;
use App\Models\brandCategory;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response([
            'status' => true ,
            'products' => Product::all()
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
    public function store(ProductStoreRequest $request)
    {
        $product = Product::query()->create($request->all());
        foreach ($request->file('images') as $image)
        {
            $file = $image;
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/product/'.$product->id, $name . '.' . $extension);
            $fileUrl = Storage::url($path);
            Image::query()->create([
                'product_id' => $product->id ,
                'src' => $fileUrl ,
                'cdn_image' => (new \App\S3\ArvanS3)->sendFile($fileUrl)
            ]);
        }
        if ($request->hasFile('multidimensional_view')) {
            $file = $request->file('multidimensional_view');
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/product/'.$product->id.'/multidimensional_view', $name . '.' . $extension);
            $fileUrl = Storage::url($path);

            $product->multidimensional_view = $fileUrl;
            $product->save();
        }
        return response([
            'status' => true ,
            'product' => Product::query()->where('id',$product->id)->with('images')->first()
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return response([
            'status' => true ,
            'product' => Product::query()->where('id',$product->id)->with('images')->first()
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());
        if($request->has('images'))
        {
            foreach ($request->file('images') as $image)
            {
                $file = $image;
                $name = uniqid();
                $extension = $file->getClientOriginalExtension();
                $path = $file->storeAs('public/images/product/'.$product->id, $name . '.' . $extension);
                $fileUrl = Storage::url($path);
                Image::query()->create([
                    'product_id' => $product->id ,
                    'src' => $fileUrl ,
                    'cdn_image' => (new \App\S3\ArvanS3)->sendFile($fileUrl)
                ]);
            }
        }
        if ($request->hasFile('multidimensional_view')) {
            $file = $request->file('multidimensional_view');
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/product/'.$product->id.'/multidimensional_view', $name . '.' . $extension);
            $fileUrl = Storage::url($path);

            # Delete previous image if it exists
            if ($product->multidimensional_view) {
                Storage::delete(parse_url($product->multidimensional_view, PHP_URL_PATH));
            }

            $product->multidimensional_view = $fileUrl;
            $product->save();
        }
        return response([
            'status' => true ,
            'product' => Product::query()->where('id',$product->id)->with('images')->first()
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product_clone = $product;
            $product->delete();
            if ($product_clone->multidimensional_view) {
                Storage::delete(parse_url($product_clone->multidimensional_view, PHP_URL_PATH));
            }
            $images = Image::query()->where('product_id',$product_clone->id)->get();
            foreach ($images as $image)
            {
                if ($image->src) {
                    Storage::delete(parse_url($image->src, PHP_URL_PATH));
                }
                if($image->cdn_image != null)
                {
                    (new \App\S3\ArvanS3)->deleteFile($image->cdn_image);
                }
                $image->delete();
            }
            return response([
                'status' => true ,
                'message' => 'محصول مورد نظر شما با موفقیت حذف گردید'
            ],200);
        }catch (\mysqli_sql_exception $exception)
        {
            return response([
                'status' => false ,
                'message' => 'امکان حذف محصول مورد نظر شما وجود ندارد'
            ],200);
        }
    }

    public function deleteImage(Image $image)
    {
        try {
            if ($image->src) {
                Storage::delete(parse_url($image->src, PHP_URL_PATH));
            }
            $image->delete();
            return response([
                'status' => true ,
                'message' => 'عکس محصول مورد نظر شما با موفقیت حذف گردید'
            ],200);
        }catch (\mysqli_sql_exception $exception)
        {
            return response([
                'status' => false ,
                'message' => 'امکان حذف این عکس وجود ندارد'
            ],200);
        }
    }

    public function getProductByBrandCategory(brandCategory $brandCategory)
    {
        $brandCategory_with_products = $brandCategory->load('products');
        return response([
            'status' => true ,
            'products' => $brandCategory_with_products
        ],200);
    }
}
