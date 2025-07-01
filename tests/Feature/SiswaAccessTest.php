<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiswaAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_wali_kelas_can_only_view_assigned_students()
    {
        // Buat wali kelas dengan custom_kelas_diampu XI
        $waliKelas = User::factory()->create([
            'role' => 'wali_kelas',
            'custom_kelas_diampu' => 'XI RPL 1'
        ]);

        // Buat siswa di kelas yang diampu
        $siswaXI = User::factory()->create([
            'role' => 'siswa',
            'kelas' => 'XI RPL 1'
        ]);

        // Buat siswa di kelas yang tidak diampu
        $siswaXII = User::factory()->create([
            'role' => 'siswa',
            'kelas' => 'XII RPL 1'
        ]);

        // Test canViewSiswa method
        $this->assertTrue($waliKelas->canViewSiswa($siswaXI));
        $this->assertFalse($waliKelas->canViewSiswa($siswaXII));
    }

    public function test_wali_kelas_can_view_students_from_custom_kelas_diampu()
    {
        // Buat wali kelas dengan multiple custom_kelas_diampu
        $waliKelas = User::factory()->create([
            'role' => 'wali_kelas',
            'custom_kelas_diampu' => 'X, XII'
        ]);

        // Buat siswa di kelas yang diampu
        $siswaX = User::factory()->create([
            'role' => 'siswa',
            'kelas' => 'X'
        ]);

        $siswaXII = User::factory()->create([
            'role' => 'siswa',
            'kelas' => 'XII'
        ]);

        // Buat siswa di kelas yang tidak diampu
        $siswaXI = User::factory()->create([
            'role' => 'siswa',
            'kelas' => 'XI RPL 1'
        ]);

        // Test canViewSiswa method
        $this->assertTrue($waliKelas->canViewSiswa($siswaX));
        $this->assertTrue($waliKelas->canViewSiswa($siswaXII));
        $this->assertFalse($waliKelas->canViewSiswa($siswaXI));
    }

    public function test_wali_kelas_can_view_custom_kelas_diampu_students()
    {
        // Buat wali kelas dengan custom_kelas_diampu saja
        $waliKelas = User::factory()->create([
            'role' => 'wali_kelas',
            'custom_kelas_diampu' => 'XI TKJ 2'
        ]);

        // Buat siswa di kelas custom yang diampu
        $siswaCustom = User::factory()->create([
            'role' => 'siswa',
            'kelas' => 'XI TKJ 2'
        ]);

        // Buat siswa di kelas lain
        $siswaLain = User::factory()->create([
            'role' => 'siswa',
            'kelas' => 'XI RPL 1'
        ]);

        // Test canViewSiswa method
        $this->assertTrue($waliKelas->canViewSiswa($siswaCustom));
        $this->assertFalse($waliKelas->canViewSiswa($siswaLain));
    }

    public function test_getSiswaByKelas_returns_correct_students()
    {
        // Buat wali kelas
        $waliKelas = User::factory()->create([
            'role' => 'wali_kelas',
            'custom_kelas_diampu' => 'XI RPL 1'
        ]);

        // Buat siswa yang sesuai
        $siswaXI = User::factory()->create([
            'role' => 'siswa',
            'kelas' => 'XI',
            'is_active' => true
        ]);

        $siswaCustom = User::factory()->create([
            'role' => 'siswa',
            'kelas' => 'XI RPL 1',
            'is_active' => true
        ]);

        // Buat siswa yang tidak sesuai
        $siswaXII = User::factory()->create([
            'role' => 'siswa',
            'kelas' => 'XII',
            'is_active' => true
        ]);

        $siswaCollection = $waliKelas->getSiswaByKelas();
        
        $this->assertCount(2, $siswaCollection);
        $this->assertTrue($siswaCollection->contains($siswaXI));
        $this->assertTrue($siswaCollection->contains($siswaCustom));
        $this->assertFalse($siswaCollection->contains($siswaXII));
    }
}