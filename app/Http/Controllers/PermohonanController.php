<?php

namespace App\Http\Controllers;

use App\Models\PermohonanPkl;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PermohonanController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of permohonan PKL berdasarkan role user
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Menggunakan scope untuk filtering otomatis berdasarkan role
        $permohonanQuery = PermohonanPkl::with(['user'])->forUser($user);
        
        // Filter tambahan berdasarkan status jika diperlukan
        if ($request->has('status') && $request->status !== '') {
            $permohonanQuery->where('status', $request->status);
        }
        
        // Filter berdasarkan tahun jika diperlukan
        if ($request->has('tahun') && $request->tahun !== '') {
            $permohonanQuery->whereYear('created_at', $request->tahun);
        }
        
        $permohonan = $permohonanQuery->orderBy('created_at', 'desc')->paginate(10);
        
        return view('permohonan.index', compact('permohonan'));
    }
    
    /**
     * Display the specified permohonan PKL
     */
    public function show(PermohonanPkl $permohonan)
    {
        // Menggunakan policy untuk authorization
        $this->authorize('view', $permohonan);
        
        $permohonan->load(['user', 'historiPermohonan.user']);
        
        return view('permohonan.show', compact('permohonan'));
    }
    
    /**
     * Process permohonan PKL (approve/reject)
     */
    public function process(Request $request, PermohonanPkl $permohonan)
    {
        // Menggunakan policy untuk authorization
        $this->authorize('process', $permohonan);
        
        $request->validate([
            'action' => 'required|in:approve,reject',
            'catatan' => 'nullable|string|max:1000'
        ]);
        
        $user = Auth::user();
        $action = $request->action;
        $catatan = $request->catatan;
        
        // Update status permohonan
        $newStatus = $permohonan->getNextStatus($action, $user->role);
        
        if (!$newStatus) {
            return back()->with('error', 'Status tidak valid untuk role Anda.');
        }
        
        $permohonan->update([
            'status' => $newStatus,
            'catatan_penolakan' => $action === 'reject' ? $catatan : null,
            'current_role' => $action === 'approve' ? $this->getNextRole($newStatus) : null
        ]);
        
        // Simpan ke histori
        $permohonan->historiPermohonan()->create([
            'user_id' => $user->id,
            'role_processor' => $user->role,
            'status_before' => $permohonan->getOriginal('status'),
            'status_after' => $newStatus,
            'action' => $action,
            'catatan' => $catatan,
            'processed_at' => now()
        ]);
        
        // Buat notifikasi untuk siswa
        $permohonan->notifikasi()->create([
            'user_id' => $permohonan->user_id,
            'title' => 'Permohonan PKL ' . ($action === 'approve' ? 'Disetujui' : 'Ditolak'),
            'message' => $action === 'approve' 
                ? "Permohonan PKL Anda telah disetujui oleh {$user->name} ({$user->role})"
                : "Permohonan PKL Anda ditolak oleh {$user->name} ({$user->role}). Alasan: {$catatan}",
            'type' => $action === 'approve' ? 'success' : 'danger',
            'is_read' => false
        ]);
        
        $message = $action === 'approve' 
            ? 'Permohonan berhasil disetujui.'
            : 'Permohonan berhasil ditolak.';
            
        return back()->with('success', $message);
    }
    
    /**
     * Get daftar siswa yang bisa dilihat berdasarkan role
     */
    public function getSiswaList()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            $siswa = User::where('role', 'siswa')->where('is_active', true)->get();
        } elseif ($user->isWaliKelas()) {
            $siswa = $user->getSiswaByKelas();
        } elseif ($user->isKaprog()) {
            $siswa = $user->getSiswaByJurusan();
        } else {
            // Role lain bisa melihat semua siswa
            $siswa = User::where('role', 'siswa')->where('is_active', true)->get();
        }
        
        return response()->json($siswa);
    }
    
    /**
     * Get next role for processing
     */
    private function getNextRole($status)
    {
        $nextRoleMap = [
            'disetujui_wali' => 'bp',
            'disetujui_bp' => 'kaprog',
            'disetujui_kaprog' => 'tu',
            'disetujui_tu' => 'hubin'
        ];
        
        return $nextRoleMap[$status] ?? null;
    }
}