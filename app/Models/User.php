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
        'kelas_diampu',
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
        'kelas_diampu' => 'array',
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
        return $this->notifikasi()->where('is_read', false)->count();
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
        if (!$this->isWaliKelas() || empty($this->kelas_diampu)) {
            return false;
        }
        
        return in_array($kelas, $this->kelas_diampu);
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
