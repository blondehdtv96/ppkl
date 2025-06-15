@extends('layouts.app')

@section('title', 'Edit Permohonan PKL')

@section('page-title', 'Edit Permohonan PKL')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Edit Permohonan PKL
                    <span class="badge badge-{{ $permohonan->getStatusColor() }} ms-2">{{ $permohonan->getStatusLabel() }}</span>
                </h5>
            </div>
            
            <div class="card-body">
                @if($permohonan->status !== 'draft' && $permohonan->status !== 'ditolak_wali' && $permohonan->status !== 'ditolak_bp' && $permohonan->status !== 'ditolak_kaprog' && $permohonan->status !== 'ditolak_tu')
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Perhatian!</strong> Permohonan ini sedang dalam proses atau sudah disetujui. 
                    Perubahan yang Anda lakukan mungkin memerlukan persetujuan ulang.
                </div>
                @endif
                
                @if($permohonan->histori->where('status', 'like', 'ditolak_%')->isNotEmpty())
                <div class="alert alert-info">
                    <h6><i class="fas fa-info-circle me-2"></i>Alasan Penolakan Terakhir:</h6>
                    @php
                        $lastRejection = $permohonan->histori->where('status', 'like', 'ditolak_%')->sortByDesc('created_at')->first();
                    @endphp
                    <p class="mb-1"><strong>{{ $lastRejection->getStatusLabel() }}</strong></p>
                    <p class="mb-1">{{ $lastRejection->keterangan }}</p>
                    <small class="text-muted">
                        Ditolak oleh {{ $lastRejection->processor->name }} pada {{ $lastRejection->created_at->format('d F Y H:i') }}
                    </small>
                </div>
                @endif
                
                <form action="{{ route('permohonan.update', $permohonan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Informasi Perusahaan -->
                    <div class="mb-4">
                        <h6 class="text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-building me-2"></i>
                            Informasi Perusahaan
                        </h6>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_perusahaan" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('nama_perusahaan') is-invalid @enderror" 
                                       id="nama_perusahaan" 
                                       name="nama_perusahaan" 
                                       value="{{ old('nama_perusahaan', $permohonan->nama_perusahaan) }}" 
                                       required
                                       placeholder="Masukkan nama perusahaan">
                                @error('nama_perusahaan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="bidang_usaha" class="form-label">Bidang Usaha <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('bidang_usaha') is-invalid @enderror" 
                                       id="bidang_usaha" 
                                       name="bidang_usaha" 
                                       value="{{ old('bidang_usaha', $permohonan->bidang_usaha) }}" 
                                       required
                                       placeholder="Contoh: Teknologi Informasi">
                                @error('bidang_usaha')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat_perusahaan') is-invalid @enderror" 
                                      id="alamat_perusahaan" 
                                      name="alamat_perusahaan" 
                                      rows="3" 
                                      required
                                      placeholder="Masukkan alamat lengkap perusahaan">{{ old('alamat_perusahaan', $permohonan->alamat_perusahaan) }}</textarea>
                            @error('alamat_perusahaan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kontak_perusahaan" class="form-label">Kontak Perusahaan</label>
                                <input type="text" 
                                       class="form-control @error('kontak_perusahaan') is-invalid @enderror" 
                                       id="kontak_perusahaan" 
                                       name="kontak_perusahaan" 
                                       value="{{ old('kontak_perusahaan', $permohonan->kontak_perusahaan) }}"
                                       placeholder="Nomor telepon atau email">
                                @error('kontak_perusahaan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nama_pembimbing" class="form-label">Nama Pembimbing Lapangan</label>
                                <input type="text" 
                                       class="form-control @error('nama_pembimbing') is-invalid @enderror" 
                                       id="nama_pembimbing" 
                                       name="nama_pembimbing" 
                                       value="{{ old('nama_pembimbing', $permohonan->nama_pembimbing) }}"
                                       placeholder="Nama pembimbing di perusahaan"
                                       @if(auth()->user()->role !== 'hubin') readonly disabled @endif>
                                @if(auth()->user()->role !== 'hubin')
                                <small class="form-text text-muted">Field ini hanya dapat diisi oleh Hubin.</small>
                                @endif
                                @error('nama_pembimbing')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Periode PKL -->
                    <div class="mb-4">
                        <h6 class="text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-calendar me-2"></i>
                            Periode PKL
                        </h6>
                        
                        @if(auth()->user()->role !== 'hubin')
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Tanggal mulai dan tanggal selesai PKL hanya dapat diubah oleh Hubin.
                        </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                       id="tanggal_mulai" 
                                       name="tanggal_mulai" 
                                       value="{{ old('tanggal_mulai', $permohonan->tanggal_mulai->format('Y-m-d')) }}" 
                                       required
                                       @if(auth()->user()->role !== 'hubin') readonly disabled @endif
                                       min="{{ date('Y-m-d') }}">
                                @if(auth()->user()->role !== 'hubin')
                                <small class="form-text text-muted">Field ini hanya dapat diisi oleh Hubin.</small>
                                @endif
                                @error('tanggal_mulai')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       id="tanggal_selesai" 
                                       name="tanggal_selesai" 
                                       value="{{ old('tanggal_selesai', $permohonan->tanggal_selesai->format('Y-m-d')) }}" 
                                       required
                                       @if(auth()->user()->role !== 'hubin') readonly disabled @endif>
                                @if(auth()->user()->role !== 'hubin')
                                <small class="form-text text-muted">Field ini hanya dapat diisi oleh Hubin.</small>
                                @endif
                                @error('tanggal_selesai')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        
                        @if(auth()->user()->role === 'hubin')
                        <div class="alert alert-info" id="duration-info" style="display: none;">
                            <i class="fas fa-info-circle me-2"></i>
                            <span id="duration-text"></span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Alasan dan Tujuan -->
                    <div class="mb-4">
                        <h6 class="text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Alasan dan Tujuan PKL
                        </h6>
                        
                        <div class="mb-3">
                            <label for="alasan" class="form-label">Alasan Memilih Perusahaan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alasan') is-invalid @enderror" 
                                      id="alasan" 
                                      name="alasan" 
                                      rows="4" 
                                      required
                                      placeholder="Jelaskan alasan Anda memilih perusahaan ini untuk PKL">{{ old('alasan', $permohonan->alasan) }}</textarea>
                            @error('alasan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                            <div class="form-text">
                                Minimal 50 karakter. Jelaskan dengan detail mengapa Anda memilih perusahaan ini.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dokumen Pendukung -->
                    <div class="mb-4">
                        <h6 class="text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-paperclip me-2"></i>
                            Dokumen Pendukung
                        </h6>
                        
                        @if($permohonan->dokumen_pendukung)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Dokumen Saat Ini:</label>
                            <div class="d-flex align-items-center bg-light p-2 rounded">
                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                <a href="{{ Storage::url($permohonan->dokumen_pendukung) }}" target="_blank" class="text-decoration-none me-2">
                                    Lihat Dokumen
                                </a>
                                <span class="text-muted">
                                    ({{ number_format(Storage::size($permohonan->dokumen_pendukung) / 1024, 2) }} KB)
                                </span>
                            </div>
                        </div>
                        @endif
                        
                        <div class="mb-3">
                            <label for="dokumen_pendukung" class="form-label">
                                {{ $permohonan->dokumen_pendukung ? 'Ganti Dokumen' : 'Upload Dokumen' }}
                            </label>
                            <input type="file" 
                                   class="form-control @error('dokumen_pendukung') is-invalid @enderror" 
                                   id="dokumen_pendukung" 
                                   name="dokumen_pendukung" 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            @error('dokumen_pendukung')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                            <div class="form-text">
                                Format yang diizinkan: PDF, DOC, DOCX, JPG, PNG. Maksimal 2MB.
                                @if($permohonan->dokumen_pendukung)
                                <br>Biarkan kosong jika tidak ingin mengganti dokumen.
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pernyataan -->
                    <div class="mb-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                    Pernyataan
                                </h6>
                                <div class="form-check">
                                    <input class="form-check-input @error('pernyataan') is-invalid @enderror" 
                                           type="checkbox" 
                                           id="pernyataan" 
                                           name="pernyataan" 
                                           value="1" 
                                           {{ old('pernyataan', true) ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="pernyataan">
                                        Saya menyatakan bahwa data yang saya ubah adalah benar dan dapat dipertanggungjawabkan. 
                                        Saya memahami bahwa perubahan ini mungkin memerlukan persetujuan ulang dari pihak terkait.
                                    </label>
                                    @error('pernyataan')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('permohonan.show', $permohonan) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Kembali
                        </a>
                        
                        <div>
                            <button type="reset" class="btn btn-outline-warning me-2">
                                <i class="fas fa-undo me-2"></i>
                                Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Simpan Perubahan
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
    @if(auth()->user()->role === 'hubin')
    // Calculate duration between dates
    function calculateDuration() {
        const startDate = document.getElementById('tanggal_mulai').value;
        const endDate = document.getElementById('tanggal_selesai').value;
        const durationInfo = document.getElementById('duration-info');
        const durationText = document.getElementById('duration-text');
        
        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            if (end > start) {
                durationText.textContent = `Durasi PKL: ${diffDays} hari`;
                durationInfo.style.display = 'block';
                durationInfo.className = 'alert alert-info';
            } else if (end < start) {
                durationText.textContent = 'Tanggal selesai harus setelah tanggal mulai!';
                durationInfo.style.display = 'block';
                durationInfo.className = 'alert alert-danger';
            } else {
                durationInfo.style.display = 'none';
            }
        } else {
            durationInfo.style.display = 'none';
        }
    }
    
    // Set minimum end date when start date changes
    document.getElementById('tanggal_mulai').addEventListener('change', function() {
        const startDate = this.value;
        const endDateInput = document.getElementById('tanggal_selesai');
        
        if (startDate) {
            endDateInput.min = startDate;
            
            // If end date is before start date, clear it
            if (endDateInput.value && endDateInput.value < startDate) {
                endDateInput.value = '';
            }
        }
        
        calculateDuration();
    });
    
    document.getElementById('tanggal_selesai').addEventListener('change', calculateDuration);
    
    // Initial calculation
    calculateDuration();
    @endif
    
    // Character counter for alasan
    const alasanTextarea = document.getElementById('alasan');
    const alasanCounter = document.createElement('div');
    alasanCounter.className = 'form-text';
    alasanTextarea.parentNode.appendChild(alasanCounter);
    
    function updateAlasanCounter() {
        const length = alasanTextarea.value.length;
        const minLength = 50;
        
        if (length < minLength) {
            alasanCounter.textContent = `${length}/${minLength} karakter (minimal ${minLength - length} karakter lagi)`;
            alasanCounter.className = 'form-text text-warning';
        } else {
            alasanCounter.textContent = `${length} karakter`;
            alasanCounter.className = 'form-text text-success';
        }
    }
    
    alasanTextarea.addEventListener('input', updateAlasanCounter);
    updateAlasanCounter();
    
    // File size validation
    document.getElementById('dokumen_pendukung').addEventListener('change', function() {
        const file = this.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB
        
        if (file && file.size > maxSize) {
            alert('Ukuran file terlalu besar! Maksimal 2MB.');
            this.value = '';
        }
    });
    
    // Form validation before submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const alasan = document.getElementById('alasan').value;
        const pernyataan = document.getElementById('pernyataan').checked;
        
        if (alasan.length < 50) {
            e.preventDefault();
            alert('Alasan harus minimal 50 karakter!');
            document.getElementById('alasan').focus();
            return false;
        }
        
        if (!pernyataan) {
            e.preventDefault();
            alert('Anda harus menyetujui pernyataan!');
            document.getElementById('pernyataan').focus();
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
    });
</script>
@endpush
@endsection