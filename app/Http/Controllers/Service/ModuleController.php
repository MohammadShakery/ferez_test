<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        return response([
            'status' => true ,
            'modules' => ($this->getUser($request))->modules
        ],200);
    }

    public function getUser(Request $request)
    {
        $inf_array = decrypt($request->header('token'));
        $user = User::query()->where('phone',$inf_array['BMSN'])->firstOrFail();
        return $user;
    }
}
