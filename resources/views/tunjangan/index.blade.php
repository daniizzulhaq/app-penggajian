@extends('layouts.app')
@section('title', 'Data Tunjangan')
@section('page-title', 'Data Tunjangan')

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
    <div class="col-md-4">
        <div class="card border-0 bg-light text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-semibold">{{ $tunjangens->total() }}</div>
                <div class="text-muted small">Total Tunjangan</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-light text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-semibold text-success">
                    {{ $tunjangens->where('jenis', 'tetap')->count() }}
                </div>
                <div class="text-muted small">Tunjangan Tetap</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-light text-center">
            <div class="card-body py-3">
                <div class="fs-3 fw-semibold text-warning">
                    {{ $tunjangens->where('jenis', 'tidak_tetap')->count() }}
                </div>
                <div class="text-muted small">Tunjangan Tidak Tetap</div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel --}}
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-plus-circle me-2"></i>Daftar Tunjangan</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus me-1"></i>Tambah Tunjangan
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Karyawan</th>
                    <th>Nama Tunjangan</th>
                    <th>Jenis</th>
                    <th>Nominal</th>
                    <th>Ditambahkan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tunjangens as $i => $t)
                <tr>
                    <td class="text-muted small">{{ $tunjangens->firstItem() + $i }}</td>
                    <td>
                        <strong>{{ $t->karyawan->nama_lengkap ?? '-' }}</strong>
                        <div class="text-muted small">{{ $t->karyawan->nik ?? '' }}</div>
                    </td>
                    <td>{{ $t->nama_tunjangan }}</td>
                    <td>
                        @if($t->jenis === 'tetap')
                            <span class="badge bg-success bg-opacity-10 text-success">Tetap</span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning">Tidak Tetap</span>
                        @endif
                    </td>
                    <td><strong>Rp {{ number_format($t->nominal, 0, ',', '.') }}</strong></td>
                    <td class="text-muted small">{{ $t->created_at->format('d/m/Y') }}</td>
                    <td>
                        <form action="{{ route('tunjangan.destroy', $t) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus tunjangan ini?')">
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
                        Belum ada data tunjangan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tunjangens->hasPages())
    <div class="card-footer d-flex justify-content-end">
        {{ $tunjangens->links() }}
    </div>
    @endif
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Tunjangan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('tunjangan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Karyawan <span class="text-danger">*</span></label>
                        <select name="karyawan_id" class="form-select @error('karyawan_id') is-invalid @enderror" required>
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
                        <label class="form-label fw-semibold">Nama Tunjangan <span class="text-danger">*</span></label>
                        <input type="text" name="nama_tunjangan"
                               class="form-control @error('nama_tunjangan') is-invalid @enderror"
                               value="{{ old('nama_tunjangan') }}"
                               placeholder="Contoh: Tunjangan Transport" required maxlength="100">
                        @error('nama_tunjangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="tetap" {{ old('jenis') === 'tetap' ? 'selected' : '' }}>Tetap</option>
                            <option value="tidak_tetap" {{ old('jenis') === 'tidak_tetap' ? 'selected' : '' }}>Tidak Tetap</option>
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