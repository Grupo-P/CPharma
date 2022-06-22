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

Route::get('/test', function() {

});

Route::get('/', function() {
    return view('welcome');
});

Route::resource('falla', 'FallaController');

Route::get('/reporteFalla', function() {
    return view('pages.falla.reporteFalla');
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

Route::get('/ConsultaPrecio', function() {
    return view('pages.consultorP.consultaPrecio');
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

Route::get('/testR', function() {
    return view('testR');
});

Route::get('/sedes_reporte', function() {
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

Route::get('/reporte12', function() {
    return view('pages.reporte.reporte12');
});

Route::get('/reporte13', function() {
    return view('pages.reporte.reporte13');
});

Route::get('/reporte14', function() {
    return view('pages.reporte.reporte14');
});

Route::get('/reporte15', function() {
    return view('pages.reporte.reporte15');
});

Route::get('/reporte16', function() {
    return view('pages.reporte.reporte16');
});

Route::get('/reporte17', function() {
    return view('pages.reporte.reporte17');
});

Route::get('/reporte18', function() {
    return view('pages.reporte.reporte18');
});

Route::get('/reporte19', function() {
    return view('pages.reporte.reporte19');
});

Route::get('/reporte20', function() {
    return view('pages.reporte.reporte20');
});

Route::get('/reporte21', function() {
    return view('pages.reporte.reporte21');
});

Route::get('/reporte22', function() {
    return view('pages.reporte.reporte22');
});

Route::get('/reporte23', function() {
    return view('pages.reporte.reporte23');
});

Route::get('/reporte24', function() {
    return view('pages.reporte.reporte24');
});

Route::get('/reporte25', function() {
    return view('pages.reporte.reporte25');
});

Route::get('/reporte26', function() {
    return view('pages.reporte.reporte26');
});

Route::get('/reporte27', function() {
    return view('pages.reporte.reporte27');
});

Route::get('/reporte28', function() {
    return view('pages.reporte.reporte28');
});

Route::get('/reporte29', function() {
    return view('pages.reporte.reporte29');
});

Route::get('/reporte30', function() {
    return view('pages.reporte.reporte30');
});

Route::get('/reporte31', function() {
    return view('pages.reporte.reporte31');
});


Route::get('/reporte32', function() {
    return view('pages.reporte.reporte32');
});

Route::get('/reporte33', function() {
    return view('pages.reporte.reporte33');
});

Route::get('/reporte34', function() {
    return view('pages.reporte.reporte34');
});

Route::get('/reporte35', function() {
    return view('pages.reporte.reporte35');
});

Route::get('/reporte36', function() {
    return view('pages.reporte.reporte36');
});

Route::get('/reporte37', function() {
    return view('pages.reporte.reporte37');
});

Route::get('/reporte38', function() {
    return view('pages.reporte.reporte38');
});

Route::get('/reporte39', function() {
    return view('pages.reporte.reporte39');
});

Route::get('/reporte40', function() {
    return view('pages.reporte.reporte40');
});

Route::get('/reporte41', function() {
    return view('pages.reporte.reporte41');
});

Route::get('/reporte42', function() {
    return view('pages.reporte.reporte42');
});

Route::get('/reporte43', function() {
    return view('pages.reporte.reporte43');
});

Route::get('/reporte44', function() {
    return view('pages.reporte.reporte44');
});

Route::get('/reporte45', function() {
    return view('pages.reporte.reporte45');
});

Route::match(['post', 'get'], '/reporte46', function() {
    return view('pages.reporte.reporte46');
});

Route::match(['post', 'get'], '/reporte47', function() {
    return view('pages.reporte.reporte47');
});

Route::get('/reporte48', function() {
    return view('pages.reporte.reporte48');
});

Route::get('/reporte49', 'Reporte49Controller@reporte49');

Route::get('/reporte50', 'Reporte50Controller@reporte50');

Route::view('/reporte51', 'pages.reporte.reporte51');

Route::get('/seccion1', function() {
    return view('pages.reporte.seccion1');
});

Route::get('/seccion2', function() {
    return view('pages.reporte.seccion2');
});

Route::get('/diascero', function() {
    return view('pages.diascero.index');
});

Route::get('/productoscaida', function() {
    return view('pages.productoscaida.index');
});

Route::get('/CapturaEtiqueta', function() {
    return view('pages.etiqueta.CapturaEtiqueta');
});

Route::get('/Generar_Etiquetas', function() {
    return view('pages.etiqueta.Generar_Etiquetas');
});

Route::get('/Generar_Etiquetas_Todo', function() {
    return view('pages.etiqueta.Generar_Etiquetas_Todo');
});

Route::get('/Generar_Etiqueta_Unica', function() {
    return view('pages.etiqueta.Generar_Etiqueta_Unica');
});

Route::get('/Generar_Etiqueta_Promocion', function() {
    return view('pages.etiqueta.Generar_Etiqueta_Promocion');
});

Route::get('/Generar_Etiqueta_Promocion_G', function() {
    return view('pages.etiqueta.Generar_Etiqueta_Promocion_G');
});

Route::get('/SearchAjuste', function() {
    return view('pages.traslado.SearchAjuste');
});

Route::get('/GuiaEnvio', function() {
    return view('pages.traslado.guia_etiqueta');
});

Route::get('/AnularOrdenCompra', function() {
    return view('pages.ordenCompra.anular');
});

Route::get('/RechazarOrdenCompra', function() {
    return view('pages.ordenCompra.rechazar');
});

Route::get('/IngresarOrdenCompra', function() {
    return view('pages.ordenCompra.ingresar');
});

Route::get('/DigitalOrdenCompra', function() {
    return view('pages.ordenCompra.digital');
});

Route::resource('/compras/reclamos', 'ReclamoController');

Route::get('/ConsultorCompra', function() {
    return view('pages.consultorP.consultorCompra');
});

Route::get('/MenuArticulosExcel', function() {
    return view('pages.articulosExcel.menuArticulosExcel');
});

Route::get('/ArticulosWeb', function() {
    return view('pages.articulosExcel.articulosWeb');
});

Route::get('/ArticulosYummy', function() {
    return view('pages.articulosExcel.articulosYummy');
});

Route::get('/ArticulosPedidosYa', function() {
    return view('pages.articulosExcel.articulosPedidosYa');
});

Route::get('/ArticulosPedidosYaCorridas', function() {
    return view('pages.articulosExcel.articulosPedidosYaCorridas');
});

Route::get('/codigosPrincipal', function() {
    return view('pages.articulosExcel.codigosPrincipal');
});

Route::get('/CorridaPrecios', function() {
    return view('pages.corridas.corridaPrecio');
});

Route::get('/AuditoriaCorridaPrecios', function() {
    return view('pages.corridas.auditoria_corridas');
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

Route::resource('sede', 'SedeController');

Route::resource('conexion', 'ConexionController');

Route::resource('configuracion', 'ConfiguracionController');

Route::resource('auditoria', 'AuditoriaController');

Route::resource('etiqueta', 'EtiquetaController');

Route::get('/traslado/finalizarConReclamo', 'TrasladoController@finalizarConReclamo');
Route::post('/traslado/finalizarConReclamo', 'TrasladoController@finalizarConReclamo');

Route::post('/traslado/validar', 'TrasladoController@validar');

Route::resource('traslado', 'TrasladoController');

Route::resource('ordenCompra', 'OrdenCompraController');

Route::resource('ordenCompraDetalle', 'OrdenCompraDetalleController');

Route::resource('unidad', 'UnidadController');

Route::get('articuloUnidad', function() {
    return view('pages.unidad.articuloUnidad');
});

Route::resource('inventario', 'InventarioController');

Route::get('/inventario/ajuste/{id}', 'InventarioController@ajuste')->name('inventario.ajuste');

Route::get('/inventarioProveedor', function() {
    return view('pages.inventario.por_proveedor');
});

Route::get('/inventarioDescripcion', function() {
    return view('pages.inventario.por_descripcion');
});

Route::get('/inventarioCodigoBarra', function() {
    return view('pages.inventario.por_codigobarra');
});

Route::get('/inventarioMarca', function() {
    return view('pages.inventario.por_marca');
});

Route::get('/inventarioAleatorio', function() {
    return view('pages.inventario.por_aleatorio');
});

Route::resource('inventarioDetalle', 'InventarioDetalleController');

Route::resource('categoria', 'CategoriaController');

Route::resource('subcategoria', 'SubcategoriaController');

Route::resource('categorizacion', 'CategorizacionController');

Route::resource('surtido', 'SurtidoController');
Route::get('surtido/{surtido}/anular', 'SurtidoController@anular');

Route::resource('trackimagen', 'TrackImagenController');
Route::get('/procesarTxt', 'TrackImagenController@procesarTxt');

Route::get('/syncategorias', 'CategorizacionController@syncategorias');

Route::resource('/cotizacion', 'CotizacionController');


//***************************** RRHH routing *****************************//
Route::resource('candidatos', 'RH_CandidatoController');
Route::get('/procesos_candidatos', 'RH_CandidatoController@procesos');
Route::get('/expediente_candidatos', 'RH_CandidatoController@expediente');
Route::get('/motivo_rechazo', 'RH_CandidatoController@motivo_rechazo');

Route::resource('procesos_referencias', 'RH_CandidatoEmpresaController');
Route::get('/primer_empleo', 'RH_CandidatoEmpresaController@primer_empleo');

Route::resource('pruebas', 'RH_PruebaController');

Route::resource('entrevistas', 'RH_EntrevistaController');

Route::resource('vacantes', 'RH_VacanteController');

Route::resource('examenesm', 'RH_ExamenesMController');

Route::resource('empresaReferencias', 'RH_EmpresaReferenciaController');

Route::resource('laboratorio', 'RH_LaboratorioController');

Route::resource('contactos', 'RH_ContactoEmpresaController');

Route::resource('convocatoria', 'RH_ConvocatoriaController');

Route::resource('fases', 'RH_FaseController');

Route::resource('candidatos_pruebas', 'RH_CandidatoPruebaController');

Route::resource('gestor_fases', 'RH_GestorFaseController');

Route::resource('practicas', 'RH_PracticaController');

//*************************** TESORERIA routing ***************************//
Route::resource('movimientos', 'TS_MovimientoController');
Route::get('/diferidos', 'TS_MovimientoController@diferidos')->name('diferidos');

Route::get('/proveedores/validar', 'ContProveedorController@validar');
Route::resource('proveedores', 'ContProveedorController');

Route::resource('cuentas', 'ContCuentasController');
Route::get('/cuentas/{id}/delete', 'ContCuentasController@delete');

Route::get('/bancos/validar', 'ContBancoController@validar');
Route::resource('bancos', 'ContBancoController');

Route::resource('deudas', 'ContDeudasController');
Route::post('/deudas/validar', 'ContDeudasController@validar');

Route::get('/pizarra-deudas', 'ContDeudasController@pizarra');

Route::resource('reclamos', 'ContReclamoController');
Route::post('/reclamos/validar', 'ContReclamoController@validar');

Route::resource('ajuste', 'ContAjusteController');

Route::post('/bancarios/validar', 'ContPagoBancarioController@validar');
Route::resource('bancarios', 'ContPagoBancarioController');
Route::get('/bancarios/soporte/{id}', 'ContPagoBancarioController@soporte');
Route::get('/bancarios/notificar/{id}', 'ContPagoBancarioController@notificar');

Route::post('/efectivoFTN/validar', 'ContPagoEfectivoFTNController@validar');
Route::resource('efectivoFTN', 'ContPagoEfectivoFTNController');
Route::get('/efectivoFTN/soporte/{id}', 'ContPagoEfectivoFTNController@soporte');
Route::get('/contabilidad/diferidosFTN', 'ContPagoEfectivoFTNController@diferidos')->name('contabilidad.diferidosFTN');

Route::post('/efectivo/validarFAU', 'ContPagoEfectivoFAUController@validar');
Route::resource('efectivoFAU', 'ContPagoEfectivoFAUController');
Route::get('/efectivoFAU/soporte/{id}', 'ContPagoEfectivoFAUController@soporte');
Route::get('/contabilidad/diferidosFAU', 'ContPagoEfectivoFAUController@diferidos')->name('contabilidad.diferidosFAU');

Route::post('/efectivo/validarFM', 'ContPagoEfectivoFMController@validar');
Route::resource('efectivoFM', 'ContPagoEfectivoFMController');
Route::get('/efectivoFM/soporte/{id}', 'ContPagoEfectivoFMController@soporte');
Route::get('/contabilidad/diferidosFM', 'ContPagoEfectivoFMController@diferidos')->name('contabilidad.diferidosFM');

Route::post('/efectivoFLL/validar', 'ContPagoEfectivoFLLController@validar');
Route::resource('efectivoFLL', 'ContPagoEfectivoFLLController');
Route::get('/efectivoFLL/soporte/{id}', 'ContPagoEfectivoFLLController@soporte');
Route::get('/contabilidad/diferidosFLL', 'ContPagoEfectivoFLLController@diferidos')->name('contabilidad.diferidosFLL');

Route::post('/bolivaresFTN/validar', 'ContPagoBolivaresFTNController@validar');
Route::resource('bolivaresFTN', 'ContPagoBolivaresFTNController');
Route::get('/bolivaresFTN/soporte/{id}', 'ContPagoBolivaresFTNController@soporte');
Route::get('/contabilidad/diferidosBolivaresFTN', 'ContPagoBolivaresFTNController@diferidos')->name('contabilidad.diferidosBolivaresFTN');

Route::post('/bolivaresFM/validar', 'ContPagoBolivaresFMController@validar');
Route::resource('bolivaresFM', 'ContPagoBolivaresFMController');
Route::get('/bolivaresFM/soporte/{id}', 'ContPagoBolivaresFMController@soporte');
Route::get('/contabilidad/diferidosBolivaresFM', 'ContPagoBolivaresFMController@diferidos')->name('contabilidad.diferidosBolivaresFM');

Route::post('/bolivares/validarFAU', 'ContPagoBolivaresFAUController@validar');
Route::resource('bolivaresFAU', 'ContPagoBolivaresFAUController');
Route::get('/bolivaresFAU/soporte/{id}', 'ContPagoBolivaresFAUController@soporte');
Route::get('/contabilidad/diferidosBolivaresFAU', 'ContPagoBolivaresFAUController@diferidos')->name('contabilidad.diferidosBolivaresFAU');

Route::post('/bolivaresFLL/validar', 'ContPagoBolivaresFLLController@validar');
Route::resource('bolivaresFLL', 'ContPagoBolivaresFLLController');
Route::get('/bolivaresFLL/soporte/{id}', 'ContPagoBolivaresFLLController@soporte');
Route::get('/contabilidad/diferidosBolivaresFLL', 'ContPagoBolivaresFLLController@diferidos')->name('contabilidad.diferidosBolivaresFLL');

Route::get('/reportes', 'ContReporteController@index');
Route::get('/reportes/movimientos-por-proveedor', 'ContReporteController@movimientos_por_proveedor');
Route::get('/reportes/movimientos-bancarios', 'ContReporteController@movimientos_bancarios');
Route::get('/reportes/deudas-por-fecha', 'ContReporteController@deudas_por_fecha');
Route::get('/reportes/pagos-por-fecha', 'ContReporteController@pagos_por_fecha');
Route::get('/reportes/reporte-por-cuentas', 'ContReporteController@reporte_por_cuentas');

Route::resource('/conciliaciones', 'ContConciliacionesController');

Route::get('/trasladoRecibir/pdf/{sede}', 'TrasladoRecibirController@pdf');
Route::resource('/trasladoRecibir', 'TrasladoRecibirController');
Route::get('/trasladoRecibir/{codigo_barra}/{sede}', 'TrasladoRecibirController@edit');
Route::get('/trasladoRecibir/limpiar', 'TrasladoRecibirController@destroy');

Route::resource('prepagados', 'ContPrepagadosController');

Route::resource('tasas', 'ContTasas');

Route::resource('corrida', 'ContCorridas');

Route::view('verificadorPagos', 'pages.verificadorPagos.index');
Route::view('verificadorPagosAjax', 'pages.verificadorPagos.ajax');
