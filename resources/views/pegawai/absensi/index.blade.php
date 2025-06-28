@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h3 class="mb-4 fw-bold"><i class="bi bi-fingerprint me-2"></i>Absensi Pegawai</h3>

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

    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-box-arrow-in-right me-2"></i>Catat Jam Datang</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pegawai.absensi.datang') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Foto Bukti Datang <span class="text-danger">*</span></label>
                    <div class="camera-wrapper" data-type="datang">
                        <div class="camera-container border rounded p-3 bg-light text-center">
                            <button type="button" class="btn btn-primary start-camera" {{ $sudahDatang ? 'disabled' : '' }}><i class="bi bi-camera-fill me-2"></i>Buka Kamera</button>
                            <video width="100%" height="auto" autoplay playsinline class="d-none rounded mt-2"></video>
                            <button type="button" class="btn btn-success click-photo mt-2 d-none"><i class="bi bi-camera-reels-fill me-2"></i>Ambil Foto</button>
                        </div>
                        <div class="preview-container mt-3 text-center d-none">
                            <h6 class="text-muted">Hasil Foto:</h6>
                            <img src="" class="img-fluid rounded shadow-sm" alt="Pratinjau Foto"/>
                            <button type="button" class="btn btn-warning retake-photo w-100 mt-2"><i class="bi bi-arrow-counterclockwise me-2"></i>Ambil Ulang</button>
                        </div>
                        <canvas class="d-none"></canvas>
                        <input type="hidden" name="foto_datang" required>
                    </div>
                     @error('foto_datang')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-sm shadow-sm" {{ $sudahDatang ? 'disabled' : '' }}>
                    <i class="bi bi-check-circle-fill me-2"></i>Catat Jam Datang
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-box-arrow-right me-2"></i>Catat Jam Pulang</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pegawai.absensi.pulang') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Foto Bukti Pulang <span class="text-danger">*</span></label>
                     <div class="camera-wrapper" data-type="pulang">
                        <div class="camera-container border rounded p-3 bg-light text-center">
                            <button type="button" class="btn btn-primary start-camera" {{ !$sudahDatang || $sudahPulang ? 'disabled' : '' }}><i class="bi bi-camera-fill me-2"></i>Buka Kamera</button>
                            <video width="100%" height="auto" autoplay playsinline class="d-none rounded mt-2"></video>
                            <button type="button" class="btn btn-success click-photo mt-2 d-none"><i class="bi bi-camera-reels-fill me-2"></i>Ambil Foto</button>
                        </div>
                        <div class="preview-container mt-3 text-center d-none">
                            <h6 class="text-muted">Hasil Foto:</h6>
                            <img src="" class="img-fluid rounded shadow-sm" alt="Pratinjau Foto"/>
                            <button type="button" class="btn btn-warning retake-photo w-100 mt-2"><i class="bi bi-arrow-counterclockwise me-2"></i>Ambil Ulang</button>
                        </div>
                        <canvas class="d-none"></canvas>
                        <input type="hidden" name="foto_pulang" required>
                    </div>
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
                <button type="submit" class="btn btn-success btn-sm shadow-sm" {{ !$sudahDatang || $sudahPulang ? 'disabled' : '' }}>
                    <i class="bi bi-arrow-right-circle-fill me-2"></i>Catat Jam Pulang
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('DEBUG: Script absensi dimuat DARI DALAM SECTION CONTENT.');

    const streams = {};

    const stopStream = (type) => {
        if (streams[type]) {
            console.log(`DEBUG: Menghentikan stream untuk: ${type}`);
            streams[type].getTracks().forEach(track => track.stop());
            streams[type] = null;
        }
    };

    document.querySelectorAll('.start-camera').forEach(button => {
        button.addEventListener('click', async function() {
            console.log('DEBUG: Tombol "Buka Kamera" diklik.', this);
            if (this.disabled) {
                console.log('DEBUG: Tombol dinonaktifkan.');
                return;
            }

            const wrapper = this.closest('.camera-wrapper');
            if (!wrapper) {
                console.error('DEBUG: Elemen .camera-wrapper tidak ditemukan!');
                return;
            }
            const type = wrapper.dataset.type;
            const video = wrapper.querySelector('video');
            const clickPhotoButton = wrapper.querySelector('.click-photo');
            
            console.log(`DEBUG: Memulai kamera untuk tipe: ${type}`);

            try {
                console.log('DEBUG: Meminta izin media (kamera)...');
                streams[type] = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
                console.log('DEBUG: Berhasil mendapatkan stream kamera:', streams[type]);

                video.srcObject = streams[type];
                video.classList.remove('d-none');
                clickPhotoButton.classList.remove('d-none');
                this.classList.add('d-none');
            } catch (err) {
                console.error(`DEBUG: Error saat getUserMedia untuk ${type}:`, err);
                alert(`Tidak dapat mengakses kamera. Pastikan Anda sudah memberikan izin.\n\nError: ${err.name}`);
            }
        });
    });

    document.querySelectorAll('.click-photo').forEach(button => {
        button.addEventListener('click', function() {
            console.log('DEBUG: Tombol "Ambil Foto" diklik.', this);
            const wrapper = this.closest('.camera-wrapper');
            const type = wrapper.dataset.type;
            const video = wrapper.querySelector('video');
            const canvas = wrapper.querySelector('canvas');
            const photoPreview = wrapper.querySelector('.preview-container img');
            const hiddenInput = wrapper.querySelector('input[type="hidden"]');
            const cameraContainer = wrapper.querySelector('.camera-container');
            const previewContainer = wrapper.querySelector('.preview-container');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.translate(canvas.width, 0);
            context.scale(-1, 1);
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const dataUrl = canvas.toDataURL('image/jpeg');
            
            photoPreview.src = dataUrl;
            hiddenInput.value = dataUrl;

            previewContainer.classList.remove('d-none');
            cameraContainer.classList.add('d-none');
            
            stopStream(type);
        });
    });

    document.querySelectorAll('.retake-photo').forEach(button => {
        button.addEventListener('click', function() {
            console.log('DEBUG: Tombol "Ambil Ulang" diklik.', this);
            const wrapper = this.closest('.camera-wrapper');
            const type = wrapper.dataset.type;
            const hiddenInput = wrapper.querySelector('input[type="hidden"]');
            const cameraContainer = wrapper.querySelector('.camera-container');
            const previewContainer = wrapper.querySelector('.preview-container');
            const startCameraButton = wrapper.querySelector('.start-camera');

            previewContainer.classList.add('d-none');
            cameraContainer.classList.remove('d-none');
            startCameraButton.classList.remove('d-none');
            hiddenInput.value = '';

            stopStream(type);
        });
    });
});
</script>
@endsection
