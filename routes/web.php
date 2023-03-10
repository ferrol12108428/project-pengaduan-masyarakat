<?php

use App\Http\Controllers\DataController;
use App\Http\Controllers\ResponseController;
use App\Http\Middleware\isLogin;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/user', function () {
    return view('welcome');
});

Route::get('/', [DataController::class, 'index'])->name('index');

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/store', [DataController::class, 'store'])->name('store');

Route::post('/auth', [DataController::class, 'auth'])->name('auth');

// route yg hanya dapat di akses setelah login dan role nya petugas
Route::middleware('isLogin', 'CekRole:petugas')->group(function() {
Route::get('/data/petugas', [DataController::class, 'dataPetugas'])->name('data.petugas');
// menampilkan from tambah atau ubah response
Route::get('/response/edit/{data_id}', [ResponseController::class, 'edit'])->name('response.edit');
// kirim data response. menggunakan patch, karena dia bisa berupa tambah data atau update data
Route::patch('/response/update/{data_id}', [ResponseController::class, 'update'])->name('response.update');
});

// Route untuk admin dan petugas setelah login 
Route::middleware(['isLogin', 'CekRole:admin,petugas'])->group(function() {
    Route::get('/logout', [DataController::class, 'logout'])->name('logout');
});

// route yg hanya dapat di akses setelah login dan role nya admin
Route::middleware('isLogin', 'CekRole:admin')->group(function() {
Route::get('/data', [DataController:: class, 'data'])->name('data');
Route::post('/destroy/{id}', [DataController::class, 'destroy'])->name('delete');
Route::get('/export/pdf', [DataController::class, 'exportPDF'])->name('export-pdf');
Route::get('/export/pdf/{id}', [DataController::class, 'createdPDF'])->name('create.pdf');
Route::get('/export/excel/', [DataController::class, 'exportExcel'])->name('export.excel');
});
