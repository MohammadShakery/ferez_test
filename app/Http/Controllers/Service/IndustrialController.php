<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\IndustrialCheckRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IndustrialController extends Controller
{
    public function CheckIndustrial(IndustrialCheckRequest $request)
    {
        $user = $this->getUser($request);
        if (Brand::query()->where('user_id', $user->id)->exists())
        {
            return response([
                'status' => false ,
                'message' => 'شما هم اکنون نیز صاحب یک برند هستید.'
            ],200);
        }
        if(\App\Models\Request::query()->where('user_id',$user->id)->where('checked',false)->exists())
        {
            return response([
                'status' => false ,
                'message' => 'شما در حال حاضر یک درخواست بررسی نشده دارید.'
            ],200);
        }
        $category = Category::query()->where('id',$request->get('category_id'))->first();
        if($category->parent_id == 0)
        {
            return response([
                'status' => false ,
                'message' => 'زیر دسته برند خود را انتخاب نمایید'
            ],200);
        }
        if(Brand::query()->where('name',$request->get('brand'))->exists())
        {
            return response([
                'status' => false ,
                'message' => 'این برند قبلا در فرز ثبت شده است!!'
            ],200);
        }
        $file = $request->file('image');
        $name = uniqid();
        $extension = $file->getClientOriginalExtension();
        $path = $file->storeAs('public/images/request/industrial', $name . '.' . $extension);
        $fileUrl = Storage::url($path);
        $data = array(
            'name' => $request->get('brand') ,
            'image' => $fileUrl ,
            'category' => $category->id ,
        );

        \App\Models\Request::query()->create([
            'type' => 'industrial' ,
            'data' => json_encode($data) ,
            'user_id' => $user->id
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
