
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
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i> Data Siswa Berdasarkan Jurusan & Kelas</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('permohonan.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
                            <label class="form-label">Kelas</label>
                            <input type="text" class="form-control" name="kelas" value="{{ request('kelas') }}" placeholder="Contoh: XI TKJ A">
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-2"></i> Cari</button>
                        </div>
                    </form>
                    @php
                        $kaprog = auth()->user();
                        $query = \App\Models\User::where('role', 'siswa');
                        if ($kaprog->jurusan_diampu && is_array($kaprog->jurusan_diampu)) {
                            $query->whereIn('jurusan', $kaprog->jurusan_diampu);
                            if (request('kelas')) {
                                $query->where('kelas', 'like', "%" . request('kelas') . "%");
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
                            <span class="badge status-badge {{ $item->getStatusColor() }}">
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
                            @if($item->current_processor_role)
                            <span class="badge bg-info">
                                {{ ucfirst(str_replace('_', ' ', $item->current_processor_role)) }}
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
@if(auth()->user()->role !== 'siswa' && $permohonan->count() > 0)
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h4 class="text-primary">{{ $stats['menunggu'] ?? 0 }}</h4>
                <small class="text-muted">Menunggu Proses</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h4 class="text-success">{{ $stats['disetujui'] ?? 0 }}</h4>
                <small class="text-muted">Disetujui</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h4 class="text-danger">{{ $stats['ditolak'] ?? 0 }}</h4>
                <small class="text-muted">Ditolak</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h4 class="text-info">{{ $stats['selesai'] ?? 0 }}</h4>
                <small class="text-muted">Selesai</small>
            </div>
        </div>
    </div>
</div>
@endif
@endsection