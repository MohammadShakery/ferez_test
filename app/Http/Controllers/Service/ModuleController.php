<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\AssignOp\Mod;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        return response([
            'status' => true ,
            'modules' => ($this->getUser($request))->modules
        ],200);
    }

    public function allModules()
    {
        return response([
            'status' => true ,
            'modules' => Module::all()
        ],200);
    }

    public function getUser(Request $request)
    {
        $inf_array = decrypt($request->header('token'));
        $user = User::query()->where('phone',$inf_array['BMSN'])->firstOrFail();
        return $user;
    }
}
