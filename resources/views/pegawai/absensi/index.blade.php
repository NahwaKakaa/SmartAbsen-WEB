@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h3 class="mb-4 fw-bold"><i class="bi bi-fingerprint me-2"></i>Absensi Pegawai</h3>

    {{-- Pesan Sukses/Error dari Controller --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Status Absensi Hari Ini --}}
    <div class="card shadow-lg mb-5">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-check-fill me-2"></i>Status Absensi Hari Ini ({{ \Carbon\Carbon::now()->translatedFormat('d F Y') }})</h5>
        </div>
        <div class="card-body text-center py-4">
            @if($sudahPulang)
                <h4 class="text-success fw-bold mb-3"><i class="bi bi-check-circle-fill me-2"></i>Anda Sudah Absen Datang & Pulang Hari Ini!</h4>
                <p class="text-muted">Absensi Anda untuk hari ini telah lengkap.</p>
            @elseif($sudahDatang)
                <h4 class="text-warning fw-bold mb-3"><i class="bi bi-exclamation-triangle-fill me-2"></i>Anda Sudah Absen Datang, Belum Absen Pulang.</h4>
                <p class="text-muted">Jangan lupa untuk mencatat jam pulang Anda nanti.</p>
            @else
                <h4 class="text-info fw-bold mb-3"><i class="bi bi-info-circle-fill me-2"></i>Anda Belum Absen Datang Hari Ini.</h4>
                <p class="text-muted">Silakan catat jam datang Anda.</p>
            @endif
        </div>
    </div>

    {{-- Form Absen Datang --}}
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-box-arrow-in-right me-2"></i>Catat Jam Datang</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pegawai.absensi.datang') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="foto_datang" class="form-label fw-semibold">Foto Bukti Datang <span class="text-danger">*</span></label>
                    <input type="file" name="foto_datang" id="foto_datang" class="form-control" required {{ $sudahDatang ? 'disabled' : '' }}>
                    @error('foto_datang')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-sm shadow-sm" {{ $sudahDatang ? 'disabled' : '' }}> {{-- btn-lg diubah ke btn-sm --}}
                    <i class="bi bi-check-circle-fill me-2"></i>Catat Jam Datang
                </button>
            </form>
        </div>
    </div>

    {{-- Form Absen Pulang --}}
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-box-arrow-right me-2"></i>Catat Jam Pulang</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pegawai.absensi.pulang') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="foto_pulang" class="form-label fw-semibold">Foto Bukti Pulang <span class="text-danger">*</span></label>
                    <input type="file" name="foto_pulang" id="foto_pulang" class="form-control" required {{ !$sudahDatang || $sudahPulang ? 'disabled' : '' }}>
                    @error('foto_pulang')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="kegiatan_pulang" class="form-label fw-semibold">Uraian Kegiatan Pulang <span class="text-danger">*</span></label>
                    <textarea name="kegiatan_pulang" id="kegiatan_pulang" class="form-control" rows="4" maxlength="500" placeholder="Contoh: Menyelesaikan laporan bulanan dan merapikan meja kerja." required {{ !$sudahDatang || $sudahPulang ? 'disabled' : '' }}></textarea>
                    @error('kegiatan_pulang')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success btn-sm shadow-sm" {{ !$sudahDatang || $sudahPulang ? 'disabled' : '' }}> {{-- btn-lg diubah ke btn-sm --}}
                    <i class="bi bi-arrow-right-circle-fill me-2"></i>Catat Jam Pulang
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
