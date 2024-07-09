<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PerhitunganKriteriaController;
use App\Http\Controllers\PerhitunganSubkriteriaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerhitunganAlternatifController;
use App\Http\Controllers\RankingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(DashboardController::class)->name('dashboard.')->middleware('auth')->group(function () {
    Route::get('/dashboard', 'index')->name('index');
});

Route::controller(PenilaianController::class)->name('penilaian.')->middleware('auth')->group(function () {
    Route::get('/dashboard/data-penilaian', 'index')->name('index');
    Route::get('/dashboard/penilaian/introduction', 'welcome')->name('welcome');
    Route::get('/dashboard/penilaian/tambah-penilaian', 'create')->name('create');
    Route::post('/dashboard/penilaian/introduction', 'store')->name('store');
});

Route::controller(PerhitunganKriteriaController::class)->name('perhitunganKriteria.')->middleware('auth')->group(function () {
    Route::get('/dashboard/perbandingan-kriteria', 'index')->name('index');
    Route::get('/dashboard/perbandingan-kriteria/hasil-perbandingan-kriteria', 'hasil')->name('hasil');
    Route::post('/dashboard/perbandingan-kriteria', 'store')->name('store');

});

Route::controller(PerhitunganSubkriteriaController::class)->name('perhitunganSubkriteria.')->middleware('auth')->group(function () {
    Route::get('/dashboard/perbandingan-subkriteria', 'index')->name('index');
    Route::get('/dashboard/perbandingan-subkriteria/hasil-perbandingan-subkriteria', 'hasil')->name('hasil');
    Route::post('/dashboard/perbandingan-subkriteria', 'store')->name('store');
});


Route::controller(PerhitunganAlternatifController::class)->name('perhitunganAlternatif.')->middleware('auth')->group(function () {
    Route::get('/dashboard/perbandingan-alternatif/introduction', 'introduction')->name('introduction');
    Route::get('/dashboard/perbandingan-alternatif', 'index')->name('index');
    Route::get('/dashboard/perbandingan-alternatif/hasil-perbandingan-alternatif', 'hasil')->name('hasil');
    
    Route::post('/dashboard/perbandingan-alternatif', 'store')->name('store');
});


Route::controller(RankingController::class)->name('ranking.')->middleware('auth')->group(function () {   
    Route::get('/dashboard/perankingan', 'index')->name('index');
    Route::post('/dashboard/perankingan', 'store')->name('store');
});


