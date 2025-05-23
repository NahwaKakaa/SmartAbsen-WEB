<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi; // Asumsi Anda memiliki model Absensi
use Carbon\Carbon; // Untuk bekerja dengan tanggal dan waktu
use Illuminate\Support\Facades\Storage; // Untuk upload file

class AbsensiController extends Controller
{
    /**
     * Menampilkan halaman absensi.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Mendapatkan pegawai yang sedang login
        $pegawai = Auth::guard('pegawai')->user();
        
        // Cek apakah pegawai sudah absen datang hari ini
        $absensiHariIni = Absensi::where('pegawai_id', $pegawai->id)
                                ->whereDate('tanggal', Carbon::today())
                                ->first();

        // Tentukan status tombol absensi
        $sudahDatang = $absensiHariIni && $absensiHariIni->jam_datang !== null;
        $sudahPulang = $absensiHariIni && $absensiHariIni->jam_pulang !== null;

        return view('pegawai.absensi.index', compact('sudahDatang', 'sudahPulang'));
    }

    /**
     * Memproses absensi datang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function datang(Request $request)
    {
        $pegawai = Auth::guard('pegawai')->user();
        $today = Carbon::today();

        // Validasi input
        $request->validate([
            'foto_datang' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
            // Hapus validasi kegiatan_datang
            // 'kegiatan_datang' => 'nullable|string|max:255',
        ]);

        try {
            // Cek apakah pegawai sudah absen datang hari ini
            $absensiHariIni = Absensi::where('pegawai_id', $pegawai->id)
                                    ->whereDate('tanggal', $today)
                                    ->first();

            if ($absensiHariIni && $absensiHariIni->jam_datang !== null) {
                return redirect()->back()->with('error', 'Anda sudah melakukan absensi datang hari ini.');
            }

            // Proses upload foto datang
            $fotoDatangPath = null;
            if ($request->hasFile('foto_datang')) {
                $fotoDatangPath = $request->file('foto_datang')->store('uploads/absensi', 'public');
            } else {
                return redirect()->back()->with('error', 'Foto bukti datang wajib diunggah.');
            }

            // Simpan absensi datang
            Absensi::create([
                'pegawai_id' => $pegawai->id,
                'tanggal' => $today,
                'jam_datang' => Carbon::now()->format('H:i:s'),
                'status' => 'Tepat Waktu', // Sesuaikan logika status jika ada aturan jam masuk
                // Hapus penyimpanan kegiatan_datang di sini
                // 'kegiatan' => $request->kegiatan_datang, // Ini akan diisi saat absen pulang
                'foto_datang' => $fotoDatangPath,
            ]);

            return redirect()->back()->with('success', 'Absensi datang berhasil dicatat!');

        } catch (\Exception $e) {
            // Tangkap error dan tampilkan pesan
            \Log::error('Error saat absensi datang: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mencatat absensi datang. Silakan coba lagi. (' . $e->getMessage() . ')');
        }
    }

    /**
     * Memproses absensi pulang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pulang(Request $request)
    {
        $pegawai = Auth::guard('pegawai')->user();
        $today = Carbon::today();

        // Validasi input
        $request->validate([
            'foto_pulang' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
            'kegiatan_pulang' => 'required|string|max:500', // Kegiatan pulang tetap wajib
        ]);

        try {
            // Cek absensi datang hari ini dan pastikan belum pulang
            $absensiHariIni = Absensi::where('pegawai_id', $pegawai->id)
                                    ->whereDate('tanggal', $today)
                                    ->first();

            if (!$absensiHariIni || $absensiHariIni->jam_datang === null) {
                return redirect()->back()->with('error', 'Anda belum melakukan absensi datang hari ini.');
            }

            if ($absensiHariIni->jam_pulang !== null) {
                return redirect()->back()->with('error', 'Anda sudah melakukan absensi pulang hari ini.');
            }

            // Proses upload foto pulang
            $fotoPulangPath = null;
            if ($request->hasFile('foto_pulang')) {
                $fotoPulangPath = $request->file('foto_pulang')->store('uploads/absensi', 'public');
            } else {
                return redirect()->back()->with('error', 'Foto bukti pulang wajib diunggah.');
            }

            // Update absensi pulang
            $absensiHariIni->update([
                'jam_pulang' => Carbon::now()->format('H:i:s'),
                'kegiatan' => $request->kegiatan_pulang, // Kegiatan diisi saat pulang
                'foto_pulang' => $fotoPulangPath,
            ]);

            return redirect()->back()->with('success', 'Absensi pulang berhasil dicatat!');

        } catch (\Exception $e) {
            // Tangkap error dan tampilkan pesan
            \Log::error('Error saat absensi pulang: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mencatat absensi pulang. Silakan coba lagi. (' . $e->getMessage() . ')');
        }
    }

    /**
     * Menampilkan riwayat absensi pegawai.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function history(Request $request)
    {
        $pegawai = Auth::guard('pegawai')->user();
        $query = Absensi::where('pegawai_id', $pegawai->id);

        // Filter berdasarkan tanggal jika ada
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        if ($tanggal_awal) {
            $query->whereDate('tanggal', '>=', $tanggal_awal);
        }
        if ($tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $tanggal_akhir);
        }

        $absensis = $query->orderBy('tanggal', 'desc')->get();

        return view('pegawai.absensi.history', compact('absensis', 'tanggal_awal', 'tanggal_akhir'));
    }
}
