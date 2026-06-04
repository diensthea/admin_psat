<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruDashboardController;
use App\Http\Controllers\SiswaDashboardController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isGuru()) {
            return redirect()->route('guru.dashboard');
        } elseif ($user->isSiswa()) {
            return redirect()->route('siswa.dashboard');
        }
    }
    return redirect()->route('login');
});

// Authentication Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Guru CRUD
        Route::get('/guru', [AdminController::class, 'guruIndex'])->name('admin.guru.index');
        Route::post('/guru/create', [AdminController::class, 'guruStore'])->name('admin.guru.store');
        Route::post('/guru/edit/{id}', [AdminController::class, 'guruUpdate'])->name('admin.guru.update');
        Route::delete('/guru/delete/{id}', [AdminController::class, 'guruDestroy'])->name('admin.guru.destroy');
        Route::post('/guru/import', [AdminController::class, 'guruImport'])->name('admin.guru.import');
        Route::post('/guru/reset-password/{id}', [AdminController::class, 'guruResetPassword'])->name('admin.guru.reset-password');

        // Siswa CRUD
        Route::get('/siswa', [AdminController::class, 'siswaIndex'])->name('admin.siswa.index');
        Route::post('/siswa/create', [AdminController::class, 'siswaStore'])->name('admin.siswa.store');
        Route::post('/siswa/edit/{id}', [AdminController::class, 'siswaUpdate'])->name('admin.siswa.update');
        Route::delete('/siswa/delete/{id}', [AdminController::class, 'siswaDestroy'])->name('admin.siswa.destroy');
        Route::post('/siswa/import', [AdminController::class, 'siswaImport'])->name('admin.siswa.import');
        Route::post('/siswa/reset-password/{id}', [AdminController::class, 'siswaResetPassword'])->name('admin.siswa.reset-password');

        // Kelas CRUD
        Route::get('/kelas', [AdminController::class, 'kelasIndex'])->name('admin.kelas.index');
        Route::post('/kelas/create', [AdminController::class, 'kelasStore'])->name('admin.kelas.store');
        Route::post('/kelas/edit/{id}', [AdminController::class, 'kelasUpdate'])->name('admin.kelas.update');
        Route::delete('/kelas/delete/{id}', [AdminController::class, 'kelasDestroy'])->name('admin.kelas.destroy');
        Route::post('/kelas/import', [AdminController::class, 'kelasImport'])->name('admin.kelas.import');

        // Penempatan Siswa
        Route::get('/penempatan', [AdminController::class, 'penempatanIndex'])->name('admin.penempatan.index');
        Route::post('/penempatan/store', [AdminController::class, 'penempatanStore'])->name('admin.penempatan.store');
        Route::delete('/penempatan/remove/{kelas_id}/{siswa_id}', [AdminController::class, 'penempatanDestroy'])->name('admin.penempatan.destroy');
        Route::post('/penempatan/import', [AdminController::class, 'penempatanImport'])->name('admin.penempatan.import');

        // Mapel CRUD
        Route::get('/mapel', [AdminController::class, 'mapelIndex'])->name('admin.mapel.index');
        Route::post('/mapel/create', [AdminController::class, 'mapelStore'])->name('admin.mapel.store');
        Route::post('/mapel/edit/{id}', [AdminController::class, 'mapelUpdate'])->name('admin.mapel.update');
        Route::delete('/mapel/delete/{id}', [AdminController::class, 'mapelDestroy'])->name('admin.mapel.destroy');
        Route::post('/mapel/import', [AdminController::class, 'mapelImport'])->name('admin.mapel.import');

        // Monitoring Berita Acara & Daftar Hadir
        Route::get('/berita-acara', [AdminController::class, 'beritaAcaraIndex'])->name('admin.berita-acara.index');
        Route::get('/berita-acara/{id}', [AdminController::class, 'beritaAcaraShow'])->name('admin.berita-acara.show');
        Route::delete('/berita-acara/delete/{id}', [AdminController::class, 'beritaAcaraDestroy'])->name('admin.berita-acara.destroy');
    });

    // Guru Routes
    Route::middleware('role:guru')->prefix('guru')->group(function () {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('guru.dashboard');
        Route::get('/berita-acara/create', [GuruDashboardController::class, 'createBeritaAcara'])->name('guru.berita-acara.create');
        Route::post('/berita-acara/store', [GuruDashboardController::class, 'storeBeritaAcara'])->name('guru.berita-acara.store');
        Route::get('/berita-acara/{id}', [GuruDashboardController::class, 'showBeritaAcara'])->name('guru.berita-acara.show');
        Route::get('/berita-acara/{id}/edit', [GuruDashboardController::class, 'editBeritaAcara'])->name('guru.berita-acara.edit');
        Route::post('/berita-acara/{id}/update', [GuruDashboardController::class, 'updateBeritaAcara'])->name('guru.berita-acara.update');
        Route::get('/berita-acara/{id}/print', [GuruDashboardController::class, 'printBeritaAcara'])->name('guru.berita-acara.print');
        Route::post('/berita-acara/attendance/{id}/status', [GuruDashboardController::class, 'updateAttendanceStatus'])->name('guru.berita-acara.attendance-status');
    });

    // Siswa Routes
    Route::middleware('role:siswa')->prefix('siswa')->group(function () {
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('siswa.dashboard');
        Route::get('/attendance/{berita_acara_id}', [SiswaDashboardController::class, 'showAttendanceForm'])->name('siswa.attendance.show');
        Route::post('/attendance/{berita_acara_id}/sign', [SiswaDashboardController::class, 'signAttendance'])->name('siswa.attendance.sign');
    });
});
