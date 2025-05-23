<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use App\Models\Absensi; 
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {

        $pegawaiCount = Pegawai::count();


        $absensiHariIni = Absensi::whereDate('tanggal', Carbon::today())
                                 ->distinct('pegawai_id') 
                                 ->count();

        $terlambatHariIni = Absensi::whereDate('tanggal', Carbon::today())
                                   ->where('status', 'Terlambat')
                                   ->distinct('pegawai_id')
                                   ->count();

        $jumlahTidakAbsenHariIni = $pegawaiCount - $absensiHariIni;

        $jumlahTidakAbsenHariIni = max(0, $jumlahTidakAbsenHariIni);

        $jumlahTepatWaktuHariIni = $absensiHariIni - $terlambatHariIni;
        $jumlahTepatWaktuHariIni = max(0, $jumlahTepatWaktuHariIni);


        return view('admin.dashboard', compact(
            'pegawaiCount',
            'absensiHariIni',
            'terlambatHariIni',
            'jumlahTidakAbsenHariIni',
            'jumlahTepatWaktuHariIni'
        ));
    }
}
