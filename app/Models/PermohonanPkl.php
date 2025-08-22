<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PermohonanPkl extends Model
{
    use HasFactory;

    protected $table = 'permohonan_pkl';

    protected $fillable = [
        'user_id',
        'nama_perusahaan',
        'alamat_perusahaan',
        'kontak_perusahaan',
        'email_perusahaan',
        'bidang_usaha',
        'nama_pembimbing',
        'tanggal_mulai',
        'tanggal_selesai',
        'alasan',
        'dokumen_pendukung',
        'status',
        'catatan_penolakan',
        'current_role',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function historiPermohonan()
    {
        return $this->hasMany(HistoriPermohonan::class, 'permohonan_id');
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class, 'permohonan_id');
    }

    // Scopes
    public function scopeForWaliKelas($query, User $waliKelas)
    {
        if (!$waliKelas->isWaliKelas()) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }

        $siswaIds = $waliKelas->getSiswaByKelas()->pluck('id')->toArray();
        
        if (empty($siswaIds)) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }

        return $query->whereIn('user_id', $siswaIds);
    }

    public function scopeForKaprog($query, User $kaprog)
    {
        if (!$kaprog->isKaprog()) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }

        $siswaIds = $kaprog->getSiswaByJurusan()->pluck('id')->toArray();
        
        if (empty($siswaIds)) {
            return $query->whereRaw('1 = 0'); // Return empty result
        }

        return $query->whereIn('user_id', $siswaIds);
    }

    public function scopeForUser($query, User $user)
    {
        if ($user->isAdmin()) {
            return $query; // Admin bisa melihat semua
        }

        if ($user->isSiswa()) {
            return $query->where('user_id', $user->id); // Siswa hanya melihat miliknya
        }

        if ($user->isWaliKelas()) {
            return $query->forWaliKelas($user);
        }

        if ($user->isKaprog()) {
            return $query->forKaprog($user);
        }

        // Role lain (BP, TU, Hubin) bisa melihat semua
        return $query;
    }

    // Helper methods
    public function getStatusLabelAttribute()
    {
        $statusLabels = [
            'draft' => 'Draft',
            'diajukan' => 'Diajukan',
            'ditolak_wali' => 'Ditolak Wali Kelas',
            'disetujui_wali' => 'Disetujui Wali Kelas',
            'ditolak_bp' => 'Ditolak BP',
            'disetujui_bp' => 'Disetujui BP',
            'ditolak_kaprog' => 'Ditolak Kaprog',
            'disetujui_kaprog' => 'Disetujui Kaprog',
            'ditolak_tu' => 'Ditolak TU',
            'disetujui_tu' => 'Disetujui TU',
            'dicetak_hubin' => 'Disetujui Hubin',
            'diperbaiki' => 'Diperbaiki',
        ];

        return $statusLabels[$this->status] ?? $this->status;
    }

    public function getStatusLabel()
    {
        return $this->getStatusLabelAttribute();
    }
    public function getStatusColor()
    {
        return $this->getStatusColorAttribute();
    }
    public function getStatusColorAttribute()
    {
        $statusColors = [
            'draft' => 'secondary',
            'diajukan' => 'info',
            'ditolak_wali' => 'danger',
            'disetujui_wali' => 'success',
            'ditolak_bp' => 'danger',
            'disetujui_bp' => 'success',
            'ditolak_kaprog' => 'danger',
            'disetujui_kaprog' => 'success',
            'ditolak_tu' => 'danger',
            'disetujui_tu' => 'success',
            'dicetak_hubin' => 'primary',
            'diperbaiki' => 'warning',
        ];

        return $statusColors[$this->status] ?? 'secondary';
    }

    public function canBeProcessedBy($role)
    {
        // Pastikan $role adalah string
        if (is_object($role) && method_exists($role, 'getAttribute')) {
            $role = $role->getAttribute('role');
        } elseif (is_object($role) && property_exists($role, 'role')) {
            $role = $role->role;
        }
        
        // Konversi ke string jika bukan string
        if (!is_string($role)) {
            $role = (string) $role;
        }
        
        $roleStatusMap = [
            'wali_kelas' => ['diajukan'],
            'bp' => ['disetujui_wali'],
            'kaprog' => ['disetujui_bp'],
            'tu' => ['disetujui_kaprog'],
            'hubin' => ['disetujui_tu'],
        ];

        return isset($roleStatusMap[$role]) && in_array($this->status, $roleStatusMap[$role]);
    }

    public function canProcess($user)
    {
        // Jika $user adalah string, anggap itu sebagai role
        if (is_string($user)) {
            return $this->canBeProcessedBy($user);
        }
        
        // Jika $user adalah objek User, gunakan atribut role
        if (is_object($user)) {
            if (method_exists($user, 'getAttribute')) {
                return $this->canBeProcessedBy($user->getAttribute('role'));
            } elseif (property_exists($user, 'role')) {
                return $this->canBeProcessedBy($user->role);
            }
        }
        
        // Jika tidak bisa mendapatkan role, kembalikan false
        return false;
    }

    public function getNextStatus($action, $role)
    {
        if ($action === 'approve') {
            $approveMap = [
                'wali_kelas' => 'disetujui_wali',
                'bp' => 'disetujui_bp',
                'kaprog' => 'disetujui_kaprog',
                'tu' => 'disetujui_tu',
                'hubin' => 'dicetak_hubin',
            ];
            return $approveMap[$role] ?? null;
        }

        if ($action === 'reject') {
            $rejectMap = [
                'wali_kelas' => 'ditolak_wali',
                'bp' => 'ditolak_bp',
                'kaprog' => 'ditolak_kaprog',
                'tu' => 'ditolak_tu',
            ];
            return $rejectMap[$role] ?? null;
        }

        return null;
    }

    public function isRejected()
    {
        return in_array($this->status, ['ditolak_wali', 'ditolak_bp', 'ditolak_kaprog', 'ditolak_tu']);
    }

    public function isCompleted()
    {
        return $this->status === 'dicetak_hubin';
    }

    public function canEdit()
    {
        return $this->canBeEdited();
    }
    public function canBeEdited()
    {
        return in_array($this->status, ['ditolak_wali', 'ditolak_bp', 'ditolak_kaprog', 'ditolak_tu']);
    }

    public function canBeRepaired()
    {
        return in_array($this->status, ['ditolak_wali', 'ditolak_bp', 'ditolak_kaprog', 'ditolak_tu']);
    }

    public function getRepairTargetRole()
    {
        // Menentukan role yang akan memproses setelah perbaikan berdasarkan status penolakan terakhir
        $roleMap = [
            'ditolak_wali' => 'wali_kelas',
            'ditolak_bp' => 'bp',
            'ditolak_kaprog' => 'kaprog',
            'ditolak_tu' => 'tu',
        ];

        return $roleMap[$this->status] ?? null;
    }

    public function getRepairTargetStatus()
    {
        // Menentukan status yang akan diset setelah perbaikan berdasarkan status penolakan terakhir
        $statusMap = [
            'ditolak_wali' => 'diajukan',
            'ditolak_bp' => 'disetujui_wali',
            'ditolak_kaprog' => 'disetujui_bp',
            'ditolak_tu' => 'disetujui_kaprog',
        ];

        return $statusMap[$this->status] ?? null;
    }

    public function getSiswaAttribute()
    {
        return $this->user;
    }

    public function getHistoriAttribute()
    {
        return $this->historiPermohonan;
    }

    public function getProcessorAttribute()
    {
        if (!$this->current_role) {
            return null;
        }
        
        // Cari user dengan role yang sesuai dengan current_role
        return User::where('role', $this->current_role)->first();
    }
}