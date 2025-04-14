<?php

use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\DetailPenjualanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Pelanggan Routes
Route::resource('pelanggans', PelangganController::class);

// Produk Routes
Route::resource('produks', ProdukController::class);

// Penjualan Routes
Route::resource('penjualans', PenjualanController::class);
Route::get('penjualans/{penjualan}/invoice', [PenjualanController::class, 'invoice'])->name('penjualans.invoice');

// Detail Penjualan Routes
Route::get('penjualans/{penjualan}/details/create', [DetailPenjualanController::class, 'create'])->name('detail_penjualans.create');
Route::post('penjualans/{penjualan}/details', [DetailPenjualanController::class, 'store'])->name('detail_penjualans.store');
Route::delete('detail_penjualans/{detailPenjualan}', [DetailPenjualanController::class, 'destroy'])->name('detail_penjualans.destroy');