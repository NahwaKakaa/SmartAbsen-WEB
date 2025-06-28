<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Absensi; 
use Carbon\Carbon;

class DashboardPegawaiController extends Controller
{
    /**
     * Menampilkan dashboard pegawai.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pegawai = Auth::guard('pegawai')->user();

        if (!$pegawai) {
            return redirect()->route('login');
        }

        $datangTerakhir = Absensi::where('pegawai_id', $pegawai->id)
                                -> whereNotNull('status')
                                -> latest('id')
                                -> first();

        $pulangTerakhir = Absensi::where('pegawai_id', $pegawai->id)
                                -> whereNotNull('status')
                                -> latest('id')
                                -> first();

        return view('pegawai.dashboard', compact('datangTerakhir', 'pulangTerakhir'));
    }
}
