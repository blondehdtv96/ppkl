# Perbaikan Masalah Akses Data Siswa

## Masalah yang Ditemukan

Wali kelas dapat melihat data siswa dari kelas yang tidak mereka ampu. Hal ini terjadi karena:

1. **Method `canViewSiswaByKelas` tidak lengkap**: Method ini tidak mempertimbangkan `custom_kelas_diampu`
2. **Tidak ada middleware validasi**: Tidak ada validasi di level route untuk memastikan akses yang tepat
3. **Filtering tidak konsisten**: Beberapa controller menggunakan filtering yang berbeda

## Solusi yang Diterapkan

### 1. Perbaikan Method `canViewSiswaByKelas` di User Model

**File**: `app/Models/User.php`

```php
private function canViewSiswaByKelas(User $siswa)
{
    if (!$siswa->kelas) {
        return false;
    }

    // Pastikan ada kelas yang diampu (baik kelas_diampu atau custom_kelas_diampu)
    if ((!$this->kelas_diampu || !is_array($this->kelas_diampu) || empty($this->kelas_diampu)) && !$this->custom_kelas_diampu) {
        return false;
    }

    // Cek kelas_diampu (array untuk kelas umum)
    if (!empty($this->kelas_diampu) && is_array($this->kelas_diampu)) {
        if (in_array($siswa->kelas, $this->kelas_diampu)) {
            return true;
        }
    }
    
    // Cek custom_kelas_diampu (string untuk kelas XI spesifik)
    if ($this->custom_kelas_diampu) {
        if ($siswa->kelas === $this->custom_kelas_diampu) {
            return true;
        }
    }

    return false;
}
```

### 2. Middleware Validasi Akses Siswa

**File**: `app/Http/Middleware/ValidateSiswaAccess.php`

Middleware ini memvalidasi setiap request yang mengakses data siswa untuk memastikan wali kelas hanya dapat mengakses siswa dari kelas yang mereka ampu.

### 3. Registrasi Middleware

**File**: `app/Http/Kernel.php`

```php
'validate.siswa.access' => \App\Http\Middleware\ValidateSiswaAccess::class,
```

### 4. Penerapan Middleware pada Routes

**File**: `routes/web.php`

```php
// Siswa Routes (for Wali Kelas)
Route::middleware(['role:wali_kelas', 'validate.siswa.access'])->group(function() {
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/{user}', [SiswaController::class, 'show'])->name('siswa.show');
});

// Permohonan PKL Routes
Route::resource('permohonan', PermohonanPklController::class)->middleware('validate.siswa.access');
```

### 5. Perbaikan Filtering di Controllers

Semua controller yang menampilkan data siswa telah diperbaiki untuk menggunakan filtering yang konsisten:

```php
if (!empty($user->kelas_diampu) || $user->custom_kelas_diampu) {
    $query->where(function($q) use ($user) {
        // Cek kelas_diampu (array untuk kelas umum)
        if (!empty($user->kelas_diampu) && is_array($user->kelas_diampu)) {
            $q->whereIn('kelas', $user->kelas_diampu);
        }
        
        // Cek custom_kelas_diampu (string untuk kelas XI spesifik)
        if ($user->custom_kelas_diampu) {
            $q->orWhere('kelas', $user->custom_kelas_diampu);
        }
    });
} else {
    // Jika tidak ada kelas_diampu yang valid, tidak tampilkan siswa apapun
    $query->whereRaw('1 = 0');
}
```

## Tools Debug yang Tersedia

### 1. Command Artisan untuk Verifikasi

```bash
php artisan verify:siswa-access
php artisan verify:siswa-access --fix
```

**File**: `app/Console/Commands/VerifySiswaAccess.php`

Command ini akan:
- Memverifikasi konsistensi data `kelas_diampu`
- Memeriksa apakah filtering berfungsi dengan benar
- Memperbaiki data yang tidak konsisten (dengan flag `--fix`)

### 2. Debug API Endpoint

```
GET /debug/siswa-access
```

Endpoint ini mengembalikan JSON dengan informasi debug lengkap tentang akses siswa.

### 3. Debug View

```
GET /debug/siswa-access-view
```

Halaman web yang menampilkan:
- Informasi wali kelas
- Test akses untuk semua siswa
- Daftar siswa yang ditampilkan di index

## Testing

**File**: `tests/Feature/SiswaAccessTest.php`

Test yang mencakup:
- Filtering berdasarkan `kelas_diampu`
- Filtering berdasarkan `custom_kelas_diampu`
- Method `canViewSiswa`
- Method `getSiswaByKelas`

```bash
php artisan test tests/Feature/SiswaAccessTest.php
```

## Cara Menggunakan Debug Tools

### 1. Jika Masih Melihat Data dari Kelas Lain

1. **Jalankan verifikasi**:
   ```bash
   php artisan verify:siswa-access
   ```

2. **Jika ditemukan masalah, perbaiki**:
   ```bash
   php artisan verify:siswa-access --fix
   ```

3. **Bersihkan cache**:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   ```

### 2. Debug Melalui Web Interface

1. Login sebagai wali kelas
2. Akses `/debug/siswa-access-view`
3. Periksa:
   - Apakah `kelas_diampu` bertipe array
   - Apakah `custom_kelas_diampu` sudah benar
   - Apakah test akses menunjukkan hasil yang benar

### 3. Debug Melalui API

1. Login sebagai wali kelas
2. Akses `/debug/siswa-access`
3. Periksa response JSON untuk informasi detail

## Catatan Penting

1. **Backup Database**: Selalu backup database sebelum menjalankan command dengan flag `--fix`

2. **Hapus Debug Routes**: Setelah masalah teratasi, hapus routes debug dari `routes/web.php`:
   ```php
   // Hapus baris ini setelah debug selesai
   Route::get('/debug/siswa-access', ...);
   Route::get('/debug/siswa-access-view', ...);
   ```

3. **Hapus Debug Files**: Hapus file debug yang tidak diperlukan:
   - `app/Http/Controllers/DebugController.php`
   - `app/Console/Commands/VerifySiswaAccess.php`
   - `resources/views/debug/siswa-access.blade.php`

4. **Format Data**: Pastikan `kelas_diampu` selalu dalam format array di database

5. **Testing**: Selalu jalankan test setelah perubahan untuk memastikan tidak ada regresi

## Struktur Data yang Benar

### Tabel Users (Wali Kelas)

```sql
-- Contoh data yang benar
INSERT INTO users (name, role, kelas_diampu, custom_kelas_diampu) VALUES
('Wali Kelas A', 'wali_kelas', '["X-A", "X-B"]', NULL),
('Wali Kelas B', 'wali_kelas', NULL, 'XI-RPL-1'),
('Wali Kelas C', 'wali_kelas', '["XII-A"]', 'XI-TKJ-2');
```

### Validasi Data

- `kelas_diampu`: Harus berupa JSON array atau NULL
- `custom_kelas_diampu`: Harus berupa string atau NULL
- Tidak boleh keduanya NULL untuk wali kelas aktif

Dengan implementasi ini, masalah wali kelas yang dapat melihat siswa dari kelas lain seharusnya sudah teratasi.