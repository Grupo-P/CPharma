<?php

use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;
use App\Http\Controllers\Core\demo\SandboxController;
use App\Http\Controllers\Core\ParametroController;
use App\Http\Controllers\Core\UserController;
use App\Http\Controllers\Core\RoleController;
use App\Http\Controllers\Core\PermissionController;

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

Route::middleware(['auth:sanctum', 'verified'])->get('/user/profile', [UserController::class, 'profile']);

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('can:dashboard')->name('dashboard');
});

//Grupo de rutas core
Route::prefix('core')->middleware(['auth:sanctum', 'verified'])->group(function () {
    
    //Grupo de rutas demo
    Route::prefix('demo')->group(function () {
        //Pagina demo para hacer pruebas del tema AdminLTE con librerias
        Route::get('sandbox1', [SandboxController::class, 'sandbox1'])->middleware('can:core.demo.sandbox1')->name('core.demo.sandbox1');
        Route::get('sandbox2', [SandboxController::class, 'sandbox2'])->middleware('can:core.demo.sandbox2')->name('core.demo.sandbox2');
    });

    //Monitor de estado de la aplicación
    Route::get('health', HealthCheckResultsController::class)->middleware('can:core.health')->name('core.health');

    //Usuarios
    Route::resource('usuarios',UserController::class)->names('core.usuarios');
    Route::post('usuarios/{id}/restore', [UserController::class, 'restore'])->name('core.usuarios.restore');
    Route::post('usuarios/{id}/active', [UserController::class, 'active'])->name('core.usuarios.active');
    Route::post('usuarios/{id}/inactive', [UserController::class, 'inactive'])->name('core.usuarios.inactive');
    Route::post('usuarios/{id}/lock', [UserController::class, 'lock'])->name('core.usuarios.lock');

    //Parametros
    Route::resource('parametros',ParametroController::class)->names('core.parametros');
    Route::post('parametros/{id}/restore', [ParametroController::class, 'restore'])->name('core.parametros.restore');
    Route::post('parametros/{id}/active', [ParametroController::class, 'active'])->name('core.parametros.active');
    Route::post('parametros/{id}/inactive', [ParametroController::class, 'inactive'])->name('core.parametros.inactive');

    //Roles
    Route::resource('roles',RoleController::class)->names('core.roles');
    Route::post('roles/{id}/restore', [RoleController::class, 'restore'])->name('core.roles.restore');
    Route::post('roles/{id}/active', [RoleController::class, 'active'])->name('core.roles.active');
    Route::post('roles/{id}/inactive', [RoleController::class, 'inactive'])->name('core.roles.inactive');

    //Permisos
    Route::resource('permisos',PermissionController::class)->names('core.permisos');
    Route::post('permisos/{id}/restore', [PermissionController::class, 'restore'])->name('core.permisos.restore');
    Route::post('permisos/{id}/active', [PermissionController::class, 'active'])->name('core.permisos.active');
    Route::post('permisos/{id}/inactive', [PermissionController::class, 'inactive'])->name('core.permisos.inactive');
});