<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KasirController;

Route::get('/', [KasirController::class, 'index'])->name('kasir.index');

Route::post('/transaksi', [KasirController::class, 'store'])->name('kasir.store');

Route::get('/struk/{id}', [KasirController::class, 'struk'])->name('kasir.struk');

Route::post('/transaksi/hold', [KasirController::class, 'hold'])->name('kasir.hold');

Route::get('/transaksi/ditahan', [KasirController::class, 'held'])->name('kasir.held');

Route::get('/transaksi/ditahan/{id}/lanjutkan', [KasirController::class, 'continueHeld'])->name('kasir.continue');

Route::get('/transaksi/riwayat', [KasirController::class, 'history'])->name('kasir.history');