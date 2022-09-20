<?php

use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;
use App\Http\Controllers\Core\demo\AdminLTEDemoController;
use App\Http\Controllers\Core\ParametroController;
use App\Http\Controllers\Core\UserController;

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

Route::get('/register', function () {
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
Route::prefix('core')->middleware(['auth:sanctum', 'verified'])->group(function () {
    
    //Grupo de rutas demo
    Route::prefix('demo')->group(function () {
        //Pagina demo para hacer pruebas del tema AdminLTE con librerias
        Route::get('AdminLTEDemo', [AdminLTEDemoController::class, 'index'])->name('core.demo.AdminLTEDemo');
    });

    //Monitor de estado de la aplicación
    Route::get('health', HealthCheckResultsController::class)->name('core.health');

    //Usuarios
    Route::resource('usuarios',UserController::class)->names('core.usuarios');
    Route::post('usuarios/{id}/restore', [UserController::class, 'restore'])->name('core.usuarios.restore');
    Route::post('usuarios/{id}/active', [UserController::class, 'active'])->name('core.usuarios.active');
    Route::post('usuarios/{id}/inactive', [UserController::class, 'inactive'])->name('core.usuarios.inactive');

    //Parametros
    Route::resource('parametros',ParametroController::class)->names('core.parametros');
    Route::post('parametros/{id}/restore', [ParametroController::class, 'restore'])->name('core.parametros.restore');
    Route::post('parametros/{id}/active', [ParametroController::class, 'active'])->name('core.parametros.active');
    Route::post('parametros/{id}/inactive', [ParametroController::class, 'inactive'])->name('core.parametros.inactive');
});