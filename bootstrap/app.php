<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TokenMismatchException $exception, Request $request) {
            $message = 'Session expired. Please choose your style preference again and submit one more time.';

            if ($request->is('smartfit/get-recommendation') || $request->is('smartfit/result/style-preference')) {
                return redirect()->route('smartfit.result')->with('csrf_expired', $message);
            }

            if ($request->is('known/get-recommendation') || $request->is('known/process')) {
                return redirect()->route('known.select')->with('csrf_expired', $message);
            }

            return redirect()->back()->with('csrf_expired', $message);
        });
    })->create();
