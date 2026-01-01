<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DevAuthMiddleware
{
    /**
     * Middleware untuk otomatis login sebagai pengurus selama tahap development.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Hanya jalankan auto-login jika user belum login
        if (!Auth::check()) {
            $user = User::where('role', 'pengurus')->first();

            if ($user) {
                Auth::login($user);
            }
        }

        return $next($request);
    }
}