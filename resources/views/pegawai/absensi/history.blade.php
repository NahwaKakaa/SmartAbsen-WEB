@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 fw-bold"><i class="bi bi-journal-text me-2"></i>Riwayat Absensi Pegawai</h3>
        <a href="{{ route('pegawai.absensi.download', request()->all()) }}" class="btn btn-success btn-sm rounded-pill shadow-sm">
            <i class="bi bi-file-earmark-arrow-down-fill me-2"></i>Unduh PDF
        </a>
    </div>

    {{-- Filter Form --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-funnel-fill me-2"></i>Filter Riwayat</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('pegawai.absensi.history') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
                </div>
                <div class="col-md-4">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill btn-sm shadow-sm"><i class="bi bi-search me-2"></i>Filter</button>
                    <a href="{{ route('pegawai.absensi.history') }}" class="btn btn-secondary flex-fill btn-sm shadow-sm"><i class="bi bi-arrow-counterclockwise me-2"></i>Reset Filter</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Riwayat Absensi --}}
    <div class="card shadow-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Datang</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th class="text-center">Foto Datang</th>
                            <th class="text-center">Foto Pulang</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($absensis as $absen)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($absen->tanggal)->translatedFormat('d F Y') }}</td>
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
                            <td class="text-center">
                                @if($absen->foto_datang)
                                    {{-- PERBAIKAN DI SINI: Tambahkan 'storage/' --}}
                                    <a href="{{ asset('storage/' . $absen->foto_datang) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $absen->foto_datang) }}" alt="Foto Datang" width="60" class="img-thumbnail rounded shadow-sm">
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($absen->foto_pulang)
                                    {{-- PERBAIKAN DI SINI: Tambahkan 'storage/' --}}
                                    <a href="{{ asset('storage/' . $absen->foto_pulang) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $absen->foto_pulang) }}" alt="Foto Pulang" width="60" class="img-thumbnail rounded shadow-sm">
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="alert alert-info text-center py-3 shadow-sm rounded-3 mb-0">
                                    <h4 class="alert-heading"><i class="bi bi-info-circle-fill me-2"></i>Belum Ada Data Absensi</h4>
                                    <p class="mb-0">Tidak ada riwayat absensi yang ditemukan untuk Anda atau kriteria filter yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
