<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kelas',
        'jurusan',
        'nis',
        'is_active',
        'jurusan_diampu',
        'custom_kelas_diampu',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'jurusan_diampu' => 'array',
    ];

    // Relasi
    public function permohonanPkl()
    {
        return $this->hasMany(PermohonanPkl::class);
    }

    public function notifikasi()
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function historiPermohonan()
    {
        return $this->hasMany(HistoriPermohonan::class);
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }

    public function isWaliKelas()
    {
        return $this->role === 'wali_kelas';
    }

    public function isBp()
    {
        return $this->role === 'bp';
    }

    public function isKaprog()
    {
        return $this->role === 'kaprog';
    }

    public function isTu()
    {
        return $this->role === 'tu';
    }

    public function isHubin()
    {
        return $this->role === 'hubin';
    }

    /**
     * Get siswa yang bisa dilihat oleh wali kelas berdasarkan kelas yang diampu
     * Wali kelas hanya bisa melihat siswa dari kelas yang secara eksplisit diberikan akses
     */
    public function getSiswaByKelas()
    {
        if (!$this->isWaliKelas()) {
            return collect();
        }

        // Pastikan ada kelas yang diampu
        if (!$this->custom_kelas_diampu) {
            return collect();
        }

        // Parse kelas yang diampu (bisa multiple, dipisah koma)
        $kelasArray = array_map('trim', explode(',', $this->custom_kelas_diampu));

        // Filter siswa berdasarkan kelas yang diampu
        return User::where('role', 'siswa')
            ->where('is_active', true)
            ->whereIn('kelas', $kelasArray)
            ->get();
    }

    /**
     * Get siswa yang bisa dilihat oleh kaprog berdasarkan jurusan yang diampu
     */
    public function getSiswaByJurusan()
    {
        if (!$this->isKaprog()) {
            return collect();
        }

        if (!$this->jurusan_diampu || !is_array($this->jurusan_diampu)) {
            return collect();
        }

        return User::where('role', 'siswa')
            ->where('is_active', true)
            ->whereIn('jurusan', $this->jurusan_diampu)
            ->get();
    }

    /**
     * Check if user can view specific siswa based on their role and assigned classes/majors
     */
    public function canViewSiswa(User $siswa)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isWaliKelas()) {
            return $this->canViewSiswaByKelas($siswa);
        }

        if ($this->isKaprog()) {
            return $this->canViewSiswaByJurusan($siswa);
        }

        // Role lain (BP, TU, Hubin) bisa melihat semua siswa
        return in_array($this->role, ['bp', 'tu', 'hubin']);
    }

    /**
     * Check if wali kelas can view specific siswa based on kelas
     * Wali kelas hanya bisa melihat siswa dari kelas yang secara eksplisit diberikan akses
     */
    private function canViewSiswaByKelas(User $siswa)
    {
        if (!$siswa->kelas) {
            return false;
        }

        // Pastikan ada kelas yang diampu
        if (!$this->custom_kelas_diampu) {
            return false;
        }

        // Parse kelas yang diampu (bisa multiple, dipisah koma)
        $kelasArray = array_map('trim', explode(',', $this->custom_kelas_diampu));
        
        // Cek apakah siswa ada di kelas yang diampu
        return in_array($siswa->kelas, $kelasArray);
    }

    /**
     * Check if kaprog can view specific siswa based on jurusan
     */
    private function canViewSiswaByJurusan(User $siswa)
    {
        if (!$siswa->jurusan) {
            return false;
        }

        if ($this->jurusan_diampu && is_array($this->jurusan_diampu)) {
            return in_array($siswa->jurusan, $this->jurusan_diampu);
        }

        return false;
    }

    public function canProcess($status)
    {
        $roleStatusMap = [
            'wali_kelas' => ['diajukan'],
            'bp' => ['disetujui_wali'],
            'kaprog' => ['disetujui_bp'],
            'tu' => ['disetujui_kaprog'],
            'hubin' => ['disetujui_tu'],
        ];

        return isset($roleStatusMap[$this->role]) && in_array($status, $roleStatusMap[$this->role]);
    }

    public function getUnreadNotificationsCount()
    {
        $query = $this->notifikasi()->where('is_read', false);
        
        // Filter khusus untuk kaprog berdasarkan jurusan
        if ($this->role === 'kaprog' && !empty($this->jurusan_diampu)) {
            $query->whereHas('permohonan.user', function($q) {
                $q->whereIn('jurusan', $this->jurusan_diampu);
            });
        }
        
        return $query->count();
    }
    
    public function getRoleLabel()
    {
        $labels = [
            'admin' => 'Administrator',
            'siswa' => 'Siswa',
            'wali_kelas' => 'Wali Kelas',
            'bp' => 'BP (Bimbingan dan Penyuluhan)',
            'kaprog' => 'Kaprog (Kepala Program)',
            'tu' => 'TU (Tata Usaha)',
            'hubin' => 'Hubin (Hubungan Industri)',
        ];

        return $labels[$this->role] ?? ucfirst($this->role);
    }
    
    public function getRoleColor()
    {
        $colors = [
            'admin' => 'danger',
            'siswa' => 'primary',
            'wali_kelas' => 'success',
            'bp' => 'info',
            'kaprog' => 'warning',
            'tu' => 'secondary',
            'hubin' => 'dark'
        ];

        return $colors[$this->role] ?? 'secondary';
    }
    
    /**
     * Check if user is assigned to a specific class
     */
    public function isAssignedToClass($kelas)
    {
        if (!$this->isWaliKelas() || !$this->custom_kelas_diampu) {
            return false;
        }
        
        // Parse kelas yang diampu (bisa multiple, dipisah koma)
        $kelasArray = array_map('trim', explode(',', $this->custom_kelas_diampu));
        
        return in_array($kelas, $kelasArray);
    }
    
    /**
     * Check if user is assigned to a specific department
     */
    public function isAssignedToDepartment($jurusan)
    {
        if (!$this->isKaprog() || empty($this->jurusan_diampu)) {
            return false;
        }
        
        return in_array($jurusan, $this->jurusan_diampu);
    }
}
