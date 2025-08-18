<?php

namespace App\Exports;

use App\Models\PermohonanPkl;
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

class PermohonanPklExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, ShouldAutoSize
{
    private $kelas;
    private $jurusan;
    private $statusFilter;
    private $kaprog;

    public function __construct($kelas = null, $jurusan = null, $statusFilter = null, User $kaprog = null)
    {
        $this->kelas = $kelas;
        $this->jurusan = $jurusan;
        $this->statusFilter = $statusFilter;
        $this->kaprog = $kaprog;
    }

    public function collection()
    {
        $query = PermohonanPkl::with(['user'])->whereHas('user', function($q) {
            // Filter berdasarkan jurusan yang diampu kaprog
            if ($this->kaprog && !empty($this->kaprog->jurusan_diampu)) {
                $q->whereIn('jurusan', $this->kaprog->jurusan_diampu);
            }

            // Filter berdasarkan kelas jika ada
            if ($this->kelas) {
                $q->where('kelas', 'like', '%' . $this->kelas . '%');
            }

            // Filter berdasarkan jurusan jika ada
            if ($this->jurusan) {
                $q->where('jurusan', $this->jurusan);
            }
        });

        // Filter berdasarkan status jika ada
        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'NIS',
            'Nama Siswa',
            'Kelas',
            'Jurusan',
            'Nama Perusahaan',
            'Bidang Usaha',
            'Alamat Perusahaan',
            'Kontak Perusahaan',
            'Email Perusahaan',
            'Pembimbing Lapangan',
            'Tanggal Mulai PKL',
            'Tanggal Selesai PKL',
            'Durasi (Hari)',
            'Alasan Memilih Perusahaan',
            'Status Permohonan',
            'Catatan Penolakan',
            'Tanggal Pengajuan',
            'Terakhir Diupdate'
        ];
    }

    public function map($permohonan): array
    {
        static $no = 0;
        $no++;

        $durasi = $permohonan->tanggal_mulai && $permohonan->tanggal_selesai 
                 ? $permohonan->tanggal_mulai->diffInDays($permohonan->tanggal_selesai) 
                 : 0;

        return [
            $no,
            $permohonan->user->nis ?? '',
            $permohonan->user->name ?? '',
            $permohonan->user->kelas ?? '',
            $permohonan->user->jurusan ?? '',
            $permohonan->nama_perusahaan,
            $permohonan->bidang_usaha,
            $permohonan->alamat_perusahaan,
            $permohonan->kontak_perusahaan ?? '',
            $permohonan->email_perusahaan ?? '',
            $permohonan->nama_pembimbing ?? '',
            $permohonan->tanggal_mulai ? $permohonan->tanggal_mulai->format('d/m/Y') : '',
            $permohonan->tanggal_selesai ? $permohonan->tanggal_selesai->format('d/m/Y') : '',
            $durasi,
            $permohonan->alasan,
            $permohonan->getStatusLabel(),
            $permohonan->catatan_penolakan ?? '',
            $permohonan->created_at->format('d/m/Y H:i'),
            $permohonan->updated_at->format('d/m/Y H:i')
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
        $title = 'Data Permohonan PKL';
        
        if ($this->kelas && $this->jurusan) {
            $title .= " - {$this->kelas} {$this->jurusan}";
        } elseif ($this->kelas) {
            $title .= " - Kelas {$this->kelas}";
        } elseif ($this->jurusan) {
            $title .= " - Jurusan {$this->jurusan}";
        }

        if ($this->statusFilter) {
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
            $statusLabel = $statusLabels[$this->statusFilter] ?? $this->statusFilter;
            $title .= " - {$statusLabel}";
        }

        return $title;
    }
}
