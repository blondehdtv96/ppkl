<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'permohonan_id',
        'judul',
        'pesan',
        'tipe',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permohonan()
    {
        return $this->belongsTo(PermohonanPkl::class, 'permohonan_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('tipe', $type);
    }

    // Helper methods
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    public function getTipeLabelAttribute()
    {
        $tipeLabels = [
            'info' => 'Informasi',
            'success' => 'Berhasil',
            'warning' => 'Peringatan',
            'error' => 'Error',
        ];

        return $tipeLabels[$this->tipe] ?? $this->tipe;
    }

    public function getTipeColorAttribute()
    {
        $tipeColors = [
            'info' => 'info',
            'success' => 'success',
            'warning' => 'warning',
            'error' => 'danger',
        ];

        return $tipeColors[$this->tipe] ?? 'secondary';
    }

    public function getTipeIconAttribute()
    {
        $tipeIcons = [
            'info' => 'fas fa-info-circle',
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'error' => 'fas fa-times-circle',
        ];

        return $tipeIcons[$this->tipe] ?? 'fas fa-bell';
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getTypeIcon()
    {
        return $this->getTipeIconAttribute();
    }

    public function getTypeColor()
    {
        return $this->getTipeColorAttribute();
    }
}