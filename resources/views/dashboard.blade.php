@extends('layouts.app')

@section('title', 'Dashboard - PKL Management')

@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    @if(auth()->user()->role === 'admin')
    <!-- Admin Dashboard -->
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $data['total_permohonan'] }}</h4>
                        <p class="card-text">Total Permohonan</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $data['permohonan_selesai'] }}</h4>
                        <p class="card-text">Permohonan Selesai</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $data['permohonan_proses'] }}</h4>
                        <p class="card-text">Dalam Proses</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $data['permohonan_ditolak'] }}</h4>
                        <p class="card-text">Ditolak</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if(auth()->user()->role === 'siswa')
    <!-- Siswa Dashboard -->
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $data['total_permohonan'] }}</h4>
                        <p class="card-text">Permohonan Saya</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $data['permohonan_selesai'] }}</h4>
                        <p class="card-text">Selesai</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $data['permohonan_proses'] }}</h4>
                        <p class="card-text">Dalam Proses</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if(in_array(auth()->user()->role, ['wali_kelas', 'bp', 'kaprog', 'tu', 'hubin']))
    <!-- Staff Dashboard -->
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $data['permohonan_menunggu'] }}</h4>
                        <p class="card-text">Menunggu Persetujuan</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-hourglass-half fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $data['permohonan_disetujui'] }}</h4>
                        <p class="card-text">Disetujui</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $data['permohonan_ditolak'] }}</h4>
                        <p class="card-text">Ditolak</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times fa-2x"></i>
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
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    @if(auth()->user()->role === 'siswa')
                        Permohonan Terbaru Saya
                    @elseif(auth()->user()->role === 'admin')
                        Semua Permohonan Terbaru
                    @else
                        Permohonan yang Perlu Diproses
                    @endif
                </h5>
                <a href="{{ route('permohonan.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($data['recent_applications']->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Perusahaan</th>
                                <th>Tanggal Mulai</th>
                                <th>Status</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['recent_applications'] as $index => $permohonan)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $permohonan->user->name }}</div>
                                            <small class="text-muted">{{ $permohonan->user->kelas ?? '-' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $permohonan->nama_perusahaan }}</div>
                                        <small class="text-muted">{{ Str::limit($permohonan->alamat_perusahaan, 30) }}</small>
                                    </div>
                                </td>
                                <td>{{ $permohonan->tanggal_mulai->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge status-badge {{ $permohonan->getStatusColor() }}">
                                        {{ $permohonan->getStatusLabel() }}
                                    </span>
                                </td>
                                <td>{{ $permohonan->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('permohonan.show', $permohonan) }}" 
                                           class="btn btn-outline-info" 
                                           title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(auth()->user()->role === 'siswa' && $permohonan->canEdit())
                                        <a href="{{ route('permohonan.edit', $permohonan) }}" 
                                           class="btn btn-outline-warning" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        @if($permohonan->canProcess(auth()->user()))
                                        <a href="{{ route('permohonan.show', $permohonan) }}" 
                                           class="btn btn-outline-success" 
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
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Belum ada permohonan</h5>
                    @if(auth()->user()->role === 'siswa')
                    <p class="text-muted">Mulai dengan membuat permohonan PKL pertama Anda.</p>
                    <a href="{{ route('permohonan.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Buat Permohonan
                    </a>
                    @else
                    <p class="text-muted">Tidak ada permohonan yang perlu diproses saat ini.</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role === 'admin')
<!-- Status Distribution Chart -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Distribusi Status Permohonan</h5>
            </div>
            <div class="card-body">
                <canvas id="statusChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Statistik Bulanan</h5>
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
@section('page-actions')
<a href="{{ route('permohonan.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>
    Buat Permohonan Baru
</a>
@endsection
@endif
@endsection