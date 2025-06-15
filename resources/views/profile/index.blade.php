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
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" 
                            data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="fas fa-key me-2"></i>
                        Ubah Password
                    </button>
                    
                    @if(auth()->user()->role === 'siswa')
                    <a href="{{ route('permohonan.create') }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-plus me-2"></i>
                        Buat Permohonan PKL
                    </a>
                    <a href="{{ route('permohonan.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-list me-2"></i>
                        Lihat Permohonan Saya
                    </a>
                    @endif
                    
                    <a href="{{ route('notifikasi.index') }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-bell me-2"></i>
                        Notifikasi
                        @if($unreadNotifications > 0)
                        <span class="badge bg-danger">{{ $unreadNotifications }}</span>
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
                            <div class="fw-bold">{{ auth()->user()->name }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small">Email</label>
                            <div class="fw-bold">{{ auth()->user()->email }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small">Role</label>
                            <div>
                                <span class="badge bg-{{ auth()->user()->getRoleColor() }}">{{ auth()->user()->getRoleLabel() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Status</label>
                            <div>
                                <span class="badge bg-{{ auth()->user()->is_active ? 'success' : 'danger' }}">
                                    {{ auth()->user()->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small">Tanggal Bergabung</label>
                            <div class="fw-bold">{{ auth()->user()->created_at->format('d M Y H:i') }}</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted small">Terakhir Diperbarui</label>
                            <div class="fw-bold">{{ auth()->user()->updated_at->format('d M Y H:i') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Student Specific Information -->
        @if(auth()->user()->role === 'siswa')
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
                            <div class="fw-bold">{{ auth()->user()->nis ?? '-' }}</div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Kelas</label>
                            <div class="fw-bold">{{ auth()->user()->kelas ?? '-' }}</div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Jurusan</label>
                            <div class="fw-bold">{{ auth()->user()->jurusan ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Student Statistics -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Statistik Permohonan PKL
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-3">
                        <div class="border-end">
                            <h4 class="text-primary mb-0">{{ $permohonanStats['total'] ?? 0 }}</h4>
                            <small class="text-muted">Total</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="border-end">
                            <h4 class="text-warning mb-0">{{ $permohonanStats['pending'] ?? 0 }}</h4>
                            <small class="text-muted">Proses</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="border-end">
                            <h4 class="text-success mb-0">{{ $permohonanStats['approved'] ?? 0 }}</h4>
                            <small class="text-muted">Disetujui</small>
                        </div>
                    </div>
                    <div class="col-3">
                        <h4 class="text-danger mb-0">{{ $permohonanStats['rejected'] ?? 0 }}</h4>
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

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Profil
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST" id="editProfileForm">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" 
                               value="{{ auth()->user()->name }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="edit_email" name="email" 
                               value="{{ auth()->user()->email }}" required>
                    </div>
                    
                    @if(auth()->user()->role === 'siswa')
                    <hr>
                    <h6 class="mb-3">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Informasi Siswa
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_nis" class="form-label">NIS</label>
                                <input type="text" class="form-control" id="edit_nis" name="nis" 
                                       value="{{ auth()->user()->nis }}">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_kelas" class="form-label">Kelas</label>
                                <select class="form-select" id="edit_kelas" name="kelas">
                                    <option value="">Pilih Kelas</option>
                                    <option value="X" {{ auth()->user()->kelas === 'X' ? 'selected' : '' }}>X</option>
                                    <option value="XI" {{ auth()->user()->kelas === 'XI' ? 'selected' : '' }}>XI</option>
                                    <option value="XII" {{ auth()->user()->kelas === 'XII' ? 'selected' : '' }}>XII</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="edit_jurusan" class="form-label">Jurusan</label>
                                <select class="form-select" id="edit_jurusan" name="jurusan">
                                    <option value="">Pilih Jurusan</option>
                                    <option value="RPL" {{ auth()->user()->jurusan === 'RPL' ? 'selected' : '' }}>RPL</option>
                                    <option value="TKJ" {{ auth()->user()->jurusan === 'TKJ' ? 'selected' : '' }}>TKJ</option>
                                    <option value="MM" {{ auth()->user()->jurusan === 'MM' ? 'selected' : '' }}>MM</option>
                                    <option value="TBSM" {{ auth()->user()->jurusan === 'TBSM' ? 'selected' : '' }}>TBSM</option>
                                    <option value="TKR" {{ auth()->user()->jurusan === 'TKR' ? 'selected' : '' }}>TKR</option>
                                    <option value="AKL" {{ auth()->user()->jurusan === 'AKL' ? 'selected' : '' }}>AKL</option>
                                    <option value="OTKP" {{ auth()->user()->jurusan === 'OTKP' ? 'selected' : '' }}>OTKP</option>
                                    <option value="BDP" {{ auth()->user()->jurusan === 'BDP' ? 'selected' : '' }}>BDP</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-key me-2"></i>
                    Ubah Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('profile.change-password') }}" method="POST" id="changePasswordForm">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="current_password" 
                               name="current_password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="new_password" 
                               name="new_password" required>
                        <div class="form-text">Minimal 8 karakter</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="new_password_confirmation" 
                               name="new_password_confirmation" required>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-key me-2"></i>
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
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

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // NIS validation (numbers only)
    $('#edit_nis').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Password confirmation validation
    $('#new_password_confirmation').on('input', function() {
        const newPassword = $('#new_password').val();
        const confirmation = $(this).val();
        
        if (confirmation && newPassword !== confirmation) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Password tidak sama</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
    
    // Form validation
    $('#changePasswordForm').submit(function(e) {
        const newPassword = $('#new_password').val();
        const confirmation = $('#new_password_confirmation').val();
        
        if (newPassword !== confirmation) {
            e.preventDefault();
            alert('Password baru dan konfirmasi password tidak sama!');
            return false;
        }
        
        if (newPassword.length < 8) {
            e.preventDefault();
            alert('Password baru minimal 8 karakter!');
            return false;
        }
    });
});
</script>
@endpush
@endsection