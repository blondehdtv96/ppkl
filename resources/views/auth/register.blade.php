@extends('layouts.app')

@section('title', 'Register - PKL Management')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h3 class="card-title mb-1">Daftar Akun Baru</h3>
                        <p class="text-muted">Lengkapi form di bawah untuk mendaftar</p>
                    </div>
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required 
                                       autofocus
                                       placeholder="Masukkan nama lengkap">
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required
                                       placeholder="Masukkan email">
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required
                                       placeholder="Masukkan password">
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       required
                                       placeholder="Konfirmasi password">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" 
                                    name="role" 
                                    required>
                                <option value="">Pilih Role</option>
                                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                <option value="wali_kelas" {{ old('role') == 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                                <option value="bp" {{ old('role') == 'bp' ? 'selected' : '' }}>BP (Bimbingan dan Penyuluhan)</option>
                                <option value="kaprog" {{ old('role') == 'kaprog' ? 'selected' : '' }}>Kaprog (Kepala Program)</option>
                                <option value="tu" {{ old('role') == 'tu' ? 'selected' : '' }}>TU (Tata Usaha)</option>
                                <option value="hubin" {{ old('role') == 'hubin' ? 'selected' : '' }}>Hubin (Hubungan Industri)</option>
                            </select>
                            @error('role')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        
                        <!-- Fields for Siswa -->
                        <div id="siswa-fields" class="d-none">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="nis" class="form-label">NIS</label>
                                    <input type="text" 
                                           class="form-control @error('nis') is-invalid @enderror" 
                                           id="nis" 
                                           name="nis" 
                                           value="{{ old('nis') }}"
                                           placeholder="Nomor Induk Siswa">
                                    @error('nis')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="kelas" class="form-label">Kelas</label>
                                    <input type="text" 
                                           class="form-control @error('kelas') is-invalid @enderror" 
                                           id="kelas" 
                                           name="kelas" 
                                           value="{{ old('kelas') }}"
                                           placeholder="Contoh: XII RPL 1">
                                    @error('kelas')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="jurusan" class="form-label">Jurusan</label>
                                    <input type="text" 
                                           class="form-control @error('jurusan') is-invalid @enderror" 
                                           id="jurusan" 
                                           name="jurusan" 
                                           value="{{ old('jurusan') }}"
                                           placeholder="Contoh: RPL">
                                    @error('jurusan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>
                                Daftar
                            </button>
                        </div>
                    </form>
                    
                    <div class="text-center mt-3">
                        <p class="text-muted mb-0">
                            Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('role').addEventListener('change', function() {
        const siswaFields = document.getElementById('siswa-fields');
        const nisField = document.getElementById('nis');
        const kelasField = document.getElementById('kelas');
        const jurusanField = document.getElementById('jurusan');
        
        if (this.value === 'siswa') {
            siswaFields.classList.remove('d-none');
            nisField.required = true;
            kelasField.required = true;
            jurusanField.required = true;
        } else {
            siswaFields.classList.add('d-none');
            nisField.required = false;
            kelasField.required = false;
            jurusanField.required = false;
            nisField.value = '';
            kelasField.value = '';
            jurusanField.value = '';
        }
    });
    
    // Trigger on page load if role is already selected
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.getElementById('role');
        if (roleSelect.value === 'siswa') {
            roleSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
@endsection