@extends('layouts.app')
@section('title', 'Data Departemen')
@section('page-title', 'Data Departemen')

@section('content')

{{-- Alert --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-center border-0 bg-light">
            <div class="card-body py-3">
                <div class="fs-3 fw-semibold">{{ $departements->count() }}</div>
                <div class="text-muted small">Total Departemen</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0 bg-light">
            <div class="card-body py-3">
                <div class="fs-3 fw-semibold">{{ $departements->sum('karyawans_count') }}</div>
                <div class="text-muted small">Total Karyawan</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center border-0 bg-light">
            <div class="card-body py-3">
                <div class="fs-3 fw-semibold text-success">{{ $departements->where('karyawans_count', '>', 0)->count() }}</div>
                <div class="text-muted small">Dept Terisi</div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel + Form Tambah --}}
<div class="card shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-building me-2"></i>Daftar Departemen</span>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus me-1"></i>Tambah Departemen
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:40px">#</th>
                    <th>Kode</th>
                    <th>Nama Departemen</th>
                    <th>Jumlah Karyawan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departements as $i => $dept)
                <tr>
                    <td class="text-muted small">{{ $i + 1 }}</td>
                    <td><code>{{ $dept->kode_departement }}</code></td>
                    <td><strong>{{ $dept->nama_departement }}</strong></td>
                    <td>
                        @if($dept->karyawans_count > 0)
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $dept->karyawans_count }} karyawan
                            </span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                Kosong
                            </span>
                        @endif
                    </td>
                    <td>
                        {{-- Tombol Edit --}}
                        <button class="btn btn-sm btn-outline-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEdit{{ $dept->id }}">
                            <i class="bi bi-pencil"></i> Edit
                        </button>

                        {{-- Tombol Hapus --}}
                        @if($dept->karyawans_count == 0)
                            <form action="{{ route('departement.destroy', $dept) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Hapus departemen {{ $dept->nama_departement }}?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </form>
                        @else
                            <button class="btn btn-sm btn-outline-danger" disabled title="Masih ada karyawan">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        @endif
                    </td>
                </tr>

                {{-- Modal Edit (per baris) --}}
                <div class="modal fade" id="modalEdit{{ $dept->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title">Edit Departemen</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('departement.update', $dept) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nama Departemen <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_departement" class="form-control"
                                               value="{{ $dept->nama_departement }}" required maxlength="100">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Kode Departemen <span class="text-danger">*</span></label>
                                        <input type="text" name="kode_departement" class="form-control text-uppercase"
                                               value="{{ $dept->kode_departement }}" required maxlength="10">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="bi bi-check-lg me-1"></i>Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                        Belum ada data departemen.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Departemen</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('departement.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Departemen <span class="text-danger">*</span></label>
                        <input type="text" name="nama_departement" class="form-control @error('nama_departement') is-invalid @enderror"
                               value="{{ old('nama_departement') }}" placeholder="Contoh: Information Technology" required maxlength="100">
                        @error('nama_departement')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Departemen <span class="text-danger">*</span></label>
                        <input type="text" name="kode_departement" class="form-control text-uppercase @error('kode_departement') is-invalid @enderror"
                               value="{{ old('kode_departement') }}" placeholder="Contoh: IT-001" required maxlength="10">
                        <div class="form-text">Maks. 10 karakter, harus unik.</div>
                        @error('kode_departement')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
    // Auto buka modal tambah jika ada validation error
    @if($errors->any())
        var modal = new bootstrap.Modal(document.getElementById('modalTambah'));
        modal.show();
    @endif
</script>
@endpush