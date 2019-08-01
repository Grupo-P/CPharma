<?php

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

Route::get('/', function() {
    return view('welcome');
});

Route::get('/cuadreDivisa', function() {
    return view('pages.caja.cuadreDivisa');
});

Route::get('/dosificacion', function() {
    return view('pages.caja.dosificaciones');
});

Route::get('/conversionDosis', function() {
    return view('pages.caja.conversionDosis');
});

Route::get('/ACI', function() {
    return view('pages.ACI.ProximamenteACI');
});

Auth::routes();

Route::get('/testS', function() {
    return view('testS');
});

Route::get('/testM', function() {
    return view('testM');
});

Route::get('/sede', function() {
    return view('pages.sedes');
});

Route::get('/reporte', function() {
    return view('pages.reportes');
});

Route::get('/reporte1', function() {
    return view('pages.reporte.reporte1');
});

Route::get('/reporte2', function() {
    return view('pages.reporte.reporte2');
});

Route::get('/reporte3', function() {
    return view('pages.reporte.reporte3');
});

Route::get('/reporte4', function() {
    return view('pages.reporte.reporte4');
});

Route::get('/reporte5', function() {
    return view('pages.reporte.reporte5');
});

Route::get('/reporte6', function() {
    return view('pages.reporte.reporte6');
});

Route::get('/reporte7', function() {
    return view('pages.reporte.reporte7');
});

Route::get('/reporte8', function() {
    return view('pages.reporte.reporte8');
});

Route::get('/reporte9', function() {
    return view('pages.reporte.reporte9');
});

Route::get('/reporte10', function() {
    return view('pages.reporte.reporte10');
});

Route::get('home', 'HomeController@index')->name('home');

Route::resource('dolar', 'DolarController');

Route::resource('empresa', 'EmpresaController');

Route::resource('proveedor', 'ProveedorController');

Route::resource('usuario', 'UserController');

Route::resource('tasaVenta','TasaVentaController');

Route::resource('cartaCompromiso', 'CartaCompromisoController');

Route::resource('rol', 'RoleController');

Route::resource('departamento', 'DepartamentoController');