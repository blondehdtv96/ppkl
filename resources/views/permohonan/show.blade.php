@extends('layouts.app')

@section('title', 'Detail Permohonan PKL')

@section('page-title')
    Detail Permohonan PKL
    <span class="badge badge-{{ $permohonan->getStatusColor() }} ms-2">{{ $permohonan->getStatusLabel() }}</span>
@endsection

@section('page-actions')
    <div class="d-flex gap-2">
        @can('update', $permohonan)
            @if($permohonan->status === 'ditolak_wali' || $permohonan->status === 'ditolak_bp' || $permohonan->status === 'ditolak_kaprog' || $permohonan->status === 'ditolak_tu')
                <a href="{{ route('permohonan.edit', $permohonan) }}" class="btn btn-warning">
                    <i class="fas fa-edit me-2"></i>
                    Edit
                </a>
            @endif
        @endcan
        
        @can('process', $permohonan)
            @if($permohonan->canBeProcessedBy(auth()->user()))
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                    <i class="fas fa-check me-2"></i>
                    Setujui
                </button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="fas fa-times me-2"></i>
                    Tolak
                </button>
            @endif
        @endcan
        
        @if(auth()->user()->role === 'hubin')
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updatePembimbingModal">
                <i class="fas fa-user-edit me-2"></i>
                Update Pembimbing & Tanggal
            </button>
        @endif
        
        @can('print', $permohonan)
            <a href="{{ route('permohonan.print', $permohonan) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-print me-2"></i>
                Cetak Surat
            </a>
        @endcan
        
        <a href="{{ route('permohonan.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <!-- Detail Permohonan -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Informasi Permohonan
                </h5>
            </div>
            
            <div class="card-body">
                <!-- Informasi Siswa -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-user me-2"></i>
                        Informasi Siswa
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Siswa:</label>
                                <p class="mb-0">{{ $permohonan->siswa->name }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">NIS:</label>
                                <p class="mb-0">{{ $permohonan->siswa->nis }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kelas:</label>
                                <p class="mb-0">{{ $permohonan->siswa->kelas }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Jurusan:</label>
                                <p class="mb-0">{{ $permohonan->siswa->jurusan }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Informasi Perusahaan -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-building me-2"></i>
                        Informasi Perusahaan
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Perusahaan:</label>
                                <p class="mb-0">{{ $permohonan->nama_perusahaan }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Bidang Usaha:</label>
                                <p class="mb-0">{{ $permohonan->bidang_usaha }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($permohonan->kontak_perusahaan)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kontak Perusahaan:</label>
                                <p class="mb-0">{{ $permohonan->kontak_perusahaan }}</p>
                            </div>
                            @endif
                            @if($permohonan->nama_pembimbing)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pembimbing Lapangan:</label>
                                <p class="mb-0">{{ $permohonan->nama_pembimbing }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Perusahaan:</label>
                        <p class="mb-0">{{ $permohonan->alamat_perusahaan }}</p>
                    </div>
                </div>
                
                <!-- Periode PKL -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-calendar me-2"></i>
                        Periode PKL
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Mulai:</label>
                                <p class="mb-0">{{ $permohonan->tanggal_mulai->format('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Selesai:</label>
                                <p class="mb-0">{{ $permohonan->tanggal_selesai->format('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Durasi:</label>
                                <p class="mb-0">
                                    {{ $permohonan->tanggal_mulai->diffInDays($permohonan->tanggal_selesai) }} hari
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Alasan -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Alasan Memilih Perusahaan
                    </h6>
                    
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">{{ $permohonan->alasan }}</p>
                    </div>
                </div>
                
                <!-- Dokumen Pendukung -->
                @if($permohonan->dokumen_pendukung)
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-paperclip me-2"></i>
                        Dokumen Pendukung
                    </h6>
                    
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-pdf text-danger me-2"></i>
                        <a href="{{ Storage::url($permohonan->dokumen_pendukung) }}" target="_blank" class="text-decoration-none">
                            Lihat Dokumen
                        </a>
                        <span class="text-muted ms-2">
                            ({{ number_format(Storage::size($permohonan->dokumen_pendukung) / 1024, 2) }} KB)
                        </span>
                    </div>
                </div>
                @endif
                
                <!-- Informasi Tambahan -->
                <div class="mb-4">
                    <h6 class="text-primary border-bottom pb-2 mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Informasi Tambahan
                    </h6>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Pengajuan:</label>
                                <p class="mb-0">{{ $permohonan->created_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Terakhir Diupdate:</label>
                                <p class="mb-0">{{ $permohonan->updated_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($permohonan->processor_id)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Sedang Diproses Oleh:</label>
                                <p class="mb-0">
                                    {{ $permohonan->processor->name }}
                                    <span class="badge bg-secondary ms-2">{{ $permohonan->processor->getRoleLabel() }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Status Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Timeline Status
                </h5>
            </div>
            
            <div class="card-body">
                <div class="timeline">
                    @foreach($permohonan->histori->sortBy('created_at') as $histori)
                    <div class="timeline-item {{ $histori->status === $permohonan->status ? 'active' : '' }}">
                        <div class="timeline-marker">
                            <i class="fas fa-{{ $histori->getStatusIcon() }}"></i>
                        </div>
                        <div class="timeline-content">
                            <h6 class="mb-1">{{ $histori->getStatusLabel() }}</h6>
                            <p class="text-muted mb-1">
                                {{ $histori->processor->name }} ({{ $histori->getRoleProcessorLabelAttribute() }})
                            </p>
                            <small class="text-muted">
                                {{ $histori->created_at->format('d M Y H:i') }}
                            </small>
                            @if($histori->keterangan)
                            <div class="mt-2">
                                <small class="text-dark">
                                    <strong>Keterangan:</strong> {{ $histori->keterangan }}
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        @if(auth()->user()->role === 'admin')
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-cogs me-2"></i>
                    Admin Actions
                </h5>
            </div>
            
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('permohonan.edit', $permohonan) }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-edit me-2"></i>
                        Edit Permohonan
                    </a>
                    
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash me-2"></i>
                        Hapus Permohonan
                    </button>
                    
                    <a href="{{ route('permohonan.print', $permohonan) }}" class="btn btn-outline-info btn-sm" target="_blank">
                        <i class="fas fa-print me-2"></i>
                        Preview Surat
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Approve Modal -->
@can('process', $permohonan)
@if($permohonan->canBeProcessedBy(auth()->user()))
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-check text-success me-2"></i>
                    Setujui Permohonan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('permohonan.process', $permohonan) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="approve">
                
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Anda akan menyetujui permohonan PKL dari <strong>{{ $permohonan->siswa->name }}</strong>.
                    </div>
                    
                    <div class="mb-3">
                        <label for="approve_keterangan" class="form-label">Keterangan (Opsional)</label>
                        <textarea class="form-control" 
                                  id="approve_keterangan" 
                                  name="keterangan" 
                                  rows="3"
                                  placeholder="Tambahkan catatan atau keterangan..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>
                        Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-times text-danger me-2"></i>
                    Tolak Permohonan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('permohonan.process', $permohonan) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="reject">
                
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Anda akan menolak permohonan PKL dari <strong>{{ $permohonan->siswa->name }}</strong>.
                    </div>
                    
                    <div class="mb-3">
                        <label for="reject_keterangan" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  id="reject_keterangan" 
                                  name="keterangan" 
                                  rows="4"
                                  required
                                  placeholder="Jelaskan alasan penolakan..."></textarea>
                        <div class="form-text">
                            Alasan penolakan akan dikirimkan kepada siswa.
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i>
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endcan

<!-- Update Pembimbing Modal (Hubin Only) -->
@if(auth()->user()->role === 'hubin')
<div class="modal fade" id="updatePembimbingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit text-primary me-2"></i>
                    Update Pembimbing & Tanggal PKL
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <form action="{{ route('permohonan.updatePembimbing', $permohonan) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Anda akan mengupdate informasi pembimbing dan tanggal PKL untuk permohonan dari <strong>{{ $permohonan->siswa->name }}</strong>.
                    </div>
                    
                    <div class="mb-3">
                        <label for="nama_pembimbing" class="form-label">Nama Pembimbing Lapangan <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_pembimbing') is-invalid @enderror" 
                               id="nama_pembimbing" 
                               name="nama_pembimbing" 
                               value="{{ old('nama_pembimbing', $permohonan->nama_pembimbing) }}" 
                               required>
                        @error('nama_pembimbing')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                                       id="tanggal_mulai" 
                                       name="tanggal_mulai" 
                                       value="{{ old('tanggal_mulai', $permohonan->tanggal_mulai->format('Y-m-d')) }}" 
                                       required>
                                @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                                       id="tanggal_selesai" 
                                       name="tanggal_selesai" 
                                       value="{{ old('tanggal_selesai', $permohonan->tanggal_selesai->format('Y-m-d')) }}" 
                                       required>
                                @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan (Opsional)</label>
                        <textarea class="form-control" 
                                  id="keterangan" 
                                  name="keterangan" 
                                  rows="3"
                                  placeholder="Tambahkan catatan atau keterangan..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
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
@endif

<!-- Delete Modal (Admin Only) -->
@if(auth()->user()->role === 'admin')
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-trash text-danger me-2"></i>
                    Hapus Permohonan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                </div>
                
                <p>Anda yakin ingin menghapus permohonan PKL dari <strong>{{ $permohonan->siswa->name }}</strong>?</p>
                <p class="text-muted">Semua data dan histori terkait permohonan ini akan dihapus permanen.</p>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                
                <form action="{{ route('permohonan.destroy', $permohonan) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@push('styles')
<style>
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

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #6c757d;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #dee2e6;
}

.timeline-item.active .timeline-marker {
    background: #0d6efd;
    box-shadow: 0 0 0 2px #0d6efd;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #dee2e6;
}

.timeline-item.active .timeline-content {
    border-left-color: #0d6efd;
    background: #e7f3ff;
}

.badge-diajukan { background-color: #6c757d; }
.badge-ditolak_wali, .badge-ditolak_bp, .badge-ditolak_kaprog, .badge-ditolak_tu { background-color: #dc3545; }
.badge-disetujui_wali, .badge-disetujui_bp, .badge-disetujui_kaprog, .badge-disetujui_tu { background-color: #198754; }
.badge-dicetak_hubin { background-color: #0d6efd; }
</style>
@endpush

@push('scripts')
<script>
    // Script untuk modal Update Pembimbing
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil elemen input tanggal
        const tanggalMulaiInput = document.getElementById('tanggal_mulai');
        const tanggalSelesaiInput = document.getElementById('tanggal_selesai');
        
        // Fungsi untuk menghitung durasi PKL
        function hitungDurasiPKL() {
            const tanggalMulai = new Date(tanggalMulaiInput.value);
            const tanggalSelesai = new Date(tanggalSelesaiInput.value);
            
            // Pastikan tanggal valid
            if (isNaN(tanggalMulai.getTime()) || isNaN(tanggalSelesai.getTime())) {
                return;
            }
            
            // Hitung selisih hari
            const selisihWaktu = tanggalSelesai.getTime() - tanggalMulai.getTime();
            const selisihHari = Math.ceil(selisihWaktu / (1000 * 3600 * 24));
            
            // Tampilkan pesan jika tanggal selesai lebih awal dari tanggal mulai
            if (selisihHari < 0) {
                alert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai!');
                tanggalSelesaiInput.value = tanggalMulaiInput.value;
            }
        }
        
        // Set tanggal minimum untuk tanggal selesai
        function setMinTanggalSelesai() {
            tanggalSelesaiInput.min = tanggalMulaiInput.value;
            
            // Jika tanggal selesai lebih awal dari tanggal mulai, sesuaikan
            if (tanggalSelesaiInput.value && tanggalSelesaiInput.value < tanggalMulaiInput.value) {
                tanggalSelesaiInput.value = tanggalMulaiInput.value;
            }
        }
        
        // Tambahkan event listener
        if (tanggalMulaiInput && tanggalSelesaiInput) {
            tanggalMulaiInput.addEventListener('change', function() {
                setMinTanggalSelesai();
                hitungDurasiPKL();
            });
            
            tanggalSelesaiInput.addEventListener('change', function() {
                hitungDurasiPKL();
            });
            
            // Set nilai awal
            setMinTanggalSelesai();
        }
    });
</script>
@endpush
@endsection