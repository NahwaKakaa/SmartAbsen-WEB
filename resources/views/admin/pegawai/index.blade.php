@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Daftar Pegawai</h3>
        {{-- Mengubah btn-lg menjadi btn-md --}}
        <a href="{{ route('pegawai.create') }}" class="btn btn-primary btn-md shadow-sm">
            <i class="bi bi-person-plus-fill me-2"></i>Tambah Pegawai
        </a>
    </div>

    <div class="card shadow-lg p-3">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered align-middle">
                    <thead>
                        <tr>
                            <th scope="col">ID Pegawai</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Jabatan</th>
                            <th scope="col">Jenis Kelamin</th>
                            <th scope="col" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pegawais as $pegawai)
                        <tr>
                            <td>{{ $pegawai->pegawai_id }}</td>
                            <td>{{ $pegawai->nama }}</td>
                            <td>{{ $pegawai->jabatan->nama_jabatan }}</td>
                            <td>{{ $pegawai->jenis_kelamin }}</td>
                            <td class="text-center text-nowrap">
                                <a href="{{ route('pegawai.edit', $pegawai->id) }}" class="btn btn-warning btn-sm me-2 rounded-pill shadow-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>

                                <form action="{{ route('pegawai.destroy', $pegawai->id) }}" method="POST" class="d-inline" data-confirm-message="Yakin ingin menghapus pegawai ini?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm me-2 rounded-pill shadow-sm">
                                        <i class="bi bi-trash-fill"></i> Hapus
                                    </button>
                                </form>

                                <form action="{{ route('pegawai.reset', $pegawai->id) }}" method="POST" class="d-inline" data-confirm-message="Yakin ingin me-reset password pegawai ini ke default?">
                                    @csrf
                                    <button type="submit" class="btn btn-info btn-sm rounded-pill shadow-sm">
                                        <i class="bi bi-key-fill"></i> Reset Password
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Tidak ada data pegawai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
