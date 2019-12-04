<?php
/*Querys que son ejecutadas en el manejador de base de datos MYSQL*/
	/**********************************************************************************/
	/*
		TITULO: MySQL_Guardar_Auditoria
		FUNCION: capturar y guardar el evento en la auditoria
		RETORNO: query para almacenar la auditoria
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Guardar_Auditoria($accion,$tabla,$registro,$user,$date) {
		$sql = "
		INSERT INTO auditorias
		(accion,tabla,registro,user,created_at,updated_at)
		VALUES 
		('$accion','$tabla','$registro','$user','$date','$date')
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Tasa_Fecha
		FUNCION: Buscar el valor de la tasa en un dia especifico
		RETORNO: Valor de la tasa al dia que se solicito
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Tasa_Fecha($Fecha) {
		$sql = "SELECT tasa FROM dolars where fecha = '$Fecha'";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Guardar_Traslado_Detalle
		FUNCION: capturar y guardar el detalle del traslado
		RETORNO: query para almacenar el traslado
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Guardar_Traslado_Detalle($id_traslado,$id_articulo,$codigo_interno,$codigo_barra,$descripcion,$gravado,$dolarizado,$cantidad,$costo_unit_bs_sin_iva,$costo_unit_usd_sin_iva,$total_imp_bs,$total_imp_usd,$total_bs,$total_usd,$date) {
		$sql = "
		INSERT INTO traslados_detalle
		(id_traslado, id_articulo, codigo_interno, codigo_barra, descripcion, gravado, dolarizado, cantidad, costo_unit_bs_sin_iva, costo_unit_usd_sin_iva, total_imp_bs, total_imp_usd, total_bs, total_usd, created_at, updated_at) 
		VALUES 
		('$id_traslado','$id_articulo','$codigo_interno','$codigo_barra','$descripcion','$gravado','$dolarizado','$cantidad','$costo_unit_bs_sin_iva','$costo_unit_usd_sin_iva','$total_imp_bs','$total_imp_usd','$total_bs','$total_usd','$date','$date')
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Buscar_Traslado_Detalle
		FUNCION: Buscar el valor de la tasa en un dia especifico
		RETORNO: Valor de la tasa al dia que se solicito
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Buscar_Traslado_Detalle($id_traslado) {
		$sql = "SELECT * FROM traslados_detalle WHERE id_traslado = '$id_traslado'";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Rango_Dias_Cero
		FUNCION: Busca la fecha mas recien y la mas antigua en dias en cero
		RETORNO: fecha mas antigua y mas recien
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Rango_Dias_Cero() {
		$sql = "SELECT 
		(SELECT fecha_captura FROM dias_ceros GROUP BY fecha_captura ORDER BY fecha_captura ASC LIMIT 1) AS Inicio,
		(SELECT fecha_captura FROM dias_ceros GROUP BY fecha_captura ORDER BY fecha_captura DESC LIMIT 1) AS Fin
		FROM dias_ceros
		LIMIT 1";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Cuenta_Veces_Dias_Cero
		FUNCION: Contar las veces que esta un articulo en dias en cero en un rango de terminado
		RETORNO: Numero de veces de apariciones en el rango
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Cuenta_Veces_Dias_Cero($IdArticulo,$FInicial,$FFinal) {
		$sql = "
			SELECT 
			COUNT(*) AS Cuenta 
			FROM dias_ceros 
			WHERE dias_ceros.id_articulo = '$IdArticulo' AND (fecha_captura >= '$FInicial' AND `fecha_captura` < '$FFinal')
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Buscar_Orden_Detalle
		FUNCION: Buscar todos los detalles de una orden
		RETORNO: articulos que conforman la orden de compra
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Buscar_Orden_Detalle($codigo_orden) {
		$sql = "SELECT * FROM orden_compra_detalles 
		WHERE codigo_orden = '$codigo_orden' AND estatus = 'ACTIVO'";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QG_Guardar_Dias_EnCero
		PARAMETROS: [$proveedor] Id del provedor solicitado
			[$articulo] Descripcion del articulo
			[$lote] Lote al que pertenece el articulo
			[$fecha_documento] Fecha de la factura
			[$fecha_recepcion] Fecha de recepcion del articulo
			[$fecha_vencimiento] Fecha de vencimiento del articulo
			[$fecha_tope] Fecha tope del compromiso
			[$causa] Causa del compromiso
			[$nota] Notas adicionales
			[$user] Usuario responsable
			[$date] Fecha de creacion del compromiso
		FUNCION: Construir las columnas correspondientes al reporte
		RETORNO: Un String con la query
	 */
	function QG_Guardar_Dias_EnCero($IdArticulo,$CodigoInterno,$Descripcion,$Existencia,$Precio,$FechaCaptura,$user,$date) {
		$sql = "
		INSERT INTO dias_ceros 
		(id_articulo,codigo_articulo,descripcion,existencia,precio,fecha_captura,user,created_at,updated_at)
		VALUES 
		('$IdArticulo','$CodigoInterno','$Descripcion','$Existencia','$Precio','$FechaCaptura','$user','$date','$date')
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QG_Captura_Diaria
		PARAMETROS: [$FechaCaptura] El dia de hoy
		FUNCION: cuenta el total de registos de dias en cero
		RETORNO: no aplica
 	*/
	function QG_Captura_Diaria($FechaCaptura) {
		$sql = "SELECT COUNT(*) AS TotalRegistros
		FROM dias_ceros 
		WHERE dias_ceros.fecha_captura = '$FechaCaptura'
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QG_Guardar_Captura_Diaria
		PARAMETROS: [$FechaCaptura] El dia de hoy
					[$date] valor para creacion y actualizacion
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		RETORNO: no aplica
	 */
	function QG_Guardar_Captura_Diaria($TotalRegistros,$FechaCaptura,$date) {
		$sql = "
		INSERT INTO capturas_diarias 
		(total_registros,fecha_captura,created_at,updated_at)
		VALUES 
		('$TotalRegistros','$FechaCaptura','$date','$date')
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QG_Validar_Captura_Diaria
		PARAMETROS: [$FechaCaptura] El dia de hoy
		FUNCION: valida que la fecha exista en la tabla captura diaria
		RETORNO: no aplica
	*/
	function QG_Validar_Captura_Diaria($FechaCaptura) {
		$sql = "SELECT count(*) AS CuentaCaptura 
		FROM capturas_diarias WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QG_Borrar_DiasCero
		PARAMETROS: [$FechaCaptura] fecha de la captura
		FUNCION: borra los registros de dias en cero de la fecha seleccionada
		RETORNO: no aplica
	*/
	function QG_Borrar_DiasCero($FechaCaptura) {
		$sql = "DELETE FROM dias_ceros WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QG_Borrar_ProductosCaida
		PARAMETROS: [$FechaCaptura] fecha de la captura
		FUNCION: borra los registros de dias en cero de la fecha seleccionada
		RETORNO: no aplica
	 */
	function QG_Borrar_ProductosCaida() {
		$sql = "DELETE FROM productos_caida";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QGuardarProductosCaida
		PARAMETROS: Todos los campos
		FUNCION: guardar en la tabla productos en caida
		RETORNO: no aplica
	 */
	function QGuardarProductosCaida($IdArticulo,$CodigoArticulo,$Descripcion,$Precio,$Existencia,$Dia10,$Dia9,$Dia8,$Dia7,$Dia6,$Dia5,$Dia4,$Dia3,$Dia2,$Dia1,$UnidadesVendidas,$DiasRestantes,$fecha_captura,$user,$created_at,$updated_at) {
		$sql = "
		INSERT INTO productos_caida
		(IdArticulo,CodigoArticulo,Descripcion,Precio,Existencia,Dia10,Dia9,Dia8,Dia7,Dia6,Dia5,Dia4,Dia3,Dia2,Dia1,UnidadesVendidas,DiasRestantes,fecha_captura,user,created_at,updated_at)
		VALUES 
		('$IdArticulo','$CodigoArticulo','$Descripcion','$Precio','$Existencia','$Dia10','$Dia9','$Dia8','$Dia7','$Dia6','$Dia5','$Dia4','$Dia3','$Dia2','$Dia1','$UnidadesVendidas','$DiasRestantes','$fecha_captura','$user','$created_at','$updated_at')
		";
		return $sql;
	}
	 /**********************************************************************************/
	/*
		TITULO: QCapturaCaida
		PARAMETROS: [$FechaCaptura] El dia de hoy
		FUNCION: cuenta el total de registos de productos en caida
		RETORNO: no aplica
	 */
	function QCapturaCaida($FechaCaptura) {
		$sql = "SELECT COUNT(*) AS TotalRegistros
		FROM productos_caida 
		WHERE productos_caida.fecha_captura = '$FechaCaptura'
		";
		return $sql;
	}
	 /**********************************************************************************/
	/*
		TITULO: QGuardarCapturaCaida
		PARAMETROS: [$FechaCaptura] El dia de hoy
					[$date] valor para creacion y actualizacion
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		RETORNO: no aplica
	 */
	function QGuardarCapturaCaida($TotalRegistros,$FechaCaptura,$date) {
		$sql = "
		INSERT INTO captura_caida 
		(total_registros,fecha_captura,created_at,updated_at)
		VALUES 
		('$TotalRegistros','$FechaCaptura','$date','$date')
		";
		return $sql;
	}
	 /**********************************************************************************/
	/*
		TITULO: QValidarCapturaCaida
		PARAMETROS: [$FechaCaptura] El dia de hoy
		FUNCION: valida que la fecha exista en la tabla captura diaria
		RETORNO: no aplica
	 */
	function QValidarCapturaCaida($FechaCaptura) {
		$sql = "SELECT count(*) AS CuentaCaptura
		FROM captura_caida WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
	 /**********************************************************************************/
	 /*
		TITULO: QCuentaExistencia
		PARAMETROS: [$IdArticulo] Id del articulo a buscar
		FUNCION: cuenta el total de repeticiones de un articulo en dias en cero
		RETORNO: no aplica
	 */
	function QCuentaExistencia($IdArticulo,$FInicial,$FFinal) {
		$sql = "
			SELECT 
			COUNT(*) AS Cuenta 
			FROM dias_ceros 
			WHERE dias_ceros.id_articulo = '$IdArticulo' AND (fecha_captura >= '$FInicial' AND `fecha_captura` < '$FFinal')
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QExistenciaDiasCero
		PARAMETROS: [$IdArticulo] Id del articulo a buscar
		FUNCION: cuenta el total de repeticiones de un articulo en dias en cero
		RETORNO: no aplica
		//
	 */
	function QExistenciaDiasCero($IdArticulo,$FechaCaptura) {
		$sql = "
			SELECT existencia 
			FROM dias_ceros 
			WHERE dias_ceros.id_articulo = '$IdArticulo' 
			AND `fecha_captura` = '$FechaCaptura'
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QG_Guardar_Etiqueta_Articulo
		PARAMETROS: $id_articulo,$codigo_articulo,$descripcion,$condicion,$clasificacion,$estatus,$user,$date
		FUNCION: guarda la informacion en la tabla etiquetas
		RETORNO: no aplica
	 */
	function QG_Guardar_Etiqueta_Articulo($id_articulo,$codigo_articulo,$descripcion,$condicion,$clasificacion,$estatus,$user,$date) {
		$sql = "
		INSERT INTO etiquetas
		(id_articulo,codigo_articulo,descripcion,condicion,clasificacion,estatus,user,created_at,updated_at)
		VALUES 
		('$id_articulo','$codigo_articulo','$descripcion','$condicion','$clasificacion','$estatus','$user','$date','$date')
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QG_Captura_Etiqueta
		PARAMETROS: [$FechaCaptura] El dia de hoy
		FUNCION: cuenta el total de registos de dias en cero
		RETORNO: no aplica
	 */
	function QG_Captura_Etiqueta($FechaCaptura) {
		$sql = "SELECT COUNT(*) AS TotalRegistros 
		FROM etiquetas 
		WHERE CONVERT(etiquetas.created_at,date) = '$FechaCaptura'
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QG_Guardar_Captura_Etiqueta
		PARAMETROS: [$FechaCaptura] El dia de hoy
					[$date] valor para creacion y actualizacion
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		RETORNO: no aplica
	 */

	function QG_Guardar_Captura_Etiqueta($TotalRegistros,$FechaCaptura,$date) {
		$sql = "
		INSERT INTO captura_etiqueta
		(total_registros,fecha_captura,created_at,updated_at)
		VALUES 
		('$TotalRegistros','$FechaCaptura','$date','$date')
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QG_Validar_Captura_Etiqueta
		PARAMETROS: [$FechaCaptura] El dia de hoy
		FUNCION: valida que la fecha exista en la tabla captura diaria
		RETORNO: no aplica
	 */
	function QG_Validar_Captura_Etiqueta($FechaCaptura) {
		$sql = "SELECT count(*) AS CuentaCaptura 
		FROM captura_etiqueta WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QG_Borrar_Captura_Etiqueta
		PARAMETROS: [$FechaCaptura] fecha de la captura
		FUNCION: borra los registros de dias en cero de la fecha seleccionada
		RETORNO: no aplica
	 */
	function QG_Borrar_Captura_Etiqueta($FechaCaptura) {
		$sql = "DELETE FROM captura_etiqueta WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
	/**********************************************************************************/
	/*
    TITULO: QG_DiasCero_PrecioAyer
    PARAMETROS: [$IdArticulo] $IdArticulo del articulo a buscar
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
   */
  	function QG_DiasCero_PrecioAyer($IdArticulo,$FechaCaptura) {
			$sql = "
				SELECT precio
				FROM dias_ceros 
				WHERE dias_ceros.id_articulo = '$IdArticulo' 
				AND `fecha_captura` = '$FechaCaptura'
			";
			return $sql;
		}
		/**********************************************************************************/
		/*
		TITULO: QVerCapturaDiaria
		PARAMETROS: [$FechaCaptura] El dia de hoy
		FUNCION: consulta todos los datos de la captura diaria
		RETORNO: no aplica
	 */
	function QVerCapturaDiaria() {
		$sql = "SELECT * FROM capturas_diarias order by fecha_captura desc";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QG_RangoMinTasaVenta
		PARAMETROS: No aplica
		FUNCION: Armar la logica necesaria para ejecutar la query
		RETORNO: String con la query
	 */
	function QG_RangoMinTasaVenta() {
		$sql = "
			SELECT * 
			FROM configuracions 
			WHERE configuracions.variable = 'RangoMinDolar'
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QG_RangoMaxTasaVenta
		PARAMETROS: No aplica
		FUNCION: Armar la logica necesaria para ejecutar la query
		RETORNO: String con la query
	 */
	function QG_RangoMaxTasaVenta() {
		$sql = "
			SELECT * 
			FROM configuracions 
			WHERE configuracions.variable = 'RangoMaxDolar'
		";
		return $sql;
	}
?>