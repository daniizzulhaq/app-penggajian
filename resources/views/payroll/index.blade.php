@extends('layouts.app')
@section('title', 'Payroll')
@section('page-title', 'Manajemen Payroll')
 
@section('content')
{{-- Generate Payroll --}}
<div class="card mb-4">
    <div class="card-header">⚡ Generate Payroll</div>
    <div class="card-body">
        <form method="POST" action="{{ route('payroll.generate') }}" class="row g-3 align-items-end"
              onsubmit="return confirm('Generate payroll untuk periode ini? Data absensi akan dihitung ulang.')">
            @csrf
            <div class="col-md-3">
                <label class="form-label fw-semibold">Bulan</label>
                <select name="bulan" class="form-select">
                    @for($i=1;$i<=12;$i++)
                        <option value="{{ $i }}" @selected($i == $bulan)>
                            {{ DateTime::createFromFormat('!m',$i)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">Tahun</label>
                <input type="number" name="tahun" class="form-control" value="{{ $tahun }}">
            </div>
            <div class="col-md-3">
                <button class="btn btn-success w-100">
                    <i class="bi bi-play-circle me-1"></i>Generate Payroll
                </button>
            </div>
        </form>
    </div>
</div>
 
{{-- Filter & Summary --}}
<div class="card mb-3">
    <div class="card-body">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <form method="GET" class="d-flex gap-2">
                    <select name="bulan" class="form-select form-select-sm">
                        @for($i=1;$i<=12;$i++)
                            <option value="{{ $i }}" @selected($i == $bulan)>{{ DateTime::createFromFormat('!m',$i)->format('F') }}</option>
                        @endfor
                    </select>
                    <input type="number" name="tahun" class="form-control form-control-sm" value="{{ $tahun }}" style="width:90px">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="draft"    @selected(request('status')=='draft')>Draft</option>
                        <option value="approved" @selected(request('status')=='approved')>Approved</option>
                        <option value="paid"     @selected(request('status')=='paid')>Lunas</option>
                    </select>
                    <button class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <form method="POST" action="{{ route('payroll.approveAll') }}" class="d-inline"
                      onsubmit="return confirm('Approve semua payroll draft?')">
                    @csrf
                    <input type="hidden" name="bulan" value="{{ $bulan }}">
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <button class="btn btn-info btn-sm text-white"><i class="bi bi-check-all me-1"></i>Approve Semua</button>
                </form>
                <form method="POST" action="{{ route('payroll.paidAll') }}" class="d-inline"
                      onsubmit="return confirm('Tandai semua approved sebagai LUNAS?')">
                    @csrf
                    <input type="hidden" name="bulan" value="{{ $bulan }}">
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <button class="btn btn-success btn-sm"><i class="bi bi-cash me-1"></i>Lunas Semua</button>
                </form>
            </div>
        </div>
    </div>
</div>
 
{{-- Summary Cards --}}
<div class="row g-2 mb-3">
    @foreach(['draft'=>['warning','Menunggu'], 'approved'=>['info','Disetujui'], 'paid'=>['success','Lunas']] as $s=>[$c,$l])
    <div class="col-md-4">
        <div class="card p-3 text-center border-0" style="background:var(--bs-{{$c}}-bg-subtle,#f8f9fa)">
            <div class="fw-bold text-{{ $c }}">{{ $l }}</div>
            <div class="fs-4 fw-bold">{{ $summary[$s]->jumlah ?? 0 }} slip</div>
            <div class="small text-muted">Rp {{ number_format($summary[$s]->total ?? 0,0,',','.') }}</div>
        </div>
    </div>
    @endforeach
</div>
 
{{-- Tabel Payroll --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Karyawan</th>
                        <th>Departemen</th>
                        <th>Hadir/Hari Kerja</th>
                        <th>Gaji Pokok</th>
                        <th>Tunjangan</th>
                        <th>Potongan</th>
                        <th>Gaji Bersih</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $p)
                    <tr>
                        <td>
                            <strong>{{ $p->karyawan->nama_lengkap }}</strong>
                            <div class="text-muted small">{{ $p->karyawan->nik }}</div>
                        </td>
                        <td class="small">{{ $p->karyawan->departement->nama_departement }}</td>
                        <td class="text-center">{{ $p->hari_hadir }}/{{ $p->hari_kerja }}
                            @if($p->hari_alpha > 0)
                                <span class="badge bg-danger ms-1">{{ $p->hari_alpha }}α</span>
                            @endif
                        </td>
                        <td>{{ number_format($p->gaji_pokok,0,',','.') }}</td>
                        <td class="text-success">+{{ number_format($p->total_tunjangan+$p->uang_lembur,0,',','.') }}</td>
                        <td class="text-danger">-{{ number_format($p->total_potongan+$p->potongan_alpha,0,',','.') }}</td>
                        <td><strong>Rp {{ number_format($p->gaji_bersih,0,',','.') }}</strong></td>
                        <td>
                            @if($p->status === 'draft')
                                <span class="badge bg-warning text-dark">Draft</span>
                            @elseif($p->status === 'approved')
                                <span class="badge bg-info">Approved</span>
                            @else
                                <span class="badge bg-success">Lunas</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('slip.cetak', $p) }}" target="_blank" class="btn btn-xs btn-outline-primary btn-sm"><i class="bi bi-receipt"></i></a>
                            @if($p->status === 'draft')
                                <form method="POST" action="{{ route('payroll.approve', $p) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-info" title="Approve"><i class="bi bi-check"></i></button>
                                </form>
                            @elseif($p->status === 'approved')
                                <form method="POST" action="{{ route('payroll.paid', $p) }}" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success" title="Lunas"><i class="bi bi-cash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">Belum ada payroll untuk periode ini. Klik "Generate Payroll" untuk memulai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $payrolls->withQueryString()->links() }}</div>
    </div>
</div>
@endsection