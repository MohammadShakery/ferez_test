<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CRM
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Cache::put("ip.".$request->getClientIp(),true,20);
        Cache::has('url.'.$request->getRequestUri()) ? Cache::increment('url.'.$request->getRequestUri()) : Cache::put('url.'.$request->getRequestUri(),1);
        if($request->hasHeader('user_id'))
        {
            Cache::add("user.".$request->header('user_id'),true,30);
        }

        return $next($request);
    }
}
