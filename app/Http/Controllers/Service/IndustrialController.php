<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\IndustrialCheckRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IndustrialController extends Controller
{
    public function CheckIndustrial(IndustrialCheckRequest $request)
    {
        if(\App\Models\Request::query()->where('user_id',($this->getUser($request))->id)->where('checked',false)->exists())
        {
            return response([
                'status' => false ,
                'message' => 'شما در حال حاضر یک درخواست بررسی نشده دارید.'
            ],200);
        }
        $file = $request->file('image');
        $name = uniqid();
        $extension = $file->getClientOriginalExtension();
        $path = $file->storeAs('public/image/request/industrial', $name . '.' . $extension);
        $fileUrl = Storage::url($path);
        $data = array(
            'name' => $request->get('brand') ,
            'image' => $fileUrl ,
            'address' => $request->get('address') ,
            'phone' => $request->get('phone') ,
        );

        \App\Models\Request::query()->create([
            'type' => 'industrial' ,
            'data' => json_encode($data) ,
            'user_id' => ($this->getUser($request))->id
        ]);

        return response([
            'status' => true ,
            'message' => 'درخواست شما با موفقیت ایجاد شد و پس از بررسی با شما تماس گرفته خواهد شد.'
        ],200);
    }

    public function getUser(Request $request)
    {
        return User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->firstOrFail();
    }
}
