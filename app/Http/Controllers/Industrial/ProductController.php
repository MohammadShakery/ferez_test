<?php

namespace App\Http\Controllers\Industrial;

use App\Http\Controllers\Controller;
use App\Http\Requests\Industrial\ProductStoreRequest;
use App\Http\Requests\Industrial\ProductUpdateRequest;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\brandCategory;
use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $user;
    protected $brand;
    public function __construct(Request $request)
    {
        $this->user = $this->getUser($request);
        $this->brand = Brand::query()->where('user_id',$this->user->id)->first();
    }

    public function getUser(Request $request)
    {
        return User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->firstOrFail();
    }

    public function index()
    {
        return response([
            'status' => true,
            'products' => $this->brand->products
        ], 200);
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
        $brand_category = brandCategory::query()->where('id',$request->get('brand_category_id'))->first();
        if($brand_category->brand_id != $this->brand->id)
        {
            return  response([
                'status' => false ,
                'message' => 'شما دسترسی به این زیر دسته بندی ندارید'
            ],200);
        }
        $product = Product::query()->create([
            'name' => $request->get('name') ,
            'price' => $request->get('price') ,
            'link' => $request->get('link') ,
            'off' => $request->get('off') ,
            'brand_category_id' => $request->get('brand_category_id')  ,
            'description' => $request->get('description')
        ]);
        foreach ($request->file('images') as $image) {
            $file = $image;
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/product/' . $product->id, $name . '.' . $extension);
            $fileUrl = Storage::url($path);
            Image::query()->create([
                'product_id' => $product->id,
                'src' => $fileUrl,
                'cdn_image' => (new \App\S3\ArvanS3)->sendFile($fileUrl)
            ]);
        }
        if ($request->has('keys') and $request->has('values')) {
            if (sizeof($request->get('keys')) != sizeof($request->get('values'))) {
                return response([
                    'status' => false,
                    'message' => 'تعداد کلید های ارسالی با جواب ها برابر نمی باشد'
                ], 200);
            }
            $keys = $request->get('keys');
            $values = $request->get('values');
            foreach ($keys as $k => $key) {
                Attribute::query()->insert([
                    'product_id' => $product->id,
                    'key' => $key,
                    'value' => $values[$k]
                ]);
            }
        }
        if ($request->hasFile('multidimensional_view')) {
            $file = $request->file('multidimensional_view');
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/product/' . $product->id . '/multidimensional_view', $name . '.' . $extension);
            $fileUrl = Storage::url($path);

            $product->multidimensional_view = $fileUrl;
            $product->save();
        }
        return response([
            'status' => true,
            'product' => $product->load(['images', 'attributes'])
        ], 200);
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
        if($product->brand_category->brand_id != $this->brand->id)
        {
            return  response([
                'status' => true ,
                'message' => 'شما دسترسی به این محصول ندارید'
            ],200);
        }
        return response([
            'status' => true,
            'product' => Product::query()->where('id', $product->id)->with(['images', 'attributes'])->first()
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $brand_category = brandCategory::query()->where('id',$request->get('brand_category_id'))->first();
        if($brand_category->brand_id != $this->brand->id)
        {
            return  response([
                'status' => false ,
                'message' => 'شما دسترسی به این زیر دسته بندی ندارید'
            ],200);
        }
        $product->update([
            'name' => $request->get('name') ,
            'price' => $request->get('price') ,
            'link' => $request->get('link') ,
            'off' => $request->get('off') ,
            'brand_category_id' => $request->get('brand_category_id')  ,
            'description' => $request->get('description')
        ]);
        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $file = $image;
                $name = uniqid();
                $extension = $file->getClientOriginalExtension();
                $path = $file->storeAs('public/images/product/' . $product->id, $name . '.' . $extension);
                $fileUrl = Storage::url($path);
                Image::query()->create([
                    'product_id' => $product->id,
                    'src' => $fileUrl,
                    'cdn_image' => (new \App\S3\ArvanS3)->sendFile($fileUrl)
                ]);
            }
        }
        if ($request->has('keys') and $request->has('values')) {
            if (sizeof($request->get('keys')) != sizeof($request->get('values'))) {
                return response([
                    'status' => false,
                    'message' => 'تعداد کلید های ارسالی با جواب ها برابر نمی باشد'
                ], 200);
            }
            $keys = $request->get('keys');
            $values = $request->get('values');
            foreach ($keys as $k => $key) {
                Attribute::query()->insert([
                    'product_id' => $product->id,
                    'key' => $key,
                    'value' => $values[$k]
                ]);
            }
        }
        if ($request->hasFile('multidimensional_view')) {
            $file = $request->file('multidimensional_view');
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/product/' . $product->id . '/multidimensional_view', $name . '.' . $extension);
            $fileUrl = Storage::url($path);

            # Delete previous image if it exists
            if ($product->multidimensional_view) {
                Storage::delete(parse_url($product->multidimensional_view, PHP_URL_PATH));
            }

            $product->multidimensional_view = $fileUrl;
            $product->save();
        }
        return response([
            'status' => true,
            'product' => $product->load(['images', 'attributes'])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if($product->brand_category->brand_id != $this->brand->id)
        {
            return  response([
                'status' => true ,
                'message' => 'شما دسترسی به این محصول ندارید'
            ],200);
        }
        try {
            $product_clone = $product;
            $product->delete();
            if ($product_clone->multidimensional_view) {
                Storage::delete(parse_url($product_clone->multidimensional_view, PHP_URL_PATH));
            }
            $images = Image::query()->where('product_id', $product_clone->id)->get();
            foreach ($images as $image) {
                if ($image->src) {
                    Storage::delete(parse_url($image->src, PHP_URL_PATH));
                }
                if ($image->cdn_image != null) {
                    (new \App\S3\ArvanS3)->deleteFile($image->cdn_image);
                }
                $image->delete();
            }
            return response([
                'status' => true,
                'message' => 'محصول مورد نظر شما با موفقیت حذف گردید'
            ], 200);
        } catch (\mysqli_sql_exception $exception) {
            return response([
                'status' => false,
                'message' => 'امکان حذف محصول مورد نظر شما وجود ندارد'
            ], 200);
        }
    }

    public function deleteImage(Image $image)
    {
        if($image->product->brand_category->brand_id != $this->brand->id)
        {
            return  response([
                'status' => true ,
                'message' => 'شما دسترسی به این عکس محصول ندارید'
            ],200);
        }
        try {
            if ($image->src) {
                Storage::delete(parse_url($image->src, PHP_URL_PATH));
            }
            $image->delete();
            return response([
                'status' => true,
                'message' => 'عکس محصول مورد نظر شما با موفقیت حذف گردید'
            ], 200);
        } catch (\mysqli_sql_exception $exception) {
            return response([
                'status' => false,
                'message' => 'امکان حذف این عکس وجود ندارد'
            ], 200);
        }
    }


    public function deleteAttribute(Attribute $attribute)
    {
        if($attribute->product->brand_category->brand_id != $this->brand->id)
        {
            return  response([
                'status' => true ,
                'message' => 'شما دسترسی به این ویژگی محصول ندارید'
            ],200);
        }
        try {
            $attribute->delete();
            return response([
                'status' => true,
                'message' => 'مشخصه مورد نظر شما با موفقیت حذف گردید'
            ], 200);
        } catch (\Exception $exception) {
            return response([
                'status' => false,
                'message' => 'امکان حذف این مشخصه وجود ندارد'
            ], 200);
        }
    }
}
