
@extends('layouts.app')

@section('title', 'Daftar Permohonan PKL')

@section('page-title')
@if(auth()->user()->role === 'siswa')
    Permohonan PKL Saya
@elseif(auth()->user()->role === 'admin')
    Semua Permohonan PKL
@else
    Kelola Permohonan PKL
@endif
@endsection

@section('content')
    @if(auth()->user()->role == 'kaprog')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0"><i class="fas fa-users me-2"></i> Data Siswa Berdasarkan Jurusan & Kelas</h5>
                        </div>
                        <div class="col-auto">
                            @php
                                $activeFilters = [];
                                if(request('jurusan_filter')) $activeFilters[] = 'Jurusan: ' . request('jurusan_filter');
                                if(request('kelas_filter')) $activeFilters[] = 'Kelas: ' . request('kelas_filter');
                                if(request('status_permohonan')) {
                                    $statusLabels = [
                                        'draft' => 'Draft',
                                        'diajukan' => 'Diajukan',
                                        'disetujui_wali' => 'Disetujui Wali Kelas',
                                        'disetujui_bp' => 'Disetujui BP',
                                        'disetujui_kaprog' => 'Disetujui Kaprog',
                                        'disetujui_tu' => 'Disetujui TU',
                                        'disetujui_hubin' => 'Disetujui Hubin',
                                        'ditolak_wali' => 'Ditolak Wali Kelas',
                                        'ditolak_bp' => 'Ditolak BP',
                                        'ditolak_kaprog' => 'Ditolak Kaprog',
                                        'ditolak_tu' => 'Ditolak TU',
                                        'ditolak_hubin' => 'Ditolak Hubin'
                                    ];
                                    $statusLabel = $statusLabels[request('status_permohonan')] ?? request('status_permohonan');
                                    $activeFilters[] = 'Status: ' . $statusLabel;
                                }
                            @endphp
                            @if(count($activeFilters) > 0)
                                <small class="text-light opacity-75">
                                    <i class="fas fa-filter me-1"></i>
                                    Filter: {{ implode(' | ', $activeFilters) }}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $kaprog = auth()->user();
                    @endphp
                    <form method="GET" action="{{ route('permohonan.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Jurusan</label>
                            <select class="form-select" name="jurusan_filter">
                                <option value="">Semua Jurusan</option>
                                @foreach(auth()->user()->jurusan_diampu as $jurusan)
                                    <option value="{{ $jurusan }}" {{ request('jurusan_filter') == $jurusan ? 'selected' : '' }}>{{ $jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kelas</label>
                            @php
                                // Ambil daftar kelas unik berdasarkan jurusan yang diampu kaprog
                                $kelasQuery = \App\Models\User::where('role', 'siswa')
                                    ->whereNotNull('kelas')
                                    ->where('kelas', '!=', '');
                                
                                if ($kaprog->jurusan_diampu && is_array($kaprog->jurusan_diampu)) {
                                    $kelasQuery->whereIn('jurusan', $kaprog->jurusan_diampu);
                                }
                                
                                // Filter berdasarkan jurusan yang dipilih jika ada
                                if (request('jurusan_filter')) {
                                    $kelasQuery->where('jurusan', request('jurusan_filter'));
                                }
                                
                                $kelasList = $kelasQuery->distinct()
                                    ->orderBy('kelas')
                                    ->pluck('kelas')
                                    ->unique()
                                    ->sort()
                                    ->values();
                            @endphp
                            <select class="form-select" name="kelas_filter" id="kelas_filter">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas }}" {{ request('kelas_filter') == $kelas ? 'selected' : '' }}>
                                        {{ $kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status Permohonan PKL</label>
                            <select class="form-select" name="status_permohonan">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status_permohonan') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="diajukan" {{ request('status_permohonan') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                <option value="disetujui_wali" {{ request('status_permohonan') == 'disetujui_wali' ? 'selected' : '' }}>Disetujui Wali Kelas</option>
                                <option value="disetujui_bp" {{ request('status_permohonan') == 'disetujui_bp' ? 'selected' : '' }}>Disetujui BP</option>
                                <option value="disetujui_kaprog" {{ request('status_permohonan') == 'disetujui_kaprog' ? 'selected' : '' }}>Disetujui Kaprog</option>
                                <option value="disetujui_tu" {{ request('status_permohonan') == 'disetujui_tu' ? 'selected' : '' }}>Disetujui TU</option>
                                <option value="disetujui_hubin" {{ request('status_permohonan') == 'disetujui_hubin' ? 'selected' : '' }}>Disetujui Hubin</option>
                                <option value="ditolak_wali" {{ request('status_permohonan') == 'ditolak_wali' ? 'selected' : '' }}>Ditolak Wali Kelas</option>
                                <option value="ditolak_bp" {{ request('status_permohonan') == 'ditolak_bp' ? 'selected' : '' }}>Ditolak BP</option>
                                <option value="ditolak_kaprog" {{ request('status_permohonan') == 'ditolak_kaprog' ? 'selected' : '' }}>Ditolak Kaprog</option>
                                <option value="ditolak_tu" {{ request('status_permohonan') == 'ditolak_tu' ? 'selected' : '' }}>Ditolak TU</option>
                                <option value="ditolak_hubin" {{ request('status_permohonan') == 'ditolak_hubin' ? 'selected' : '' }}>Ditolak Hubin</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i> Cari</button>
                        </div>
                        <div class="col-md-2">
                            @if(request()->hasAny(['jurusan_filter', 'kelas_filter', 'status_permohonan']))
                                <a href="{{ route('permohonan.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-times me-1"></i> Reset
                                </a>
                            @else
                                <button type="button" class="btn btn-outline-secondary w-100" disabled>
                                    <i class="fas fa-times me-1"></i> Reset
                                </button>
                            @endif
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-info w-100" onclick="printSiswaData()" title="Print Data Siswa">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                        <div class="col-md-2">
                            <div class="dropdown">
                                <button class="btn btn-success dropdown-toggle w-100" type="button" id="exportSiswaDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-file-excel me-1"></i> Export Excel
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportSiswaDropdown">
                                    <li><h6 class="dropdown-header">Export Data Siswa</h6></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="#" onclick="exportSiswaExcel()">
                                            <i class="fas fa-download me-2"></i>
                                            Semua Data (Filter Aktif)
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><small class="dropdown-item-text text-muted px-3">Ekspor akan menggunakan filter yang sedang aktif</small></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                    @php
                        $query = \App\Models\User::where('role', 'siswa');
                        if ($kaprog->jurusan_diampu && is_array($kaprog->jurusan_diampu)) {
                            $query->whereIn('jurusan', $kaprog->jurusan_diampu);
                            if (request('jurusan_filter')) {
                                $query->where('jurusan', request('jurusan_filter'));
                            }
                            if (request('kelas_filter')) {
                                $query->where('kelas', request('kelas_filter'));
                            }
                        } else {
                            $query->whereRaw('1 = 0');
                        }
                        $siswaKaprog = $query->orderBy('kelas', 'asc')->orderBy('name', 'asc')->get();
                        // Filter siswa berdasarkan status permohonan PKL
                        if(request('status_permohonan')) {
                            $siswaKaprog = $siswaKaprog->filter(function($siswa) {
                                $latestPermohonan = $siswa->permohonanPkl()->latest()->first();
                                return $latestPermohonan && $latestPermohonan->status == request('status_permohonan');
                            });
                        }
                    @endphp
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Kelas</th>
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
                                    <td colspan="5" class="text-center text-muted">Tidak ada data siswa ditemukan.</td>
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

    @if(auth()->user()->role == 'wali_kelas')
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0"><i class="fas fa-users me-2"></i> Data Siswa Permohonan PKL - Kelas yang Diampu</h5>
                        </div>
                        <div class="col-auto">
                            @php
                                $activeFiltersWali = [];
                                if(request('kelas_wali_filter')) $activeFiltersWali[] = 'Kelas: ' . request('kelas_wali_filter');
                                if(request('status_pkl_filter')) {
                                    $statusLabelsWali = [
                                        'draft' => 'Draft',
                                        'diajukan' => 'Diajukan',
                                        'disetujui_wali' => 'Disetujui Wali Kelas',
                                        'disetujui_bp' => 'Disetujui BP',
                                        'disetujui_kaprog' => 'Disetujui Kaprog',
                                        'disetujui_tu' => 'Disetujui TU',
                                        'disetujui_hubin' => 'Disetujui Hubin',
                                        'ditolak_wali' => 'Ditolak Wali Kelas',
                                        'ditolak_bp' => 'Ditolak BP',
                                        'ditolak_kaprog' => 'Ditolak Kaprog',
                                        'ditolak_tu' => 'Ditolak TU',
                                        'ditolak_hubin' => 'Ditolak Hubin'
                                    ];
                                    $statusLabelWali = $statusLabelsWali[request('status_pkl_filter')] ?? request('status_pkl_filter');
                                    $activeFiltersWali[] = 'Status: ' . $statusLabelWali;
                                }
                            @endphp
                            @if(count($activeFiltersWali) > 0)
                                <small class="text-light opacity-75">
                                    <i class="fas fa-filter me-1"></i>
                                    Filter: {{ implode(' | ', $activeFiltersWali) }}
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $waliKelas = auth()->user();
                    @endphp
                    <form method="GET" action="{{ route('permohonan.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label">Kelas</label>
                            @php
                                // Ambil daftar kelas yang diampu wali kelas
                                $kelasArrayWali = [];
                                if ($waliKelas->custom_kelas_diampu) {
                                    $kelasArrayWali = array_map('trim', explode(',', $waliKelas->custom_kelas_diampu));
                                }
                            @endphp
                            <select class="form-select" name="kelas_wali_filter">
                                <option value="">Semua Kelas</option>
                                @foreach($kelasArrayWali as $kelas)
                                    <option value="{{ $kelas }}" {{ request('kelas_wali_filter') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status Permohonan PKL</label>
                            <select class="form-select" name="status_pkl_filter">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status_pkl_filter') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="diajukan" {{ request('status_pkl_filter') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                                <option value="disetujui_wali" {{ request('status_pkl_filter') == 'disetujui_wali' ? 'selected' : '' }}>Disetujui Wali Kelas</option>
                                <option value="disetujui_bp" {{ request('status_pkl_filter') == 'disetujui_bp' ? 'selected' : '' }}>Disetujui BP</option>
                                <option value="disetujui_kaprog" {{ request('status_pkl_filter') == 'disetujui_kaprog' ? 'selected' : '' }}>Disetujui Kaprog</option>
                                <option value="disetujui_tu" {{ request('status_pkl_filter') == 'disetujui_tu' ? 'selected' : '' }}>Disetujui TU</option>
                                <option value="disetujui_hubin" {{ request('status_pkl_filter') == 'disetujui_hubin' ? 'selected' : '' }}>Disetujui Hubin</option>
                                <option value="ditolak_wali" {{ request('status_pkl_filter') == 'ditolak_wali' ? 'selected' : '' }}>Ditolak Wali Kelas</option>
                                <option value="ditolak_bp" {{ request('status_pkl_filter') == 'ditolak_bp' ? 'selected' : '' }}>Ditolak BP</option>
                                <option value="ditolak_kaprog" {{ request('status_pkl_filter') == 'ditolak_kaprog' ? 'selected' : '' }}>Ditolak Kaprog</option>
                                <option value="ditolak_tu" {{ request('status_pkl_filter') == 'ditolak_tu' ? 'selected' : '' }}>Ditolak TU</option>
                                <option value="ditolak_hubin" {{ request('status_pkl_filter') == 'ditolak_hubin' ? 'selected' : '' }}>Ditolak Hubin</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-success w-100"><i class="fas fa-search me-2"></i> Cari</button>
                        </div>
                        <div class="col-md-2">
                            @if(request()->hasAny(['kelas_wali_filter', 'status_pkl_filter']))
                                <a href="{{ route('permohonan.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-times me-1"></i> Reset
                                </a>
                            @else
                                <button type="button" class="btn btn-outline-secondary w-100" disabled>
                                    <i class="fas fa-times me-1"></i> Reset
                                </button>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-info w-100" onclick="printWaliKelasData()" title="Print Data Siswa PKL">
                                <i class="fas fa-print me-1"></i> Print
                            </button>
                        </div>
                    </form>
                    @php
                        // Query untuk mendapatkan data siswa yang diampu wali kelas dengan permohonan PKL
                        $queryWali = \App\Models\User::where('role', 'siswa')
                            ->whereNotNull('kelas')
                            ->where('kelas', '!=', '');
                        
                        if ($waliKelas->custom_kelas_diampu) {
                            $kelasArrayWali = array_map('trim', explode(',', $waliKelas->custom_kelas_diampu));
                            $queryWali->whereIn('kelas', $kelasArrayWali);
                            
                            // Filter berdasarkan kelas yang dipilih jika ada
                            if (request('kelas_wali_filter')) {
                                $queryWali->where('kelas', request('kelas_wali_filter'));
                            }
                        } else {
                            $queryWali->whereRaw('1 = 0'); // Tidak ada kelas yang diampu
                        }
                        
                        $siswaWaliKelas = $queryWali->with('permohonanPkl')
                            ->orderBy('kelas', 'asc')
                            ->orderBy('name', 'asc')
                            ->get();
                        
                        // Filter siswa berdasarkan status permohonan PKL jika dipilih
                        if(request('status_pkl_filter')) {
                            $siswaWaliKelas = $siswaWaliKelas->filter(function($siswa) {
                                $latestPermohonan = $siswa->permohonanPkl()->latest()->first();
                                return $latestPermohonan && $latestPermohonan->status == request('status_pkl_filter');
                            });
                        }
                    @endphp
                    
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered" id="waliKelasTable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>NIS</th>
                                    <th>Status Permohonan PKL</th>
                                    <th>Perusahaan</th>
                                    <th>Periode PKL</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($siswaWaliKelas as $index => $siswa)
                                @php
                                    $latestPermohonan = $siswa->permohonanPkl()->latest()->first();
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-bold">{{ $siswa->name }}</div>
                                        <small class="text-muted">{{ $siswa->email }}</small>
                                    </td>
                                    <td>{{ $siswa->kelas }}</td>
                                    <td>{{ $siswa->nis ?? '-' }}</td>
                                    <td>
                                        @if($latestPermohonan)
                                            <span class="badge bg-{{ $latestPermohonan->status_color ?? 'secondary' }}">
                                                {{ $latestPermohonan->status_label ?? $latestPermohonan->status }}
                                            </span>
                                        @else
                                            <span class="text-muted">Belum ada permohonan</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($latestPermohonan)
                                            <div class="fw-bold">{{ $latestPermohonan->nama_perusahaan }}</div>
                                            <small class="text-muted">{{ Str::limit($latestPermohonan->alamat_perusahaan, 30) }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($latestPermohonan)
                                            <div>
                                                <strong>{{ $latestPermohonan->tanggal_mulai->format('d/m/Y') }}</strong>
                                                <br>
                                                <small class="text-muted">s/d {{ $latestPermohonan->tanggal_selesai->format('d/m/Y') }}</small>
                                                <br>
                                                <small class="badge bg-light text-dark">{{ $latestPermohonan->tanggal_mulai->diffInDays($latestPermohonan->tanggal_selesai) }} hari</small>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($latestPermohonan)
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('permohonan.show', $latestPermohonan) }}" 
                                                   class="btn btn-outline-info" 
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($latestPermohonan->canProcess(auth()->user()))
                                                <a href="{{ route('permohonan.show', $latestPermohonan) }}" 
                                                   class="btn btn-outline-success" 
                                                   title="Proses Permohonan">
                                                    <i class="fas fa-tasks"></i>
                                                </a>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Tidak ada data siswa ditemukan.</td>
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
    {{-- ...existing code... --}}
    @parent
@endsection

@section('page-actions')
@if(auth()->user()->role === 'siswa')
<a href="{{ route('permohonan.create') }}" class="btn btn-primary">
    <i class="fas fa-plus me-2"></i>
    Buat Permohonan Baru
</a>
@endif
@endsection

@section('content')
<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('permohonan.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Pencarian</label>
                <input type="text" 
                       class="form-control" 
                       id="search" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nama siswa atau perusahaan...">
            </div>
            
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="diajukan" {{ request('status') == 'diajukan' ? 'selected' : '' }}>Diajukan</option>
                    <option value="ditolak_wali" {{ request('status') == 'ditolak_wali' ? 'selected' : '' }}>Ditolak Wali</option>
                    <option value="disetujui_wali" {{ request('status') == 'disetujui_wali' ? 'selected' : '' }}>Disetujui Wali</option>
                    <option value="ditolak_bp" {{ request('status') == 'ditolak_bp' ? 'selected' : '' }}>Ditolak BP</option>
                    <option value="disetujui_bp" {{ request('status') == 'disetujui_bp' ? 'selected' : '' }}>Disetujui BP</option>
                    <option value="ditolak_kaprog" {{ request('status') == 'ditolak_kaprog' ? 'selected' : '' }}>Ditolak Kaprog</option>
                    <option value="disetujui_kaprog" {{ request('status') == 'disetujui_kaprog' ? 'selected' : '' }}>Disetujui Kaprog</option>
                    <option value="ditolak_tu" {{ request('status') == 'ditolak_tu' ? 'selected' : '' }}>Ditolak TU</option>
                    <option value="disetujui_tu" {{ request('status') == 'disetujui_tu' ? 'selected' : '' }}>Disetujui TU</option>
                    <option value="dicetak_hubin" {{ request('status') == 'dicetak_hubin' ? 'selected' : '' }}>Dicetak Hubin</option>
                </select>
            </div>
            
            @if(auth()->user()->role === 'admin')
            <div class="col-md-2">
                <label for="role" class="form-label">Role Pemroses</label>
                <select class="form-select" id="role" name="role">
                    <option value="">Semua Role</option>
                    <option value="wali_kelas" {{ request('role') == 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                    <option value="bp" {{ request('role') == 'bp' ? 'selected' : '' }}>BP</option>
                    <option value="kaprog" {{ request('role') == 'kaprog' ? 'selected' : '' }}>Kaprog</option>
                    <option value="tu" {{ request('role') == 'tu' ? 'selected' : '' }}>TU</option>
                    <option value="hubin" {{ request('role') == 'hubin' ? 'selected' : '' }}>Hubin</option>
                </select>
            </div>
            @endif
            
            @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'wali_kelas' && auth()->user()->custom_kelas_diampu))
            <div class="col-md-2">
                <label for="kelas" class="form-label">Kelas</label>
                <select class="form-select" id="kelas" name="kelas">
                    <option value="">Semua Kelas</option>
                    @if(auth()->user()->role === 'admin')
                        <option value="X" {{ request('kelas') == 'X' ? 'selected' : '' }}>X</option>
                        <option value="XI" {{ request('kelas') == 'XI' ? 'selected' : '' }}>XI</option>
                        <option value="XII" {{ request('kelas') == 'XII' ? 'selected' : '' }}>XII</option>
                    @else
                        @php
                            $kelasArray = array_map('trim', explode(',', auth()->user()->custom_kelas_diampu));
                        @endphp
                        @foreach($kelasArray as $kelas)
                            <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            @endif
            
            @if(auth()->user()->role === 'admin' || (auth()->user()->role === 'kaprog' && !empty(auth()->user()->jurusan_diampu)))
            <div class="col-md-2">
                <label for="jurusan" class="form-label">Jurusan</label>
                <select class="form-select" id="jurusan" name="jurusan">
                    <option value="">Semua Jurusan</option>
                    @if(auth()->user()->role === 'admin')
                        <option value="RPL" {{ request('jurusan') == 'RPL' ? 'selected' : '' }}>RPL</option>
                        <option value="TKJ" {{ request('jurusan') == 'TKJ' ? 'selected' : '' }}>TKJ</option>
                        <option value="MM" {{ request('jurusan') == 'MM' ? 'selected' : '' }}>MM</option>
                        <option value="TBSM" {{ request('jurusan') == 'TBSM' ? 'selected' : '' }}>TBSM</option>
                        <option value="TKR" {{ request('jurusan') == 'TKR' ? 'selected' : '' }}>TKR</option>
                        <option value="AKL" {{ request('jurusan') == 'AKL' ? 'selected' : '' }}>AKL</option>
                        <option value="OTKP" {{ request('jurusan') == 'OTKP' ? 'selected' : '' }}>OTKP</option>
                    @else
                        @foreach(auth()->user()->jurusan_diampu as $jurusan)
                            <option value="{{ $jurusan }}" {{ request('jurusan') == $jurusan ? 'selected' : '' }}>{{ $jurusan }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            @endif
            
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'siswa')
            <div class="col-md-2">
                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                <input type="date" 
                       class="form-control" 
                       id="tanggal_mulai" 
                       name="tanggal_mulai" 
                       value="{{ request('tanggal_mulai') }}">
            </div>
            @endif
            
            <div class="col-md-2">
                <label for="per_page" class="form-label">Per Halaman</label>
                <select class="form-select" id="per_page" name="per_page">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div>
            
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Results Section -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            Daftar Permohonan 
            <span class="badge bg-secondary">{{ $permohonan->total() }}</span>
        </h5>
        
        @if(request()->hasAny(['search', 'status', 'role', 'tanggal_mulai']))
        <a href="{{ route('permohonan.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-times me-1"></i>
            Reset Filter
        </a>
        @endif
    </div>
    
    <div class="card-body">
        @if($permohonan->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        @if(auth()->user()->role !== 'siswa')
                        <th>Siswa</th>
                        @endif
                        <th>Perusahaan</th>
                        <th>Periode PKL</th>
                        <th>Status</th>
                        <th>Pemroses Saat Ini</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permohonan as $index => $item)
                    <tr>
                        <td>{{ $permohonan->firstItem() + $index }}</td>
                        
                        @if(auth()->user()->role !== 'siswa')
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-2">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $item->user->name }}</div>
                                    <small class="text-muted">
                                        {{ $item->user->kelas ?? '-' }} | 
                                        {{ $item->user->nis ?? '-' }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        @endif
                        
                        <td>
                            <div>
                                <div class="fw-bold">{{ $item->nama_perusahaan }}</div>
                                <small class="text-muted">{{ Str::limit($item->alamat_perusahaan, 40) }}</small>
                            </div>
                        </td>
                        
                        <td>
                            <div>
                                <strong>{{ $item->tanggal_mulai->format('d/m/Y') }}</strong>
                                <br>
                                <small class="text-muted">s/d {{ $item->tanggal_selesai->format('d/m/Y') }}</small>
                                <br>
                                <small class="badge bg-light text-dark">{{ $item->tanggal_mulai->diffInDays($item->tanggal_selesai) }} hari</small>
                            </div>
                        </td>
                        
                        <td>
                            <span class="badge status-badge {{ $item->getStatusColor() }}" style="color:
                                @if($item->status == 'diajukan') #0d6efd;
                                @elseif(Str::startsWith($item->status, 'disetujui')) #198754;
                                @elseif(Str::startsWith($item->status, 'ditolak')) #dc3545;
                                @elseif($item->status == 'dicetak_hubin') #0dcaf0;
                                @elseif($item->status == 'diperbaiki') #000;
                                @else #6c757d;
                                @endif
                            ">
                                {{ $item->getStatusLabel() }}
                            </span>
                            @if($item->isRejected())
                            <br>
                            <small class="text-danger">
                                <i class="fas fa-exclamation-triangle"></i>
                                Perlu Perbaikan
                            </small>
                            @endif
                        </td>
                        
                        <td>
                            @if($item->current_role)
                            <span class="badge bg-info">
                                {{ ucfirst(str_replace('_', ' ', $item->current_role)) }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        
                        <td>
                            <div>
                                {{ $item->created_at->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                            </div>
                        </td>
                        
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('permohonan.show', $item) }}" 
                                   class="btn btn-outline-info" 
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if(auth()->user()->role === 'siswa' && $item->canEdit())
                                <a href="{{ route('permohonan.edit', $item) }}" 
                                   class="btn btn-outline-warning" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                
                                @if(auth()->user()->role === 'siswa' && $item->canBeRepaired())
                                <form action="{{ route('permohonan.repair', $item) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin memperbaiki dan mengajukan ulang permohonan ini?')">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-outline-primary" 
                                            title="Perbaiki Permohonan">
                                        <i class="fas fa-tools"></i>
                                    </button>
                                </form>
                                @endif
                                
                                @if($item->canProcess(auth()->user()))
                                <a href="{{ route('permohonan.show', $item) }}" 
                                   class="btn btn-outline-success" 
                                   title="Proses Permohonan">
                                    <i class="fas fa-tasks"></i>
                                </a>
                                @endif
                                
                                @if($item->status === 'dicetak_hubin')
                                <a href="{{ route('permohonan.print', $item) }}" 
                                   class="btn btn-outline-secondary" 
                                   title="Cetak Surat" 
                                   target="_blank">
                                    <i class="fas fa-print"></i>
                                </a>
                                @endif
                                
                                @if(auth()->user()->role === 'admin')
                                <div class="btn-group">
                                    <button type="button" 
                                            class="btn btn-outline-danger dropdown-toggle" 
                                            data-bs-toggle="dropdown" 
                                            title="Aksi Admin">
                                        <i class="fas fa-cog"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('permohonan.edit', $item) }}">
                                                <i class="fas fa-edit me-2"></i>
                                                Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('permohonan.destroy', $item) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus permohonan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash me-2"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <div>
                <small class="text-muted">
                    Menampilkan {{ $permohonan->firstItem() }} - {{ $permohonan->lastItem() }} 
                    dari {{ $permohonan->total() }} permohonan
                </small>
            </div>
            <div>
                {{ $permohonan->appends(request()->query())->links() }}
            </div>
        </div>
        
        @else
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Tidak ada permohonan ditemukan</h5>
            
            @if(request()->hasAny(['search', 'status', 'role', 'tanggal_mulai']))
            <p class="text-muted">Coba ubah filter pencarian Anda.</p>
            <a href="{{ route('permohonan.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-times me-2"></i>
                Reset Filter
            </a>
            @else
                @if(auth()->user()->role === 'siswa')
                <p class="text-muted">Mulai dengan membuat permohonan PKL pertama Anda.</p>
                <a href="{{ route('permohonan.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Buat Permohonan
                </a>
                @else
                <p class="text-muted">Belum ada permohonan yang masuk.</p>
                @endif
            @endif
        </div>
        @endif
    </div>
</div>

<!-- Quick Stats -->
@if(auth()->user()->role !== 'siswa')
@php
    $allPermohonan = \App\Models\PermohonanPkl::query();
    if(auth()->user()->role === 'admin') {
        // admin: semua data
    } elseif(auth()->user()->role === 'kaprog') {
        $allPermohonan->whereHas('user', function($q) {
            $q->whereIn('jurusan', auth()->user()->jurusan_diampu ?? []);
        });
    } // tambahkan else if lain jika perlu
    $menunggu = (clone $allPermohonan)->where('status', 'diajukan')->count();
    $disetujui = (clone $allPermohonan)->where(function($q){
        $q->where('status', 'disetujui_wali')
          ->orWhere('status', 'disetujui_bp')
          ->orWhere('status', 'disetujui_kaprog')
          ->orWhere('status', 'disetujui_tu')
          ->orWhere('status', 'disetujui_hubin');
    })->count();
    $ditolak = (clone $allPermohonan)->where(function($q){
        $q->where('status', 'ditolak_wali')
          ->orWhere('status', 'ditolak_bp')
          ->orWhere('status', 'ditolak_kaprog')
          ->orWhere('status', 'ditolak_tu')
          ->orWhere('status', 'ditolak_hubin');
    })->count();
    $selesai = (clone $allPermohonan)->where('status', 'dicetak_hubin')->count();
@endphp
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h4 class="text-primary">{{ $menunggu }}</h4>
                <small class="text-muted">Menunggu Proses</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h4 class="text-success">{{ $disetujui }}</h4>
                <small class="text-muted">Disetujui</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h4 class="text-danger">{{ $ditolak }}</h4>
                <small class="text-muted">Ditolak</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h4 class="text-info">{{ $selesai }}</h4>
                <small class="text-muted">Selesai</small>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
@if(auth()->user()->role === 'kaprog' || auth()->user()->role === 'wali_kelas')
<script>
    // Dynamic filter untuk kelas berdasarkan jurusan
    document.addEventListener('DOMContentLoaded', function() {
        const jurusanSelect = document.querySelector('select[name="jurusan_filter"]');
        const kelasSelect = document.querySelector('select[name="kelas_filter"]');
        
        if (jurusanSelect && kelasSelect) {
            // Store original kelas options
            const originalKelasOptions = Array.from(kelasSelect.options).slice(1); // Exclude "Semua Kelas"
            
            jurusanSelect.addEventListener('change', function() {
                const selectedJurusan = this.value;
                
                // Clear current options except "Semua Kelas"
                kelasSelect.innerHTML = '<option value="">Semua Kelas</option>';
                
                if (selectedJurusan) {
                    // Filter kelas options based on selected jurusan
                    // This will be updated dynamically via AJAX in the future
                    // For now, just keep all options
                    originalKelasOptions.forEach(option => {
                        kelasSelect.appendChild(option.cloneNode(true));
                    });
                } else {
                    // Show all kelas if no jurusan selected
                    originalKelasOptions.forEach(option => {
                        kelasSelect.appendChild(option.cloneNode(true));
                    });
                }
                
                // Reset selected value
                kelasSelect.value = '';
            });
        }
    });
    
    function exportExcel() {
        // Get current filter parameters
        const urlParams = new URLSearchParams(window.location.search);
        const params = new URLSearchParams();
        
        // Add filter parameters if they exist
        if (urlParams.get('kelas')) {
            params.append('kelas', urlParams.get('kelas'));
        }
        if (urlParams.get('jurusan')) {
            params.append('jurusan', urlParams.get('jurusan'));
        }
        if (urlParams.get('status')) {
            params.append('status_permohonan', urlParams.get('status'));
        }
        
        // Build export URL
        const exportUrl = '{{ route("permohonan.export.excel") }}' + '?' + params.toString();
        
        // Show loading state
        const button = document.getElementById('exportDropdown');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mengekspor...';
        button.disabled = true;
        
        // Create a temporary link to download file
        const link = document.createElement('a');
        link.href = exportUrl;
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Reset button after a short delay
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        }, 2000);
    }

    function exportSiswaExcel() {
        // Get current filter parameters from the student data section
        const form = document.querySelector('form[action="{{ route("permohonan.index") }}"]');
        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        // Add filter parameters
        if (formData.get('jurusan_filter')) {
            params.append('jurusan', formData.get('jurusan_filter'));
        }
        if (formData.get('kelas_filter')) {
            params.append('kelas', formData.get('kelas_filter'));
        }
        if (formData.get('status_permohonan')) {
            params.append('status', formData.get('status_permohonan'));
        }
        
        // Build export URL
        const exportUrl = '{{ route("siswa.export.excel") }}' + '?' + params.toString();
        
        // Show loading state
        const button = document.getElementById('exportSiswaDropdown');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Mengekspor...';
        button.disabled = true;
        
        // Create a temporary link to download file
        const link = document.createElement('a');
        link.href = exportUrl;
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Reset button after a short delay
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        }, 2000);
    }

    function printSiswaData() {
        // Get filter information
        const form = document.querySelector('form[action="{{ route("permohonan.index") }}"]');
        const formData = new FormData(form);
        const jurusanFilter = formData.get('jurusan_filter') || 'Semua Jurusan';
        const kelasFilter = formData.get('kelas_filter') || 'Semua Kelas';
        const statusFilter = formData.get('status_permohonan');
        
        // Get status label
        let statusLabel = 'Semua Status';
        if (statusFilter) {
            const statusLabels = {
                'draft': 'Draft',
                'diajukan': 'Diajukan',
                'disetujui_wali': 'Disetujui Wali Kelas',
                'disetujui_bp': 'Disetujui BP',
                'disetujui_kaprog': 'Disetujui Kaprog',
                'disetujui_tu': 'Disetujui TU',
                'disetujui_hubin': 'Disetujui Hubin',
                'ditolak_wali': 'Ditolak Wali Kelas',
                'ditolak_bp': 'Ditolak BP',
                'ditolak_kaprog': 'Ditolak Kaprog',
                'ditolak_tu': 'Ditolak TU',
                'ditolak_hubin': 'Ditolak Hubin'
            };
            statusLabel = statusLabels[statusFilter] || statusFilter;
        }
        
        // Get table content
        const table = document.querySelector('.table-responsive .table');
        if (!table) {
            alert('Tidak ada data untuk dicetak');
            return;
        }
        
        // Clone table to avoid modifying original
        const tableClone = table.cloneNode(true);
        
        // Create print content
        const printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Data Siswa - {{ auth()->user()->name }}</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                        color: #000;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 30px;
                        border-bottom: 2px solid #333;
                        padding-bottom: 15px;
                    }
                    .header h1 {
                        margin: 0;
                        font-size: 24px;
                        color: #333;
                    }
                    .header h2 {
                        margin: 5px 0 0 0;
                        font-size: 16px;
                        color: #666;
                        font-weight: normal;
                    }
                    .filter-info {
                        background: #f8f9fa;
                        padding: 15px;
                        margin-bottom: 20px;
                        border-left: 4px solid #007bff;
                    }
                    .filter-info h3 {
                        margin: 0 0 10px 0;
                        font-size: 16px;
                        color: #333;
                    }
                    .filter-info p {
                        margin: 5px 0;
                        font-size: 14px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 8px;
                        text-align: left;
                    }
                    th {
                        background-color: #f2f2f2;
                        font-weight: bold;
                        color: #333;
                    }
                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                    .badge {
                        display: inline-block;
                        padding: 4px 8px;
                        font-size: 12px;
                        border-radius: 4px;
                        color: #fff;
                    }
                    .bg-success {
                        background-color: #28a745 !important;
                    }
                    .bg-danger {
                        background-color: #dc3545 !important;
                    }
                    .bg-secondary {
                        background-color: #6c757d !important;
                    }
                    .text-muted {
                        color: #666 !important;
                    }
                    .footer {
                        margin-top: 40px;
                        text-align: right;
                        font-size: 12px;
                        color: #666;
                    }
                    @media print {
                        body {
                            margin: 0;
                        }
                        .no-print {
                            display: none !important;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Data Siswa Berdasarkan Jurusan & Kelas</h1>
                    <h2>Kaprog: {{ auth()->user()->name }}</h2>
                </div>
                
                <div class="filter-info">
                    <h3>Filter yang Diterapkan:</h3>
                    <p><strong>Jurusan:</strong> ${jurusanFilter}</p>
                    <p><strong>Kelas:</strong> ${kelasFilter}</p>
                    <p><strong>Status Permohonan:</strong> ${statusLabel}</p>
                    <p><strong>Tanggal Cetak:</strong> ${new Date().toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}</p>
                </div>
                
                ${tableClone.outerHTML}
                
                <div class="footer">
                    <p>Dicetak oleh: {{ auth()->user()->name }} | Sistem Informasi PKL</p>
                </div>
            </body>
            </html>
        `;
        
        // Create new window for printing
        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();
        
        // Wait for content to load then print
        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
            // Close after a short delay to allow printing dialog
            setTimeout(() => {
                printWindow.close();
            }, 1000);
        };
    }

    function printWaliKelasData() {
        // Get filter information for wali kelas
        const forms = document.querySelectorAll('form[action="{{ route("permohonan.index") }}"]');
        const waliForm = forms[forms.length - 1]; // Get the last form (wali kelas form)
        const formData = new FormData(waliForm);
        const kelasFilter = formData.get('kelas_wali_filter') || 'Semua Kelas';
        const statusFilter = formData.get('status_pkl_filter');
        
        // Get status label
        let statusLabel = 'Semua Status';
        if (statusFilter) {
            const statusLabels = {
                'draft': 'Draft',
                'diajukan': 'Diajukan',
                'disetujui_wali': 'Disetujui Wali Kelas',
                'disetujui_bp': 'Disetujui BP',
                'disetujui_kaprog': 'Disetujui Kaprog',
                'disetujui_tu': 'Disetujui TU',
                'disetujui_hubin': 'Disetujui Hubin',
                'ditolak_wali': 'Ditolak Wali Kelas',
                'ditolak_bp': 'Ditolak BP',
                'ditolak_kaprog': 'Ditolak Kaprog',
                'ditolak_tu': 'Ditolak TU',
                'ditolak_hubin': 'Ditolak Hubin'
            };
            statusLabel = statusLabels[statusFilter] || statusFilter;
        }
        
        // Get wali kelas table content
        const table = document.querySelector('#waliKelasTable');
        if (!table) {
            alert('Tidak ada data untuk dicetak');
            return;
        }
        
        // Clone table to avoid modifying original
        const tableClone = table.cloneNode(true);
        
        // Remove action column for printing
        const actionHeaders = tableClone.querySelectorAll('th:last-child');
        actionHeaders.forEach(header => {
            if (header.textContent.includes('Aksi')) {
                header.remove();
            }
        });
        
        const actionCells = tableClone.querySelectorAll('td:last-child');
        actionCells.forEach(cell => {
            if (cell.querySelector('.btn-group') || cell.textContent.includes('-')) {
                cell.remove();
            }
        });
        
        // Create print content for wali kelas
        const printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Data Siswa PKL - {{ auth()->user()->name }}</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                        color: #000;
                    }
                    .header {
                        text-align: center;
                        margin-bottom: 30px;
                        border-bottom: 2px solid #333;
                        padding-bottom: 15px;
                    }
                    .header h1 {
                        margin: 0;
                        font-size: 24px;
                        color: #333;
                    }
                    .header h2 {
                        margin: 5px 0 0 0;
                        font-size: 16px;
                        color: #666;
                        font-weight: normal;
                    }
                    .filter-info {
                        background: #f8f9fa;
                        padding: 15px;
                        margin-bottom: 20px;
                        border-left: 4px solid #28a745;
                    }
                    .filter-info h3 {
                        margin: 0 0 10px 0;
                        font-size: 16px;
                        color: #333;
                    }
                    .filter-info p {
                        margin: 5px 0;
                        font-size: 14px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                        font-size: 12px;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        padding: 6px;
                        text-align: left;
                    }
                    th {
                        background-color: #f2f2f2;
                        font-weight: bold;
                        color: #333;
                        font-size: 11px;
                    }
                    tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                    .badge {
                        display: inline-block;
                        padding: 2px 6px;
                        font-size: 10px;
                        border-radius: 3px;
                        color: #fff;
                    }
                    .bg-success {
                        background-color: #28a745 !important;
                    }
                    .bg-danger {
                        background-color: #dc3545 !important;
                    }
                    .bg-secondary {
                        background-color: #6c757d !important;
                    }
                    .bg-primary {
                        background-color: #007bff !important;
                    }
                    .bg-warning {
                        background-color: #ffc107 !important;
                        color: #000 !important;
                    }
                    .bg-info {
                        background-color: #17a2b8 !important;
                    }
                    .text-muted {
                        color: #666 !important;
                    }
                    .fw-bold {
                        font-weight: bold;
                    }
                    .footer {
                        margin-top: 40px;
                        text-align: right;
                        font-size: 12px;
                        color: #666;
                    }
                    @media print {
                        body {
                            margin: 0;
                        }
                        .no-print {
                            display: none !important;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>Data Siswa Permohonan PKL</h1>
                    <h2>Wali Kelas: {{ auth()->user()->name }}</h2>
                </div>
                
                <div class="filter-info">
                    <h3>Filter yang Diterapkan:</h3>
                    <p><strong>Kelas:</strong> ${kelasFilter}</p>
                    <p><strong>Status Permohonan PKL:</strong> ${statusLabel}</p>
                    <p><strong>Tanggal Cetak:</strong> ${new Date().toLocaleDateString('id-ID', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })}</p>
                </div>
                
                ${tableClone.outerHTML}
                
                <div class="footer">
                    <p>Dicetak oleh: {{ auth()->user()->name }} | Sistem Informasi PKL</p>
                </div>
            </body>
            </html>
        `;
        
        // Create new window for printing
        const printWindow = window.open('', '_blank');
        printWindow.document.write(printContent);
        printWindow.document.close();
        
        // Wait for content to load then print
        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
            // Close after a short delay to allow printing dialog
            setTimeout(() => {
                printWindow.close();
            }, 1000);
        };
    }
</script>
@endif
@endpush

@endsection
