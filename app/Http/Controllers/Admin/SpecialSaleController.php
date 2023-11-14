<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SpecialSaleStoreRequest;
use App\Http\Requests\Admin\SpecialSaleUpdateRequest;
use App\Models\Brand;
use App\Models\specialSale;
use App\Models\specialSaleImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SpecialSaleController extends Controller
{
    public function index()
    {
        return response([
            'status' => true,
            'sales' => specialSale::query()->with('images')->orderByDesc('created_at')->get()
        ]);
    }

    public function show(specialSale $specialSale)
    {
        $special_sale2 = specialSale::query()->where('id',$specialSale->id)->with('images')->firstOrFail();
        return response([
            'status' => true ,
            'sale' => $special_sale2
        ],200);
    }

    public function store(SpecialSaleStoreRequest $request)
    {
        $specialSale = specialSale::query()->create([
            'title' => $request->get('title') ,
            'description' => $request->get('description') ,
            'price' => $request->get('price') ,
            'percent' => $request->get('percent') ,
            'contact' => $request->get('contact')
        ]);
        foreach ($request->file('images') as $image)
        {
            $name = uniqid();
            $guessExtension = $image->guessExtension();
            $file = $image->storeAs('public/images/special_sale', $name.'.'.$guessExtension );
            $path = Storage::url($file);
            specialSaleImage::query()->create([
                'special_sale_id' => $specialSale->id ,
                'src' => $path ,
                'cdn_image' => (new \App\S3\ArvanS3)->sendFile($path)
            ]);
        }
        return response([
            'status' => true ,
            'message' => 'محصول مورد نظر شما در بخش فروش ویژه قرار گرفت'
        ],200);
    }

    public function update(SpecialSaleUpdateRequest $request, specialSale $specialSale)
    {
        $specialSale->update($request->all());
        if($request->has('images'))
        {
            foreach ($request->file('images') as $image)
            {
                $name = uniqid();
                $guessExtension = $image->guessExtension();
                $file = $image->storeAs('public/images/special_sale', $name.'.'.$guessExtension );
                $path = Storage::url($file);
                specialSaleImage::query()->create([
                    'special_sale_id' => $specialSale->id ,
                    'src' => $path ,
                    'cdn_image' => (new \App\S3\ArvanS3)->sendFile($path)
                ]);
            }
        }
        return response([
            'status' => true ,
            'message' => 'محصول فروش ویژه شما با موفقیت بروزرسانی گردید'
        ],200);
    }

    public function deleteImage(specialSaleImage $specialSaleImage)
    {
        try {
            $specialSaleImage->delete();
            return response([
                'status' => true ,
                'message' => 'تصویر مورد نظر شما با موفقیت حذف گردید'
            ],200);
        }catch (\mysqli_sql_exception $exception)
        {
            return response([
                'status' => false ,
                'message' => 'امکان حذف تصویر مورد نظر وجو ندارد'
            ],200);
        }
    }

    public function delete(specialSale $specialSale)
    {
        try {
            DB::transaction(function () use ($specialSale) {
                foreach ($specialSale->images as $image)
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
                $specialSale->delete();
            });
            return response([
                'status' => true ,
                'message' => 'این محصول از فروش وِیژه حذف گردید'
            ],200);
        }
        catch (\mysqli_sql_exception $exception)
        {
            return response([
                'status' => false ,
                'message' => 'امکان حذف این محصول از فروش ویژه وجود ندارد'
            ],200);
        }
    }

}
