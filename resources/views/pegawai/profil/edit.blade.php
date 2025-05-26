@extends('layouts.app')

@section('content')
<div class="container my-5" x-data="{ showProfileForm: false, showPasswordForm: false }">
    <h3 class="mb-4 fw-bold"><i class="bi bi-person-circle me-2"></i>Profil Saya</h3>

    {{-- Tampilan Info Profil --}}
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Informasi Profil</h5>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-3"><strong class="text-color-override">Nama:</strong></div>
                <div class="col-md-9 text-color-override">{{ $pegawai->nama }}</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-3"><strong class="text-color-override">Email:</strong></div>
                <div class="col-md-9 text-color-override">{{ $pegawai->email ?? '-' }}</div>
            </div>
            <div class="row">
                <div class="col-md-3"><strong class="text-color-override">Alamat:</strong></div>
                <div class="col-md-9 text-color-override">{{ $pegawai->alamat ?? '-' }}</div>
            </div>

            <hr class="my-4 border-secondary opacity-25">

            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm rounded-pill shadow-sm" @click="showProfileForm = !showProfileForm; showPasswordForm = false">
                    <i class="bi bi-pencil-square me-2"></i>Ubah Profil
                </button>
                <button class="btn btn-outline-warning btn-sm rounded-pill shadow-sm" @click="showPasswordForm = !showPasswordForm; showProfileForm = false">
                    <i class="bi bi-key-fill me-2"></i>Ubah Password
                </button>
            </div>
        </div>
    </div>

    <div x-show="showProfileForm" x-transition:enter="animate__animated animate__fadeIn" x-transition:leave="animate__animated animate__fadeOut" class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-fill me-2"></i>Ubah Profil</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('POST')

                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', $pegawai->nama) }}" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                    @error('nama')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $pegawai->email) }}" class="form-control" placeholder="Masukkan Email" required>
                    @error('email')
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

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <button type="submit" class="btn btn-success btn-sm shadow-sm"><i class="bi bi-save-fill me-2"></i>Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary btn-sm shadow-sm" @click="showProfileForm = false"><i class="bi bi-x-circle-fill me-2"></i>Batal</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showPasswordForm" x-transition:enter="animate__animated animate__fadeIn" x-transition:leave="animate__animated animate__fadeOut" class="card shadow-lg">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-lock-fill me-2"></i>Ubah Password</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.password') }}">
                @csrf

                <div class="mb-3">
                    <label for="password_lama" class="form-label fw-semibold">Password Lama <span class="text-danger">*</span></label>
                    <input type="password" name="password_lama" id="password_lama" class="form-control" required>
                    @error('password_lama')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_baru" class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                    <input type="password" name="password_baru" id="password_baru" class="form-control" required>
                    @error('password_baru')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password_baru_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                    <input type="password" name="password_baru_confirmation" id="password_baru_confirmation" class="form-control" required>
                    @error('password_baru_confirmation')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <button type="submit" class="btn btn-warning btn-sm shadow-sm"><i class="bi bi-arrow-repeat me-2"></i>Ubah Password</button>
                    <button type="button" class="btn btn-secondary btn-sm shadow-sm" @click="showPasswordForm = false"><i class="bi bi-x-circle-fill me-2"></i>Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush
@endsection