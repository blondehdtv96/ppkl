@extends('layouts.app')

@section('title', 'Notifikasi')

@section('page-title', 'Notifikasi')

@section('page-actions')
    <div class="d-flex gap-2">
        @if($notifikasi->where('is_read', false)->count() > 0)
            <form action="{{ route('notifikasi.read-all') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-check-double me-2"></i>
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
        
        @if($notifikasi->count() > 0)
            <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteAllModal">
                <i class="fas fa-trash me-2"></i>
                Hapus Semua
            </button>
        @endif
    </div>
@endsection

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fas fa-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="row">
    <div class="col-12">
        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('notifikasi.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="type" class="form-label">Tipe</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">Semua Tipe</option>
                            <option value="permohonan_baru" {{ request('type') === 'permohonan_baru' ? 'selected' : '' }}>Permohonan Baru</option>
                            <option value="permohonan_disetujui" {{ request('type') === 'permohonan_disetujui' ? 'selected' : '' }}>Permohonan Disetujui</option>
                            <option value="permohonan_ditolak" {{ request('type') === 'permohonan_ditolak' ? 'selected' : '' }}>Permohonan Ditolak</option>
                            <option value="permohonan_selesai" {{ request('type') === 'permohonan_selesai' ? 'selected' : '' }}>Permohonan Selesai</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="per_page" class="form-label">Per Halaman</label>
                        <select class="form-select" id="per_page" name="per_page">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-2"></i>
                                Filter
                            </button>
                            <a href="{{ route('notifikasi.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Notifications List -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bell me-2"></i>
                    Daftar Notifikasi
                    @if($notifikasi->where('is_read', false)->count() > 0)
                        <span class="badge bg-danger ms-2">{{ $notifikasi->where('is_read', false)->count() }} Belum Dibaca</span>
                    @endif
                </h5>
            </div>
            
            <div class="card-body p-0">
                @if($notifikasi->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($notifikasi as $item)
                        <div class="list-group-item {{ !$item->is_read ? 'list-group-item-light border-start border-primary border-3' : '' }}">
                            <div class="d-flex w-100 justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-{{ $item->getTypeIcon() }} text-{{ $item->getTypeColor() }} me-2"></i>
                                        <h6 class="mb-0 {{ !$item->is_read ? 'fw-bold' : '' }}">
                                            {{ $item->judul }}
                                        </h6>
                                        @if(!$item->is_read)
                                            <span class="badge bg-primary ms-2">Baru</span>
                                        @endif
                                    </div>
                                    
                                    <p class="mb-2 text-muted">{{ $item->pesan }}</p>
                                    
                                    <div class="d-flex align-items-center text-muted small">
                                        <i class="fas fa-clock me-1"></i>
                                        <span>{{ $item->created_at->diffForHumans() }}</span>
                                        
                                        @if($item->permohonan_id)
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-file-alt me-1"></i>
                                            <span>Permohonan #{{ $item->permohonan_id }}</span>
                                        @endif
                                        
                                        @if($item->is_read)
                                            <span class="mx-2">•</span>
                                            <i class="fas fa-check text-success me-1"></i>
                                            <span>Dibaca {{ $item->read_at->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if(!$item->is_read)
                                            <li>
                                                <a href="{{ route('notifikasi.read', $item) }}" class="dropdown-item">
                                                    <i class="fas fa-check me-2"></i>
                                                    Tandai Dibaca
                                                </a>
                                            </li>
                                        @endif
                                        
                                        @if($item->permohonan_id)
                                            <li>
                                                <a class="dropdown-item" href="{{ route('permohonan.show', $item->permohonan_id) }}">
                                                    <i class="fas fa-eye me-2"></i>
                                                    Lihat Permohonan
                                                </a>
                                            </li>
                                        @endif
                                        
                                        <li><hr class="dropdown-divider"></li>
                                        
                                        <li>
                                            <form action="{{ route('notifikasi.destroy', $item) }}" method="POST" 
                                                  onsubmit="return confirm('Yakin ingin menghapus notifikasi ini?')">
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
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if($notifikasi->hasPages())
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Menampilkan {{ $notifikasi->firstItem() }} - {{ $notifikasi->lastItem() }} 
                                dari {{ $notifikasi->total() }} notifikasi
                            </div>
                            {{ $notifikasi->links() }}
                        </div>
                    </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-bell-slash text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">Tidak ada notifikasi</h5>
                        <p class="text-muted">Notifikasi akan muncul di sini ketika ada aktivitas terkait permohonan PKL.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete All Modal -->
@if($notifikasi->count() > 0)
<div class="modal fade" id="deleteAllModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-trash text-danger me-2"></i>
                    Hapus Semua Notifikasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Peringatan!</strong> Tindakan ini tidak dapat dibatalkan.
                </div>
                
                <p>Anda yakin ingin menghapus <strong>semua notifikasi</strong>?</p>
                <p class="text-muted">Semua notifikasi ({{ $notifikasi->count() }} item) akan dihapus permanen.</p>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Batal
                </button>
                
                <form action="{{ route('notifikasi.destroy-all') }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Hapus Semua
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Auto-refresh unread count every 30 seconds
    setInterval(function() {
        fetch('{{ route('notifikasi.unread-count') }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.notification-badge');
                if (badge) {
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'inline';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            })
            .catch(error => console.log('Error fetching unread count:', error));
    }, 30000);
    
    // Mark notification as read when clicked
    document.querySelectorAll('.list-group-item').forEach(item => {
        item.addEventListener('click', function(e) {
            // Don't trigger if clicking on dropdown or buttons
            if (e.target.closest('.dropdown') || e.target.closest('button') || e.target.closest('form')) {
                return;
            }
            
            const notificationId = this.dataset.notificationId;
            if (notificationId && this.classList.contains('list-group-item-light')) {
                // Mark as read via AJAX
                fetch(`/notifikasi/${notificationId}/read`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.classList.remove('list-group-item-light', 'border-start', 'border-primary', 'border-3');
                        const badge = this.querySelector('.badge.bg-primary');
                        if (badge) badge.remove();
                        
                        const title = this.querySelector('h6');
                        if (title) title.classList.remove('fw-bold');
                    }
                })
                .catch(error => console.log('Error marking as read:', error));
            }
        });
    });
</script>
@endpush
@endsection