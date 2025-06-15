@extends('layouts.app')

@section('title', 'Daftar Siswa')

@section('page-title', 'Daftar Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['total'] }}</h4>
                                <p class="mb-0">Total Siswa</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['active'] }}</h4>
                                <p class="mb-0">Siswa Aktif</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['inactive'] }}</h4>
                                <p class="mb-0">Siswa Nonaktif</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-times fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('siswa.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Nama, email, atau NIS...">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input type="text" class="form-control" id="kelas" name="kelas" 
                               value="{{ request('kelas') }}" placeholder="Contoh: XI TKJ A">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="jurusan" class="form-label">Jurusan</label>
                        <select class="form-select" id="jurusan" name="jurusan">
                            <option value="">Semua Jurusan</option>
                            <option value="TKJ" {{ request('jurusan') === 'TKJ' ? 'selected' : '' }}>TKJ</option>
                            <option value="TBSM" {{ request('jurusan') === 'TBSM' ? 'selected' : '' }}>TBSM</option>
                            <option value="TKR" {{ request('jurusan') === 'TKR' ? 'selected' : '' }}>TKR</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>
                                Cari
                            </button>
                            <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Siswa Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Daftar Siswa Kelas yang Diampu
                    <span class="badge bg-secondary ms-2">{{ $siswa->total() }} siswa</span>
                </h5>
            </div>
            
            <div class="card-body p-0">
                @if($siswa->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jurusan</th>
                                    <th>NIS</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswa as $s)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ strtoupper(substr($s->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $s->name }}</h6>
                                                <small class="text-muted">{{ $s->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>{{ $s->kelas }}</td>
                                    <td>{{ $s->jurusan }}</td>
                                    <td>{{ $s->nis }}</td>
                                    
                                    <td>
                                        <span class="badge bg-{{ $s->is_active ? 'success' : 'danger' }}">
                                            {{ $s->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('siswa.show', $s) }}">
                                                        <i class="fas fa-eye me-2"></i>
                                                        Lihat Detail
                                                    </a>
                                                </li>
                                                
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('permohonan.index', ['siswa' => $s->id]) }}">
                                                        <i class="fas fa-file-alt me-2"></i>
                                                        Lihat Permohonan
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($siswa->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Menampilkan {{ $siswa->firstItem() }} - {{ $siswa->lastItem() }} 
                                dari {{ $siswa->total() }} siswa
                            </div>
                            {{ $siswa->links() }}
                        </div>
                    </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-graduation-cap text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">Tidak ada siswa ditemukan</h5>
                        <p class="text-muted">Coba ubah filter pencarian atau periksa kelas yang diampu.</p>
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

.badge {
    font-size: 0.75em;
}
</style>
@endpush
@endsection