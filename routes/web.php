<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\DetailController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
// Route::get('/stok', function () {
//     return view('stok');
// });
Route::get('/', [AuthController::class, 'param']);


Route::resource('/admin/stok',BarangController::class);
Route::post('/stok/tambah', [BarangController::class, 'tambah']);
Route::put('/stok/edit/{id}', [BarangController::class, 'update']);
Route::delete('barang/{barang}/destroy', [BarangController::class, 'destroy'])->name('barang.destroy');


Route::get('/admin/employee',[UserController::class, 'index']);
Route::post('/user/tambah', [UserController::class, 'tambah']);
Route::put('/user/update/{id}', [UserController::class, 'edit']);
Route::delete('/user/delete/{id}', [UserController::class, 'hapus']);


Route::resource('/admin/diskon',DiskonController::class);
Route::post('/diskon/tambah', [DiskonController::class, 'tambah']);
Route::put('/diskon/update/{id}', [DiskonController::class, 'update'])->name('diskon.update');
Route::delete('diskon/{diskon}/destroy', [DiskonController::class, 'destroy'])->name('diskon.destroy');



Route::get('/penjualan',[BarangController::class, 'penjualan']);
Route::post('/barang/selected', [BarangController::class, 'showSelected'])->name('barang.selected');
Route::post('/bayar/{id}', [PenjualanController::class, 'bayar'])->name('bayar');

Route::get('/admin/history',[PenjualanController::class, 'index']);
Route::get('/admin/history/{id}',[DetailController::class, 'index']);




