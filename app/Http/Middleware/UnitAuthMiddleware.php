<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UnitAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::guard('unit')->check()) {
            return redirect()->route('login-unit');
        }

        return $next($request);
    }
}