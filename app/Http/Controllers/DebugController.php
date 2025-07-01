<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DebugController extends Controller
{
    public function debugSiswaAccess()
    {
        $user = Auth::user();
        
        if (!$user || !$user->isWaliKelas()) {
            return response()->json(['error' => 'Hanya wali kelas yang dapat mengakses debug ini']);
        }
        
        // Debug informasi user
        $debugInfo = [
            'user_info' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'custom_kelas_diampu' => $user->custom_kelas_diampu,
            ],
        ];
        
        // Ambil semua siswa tanpa filter
        $allSiswa = User::where('role', 'siswa')->get(['id', 'name', 'kelas', 'jurusan']);
        
        // Ambil siswa dengan filter yang seharusnya
        $query = User::where('role', 'siswa');
        
        if ($user->custom_kelas_diampu) {
            $kelasArray = array_map('trim', explode(',', $user->custom_kelas_diampu));
            $query->whereIn('kelas', $kelasArray);
        } else {
            $query->whereRaw('1 = 0');
        }
        
        $filteredSiswa = $query->get(['id', 'name', 'kelas', 'jurusan']);
        
        // Test canViewSiswa untuk setiap siswa
        $canViewResults = [];
        foreach ($allSiswa as $siswa) {
            $canViewResults[] = [
                'siswa_id' => $siswa->id,
                'siswa_name' => $siswa->name,
                'siswa_kelas' => $siswa->kelas,
                'can_view' => $user->canViewSiswa($siswa),
            ];
        }
        
        return response()->json([
            'debug_info' => $debugInfo,
            'all_siswa_count' => $allSiswa->count(),
            'filtered_siswa_count' => $filteredSiswa->count(),
            'all_siswa' => $allSiswa,
            'filtered_siswa' => $filteredSiswa,
            'can_view_results' => $canViewResults,
        ], 200, [], JSON_PRETTY_PRINT);
    }
    
    public function debugSiswaAccessView()
    {
        $user = Auth::user();
        
        if (!$user || !$user->isWaliKelas()) {
            abort(403, 'Hanya wali kelas yang dapat mengakses halaman debug ini');
        }
        
        return view('debug.siswa-access');
    }
}