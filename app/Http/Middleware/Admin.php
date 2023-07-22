<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!\App\Models\Admin::query()->where('token',$request->get('token'))->exists())
        {
            return \response([
                'status' => false ,
                'message' => 'لطفا ابتدا وارد شوید'
            ],200);
        }
        return $next($request);
    }
}
