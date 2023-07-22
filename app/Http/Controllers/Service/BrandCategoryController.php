<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandCategoryController extends Controller
{
    public function index(Brand $brand)
    {
        $brand->update([
            'view' => $brand->view++
        ]);
        return response([
            'status' => true ,
            'brand' => Brand::query()->where('id',$brand->id)->with(['brandCategory','comments'])->first()
        ],200);
    }
}
