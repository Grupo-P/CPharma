<?php

use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;
use App\Http\Controllers\AdminLTEDemoController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

//Monitor de estado de la aplicación
Route::middleware(['auth:sanctum', 'verified'])->get('health', HealthCheckResultsController::class)->name('health');

//Pagina demo para hacer pruebas de AdminLTE con librerias
Route::middleware(['auth:sanctum', 'verified'])->get('AdminLTEDemo', [AdminLTEDemoController::class, 'index'])->name('AdminLTEDemo');

