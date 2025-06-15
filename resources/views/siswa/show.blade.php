@extends('layouts.app')

@section('title', 'Detail Siswa - ' . $siswa->name)

@section('page-title', 'Detail Siswa')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <!-- Profil Siswa -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-user-graduate me-2"></i>
                    Profil Siswa
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="avatar-circle mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2rem;">
                        {{ strtoupper(substr($siswa->name, 0, 2)) }}
                    </div>
                    <h5 class="mb-0">{{ $siswa->name }}</h5>
                    <p class="text-muted">{{ $siswa->email }}</p>
                    <span class="badge bg-{{ $siswa->is_active ? 'success' : 'danger' }}">
                        {{ $siswa->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                
                <hr>
                
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Informasi Siswa</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span><i class="fas fa-id-card me-2"></i> NIS</span>
                            <span class="fw-bold">{{ $siswa->nis ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span><i class="fas fa-graduation-cap me-2"></i> Kelas</span>
                            <span class="fw-bold">{{ $siswa->kelas ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span><i class="fas fa-building me-2"></i> Jurusan</span>
                            <span class="fw-bold">{{ $siswa->jurusan ?? '-' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span><i class="fas fa-calendar-alt me-2"></i> Terdaftar</span>
                            <span class="fw-bold">{{ $siswa->created_at->format('d M Y') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Riwayat Permohonan PKL -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Riwayat Permohonan PKL
                </h5>
            </div>
            <div class="card-body p-0">
                @if($siswa->permohonanPkl->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Perusahaan</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswa->permohonanPkl as $permohonan)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0">{{ $permohonan->nama_perusahaan }}</h6>
                                            <small class="text-muted">{{ $permohonan->alamat_perusahaan }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $permohonan->created_at->format('d M Y') }}</td>
                                    <td>
                                        @php
                                            $statusClass = '';
                                            $statusText = '';
                                            
                                            switch($permohonan->status) {
                                                case 'draft':
                                                    $statusClass = 'secondary';
                                                    $statusText = 'Draft';
                                                    break;
                                                case 'submitted':
                                                    $statusClass = 'info';
                                                    $statusText = 'Diajukan';
                                                    break;
                                                case 'approved_walas':
                                                    $statusClass = 'primary';
                                                    $statusText = 'Disetujui Wali Kelas';
                                                    break;
                                                case 'approved_kaprog':
                                                    $statusClass = 'primary';
                                                    $statusText = 'Disetujui Kaprog';
                                                    break;
                                                case 'approved_hubin':
                                                    $statusClass = 'success';
                                                    $statusText = 'Disetujui Hubin';
                                                    break;
                                                case 'rejected':
                                                    $statusClass = 'danger';
                                                    $statusText = 'Ditolak';
                                                    break;
                                                default:
                                                    $statusClass = 'secondary';
                                                    $statusText = 'Tidak Diketahui';
                                            }
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('permohonan.show', $permohonan) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">Belum Ada Permohonan PKL</h5>
                        <p class="text-muted">Siswa ini belum mengajukan permohonan PKL.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.table td {
    vertical-align: middle;
}
</style>
@endpush
@endsection