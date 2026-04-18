@extends('layouts.app')

@section('title', 'Rekap Gaji Karyawan')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Rekap Gaji Karyawan</h4>
    </div>

    {{-- Filter --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('laporan.rekap') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tahun</label>
                    <select name="tahun" class="form-select">
                        @for ($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Karyawan</label>
                    <select name="karyawan_id" class="form-select">
                        <option value="">-- Semua Karyawan --</option>
                        @foreach ($karyawans as $k)
                            <option value="{{ $k->id }}" {{ $karyawan_id == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Rekap --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-semibold">Rekap Tahun {{ $tahun }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Karyawan</th>
                            <th>Bulan</th>
                            <th>Gaji Pokok</th>
                            <th>Total Tunjangan</th>
                            <th>Total Potongan</th>
                            <th>Gaji Bersih</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $i => $p)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $p->karyawan->nama_lengkap ?? '-' }}</td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::create((int) $p->tahun, (int) $p->bulan, 1)->translatedFormat('F') }}
                            </td>
                            <td class="text-end">Rp {{ number_format($p->gaji_pokok, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($p->total_tunjangan, 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($p->total_potongan, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($p->gaji_bersih, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @php
                                    $badge = match($p->status) {
                                        'disetujui' => 'success',
                                        'pending'   => 'warning',
                                        'ditolak'   => 'danger',
                                        default     => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($p->status) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                Tidak ada data rekap untuk filter yang dipilih.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if ($data->count() > 0)
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="3" class="text-center">TOTAL</td>
                            <td class="text-end">Rp {{ number_format($data->sum('gaji_pokok'), 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($data->sum('total_tunjangan'), 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($data->sum('total_potongan'), 0, ',', '.') }}</td>
                            <td class="text-end">Rp {{ number_format($data->sum('gaji_bersih'), 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

</div>

@php \Carbon\Carbon::setLocale('id'); @endphp
@endsection