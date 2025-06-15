<?php

namespace App\Http\Controllers;

use App\Models\PermohonanPkl;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        switch ($user->role) {
            case 'admin':
                $data = $this->getAdminDashboardData();
                break;
            case 'siswa':
                $data = $this->getSiswaDashboardData();
                break;
            case 'wali_kelas':
            case 'bp':
            case 'kaprog':
            case 'tu':
            case 'hubin':
                $data = $this->getStaffDashboardData($user->role);
                break;
        }

        return view('dashboard.index', compact('data'));
    }

    private function getAdminDashboardData()
    {
        return [
            'total_permohonan' => PermohonanPkl::count(),
            'permohonan_pending' => PermohonanPkl::whereNotIn('status', ['dicetak_hubin'])->count(),
            'permohonan_selesai' => PermohonanPkl::where('status', 'dicetak_hubin')->count(),
            'permohonan_ditolak' => PermohonanPkl::where('status', 'like', 'ditolak_%')->count(),
            'total_siswa' => User::where('role', 'siswa')->count(),
            'recent_permohonan' => PermohonanPkl::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get(),
            'status_distribution' => PermohonanPkl::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status'),
        ];
    }

    private function getSiswaDashboardData()
    {
        $user = Auth::user();
        
        return [
            'total_permohonan' => $user->permohonanPkl()->count(),
            'permohonan_draft' => $user->permohonanPkl()->where('status', 'draft')->count(),
            'permohonan_proses' => $user->permohonanPkl()
                ->whereNotIn('status', ['draft', 'dicetak_hubin'])
                ->where('status', 'not like', 'ditolak_%')
                ->count(),
            'permohonan_selesai' => $user->permohonanPkl()->where('status', 'dicetak_hubin')->count(),
            'permohonan_ditolak' => $user->permohonanPkl()->where('status', 'like', 'ditolak_%')->count(),
            'recent_permohonan' => $user->permohonanPkl()
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
            'unread_notifications' => $user->getUnreadNotificationsCount(),
        ];
    }

    private function getStaffDashboardData($role)
    {
        $statusMap = [
            'wali_kelas' => 'diajukan',
            'bp' => 'disetujui_wali',
            'kaprog' => 'disetujui_bp',
            'tu' => 'disetujui_kaprog',
            'hubin' => 'disetujui_tu',
        ];

        $targetStatus = $statusMap[$role] ?? null;
        $user = Auth::user();

        return [
            'pending_permohonan' => $targetStatus ? PermohonanPkl::where('status', $targetStatus)->count() : 0,
            'processed_today' => PermohonanPkl::whereHas('historiPermohonan', function($query) use ($role) {
                $query->where('role_processor', $role)
                    ->whereDate('created_at', today());
            })->count(),
            'total_processed' => PermohonanPkl::whereHas('historiPermohonan', function($query) use ($role) {
                $query->where('role_processor', $role);
            })->count(),
            'pending_list' => $targetStatus ? PermohonanPkl::with('user')
                ->where('status', $targetStatus)
                ->orderBy('created_at', 'asc')
                ->limit(10)
                ->get() : collect(),
            'unread_notifications' => $user->getUnreadNotificationsCount(),
            'role_label' => $this->getRoleLabel($role),
        ];
    }

    private function getRoleLabel($role)
    {
        $labels = [
            'wali_kelas' => 'Wali Kelas',
            'bp' => 'BP (Bimbingan dan Penyuluhan)',
            'kaprog' => 'Kaprog (Kepala Program)',
            'tu' => 'TU (Tata Usaha)',
            'hubin' => 'Hubin (Hubungan Industri)',
        ];

        return $labels[$role] ?? $role;
    }
}