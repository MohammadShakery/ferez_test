<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BrandModule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::query()->where('phone',decrypt($request->header('token'))['BMSN'])->first();
        if($user->hasAccess(1))
        {
            return $next($request);
        }
        return \response([
            'status' => false ,
            'message' => 'شما دسترسی به این قسمت ندارید'
        ],403);
    }
}
