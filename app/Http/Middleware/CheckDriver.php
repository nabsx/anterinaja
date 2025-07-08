<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Periksa apakah user memiliki role user
        if (Auth::user()->role !== 'user') {
            // Jika request API, kembalikan response JSON
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Hanya regular user yang diizinkan.'
                ], 403);
            }

            return redirect()->route('home')->with('error', 'Akses ditolak. Hanya regular user yang diizinkan.');
        }

        // Periksa apakah user sudah verifikasi email (opsional)
        if (config('auth.must_verify_email') && !Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice')
                ->with('warning', 'Anda perlu memverifikasi email terlebih dahulu.');
        }

        return $next($request);
    }
}