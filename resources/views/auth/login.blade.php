{{-- ================================================================ --}}
{{-- FILE: resources/views/auth/login.blade.php --}}
{{-- ================================================================ --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Penggajian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #1e3a5f 0%, #2e6da4 100%); min-height: 100vh; }
        .card { border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        .logo-icon { font-size: 3rem; color: #1e3a5f; }
        .btn-login { background: #1e3a5f; border: none; padding: 12px; font-weight: 600; letter-spacing: 0.5px; }
        .btn-login:hover { background: #2e6da4; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card p-5">
                    <div class="text-center mb-4">
                        
                        <h4 class="fw-bold mt-2 text-dark">Sistem Penggajian</h4>
                        <h4 class="fw-bold mt-2 text-dark">PT. Sinar Indo Busana</h4>
                        <p class="text-muted small">Masuk ke akun Anda</p>
                    </div>
 
                    @if($errors->any())
                        <div class="alert alert-danger small py-2">
                            {{ $errors->first() }}
                        </div>
                    @endif
 
                    @if(session('success'))
                        <div class="alert alert-success small py-2">{{ session('success') }}</div>
                    @endif
 
                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" placeholder="email@contoh.com" required autofocus>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Ingat saya</label>
                        </div>
                        <button type="submit" class="btn btn-login btn-primary w-100 text-white">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                        </button>
                    </form>
                </div>
                <p class="text-center text-white mt-3 small opacity-75">&copy; {{ date('Y') }} Sistem Penggajian Karyawan</p>
            </div>
        </div>
    </div>
</body>
</html>