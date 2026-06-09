@extends('layouts.app')
@section('title','Edit Karyawan')
@section('page-title','Edit Data Karyawan')

@section('content')
<div class="card">
    <div class="card-header"><i class="bi bi-person-gear me-2"></i>Form Edit Karyawan</div>
    <div class="card-body">
        <form method="POST" action="{{ route('karyawan.update', $karyawan->id) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">NIK <span class="text-danger">*</span></label>
                    <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik', $karyawan->nik) }}" required>
                    @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control @error('nama_lengkap') is-invalid @enderror" value="{{ old('nama_lengkap', $karyawan->nama_lengkap) }}" required>
                    @error('nama_lengkap')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $karyawan->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Password Baru <small class="text-muted fw-normal">(kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                    <select name="jabatan_id" class="form-select @error('jabatan_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach($jabatans as $j)
                            <option value="{{ $j->id }}" @selected(old('jabatan_id', $karyawan->jabatan_id) == $j->id)>
                                {{ $j->nama_jabatan }} (Rp {{ number_format($j->gaji_pokok,0,',','.') }})
                            </option>
                        @endforeach
                    </select>
                    @error('jabatan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Departemen <span class="text-danger">*</span></label>
                    <select name="departement_id" class="form-select @error('departement_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Departemen --</option>
                        @foreach($departements as $d)
                            <option value="{{ $d->id }}" @selected(old('departement_id', $karyawan->departement_id) == $d->id)>{{ $d->nama_departement }}</option>
                        @endforeach
                    </select>
                    @error('departement_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="L" @selected(old('jenis_kelamin', $karyawan->jenis_kelamin)=='L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin', $karyawan->jenis_kelamin)=='P')>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Masuk <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" value="{{ old('tanggal_masuk', $karyawan->tanggal_masuk) }}" required>
                    @error('tanggal_masuk')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">No. Telepon</label>
                    <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp', $karyawan->no_telp) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Gaji Pokok <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="gaji_pokok" class="form-control @error('gaji_pokok') is-invalid @enderror" value="{{ old('gaji_pokok', $karyawan->gaji_pokok) }}" min="0" required>
                    </div>
                    @error('gaji_pokok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="aktif"    @selected(old('status', $karyawan->status)=='aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('status', $karyawan->status)=='nonaktif')>Nonaktif</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $karyawan->alamat) }}</textarea>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection