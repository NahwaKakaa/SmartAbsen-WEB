<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi Pegawai</title>
    <style>
        body {
            font-family: 'Inter', sans-serif; /* Menggunakan Inter jika tersedia */
            font-size: 11px; /* Ukuran font sedikit lebih kecil agar muat */
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h1 {
            font-size: 24px;
            color: #0d6efd; /* Warna biru Bootstrap primary */
            margin-bottom: 5px;
        }
        .header p {
            font-size: 13px;
            color: #666;
            margin: 0;
        }
        .info-section {
            margin-bottom: 20px;
            padding: 10px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        .info-section strong {
            color: #555;
        }
        .info-section p {
            margin: 3px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Sedikit bayangan pada tabel */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px; /* Padding lebih besar */
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #e9ecef; /* Latar belakang header tabel */
            color: #333;
            font-weight: bold;
            text-align: center;
            font-size: 11px;
            text-transform: uppercase; /* Huruf kapital */
        }
        tbody tr:nth-child(even) {
            background-color: #f8f8f8; /* Warna baris ganjil */
        }
        tbody tr:hover {
            background-color: #e0e0e0; /* Efek hover ringan */
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 10px;
            font-weight: bold;
            line-height: 1;
            border-radius: 12px; /* Lebih membulat */
            color: #fff;
            text-align: center;
            white-space: nowrap;
        }
        .badge-success { background-color: #198754; } /* Bootstrap success */
        .badge-warning { background-color: #ffc107; color: #212529; } /* Bootstrap warning, teks gelap */
        .badge-secondary { background-color: #6c757d; } /* Bootstrap secondary */
        /* img-thumbnail-pdf tidak lagi digunakan karena foto dihapus */
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
    <div class="header">
        <h1>LAPORAN ABSENSI PEGAWAI</h1>
        <p>Ringkasan Riwayat Absensi Karyawan</p>
    </div>

    <div class="info-section">
        <p><strong>Nama Pegawai:</strong> {{ $absen->pegawai->nama ?? 'N/A' }}</p> {{-- Asumsi data pegawai bisa diakses dari $absen jika ini laporan single --}}
        <p><strong>ID Pegawai:</strong> {{ $absen->pegawai->pegawai_id ?? 'N/A' }}</p>
        <p><strong>Jabatan:</strong> {{ $absen->pegawai->jabatan->nama_jabatan ?? 'N/A' }}</p>
        <p><strong>Periode Laporan:</strong>
            @if(isset($tanggal_awal) && isset($tanggal_akhir))
                {{ \Carbon\Carbon::parse($tanggal_awal)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($tanggal_akhir)->translatedFormat('d F Y') }}
            @else
                Semua Data
            @endif
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jam Datang</th>
                <th>Jam Pulang</th>
                <th>Status</th>
                <th>Kegiatan</th>
                {{-- Kolom Foto Datang dan Foto Pulang dihapus --}}
            </tr>
        </thead>
        <tbody>
            @forelse ($absensis as $absen_record) {{-- Ubah nama variabel loop agar tidak konflik --}}
            <tr>
                <td class="text-center">{{ \Carbon\Carbon::parse($absen_record->tanggal)->translatedFormat('d M Y') }}</td>
                <td class="text-center">{{ $absen_record->jam_datang ?? '-' }}</td>
                <td class="text-center">{{ $absen_record->jam_pulang ?? '-' }}</td>
                <td class="text-center">
                    <span class="badge
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
                <td colspan="5" class="text-center" style="padding: 20px; color: #888;">Tidak ada data absensi untuk periode ini.</td> {{-- Colspan disesuaikan dari 7 menjadi 5 --}}
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan dibuat pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i:s') }} WITA</p>
        <p>Dicetak oleh SmartAbsen</p>
    </div>
</body>
</html>
