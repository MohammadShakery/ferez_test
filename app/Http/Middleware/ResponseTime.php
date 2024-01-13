<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\TerminableInterface;

class ResponseTime implements TerminableInterface
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }


    public function terminate(\Symfony\Component\HttpFoundation\Request $request, Response $response)
    {
        $response_time = microtime(true) - LARAVEL_START;
        Log::info($response_time);
        if(Cache::has('time'.$request->getRequestUri()))
        {
            $array = json_decode(Cache::get('time'.$request->getRequestUri()));
            $array[] = round($response_time,4);
            Cache::set('time'.$request->getRequestUri(),json_encode($array));
        }
        else
        {
            $array[] = round($response_time,4);
            Cache::set('time'.$request->getRequestUri(),json_encode($array));
        }
    }
}
