<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    public function index()
    {
        $pegawai = Auth::guard('pegawai')->user();
        
        $absensiHariIni = Absensi::where('pegawai_id', $pegawai->id)
                                ->whereDate('tanggal', Carbon::today())
                                ->first();

        $sudahDatang = $absensiHariIni && $absensiHariIni->jam_datang !== null;
        $sudahPulang = $absensiHariIni && $absensiHariIni->jam_pulang !== null;

        return view('pegawai.absensi.index', compact('sudahDatang', 'sudahPulang'));
    }

    public function datang(Request $request)
    {
        $pegawai = Auth::guard('pegawai')->user();
        $today = Carbon::today();
        $jamSekarang = Carbon::now();

        $jamMasukBatas = Carbon::createFromTime(8, 0, 0);

        $request->validate([
            'foto_datang' => 'required|image|mimes:jpeg,png,jpg|max:40960',
        ]);

        try {
            $fotoDatangPath = null;
            if ($request->hasFile('foto_datang')) {
                $fotoDatangPath = $request->file('foto_datang')->store('uploads/absensi', 'public');
            } else {
                return redirect()->back()->with('error', 'Foto bukti datang wajib diunggah.');
            }

            $statusAbsensi = ($jamSekarang->greaterThan($jamMasukBatas)) ? 'Terlambat' : 'Tepat Waktu';

            Absensi::create([
                'pegawai_id' => $pegawai->id,
                'tanggal' => $today,
                'jam_datang' => $jamSekarang->format('H:i:s'),
                'status' => $statusAbsensi,
                'foto_datang' => $fotoDatangPath,
            ]);

            return redirect()->back()->with('success', 'Absensi datang berhasil dicatat! Status: ' . $statusAbsensi);

        } catch (\Exception $e) {
            Log::error('Error saat absensi datang untuk pegawai ID ' . $pegawai->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mencatat absensi datang. Silakan coba lagi. Detail: ' . $e->getMessage());
        }
    }

    public function pulang(Request $request)
    {
        $pegawai = Auth::guard('pegawai')->user();
        $today = Carbon::today();
        $jamSekarang = Carbon::now();

        $request->validate([
            'foto_pulang' => 'required|image|mimes:jpeg,png,jpg|max:40960',
            'kegiatan_pulang' => 'required|string|max:500',
        ]);

        try {
            $absensiHariIni = Absensi::where('pegawai_id', $pegawai->id)
                                    ->whereDate('tanggal', $today)
                                    ->first();
            
            $fotoPulangPath = null;
            if ($request->hasFile('foto_pulang')) {
                $fotoPulangPath = $request->file('foto_pulang')->store('uploads/absensi', 'public');
            } else {
                return redirect()->back()->with('error', 'Foto bukti pulang wajib diunggah.');
            }

            if ($absensiHariIni) {
                $absensiHariIni->update([
                    'jam_pulang' => $jamSekarang->format('H:i:s'),
                    'kegiatan' => $request->kegiatan_pulang,
                    'foto_pulang' => $fotoPulangPath,
                ]);
                return redirect()->back()->with('success', 'Absensi pulang berhasil dicatat!');
            } else {
                return redirect()->back()->with('error', 'Absensi datang hari ini tidak ditemukan untuk dicatat pulangnya.');
            }

        } catch (\Exception $e) {
            Log::error('Error saat absensi pulang untuk pegawai ID ' . $pegawai->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mencatat absensi pulang. Silakan coba lagi. Detail: ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $pegawai = Auth::guard('pegawai')->user();
        $query = Absensi::where('pegawai_id', $pegawai->id);

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
