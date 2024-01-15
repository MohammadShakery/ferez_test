<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndustrialStoreRequest;
use App\Models\Brand;
use App\Models\User;

class IndustrialController extends Controller
{
    public function index()
    {
        return response([
            'status' => true ,
            'industrial_requests' => \App\Models\Request::query()->where('checked',false)->orderBy('created_at')->with('user')->get()
        ],200);
    }

    public function store(IndustrialStoreRequest $industrial_request,\App\Models\Request $request)
    {
        if($request->checked == true)
        {
            return  response([
                'status' => false ,
                'message' => 'این درخواست قبلا تعیین وضعیت شده است و امکان تغییر آن وجود ندارد'
            ],200);
        }
        if($industrial_request->get('status') == 1)
        {
            $user = User::query()->where('id',$request->user_id)->first();
            $user->update([
                'is_industrial' => true
            ]);
            $data = json_decode($request->data);
            Brand::query()->create([
                'name' => $data->name ,
                'user_id' => $request->user_id ,
                'category_id' => $data->category,
                'image' => $data->image ,
                'status' => false
            ]);
        }
        $request->update([
            'checked' => true
        ]);

        return response([
            'status' => true ,
            'message' => 'درخواست با موفقیت تایید شد'
        ],200);
    }
}
