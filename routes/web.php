<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PGConnectController;
use App\Http\Controllers\SQLServerController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
// routes/web.php


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/check-sql-connection', [SQLServerController::class, 'checkConnection']);
Route::get('/testpg', [PGConnectController::class, 'testPostgreSQLConnection']);
// Route::get('/show-marcaciones', [PGConnectController::class, 'showMarcaciones']);
// Route::get('/sqlserver-marcaciones', [SQLServerController::class, 'showMarcaciones']);
// routes/web.php
Route::post('/seleccionar_dispositivo', [DashboardController::class, 'seleccionarDispositivo'])->name('seleccionar_dispositivo');
// Route::get('/indexUpMark', [DashboardController::class, 'indexUpMark'])->name('indexUpMark');
Route::get('/index-up-mark', function () {
    return view('dashboard.indexUpMark');
})->name('indexUpMark');
// Route::middleware('auth')->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });
