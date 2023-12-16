<?php

namespace App\Http\Controllers\Server;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeployController extends Controller
{
    public function pullBackend(Request $request)
    {
        Log::info(print_r($request->all(),true));
        return response([
            'status' => true
        ],200);
    }

    public function pullApp(Request $request)
    {

    }
}
