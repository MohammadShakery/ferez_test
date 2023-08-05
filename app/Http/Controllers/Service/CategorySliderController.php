<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\categorySlider;
use Illuminate\Http\Request;

class CategorySliderController extends Controller
{
    public function show(Category $category)
    {
        return response([
            'status' => true ,
            'sliders' => categorySlider::query()->where('category_id',$category->id)
            ->orderByDesc('priority')->limit(5)->get()
        ],200);
    }
}
