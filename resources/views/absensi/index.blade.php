@extends('layouts.app')
@section('title', 'Absensi')
@section('page-title', 'Kelola Absensi')
 
@section('content')
<div class="row g-3">
    {{-- Form Input Absensi --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">📋 Input Absensi</div>
            <div class="card-body">
                <form method="POST" action="{{ route('absensi.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Karyawan</label>
                        <select name="karyawan_id" class="form-select" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawans as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpha">Alpha</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jam Lembur</label>
                        <input type="number" name="jam_lembur" class="form-control" step="0.5" min="0" max="24" placeholder="0" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2" placeholder="Opsional..."></textarea>
                    </div>
                    <button class="btn btn-primary w-100"><i class="bi bi-save me-1"></i>Simpan Absensi</button>
                </form>
 
                <hr>
                <p class="small fw-semibold">Input Massal (Semua Karyawan Hadir)</p>
                <form method="POST" action="{{ route('absensi.bulk') }}">
                    @csrf
                    <div class="input-group">
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        <button class="btn btn-outline-success" type="submit">Hadir Semua</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
 
    {{-- Tabel Absensi --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <form method="GET" class="row g-2 align-items-end">
                    <div class="col">
                        <select name="bulan" class="form-select form-select-sm">
                            @for($i=1;$i<=12;$i++)
                                <option value="{{ $i }}" @selected($i == $bulan)>
                                    {{ DateTime::createFromFormat('!m',$i)->format('F') }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col">
                        <input type="number" name="tahun" class="form-control form-control-sm" value="{{ $tahun }}">
                    </div>
                    <div class="col">
                        <select name="karyawan_id" class="form-select form-select-sm">
                            <option value="">Semua Karyawan</option>
                            @foreach($karyawans as $k)
                                <option value="{{ $k->id }}" @selected(request('karyawan_id') == $k->id)>{{ $k->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Karyawan</th>
                                <th>Status</th>
                                <th>Lembur</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensis as $a)
                            <tr>
                                <td>{{ $a->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $a->karyawan->nama_lengkap }}</td>
                                <td>
                                    @php $badges = ['hadir'=>'success','izin'=>'info','sakit'=>'warning','alpha'=>'danger']; @endphp
                                    <span class="badge bg-{{ $badges[$a->status] }}">{{ ucfirst($a->status) }}</span>
                                </td>
                                <td>{{ $a->jam_lembur > 0 ? $a->jam_lembur.' jam' : '-' }}</td>
                                <td>
                                    <form action="{{ route('absensi.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-3">Tidak ada data absensi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $absensis->withQueryString()->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection