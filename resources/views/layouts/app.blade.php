<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Penggajian')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 260px; --sidebar-bg: #1e3a5f; --accent: #2e6da4; }
        body { background: #f0f4f8; font-family: 'Segoe UI', sans-serif; }
        .sidebar { position: fixed; top:0; left:0; width: var(--sidebar-width); height:100vh;
                   background: var(--sidebar-bg); overflow-y:auto; z-index:1000; transition:.3s; }
        .sidebar .brand { padding: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar .brand h6 { color:#fff; font-weight:700; margin:0; font-size:1rem; }
        .sidebar .brand small { color:rgba(255,255,255,0.5); font-size:0.75rem; }
        .nav-section { padding: 8px 16px 4px; color:rgba(255,255,255,0.4); font-size:0.65rem;
                       text-transform:uppercase; letter-spacing:1.5px; font-weight:600; }
        .sidebar .nav-link { color:rgba(255,255,255,0.75); padding:10px 20px; border-radius:8px;
                             margin:2px 8px; font-size:0.88rem; transition:.2s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background:rgba(255,255,255,0.15); color:#fff; }
        .sidebar .nav-link i { width:20px; margin-right:8px; }
        .main-content { margin-left: var(--sidebar-width); min-height:100vh; }
        .topbar { background:#fff; padding:12px 24px; border-bottom:1px solid #e2e8f0;
                  display:flex; justify-content:space-between; align-items:center; position:sticky; top:0; z-index:999; }
        .content-area { padding: 24px; }
        .card { border:none; border-radius:12px; box-shadow:0 1px 8px rgba(0,0,0,0.07); }
        .card-header { background:transparent; border-bottom:1px solid #f0f4f8; font-weight:600; padding:16px 20px; }
        .badge-role { padding:4px 10px; border-radius:20px; font-size:0.72rem; font-weight:600; }
        .bg-admin { background:#e3f0ff; color:#1e3a5f; }
        .bg-hrd   { background:#e8f5e9; color:#1b5e20; }
        .bg-kary  { background:#fff3e0; color:#e65100; }
        @media(max-width:768px){
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left:0; }
        }
    </style>
    @stack('styles')
</head>
<body>
 
{{-- SIDEBAR --}}
<div class="sidebar" id="sidebar">
    <div class="brand d-flex align-items-center gap-2">
        <div>
            <h6>Sistem Penggajian</h6>
            <h6>PT. Sinar Indo Busana</h6>
        </div>
    </div>
 
    <nav class="mt-3">
        <div class="nav-section">Umum</div>
        <a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
 
        @if(auth()->user()->isAdmin())
            <div class="nav-section mt-2">Data Master</div>
            <a href="{{ route('karyawan.index') }}" class="nav-link @if(request()->routeIs('karyawan.*')) active @endif">
                <i class="bi bi-people"></i> Karyawan
            </a>
            <a href="{{ route('jabatan.index') }}" class="nav-link @if(request()->routeIs('jabatan.*')) active @endif">
                <i class="bi bi-diagram-2"></i> Jabatan
            </a>
            <a href="{{ route('departement.index') }}" class="nav-link @if(request()->routeIs('departement.*')) active @endif">
                <i class="bi bi-building"></i> Departemen
            </a>
            <a href="{{ route('tunjangan.index') }}" class="nav-link @if(request()->routeIs('tunjangan.*')) active @endif">
                <i class="bi bi-plus-circle"></i> Tunjangan
            </a>
            <a href="{{ route('potongan.index') }}" class="nav-link @if(request()->routeIs('potongan.*')) active @endif">
                <i class="bi bi-dash-circle"></i> Potongan
            </a>
 
            <div class="nav-section mt-2">Operasional</div>
            <a href="{{ route('absensi.index') }}" class="nav-link @if(request()->routeIs('absensi.*')) active @endif">
                <i class="bi bi-calendar-check"></i> Absensi
            </a>
            <a href="{{ route('payroll.index') }}" class="nav-link @if(request()->routeIs('payroll.*')) active @endif">
                <i class="bi bi-cash-coin"></i> Payroll
            </a>
 
            <div class="nav-section mt-2">Laporan</div>
            <a href="{{ route('laporan.index') }}" class="nav-link @if(request()->routeIs('laporan.*')) active @endif">
                <i class="bi bi-bar-chart"></i> Laporan Gaji
            </a>
            <a href="{{ route('laporan.rekap') }}" class="nav-link">
                <i class="bi bi-file-earmark-text"></i> Rekap Tahunan
            </a>
        @endif
 
        @if(auth()->user()->isKaryawan())
            <div class="nav-section mt-2">Gaji Saya</div>
            <a href="{{ route('slip.my') }}" class="nav-link @if(request()->routeIs('slip.*')) active @endif">
                <i class="bi bi-receipt"></i> Slip Gaji
            </a>
        @endif
    </nav>
</div>
 
{{-- MAIN CONTENT --}}
<div class="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h6 class="mb-0 fw-semibold text-dark">@yield('page-title', 'Dashboard')</h6>
        </div>
        <div class="d-flex align-items-center gap-3">
            @php $role = auth()->user()->role; @endphp
            <span class="badge-role {{ $role === 'admin' ? 'bg-admin' : ($role === 'hrd' ? 'bg-hrd' : 'bg-kary') }}">
                {{ strtoupper($role) }}
            </span>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:36px;height:36px;font-size:0.85rem;font-weight:600;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="d-none d-md-inline text-dark fw-semibold small">{{ auth()->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text small text-muted">{{ auth()->user()->email }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
 
    <div class="content-area">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
 
        @yield('content')
    </div>
</div>
 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
 