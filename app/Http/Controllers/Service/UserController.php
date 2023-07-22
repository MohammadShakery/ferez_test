<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\ProfileStoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return response([
            'status' => true ,
            'user' => User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->with('modules')->firstOrFail()
        ],200);
    }

    public function getUser(Request $request)
    {
        return User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->firstOrFail();
    }

    public function store(ProfileStoreRequest $request)
    {
        $user = $this->getUser($request);
        $user->update($request->only(['email','name']));
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = uniqid();
            $extension = $file->getClientOriginalExtension();
            $path = $file->storeAs('public/image/users', $name . '.' . $extension);
            $fileUrl = Storage::url($path);

            # Delete previous image if it exists
            if ($user->image) {
                Storage::delete(parse_url($user->image, PHP_URL_PATH));
            }

            $user->image = $fileUrl;
            $user->save();
        }
        return response([
            'status' => true ,
            'message' => 'پروفایل شما بروزرسانی گردید' ,
            'user' => $user
        ],200);
    }
}
