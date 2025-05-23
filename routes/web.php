<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PegawaiController;
use App\Http\Controllers\Pegawai\AbsensiController;
use App\Http\Controllers\Admin\LaporanAbsensiController;
use App\Http\Controllers\Pegawai\ProfilController;
use App\Http\Controllers\Admin\ProfileAdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Pegawai\DashboardPegawaiController;
use App\Http\Controllers\Pegawai\LaporanAbsensiPegawaiController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth:pegawai'])->group(function () {
     Route::get('/pegawai/dashboard', [DashboardPegawaiController::class, 'index'])->name('pegawai.dashboard');

    Route::get('/absensi', [AbsensiController::class, 'index'])->name('pegawai.absensi.index');
    Route::post('/absensi/datang', [AbsensiController::class, 'datang'])->name('pegawai.absensi.datang');
    Route::post('/absensi/pulang', [AbsensiController::class, 'pulang'])->name('pegawai.absensi.pulang');
    Route::get('/profile', [ProfilController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfilController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfilController::class, 'updatePassword'])->name('profile.password');
    Route::get('/absensi/history', [AbsensiController::class, 'history'])->name('pegawai.absensi.history');
    Route::get('/absensi/history/download', [LaporanAbsensiPegawaiController::class, 'download'])->name('pegawai.absensi.download');
});


Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('/pegawai', PegawaiController::class);
    Route::get('/pegawai', [PegawaiController::class, 'index'])->name('admin.pegawai.index');
    Route::post('/pegawai/{id}/reset-password', [PegawaiController::class, 'resetPassword'])->name('pegawai.reset');
    Route::get('/laporan-absensi', [LaporanAbsensiController::class, 'index'])->name('admin.laporan.index');
    Route::get('/laporan-absensi/cetak', [LaporanAbsensiController::class, 'cetak'])->name('admin.laporan.cetak');
    Route::get('/admin/profile', [ProfileAdminController::class, 'edit'])->name('admin.profile.edit');
    Route::post('/admin/profile', [ProfileAdminController::class, 'update'])->name('admin.profile.update');
    Route::post('/admin/profile/password', [ProfileAdminController::class, 'updatePassword'])->name('admin.profile.password');
});

require __DIR__.'/auth.php';