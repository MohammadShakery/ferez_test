<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Violation;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function index()
    {
        return response([
            'status' => true ,
            'violations' => Violation::query()->orderByDesc('created_at')->get()
        ],200);
    }
}
