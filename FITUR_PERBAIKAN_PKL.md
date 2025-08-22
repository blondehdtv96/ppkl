# Fitur Perbaikan Permohonan PKL

## Deskripsi
Fitur ini memungkinkan siswa untuk memperbaiki dan mengajukan ulang permohonan PKL yang telah ditolak oleh role tertentu dalam alur persetujuan.

## Cara Kerja

### Flow Perbaikan
1. **Siswa mengajukan permohonan PKL** → Status: `diajukan`
2. **Role tertentu menolak permohonan** → Status: `ditolak_wali`, `ditolak_bp`, `ditolak_kaprog`, atau `ditolak_tu`
3. **Siswa memperbaiki permohonan** → Status sementara: `diperbaiki`, lalu otomatis kembali ke status sebelum ditolak
4. **Permohonan masuk kembali ke alur approval** di role yang menolak sebelumnya

### Target Status Setelah Perbaikan
- `ditolak_wali` → kembali ke `diajukan` (diproses oleh wali_kelas)
- `ditolak_bp` → kembali ke `disetujui_wali` (diproses oleh bp)
- `ditolak_kaprog` → kembali ke `disetujui_bp` (diproses oleh kaprog) 
- `ditolak_tu` → kembali ke `disetujui_kaprog` (diproses oleh tu)

## Implementasi Teknis

### 1. Route Baru
```php
Route::post('/permohonan/{permohonan}/repair', [PermohonanPklController::class, 'repair'])
    ->name('permohonan.repair')
    ->middleware('validate.siswa.access');
```

### 2. Method di PermohonanPklController
- `repair()` - Method utama untuk menangani perbaikan permohonan
- Validasi: hanya siswa pemilik dan status yang dapat diperbaiki
- Otomatis mengembalikan ke alur approval yang sesuai
- Mengirim notifikasi ke role yang harus memproses

### 3. Method Baru di Model PermohonanPkl
- `canBeRepaired()` - Mengecek apakah permohonan dapat diperbaiki
- `getRepairTargetRole()` - Menentukan role target setelah perbaikan
- `getRepairTargetStatus()` - Menentukan status target setelah perbaikan

### 4. Status Baru: `diperbaiki`
- Ditambahkan ke enum status di database
- Memiliki warna `warning` (kuning) di interface
- Label: "Diperbaiki"

### 5. UI/UX
- Tombol "Perbaiki Permohonan" dengan ikon wrench (`fas fa-tools`)
- Muncul hanya untuk siswa pada permohonan dengan status ditolak
- Konfirmasi sebelum melakukan perbaikan
- Warna tombol: `btn-outline-primary`

## Histori Permohonan
Setiap perbaikan akan mencatat 2 entry histori:
1. Perubahan status ke `diperbaiki`
2. Perubahan status ke target status (misal: `diajukan` atau `disetujui_wali`)

## Notifikasi
- **Role yang menolak**: Menerima notifikasi bahwa permohonan telah diperbaiki
- **Siswa**: Mendapat konfirmasi sukses perbaikan melalui flash message

## Keamanan
- Hanya siswa pemilik permohonan yang dapat melakukan perbaikan
- Middleware `validate.siswa.access` memastikan akses yang tepat
- Validasi status memastikan hanya permohonan yang ditolak yang dapat diperbaiki

## Manfaat
1. **Efisiensi**: Siswa tidak perlu membuat permohonan baru
2. **Kelanjutan Alur**: Permohonan langsung masuk ke role yang menolak sebelumnya
3. **Histori Lengkap**: Semua perubahan tercatat dengan baik
4. **User Experience**: Interface yang intuitif dan mudah digunakan

## Testing
Untuk menguji fitur:
1. Login sebagai siswa
2. Buat permohonan PKL
3. Login sebagai role lain (wali kelas, bp, kaprog, atau tu)
4. Tolak permohonan tersebut
5. Login kembali sebagai siswa
6. Klik tombol "Perbaiki Permohonan" (ikon wrench)
7. Verifikasi bahwa status berubah sesuai alur yang diharapkan
