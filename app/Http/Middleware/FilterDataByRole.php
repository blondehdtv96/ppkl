<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FilterDataByRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Jika user tidak aktif, logout
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif.');
        }
        
        // Set global variable untuk filtering
        app()->instance('current_user', $user);
        
        return $next($request);
    }
}

class CheckRoleAccess
{
    /**
     * Handle an incoming request untuk memastikan user hanya mengakses data yang sesuai
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Cek apakah user memiliki role yang diizinkan
        if (!in_array($user->role, $roles) && !$user->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        
        // Untuk wali kelas, pastikan mereka hanya mengakses data siswa yang sesuai
        if ($user->isWaliKelas() && $request->route('siswa')) {
            $siswa = $request->route('siswa');
            if (!$user->canViewSiswa($siswa)) {
                abort(403, 'Anda tidak memiliki akses untuk melihat data siswa ini.');
            }
        }
        
        // Untuk kaprog, pastikan mereka hanya mengakses data siswa yang sesuai
        if ($user->isKaprog() && $request->route('siswa')) {
            $siswa = $request->route('siswa');
            if (!$user->canViewSiswa($siswa)) {
                abort(403, 'Anda tidak memiliki akses untuk melihat data siswa ini.');
            }
        }
        
        return $next($request);
    }
}

class ValidateDataAccess
{
    /**
     * Middleware untuk memvalidasi akses data berdasarkan role
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Jika ada parameter permohonan di route
        if ($request->route('permohonan')) {
            $permohonan = $request->route('permohonan');
            
            // Untuk wali kelas, cek apakah permohonan dari siswa yang diampu
            if ($user->isWaliKelas()) {
                if (!$user->canViewSiswa($permohonan->user)) {
                    abort(403, 'Anda tidak memiliki akses untuk melihat permohonan ini.');
                }
            }
            
            // Untuk kaprog, cek apakah permohonan dari siswa yang diampu
            if ($user->isKaprog()) {
                if (!$user->canViewSiswa($permohonan->user)) {
                    abort(403, 'Anda tidak memiliki akses untuk melihat permohonan ini.');
                }
            }
        }
        
        return $next($request);
    }
}