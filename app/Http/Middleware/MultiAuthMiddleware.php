<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class MultiAuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('web')->check()) {
            return $next($request);
        }

        if (Auth::guard('unit')->check()) {
            return $next($request);
        }

        return redirect()->route('login'); // atau login.unit
    }
}