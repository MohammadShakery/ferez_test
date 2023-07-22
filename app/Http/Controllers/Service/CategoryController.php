<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return response([
            'status' => true ,
            'categories' => Category::all()
        ],200);
    }

    public function show(Category $category)
    {
        return response([
            'status' => true ,
            'category' => $category
        ],200);
    }
}
