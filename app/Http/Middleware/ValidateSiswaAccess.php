<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateSiswaAccess
{
    /**
     * Handle an incoming request untuk memvalidasi akses data siswa
     * Middleware ini memastikan wali kelas hanya dapat mengakses data siswa
     * dari kelas yang mereka ampu
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // Jika bukan wali kelas, lewati validasi ini
        if (!$user->isWaliKelas()) {
            return $next($request);
        }
        
        // Jika ada parameter siswa/user di route
        $siswa = $request->route('user') ?? $request->route('siswa');
        
        // Jika ada parameter permohonan di route
        $permohonan = $request->route('permohonan');
        if ($permohonan) {
            $siswa = $permohonan->user;
        }
        
        if ($siswa && $siswa->role === 'siswa') {
            // Validasi apakah wali kelas dapat melihat siswa ini
            if (!$user->canViewSiswa($siswa)) {
                abort(403, 'Anda tidak memiliki akses untuk melihat data siswa ini. Siswa tidak berada di kelas yang Anda ampu.');
            }
        }
        
        return $next($request);
    }
}