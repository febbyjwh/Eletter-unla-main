<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ActiveUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {

            $user = Auth::user();

            // akun belum aktif
            if ($user->status != 1) {
                return redirect()->route('waiting.approval');
            }

            // role belum ditentukan
            if (is_null($user->role_id)) {
                return redirect()->route('waiting.approval');
            }   
        }

        return $next($request);
    }
}
