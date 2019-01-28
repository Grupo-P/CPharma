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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/test', function () {
    return view('test');
});

Route::get('/reporte', function () {
    return view('pages.reportes');
});

Route::get('/reporte1', function () {
    return view('pages.reporte.reporte1');
});

Route::get('/reporte2', function () {
    return view('pages.reporte.reporte2');
});

Route::get('home', 'HomeController@index')->name('home');

Route::resource('empresa', 'EmpresaController');

Route::resource('proveedor', 'ProveedorController');

Route::resource('usuario', 'UserController');