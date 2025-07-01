@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('page-title', 'Tambah Pengguna')

@section('page-actions')
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>
        Kembali
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    Form Tambah Pengguna
                </h5>
            </div>
            
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" id="createUserForm">
                    @csrf
                    
                    <!-- Basic Information -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
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
                                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Role Selection -->
                    <div class="mb-3">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select @error('role') is-invalid @enderror" 
                                id="role" name="role" required>
                            <option value="">Pilih Role</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="siswa" {{ old('role') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                            <option value="wali_kelas" {{ old('role') === 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                            <option value="bp" {{ old('role') === 'bp' ? 'selected' : '' }}>BP (Bimbingan dan Penyuluhan)</option>
                            <option value="kaprog" {{ old('role') === 'kaprog' ? 'selected' : '' }}>Kaprog (Kepala Program)</option>
                            <option value="tu" {{ old('role') === 'tu' ? 'selected' : '' }}>TU (Tata Usaha)</option>
                            <option value="hubin" {{ old('role') === 'hubin' ? 'selected' : '' }}>Hubin (Hubungan Industri)</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Student Specific Fields -->
                    <div id="studentFields" class="d-none">
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
                                                   id="nis" name="nis" value="{{ old('nis') }}">
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
                                                <option value="XI TKJ A" {{ old('kelas') === 'XI TKJ A' ? 'selected' : '' }}>XI TKJ A</option>
                                                <option value="XI TKJ B" {{ old('kelas') === 'XI TKJ B' ? 'selected' : '' }}>XI TKJ B</option>
                                                <option value="XI TBSM A" {{ old('kelas') === 'XI TBSM A' ? 'selected' : '' }}>XI TBSM A</option>
                                                <option value="XI TBSM B" {{ old('kelas') === 'XI TBSM B' ? 'selected' : '' }}>XI TBSM B</option>
                                                <option value="XI TKR A" {{ old('kelas') === 'XI TKR A' ? 'selected' : '' }}>XI TKR A</option>
                                                <option value="XI TKR B" {{ old('kelas') === 'XI TKR B' ? 'selected' : '' }}>XI TKR B</option>
                                                <option value="custom" {{ old('kelas') === 'custom' ? 'selected' : '' }}>Kelas Lainnya</option>
                                            </select>
                                            @error('kelas')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div id="customKelasField" class="col-md-4 d-none">
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
                                                <option value="TKJ" {{ old('jurusan') === 'TKJ' ? 'selected' : '' }}>TKJ (Teknik Komputer dan Jaringan)</option>
                                                <option value="TBSM" {{ old('jurusan') === 'TBSM' ? 'selected' : '' }}>TBSM (Teknik Bisnis Sepeda Motor)</option>
                                                <option value="TKR" {{ old('jurusan') === 'TKR' ? 'selected' : '' }}>TKR (Teknik Kendaraan Ringan)</option>
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
                    <div id="waliKelasFields" class="d-none">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-chalkboard-teacher me-2"></i>
                                    Kelas yang Diampu
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-text mb-2">
                                        Masukkan spesifikasi kelas XI yang diampu oleh wali kelas ini. Wali kelas hanya akan melihat permohonan PKL dari siswa di kelas yang dipilih.
                                    </div>
                                    
                                    <label for="custom_kelas_diampu" class="form-label">Spesifikasi Kelas XI <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('custom_kelas_diampu') is-invalid @enderror" 
                                           id="custom_kelas_diampu" name="custom_kelas_diampu" 
                                           value="{{ old('custom_kelas_diampu') }}" 
                                           placeholder="Contoh: XI TKJ A, XI TBSM B">
                                    <div class="form-text">Masukkan spesifikasi kelas XI yang diampu (contoh: XI TKJ A, XI TBSM B)</div>
                                    @error('custom_kelas_diampu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Kaprog Specific Fields -->
                    <div id="kaprogFields" class="d-none">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-user-tie me-2"></i>
                                    Jurusan yang Diampu
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-text mb-2">
                                        Pilih jurusan yang diampu oleh kepala program ini. Kaprog hanya akan melihat permohonan PKL dari siswa di jurusan yang dipilih.
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="jurusan_diampu_rpl" 
                                               name="jurusan_diampu[]" value="RPL" {{ in_array('RPL', old('jurusan_diampu', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="jurusan_diampu_rpl">RPL (Rekayasa Perangkat Lunak)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="jurusan_diampu_tkj" 
                                               name="jurusan_diampu[]" value="TKJ" {{ in_array('TKJ', old('jurusan_diampu', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="jurusan_diampu_tkj">TKJ (Teknik Komputer dan Jaringan)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="jurusan_diampu_mm" 
                                               name="jurusan_diampu[]" value="MM" {{ in_array('MM', old('jurusan_diampu', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="jurusan_diampu_mm">MM (Multimedia)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="jurusan_diampu_tbsm" 
                                               name="jurusan_diampu[]" value="TBSM" {{ in_array('TBSM', old('jurusan_diampu', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="jurusan_diampu_tbsm">TBSM (Teknik Bisnis Sepeda Motor)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="jurusan_diampu_tkr" 
                                               name="jurusan_diampu[]" value="TKR" {{ in_array('TKR', old('jurusan_diampu', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="jurusan_diampu_tkr">TKR (Teknik Kendaraan Ringan)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="jurusan_diampu_akl" 
                                               name="jurusan_diampu[]" value="AKL" {{ in_array('AKL', old('jurusan_diampu', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="jurusan_diampu_akl">AKL (Akuntansi dan Keuangan Lembaga)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="jurusan_diampu_otkp" 
                                               name="jurusan_diampu[]" value="OTKP" {{ in_array('OTKP', old('jurusan_diampu', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="jurusan_diampu_otkp">OTKP (Otomatisasi dan Tata Kelola Perkantoran)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="jurusan_diampu_bdp" 
                                               name="jurusan_diampu[]" value="BDP" {{ in_array('BDP', old('jurusan_diampu', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="jurusan_diampu_bdp">BDP (Bisnis Daring dan Pemasaran)</label>
                                    </div>
                                    @error('jurusan_diampu')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" 
                                   name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Pengguna Aktif
                            </label>
                        </div>
                        <div class="form-text">Pengguna yang tidak aktif tidak dapat login ke sistem</div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                            <i class="fas fa-undo me-2"></i>
                            Reset
                        </button>
                        
                        <div>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-2"></i>
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                <span id="submitText">Simpan Pengguna</span>
                                <span id="submitSpinner" class="d-none">
                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                    Menyimpan...
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
        
        // Hide all role-specific fields first
        studentFields.addClass('d-none');
        waliKelasFields.addClass('d-none');
        kaprogFields.addClass('d-none');
        
        // Remove required attribute from all fields
        $('#nis, #kelas, #jurusan').removeAttr('required');
        $('#custom_kelas').removeAttr('required');
        $('#custom_kelas_diampu').removeAttr('required');
        
        // Show fields based on selected role
        if (selectedRole === 'siswa') {
            studentFields.removeClass('d-none');
            $('#nis').attr('required', true);
            $('#kelas').attr('required', true);
            $('#jurusan').attr('required', true);
            
            // Check if custom kelas is selected
            if ($('#kelas').val() === 'custom') {
                $('#customKelasField').removeClass('d-none');
                $('#custom_kelas').attr('required', true);
            } else {
                $('#customKelasField').addClass('d-none');
                $('#custom_kelas').removeAttr('required');
            }
        } else if (selectedRole === 'wali_kelas') {
            waliKelasFields.removeClass('d-none');
            $('#custom_kelas_diampu').attr('required', true);
        } else if (selectedRole === 'kaprog') {
            kaprogFields.removeClass('d-none');
        }
        
        // Clear fields that are not visible
        if (selectedRole !== 'siswa') {
            $('#nis').val('');
            $('#kelas').val('');
            $('#jurusan').val('');
            $('#custom_kelas').val('');
        }
        
        if (selectedRole !== 'wali_kelas') {
            $('#custom_kelas_diampu').val('');
        }
        
        if (selectedRole !== 'kaprog') {
            $('input[name="jurusan_diampu[]"]').prop('checked', false);
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
    
    // Trigger change event on page load to handle old input
    $('#role').trigger('change');
    
    // Form validation
    $('#createUserForm').submit(function(e) {
        const password = $('#password').val();
        const passwordConfirmation = $('#password_confirmation').val();
        
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
});

function resetForm() {
    if (confirm('Yakin ingin mereset form? Semua data yang telah diisi akan hilang.')) {
        document.getElementById('createUserForm').reset();
        $('#role').trigger('change');
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Reset checkboxes for jurusan_diampu
        $('input[name="jurusan_diampu[]"]').prop('checked', false);
        // Reset custom kelas diampu field
        $('#custom_kelas_diampu').val('');
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
</style>
@endpush
@endsection