@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
 
@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card p-3 h-100" style="border-left:4px solid #1e3a5f">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Total Karyawan Aktif</div>
                    <div class="fs-3 fw-bold text-dark">{{ $data['total_karyawan'] }}</div>
                </div>
                <i class="bi bi-people fs-2 text-primary opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 h-100" style="border-left:4px solid #28a745">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Payroll Lunas Bulan Ini</div>
                    <div class="fs-3 fw-bold text-dark">{{ $data['payroll_paid'] }}</div>
                </div>
                <i class="bi bi-check-circle fs-2 text-success opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 h-100" style="border-left:4px solid #ffc107">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Payroll Draft</div>
                    <div class="fs-3 fw-bold text-dark">{{ $data['payroll_draft'] }}</div>
                </div>
                <i class="bi bi-hourglass-split fs-2 text-warning opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card p-3 h-100" style="border-left:4px solid #dc3545">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Total Gaji Dibayar</div>
                    <div class="fs-5 fw-bold text-dark">Rp {{ number_format($data['total_gaji'],0,',','.') }}</div>
                </div>
                <i class="bi bi-cash-coin fs-2 text-danger opacity-50"></i>
            </div>
        </div>
    </div>
</div>
 
<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">🚀 Aksi Cepat</div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('absensi.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-calendar-check me-2"></i>Input Absensi Hari Ini
                </a>
                <a href="{{ route('payroll.index') }}" class="btn btn-outline-success">
                    <i class="bi bi-cash-coin me-2"></i>Kelola Payroll Bulan Ini
                </a>
                <a href="{{ route('karyawan.create') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-person-plus me-2"></i>Tambah Karyawan Baru
                </a>
                <a href="{{ route('laporan.index') }}" class="btn btn-outline-dark">
                    <i class="bi bi-bar-chart me-2"></i>Lihat Laporan Gaji
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">📊 Info Bulan Ini</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr><td class="text-muted">Absensi Tercatat Hari Ini</td><td><strong>{{ $data['absensi_hari_ini'] }} orang</strong></td></tr>
                    <tr><td class="text-muted">Total Payroll Diproses</td><td><strong>{{ $data['total_payroll'] }} slip</strong></td></tr>
                    <tr><td class="text-muted">Status Draft</td><td><span class="badge bg-warning text-dark">{{ $data['payroll_draft'] }}</span></td></tr>
                    <tr><td class="text-muted">Status Lunas</td><td><span class="badge bg-success">{{ $data['payroll_paid'] }}</span></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection