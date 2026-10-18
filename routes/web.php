<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UnitKerjaController;
use App\Http\Controllers\MasterPpkController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\IzinController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\PengajuanAbsenController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RekapController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin CRUD Routes (F2.3)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('unit-kerja', UnitKerjaController::class);
        Route::resource('master-ppk', MasterPpkController::class);
        Route::resource('pegawai', PegawaiController::class);
    });

    // Penilaian Kinerja (F2.4)
    Route::resource('penilaian', PenilaianController::class);

    // Modul Izin / Cuti (F2.5)
    Route::resource('izin', IzinController::class)->except(['edit', 'update']);
    Route::patch('izin/{izin}/status', [IzinController::class, 'updateStatus'])->name('izin.status');

    // Modul Pengaduan (F2.6)
    Route::resource('pengaduan', PengaduanController::class)->except(['edit', 'update']);
    Route::patch('pengaduan/{pengaduan}/status', [PengaduanController::class, 'updateStatus'])->name('pengaduan.status');

    // Rating Pegawai (F2.7)
    Route::get('rating', [RatingController::class, 'index'])->name('rating.index');
    Route::post('rating', [RatingController::class, 'store'])->name('rating.store');
    Route::delete('rating/{rating}', [RatingController::class, 'destroy'])->name('rating.destroy');

    // Modul Lupa Absen / Don't Forget (F3)
    Route::resource('absen', PengajuanAbsenController::class)->except(['edit', 'update']);
    Route::patch('absen/{absen}/status', [PengajuanAbsenController::class, 'updateStatus'])->name('absen.status');

    // Modul Perjalanan Dinas / Pajokka (F4)
    Route::resource('pajokka', ReportController::class)->except(['edit', 'update']);
    Route::patch('pajokka/{report}/status', [ReportController::class, 'updateStatus'])->name('pajokka.status');
    Route::get('pajokka/{report}/docx', [ReportController::class, 'exportDocx'])->name('pajokka.docx');

    // Modul Rekap & Statistik (F5)
    Route::get('rekap', [RekapController::class, 'index'])->name('rekap.index');
    Route::get('rekap/export/{type}', [RekapController::class, 'export'])->name('rekap.export');
});

require __DIR__.'/auth.php';
