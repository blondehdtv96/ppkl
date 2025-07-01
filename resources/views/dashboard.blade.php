@extends('layouts.app')

@section('title', 'Dashboard - PKL Management')

@section('page-title', 'Dashboard')

@push('styles')
<style>
    .modern-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }
    
    .modern-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        pointer-events: none;
    }
    
    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .modern-card-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .modern-card-success {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    }
    
    .modern-card-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .modern-card-danger {
        background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    }
    
    .modern-card-info {
        background: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
    }
    
    .stats-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    
    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0;
    }
    
    .stats-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .content-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    
    .content-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }
    
    .table-modern {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .table-modern thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .table-modern tbody tr {
        transition: all 0.2s ease;
    }
    
    .table-modern tbody tr:hover {
        background-color: rgba(102, 126, 234, 0.05);
        transform: scale(1.01);
    }
    
    .welcome-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        color: white;
        padding: 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .welcome-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    .chart-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: none;
    }
    
    .empty-state {
        padding: 3rem 1rem;
        text-align: center;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        color: #e9ecef;
        margin-bottom: 1rem;
    }
    
    .btn-modern {
        border-radius: 25px;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .avatar-sm {
        width: 35px;
        height: 35px;
        font-size: 0.875rem;
    }
    
    .status-badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-weight: 600;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    .text-dark {
        color: #212529 !important;
    }
    
    .fw-medium {
        font-weight: 500 !important;
    }
    
    .gap-1 {
        gap: 0.25rem !important;
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }
    
    .gap-3 {
        gap: 1rem !important;
    }
    
    .rounded-pill {
        border-radius: 50rem !important;
    }
    
    .opacity-75 {
        opacity: 0.75 !important;
    }
    
    .card-header {
        padding: 1.5rem 1.5rem 0.75rem 1.5rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    @media (max-width: 768px) {
        .stats-number {
            font-size: 2rem;
        }
        
        .stats-icon {
            font-size: 2rem;
        }
        
        .welcome-section {
            padding: 1.5rem;
        }
        
        .welcome-section h2 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Welcome Section -->
<div class="welcome-section">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2 class="mb-2">Selamat Datang, {{ auth()->user()->name }}!</h2>
            <p class="mb-0 opacity-75">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }} - {{ now()->format('l, d F Y') }}</p>
        </div>
        <div class="col-md-4 text-end">
            <i class="fas fa-graduation-cap" style="font-size: 4rem; opacity: 0.3;"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    @if(auth()->user()->role === 'admin')
    <!-- Admin Dashboard -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="modern-card modern-card-primary text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="stats-number">{{ $data['total_permohonan'] }}</h2>
                        <p class="stats-label">Total Permohonan</p>
                    </div>
                    <div>
                        <i class="fas fa-file-alt stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="modern-card modern-card-success text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="stats-number">{{ $data['permohonan_selesai'] }}</h2>
                        <p class="stats-label">Permohonan Selesai</p>
                    </div>
                    <div>
                        <i class="fas fa-check-circle stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="modern-card modern-card-warning text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="stats-number">{{ $data['permohonan_proses'] }}</h2>
                        <p class="stats-label">Dalam Proses</p>
                    </div>
                    <div>
                        <i class="fas fa-clock stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="modern-card modern-card-danger text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="stats-number">{{ $data['permohonan_ditolak'] }}</h2>
                        <p class="stats-label">Ditolak</p>
                    </div>
                    <div>
                        <i class="fas fa-times-circle stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if(auth()->user()->role === 'siswa')
    <!-- Siswa Dashboard -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="modern-card modern-card-info text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="stats-number">{{ $data['total_permohonan'] }}</h2>
                        <p class="stats-label">Permohonan Saya</p>
                    </div>
                    <div>
                        <i class="fas fa-file-alt stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="modern-card modern-card-success text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="stats-number">{{ $data['permohonan_selesai'] }}</h2>
                        <p class="stats-label">Selesai</p>
                    </div>
                    <div>
                        <i class="fas fa-check-circle stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="modern-card modern-card-warning text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="stats-number">{{ $data['permohonan_proses'] }}</h2>
                        <p class="stats-label">Dalam Proses</p>
                    </div>
                    <div>
                        <i class="fas fa-clock stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if(in_array(auth()->user()->role, ['wali_kelas', 'bp', 'kaprog', 'tu', 'hubin']))
    <!-- Staff Dashboard -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="modern-card modern-card-primary text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="stats-number">{{ $data['permohonan_menunggu'] }}</h2>
                        <p class="stats-label">Menunggu Persetujuan</p>
                    </div>
                    <div>
                        <i class="fas fa-hourglass-half stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="modern-card modern-card-success text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="stats-number">{{ $data['permohonan_disetujui'] }}</h2>
                        <p class="stats-label">Disetujui</p>
                    </div>
                    <div>
                        <i class="fas fa-check stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="modern-card modern-card-danger text-white">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="stats-number">{{ $data['permohonan_ditolak'] }}</h2>
                        <p class="stats-label">Ditolak</p>
                    </div>
                    <div>
                        <i class="fas fa-times stats-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Recent Applications -->
<div class="row mt-4">
    <div class="col-12">
        <div class="content-card">
            <div class="card-header bg-white border-0 pb-0">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">
                        📋 
                        @if(auth()->user()->role === 'siswa')
                            Permohonan Terbaru Saya
                        @elseif(auth()->user()->role === 'admin')
                            Semua Permohonan Terbaru
                        @else
                            Permohonan yang Perlu Diproses
                        @endif
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark">{{ $data['recent_applications']->count() }} permohonan</span>
                        <a href="{{ route('permohonan.index') }}" class="btn btn-sm btn-modern btn-outline-primary">
                            <i class="fas fa-arrow-right me-1"></i>
                            Lihat Semua
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($data['recent_applications']->count() > 0)
                <div class="table-responsive">
                    <table class="table table-modern table-hover">
                        <thead>
                            <tr>
                                <th class="border-0">No</th>
                                <th class="border-0">Nama Siswa</th>
                                <th class="border-0">Perusahaan</th>
                                <th class="border-0">Tanggal Mulai</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Tanggal Pengajuan</th>
                                <th class="border-0">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['recent_applications'] as $index => $permohonan)
                            <tr>
                                <td class="border-0">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="badge bg-light text-dark rounded-pill">{{ $index + 1 }}</span>
                                    </div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $permohonan->user->name }}</div>
                                            <small class="text-muted">{{ $permohonan->user->kelas ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-building text-primary me-2"></i>
                                        <div>
                                            <div class="fw-medium text-dark">{{ $permohonan->nama_perusahaan }}</div>
                                            <small class="text-muted">{{ Str::limit($permohonan->alamat_perusahaan, 30) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-calendar text-success me-2"></i>
                                        <span class="fw-medium">{{ $permohonan->tanggal_mulai->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="border-0">
                                    <span class="badge status-badge {{ $permohonan->getStatusColor() }} d-flex align-items-center" style="width: fit-content;">
                                        <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                        {{ $permohonan->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-clock text-warning me-2"></i>
                                        <span class="fw-medium">{{ $permohonan->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </td>
                                <td class="border-0">
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('permohonan.show', $permohonan) }}" 
                                           class="btn btn-sm btn-modern btn-outline-info" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->role === 'siswa' && $permohonan->canEdit())
                                        <a href="{{ route('permohonan.edit', $permohonan) }}" 
                                           class="btn btn-sm btn-modern btn-outline-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        @if($permohonan->canProcess(auth()->user()))
                                        <a href="{{ route('permohonan.show', $permohonan) }}" 
                                           class="btn btn-sm btn-modern btn-outline-success" 
                                           title="Proses">
                                            <i class="fas fa-tasks"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <i class="fas fa-inbox empty-state-icon"></i>
                    <h5 class="text-muted mb-2">Belum ada permohonan</h5>
                    @if(auth()->user()->role === 'siswa')
                    <p class="text-muted mb-3">Mulai dengan membuat permohonan PKL pertama Anda.</p>
                    <a href="{{ route('permohonan.create') }}" class="btn btn-modern btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Buat Permohonan
                    </a>
                    @else
                    <p class="text-muted mb-0">Tidak ada permohonan yang perlu diproses saat ini.</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'admin')
<!-- Charts Section -->
<div class="row mt-4 g-4">
    <div class="col-lg-6">
        <div class="chart-card">
            <div class="card-header bg-white border-0">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-chart-pie text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Distribusi Status Permohonan</h5>
                        <p class="text-muted mb-0 small">Ringkasan status semua permohonan</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <canvas id="statusChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="chart-card">
            <div class="card-header bg-white border-0">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 fw-bold">Statistik Bulanan</h5>
                        <p class="text-muted mb-0 small">Data permohonan bulan ini</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <h4 class="text-primary">{{ $data['monthly_submissions'] }}</h4>
                        <small class="text-muted">Pengajuan Bulan Ini</small>
                    </div>
                    <div class="col-6 mb-3">
                        <h4 class="text-success">{{ $data['monthly_approvals'] }}</h4>
                        <small class="text-muted">Persetujuan Bulan Ini</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ $data['pending_count'] }}</h4>
                        <small class="text-muted">Menunggu Proses</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info">{{ $data['active_users'] }}</h4>
                        <small class="text-muted">Pengguna Aktif</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status Distribution Chart
    const ctx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($data['status_distribution'])) !!},
            datasets: [{
                data: {!! json_encode(array_values($data['status_distribution'])) !!},
                backgroundColor: [
                    '#007bff', // Diajukan
                    '#28a745', // Disetujui
                    '#ffc107', // Dalam Proses
                    '#dc3545', // Ditolak
                    '#6c757d', // Lainnya
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
@endif

@if(auth()->user()->role === 'siswa')
<!-- Create New Application Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="content-card text-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="card-body py-5">
                <div class="mb-4">
                    <i class="fas fa-plus-circle" style="font-size: 4rem; opacity: 0.8;"></i>
                </div>
                <h3 class="mb-3">Siap untuk memulai PKL?</h3>
                <p class="mb-4 opacity-75">Buat permohonan PKL baru dan mulai perjalanan profesional Anda</p>
                <a href="{{ route('permohonan.create') }}" class="btn btn-light btn-lg btn-modern px-5">
                    <i class="fas fa-plus me-2"></i>
                    Buat Permohonan Baru
                </a>
            </div>
        </div>
    </div>
</div>

@section('page-actions')
<a href="{{ route('permohonan.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>
    Buat Permohonan Baru
</a>
@endsection
@endif
@endsection