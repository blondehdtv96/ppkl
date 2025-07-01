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

        // Wali kelas hanya bisa melihat siswa dari kelas yang diampu
        if ($user->isWaliKelas()) {
            return $this->canViewBasedOnKelas($user, $permohonanPkl) && $this->canProcessOrHasProcessed($user, $permohonanPkl);
        }

        // Kaprog hanya bisa melihat siswa dari jurusan yang diampu
        if ($user->isKaprog()) {
            return $this->canViewBasedOnJurusan($user, $permohonanPkl) && $this->canProcessOrHasProcessed($user, $permohonanPkl);
        }

        // Staff lainnya bisa melihat permohonan yang perlu diproses atau sudah diproses
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

        // Wali kelas hanya bisa memproses siswa dari kelas yang diampu
        if ($user->isWaliKelas()) {
            return $this->canViewBasedOnKelas($user, $permohonanPkl) && $permohonanPkl->canBeProcessedBy($user->role);
        }

        // Kaprog hanya bisa memproses siswa dari jurusan yang diampu
        if ($user->isKaprog()) {
            return $this->canViewBasedOnJurusan($user, $permohonanPkl) && $permohonanPkl->canBeProcessedBy($user->role);
        }

        // Staff lainnya bisa memproses sesuai dengan role dan status
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

    /**
     * Helper method to check if wali kelas can view based on kelas
     */
    private function canViewBasedOnKelas(User $user, PermohonanPkl $permohonanPkl): bool
    {
        $siswa = $permohonanPkl->user;
        
        // Jika siswa tidak memiliki kelas, tidak bisa dilihat
        if (!$siswa->kelas) {
            return false;
        }

        // Cek custom_kelas_diampu (bisa multiple kelas dipisah koma)
        if ($user->custom_kelas_diampu) {
            // Parse kelas yang diampu (bisa multiple, dipisah koma)
            $kelasArray = array_map('trim', explode(',', $user->custom_kelas_diampu));
            return in_array($siswa->kelas, $kelasArray);
        }

        return false;
    }

    /**
     * Helper method to check if kaprog can view based on jurusan
     */
    private function canViewBasedOnJurusan(User $user, PermohonanPkl $permohonanPkl): bool
    {
        $siswa = $permohonanPkl->user;
        
        // Jika siswa tidak memiliki jurusan, tidak bisa dilihat
        if (!$siswa->jurusan) {
            return false;
        }

        // Cek jurusan_diampu (array)
        if ($user->jurusan_diampu && is_array($user->jurusan_diampu)) {
            return in_array($siswa->jurusan, $user->jurusan_diampu);
        }

        return false;
    }
}