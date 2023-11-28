<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response([
            'status' => true ,
            'users' => User::all()
        ],200);
    }

    public function show(User $user)
    {
        return response([
            'status' => true ,
            'user' => $user
        ],200);
    }

    public function update(User $user,UserUpdateRequest $request)
    {
        $user->update([
            'name' => $request->get('name') ,
            'email' => $request->get('email')
        ]);
    }
}
