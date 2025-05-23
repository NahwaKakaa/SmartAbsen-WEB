@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card p-4 mx-auto shadow-lg" style="max-width: 600px; border-radius: 1rem;">
        <div class="card-header bg-primary text-white text-center py-3" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
            <h3 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Data Pegawai</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pegawai.update', $pegawai->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="pegawai_id" class="form-label fw-semibold">ID Pegawai <span class="text-danger">*</span></label>
                    <input type="text" name="pegawai_id" id="pegawai_id" class="form-control" value="{{ old('pegawai_id', $pegawai->pegawai_id) }}" placeholder="Masukkan ID Pegawai" required>
                    @error('pegawai_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $pegawai->nama) }}" placeholder="Masukkan Nama Lengkap" required>
                    @error('nama')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $pegawai->email) }}" placeholder="Masukkan Email (opsional)">
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                        <option value="" disabled>Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $pegawai->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="4" placeholder="Masukkan Alamat Lengkap">{{ old('alamat', $pegawai->alamat) }}</textarea>
                    @error('alamat')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="jabatan_id" class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                    <select name="jabatan_id" id="jabatan_id" class="form-select" required>
                        <option value="" disabled>Pilih Jabatan</option>
                        @foreach ($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}" {{ old('jabatan_id', $pegawai->jabatan_id) == $jabatan->id ? 'selected' : '' }}>{{ $jabatan->nama_jabatan }}</option>
                        @endforeach
                    </select>
                    @error('jabatan_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex gap-2 justify-content-end mt-4">
                    <button type="submit" class="btn btn-success btn-md shadow-sm"><i class="bi bi-save-fill me-2"></i>Simpan Perubahan</button>
                    <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary btn-md shadow-sm"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
