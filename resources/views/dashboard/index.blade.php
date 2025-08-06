@extends('layouts.app')

@section('title', 'Dashboard - PKL Management')

@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Role-specific dashboard content -->
    @if(auth()->user()->role == 'admin')
        <!-- Admin Dashboard -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Permohonan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['total_permohonan'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Permohonan Pending</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['permohonan_pending'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Permohonan Selesai</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['permohonan_selesai'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Permohonan Ditolak</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['permohonan_ditolak'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Permohonan Terbaru</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['recent_permohonan'] as $permohonan)
                                    <tr>
                                        <td>{{ $permohonan->user->name }}</td>
                                        <td>{{ $permohonan->created_at->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $permohonan->status_color }}">{{ $permohonan->status_label }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('permohonan.show', $permohonan->id) }}" class="btn btn-sm btn-info">
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
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Distribusi Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="statusPieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif(auth()->user()->role == 'siswa')
        <!-- Siswa Dashboard -->
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Permohonan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['total_permohonan'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Draft</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['permohonan_draft'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-edit fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Dalam Proses</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['permohonan_proses'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Selesai</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['permohonan_selesai'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Permohonan Saya</h6>
                        <a href="{{ route('permohonan.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Buat Permohonan
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['recent_permohonan'] as $permohonan)
                                    <tr>
                                        <td>{{ $permohonan->created_at->format('d M Y') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $permohonan->status_color }}">{{ $permohonan->status_label }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('permohonan.show', $permohonan->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($permohonan->status == 'draft')
                                            <a href="{{ route('permohonan.edit', $permohonan->id) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Notifikasi</h6>
                        <a href="{{ route('notifikasi.index') }}" class="btn btn-sm btn-info">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        @if($data['unread_notifications'] > 0)
                            <div class="alert alert-info">
                                Anda memiliki {{ $data['unread_notifications'] }} notifikasi yang belum dibaca.
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-bell-slash fa-3x text-gray-300 mb-3"></i>
                                <p>Tidak ada notifikasi baru</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Staff Dashboard (Wali Kelas, BP, Kaprog, TU, Hubin) -->
    @if(auth()->user()->role == 'kaprog')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i> Data Siswa Berdasarkan Jurusan & Kelas</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Jurusan</label>
                            <select class="form-select" name="jurusan">
                                <option value="">Semua Jurusan</option>
                                @foreach(auth()->user()->jurusan_diampu ?? [] as $jurusan)
                                    <option value="{{ $jurusan }}" {{ request('jurusan') == $jurusan ? 'selected' : '' }}>{{ $jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kelas</label>
                            <input type="text" class="form-control" name="kelas" value="{{ request('kelas') }}" placeholder="Contoh: XI TKJ A">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i> Cari</button>
                        </div>
                    </form>
                    @php
                        $kaprog = auth()->user();
                        $query = \App\Models\User::where('role', 'siswa');
                        if ($kaprog->jurusan_diampu && is_array($kaprog->jurusan_diampu)) {
                            if (request('jurusan')) {
                                if (in_array(request('jurusan'), $kaprog->jurusan_diampu)) {
                                    $query->where('jurusan', request('jurusan'));
                                } else {
                                    $query->whereRaw('1 = 0');
                                }
                            } else {
                                $query->whereIn('jurusan', $kaprog->jurusan_diampu);
                            }
                            if (request('kelas')) {
                                $query->where('kelas', 'like', "%" . request('kelas') . "%");
                            }
                        } else {
                            $query->whereRaw('1 = 0');
                        }
                        $siswaKaprog = $query->orderBy('kelas', 'asc')->orderBy('name', 'asc')->get();
                    @endphp
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Jurusan</th>
                                    <th>NIS</th>
                                    <th>Status</th>
                                    <th>Status Permohonan PKL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswaKaprog as $siswa)
                                <tr>
                                    <td>{{ $siswa->name }}</td>
                                    <td>{{ $siswa->kelas }}</td>
                                    <td>{{ $siswa->jurusan }}</td>
                                    <td>{{ $siswa->nis }}</td>
                                    <td>
                                        <span class="badge bg-{{ $siswa->is_active ? 'success' : 'danger' }}">
                                            {{ $siswa->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $latestPermohonan = $siswa->permohonanPkl()->latest()->first();
                                        @endphp
                                        @if($latestPermohonan)
                                            <span class="badge bg-{{ $latestPermohonan->status_color ?? 'secondary' }}">
                                                {{ $latestPermohonan->status_label ?? $latestPermohonan->status }}
                                            </span>
                                        @else
                                            <span class="text-muted">Belum ada permohonan</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada data siswa ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
        <div class="row">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Permohonan Menunggu</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['permohonan_menunggu'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Diproses Hari Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['processed_today'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Diproses</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['total_processed'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Permohonan Menunggu Persetujuan {{ $data['role_label'] }}</h6>
                    </div>
                    <div class="card-body">
                        @if($data['recent_applications']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nama Siswa</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['recent_applications'] as $permohonan)
                                        <tr>
                                            <td>{{ $permohonan->user->name }}</td>
                                            <td>{{ $permohonan->created_at->format('d M Y') }}</td>
                                            <td>
                                                <a href="{{ route('permohonan.show', $permohonan->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Lihat & Proses
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <p>Tidak ada permohonan yang menunggu persetujuan Anda saat ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Notifikasi</h6>
                        <a href="{{ route('notifikasi.index') }}" class="btn btn-sm btn-info">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        @if($data['unread_notifications'] > 0)
                            <div class="alert alert-info">
                                Anda memiliki {{ $data['unread_notifications'] }} notifikasi yang belum dibaca.
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-bell-slash fa-3x text-gray-300 mb-3"></i>
                                <p>Tidak ada notifikasi baru</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
@if(auth()->user()->role == 'admin')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Status distribution pie chart
    const statusData = @json($data['status_distribution']);
    const statusLabels = Object.keys(statusData).map(status => {
        const statusMap = {
            'draft': 'Draft',
            'diajukan': 'Diajukan',
            'disetujui_wali': 'Disetujui Wali Kelas',
            'disetujui_bp': 'Disetujui BP',
            'disetujui_kaprog': 'Disetujui Kaprog',
            'disetujui_tu': 'Disetujui TU',
            'disetujui_hubin': 'Disetujui Hubin',
            'dicetak_hubin': 'Disetujui Hubin',
            'ditolak_wali': 'Ditolak Wali Kelas',
            'ditolak_bp': 'Ditolak BP',
            'ditolak_kaprog': 'Ditolak Kaprog',
            'ditolak_tu': 'Ditolak TU',
            'ditolak_hubin': 'Ditolak Hubin'
        };
        return statusMap[status] || status;
    });
    const statusValues = Object.values(statusData);
    const statusColors = [
        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
        '#6f42c1', '#fd7e14', '#20c9a6', '#5a5c69', '#858796',
        '#f8f9fc', '#d1d3e2', '#b7b9cc'
    ];

    const ctx = document.getElementById('statusPieChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusValues,
                backgroundColor: statusColors,
                hoverBackgroundColor: statusColors,
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
            },
            legend: {
                display: true,
                position: 'bottom'
            },
            cutoutPercentage: 70,
        },
    });
</script>
@endif
@endsection