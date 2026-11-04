<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ImportBnbaController;

// 1. HALAMAN PUBLIK (MASYARAKAT UMUM)
Route::get('/', [PublicController::class, 'index'])->name('public.landing');
Route::get('/ajukan-bantuan', [PublicController::class, 'formPengajuan'])->name('public.ajukan');
Route::post('/ajukan-bantuan', [PublicController::class, 'simpanPengajuan'])->name('public.simpan');
Route::get('/api/map-public', [PublicController::class, 'getMapPublicJson'])->name('api.map.public');

// 2. HALAMAN LOGIN STAF
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'prosesLogin'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. HALAMAN INTERNAL ADMIN / STAF (WAJIB LOGIN)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Peta GIS
    Route::get('/peta-gis', [AdminController::class, 'petaGis'])->name('gis');
    Route::get('/api/map-admin', [AdminController::class, 'getMapAdminJson'])->name('api.map.admin');

    // Import BNBA Excel
    Route::get('/import-bnba', [ImportBnbaController::class, 'index'])->name('import-bnba.index');
    Route::post('/import-bnba', [ImportBnbaController::class, 'store'])->name('import-bnba.store');

    // CRUD Data Warga
    Route::get('/warga', [AdminController::class, 'wargaIndex'])->name('warga.index');
    Route::get('/warga/tambah', [AdminController::class, 'wargaCreate'])->name('warga.create');
    Route::post('/warga', [AdminController::class, 'wargaStore'])->name('warga.store');
    Route::get('/warga/{id}/edit', [AdminController::class, 'wargaEdit'])->name('warga.edit');
    Route::put('/warga/{id}', [AdminController::class, 'wargaUpdate'])->name('warga.update');
    Route::delete('/warga/{id}', [AdminController::class, 'wargaDestroy'])->name('warga.destroy');

    // Pengajuan Alat Bantu
    Route::get('/pengajuan', [AdminController::class, 'pengajuanIndex'])->name('pengajuan.index');
    Route::post('/pengajuan', [AdminController::class, 'pengajuanStore'])->name('pengajuan.store');
    Route::patch('/pengajuan/{id}/status', [AdminController::class, 'pengajuanUpdateStatus'])->name('pengajuan.status');

    // Cetak Laporan RDP
    Route::get('/laporan', [AdminController::class, 'laporanIndex'])->name('laporan.index');
    Route::get('/laporan/pdf', [AdminController::class, 'cetakPdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [AdminController::class, 'exportExcel'])->name('laporan.excel');

    // Panduan & Pengaturan
    Route::get('/pengaturan', [AdminController::class, 'pengaturanIndex'])->name('pengaturan.index');
    Route::put('/pengaturan/password', [AdminController::class, 'updatePassword'])->name('pengaturan.password');
});