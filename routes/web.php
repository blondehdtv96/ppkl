<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermohonanPklController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Redirect root ke dashboard jika sudah login, atau ke login jika belum
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [UserController::class, 'profile'])->name('users.profile');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('users.profile.update');

    // Siswa Routes (for Wali Kelas)
    Route::middleware('role:wali_kelas')->group(function() {
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/{user}', [SiswaController::class, 'show'])->name('siswa.show');
    });

    // Permohonan PKL Routes
    Route::resource('permohonan', PermohonanPklController::class);
    Route::post('/permohonan/{permohonan}/submit', [PermohonanPklController::class, 'submit'])->name('permohonan.submit');
    Route::post('/permohonan/{permohonan}/process', [PermohonanPklController::class, 'process'])->name('permohonan.process');
    Route::get('/permohonan/{permohonan}/print', [PermohonanPklController::class, 'print'])->name('permohonan.print');
    Route::patch('/permohonan/{permohonan}/update-pembimbing', [PermohonanPklController::class, 'updatePembimbing'])->name('permohonan.updatePembimbing')->middleware('role:hubin');

    // Notifikasi Routes
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/{notifikasi}', [NotifikasiController::class, 'show'])->name('notifikasi.show');
    Route::get('/notifikasi/{notifikasi}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
    Route::post('/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.read-all');
    Route::delete('/notifikasi/{notifikasi}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
    Route::delete('/notifikasi', [NotifikasiController::class, 'destroyAll'])->name('notifikasi.destroy-all');

    // User Management Routes (Admin only)
    Route::middleware('can:viewAny,App\Models\User')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('/users-export', [UserController::class, 'export'])->name('users.export');
        Route::post('/users-import', [UserController::class, 'import'])->name('users.import');
    });
});
