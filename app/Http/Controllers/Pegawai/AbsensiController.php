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

        // Validasi untuk Base64 string
        $request->validate([
            'foto_datang' => 'required|string',
        ]);

        try {
            $fotoDatangPath = null;
            
            // Logika untuk menyimpan gambar dari Base64
            $base64Image = $request->input('foto_datang');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
                $type = strtolower($type[1]);
                $imageData = base64_decode($imageData);
                $fileName = 'datang-' . uniqid() . '.' . $type;
                $path = 'uploads/absensi/' . $fileName;

                Storage::disk('public')->put($path, $imageData);
                $fotoDatangPath = $path;
            } else {
                return redirect()->back()->with('error', 'Format foto bukti datang tidak valid.');
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
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mencatat absensi datang. Silakan coba lagi.');
        }
    }

    public function pulang(Request $request)
    {
        $pegawai = Auth::guard('pegawai')->user();
        $today = Carbon::today();
        $jamSekarang = Carbon::now();

        // Validasi untuk Base64 string dan kegiatan
        $request->validate([
            'foto_pulang' => 'required|string',
            'kegiatan_pulang' => 'required|string|max:500',
        ]);

        try {
            $absensiHariIni = Absensi::where('pegawai_id', $pegawai->id)
                                      ->whereDate('tanggal', $today)
                                      ->first();
            
            if (!$absensiHariIni) {
                 return redirect()->back()->with('error', 'Absensi datang hari ini tidak ditemukan untuk dicatat pulangnya.');
            }

            // Logika untuk menyimpan gambar dari Base64
            $fotoPulangPath = null;
            $base64Image = $request->input('foto_pulang');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
                $type = strtolower($type[1]);
                $imageData = base64_decode($imageData);
                $fileName = 'pulang-' . uniqid() . '.' . $type;
                $path = 'uploads/absensi/' . $fileName;

                Storage::disk('public')->put($path, $imageData);
                $fotoPulangPath = $path;
            } else {
                return redirect()->back()->with('error', 'Format foto bukti pulang tidak valid.');
            }

            // Logika status pulang
            $jamPulangStandar = Carbon::createFromTimeString('17:00:00');
            $batasWaktuLembur = $jamPulangStandar->copy()->addMinutes(10);
            $statusPulang = '';
            if ($jamSekarang->lessThan($jamPulangStandar)) {
                $statusPulang = 'Pulang Lebih Cepat';
            } elseif ($jamSekarang->between($jamPulangStandar, $batasWaktuLembur, true)) {
                $statusPulang = 'Sesuai';
            } else {
                $statusPulang = 'Lembur';
            }

            $absensiHariIni->update([
                'jam_pulang' => $jamSekarang->format('H:i:s'),
                'kegiatan' => $request->kegiatan_pulang,
                'foto_pulang' => $fotoPulangPath,
                'status_pulang' => $statusPulang,
            ]);

            return redirect()->back()->with('success', 'Absensi pulang berhasil dicatat! Status: ' . $statusPulang);

        } catch (\Exception $e) {
            Log::error('Error saat absensi pulang untuk pegawai ID ' . $pegawai->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mencatat absensi pulang. Silakan coba lagi.');
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
