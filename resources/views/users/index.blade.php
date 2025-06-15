@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('page-title', 'Manajemen Pengguna')

@section('page-actions')
    @can('create', App\Models\User::class)
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>
        Tambah Pengguna
    </a>
    @endcan
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['total'] }}</h4>
                                <p class="mb-0">Total Pengguna</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['active'] }}</h4>
                                <p class="mb-0">Pengguna Aktif</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['inactive'] }}</h4>
                                <p class="mb-0">Pengguna Nonaktif</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user-times fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['siswa'] }}</h4>
                                <p class="mb-0">Siswa</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-graduation-cap fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('users.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Pencarian</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Nama, email, atau NIS...">
                    </div>
                    
                    <div class="col-md-2">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="siswa" {{ request('role') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="wali_kelas" {{ request('role') === 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                            <option value="bp" {{ request('role') === 'bp' ? 'selected' : '' }}>BP</option>
                            <option value="kaprog" {{ request('role') === 'kaprog' ? 'selected' : '' }}>Kaprog</option>
                            <option value="tu" {{ request('role') === 'tu' ? 'selected' : '' }}>TU</option>
                            <option value="hubin" {{ request('role') === 'hubin' ? 'selected' : '' }}>Hubin</option>
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
                    
                    <div class="col-md-2">
                        <label for="per_page" class="form-label">Per Halaman</label>
                        <select class="form-select" id="per_page" name="per_page">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>
                                Cari
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Users Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Daftar Pengguna
                    <span class="badge bg-secondary ms-2">{{ $users->total() }} pengguna</span>
                </h5>
            </div>
            
            <div class="card-body p-0">
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Pengguna</th>
                                    <th>Role</th>
                                    <th>Informasi Tambahan</th>
                                    <th>Status</th>
                                    <th>Bergabung</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <span class="badge bg-{{ $user->getRoleColor() }}">
                                            {{ $user->getRoleLabel() }}
                                        </span>
                                    </td>
                                    
                                    <td>
                                        @if($user->role === 'siswa')
                                            <div>
                                                <small class="text-muted">NIS:</small> {{ $user->nis }}<br>
                                                <small class="text-muted">Kelas:</small> {{ $user->kelas }}<br>
                                                <small class="text-muted">Jurusan:</small> {{ $user->jurusan }}
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }} me-2">
                                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                            
                                            @can('toggleStatus', $user)
                                            <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-{{ $user->is_active ? 'warning' : 'success' }}" 
                                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div>
                                            {{ $user->created_at->format('d M Y') }}<br>
                                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                    type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @can('view', $user)
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('users.show', $user) }}">
                                                        <i class="fas fa-eye me-2"></i>
                                                        Lihat Detail
                                                    </a>
                                                </li>
                                                @endcan
                                                
                                                @can('update', $user)
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('users.edit', $user) }}">
                                                        <i class="fas fa-edit me-2"></i>
                                                        Edit
                                                    </a>
                                                </li>
                                                @endcan
                                                
                                                @if($user->role === 'siswa')
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('permohonan.index', ['siswa' => $user->id]) }}">
                                                        <i class="fas fa-file-alt me-2"></i>
                                                        Lihat Permohonan
                                                    </a>
                                                </li>
                                                @endif
                                                
                                                @can('delete', $user)
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST" 
                                                          onsubmit="return confirm('Yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-trash me-2"></i>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($users->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} 
                                dari {{ $users->total() }} pengguna
                            </div>
                            {{ $users->links() }}
                        </div>
                    </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">Tidak ada pengguna ditemukan</h5>
                        <p class="text-muted">Coba ubah filter pencarian atau tambah pengguna baru.</p>
                        @can('create', App\Models\User::class)
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Tambah Pengguna Pertama
                        </a>
                        @endcan
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