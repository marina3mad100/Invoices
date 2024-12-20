<?php

namespace App\Http\Middleware;

use Closure;
use Request;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        //dd($request);
        if (Request::is('api/*')) {
            $request->headers->set('Accept', 'application/json');
        }
        return $next($request);
    }
}
