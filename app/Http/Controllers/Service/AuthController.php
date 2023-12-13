<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\AuthStoreRequest;
use App\Http\Requests\Service\RegisterRequest;
use App\Http\Requests\Service\VerifyOTPRequest;
use App\Models\Network;
use App\Models\Setting;
use App\Models\User;
use App\Services\SMS\SmsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuthController extends Controller
{


    public function store(AuthStoreRequest $request)
    {
        if(User::query()->where('phone',$request->get('phone'))->exists())
        {
            return $this->login($request);
        }
        return $this->register($request);
    }

    public function login(Request $request)
    {
        $user = User::query()->where('phone',$request->get('phone'))->first();
        if($user->otp_expiration > Carbon::now()->addMinutes(5))
        {
            return response([
                'status' => false ,
                'message' => 'کد ارسال شده قبلی معتبر می باشد'
            ],200);
        }
        $sms_api = new SmsService();
        $code = rand(10000,99999);
        $sms_api->OTP($user->phone,$code);
        $user->update([
            'otp_code' => $code ,
            'otp_expiration' => Carbon::now()->addMinutes(20)
        ]);
        return response([
            'status' => true ,
            'message' => 'کد احراز هویت به شماره همراه شما ارسال گردید',
            'register' => false

        ],200);

    }

    public function register(Request $request)
    {
        $sms_api = new SmsService();
        $code = rand(10000,99999);
        $user = User::query()->create([
            'otp_code'       => $code ,
            'otp_expiration' => Carbon::now()->addMinutes(20) ,
            'phone'          => $request->get('phone')
        ]);
        $this->createDefaultsNetworkUser($user);
        $sms_api->OTP($user->phone,$code);

        return response([
            'status' => true ,
            'message' => 'ثبت نام انجام شد و کد احراز هویت به شماره همراه شما ارسال گردید' ,
            'register' => true
        ],200);
    }

    public function VerifyOTP(VerifyOTPRequest $request)
    {
        $user = User::query()->where('phone',$request->get('phone'))->firstOrFail();
        if($user->otp_expiration < Carbon::now())
        {
            return response([
                'status' => false ,
                'message' => 'کد ارسال شده منقضی شده است لطفا دوباره امتحان کنید'
            ],200);
        }
        if($request->get('code') != $user->otp_code)
        {
            return  response([
                'status' => false ,
                'message' => 'کد ارسال شده صحبح نمی باشد'
            ],200);
        }
        $token = encrypt(array(
            'AMSN' => $user->id ,
            'BMSN' => $user->phone
        ));
        $user->update([
            'token' => $token ,
            'name' => $request->get('name'),
            'email' => $request->get('email'),
        ]);

        return response([
            'status' => true ,
            'user' => $user ,
            'message' => 'کد احراز هویت شما تایید شد و شما با موفقیت وارد شدید' ,
            'token' => $token ,
        ],200);
    }

    public function createDefaultsNetworkUser(User $user)
    {
        Network::query()->create([
            'name' => "همکاران" ,
            'user_id' => $user->id ,
            'icon' => 'star.png'
        ]);
        Network::query()->create([
            'name' => "تامین کنندگان" ,
            'user_id' => $user->id ,
            'icon' => 'truck.png'
        ]);
        Network::query()->create([
            'name' => "خریداران" ,
            'user_id' => $user->id ,
            'icon' => 'box.png'
        ]);
    }


}
