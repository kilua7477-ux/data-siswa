<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::get('/home', [SiswaController::class, 'index'])->name('home');

Route::get('/siswa/template/download', [SiswaController::class, 'downloadTemplate'])->name('siswa.template');
Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
Route::resource('siswa', SiswaController::class);