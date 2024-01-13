<?php

namespace App\Http\Controllers\Industrial;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;

class RequirementController extends Controller
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
}
