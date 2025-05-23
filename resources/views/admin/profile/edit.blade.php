@extends('layouts.app')

@section('content')
<div class="container my-5" x-data="{ showEditForm: false, showPasswordForm: false }">
    <h3 class="mb-4 fw-bold"><i class="bi bi-person-circle me-2"></i>Profil Admin</h3>

    {{-- Tampilan Info Admin --}}
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Informasi Profil</h5>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-3"><strong class="text-color-override">Nama:</strong></div>
                <div class="col-md-9 text-color-override">{{ Auth::guard('admin')->user()->nama }}</div>
            </div>
            <div class="row">
                <div class="col-md-3"><strong class="text-color-override">Username:</strong></div>
                <div class="col-md-9 text-color-override">{{ Auth::guard('admin')->user()->username }}</div>
            </div>

            <hr class="my-4">

            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary btn-sm rounded-pill shadow-sm" @click="showEditForm = !showEditForm; showPasswordForm = false">
                    <i class="bi bi-pencil-square me-2"></i>Edit Profil
                </button>
                <button class="btn btn-outline-warning btn-sm rounded-pill shadow-sm" @click="showPasswordForm = !showPasswordForm; showEditForm = false">
                    <i class="bi bi-key-fill me-2"></i>Ubah Password
                </button>
            </div>
        </div>
    </div>

    {{-- Form Edit Profil (Menggunakan Alpine.js) --}}
    <div x-show="showEditForm" x-transition:enter="animate__animated animate__fadeIn" x-transition:leave="animate__animated animate__fadeOut" class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-fill me-2"></i>Edit Profil</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('POST')

                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama <span class="text-danger">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama', Auth::guard('admin')->user()->nama) }}" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                    @error('nama')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                    <input type="text" name="username" id="username" value="{{ old('username', Auth::guard('admin')->user()->username) }}" class="form-control" placeholder="Masukkan Username" required>
                    @error('username')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <button type="submit" class="btn btn-success btn-sm shadow-sm"><i class="bi bi-save-fill me-2"></i>Simpan Perubahan</button> {{-- btn-lg diubah ke btn-sm --}}
                    <button type="button" class="btn btn-secondary btn-sm shadow-sm" @click="showEditForm = false"><i class="bi bi-x-circle-fill me-2"></i>Batal</button> {{-- btn-lg diubah ke btn-sm --}}
                </div>
            </form>
        </div>
    </div>

    {{-- Password Change Form (Menggunakan Alpine.js) --}}
    <div x-show="showPasswordForm" x-transition:enter="animate__animated animate__fadeIn" x-transition:leave="animate__animated animate__fadeOut" class="card shadow-lg">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-lock-fill me-2"></i>Ubah Password</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.profile.password') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="old_password" class="form-label fw-semibold">Password Lama <span class="text-danger">*</span></label>
                    <input type="password" name="old_password" id="old_password" class="form-control" required>
                    @error('old_password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label fw-semibold">Password Baru <span class="text-danger">*</span></label>
                    <input type="password" name="new_password" id="new_password" class="form-control" required>
                    @error('new_password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                    @error('new_password_confirmation')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2 justify-content-end mt-4">
                    <button type="submit" class="btn btn-warning btn-sm shadow-sm"><i class="bi bi-arrow-repeat me-2"></i>Ubah Password</button> {{-- btn-lg diubah ke btn-sm --}}
                    <button type="button" class="btn btn-secondary btn-sm shadow-sm" @click="showPasswordForm = false"><i class="bi bi-x-circle-fill me-2"></i>Batal</button> {{-- btn-lg diubah ke btn-sm --}}
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush
@endsection