<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\ViolationStoreRequest;
use App\Models\Brand;
use App\Models\User;
use App\Models\Violation;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function store(Brand $brand, ViolationStoreRequest $request)
    {
        Violation::query()->create([
            'brand_id' => $brand->id ,
            'user_id' => ($this->getUser($request))->id ,
            'content' => $request->get('content')
        ]);

        return response([
            'status' => true ,
            'message' => 'درخواست تخلف این برند ایجاد گردید و توسط مدیر پیگیری می گردد.سپاس از گزارش شما'
        ],200);
    }

    public function getUser(Request $request)
    {
        return User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->firstOrFail();
    }
}
