<?php

namespace App\Policies;

use App\Models\PermohonanPkl;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PermohonanPklPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Semua user yang sudah login bisa melihat daftar
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PermohonanPkl $permohonanPkl): bool
    {
        // Admin bisa melihat semua
        if ($user->isAdmin()) {
            return true;
        }

        // Siswa hanya bisa melihat permohonannya sendiri
        if ($user->isSiswa()) {
            return $user->id === $permohonanPkl->user_id;
        }

        // Staff bisa melihat permohonan yang perlu diproses atau sudah diproses
        return $this->canProcessOrHasProcessed($user, $permohonanPkl);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Hanya siswa yang bisa membuat permohonan
        return $user->isSiswa();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PermohonanPkl $permohonanPkl): bool
    {
        // Admin bisa update semua
        if ($user->isAdmin()) {
            return true;
        }

        // Siswa hanya bisa update permohonannya sendiri dan dalam status tertentu
        if ($user->isSiswa()) {
            return $user->id === $permohonanPkl->user_id && $permohonanPkl->canBeEdited();
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PermohonanPkl $permohonanPkl): bool
    {
        // Hanya admin yang bisa menghapus
        if ($user->isAdmin()) {
            return true;
        }

        // Siswa bisa menghapus permohonannya sendiri jika masih draft
        if ($user->isSiswa()) {
            return $user->id === $permohonanPkl->user_id && $permohonanPkl->status === 'draft';
        }

        return false;
    }

    /**
     * Determine whether the user can process the model.
     */
    public function process(User $user, PermohonanPkl $permohonanPkl): bool
    {
        // Admin bisa memproses semua
        if ($user->isAdmin()) {
            return true;
        }

        // Staff bisa memproses sesuai dengan role dan status
        return $permohonanPkl->canBeProcessedBy($user->role);
    }

    /**
     * Determine whether the user can print the model.
     */
    public function print(User $user, PermohonanPkl $permohonanPkl): bool
    {
        // Admin bisa mencetak semua permohonan
        if ($user->isAdmin()) {
            return true;
        }
        
        // Hubin yang bisa mencetak dengan status tertentu
        return $user->isHubin() && ($permohonanPkl->status === 'disetujui_tu' || $permohonanPkl->status === 'dicetak_hubin');
    }

    /**
     * Helper method to check if user can process or has processed the application
     */
    private function canProcessOrHasProcessed(User $user, PermohonanPkl $permohonanPkl): bool
    {
        // Cek apakah user bisa memproses permohonan ini
        if ($permohonanPkl->canBeProcessedBy($user->role)) {
            return true;
        }

        // Cek apakah user pernah memproses permohonan ini
        return $permohonanPkl->historiPermohonan()
            ->where('user_id', $user->id)
            ->where('role_processor', $user->role)
            ->exists();
    }
}