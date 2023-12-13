<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\SpecialSaleStoreRequest;
use App\Models\Category;
use App\Models\specialSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SpecialSaleController extends Controller
{
    public function getSpecialSaleByPage(Request $request)
    {
        if(Cache::has('special_sale_'.$request->get('page')))
        {
            $data_array = (array)json_decode(Cache::get('special_sale_'.$request->get('page')));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $sales = specialSale::query()->with('images')->orderByDesc('created_at')->paginate(10);
        $data =array(
            'status' => true ,
            'sales' => $sales);
        Cache::put('special_sale_'.$request->get('page'),json_encode($data),now()->addSeconds(60));
        return response([
            'status' => true ,
            'sales' => $sales
        ],200);
    }

    public function getSpecialSale(specialSale $specialSale)
    {
        if(Cache::has('special_sale_id_'.$specialSale->id))
        {
            $data_array = (array)json_decode(Cache::get('special_sale_id_'.$specialSale->id));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $sale = $specialSale->load('images');
        $data =array(
            'status' => true ,
            'sale' => $sale
        );
        Cache::put('special_sale_id_'.$specialSale->id,json_encode($data),now()->addSeconds(60));
        return response([
            'status' => true ,
            'sale' => $sale
        ],200);

    }

    public function getSpecialSaleByCategory(Request $request, Category $category)
    {
        if(Cache::has('special_sale_by_category_id_'.$category->id.'_page_'.$request->get('page')))
        {
            $data_array = (array)json_decode(Cache::get('special_sale_by_category_id_'.$category->id.'_page_'.$request->get('page')));
            $data_array["cache"] = true;
            return response($data_array,200);
        }
        $sale = specialSale::query()->where('category_id',$category->id)->paginate();
        $data =array(
            'status' => true ,
            'sales' => $sale ,
            'category' => $category
        );
        Cache::put('special_sale_by_category_id_'.$category->id.'_page_'.$request->get('page'),json_encode($data),now()->addSeconds(60));
        return response($data,200);

    }



}
