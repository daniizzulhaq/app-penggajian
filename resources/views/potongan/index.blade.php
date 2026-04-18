@extends('layouts.app')
@section('title', 'Data Potongan')
@section('page-title', 'Data Potongan')

@section('content')

{{-- Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-0 bg-light text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-semibold">{{ $potongans->total() }}</div>
                <div class="text-muted small">Total Potongan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-light text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-semibold text-info">
                    {{ $potongans->getCollection()->where('jenis', 'bpjs_kes')->count() }}
                </div>
                <div class="text-muted small">BPJS Kesehatan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-light text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-semibold text-primary">
                    {{ $potongans->getCollection()->where('jenis', 'bpjs_tk')->count() }}
                </div>
                <div class="text-muted small">BPJS Ketenagakerjaan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-light text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-semibold text-danger">
                    {{ $potongans->getCollection()->where('jenis', 'pph21')->count() }}
                </div>
                <div class="text-muted small">PPh 21</div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel --}}
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-dash-circle me-2"></i>Daftar Potongan</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus me-1"></i>Tambah Potongan
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Karyawan</th>
                    <th>Nama Potongan</th>
                    <th>Jenis</th>
                    <th>Nominal</th>
                    <th>Ditambahkan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($potongans as $i => $p)
                <tr>
                    <td class="text-muted small">{{ $potongans->firstItem() + $i }}</td>
                    <td>
                        <strong>{{ $p->karyawan->nama_lengkap ?? '-' }}</strong>
                        <div class="text-muted small">{{ $p->karyawan->nik ?? '' }}</div>
                    </td>
                    <td>{{ $p->nama_potongan }}</td>
                    <td>
                        @php
                            $jenisBadge = [
                                'bpjs_kes' => ['bg-info bg-opacity-10 text-info',        'BPJS Kesehatan'],
                                'bpjs_tk'  => ['bg-primary bg-opacity-10 text-primary',  'BPJS Ketenagakerjaan'],
                                'pph21'    => ['bg-danger bg-opacity-10 text-danger',     'PPh 21'],
                                'lainnya'  => ['bg-secondary bg-opacity-10 text-secondary','Lainnya'],
                            ];
                            [$cls, $label] = $jenisBadge[$p->jenis] ?? ['bg-secondary bg-opacity-10 text-secondary', $p->jenis];
                        @endphp
                        <span class="badge {{ $cls }}">{{ $label }}</span>
                    </td>
                    <td><strong class="text-danger">Rp {{ number_format($p->nominal, 0, ',', '.') }}</strong></td>
                    <td class="text-muted small">{{ $p->created_at->format('d/m/Y') }}</td>
                    <td>
                        <form action="{{ route('potongan.destroy', $p) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus potongan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                        Belum ada data potongan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($potongans->hasPages())
    <div class="card-footer d-flex justify-content-end">
        {{ $potongans->links() }}
    </div>
    @endif
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Potongan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('potongan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Karyawan <span class="text-danger">*</span></label>
                        <select name="karyawan_id"
                                class="form-select @error('karyawan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawans as $k)
                                <option value="{{ $k->id }}" {{ old('karyawan_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_lengkap }} ({{ $k->nik }})
                                </option>
                            @endforeach
                        </select>
                        @error('karyawan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Potongan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_potongan"
                               class="form-control @error('nama_potongan') is-invalid @enderror"
                               value="{{ old('nama_potongan') }}"
                               placeholder="Contoh: BPJS Kesehatan 1%" required maxlength="100">
                        @error('nama_potongan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                        <select name="jenis"
                                class="form-select @error('jenis') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="bpjs_kes" {{ old('jenis') === 'bpjs_kes' ? 'selected' : '' }}>BPJS Kesehatan</option>
                            <option value="bpjs_tk"  {{ old('jenis') === 'bpjs_tk'  ? 'selected' : '' }}>BPJS Ketenagakerjaan</option>
                            <option value="pph21"    {{ old('jenis') === 'pph21'    ? 'selected' : '' }}>PPh 21</option>
                            <option value="lainnya"  {{ old('jenis') === 'lainnya'  ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('jenis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nominal <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="nominal"
                                   class="form-control @error('nominal') is-invalid @enderror"
                                   value="{{ old('nominal') }}"
                                   placeholder="0" min="0" required>
                            @error('nominal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus me-1"></i>Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    @if($errors->any())
        new bootstrap.Modal(document.getElementById('modalTambah')).show();
    @endif
</script>
@endpush