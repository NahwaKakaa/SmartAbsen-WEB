@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- Pesan Selamat Datang dan Info Ringkas Pegawai --}}
    <div class="welcome-jumbotron mb-5 animate__animated animate__fadeInDown">
        <h1 class="mb-3">Selamat Datang, {{ Auth::guard('pegawai')->user()->nama }}!</h1>
        <p class="lead text-muted">Kelola absensi Anda dengan mudah dan efisien.</p>

        <hr class="my-4 border-secondary opacity-25"> {{-- Garis pemisah yang lebih halus --}}

        <div class="row text-start justify-content-center">
            <div class="col-md-8 col-lg-6"> {{-- Lebar kolom disesuaikan --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="text-color-override">ID Pegawai:</strong>
                    <span class="text-color-override">{{ Auth::guard('pegawai')->user()->pegawai_id }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong class="text-color-override">Jabatan:</strong>
                    <span class="text-color-override">{{ Auth::guard('pegawai')->user()->jabatan->nama_jabatan }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-0">
                    <strong class="text-color-override">Email:</strong>
                    <span class="text-color-override">{{ Auth::guard('pegawai')->user()->email ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Absensi Ringkas --}}
    <h3 class="mb-4 fw-bold text-center"><i class="bi bi-bar-chart-fill me-2"></i>Ringkasan Absensi Anda</h3>
    <div class="row g-4 mb-5">
        {{-- Total Absensi --}}
        <div class="col-md-4">
            <div class="card border-start border-primary border-5 shadow-lg h-100 animate__animated animate__fadeInUp" style="cursor: pointer; transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon-wrapper stat-icon-primary">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title text-muted mb-0">Total Absensi</h5>
                            <h3 class="fw-bold display-4 text-color-override">{{ $totalAbsensi }}</h3> {{-- Pastikan teks adaptif --}}
                        </div>
                    </div>
                    <small class="text-muted mt-3">Semua absensi tercatat</small>
                </div>
            </div>
        </div>

        {{-- Absensi Hari Ini --}}
        <div class="col-md-4">
            <div class="card border-start border-success border-5 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-1s" style="cursor: pointer; transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon-wrapper stat-icon-success">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title text-muted mb-0">Absensi Hari Ini</h5>
                            <h3 class="fw-bold display-4 text-color-override">{{ $absensiHariIni }}</h3> {{-- Pastikan teks adaptif --}}
                        </div>
                    </div>
                    <small class="text-muted mt-3">Absensi Anda hari ini</small>
                </div>
            </div>
        </div>

        {{-- Status Absensi Terakhir --}}
        <div class="col-md-4">
            <div class="card border-start border-info border-5 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-2s" style="cursor: pointer; transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon-wrapper stat-icon-primary">
                            <i class="bi bi-info-circle-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title text-muted mb-0">Status Terakhir</h5>
                            {{-- Perbaikan: Menggunakan ukuran font yang lebih kecil untuk badge agar lebih rapi --}}
                            <h3 class="fw-bold display-6"> {{-- Mengubah display-4 menjadi display-6 --}}
                                @if($lastStatus)
                                    <span class="badge rounded-pill px-3 py-2
                                        @if($lastStatus === 'Tepat Waktu') bg-success
                                        @elseif($lastStatus === 'Terlambat') bg-warning
                                        @else bg-secondary
                                        @endif
                                    ">
                                        {{ $lastStatus }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </h3>
                        </div>
                    </div>
                    <small class="text-muted mt-3">Status absensi terakhir Anda</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Tombol Akses Cepat --}}
    <h3 class="mb-4 fw-bold text-center"><i class="bi bi-lightning-charge-fill me-2"></i>Akses Cepat</h3>
    <div class="row g-4 justify-content-center">
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('pegawai.absensi.index') }}" class="card text-center p-4 shadow-lg h-100 d-flex flex-column justify-content-center align-items-center text-decoration-none animate__animated animate__zoomIn" style="transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
                <i class="bi bi-fingerprint display-2 text-primary mb-3"></i> {{-- Ukuran ikon lebih besar --}}
                <h4 class="fw-bold mb-1 text-color-override">Lakukan Absensi</h4> {{-- Pastikan teks adaptif --}}
                <p class="text-muted mb-0">Catat kehadiran Anda hari ini.</p>
            </a>
        </div>
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('pegawai.absensi.history') }}" class="card text-center p-4 shadow-lg h-100 d-flex flex-column justify-content-center align-items-center text-decoration-none animate__animated animate__zoomIn animate__delay-1s" style="transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
                <i class="bi bi-journal-text display-2 text-success mb-3"></i> {{-- Ukuran ikon lebih besar --}}
                <h4 class="fw-bold mb-1 text-color-override">Riwayat Absensi</h4> {{-- Pastikan teks adaptif --}}
                <p class="text-muted mb-0">Lihat semua catatan absensi Anda.</p>
            </a>
        </div>
    </div>
</div>

@push('scripts')
{{-- Animate.css untuk efek fade-in --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Efek hover untuk kartu statistik dan akses cepat
        document.querySelectorAll('.card[style*="cursor: pointer"], .card.text-center').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-8px)';
                card.style.boxShadow = '0 0.75rem 1.5rem rgba(0,0,0,.2)'; /* Bayangan lebih besar saat hover */
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = 'var(--card-shadow)'; /* Kembali ke shadow default */
            });
        });
    });
</script>
@endpush
@endsection