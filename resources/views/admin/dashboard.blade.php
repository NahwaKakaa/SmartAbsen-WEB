@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- Pesan Selamat Datang --}}
    <div class="welcome-jumbotron mb-5 animate__animated animate__fadeInDown">
        <h1 class="mb-3">Selamat Datang, {{ Auth::guard('admin')->user()->nama }}!</h1>
        <p class="lead">Pantau dan kelola absensi karyawan Anda dengan mudah.</p>
    </div>

    {{-- Statistik Atas (Jumlah Pegawai, Absensi Hari Ini, Terlambat Hari Ini) --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-start border-primary border-5 shadow-lg h-100 animate__animated animate__fadeInUp" style="cursor: pointer; transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon-wrapper stat-icon-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title text-muted mb-0">Jumlah Pegawai</h5>
                            <h3 class="fw-bold display-4">{{ $pegawaiCount }}</h3>
                        </div>
                    </div>
                    <small class="text-muted mt-3">Data terbaru</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start border-success border-5 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-1s" style="cursor: pointer; transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon-wrapper stat-icon-success">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title text-muted mb-0">Absensi Hari Ini</h5>
                            <h3 class="fw-bold display-4">{{ $absensiHariIni }}</h3>
                        </div>
                    </div>
                    <small class="text-muted mt-3">Absensi yang tercatat</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start border-warning border-5 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-2s" style="cursor: pointer; transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon-wrapper stat-icon-warning">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title text-muted mb-0">Terlambat Hari Ini</h5>
                            <h3 class="fw-bold display-4">{{ $terlambatHariIni }}</h3>
                        </div>
                    </div>
                    <small class="text-muted mt-3">Pegawai yang terlambat</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistik Baru (Tidak Absen dan Tepat Waktu Hari Ini) --}}
    {{-- Judul untuk seksi ini jika diperlukan --}}
    <h4 class="mb-3 fw-bold animate__animated animate__fadeInUp animate__delay-2s">Status Kehadiran Lainnya Hari Ini</h4>
    <div class="row g-4 mb-4">
        <div class="col-lg-6 col-md-6"> {{-- Menggunakan col-lg-6 agar lebih lebar jika hanya ada 2 --}}
            <div class="card border-start border-danger border-5 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-3s" style="cursor: pointer; transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon-wrapper stat-icon-danger">
                            <i class="bi bi-person-x-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title text-muted mb-0">Tidak Absen Hari Ini</h5>
                            <h3 class="fw-bold display-4">{{ $jumlahTidakAbsenHariIni ?? 0 }}</h3>
                        </div>
                    </div>
                    <small class="text-muted mt-3">Pegawai belum melakukan absensi</small>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="card border-start border-info border-5 shadow-lg h-100 animate__animated animate__fadeInUp animate__delay-4s" style="cursor: pointer; transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon-wrapper stat-icon-info">
                            <i class="bi bi-alarm-fill"></i>
                        </div>
                        <div>
                            <h5 class="card-title text-muted mb-0">Tepat Waktu Hari Ini</h5>
                            <h3 class="fw-bold display-4">{{ $jumlahTepatWaktuHariIni ?? 0 }}</h3>
                        </div>
                    </div>
                    <small class="text-muted mt-3">Pegawai hadir tepat waktu</small>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- Animate.css jika masih ingin digunakan untuk efek --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function getCssVariable(variable) {
        // Pastikan document.documentElement ada sebelum memanggil getComputedStyle
        if (document.documentElement) {
            return getComputedStyle(document.documentElement).getPropertyValue(variable).trim();
        }
        return null; // atau nilai default
    }

    // Efek hover untuk kartu statistik (dengan shadow adaptif)
    // Pastikan variabel --card-shadow-hover-light dan --card-shadow-hover-dark ada di theme.css
    document.querySelectorAll('.card[style*="cursor: pointer"]').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-8px)';
            const isDarkMode = document.body.classList.contains('dark-mode');
            const hoverShadowDark = getCssVariable('--card-shadow-hover-dark');
            const hoverShadowLight = getCssVariable('--card-shadow-hover-light');
            
            if (isDarkMode && hoverShadowDark) {
                card.style.boxShadow = hoverShadowDark;
            } else if (!isDarkMode && hoverShadowLight) {
                card.style.boxShadow = hoverShadowLight;
            } else {
                // Fallback jika variabel tidak ditemukan
                card.style.boxShadow = isDarkMode ? '0 0.75rem 1.5rem rgba(0,0,0,.25)' : '0 0.75rem 1.5rem rgba(0,0,0,.15)';
            }
        });
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
            const defaultCardShadow = getCssVariable('--card-shadow');
            card.style.boxShadow = defaultCardShadow || '0 0.125rem 0.25rem rgba(0,0,0,.075)'; // Fallback
        });
    });
});
</script>
@endpush