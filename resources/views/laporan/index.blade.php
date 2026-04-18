@extends('layouts.app')
@section('title','Laporan Gaji')
@section('page-title','Laporan Gaji Karyawan')
 
@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Bulan</label>
                <select name="bulan" class="form-select">
                    @for($i=1;$i<=12;$i++)
                        <option value="{{ $i }}" @selected($i == $bulan)>{{ DateTime::createFromFormat('!m',$i)->format('F') }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Tahun</label>
                <input type="number" name="tahun" class="form-control" value="{{ $tahun }}">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Filter</button>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('laporan.pdf', ['bulan'=>$bulan,'tahun'=>$tahun]) }}" class="btn btn-danger">
                    <i class="bi bi-file-pdf me-1"></i>Export PDF
                </a>
            </div>
        </form>
    </div>
</div>
 
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card p-3 text-center" style="border-left:4px solid #1e3a5f">
            <div class="text-muted small">Total Gaji Bersih</div>
            <div class="fw-bold fs-5">Rp {{ number_format($totalGaji,0,',','.') }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center" style="border-left:4px solid #28a745">
            <div class="text-muted small">Total Tunjangan</div>
            <div class="fw-bold fs-5 text-success">Rp {{ number_format($totalTunjangan,0,',','.') }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card p-3 text-center" style="border-left:4px solid #dc3545">
            <div class="text-muted small">Total Potongan</div>
            <div class="fw-bold fs-5 text-danger">Rp {{ number_format($totalPotongan,0,',','.') }}</div>
        </div>
    </div>
</div>
 
<div class="card">
    <div class="card-header">📊 Rekap Gaji — {{ DateTime::createFromFormat('!m',$bulan)->format('F') }} {{ $tahun }}</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th><th>NIK</th><th>Nama</th><th>Jabatan</th>
                        <th>Gaji Pokok</th><th>Tunjangan</th><th>Potongan</th>
                        <th>Gaji Bersih</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $i => $p)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td><code>{{ $p->karyawan->nik }}</code></td>
                        <td>{{ $p->karyawan->nama_lengkap }}</td>
                        <td class="small">{{ $p->karyawan->jabatan->nama_jabatan }}</td>
                        <td>{{ number_format($p->gaji_pokok,0,',','.') }}</td>
                        <td class="text-success">{{ number_format($p->total_tunjangan+$p->uang_lembur,0,',','.') }}</td>
                        <td class="text-danger">{{ number_format($p->total_potongan+$p->potongan_alpha,0,',','.') }}</td>
                        <td><strong>Rp {{ number_format($p->gaji_bersih,0,',','.') }}</strong></td>
                        <td><span class="badge bg-{{ $p->status==='paid'?'success':($p->status==='approved'?'info':'warning text-dark') }}">{{ ucfirst($p->status) }}</span></td>
                    </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data laporan untuk periode ini.</td></tr>
                    @endforelse
                </tbody>
                @if($payrolls->isNotEmpty())
                <tfoot class="table-dark">
                    <tr>
                        <td colspan="4" class="text-end fw-bold">TOTAL</td>
                        <td>{{ number_format($payrolls->sum('gaji_pokok'),0,',','.') }}</td>
                        <td>{{ number_format($payrolls->sum('total_tunjangan'),0,',','.') }}</td>
                        <td>{{ number_format($payrolls->sum('total_potongan'),0,',','.') }}</td>
                        <td><strong>Rp {{ number_format($totalGaji,0,',','.') }}</strong></td>
                        <td>{{ $payrolls->count() }} slip</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection