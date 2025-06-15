<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Hanya admin yang bisa melihat daftar semua user
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Admin bisa melihat semua user
        if ($user->isAdmin()) {
            return true;
        }

        // User bisa melihat profil sendiri
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Hanya admin yang bisa membuat user baru
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Admin bisa update semua user
        if ($user->isAdmin()) {
            return true;
        }

        // User bisa update profil sendiri (tapi tidak bisa mengubah role)
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Hanya admin yang bisa menghapus user
        if (!$user->isAdmin()) {
            return false;
        }

        // Admin tidak bisa menghapus dirinya sendiri
        if ($user->id === $model->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Determine whether the user can toggle status of the model.
     */
    public function toggleStatus(User $user, User $model): bool
    {
        // Hanya admin yang bisa mengubah status aktif user
        if (!$user->isAdmin()) {
            return false;
        }

        // Admin tidak bisa menonaktifkan dirinya sendiri
        if ($user->id === $model->id) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can change role of the model.
     */
    public function changeRole(User $user, User $model): bool
    {
        // Hanya admin yang bisa mengubah role
        if (!$user->isAdmin()) {
            return false;
        }

        // Admin tidak bisa mengubah role dirinya sendiri
        if ($user->id === $model->id) {
            return false;
        }

        return true;
    }
}