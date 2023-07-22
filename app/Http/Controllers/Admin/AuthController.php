<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginStoreRequest;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginStoreRequest $request)
    {
        if(!Admin::query()->where('tell',$request->get('tell'))->exists())
        {
            return response([
                'status' => false ,
                'message' => 'اطلاعات وارد شده صحیح نمی باشد'
            ],200);
        }
        $admin = Admin::query()->where('tell',$request->get('tell'))->first();
        if(Hash::check($request->get('password'),$admin->password))
        {
            return response([
                'status' => false ,
                'message' => 'اطلاعات وارد شده صحیح نمی باشد'
            ],200);
        }
        $admin->update([
            'token' => encrypt($admin->mail)
        ]);
        return response([
            'status' => true ,
            'token' => $admin->token ,
            'message' => 'شما با موفقیت وارد شدید'
        ],200);
    }
}
