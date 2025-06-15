@extends('layouts.app')

@section('title', 'Detail Pengguna - ' . $user->name)

@section('page-title')
    Detail Pengguna
    <span class="badge bg-{{ $user->getRoleColor() }} ms-2">{{ $user->getRoleLabel() }}</span>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        @can('update', $user)
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>
            Edit Pengguna
        </a>
        @endcan
        
        @can('toggleStatus', $user)
        <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-outline-{{ $user->is_active ? 'warning' : 'success' }}">
                <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }} me-2"></i>
                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>
        </form>
        @endcan
        
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- User Information -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-circle-large mx-auto mb-3">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-3">{{ $user->email }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-{{ $user->getRoleColor() }} fs-6">
                        {{ $user->getRoleLabel() }}
                    </span>
                    <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }} fs-6">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                
                <div class="text-muted small">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Bergabung {{ $user->created_at->format('d M Y') }}
                </div>
            </div>
        </div>
        
        <!-- Quick Stats for Siswa -->
        @if($user->role === 'siswa')
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistik Permohonan
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">{{ $permohonanStats['total'] ?? 0 }}</h4>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success mb-0">{{ $permohonanStats['approved'] ?? 0 }}</h4>
                        <small class="text-muted">Disetujui</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="text-warning mb-0">{{ $permohonanStats['pending'] ?? 0 }}</h4>
                            <small class="text-muted">Proses</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="text-danger mb-0">{{ $permohonanStats['rejected'] ?? 0 }}</h4>
                        <small class="text-muted">Ditolak</small>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Detailed Information -->
    <div class="col-md-8">
        <!-- Basic Information -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Dasar
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Nama Lengkap</label>
                            <div class="fw-bold">{{ $user->name }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small">Email</label>
                            <div class="fw-bold">{{ $user->email }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small">Role</label>
                            <div>
                                <span class="badge bg-{{ $user->getRoleColor() }}">{{ $user->getRoleLabel() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Status</label>
                            <div>
                                <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small">Tanggal Bergabung</label>
                            <div class="fw-bold">{{ $user->created_at->format('d M Y H:i') }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small">Terakhir Diperbarui</label>
                            <div class="fw-bold">{{ $user->updated_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Student Specific Information -->
        @if($user->role === 'siswa')
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Informasi Siswa
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label text-muted small">NIS</label>
                            <div class="fw-bold">{{ $user->nis ?? '-' }}</div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Kelas</label>
                            <div class="fw-bold">{{ $user->kelas ?? '-' }}</div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Jurusan</label>
                            <div class="fw-bold">{{ $user->jurusan ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Recent Activity -->
        @if($user->role === 'siswa' && isset($recentPermohonan) && $recentPermohonan->count() > 0)
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Permohonan Terbaru
                </h6>
                <a href="{{ route('permohonan.index', ['siswa' => $user->id]) }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Perusahaan</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPermohonan as $permohonan)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $permohonan->nama_perusahaan }}</div>
                                    <small class="text-muted">{{ $permohonan->bidang_usaha }}</small>
                                </td>
                                <td>
                                    <small>
                                        {{ $permohonan->tanggal_mulai->format('d M') }} - 
                                        {{ $permohonan->tanggal_selesai->format('d M Y') }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $permohonan->getStatusColor() }}">
                                        {{ $permohonan->getStatusLabel() }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $permohonan->created_at->format('d M Y') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('permohonan.show', $permohonan) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Activity Log (if available) -->
        @if(isset($activityLog) && $activityLog->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Log Aktivitas
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($activityLog as $activity)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">{{ $activity->description }}</h6>
                            <p class="timeline-text text-muted">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.avatar-circle-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 24px;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid white;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 14px;
}

.timeline-text {
    margin-bottom: 0;
    font-size: 12px;
}

.border-end {
    border-right: 1px solid #dee2e6 !important;
}
</style>
@endpush
@endsection