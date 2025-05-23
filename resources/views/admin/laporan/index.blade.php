@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h3 class="mb-4 fw-bold"><i class="bi bi-file-earmark-bar-graph-fill me-2"></i>Laporan Absensi Pegawai</h3>

    {{-- Filter Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light py-3"> {{-- Gunakan bg-light agar adaptif melalui CSS kustom --}}
            <h5 class="mb-0 fw-bold"><i class="bi bi-funnel-fill me-2"></i>Filter Laporan</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="nama" class="form-label">Nama Pegawai</label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Cari Nama Pegawai" value="{{ request('nama') }}">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                </div>
                <div class="col-md-3">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill shadow-sm"><i class="bi bi-search me-2"></i>Filter</button>
                    <a href="{{ route('admin.laporan.cetak', request()->all()) }}" class="btn btn-success flex-fill shadow-sm"><i class="bi bi-file-earmark-arrow-down-fill me-2"></i>Unduh PDF</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Absensi Per Tanggal --}}
    @php
        $grouped = $absensis->groupBy('tanggal');
    @endphp

    @forelse($grouped as $tanggal => $list)
    <div class="card mb-4 shadow-lg"> {{-- shadow-lg untuk bayangan lebih kuat --}}
        <div class="card-header bg-light fw-bold py-3"> {{-- Gunakan bg-light agar adaptif melalui CSS kustom --}}
            Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                {{-- Tambahkan kelas table-striped di sini --}}
                <table class="table table-striped table-bordered table-hover mb-0 align-middle">
                    <thead> {{-- Hapus table-light agar adaptif --}}
                        <tr>
                            <th>Nama</th>
                            <th>Jam Datang</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th>Kegiatan</th>
                            <th class="text-center">Foto Datang</th>
                            <th class="text-center">Foto Pulang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($list as $absen)
                        <tr>
                            <td>{{ $absen->pegawai->nama }}</td>
                            <td>{{ $absen->jam_datang ?? '-' }}</td>
                            <td>{{ $absen->jam_pulang ?? '-' }}</td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2
                                    @if($absen->status === 'Tepat Waktu') bg-success
                                    @elseif($absen->status === 'Terlambat') bg-warning
                                    @else bg-secondary
                                    @endif
                                ">
                                    {{ $absen->status }}
                                </span>
                            </td>
                            <td>{{ $absen->kegiatan ?? '-' }}</td>
                            <td class="text-center">
                                @if($absen->foto_datang)
                                    <a href="{{ asset('uploads/absensi/' . $absen->foto_datang) }}" target="_blank">
                                        <img src="{{ asset('uploads/absensi/' . $absen->foto_datang) }}" alt="Foto Datang" width="60" class="img-thumbnail rounded shadow-sm">
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($absen->foto_pulang)
                                    <a href="{{ asset('uploads/absensi/' . $absen->foto_pulang) }}" target="_blank">
                                        <img src="{{ asset('uploads/absensi/' . $absen->foto_pulang) }}" alt="Foto Pulang" width="60" class="img-thumbnail rounded shadow-sm">
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @empty
        <div class="alert alert-info text-center py-4 shadow-sm rounded-3">
            <h4 class="alert-heading"><i class="bi bi-info-circle-fill me-2"></i>Tidak Ada Data Absensi</h4>
            <p class="mb-0">Tidak ada laporan absensi yang ditemukan untuk kriteria filter yang dipilih.</p>
        </div>
    @endforelse
</div>
@endsection
