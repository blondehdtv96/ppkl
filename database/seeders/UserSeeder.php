<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@pkl.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Wali Kelas
        User::create([
            'name' => 'Wali Kelas',
            'email' => 'wali@pkl.test',
            'password' => Hash::make('password'),
            'role' => 'wali_kelas',
            'is_active' => true,
        ]);

        // BP (Bimbingan dan Penyuluhan)
        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'bp@pkl.test',
            'password' => Hash::make('password'),
            'role' => 'bp',
            'is_active' => true,
        ]);

        // // Kaprog (Kepala Program)
        // User::create([
        //     'name' => 'Ahmad Fauzi',
        //     'email' => 'kaprog@pkl.test',
        //     'password' => Hash::make('password'),
        //     'role' => 'kaprog',
        //     'is_active' => true,
        // ]);

        // TU (Tata Usaha)
        User::create([
            'name' => 'Dewi Sartika',
            'email' => 'tu@pkl.test',
            'password' => Hash::make('password'),
            'role' => 'tu',
            'is_active' => true,
        ]);

        // Hubin (Hubungan Industri)
        User::create([
            'name' => 'Rudi Hermawan',
            'email' => 'hubin@pkl.test',
            'password' => Hash::make('password'),
            'role' => 'hubin',
            'is_active' => true,
        ]);

        // // Siswa contoh
        // User::create([
        //     'name' => 'Andi Pratama',
        //     'email' => 'siswa1@pkl.test',
        //     'password' => Hash::make('password'),
        //     'role' => 'siswa',
        //     'kelas' => 'XII RPL 1',
        //     'jurusan' => 'Rekayasa Perangkat Lunak',
        //     'nis' => '2021001',
        //     'is_active' => true,
        // ]);

        // User::create([
        //     'name' => 'Sari Indah',
        //     'email' => 'siswa2@pkl.test',
        //     'password' => Hash::make('password'),
        //     'role' => 'siswa',
        //     'kelas' => 'XII TKJ 1',
        //     'jurusan' => 'Teknik Komputer dan Jaringan',
        //     'nis' => '2021002',
        //     'is_active' => true,
        // ]);

        // User::create([
        //     'name' => 'Budi Setiawan',
        //     'email' => 'siswa3@pkl.test',
        //     'password' => Hash::make('password'),
        //     'role' => 'siswa',
        //     'kelas' => 'XII MM 1',
        //     'jurusan' => 'Multimedia',
        //     'nis' => '2021003',
        //     'is_active' => true,
        // ]);
    }
}