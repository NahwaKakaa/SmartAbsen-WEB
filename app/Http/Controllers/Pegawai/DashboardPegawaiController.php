<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi; // Asumsi Anda memiliki model Absensi
use App\Models\Jabatan; // Asumsi Anda memiliki model Jabatan (jika diperlukan untuk relasi)
use Carbon\Carbon; // Untuk bekerja dengan tanggal dan waktu

class DashboardPegawaiController extends Controller // Nama kelas diubah di sini!
{
    /**
     * Menampilkan dashboard pegawai.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mendapatkan instance pegawai yang sedang login
        $pegawai = Auth::guard('pegawai')->user();

        // Pastikan objek $pegawai tidak null sebelum mengakses propertinya
        if (!$pegawai) {
            // Jika pegawai tidak ditemukan (misalnya sesi kedaluwarsa), arahkan ke login
            return redirect()->route('login');
        }

        // --- Ambil Data Statistik Absensi Pegawai ---

        // 1. Total Absensi
        // Menghitung semua catatan absensi untuk pegawai yang sedang login
        $totalAbsensi = Absensi::where('pegawai_id', $pegawai->id)->count();

        // 2. Absensi Hari Ini
        // Menghitung absensi pegawai untuk tanggal hari ini
        $absensiHariIni = Absensi::where('pegawai_id', $pegawai->id)
                                ->whereDate('tanggal', Carbon::today())
                                ->count();

        // 3. Status Absensi Terakhir
        // Mencari catatan absensi terakhir untuk pegawai ini
        // Mengambil yang terbaru berdasarkan tanggal, lalu jam pulang (atau jam datang jika pulang null)
        $lastAbsensi = Absensi::where('pegawai_id', $pegawai->id)
                                ->latest('tanggal')
                                ->latest('jam_pulang')
                                ->first();

        // Menentukan status terakhir
        $lastStatus = null;
        if ($lastAbsensi) {
            $lastStatus = $lastAbsensi->status; // Ambil status dari absensi terakhir
        }

        // Mengirim data ke view
        return view('pegawai.dashboard', compact('totalAbsensi', 'absensiHariIni', 'lastStatus'));
    }
}
