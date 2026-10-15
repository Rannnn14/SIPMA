<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Kemahasiswaan has super admin privileges across views
        if (in_array($user->role, ['kemahasiswaan', 'admin'])) {
            return $next($request);
        }

        // Check if user has one of the allowed roles
        if (!in_array($user->role, $roles)) {
            // Redirect to their own dashboard
            return redirect()->route($user->role . '.dashboard')->with('error', 'Anda tidak memiliki hak akses ke halaman tersebut.');
        }

        return $next($request);
    }
}
