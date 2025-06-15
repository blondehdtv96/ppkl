<p align="center"><svg width="150" height="150" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg">
    <!-- Gear (Blue) -->
    <path d="M150,20 C80,20 20,80 20,150 C20,220 80,280 150,280 C220,280 280,220 280,150 C280,80 220,20 150,20 Z M150,40 C210,40 260,90 260,150 C260,210 210,260 150,260 C90,260 40,210 40,150 C40,90 90,40 150,40 Z" fill="#0000CC"/>
    <!-- Inner Gear Teeth -->
    <path d="M150,60 L160,40 L140,40 Z" fill="#0000CC"/>
    <path d="M190,70 L210,55 L190,50 Z" fill="#0000CC"/>
    <path d="M220,110 L240,100 L230,80 Z" fill="#0000CC"/>
    <path d="M220,190 L240,200 L230,220 Z" fill="#0000CC"/>
    <path d="M190,230 L210,245 L190,250 Z" fill="#0000CC"/>
    <path d="M150,240 L160,260 L140,260 Z" fill="#0000CC"/>
    <path d="M110,230 L90,245 L110,250 Z" fill="#0000CC"/>
    <path d="M80,190 L60,200 L70,220 Z" fill="#0000CC"/>
    <path d="M80,110 L60,100 L70,80 Z" fill="#0000CC"/>
    <path d="M110,70 L90,55 L110,50 Z" fill="#0000CC"/>
    
    <!-- Book (Blue) -->
    <path d="M100,220 L200,220 L200,240 L100,240 Z" fill="#0000CC"/>
    <path d="M100,240 C80,230 80,230 100,220" fill="#0000CC"/>
    <path d="M200,240 C220,230 220,230 200,220" fill="#0000CC"/>
    <path d="M150,220 L150,240" stroke="#FFFFFF" stroke-width="2"/>
    
    <!-- Computer/Student (White) -->
    <rect x="130" y="130" width="40" height="30" fill="white"/>
    <rect x="140" y="160" width="20" height="20" fill="white"/>
    <circle cx="150" cy="110" r="15" fill="white"/>
    
    <!-- Lightning Bolts (Red) -->
    <path d="M100,150 L120,170 L110,190 L140,160 L130,140 L140,120 Z" fill="#CC0000"/>
    <path d="M200,150 L180,170 L190,190 L160,160 L170,140 L160,120 Z" fill="#CC0000"/>
    
    <!-- Star (Red) -->
    <path d="M150,20 L157,35 L173,35 L162,45 L167,60 L150,50 L133,60 L138,45 L127,35 L143,35 Z" fill="#CC0000"/>
</svg></p>

<h1 align="center">Pendaftaran Permohonan PKL SMK BINA MANDIRI</h1>

<p align="center">
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="License: MIT"></a>
</p>

## Tentang Aplikasi

Aplikasi Pendaftaran Permohonan PKL SMK BINA MANDIRI adalah sistem manajemen untuk memudahkan pengelolaan dan pemantauan kegiatan Praktik Kerja Lapangan (PKL) di SMK BINA MANDIRI. Aplikasi ini dikembangkan menggunakan framework Laravel dan dirancang untuk membantu berbagai pemangku kepentingan dalam proses PKL.

## Fitur Utama

- **Manajemen Pengguna Multi-Role**: Admin, Siswa, Wali Kelas, BP, Kaprog, TU, dan Hubin
- **Pendaftaran PKL**: Siswa dapat mendaftar untuk program PKL
- **Pemantauan Real-time**: Pantau perkembangan peserta PKL secara real-time
- **Pengelolaan Data**: Kelola data peserta dan pembimbing dengan mudah
- **Informasi Jadwal & Lokasi**: Akses informasi jadwal dan lokasi PKL
- **Laporan & Evaluasi**: Buat dan lihat laporan serta evaluasi kegiatan PKL
- **Sistem Notifikasi**: Dapatkan pemberitahuan untuk aktivitas penting

## Persyaratan Sistem

- PHP >= 8.0
- MySQL/MariaDB
- Composer
- Node.js & NPM

## Instalasi

1. Clone repositori ini
   ```bash
   git clone https://github.com/username/pkl-management.git
   ```

2. Masuk ke direktori proyek
   ```bash
   cd pkl-management
   ```

3. Instal dependensi PHP
   ```bash
   composer install
   ```

4. Salin file .env.example menjadi .env dan sesuaikan konfigurasi database
   ```bash
   cp .env.example .env
   ```

5. Generate application key
   ```bash
   php artisan key:generate
   ```

6. Jalankan migrasi dan seeder
   ```bash
   php artisan migrate --seed
   ```

7. Jalankan server pengembangan
   ```bash
   php artisan serve
   ```

## Akun Demo

- **Admin**: admin@pkl.com / password
- **Siswa**: siswa1@pkl.com / password
- **Wali Kelas**: walikelas@pkl.com / password
- **BP**: bp@pkl.com / password
- **Kaprog**: kaprog@pkl.com / password
- **TU**: tu@pkl.com / password
- **Hubin**: hubin@pkl.com / password

## Kontribusi

Kontribusi untuk pengembangan aplikasi ini sangat diterima. Silakan buat pull request atau laporkan masalah melalui issue tracker.

## Lisensi

Aplikasi ini dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).

## Pengembang

Dikembangkan oleh TEAM ICT SMK BINA MANDIRI.

## Kontak

Untuk informasi lebih lanjut tentang aplikasi ini, silakan hubungi:

TEAM ICT SMK BINA MANDIRI  
Email: ict@smkbinamandiri.sch.id  
Website: [www.smkbinamandiri.sch.id](http://www.smkbinamandiri.sch.id)

## License

Aplikasi ini dilisensikan di bawah [MIT license](https://opensource.org/licenses/MIT).
