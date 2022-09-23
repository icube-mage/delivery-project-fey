<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Backoffice\DashboardController;
use App\Http\Controllers\Backoffice\UploadFileController;
use App\Http\Controllers\Backoffice\ConfigurationController;
use App\Http\Controllers\Backoffice\HistoricalDataController;
use App\Http\Controllers\Backoffice\ReportController;
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
    Route::prefix('menu')->name('menu.')->group(function(){
        Route::get('uploadfile', UploadFileController::class)->name('uploadfile');
        Route::get('uploadfile/checkprice', [UploadFileController::class, 'checkPrice'])->name('uploadfile.checkprice');
        Route::get('report', [ReportController::class, 'index'])->name('report');
        Route::get('historicaldata', [HistoricalDataController::class, 'index'])->name('historicaldata');
        Route::get('historicaldata/{hash}', [HistoricalDataController::class, 'show'])->name('historicaldata.show');
    });
    Route::prefix('export')->name('export.')->group(function(){
        Route::get('export/log',  [HistoricalDataController::class, 'exportAll'])->name('catalog.price');
        Route::get('export/log/{hash}',  [HistoricalDataController::class, 'exportByHash'])->name('catalog.price.hash');
        Route::get('report', [ReportController::class, 'export'])->name('report');
        Route::get('updateddata', [UploadFileController::class, 'export'])->name('updateddata');
    });
    Route::prefix('settings')->group(function(){
        Route::get('/configuration', ConfigurationController::class)->name('settings.configuration');
    });
});