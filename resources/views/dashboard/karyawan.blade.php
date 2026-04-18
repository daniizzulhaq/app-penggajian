@extends('layouts.app')
@section('title', 'Dashboard Karyawan')
@section('page-title', 'Dashboard')
 
@section('content')
@if($karyawan)
<div class="card mb-4 p-4" style="background:linear-gradient(135deg,#1e3a5f,#2e6da4);color:white;border-radius:16px;">
    <h5 class="mb-1">Selamat Datang, {{ $karyawan->nama_lengkap }}!</h5>
    <div class="small opacity-75">NIK: {{ $karyawan->nik }} | {{ $karyawan->jabatan->nama_jabatan }} | {{ $karyawan->departement->nama_departement }}</div>
    <div class="mt-2">Gaji Pokok: <strong>Rp {{ number_format($karyawan->gaji_pokok,0,',','.') }}</strong></div>
</div>
@endif
 
<div class="card">
    <div class="card-header">📄 Slip Gaji Terbaru</div>
    <div class="card-body">
        @if($payrolls->isEmpty())
            <p class="text-muted text-center py-3">Belum ada slip gaji tersedia.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Periode</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Potongan</th>
                            <th>Gaji Bersih</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payrolls as $p)
                        <tr>
                            <td><strong>{{ $p->nama_bulan }} {{ $p->tahun }}</strong></td>
                            <td>Rp {{ number_format($p->gaji_pokok,0,',','.') }}</td>
                            <td class="text-success">+Rp {{ number_format($p->total_tunjangan,0,',','.') }}</td>
                            <td class="text-danger">-Rp {{ number_format($p->total_potongan + $p->potongan_alpha,0,',','.') }}</td>
                            <td><strong>Rp {{ number_format($p->gaji_bersih,0,',','.') }}</strong></td>
                            <td>
                                @if($p->status === 'paid')
                                    <span class="badge bg-success">Lunas</span>
                                @elseif($p->status === 'approved')
                                    <span class="badge bg-info">Disetujui</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('slip.cetak', $p->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="bi bi-download"></i> Unduh
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
 