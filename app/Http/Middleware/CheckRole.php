<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Periksa apakah user memiliki salah satu role yang diizinkan
        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Role tidak sesuai.'
            ], 403);
        }

        return redirect()->route('home')->with('error', 'Akses ditolak.');
    }
}