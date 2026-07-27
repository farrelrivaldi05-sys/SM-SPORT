<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservasiController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route Jadwal & Ketersediaan Lapangan (Fitur Baru)
    Route::get('/jadwal', [ReservasiController::class, 'jadwal'])->name('jadwal.index');

    // Route Reservasi
    Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi.index');
    Route::get('/reservasi/laporan', [ReservasiController::class, 'laporan'])->name('reservasi.laporan');
    Route::get('/reservasi/create', [ReservasiController::class, 'create'])->name('reservasi.create');
    Route::post('/reservasi', [ReservasiController::class, 'store'])->name('reservasi.store');
    Route::get('/reservasi/{id}/nota', [ReservasiController::class, 'nota'])->name('reservasi.nota');
    Route::patch('/reservasi/{id}/approve', [ReservasiController::class, 'approve'])->name('reservasi.approve');
    Route::patch('/reservasi/{id}/cancel', [ReservasiController::class, 'cancel'])->name('reservasi.cancel');
    Route::delete('/reservasi/{id}', [ReservasiController::class, 'destroy'])->name('reservasi.destroy');
});

require __DIR__.'/auth.php';