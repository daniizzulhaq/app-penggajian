<?php
// ================================================================
// FILE: routes/web.php  (REPLACE seluruh isinya)
// ================================================================
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\TunjanganController;
use App\Http\Controllers\PotongonController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\SlipGajiController;
use App\Http\Controllers\LaporanController;

// ----------------------------------------------------------------
// AUTH
// ----------------------------------------------------------------
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login',  [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout',[LoginController::class, 'logout'])->name('logout');

// ----------------------------------------------------------------
// SEMUA USER YANG LOGIN
// ----------------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Slip gaji untuk karyawan sendiri
    Route::get('/slip-saya',          [SlipGajiController::class, 'mySlip'])->name('slip.my');
    Route::get('/slip/cetak/{payroll}',[SlipGajiController::class, 'cetak'])->name('slip.cetak');

});

// ----------------------------------------------------------------
// ADMIN / HRD ONLY
// ----------------------------------------------------------------
Route::middleware(['auth', 'role:admin,hrd'])->group(function () {

    // === DATA MASTER ===
    Route::resource('jabatan',    JabatanController::class)->except(['show', 'create', 'edit']);
    Route::resource('departement',DepartementController::class)->except(['show', 'create', 'edit']);
    Route::resource('karyawan',   KaryawanController::class);
    Route::resource('tunjangan',  TunjanganController::class)->except(['show', 'create', 'edit']);
    Route::resource('potongan',   PotongonController::class)->except(['show', 'create', 'edit']);

    // === ABSENSI ===
    Route::get('/absensi',               [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi',              [AbsensiController::class, 'store'])->name('absensi.store');
    Route::post('/absensi/bulk',         [AbsensiController::class, 'importBulk'])->name('absensi.bulk');
    Route::delete('/absensi/{absensi}',  [AbsensiController::class, 'destroy'])->name('absensi.destroy');

    // === PAYROLL ===
    Route::get('/payroll',               [PayrollController::class, 'index'])->name('payroll.index');
    Route::post('/payroll/generate',     [PayrollController::class, 'generate'])->name('payroll.generate');
    Route::get('/payroll/{payroll}',     [PayrollController::class, 'show'])->name('payroll.show');
    Route::post('/payroll/{payroll}/approve',   [PayrollController::class, 'approve'])->name('payroll.approve');
    Route::post('/payroll/approve-all',         [PayrollController::class, 'approveAll'])->name('payroll.approveAll');
    Route::post('/payroll/{payroll}/paid',      [PayrollController::class, 'markPaid'])->name('payroll.paid');
    Route::post('/payroll/paid-all',            [PayrollController::class, 'markAllPaid'])->name('payroll.paidAll');

    // === LAPORAN ===
    Route::get('/laporan',              [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-pdf',   [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/laporan/rekap',        [LaporanController::class, 'rekap'])->name('laporan.rekap');

});