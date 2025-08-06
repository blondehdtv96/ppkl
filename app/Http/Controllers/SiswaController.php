<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Hanya wali kelas dan kaprog yang bisa mengakses halaman ini
        if (!$user->isWaliKelas() && !$user->isKaprog()) {
            abort(403, 'Unauthorized action.');
        }
        
        $query = User::where('role', 'siswa');
        
        if ($user->isWaliKelas()) {
            // Filter siswa berdasarkan kelas yang diampu oleh wali kelas
            if ($user->custom_kelas_diampu) {
                $kelasArray = array_map('trim', explode(',', $user->custom_kelas_diampu));
                $query->whereIn('kelas', $kelasArray);
            } else {
                $query->whereRaw('1 = 0');
            }
        }
        if ($user->isKaprog()) {
            // Filter siswa berdasarkan jurusan yang diampu oleh kaprog
            if ($user->jurusan_diampu && is_array($user->jurusan_diampu)) {
                if ($request->filled('jurusan')) {
                    // Jika ada filter jurusan, pastikan hanya jurusan yang diampu
                    if (in_array($request->jurusan, $user->jurusan_diampu)) {
                        $query->where('jurusan', $request->jurusan);
                    } else {
                        $query->whereRaw('1 = 0');
                    }
                } else {
                    $query->whereIn('jurusan', $user->jurusan_diampu);
                }
                // Filter kelas hanya untuk siswa dengan jurusan yang diampu
                if ($request->filled('kelas')) {
                    $query->where('kelas', 'like', "%{$request->kelas}%");
                }
            } else {
                $query->whereRaw('1 = 0');
            }
        }
        
        // Filter pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('kelas', 'like', "%{$search}%")
                  ->orWhere('jurusan', 'like', "%{$search}%");
            });
        }
        
        // Filter berdasarkan kelas
        if ($request->filled('kelas')) {
            $query->where('kelas', 'like', "%{$request->kelas}%");
        }
        
        // Filter berdasarkan jurusan
        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }
        
        // Filter berdasarkan status aktif
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        $siswa = $query->orderBy('kelas', 'asc')
                      ->orderBy('name', 'asc')
                      ->paginate($request->get('per_page', 15));
        
        // Menghitung statistik siswa
        $stats = [
            'total' => $query->count(),
            'active' => (clone $query)->where('is_active', true)->count(),
            'inactive' => (clone $query)->where('is_active', false)->count(),
        ];
        
        return view('siswa.index', compact('siswa', 'stats'));
    }
    
    public function show(User $siswa)
    {
        $user = Auth::user();
        
        // Hanya wali kelas dan kaprog yang bisa mengakses halaman ini
        if (!$user->isWaliKelas() && !$user->isKaprog()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Pastikan siswa yang dilihat adalah siswa yang berada di kelas/jurusan yang diampu
        if ($user->isKaprog()) {
            // Kaprog hanya bisa melihat siswa dengan jurusan yang diampu
            if (!in_array($siswa->jurusan, $user->jurusan_diampu ?? [])) {
                abort(403, 'Anda tidak memiliki akses untuk melihat siswa ini.');
            }
        }
        if (!$user->canViewSiswa($siswa)) {
            abort(403, 'Anda tidak memiliki akses untuk melihat siswa ini.');
        }
        
        $siswa->load(['permohonanPkl']);
        return view('siswa.show', compact('siswa'));
    }
}