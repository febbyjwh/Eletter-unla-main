<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
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

            // pending approval
            if ($user->status !== 1) {
                Auth::logout();

                return redirect('/waiting-approval')
                    ->with('message', 'Akun Anda sedang menunggu verifikasi SPAdmin.');
            }

            // jika user belum memiliki role, anggap sebagai guest
            if (!$user->role_id) {
                Auth::logout();

                return redirect('/waiting-approval')
                    ->with('message', 'Role belum ditentukan oleh admin.');
            }
        }

        return $next($request);
    }
}
