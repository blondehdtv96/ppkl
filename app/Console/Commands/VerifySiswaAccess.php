<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifySiswaAccess extends Command
{
    protected $signature = 'verify:siswa-access {--fix : Fix inconsistent data}';
    protected $description = 'Verify and optionally fix siswa access filtering';

    public function handle()
    {
        $this->info('Memverifikasi akses data siswa...');
        
        // Ambil semua wali kelas
        $waliKelas = User::where('role', 'wali_kelas')->get();
        
        $this->info("Ditemukan {$waliKelas->count()} wali kelas");
        
        foreach ($waliKelas as $wali) {
            $this->line("\n--- Wali Kelas: {$wali->name} ---");
            $this->line("ID: {$wali->id}");
            $this->line("Custom Kelas Diampu: {$wali->custom_kelas_diampu}");
            
            // Cek apakah ada custom_kelas_diampu
            if (!$wali->custom_kelas_diampu) {
                $this->error("MASALAH: Wali kelas {$wali->name} tidak memiliki custom_kelas_diampu");
            }
            
            // Hitung siswa yang bisa dilihat
            $siswaQuery = User::where('role', 'siswa');
            
            if ($wali->custom_kelas_diampu) {
                $kelasArray = array_map('trim', explode(',', $wali->custom_kelas_diampu));
                $siswaQuery->whereIn('kelas', $kelasArray);
            } else {
                $siswaQuery->whereRaw('1 = 0');
            }
            
            $siswaCount = $siswaQuery->count();
            $this->line("Siswa yang dapat dilihat: {$siswaCount}");
            
            // Tampilkan kelas-kelas siswa yang dapat dilihat
            $kelasYangDilihat = $siswaQuery->distinct('kelas')->pluck('kelas')->toArray();
            $this->line("Kelas yang dapat dilihat: " . implode(', ', $kelasYangDilihat));
            
            // Cek apakah ada siswa dari kelas lain yang bisa dilihat
            $allSiswa = User::where('role', 'siswa')->get();
            $wrongAccess = [];
            
            foreach ($allSiswa as $siswa) {
                $canView = $wali->canViewSiswa($siswa);
                $shouldView = false;
                
                // Tentukan apakah seharusnya bisa melihat berdasarkan custom_kelas_diampu
            if ($wali->custom_kelas_diampu) {
                $kelasArray = array_map('trim', explode(',', $wali->custom_kelas_diampu));
                $shouldView = in_array($siswa->kelas, $kelasArray);
            }
                
                if ($canView !== $shouldView) {
                    $wrongAccess[] = [
                        'siswa' => $siswa->name,
                        'kelas' => $siswa->kelas,
                        'can_view' => $canView,
                        'should_view' => $shouldView
                    ];
                }
            }
            
            if (!empty($wrongAccess)) {
                $this->error("MASALAH: Akses tidak konsisten untuk " . count($wrongAccess) . " siswa");
                foreach ($wrongAccess as $wrong) {
                    $this->line("  - {$wrong['siswa']} ({$wrong['kelas']}): canView={$wrong['can_view']}, shouldView={$wrong['should_view']}");
                }
            } else {
                $this->info("✓ Akses konsisten untuk semua siswa");
            }
        }
        
        $this->info("\nVerifikasi selesai.");
        
        if (!$this->option('fix')) {
            $this->line("Gunakan --fix untuk memperbaiki masalah yang ditemukan.");
        }
        
        return 0;
    }
}