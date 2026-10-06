<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ProductController;

// Halaman awal
Route::get('/', function () {
    return view('welcome');
});

// Dashboard umum setelah login
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard Admin
Route::get('/admin/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'role:admin'])->name('admin.dashboard');

// Dashboard Kasir
Route::get('/kasir/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'role:kasir'])->name('kasir.dashboard');

Route::get('/transaksi', [KasirController::class, 'index'])
    ->middleware(['auth', 'role:admin,kasir'])
    ->name('kasir.index');

    Route::post('/transaksi', [KasirController::class, 'store'])
    ->middleware(['auth', 'role:admin,kasir'])
    ->name('kasir.store');

    Route::post('/transaksi/hold', [KasirController::class, 'hold'])
    ->middleware(['auth', 'role:admin,kasir'])
    ->name('kasir.hold');

    Route::get('/transaksi/ditahan', [KasirController::class, 'held'])
    ->middleware(['auth', 'role:admin,kasir'])
    ->name('kasir.held');

    Route::get('/transaksi/ditahan/{id}', [KasirController::class, 'continueHeld'])
    ->middleware(['auth', 'role:admin,kasir'])
    ->name('kasir.continueHeld');

    Route::get('/transaksi/riwayat', [KasirController::class, 'history'])
    ->middleware(['auth', 'role:admin,kasir'])
    ->name('kasir.history');

    Route::get('/transaksi/{id}/struk', [KasirController::class, 'struk'])
    ->middleware(['auth', 'role:admin,kasir'])
    ->name('kasir.struk');

    // Produk - Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('products', ProductController::class);
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';