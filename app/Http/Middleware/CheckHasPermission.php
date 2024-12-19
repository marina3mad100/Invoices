<?php

namespace App\Http\Middleware;

use App\CPU\ResponseUtil;
use App\Models\Admin;
use App\Models\Project;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckHasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next , $permission): Response
    {
        
        if(Auth()->user())
        {
            $user = User::where('id',Auth()->user()->id)->with('userable:id,status')->first();;
            //dd($user);
            if($user->userable->status->value == 1 &&  $permission != 'admin only' && Auth()->user()->hasPermissionTo($permission)){
                return $next($request);
            }
            else if($user->userable->status->value == 1 &&  $permission == 'admin only' && Auth()->user()->userable_type == Admin::class){
                return $next($request);
            }
            else{
                if ($request->is('api/*')) {
                    return response()->json(ResponseUtil::makeError("this action is not allowed.", [], 401));
                }
                return redirect()->route('login');
            }
        }else{
            if ($request->is('api/*')) {
                return response()->json(ResponseUtil::makeError("this action is not allowed.", [], 401));
            }
            return redirect()->route('login');
        }
        
    }
}
