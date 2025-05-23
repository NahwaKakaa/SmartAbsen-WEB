<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Absensi Pegawai - {{ $pegawai->nama }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Gaya dasar untuk dokumen PDF */
        body {
            font-family: 'Inter', sans-serif; /* Menggunakan Inter jika tersedia, fallback ke sans-serif */
            font-size: 11px; /* Ukuran font sedikit lebih kecil agar muat */
            line-height: 1.5;
            color: #333; /* Warna teks default */
            margin: 0;
            padding: 20px;
        }
        .container-pdf { /* Kelas kustom untuk container di PDF */
            width: 100%;
            margin: 0 auto;
            padding: 0; /* Padding sudah di body */
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Inter', sans-serif;
            color: #2c3e50; /* Warna heading default */
            margin-top: 0;
            margin-bottom: 10px;
        }
        .h1-pdf { /* Kelas kustom untuk h1 di PDF */
            font-size: 24px;
            text-align: center;
            margin-bottom: 25px;
            color: #0d6efd; /* Warna biru primary */
            font-weight: bold;
        }
        .card-pdf { /* Kelas kustom untuk card di PDF */
            border: 1px solid #e9ecef;
            border-radius: 0.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
        }
        .card-body-pdf { /* Kelas kustom untuk card-body di PDF */
            padding: 1rem;
        }
        .card-title-pdf { /* Kelas kustom untuk card-title di PDF */
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color: #212529;
        }
        /* row-pdf dan col-md-x-pdf tidak lagi digunakan untuk info pegawai */
        .mb-1-pdf { margin-bottom: 0.25rem !important; }
        .mb-3-pdf { margin-bottom: 1rem !important; }
        .mb-4-pdf { margin-bottom: 1.5rem !important; }
        .fw-bold-pdf { font-weight: bold; }

        /* Override gaya Bootstrap untuk tabel agar lebih cocok untuk cetak */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            color: #212529;
        }
        .table > :not(caption) > * > * {
            padding: 0.5rem 0.5rem;
            background-color: transparent; /* Default transparent */
            border-bottom-width: 1px;
            border-color: #dee2e6;
        }
        .table > thead {
            vertical-align: bottom;
        }
        .table > tbody {
            vertical-align: inherit;
        }
        .table > thead > tr > th {
            background-color: #e9ecef;
            color: #212529;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
            text-transform: uppercase;
            border-color: #dee2e6;
        }
        .table-bordered > :not(caption) > * {
            border-width: 1px 0;
        }
        .table-bordered > :not(caption) > * > * {
            border-width: 0 1px;
        }
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(0, 0, 0, 0.05);
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        
        .badge-pdf { /* Kelas kustom untuk badge di PDF */
            display: inline-block;
            padding: .35em .65em;
            font-size: .75em;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
        }
        .badge-success { background-color: #198754; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-secondary { background-color: #6c757d; }
        
        .alert-pdf { /* Kelas kustom untuk alert di PDF */
            position: relative;
            padding: 1rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
            font-size: 11px;
        }
        .alert-info-pdf {
            color: #055160;
            background-color: #cfe2ff;
            border-color: #9ec5fe;
        }
        .alert-heading-pdf {
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: inherit;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container-pdf">
        <h1 class="h1-pdf">RINGKASAN ABSENSI PEGAWAI</h1>

        <div class="card-pdf">
            <div class="card-body-pdf">
                <h5 class="card-title-pdf">Informasi Pegawai</h5>
                <p class="mb-1-pdf"><span class="fw-bold-pdf">Nama Pegawai:</span> {{ $pegawai->nama }}</p>
                <p class="mb-1-pdf"><span class="fw-bold-pdf">ID Pegawai:</span> {{ $pegawai->pegawai_id }}</p>
                <p class="mb-1-pdf"><span class="fw-bold-pdf">Jabatan:</span> {{ $pegawai->jabatan->nama_jabatan }}</p>
                <p class="mb-0-pdf"><span class="fw-bold-pdf">Periode:</span>
                    @if(isset($tanggal_awal) && isset($tanggal_akhir))
                        {{ \Carbon\Carbon::parse($tanggal_awal)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->translatedFormat('d F Y') }}
                    @else
                        Semua Data
                    @endif
                </p>
            </div>
        </div>

        <h4 class="h1-pdf" style="font-size: 16px; text-align: left; margin-bottom: 15px; color: #2c3e50;">Detail Absensi</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th class="text-center">Tanggal</th>
                        <th class="text-center">Jam Datang</th>
                        <th class="text-center">Jam Pulang</th>
                        <th class="text-center">Status</th>
                        <th>Kegiatan</th>
                        {{-- Kolom Foto Datang dan Foto Pulang dihapus --}}
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $absen_record)
                    <tr>
                        <td class="text-center">{{ \Carbon\Carbon::parse($absen_record->tanggal)->translatedFormat('d M Y') }}</td>
                        <td class="text-center">{{ $absen_record->jam_datang ?? '-' }}</td>
                        <td class="text-center">{{ $absen_record->jam_pulang ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge-pdf
                                @if($absen_record->status === 'Tepat Waktu') badge-success
                                @elseif($absen_record->status === 'Terlambat') badge-warning
                                @else badge-secondary
                                @endif
                            ">
                                {{ $absen_record->status }}
                            </span>
                        </td>
                        <td>{{ $absen_record->kegiatan ?? '-' }}</td>
                        {{-- Data Foto Datang dan Foto Pulang dihapus --}}
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center" style="padding: 20px; color: #888;">
                            <div class="alert-pdf alert-info-pdf small mb-0">
                                <h4 class="alert-heading-pdf">Tidak Ada Data Absensi</h4>
                                <p class="mb-0">Tidak ada laporan absensi yang ditemukan untuk periode ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p class="small text-muted mb-0">Laporan dibuat pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i:s') }} WITA</p>
            <p class="small text-muted">Dicetak oleh SmartAbsen</p>
        </div>
    </div>
</body>
</html>
