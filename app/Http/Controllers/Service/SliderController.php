<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function show(string $location)
    {
        return response([
            'status' => true ,
            'sliders' => Slider::query()->where('location',$location)
                ->orderByDesc('priority')->limit(5)->get()
        ],200);
    }
}
