<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndustrialStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;

class IndustrialController extends Controller
{
    public function index()
    {
        return response([
            'status' => true ,
            'industrial_requests' => \App\Models\Request::query()->where('checked',false)->orderBy('created_at')->get()
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
            $user = User::query()->where('phone',(json_decode($request->data))->phone)->first();
            $user->update([
                'is_industrial' => true
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
