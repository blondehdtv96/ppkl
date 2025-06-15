@extends('layouts.app')

@section('title', 'Edit Pengguna - ' . $user->name)

@section('page-title')
    Edit Pengguna
    <span class="badge bg-{{ $user->getRoleColor() }} ms-2">{{ $user->getRoleLabel() }}</span>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        <a href="{{ route('users.show', $user) }}" class="btn btn-outline-info">
            <i class="fas fa-eye me-2"></i>
            Lihat Detail
        </a>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-edit me-2"></i>
                    Form Edit Pengguna
                </h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('users.update', $user) }}" method="POST" id="editUserForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Password Section -->
                    <div class="card bg-light mb-3">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="fas fa-lock me-2"></i>
                                Ubah Password
                                <small class="text-muted">(Kosongkan jika tidak ingin mengubah password)</small>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password Baru</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" name="password">
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Minimal 8 karakter</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" 
                                               id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Role Selection -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="siswa" {{ old('role', $user->role) === 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="wali_kelas" {{ old('role', $user->role) === 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                            <option value="bp" {{ old('role', $user->role) === 'bp' ? 'selected' : '' }}>BP (Bimbingan dan Penyuluhan)</option>
                            <option value="kaprog" {{ old('role', $user->role) === 'kaprog' ? 'selected' : '' }}>Kaprog (Kepala Program)</option>
                            <option value="tu" {{ old('role', $user->role) === 'tu' ? 'selected' : '' }}>TU (Tata Usaha)</option>
                            <option value="hubin" {{ old('role', $user->role) === 'hubin' ? 'selected' : '' }}>Hubin (Hubungan Industri)</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($user->role !== old('role', $user->role))
                            <div class="alert alert-warning mt-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Peringatan:</strong> Mengubah role akan mempengaruhi hak akses pengguna dalam sistem.
                            </div>
                        @endif
                    </div>
                    
                    <!-- Student Specific Fields -->
                    <div id="studentFields" class="{{ $user->role === 'siswa' ? '' : 'd-none' }}">
                        <div class="card bg-light">
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
                                            <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('nis') is-invalid @enderror" 
                                                   id="nis" name="nis" value="{{ old('nis', $user->nis) }}">
                                            @error('nis')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                                            <select class="form-select @error('kelas') is-invalid @enderror" 
                                                    id="kelas" name="kelas">
                                                <option value="">Pilih Kelas</option>
                                                <option value="XI TKJ A" {{ old('kelas', $user->kelas) === 'XI TKJ A' ? 'selected' : '' }}>XI TKJ A</option>
                                                <option value="XI TKJ B" {{ old('kelas', $user->kelas) === 'XI TKJ B' ? 'selected' : '' }}>XI TKJ B</option>
                                                <option value="XI TBSM A" {{ old('kelas', $user->kelas) === 'XI TBSM A' ? 'selected' : '' }}>XI TBSM A</option>
                                                <option value="XI TBSM B" {{ old('kelas', $user->kelas) === 'XI TBSM B' ? 'selected' : '' }}>XI TBSM B</option>
                                                <option value="XI TKR A" {{ old('kelas', $user->kelas) === 'XI TKR A' ? 'selected' : '' }}>XI TKR A</option>
                                                <option value="XI TKR B" {{ old('kelas', $user->kelas) === 'XI TKR B' ? 'selected' : '' }}>XI TKR B</option>
                                                <option value="custom" {{ old('kelas', $user->kelas) === 'custom' ? 'selected' : '' }}>Kelas Lainnya</option>
                                            </select>
                                            @error('kelas')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div id="customKelasField" class="col-md-4 {{ old('kelas', $user->kelas) === 'custom' ? '' : 'd-none' }}">
                                        <div class="mb-3">
                                            <label for="custom_kelas" class="form-label">Kelas Kustom <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('custom_kelas') is-invalid @enderror" 
                                                   id="custom_kelas" name="custom_kelas" value="{{ old('custom_kelas') }}" placeholder="Contoh: XI TKJ C">
                                            @error('custom_kelas')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="jurusan" class="form-label">Jurusan <span class="text-danger">*</span></label>
                                            <select class="form-select @error('jurusan') is-invalid @enderror" 
                                                    id="jurusan" name="jurusan">
                                                <option value="">Pilih Jurusan</option>
                                                <option value="TKJ" {{ old('jurusan', $user->jurusan) === 'TKJ' ? 'selected' : '' }}>TKJ (Teknik Komputer dan Jaringan)</option>
                                                <option value="TBSM" {{ old('jurusan', $user->jurusan) === 'TBSM' ? 'selected' : '' }}>TBSM (Teknik Bisnis Sepeda Motor)</option>
                                                <option value="TKR" {{ old('jurusan', $user->jurusan) === 'TKR' ? 'selected' : '' }}>TKR (Teknik Kendaraan Ringan)</option>
                                            </select>
                                            @error('jurusan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Wali Kelas Specific Fields -->
                    <div id="waliKelasFields" class="{{ $user->role === 'wali_kelas' ? '' : 'd-none' }}">
                        <div class="card bg-light mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>
                                    Kelas yang Diampu
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Kelas yang Diampu <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input kelas-diampu" type="checkbox" name="kelas_diampu[]" value="XI" id="kelasXI"
                                                    {{ in_array('XI', old('kelas_diampu', json_decode($user->kelas_diampu ?? '[]', true))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="kelasXI">Kelas XI</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div id="customKelasDiampuField" class="mt-3 {{ in_array('XI', old('kelas_diampu', json_decode($user->kelas_diampu ?? '[]', true))) ? '' : 'd-none' }}">
                                        <label class="form-label">Spesifikasi Kelas XI yang Diampu</label>
                                        <input type="text" class="form-control" id="custom_kelas_diampu" name="custom_kelas_diampu" 
                                               value="{{ old('custom_kelas_diampu', $user->custom_kelas_diampu ?? '') }}" 
                                               placeholder="Contoh: XI TKJ A, XI TBSM B">
                                        <div class="form-text">Masukkan spesifikasi kelas yang diampu, pisahkan dengan koma jika lebih dari satu</div>
                                    </div>
                                    
                                    @error('kelas_diampu')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Wali kelas hanya akan melihat permohonan PKL dari siswa di kelas yang dipilih</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kaprog Specific Fields -->
                    <div id="kaprogFields" class="{{ $user->role === 'kaprog' ? '' : 'd-none' }}">
                        <div class="card bg-light mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-sitemap me-2"></i>
                                    Jurusan yang Diampu
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Pilih Jurusan yang Diampu <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input jurusan-diampu" type="checkbox" name="jurusan_diampu[]" value="RPL" id="jurusanRPL"
                                                    {{ in_array('RPL', old('jurusan_diampu', json_decode($user->jurusan_diampu ?? '[]', true))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="jurusanRPL">RPL (Rekayasa Perangkat Lunak)</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input jurusan-diampu" type="checkbox" name="jurusan_diampu[]" value="TKJ" id="jurusanTKJ"
                                                    {{ in_array('TKJ', old('jurusan_diampu', json_decode($user->jurusan_diampu ?? '[]', true))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="jurusanTKJ">TKJ (Teknik Komputer dan Jaringan)</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input jurusan-diampu" type="checkbox" name="jurusan_diampu[]" value="MM" id="jurusanMM"
                                                    {{ in_array('MM', old('jurusan_diampu', json_decode($user->jurusan_diampu ?? '[]', true))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="jurusanMM">MM (Multimedia)</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input jurusan-diampu" type="checkbox" name="jurusan_diampu[]" value="TBSM" id="jurusanTBSM"
                                                    {{ in_array('TBSM', old('jurusan_diampu', json_decode($user->jurusan_diampu ?? '[]', true))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="jurusanTBSM">TBSM (Teknik Bisnis Sepeda Motor)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input jurusan-diampu" type="checkbox" name="jurusan_diampu[]" value="TKR" id="jurusanTKR"
                                                    {{ in_array('TKR', old('jurusan_diampu', json_decode($user->jurusan_diampu ?? '[]', true))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="jurusanTKR">TKR (Teknik Kendaraan Ringan)</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input jurusan-diampu" type="checkbox" name="jurusan_diampu[]" value="AKL" id="jurusanAKL"
                                                    {{ in_array('AKL', old('jurusan_diampu', json_decode($user->jurusan_diampu ?? '[]', true))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="jurusanAKL">AKL (Akuntansi dan Keuangan Lembaga)</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input jurusan-diampu" type="checkbox" name="jurusan_diampu[]" value="OTKP" id="jurusanOTKP"
                                                    {{ in_array('OTKP', old('jurusan_diampu', json_decode($user->jurusan_diampu ?? '[]', true))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="jurusanOTKP">OTKP (Otomatisasi dan Tata Kelola Perkantoran)</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input jurusan-diampu" type="checkbox" name="jurusan_diampu[]" value="BDP" id="jurusanBDP"
                                                    {{ in_array('BDP', old('jurusan_diampu', json_decode($user->jurusan_diampu ?? '[]', true))) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="jurusanBDP">BDP (Bisnis Daring dan Pemasaran)</label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('jurusan_diampu')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Kaprog hanya akan melihat permohonan PKL dari siswa di jurusan yang dipilih</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" 
                                   name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Pengguna Aktif
                            </label>
                        </div>
                        <div class="form-text">Pengguna yang tidak aktif tidak dapat login ke sistem</div>
                    </div>
                    
                    <!-- Last Updated Info -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Informasi:</strong> Pengguna ini terakhir diperbarui pada {{ $user->updated_at->format('d M Y H:i') }}
                        ({{ $user->updated_at->diffForHumans() }})
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-warning" onclick="resetForm()">
                            <i class="fas fa-undo me-2"></i>
                            Reset ke Data Asli
                        </button>
                        
                        <div>
                            <a href="{{ route('users.show', $user) }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-2"></i>
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                <span id="submitText">Perbarui Pengguna</span>
                                <span id="submitSpinner" class="d-none">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Memperbarui...
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const originalData = {
        name: '{{ $user->name }}',
        email: '{{ $user->email }}',
        role: '{{ $user->role }}',
        nis: '{{ $user->nis }}',
        kelas: '{{ $user->kelas }}',
        jurusan: '{{ $user->jurusan }}',
        is_active: {{ $user->is_active ? 'true' : 'false' }},
        kelas_diampu: {!! json_encode(is_array($user->kelas_diampu) ? $user->kelas_diampu : json_decode($user->kelas_diampu ?? '[]', true)) !!},
        jurusan_diampu: {!! json_encode(is_array($user->jurusan_diampu) ? $user->jurusan_diampu : json_decode($user->jurusan_diampu ?? '[]', true)) !!}
    };
    
    // Toggle password visibility
    $('#togglePassword').click(function() {
        const passwordField = $('#password');
        const passwordIcon = $('#togglePasswordIcon');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            passwordIcon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            passwordIcon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    // Handle role change
    $('#role').change(function() {
        const selectedRole = $(this).val();
        const studentFields = $('#studentFields');
        const waliKelasFields = $('#waliKelasFields');
        const kaprogFields = $('#kaprogFields');
        const nisField = $('#nis');
        const kelasField = $('#kelas');
        const jurusanField = $('#jurusan');
        
        // Hide all role-specific fields first
        studentFields.addClass('d-none');
        waliKelasFields.addClass('d-none');
        kaprogFields.addClass('d-none');
        
        // Remove required attributes
        nisField.removeAttr('required');
        kelasField.removeAttr('required');
        jurusanField.removeAttr('required');
        $('#custom_kelas').removeAttr('required');
        $('.kelas-diampu').removeAttr('required');
        $('.jurusan-diampu').removeAttr('required');
        
        // Show fields based on selected role
        if (selectedRole === 'siswa') {
            studentFields.removeClass('d-none');
            nisField.attr('required', true);
            kelasField.attr('required', true);
            jurusanField.attr('required', true);
            
            // Check if custom kelas is selected
            if (kelasField.val() === 'custom') {
                $('#customKelasField').removeClass('d-none');
                $('#custom_kelas').attr('required', true);
            } else {
                $('#customKelasField').addClass('d-none');
                $('#custom_kelas').removeAttr('required');
            }
            
            // Clear other fields if role has changed
            if (selectedRole !== originalData.role) {
                $('.kelas-diampu').prop('checked', false);
                $('.jurusan-diampu').prop('checked', false);
            }
        } else if (selectedRole === 'wali_kelas') {
            waliKelasFields.removeClass('d-none');
            
            // Clear student fields if changing from student role
            if (selectedRole !== originalData.role) {
                if (originalData.role === 'siswa') {
                    nisField.val('');
                    kelasField.val('');
                    jurusanField.val('');
                    $('#custom_kelas').val('');
                }
                
                // Clear kaprog fields if changing from kaprog role
                if (originalData.role === 'kaprog') {
                    $('.jurusan-diampu').prop('checked', false);
                }
            }
        } else if (selectedRole === 'kaprog') {
            kaprogFields.removeClass('d-none');
            
            // Clear fields if role has changed
            if (selectedRole !== originalData.role) {
                // Clear student fields if changing from student role
                if (originalData.role === 'siswa') {
                    nisField.val('');
                    kelasField.val('');
                    jurusanField.val('');
                    $('#custom_kelas').val('');
                }
                
                // Clear wali kelas fields if changing from wali kelas role
                if (originalData.role === 'wali_kelas') {
                    $('.kelas-diampu').prop('checked', false);
                }
            }
        } else {
            // For other roles, clear all specific fields if role has changed
            if (selectedRole !== originalData.role) {
                if (originalData.role === 'siswa') {
                    nisField.val('');
                    kelasField.val('');
                    jurusanField.val('');
                    $('#custom_kelas').val('');
                } else if (originalData.role === 'wali_kelas') {
                    $('.kelas-diampu').prop('checked', false);
                } else if (originalData.role === 'kaprog') {
                    $('.jurusan-diampu').prop('checked', false);
                }
            }
        }
    });
    
    // Handle kelas change for custom kelas option
    $('#kelas').change(function() {
        const selectedKelas = $(this).val();
        const customKelasField = $('#customKelasField');
        const customKelasInput = $('#custom_kelas');
        
        if (selectedKelas === 'custom') {
            customKelasField.removeClass('d-none');
            customKelasInput.attr('required', true);
        } else {
            customKelasField.addClass('d-none');
            customKelasInput.removeAttr('required');
        }
    });
    
    // Handle kelas_diampu checkbox for custom field
    $('#kelasXI').change(function() {
        const customKelasDiampuField = $('#customKelasDiampuField');
        
        if ($(this).is(':checked')) {
            customKelasDiampuField.removeClass('d-none');
        } else {
            customKelasDiampuField.addClass('d-none');
            $('#custom_kelas_diampu').val('');
        }
    });
    
    // Form validation
    $('#editUserForm').submit(function(e) {
        const password = $('#password').val();
        const passwordConfirmation = $('#password_confirmation').val();
        
        // Only validate password if it's being changed
        if (password || passwordConfirmation) {
            if (password !== passwordConfirmation) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak sama!');
                return false;
            }
            
            if (password.length < 8) {
                e.preventDefault();
                alert('Password minimal 8 karakter!');
                return false;
            }
        }
        
        // Show loading state
        $('#submitBtn').prop('disabled', true);
        $('#submitText').addClass('d-none');
        $('#submitSpinner').removeClass('d-none');
    });
    
    // Real-time password confirmation validation
    $('#password_confirmation').on('input', function() {
        const password = $('#password').val();
        const passwordConfirmation = $(this).val();
        
        if (passwordConfirmation && password !== passwordConfirmation) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">Password tidak sama</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
    
    // NIS validation (numbers only)
    $('#nis').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
    
    // Check for unsaved changes
    let hasUnsavedChanges = false;
    
    $('#editUserForm input, #editUserForm select').on('change input', function() {
        hasUnsavedChanges = true;
    });
    
    // Warn user about unsaved changes
    $(window).on('beforeunload', function() {
        if (hasUnsavedChanges) {
            return 'Anda memiliki perubahan yang belum disimpan. Yakin ingin meninggalkan halaman ini?';
        }
    });
    
    // Remove warning when form is submitted
    $('#editUserForm').on('submit', function() {
        hasUnsavedChanges = false;
    });
    
    // Trigger role change to initialize form state
    $('#role').trigger('change');
    
    // Trigger kelas change to initialize custom kelas field
    $('#kelas').trigger('change');
});

function resetForm() {
    if (confirm('Yakin ingin mereset form ke data asli? Semua perubahan akan hilang.')) {
        // Reset to original values
        $('#name').val('{{ $user->name }}');
        $('#email').val('{{ $user->email }}');
        $('#role').val('{{ $user->role }}').trigger('change');
        $('#nis').val('{{ $user->nis }}');
        $('#kelas').val('{{ $user->kelas }}');
        $('#jurusan').val('{{ $user->jurusan }}');
        $('#is_active').prop('checked', {{ $user->is_active ? 'true' : 'false' }});
        
        // Reset kelas_diampu checkboxes
        $('.kelas-diampu').each(function() {
            const value = $(this).val();
            const originalKelasDiampu = {!! json_encode(is_array($user->kelas_diampu) ? $user->kelas_diampu : json_decode($user->kelas_diampu ?? '[]', true)) !!};
            $(this).prop('checked', originalKelasDiampu.includes(value));
        });
        
        // Reset jurusan_diampu checkboxes
        $('.jurusan-diampu').each(function() {
            const value = $(this).val();
            const originalJurusanDiampu = {!! json_encode(is_array($user->jurusan_diampu) ? $user->jurusan_diampu : json_decode($user->jurusan_diampu ?? '[]', true)) !!};
            $(this).prop('checked', originalJurusanDiampu.includes(value));
        });
        
        // Clear password fields
        $('#password').val('');
        $('#password_confirmation').val('');
        
        // Remove validation classes
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        hasUnsavedChanges = false;
    }
}
</script>
@endpush

@push('styles')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.form-label {
    font-weight: 500;
}

.text-danger {
    color: #dc3545 !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.input-group .btn {
    border-left: 0;
}

.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
}

.alert {
    border: 1px solid transparent;
    border-radius: 0.375rem;
}

.alert-info {
    color: #055160;
    background-color: #cff4fc;
    border-color: #b6effb;
}

.alert-warning {
    color: #664d03;
    background-color: #fff3cd;
    border-color: #ffecb5;
}
</style>
@endpush
@endsection