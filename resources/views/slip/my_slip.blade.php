@extends('layouts.app')
@section('title', 'Slip Gaji Saya')
@section('page-title', 'Slip Gaji Saya')
 
@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-receipt me-2"></i>Riwayat Slip Gaji</div>
    <div class="card-body">
        @if(!$karyawan)
            <div class="alert alert-warning">Data karyawan Anda belum terdaftar. Hubungi HRD.</div>
        @elseif($payrolls->isEmpty())
            <p class="text-muted text-center py-4">Belum ada slip gaji tersedia.</p>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Periode</th><th>Gaji Pokok</th><th>Tunjangan</th><th>Potongan</th><th>Gaji Bersih</th><th>Status</th><th>Download</th></tr>
                </thead>
                <tbody>
                    @foreach($payrolls as $p)
                    <tr>
                        <td><strong>{{ $p->nama_bulan }} {{ $p->tahun }}</strong></td>
                        <td>Rp {{ number_format($p->gaji_pokok,0,',','.') }}</td>
                        <td class="text-success">+Rp {{ number_format($p->total_tunjangan+$p->uang_lembur,0,',','.') }}</td>
                        <td class="text-danger">-Rp {{ number_format($p->total_potongan+$p->potongan_alpha,0,',','.') }}</td>
                        <td><strong>Rp {{ number_format($p->gaji_bersih,0,',','.') }}</strong></td>
                        <td><span class="badge bg-{{ $p->status === 'paid' ? 'success' : 'info' }}">{{ ucfirst($p->status) }}</span></td>
                        <td>
                            <a href="{{ route('slip.cetak', $p) }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="bi bi-file-pdf me-1"></i>PDF
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $payrolls->links() }}
        @endif
    </div>
</div>
@endsection