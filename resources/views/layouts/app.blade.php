<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PKL Management System')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        /* Consistent icon sizing */
        .fas, .far, .fab {
            font-size: 1rem;
        }
        .btn .fas, .btn .far, .btn .fab {
            font-size: 0.875rem;
        }
        .btn-sm .fas, .btn-sm .far, .btn-sm .fab {
            font-size: 0.75rem;
        }
        .btn-lg .fas, .btn-lg .far, .btn-lg .fab {
            font-size: 1.125rem;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @auth
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h5 class="text-white">PKL Management</h5>
                        <small class="text-light">{{ auth()->user()->name }}</small>
                        <br>
                        <small class="badge bg-light text-dark mt-1">{{ ucfirst(auth()->user()->role) }}</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        @if(auth()->user()->role === 'siswa')
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('permohonan.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('permohonan.index') }}">
                                <i class="fas fa-file-alt me-2"></i>
                                Permohonan PKL
                            </a>
                        </li>
                        @endif
                        
                        @if(in_array(auth()->user()->role, ['admin', 'wali_kelas', 'bp', 'kaprog', 'tu', 'hubin']))
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('permohonan.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('permohonan.index') }}">
                                <i class="fas fa-tasks me-2"></i>
                                Kelola Permohonan
                            </a>
                        </li>
                        @endif
                        
                        @if(auth()->user()->role === 'wali_kelas')
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('siswa.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('siswa.index') }}">
                                <i class="fas fa-graduation-cap me-2"></i>
                                Daftar Siswa
                            </a>
                        </li>
                        @endif
                        
                        @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('users.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('users.index') }}">
                                <i class="fas fa-users me-2"></i>
                                Kelola Pengguna
                            </a>
                        </li>
                        @endif
                        
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('notifikasi.*') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('notifikasi.index') }}" style="position: relative;">
                                <i class="fas fa-bell me-2"></i>
                                Notifikasi
                                <span id="unread-count" class="notification-badge d-none"></span>
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link text-white {{ request()->routeIs('users.profile') ? 'active bg-white bg-opacity-25' : '' }}" href="{{ route('users.profile') }}">
                                <i class="fas fa-user me-2"></i>
                                Profil
                            </a>
                        </li>
                    </ul>
                    
                    <hr class="text-white">
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm w-100">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </nav>
            @endauth
            
            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @auth
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">@yield('page-title', 'Dashboard')</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        @yield('page-actions')
                    </div>
                </div>
                @endauth
                
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Terdapat kesalahan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Update notification count
        function updateNotificationCount() {
            fetch('/api/notifikasi/unread-count', {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('unread-count');
                    if (data.count > 0) {
                        badge.textContent = data.count > 99 ? '99+' : data.count;
                        badge.classList.remove('d-none');
                    } else {
                        badge.classList.add('d-none');
                    }
                })
                .catch(error => console.error('Error fetching notification count:', error));
        }
        
        // Update notification count on page load
        @auth
        document.addEventListener('DOMContentLoaded', updateNotificationCount);
        
        // Update notification count every 30 seconds
        setInterval(updateNotificationCount, 30000);
        @endauth
    </script>
    
    @stack('scripts')
</body>
</html>