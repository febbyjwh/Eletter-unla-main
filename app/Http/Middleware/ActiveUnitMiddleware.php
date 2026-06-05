<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ActiveUnitMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('unit')->check()) {

            $unit = Auth::guard('unit')->user();

            if ($unit->status != 1) {
                return redirect()->route('waiting.approval');
            }
        } else {

            return redirect()->route('login-unit');
        }

        return $next($request);
    }
}
