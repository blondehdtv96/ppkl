@extends('layouts.app')

@section('title', 'Profil Saya')

@section('page-title', 'Profil Saya')

@section('page-actions')
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
        <i class="fas fa-edit me-2"></i>
        Edit Profil
    </button>
@endsection

@section('content')
<div class="row">
    <!-- Profile Information -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar-circle-large mx-auto mb-3">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                
                <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
                
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-{{ auth()->user()->getRoleColor() }} fs-6">
                        {{ auth()->user()->getRoleLabel() }}
                    </span>
                    <span class="badge bg-{{ auth()->user()->is_active ? 'success' : 'danger' }} fs-6">
                        {{ auth()->user()->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
                
                <div class="text-muted small">
                    <i class="fas fa-calendar-alt me-1"></i>
                    Bergabung {{ auth()->user()->created_at->format('d M Y') }}
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Aksi Cepat
                </h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('users.profile') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-user me-2"></i>
                        Profil Saya
                    </a>
                    
                    @if(auth()->user()->role === 'siswa')
                    <a href="{{ route('permohonan.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-file-alt me-2"></i>
                        Permohonan PKL
                    </a>
                    @endif
                    
                    <a href="{{ route('notifikasi.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-bell me-2"></i>
                        Notifikasi
                        @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
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
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Email</label>
                            <div class="fw-bold">{{ $user->email }}</div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Role</label>
                            <div>
                                <span class="badge bg-{{ $user->getRoleColor() }}">
                                    {{ $user->getRoleLabel() }}
                                </span>
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
                    </div>
                </div>
                
                @if($user->role === 'siswa')
                <hr>
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
                @endif
                
                @if($user->role === 'wali_kelas')
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Kelas yang Diampu</label>
                            <div class="fw-bold">
                                @if($user->custom_kelas_diampu)
                                    <span class="badge bg-primary me-1 mb-1">{{ $user->custom_kelas_diampu }}</span>
                                @else
                                    <span class="text-muted">Belum ada kelas yang diampu</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($user->role === 'kaprog')
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Jurusan yang Diampu</label>
                            <div class="fw-bold">
                                @if($user->jurusan_diampu && count($user->jurusan_diampu) > 0)
                                    @foreach($user->jurusan_diampu as $jurusan)
                                        <span class="badge bg-success me-1 mb-1">{{ $jurusan }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Belum ada jurusan yang diampu</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Student Statistics -->
        @if($user->role === 'siswa')
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Statistik PKL
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="stat-circle bg-primary mx-auto mb-2">
                            {{ $user->permohonanPkl->count() }}
                        </div>
                        <small class="text-muted">Total Permohonan</small>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stat-circle bg-success mx-auto mb-2">
                            {{ $user->permohonanPkl->where('status', 'disetujui')->count() }}
                        </div>
                        <small class="text-muted">Disetujui</small>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stat-circle bg-warning mx-auto mb-2">
                            {{ $user->permohonanPkl->whereIn('status', ['menunggu', 'diproses'])->count() }}
                        </div>
                        <small class="text-muted">Dalam Proses</small>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="stat-circle bg-danger mx-auto mb-2">
                            {{ $user->permohonanPkl->where('status', 'ditolak')->count() }}
                        </div>
                        <small class="text-muted">Ditolak</small>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Recent Activity -->
        @if(isset($recentActivity) && $recentActivity->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Aktivitas Terbaru
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($recentActivity as $activity)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">{{ $activity->description }}</h6>
                            <p class="timeline-text text-muted small">
                                {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit me-2"></i>
                    Edit Profil
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('users.profile.update') }}" method="POST" id="editProfileForm">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" 
                               value="{{ $user->name }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit_email" name="email" 
                               value="{{ $user->email }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="edit_password" name="password" 
                               placeholder="Biarkan kosong jika tidak ingin mengubah password">
                        <div class="form-text">Minimal 8 karakter</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="edit_password_confirmation" 
                               name="password_confirmation">
                    </div>
                    
                    @if($user->role === 'siswa')
                    <hr>
                    <h6 class="mb-3">Informasi Siswa</h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_nis" class="form-label">NIS</label>
                                <input type="text" class="form-control" id="edit_nis" name="nis" 
                                       value="{{ $user->nis }}">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_kelas" class="form-label">Kelas</label>
                                <select class="form-select" id="edit_kelas" name="kelas">
                                    <option value="">Pilih Kelas</option>
                                    <option value="X" {{ $user->kelas === 'X' ? 'selected' : '' }}>X</option>
                                    <option value="XI" {{ $user->kelas === 'XI' ? 'selected' : '' }}>XI</option>
                                    <option value="XII" {{ $user->kelas === 'XII' ? 'selected' : '' }}>XII</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_jurusan" class="form-label">Jurusan</label>
                                <select class="form-select" id="edit_jurusan" name="jurusan">
                                    <option value="">Pilih Jurusan</option>
                                    <option value="RPL" {{ $user->jurusan === 'RPL' ? 'selected' : '' }}>RPL</option>
                                    <option value="TKJ" {{ $user->jurusan === 'TKJ' ? 'selected' : '' }}>TKJ</option>
                                    <option value="MM" {{ $user->jurusan === 'MM' ? 'selected' : '' }}>MM</option>
                                    <option value="TBSM" {{ $user->jurusan === 'TBSM' ? 'selected' : '' }}>TBSM</option>
                                    <option value="TKR" {{ $user->jurusan === 'TKR' ? 'selected' : '' }}>TKR</option>
                                    <option value="AKL" {{ $user->jurusan === 'AKL' ? 'selected' : '' }}>AKL</option>
                                    <option value="OTKP" {{ $user->jurusan === 'OTKP' ? 'selected' : '' }}>OTKP</option>
                                    <option value="BDP" {{ $user->jurusan === 'BDP' ? 'selected' : '' }}>BDP</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- kelas_diampu form section removed - using custom_kelas_diampu only --}}
                    
                    @if($user->role === 'kaprog')
                    <hr>
                    <h6 class="mb-3">Jurusan yang Diampu</h6>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <div class="form-text mb-2">Pilih jurusan yang diampu oleh kepala program ini</div>
                                <div class="d-flex flex-wrap gap-3">
                                    @php
                                        $jurusanOptions = ['RPL', 'TKJ', 'MM', 'TBSM', 'TKR', 'AKL', 'OTKP', 'BDP'];
                                        $jurusanArray = $user->jurusan_diampu ?? [];
                                    @endphp
                                    
                                    @foreach($jurusanOptions as $jurusan)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="jurusan_diampu[]" value="{{ $jurusan }}" id="jurusan_{{ $jurusan }}"
                                                   {{ in_array($jurusan, $jurusanArray) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="jurusan_{{ $jurusan }}">{{ $jurusan }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('jurusan_diampu')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
.avatar-circle-large {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 24px;
}

.stat-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 25px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 15px;
    height: 15px;
    border-radius: 50%;
}

.timeline-content {
    position: relative;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 16px;
}

.timeline:before {
    content: '';
    position: absolute;
    left: -23px;
    top: 0;
    height: 100%;
    width: 2px;
    background-color: #e9ecef;
}
</style>
@endpush

@endsection