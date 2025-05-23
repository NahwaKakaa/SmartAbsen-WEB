@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card p-4 mx-auto shadow-lg" style="max-width: 600px; border-radius: 1rem;"> 
        <div class="card-header bg-primary text-white text-center py-3" style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;"> 
            <h3 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Tambah Pegawai Baru</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pegawai.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="pegawai_id" class="form-label fw-semibold">ID Pegawai <span class="text-danger">*</span></label>
                    <input type="text" name="pegawai_id" id="pegawai_id" class="form-control" placeholder="Cth: EMP0001" required>
                    @error('pegawai_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                    @error('nama')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan Email (opsional)">
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                        <option value="" disabled selected>Pilih Jenis Kelamin</option> 
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label fw-semibold">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="4" placeholder="Masukkan Alamat Lengkap"></textarea>
                    @error('alamat')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="jabatan_id" class="form-label fw-semibold">Jabatan <span class="text-danger">*</span></label>
                    <select name="jabatan_id" id="jabatan_id" class="form-select" required>
                        <option value="" disabled selected>Pilih Jabatan</option> 
                        @foreach ($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}">{{ $jabatan->nama_jabatan }}</option>
                        @endforeach
                    </select>
                    @error('jabatan_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex gap-2 justify-content-end mt-4"> 
                    <button type="submit" class="btn btn-success btn-MD shadow-sm"><i class="bi bi-save-fill me-2"></i>Simpan</button>
                    <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary btn-MD shadow-sm"><i class="bi bi-arrow-left me-2"></i>Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
