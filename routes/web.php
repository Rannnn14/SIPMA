<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AccountController;

// Root redirects to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected application routes
Route::middleware('auth')->group(function () {
    // Central dispatcher
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Dedicated routes for each role
    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/mahasiswa/dashboard', [DashboardController::class, 'mahasiswa'])->name('mahasiswa.dashboard');
    });

    Route::middleware('role:kemahasiswaan')->group(function () {
        Route::put('/kemahasiswaan/profil/nama', [AccountController::class, 'updateOwnName'])->name('kemahasiswaan.profile.name.update');
        Route::get('/kemahasiswaan/dashboard', [DashboardController::class, 'kemahasiswaan'])->name('kemahasiswaan.dashboard');
        Route::post('/kemahasiswaan/academic-years', [DashboardController::class, 'storeAcademicYear'])->name('kemahasiswaan.academic-years.store');
        Route::put('/kemahasiswaan/academic-years/{academicYear}', [DashboardController::class, 'updateAcademicYear'])->name('kemahasiswaan.academic-years.update');
        Route::delete('/kemahasiswaan/academic-years/{academicYear}', [DashboardController::class, 'destroyAcademicYear'])->name('kemahasiswaan.academic-years.destroy');
        Route::get('/kemahasiswaan/akun', [AccountController::class, 'index'])->name('kemahasiswaan.accounts.index');
        Route::post('/kemahasiswaan/akun', [AccountController::class, 'store'])->name('kemahasiswaan.accounts.store');
        Route::put('/kemahasiswaan/akun/{user}', [AccountController::class, 'update'])->name('kemahasiswaan.accounts.update');
        Route::delete('/kemahasiswaan/akun/{user}', [AccountController::class, 'destroy'])->name('kemahasiswaan.accounts.destroy');
    });

    Route::middleware('role:kajur')->group(function () {
        Route::get('/kajur/dashboard', [DashboardController::class, 'kajur'])->name('kajur.dashboard');
    });

    Route::middleware('role:dekan')->group(function () {
        Route::get('/dekan/dashboard', [DashboardController::class, 'dekan'])->name('dekan.dashboard');
    });

    Route::middleware('role:wadek3')->group(function () {
        Route::get('/wadek3/dashboard', [DashboardController::class, 'wadek3'])->name('wadek3.dashboard');
    });

    Route::middleware('role:warek')->group(function () {
        Route::get('/warek/dashboard', [DashboardController::class, 'warek'])->name('warek.dashboard');
    });

    // Reports routes
    Route::get('/laporan', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/laporan/inbox', [ReportController::class, 'inbox'])->name('reports.inbox');
    Route::get('/laporan/riwayat', [ReportController::class, 'history'])->name('reports.history');
    Route::get('/laporan/buat', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/laporan', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/laporan/export/excel', [ReportController::class, 'exportExcel'])->name('reports.export.excel');
    Route::get('/laporan/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
    Route::get('/laporan/{id}', [ReportController::class, 'show'])->name('reports.show');

    // Kemahasiswaan Actions (Verifikator & Super Admin)
    Route::post('/laporan/{id}/koreksi', [ReportController::class, 'updateData'])->name('reports.koreksi');
    Route::post('/laporan/{id}/setujui', [ReportController::class, 'approveAndForward'])->name('reports.approve');
    Route::post('/laporan/{id}/tolak', [ReportController::class, 'reject'])->name('reports.reject');

    // Ketua Jurusan (Kajur) Actions
    Route::post('/laporan/{id}/tindak-lanjut', [ReportController::class, 'tindakLanjut'])->name('reports.tindakLanjut');
});
