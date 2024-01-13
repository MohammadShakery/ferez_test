<?php

namespace App\Http\Controllers\Industrial;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    protected $user;
    protected $brand;
    public function __construct(Request $request)
    {
        $this->user = $this->getUser($request);
        $this->brand = Brand::query()->where('user_id',$this->user->id)->first();
    }
    public function getUser(Request $request)
    {
        return User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->firstOrFail();
    }

    public function store(Request $request){
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/images/brands', $name . '.' . $extension);
            $fileUrl = Storage::url($path);

            # Delete previous image if it exists
            if ($this->brand->image) {
                Storage::delete(parse_url($this->brand->image, PHP_URL_PATH));
            }

            $this->brand->image = $fileUrl;

            $this->brand->cdn_image = (new \App\S3\ArvanS3)->sendFile($path);
            $this->brand->save();
        }
        $this->brand->update([
            'tell' => $request->get('tell') ,
            'description' => $request->get('description') ,
            'address' => $request->get('address') ,
            'site' => $request->get('site') ,
            'instagram' => $request->get('instagram') ,
            'email' => $request->get('email'),
            'whatsapp' => $request->get('whatsapp') ,
            'linkedin' => $request->get('linkedin') ,
            'telegram' => $request->get('telegram')
        ]);

        return response([
            'status' => true ,
            'message' => 'اطلاعات برند شما با موفقیت بروز گردید' ,
            'brand' => $this->brand
        ],200);
    }

}
