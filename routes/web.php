<?php

use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;
use App\Http\Controllers\Core\demo\AdminLTEDemoController;
use App\Http\Controllers\Core\ParametroController;

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

//Grupo de rutas core
Route::prefix('core')->group(function () {
    
    //Grupo de rutas demo
    Route::prefix('demo')->group(function () {
        //Pagina demo para hacer pruebas del tema AdminLTE con librerias
        Route::get('AdminLTEDemo', [AdminLTEDemoController::class, 'index'])->middleware(['auth:sanctum', 'verified'])->name('core.demo.AdminLTEDemo');
    });

    //Monitor de estado de la aplicación
    Route::get('health', HealthCheckResultsController::class)->middleware(['auth:sanctum', 'verified'])->name('core.health');

    //Parametros
    Route::resource('parametros',ParametroController::class)->middleware(['auth:sanctum', 'verified'])->names('core.parametros');
    Route::post('parametros/{id}/restore', [ParametroController::class, 'restore'])->middleware(['auth:sanctum', 'verified'])->name('core.parametros.restore');
    Route::post('parametros/{id}/active', [ParametroController::class, 'active'])->middleware(['auth:sanctum', 'verified'])->name('core.parametros.active');
    Route::post('parametros/{id}/inactive', [ParametroController::class, 'inactive'])->middleware(['auth:sanctum', 'verified'])->name('core.parametros.inactive');
});