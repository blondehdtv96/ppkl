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
        
        // Hanya wali kelas yang bisa mengakses halaman ini
        if (!$user->isWaliKelas()) {
            abort(403, 'Unauthorized action.');
        }
        
        $query = User::where('role', 'siswa');
        
        // Filter siswa berdasarkan kelas yang diampu oleh wali kelas
        // Wali kelas hanya bisa melihat siswa dari kelas yang secara eksplisit diberikan akses
        if ($user->custom_kelas_diampu) {
            // Parse kelas yang diampu (bisa multiple, dipisah koma)
            $kelasArray = array_map('trim', explode(',', $user->custom_kelas_diampu));
            $query->whereIn('kelas', $kelasArray);
        } else {
            // Jika tidak ada kelas yang diampu, tidak tampilkan siswa apapun
            $query->whereRaw('1 = 0'); // Kondisi yang selalu false
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
        
        // Hanya wali kelas yang bisa mengakses halaman ini
        if (!$user->isWaliKelas()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Pastikan siswa yang dilihat adalah siswa yang berada di kelas yang diampu
        // Wali kelas hanya bisa melihat siswa dari kelas yang secara eksplisit diberikan akses
        if (!$user->canViewSiswa($siswa)) {
            abort(403, 'Anda tidak memiliki akses untuk melihat siswa ini.');
        }
        
        $siswa->load(['permohonanPkl']);
        return view('siswa.show', compact('siswa'));
    }
}