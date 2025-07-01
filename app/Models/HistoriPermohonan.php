<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriPermohonan extends Model
{
    use HasFactory;

    protected $table = 'histori_permohonan';

    protected $fillable = [
        'permohonan_id',
        'user_id',
        'status_dari',
        'status_ke',
        'role_processor',
        'catatan',
        'aksi',
    ];

    // Relasi
    public function permohonan()
    {
        return $this->belongsTo(PermohonanPkl::class, 'permohonan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function getAksiLabelAttribute()
    {
        $aksiLabels = [
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            'dikembalikan' => 'Dikembalikan',
            'diteruskan' => 'Diteruskan',
        ];

        return $aksiLabels[$this->aksi] ?? $this->aksi;
    }

    public function getAksiColorAttribute()
    {
        $aksiColors = [
            'disetujui' => 'success',
            'ditolak' => 'danger',
            'dikembalikan' => 'warning',
            'diteruskan' => 'info',
        ];

        return $aksiColors[$this->aksi] ?? 'secondary';
    }

    public function getRoleProcessorLabelAttribute()
    {
        $roleLabels = [
            'wali_kelas' => 'Wali Kelas',
            'bp' => 'BP (Bimbingan dan Penyuluhan)',
            'kaprog' => 'Kaprog (Kepala Program)',
            'tu' => 'TU (Tata Usaha)',
            'hubin' => 'Hubin (Hubungan Industri)',
            'admin' => 'Administrator',
        ];

        return $roleLabels[$this->role_processor] ?? $this->role_processor;
    }

    public function getStatusLabel()
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
        ];

        return $statusLabels[$this->status_ke] ?? $this->status_ke;
    }

    public function getStatusIcon()
    {
        if (strpos($this->status_ke, 'ditolak') !== false) {
            return 'times-circle';
        } elseif (strpos($this->status_ke, 'disetujui') !== false) {
            return 'check-circle';
        } elseif ($this->status_ke === 'dicetak_hubin') {
            return 'print';
        } elseif ($this->status_ke === 'diajukan') {
            return 'paper-plane';
        } else {
            return 'circle';
        }
    }

    public function getProcessorAttribute()
    {
        return $this->user;
    }
}