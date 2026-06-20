<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu!');
        }
        
        $user = auth()->user();
        
        // 2. Check if user account is active
        if (!$user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')
                ->with('error', 'Akun Anda telah dinonaktifkan. Hubungi administrator.');
        }
        
        // 3. Check if user is admin
        if (!$user->isAdmin()) {
            // Redirect ke dashboard user biasa dengan pesan error
            return redirect()->route('dashboard')
                ->with('error', 'Akses ditolak. Halaman ini hanya untuk administrator.');
        }
        
        // 4. Update last login time (every 5 minutes to avoid too many queries)
        if (!session()->has('last_login_update') || 
            now()->diffInMinutes(session('last_login_update')) >= 5) {
            $user->update(['last_login_at' => now()]);
            session(['last_login_update' => now()]);
        }
        
        return $next($request);
    }
}