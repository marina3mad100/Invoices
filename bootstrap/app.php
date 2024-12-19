<?php

use App\Http\Middleware\Cors;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;





return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
		then: function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->group(base_path('routes/api.php'));

        }
    )
	
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
           // \Illuminate\Http\Middleware\HandleCors::class,
        ]);
        $middleware->alias([

            'CheckHasPermission' => \App\Http\Middleware\CheckHasPermission::class,
            'cors' => \App\Http\Middleware\Cors::class,

        ]);
        $middleware->append(Cors::class);
        $middleware->append(HandleCors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        header("Access-Control-Allow-Origin: *");
        
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }
 
            return $request->expectsJson();
        });
        //
    })->create();
