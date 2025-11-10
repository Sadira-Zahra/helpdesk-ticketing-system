<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GantiProfilController;
use App\Http\Controllers\Tiket\TiketController;
use App\Http\Controllers\Master_User\UserController;
use App\Http\Controllers\Master_User\AdminController;
use App\Http\Controllers\Tiket\TiketActionController;
use App\Http\Controllers\Tiket\LaporanTiketController;
use App\Http\Controllers\Master_Data\UrgencyController;
use App\Http\Controllers\Master_User\TeknisiController;
use App\Http\Controllers\Master_Data\DepartemenController;
use App\Http\Controllers\Master_User\AdministratorController;

// ============ PUBLIC ROUTES ============
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ============ AUTHENTICATION ROUTES ============
// User Login
Route::get('login', [AuthController::class, 'showLoginUser'])
    ->name('login')
    ->middleware('guest');

Route::get('login_user', [AuthController::class, 'showLoginUser'])
    ->name('login_user')
    ->middleware('guest');

Route::post('login_user', [AuthController::class, 'loginUserPost'])
    ->name('login_user.post')
    ->middleware('guest');

// User Register
Route::get('register_user', [AuthController::class, 'showRegisterUser'])
    ->name('register_user')
    ->middleware('guest');

Route::post('register_user', [AuthController::class, 'registerUserPost'])
    ->name('register_user.post')
    ->middleware('guest');

// Petugas (Admin/Teknisi) Login
Route::get('login_petugas', [AuthController::class, 'showLoginPetugas'])
    ->name('login_petugas')
    ->middleware('guest');

Route::post('login_petugas', [AuthController::class, 'loginPetugasPost'])
    ->name('login_petugas.post')
    ->middleware('guest');

// Logout
Route::post('logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// ============ AUTHENTICATED ROUTES ============
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profil Routes
    Route::get('profil/edit', function () {
        return view('profil.edit');
    })->name('profil.edit');

    Route::put('profil/update', function () {
        // Update profil logic
    })->name('profil.update');

    Route::get('password/edit', function () {
        return view('password.edit');
    })->name('password.edit');

    Route::put('password/update', function () {
        // Update password logic
    })->name('password.update');
});

// ============ MASTER USER ROUTES (PROTECTED) ============
Route::middleware(['auth'])->group(function () {
    // Administrator Resource Routes
    Route::resource('administrator', AdministratorController::class, [
        'names' => [
            'index'   => 'administrator.index',
            'create'  => 'administrator.create',
            'store'   => 'administrator.store',
            'show'    => 'administrator.show',
            'edit'    => 'administrator.edit',
            'update'  => 'administrator.update',
            'destroy' => 'administrator.destroy',
        ]
    ]);

    // Admin Resource Routes
    Route::resource('admin', AdminController::class, [
        'names' => [
            'index'   => 'admin.index',
            'create'  => 'admin.create',
            'store'   => 'admin.store',
            'show'    => 'admin.show',
            'edit'    => 'admin.edit',
            'update'  => 'admin.update',
            'destroy'  => 'admin.destroy',
        ]
    ]);

    Route::resource('departemen', DepartemenController::class, [
        'names' => [
            'index'   => 'departemen.index',
            'create'  => 'departemen.create',
            'store'   => 'departemen.store',
            'show'    => 'departemen.show',
            'edit'    => 'departemen.edit',
            'update'  => 'departemen.update',
            'destroy' => 'departemen.destroy',
        ]
    ]);

    Route::resource('urgency', UrgencyController::class, [
        'names' => [
            'index'   => 'urgency.index',
            'create'  => 'urgency.create',
            'store'   => 'urgency.store',
            'show'    => 'urgency.show',
            'edit'    => 'urgency.edit',
            'update'  => 'urgency.update',
            'destroy' => 'urgency.destroy',
        ]
    ]);

    Route::resource('teknisi', TeknisiController::class, [
        'names' => [
            'index'   => 'teknisi.index',
            'create'  => 'teknisi.create',
            'store'   => 'teknisi.store',
            'show'    => 'teknisi.show',
            'edit'    => 'teknisi.edit',
            'update'  => 'teknisi.update',
            'destroy' => 'teknisi.destroy',
        ]
    ]);

    Route::resource('user', UserController::class, [
        'names' => [
            'index'   => 'user.index',
            'create'  => 'user.create',
            'store'   => 'user.store',
            'show'    => 'user.show',
            'edit'    => 'user.edit',
            'update'  => 'user.update',
            'destroy' => 'user.destroy',
        ]
    ]);

    Route::get('ganti-profil', [GantiProfilController::class, 'index'])->name('ganti_profil.index');
    Route::put('ganti-profil/update', [GantiProfilController::class, 'update'])->name('ganti_profil.update');
    Route::put('ganti-profil/password', [GantiProfilController::class, 'updatePassword'])->name('ganti_profil.update_password');


    // Tiket routes
    Route::get('tiket', [TiketController::class, 'index'])->name('tiket.index');
    Route::post('tiket', [TiketController::class, 'store'])->name('tiket.store');
    Route::get('tiket/{id}', [TiketController::class, 'show'])->name('tiket.show');
    
    // Action routes
    Route::post('tiket/{id}/assign', [TiketActionController::class, 'assign'])->name('tiket.assign');
    Route::post('tiket/{id}/unassign', [TiketActionController::class, 'unassign'])->name('tiket.unassign');
    Route::post('tiket/{id}/status', [TiketActionController::class, 'updateStatus'])->name('tiket.updateStatus');
    
    // Teknisi routes (baru)
    Route::post('tiket/{id}/accept', [TiketActionController::class, 'accept'])->name('tiket.accept');
    Route::post('tiket/{id}/reject', [TiketActionController::class, 'reject'])->name('tiket.reject');
    Route::post('tiket/{id}/finish', [TiketActionController::class, 'finish'])->name('tiket.finish');

    // Laporan Tiket routes
    Route::get('tiket-laporan', [LaporanTiketController::class, 'index'])->name('tiket.laporan');

 
    // Route untuk controller lain (uncomment saat sudah dibuat)
    // Route::resource('teknisi', TeknisiController::class);
    // Route::resource('user', UserController::class);
});

// ============ FALLBACK (Untuk 404) ============
Route::fallback(function () {
    abort(404, 'Halaman tidak ditemukan');
});
