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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - PKL SMK BINA MANDIRI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(120deg, #6366F1 0%, #8B5CF6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .card-login {
            width: 100%;
            max-width: 420px;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(99,102,241,0.15);
            overflow: hidden;
            background: #fff;
            padding: 32px 28px 24px 28px;
            position: relative;
        }
        .logo {
            width: 180px;
            height: 150px;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 500;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            transition: box-shadow 0.2s;
        }
        .form-control:focus {
            box-shadow: 0 0 0 2px #6366F1;
            border-color: #6366F1;
        }
        .btn-primary {
            background: linear-gradient(90deg, #6366F1 0%, #8B5CF6 100%);
            border: none;
            border-radius: 8px;
            font-weight: 600;
            padding: 10px;
            transition: background 0.2s;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #4F46E5 0%, #6366F1 100%);
        }
        .input-group-text {
            background: #f3f4f6;
            border-radius: 8px 0 0 8px;
            border: 1px solid #e5e7eb;
        }
        .demo-info {
            background: #f3f4f6;
            border-radius: 8px;
            padding: 12px;
            font-size: 13px;
            margin-top: 18px;
        }
        .copyright {
            position: fixed;
            bottom: 10px;
            left: 0;
            width: 100vw;
            text-align: center;
            color: #fff;
            font-size: 14px;
            z-index: 10;
            text-shadow: 0 2px 8px rgba(99,102,241,0.2);
        }
        @media (max-width: 600px) {
            .card-login {
                padding: 18px 8px 12px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="card-login mx-auto">
        <div class="card shadow-sm mb-3 border-0" style="background: linear-gradient(120deg, #f3f4f6 60%, #e0e7ff 100%); border-radius: 16px;">
            <div class="card-body p-3 text-center">
                <img src="{{ asset('logos.png') }}" alt="Logo SMK BINA MANDIRI" class="logo">
                <h4 class="fw-bold mb-2" style="color:#6366F1;">Tentang Sistem PKL</h4>
                <p class="mb-0 text-secondary" style="font-size:15px;">
                    Selamat datang di Sistem Pendaftaran Permohonan PKL SMK BINA MANDIRI.<br>
                    Silakan login menggunakan akun yang telah diberikan untuk mengakses fitur dan informasi terkait PKL.<br>
                    Sistem ini membantu Anda memantau, mengelola, dan melaporkan kegiatan PKL secara mudah dan efisien.<br>
                    Jika mengalami kendala login, silakan hubungi admin sekolah.
                </p>
            </div>
        </div>
        <div class="text-center mb-3">
            <h4 class="fw-bold mb-1">Login PKL</h4>
        </div>
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Nama Pengguna</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Masukkan Nama Pengguna">
                </div>
                @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Masukkan Kata Sandi">
                </div>
                @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-2 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Ingat Saya</label>
            </div>
            <div class="d-grid mt-3">
                <button type="submit" class="btn btn-primary">Masuk</button>
            </div>
        </form>
        <div class="demo-info mt-3">
            <strong>Akun Demo:</strong><br>
            <span><strong>Admin:</strong> admin@pkl.com / password</span><br>
            <span><strong>Siswa:</strong> siswa1@pkl.com / password</span><br>
            <span><strong>Wali Kelas:</strong> walikelas@pkl.com / password</span><br>
            <span><strong>BP:</strong> bp@pkl.com / password</span><br>
            <span><strong>Kaprog:</strong> kaprog@pkl.com / password</span><br>
            <span><strong>TU:</strong> tu@pkl.com / password</span><br>
            <span><strong>Hubin:</strong> hubin@pkl.com / password</span>
        </div>
    </div>
    <div class="copyright">
        © 2025 Dikembangkan Oleh TEAM ICT SMK BINA MANDIRI.
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>