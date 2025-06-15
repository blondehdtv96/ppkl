<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Pendaftaran Permohonan PKL SMK BINA MANDIRI</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .login-container {
            display: flex;
            max-width: 1000px;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .login-info {
            flex: 1;
            padding: 40px;
            background-color: white;
        }
        .login-form {
            flex: 1;
            padding: 40px;
            background-color: white;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
        }
        .form-control, .input-group-text {
            border-radius: 5px;
            padding: 10px 15px;
        }
        .btn-primary {
            background-color: #6366F1;
            border-color: #6366F1;
            padding: 10px;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background-color: #4F46E5;
            border-color: #4F46E5;
        }
        .feature-item {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .feature-item i {
            color: #6366F1;
            margin-right: 10px;
        }
        .copyright {
            position: absolute;
            bottom: 10px;
            width: 100%;
            text-align: center;
            color: white;
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            .login-info {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-info">
            <h2 class="mb-4">Selamat Datang di Pendaftaran Permohonan PKL SMK BINA MANDIRI</h2>
            <p class="text-muted mb-4">Sistem ini dirancang untuk memudahkan pengelolaan dan pemantauan kegiatan Praktik Kerja Industri (PKL) di SMK BINA MANDIRI.</p>
            
            <div class="feature-item">
                <i class="fas fa-chart-line"></i>
                <span>Pantau perkembangan peserta prakerin secara real-time</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-database"></i>
                <span>Kelola data peserta dan pembimbing dengan mudah</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Akses informasi jadwal dan lokasi prakerin</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-file-alt"></i>
                <span>Laporan dan evaluasi kegiatan prakerin</span>
            </div>
        </div>
        
        <div class="login-form">
            <div class="text-center mb-4">
                <svg width="100" height="100" viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg" class="logo">
                    <!-- Gear (Blue) -->
                    <path d="M150,20 C80,20 20,80 20,150 C20,220 80,280 150,280 C220,280 280,220 280,150 C280,80 220,20 150,20 Z M150,40 C210,40 260,90 260,150 C260,210 210,260 150,260 C90,260 40,210 40,150 C40,90 90,40 150,40 Z" fill="#0000CC"/>
                    <!-- Inner Gear Teeth -->
                    <path d="M150,60 L160,40 L140,40 Z" fill="#0000CC"/>
                    <path d="M190,70 L210,55 L190,50 Z" fill="#0000CC"/>
                    <path d="M220,110 L240,100 L230,80 Z" fill="#0000CC"/>
                    <path d="M220,190 L240,200 L230,220 Z" fill="#0000CC"/>
                    <path d="M190,230 L210,245 L190,250 Z" fill="#0000CC"/>
                    <path d="M150,240 L160,260 L140,260 Z" fill="#0000CC"/>
                    <path d="M110,230 L90,245 L110,250 Z" fill="#0000CC"/>
                    <path d="M80,190 L60,200 L70,220 Z" fill="#0000CC"/>
                    <path d="M80,110 L60,100 L70,80 Z" fill="#0000CC"/>
                    <path d="M110,70 L90,55 L110,50 Z" fill="#0000CC"/>
                    
                    <!-- Book (Blue) -->
                    <path d="M100,220 L200,220 L200,240 L100,240 Z" fill="#0000CC"/>
                    <path d="M100,240 C80,230 80,230 100,220" fill="#0000CC"/>
                    <path d="M200,240 C220,230 220,230 200,220" fill="#0000CC"/>
                    <path d="M150,220 L150,240" stroke="#FFFFFF" stroke-width="2"/>
                    
                    <!-- Computer/Student (White) -->
                    <rect x="130" y="130" width="40" height="30" fill="white"/>
                    <rect x="140" y="160" width="20" height="20" fill="white"/>
                    <circle cx="150" cy="110" r="15" fill="white"/>
                    
                    <!-- Lightning Bolts (Red) -->
                    <path d="M100,150 L120,170 L110,190 L140,160 L130,140 L140,120 Z" fill="#CC0000"/>
                    <path d="M200,150 L180,170 L190,190 L160,160 L170,140 L160,120 Z" fill="#CC0000"/>
                    
                    <!-- Star (Red) -->
                    <path d="M150,20 L157,35 L173,35 L162,45 L167,60 L150,50 L133,60 L138,45 L127,35 L143,35 Z" fill="#CC0000"/>
                </svg>
                <h3>Selamat Datang</h3>
                <p class="text-muted">Masukkan Nama Pengguna dan Kata Sandi untuk melanjutkan.</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">Nama Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               placeholder="Masukkan Nama Pengguna">
                    </div>
                    @error('email')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required
                               placeholder="Masukkan Kata Sandi">
                    </div>
                    @error('password')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Ingat Saya
                    </label>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        Masuk
                    </button>
                </div>
            </form>
            
            <!-- Demo Accounts Info -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">Akun Demo:</h6>
                    <small class="text-muted">
                        <strong>Admin:</strong> admin@pkl.com / password<br>
                        <strong>Siswa:</strong> siswa1@pkl.com / password<br>
                        <strong>Wali Kelas:</strong> walikelas@pkl.com / password<br>
                        <strong>BP:</strong> bp@pkl.com / password<br>
                        <strong>Kaprog:</strong> kaprog@pkl.com / password<br>
                        <strong>TU:</strong> tu@pkl.com / password<br>
                        <strong>Hubin:</strong> hubin@pkl.com / password
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="copyright">
        © 2023 Dikembangkan Oleh TEAM ICT SMK BINA MANDIRI.
    </div>
</body>
</html>