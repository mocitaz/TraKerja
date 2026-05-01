<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SoftMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah Maintenance Mode aktif di tabel settings
        $isMaintenance = (bool) Setting::get('maintenance_mode', false);

        if ($isMaintenance) {
            // Pengecekan Admin Bypass yang lebih kuat
            if (Auth::check()) {
                $user = Auth::user();
                // Bypass jika role-nya admin atau is_admin true
                if ($user->role === 'admin' || $user->is_admin) {
                    return $next($request);
                }
            }

            // Selalu izinkan akses ke rute login dan logout agar admin bisa masuk
            if ($request->is('login', 'logout', 'admin/login')) {
                return $next($request);
            }

            // Tampilkan halaman maintenance untuk user biasa
            return response()->view('errors.503', [], 503);
        }

        return $next($request);
    }
}
