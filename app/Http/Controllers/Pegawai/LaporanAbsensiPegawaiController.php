<?php

    namespace App\Http\Controllers\Pegawai;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use App\Models\Absensi;
    use Carbon\Carbon;
    use Barryvdh\DomPDF\Facade\Pdf;
    use Illuminate\Http\Response; // Tambahkan ini
    use Illuminate\Http\RedirectResponse; // Tambahkan ini

    class LaporanAbsensiPegawaiController extends Controller
    {
        /**
         * Mengunduh ringkasan/riwayat absensi pegawai yang sedang login sebagai PDF.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse // Perbarui PHPDoc
         */
        public function download(Request $request): Response|RedirectResponse // Perbaikan di sini!
        {
            // Mendapatkan instance pegawai yang sedang login
            $pegawai = Auth::guard('pegawai')->user();

            // Pastikan pegawai ditemukan
            if (!$pegawai) {
                return redirect()->route('login')->with('error', 'Sesi pegawai tidak ditemukan.');
            }

            // Memuat relasi pegawai.jabatan jika diperlukan di view PDF
            $query = Absensi::where('pegawai_id', $pegawai->id)->with('pegawai.jabatan');

            // Filter berdasarkan tanggal jika ada dari request (mirip dengan logika admin)
            $tanggal_awal = $request->input('tanggal_awal');
            $tanggal_akhir = $request->input('tanggal_akhir');

            $absensis = $query
                ->when($tanggal_awal && $tanggal_akhir, function($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tanggal', [$tanggal_awal, $tanggal_akhir]);
                })
                ->orderBy('tanggal', 'desc')
                ->get();

            // Load view ke Dompdf
            // Menggunakan view pegawai/absensi/pdf
            $pdf = PDF::loadView('pegawai.absensi.pdf', compact('pegawai', 'absensis', 'tanggal_awal', 'tanggal_akhir'));

            // Opsional: Atur ukuran kertas dan orientasi
            $pdf->setPaper('A4', 'portrait'); 

            // Unduh PDF
            return $pdf->download('ringkasan_absensi_' . $pegawai->nama . '_' . Carbon::now()->format('Ymd_His') . '.pdf');
        }
    }
    