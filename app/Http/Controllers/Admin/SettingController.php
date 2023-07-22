<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingDefaultModuleRequest;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function DefaultModule(SettingDefaultModuleRequest $request)
    {
        if(Setting::query()->where('lable','default_modules')->exists())
        {
            $setting = Setting::query()->where('lable','default_modules')->first();
            $setting->update([
                'date' => json_encode($request->get('modules'))
            ]);

            return response([
                'status' => true ,
                'message' => 'ماژول های فعال پیشفرض کاربران بروزرسانی گردید'
            ],200);
        }
        Setting::query()->create([
            'lable' => "default_modules" ,
            'data' => json_encode($request->get('modules'))
        ]);

        return response([
            'status' => true ,
            'message' => 'ماژول های فعال پیشفرض کاربران بروزرسانی گردید'
        ],200);
    }
}
