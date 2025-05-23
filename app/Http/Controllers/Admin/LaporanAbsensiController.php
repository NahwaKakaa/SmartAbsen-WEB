<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $nama = $request->input('nama');
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $absensis = Absensi::with('pegawai')
            ->when($nama, function($query) use ($nama) {
                $query->whereHas('pegawai', function($q) use ($nama) {
                    $q->where('nama', 'like', '%' . $nama . '%');
                });
            })
            ->when($tanggal_awal && $tanggal_akhir, function($query) use ($tanggal_awal, $tanggal_akhir) {
                $query->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.laporan.index', compact('absensis'));
    }

    public function cetak(Request $request)
    {
        $nama = $request->input('nama');
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        $absensis = Absensi::with('pegawai')
            ->when($nama, function($query) use ($nama) {
                $query->whereHas('pegawai', function($q) use ($nama) {
                    $q->where('nama', 'like', '%' . $nama . '%');
                });
            })
            ->when($tanggal_awal && $tanggal_akhir, function($query) use ($tanggal_awal, $tanggal_akhir) {
                $query->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        $pdf = PDF::loadView('admin.laporan.pdf', compact('absensis'))
                  ->setPaper('A4', 'potrait');

        return $pdf->download('laporan_absensi.pdf');
    }
}


