<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class SiswaExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    private $jurusan;
    private $kelas;
    private $statusPermohonan;
    private $kaprog;

    public function __construct($jurusan = null, $kelas = null, $statusPermohonan = null, User $kaprog = null)
    {
        $this->jurusan = $jurusan;
        $this->kelas = $kelas;
        $this->statusPermohonan = $statusPermohonan;
        $this->kaprog = $kaprog;
    }

    public function collection()
    {
        $query = User::where('role', 'siswa');

        // Filter berdasarkan jurusan yang diampu kaprog
        if ($this->kaprog && !empty($this->kaprog->jurusan_diampu)) {
            $query->whereIn('jurusan', $this->kaprog->jurusan_diampu);
        }

        // Filter berdasarkan jurusan jika ada
        if ($this->jurusan) {
            $query->where('jurusan', $this->jurusan);
        }

        // Filter berdasarkan kelas jika ada
        if ($this->kelas) {
            $query->where('kelas', $this->kelas);
        }

        $siswa = $query->orderBy('kelas', 'asc')->orderBy('name', 'asc')->get();

        // Filter berdasarkan status permohonan PKL jika ada
        if ($this->statusPermohonan) {
            $siswa = $siswa->filter(function($siswaItem) {
                $latestPermohonan = $siswaItem->permohonanPkl()->latest()->first();
                return $latestPermohonan && $latestPermohonan->status == $this->statusPermohonan;
            });
        }

        return $siswa;
    }

    public function headings(): array
    {
        return [
            'No',
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Jurusan',
            'Email',
            'Status Aktif',
            'Status Permohonan PKL',
            'Nama Perusahaan PKL',
            'Alamat Perusahaan',
            'Periode PKL',
            'Durasi PKL (Hari)',
            'Pembimbing Lapangan',
            'Tanggal Pengajuan',
            'Terakhir Update Status',
            'Catatan/Alasan Penolakan'
        ];
    }

    public function map($siswa): array
    {
        static $no = 0;
        $no++;

        // Ambil permohonan PKL terbaru siswa
        $latestPermohonan = $siswa->permohonanPkl()->latest()->first();

        // Status permohonan PKL
        $statusPkl = $latestPermohonan ? $latestPermohonan->getStatusLabel() : 'Belum ada permohonan';
        
        // Informasi perusahaan dan periode PKL
        $namaPerusahaan = $latestPermohonan ? $latestPermohonan->nama_perusahaan : '';
        $alamatPerusahaan = $latestPermohonan ? $latestPermohonan->alamat_perusahaan : '';
        $pembimbingLapangan = $latestPermohonan ? ($latestPermohonan->nama_pembimbing ?? '') : '';
        $tanggalPengajuan = $latestPermohonan ? $latestPermohonan->created_at->format('d/m/Y H:i') : '';
        $lastUpdate = $latestPermohonan ? $latestPermohonan->updated_at->format('d/m/Y H:i') : '';
        $catatanPenolakan = $latestPermohonan ? ($latestPermohonan->catatan_penolakan ?? '') : '';

        // Periode PKL
        $periodePkl = '';
        $durasiPkl = '';
        if ($latestPermohonan && $latestPermohonan->tanggal_mulai && $latestPermohonan->tanggal_selesai) {
            $periodePkl = $latestPermohonan->tanggal_mulai->format('d/m/Y') . ' - ' . $latestPermohonan->tanggal_selesai->format('d/m/Y');
            $durasiPkl = $latestPermohonan->tanggal_mulai->diffInDays($latestPermohonan->tanggal_selesai);
        }

        return [
            $no,
            $siswa->nis ?? '',
            $siswa->name,
            $siswa->kelas ?? '',
            $siswa->jurusan ?? '',
            $siswa->email ?? '',
            $siswa->is_active ? 'Aktif' : 'Nonaktif',
            $statusPkl,
            $namaPerusahaan,
            $alamatPerusahaan,
            $periodePkl,
            $durasiPkl,
            $pembimbingLapangan,
            $tanggalPengajuan,
            $lastUpdate,
            $catatanPenolakan
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
        ];
    }

    public function title(): string
    {
        $title = 'Data Siswa';
        
        if ($this->jurusan && $this->kelas) {
            $title .= " - {$this->kelas} {$this->jurusan}";
        } elseif ($this->jurusan) {
            $title .= " - Jurusan {$this->jurusan}";
        } elseif ($this->kelas) {
            $title .= " - Kelas {$this->kelas}";
        }

        if ($this->statusPermohonan) {
            $statusLabels = [
                'draft' => 'Draft',
                'diajukan' => 'Diajukan',
                'ditolak_wali' => 'Ditolak Wali Kelas',
                'disetujui_wali' => 'Disetujui Wali Kelas',
                'ditolak_bp' => 'Ditolak BP',
                'disetujui_bp' => 'Disetujui BP',
                'ditolak_kaprog' => 'Ditolak Kaprog',
                'disetujui_kaprog' => 'Disetujui Kaprog',
                'ditolak_tu' => 'Ditolak TU',
                'disetujui_tu' => 'Disetujui TU',
                'dicetak_hubin' => 'Disetujui Hubin'
            ];
            $statusLabel = $statusLabels[$this->statusPermohonan] ?? $this->statusPermohonan;
            $title .= " - {$statusLabel}";
        }

        return $title;
    }
}
