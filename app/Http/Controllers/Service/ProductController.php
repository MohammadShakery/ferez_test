<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        if(Cache::has('product_'.$product->id))
        {
            $data_array = (array)json_decode(Cache::get('product_'.$product->id));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $data =array(
            'status' => true ,
            'product' => $product->load(['brand_category','attributes','images']));
        Cache::put('product_'.$product->id,json_encode($data),now()->addSeconds(300));
        return response($data,200);
    }
}
