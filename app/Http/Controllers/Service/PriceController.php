<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\categoryPrice;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PriceController extends Controller
{
    public function price()
    {
        if(Cache::has('price'))
        {
            $data_array = (array)json_decode(Cache::get('price'));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $data = array(
            'status'         => true ,
            'price'          => Price::query()->with('category')->get()
        );
        Cache::put('price',json_encode($data),now()->addSeconds(100));
        return response($data,200);
    }

    public function priceCategories()
    {
        if(Cache::has('category_price'))
        {
            $data_array = (array)json_decode(Cache::get('category_price'));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $data = array(
            'status'         => true ,
            'price'          => categoryPrice::all()
        );
        Cache::put('category_price',json_encode($data),now()->addSeconds(500));
        return response($data,200);
    }

    public function getPriceByCategory(categoryPrice $categoryPrice)
    {
        if(Cache::has('category_'.$categoryPrice->id.'_price'))
        {
            $data_array = (array)json_decode(Cache::get('category_'.$categoryPrice->id.'_price'));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $data = array(
            'status'         => true ,
            'price'          => Price::query()->where('category_price_id',$categoryPrice->id)->get()
        );
        Cache::put('category_'.$categoryPrice->id.'_price',json_encode($data),now()->addSeconds(100));
        return response($data,200);
    }
}
