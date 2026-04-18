@extends('layouts.app')
@section('title','Jabatan')
@section('page-title','Master Jabatan')
 
@section('content')
<div class="row g-3">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">+ Tambah Jabatan</div>
            <div class="card-body">
                <form method="POST" action="{{ route('jabatan.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Jabatan</label>
                        <input type="text" name="nama_jabatan" class="form-control @error('nama_jabatan') is-invalid @enderror" value="{{ old('nama_jabatan') }}" required>
                        @error('nama_jabatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Gaji Pokok Default</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="gaji_pokok" class="form-control" value="{{ old('gaji_pokok',0) }}" min="0">
                        </div>
                    </div>
                    <button class="btn btn-primary w-100"><i class="bi bi-plus me-1"></i>Tambah</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-diagram-2 me-2"></i>Daftar Jabatan</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Nama Jabatan</th><th>Gaji Pokok</th><th>Karyawan</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse($jabatans as $j)
                        <tr>
                            <td><strong>{{ $j->nama_jabatan }}</strong></td>
                            <td>Rp {{ number_format($j->gaji_pokok,0,',','.') }}</td>
                            <td><span class="badge bg-secondary">{{ $j->karyawans_count }}</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-warning"
                                    onclick="editJabatan({{ $j->id }}, '{{ $j->nama_jabatan }}', {{ $j->gaji_pokok }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form method="POST" action="{{ route('jabatan.destroy', $j) }}" class="d-inline"
                                      onsubmit="return confirm('Hapus jabatan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">Belum ada jabatan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
 
{{-- Modal Edit --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Edit Jabatan</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jabatan</label>
                        <input type="text" name="nama_jabatan" id="editNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gaji Pokok</label>
                        <input type="number" name="gaji_pokok" id="editGaji" class="form-control" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
 
@push('scripts')
<script>
function editJabatan(id, nama, gaji) {
    document.getElementById('editForm').action = '/jabatan/' + id;
    document.getElementById('editNama').value = nama;
    document.getElementById('editGaji').value = gaji;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
@endpush
 