<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backoffice\DashboardController;
use App\Http\Controllers\Backoffice\UploadCsvController;
use App\Http\Controllers\Backoffice\ConfigurationController;
use App\Http\Controllers\Backoffice\HistoricalDataController;
use App\Http\Livewire\UserManagement;

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

Route::get('/login', [LoginController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'store'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');
Route::middleware(['auth'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::prefix('user')->group(function(){
        Route::get('/manage', UserManagement::class)->name('user.manage');
    });
    Route::prefix('menu')->group(function(){
        Route::get('/uploadcsv', UploadCsvController::class)->name('menu.uploadcsv');
        Route::get('/historicaldata', HistoricalDataController::class)->name('menu.historicaldata');
    });
    Route::prefix('settings')->group(function(){
        Route::get('/configuration', ConfigurationController::class)->name('settings.configuration');
    });
});