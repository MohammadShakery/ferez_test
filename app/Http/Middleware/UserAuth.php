<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->header('token'))
        {
            return \response([
                'status' => false ,
                'message' => 'لطفا ابتدا وارد حساب کاربری خود شوید'
            ],403);
        }
        try {
            $inf_array = decrypt($request->header('token'));
            $user = User::query()->where('phone',$inf_array['BMSN'])->firstOrFail();
            return $next($request);

        }catch (\Exception $exception)
        {
            return \response([
                'status' => false ,
                'message' => 'لطفا ابتدا وارد حساب کاربری خود شوید'
            ],403);
        }


    }
}
