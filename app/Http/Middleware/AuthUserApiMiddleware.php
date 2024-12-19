<?php

namespace App\Http\Middleware;

use App\CPU\ResponseUtil;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AuthUserApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        if(Auth()->user())
        {
            return $next($request);
        }else{
            return response()->json(ResponseUtil::makeError("Affiliate not found or Account has been suspended.", [], 401));
        }
       
       /* if (Auth::check() && auth()->user()->is_active) {
            Config::set('activitylog.default_auth_driver', 'affiliate-api');
            return $next($request);
        } else {
            return response()->json(ResponseUtil::makeError("Affiliate not found or Account has been suspended.", [], 401));
        }
            */
    }
}
