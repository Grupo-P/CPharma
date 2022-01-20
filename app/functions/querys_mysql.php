<?php
	function MySQL_Guardar_Auditoria_Corrida($evaluados,$cont_exito,$cont_falla,$cont_cambios,$cont_noCambio,$operador,$tipoCorrida,$tasaCalculo,$fecha,$hora,$fallas) {
		$sql = "
		INSERT INTO auditoria_corridas (evaluados, exitos, fallas, cambios, nocambio, operador, tipocorrida, tasacalculo, fecha, hora, observacion)
		VALUES 
		('$evaluados','$cont_exito','$cont_falla','$cont_cambios','$cont_noCambio','$operador','$tipoCorrida','$tasaCalculo','$fecha','$hora','$fallas')
		";
		return $sql;
	}
/*Querys que son ejecutadas en el manejador de base de datos MYSQL*/
	/**********************************************************************************/
	/*
		TITULO: MySQL_Guardar_Auditoria
		FUNCION: capturar y guardar el evento en la auditoria
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
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Tasa_Fecha($Fecha) {
		$sql = "SELECT tasa FROM dolars where fecha = '$Fecha'";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Tasa_Fecha
		FUNCION: Buscar el valor de la tasa en un dia especifico
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Tasa_Fecha_Venta($Fecha) {
		$sql = "SELECT tasa FROM tasa_ventas where fecha = '$Fecha' and moneda = 'Dolar'";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Tasa_Fecha_Venta_General
		FUNCION: Buscar el valor de la tasa en un dia especifico
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Tasa_Fecha_Venta_General() {
		$sql = "SELECT tasa FROM tasa_ventas where moneda = 'Dolar'";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Guardar_Traslado_Detalle
		FUNCION: capturar y guardar el detalle del traslado
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
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Buscar_Traslado_Detalle($id_traslado) {
		$sql = "SELECT * FROM traslados_detalle WHERE id_traslado = '$id_traslado' ORDER BY descripcion ASC";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Rango_Dias_Cero
		FUNCION: Busca la fecha mas recien y la mas antigua en dias en cero
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
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Buscar_Orden_Detalle($codigo_orden) {
		$sql = "SELECT * FROM orden_compra_detalles 
		WHERE codigo_orden = '$codigo_orden' AND estatus = 'ACTIVO'";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Guardar_Dias_EnCero
		FUNCION: Construir las columnas correspondientes al reporte
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Guardar_Dias_EnCero($IdArticulo,$CodigoInterno,$Descripcion,$Existencia,$Precio,$FechaCaptura,$user,$date,$PrecioDolar) {
		$sql = "
		INSERT INTO dias_ceros 
		(id_articulo,codigo_articulo,descripcion,existencia,precio,fecha_captura,user,created_at,updated_at,precio_dolar)
		VALUES 
		('$IdArticulo','$CodigoInterno','$Descripcion','$Existencia','$Precio','$FechaCaptura','$user','$date','$date','$PrecioDolar')
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Captura_Diaria
		FUNCION: cuenta el total de registos de dias en cero
		DESARROLLADO POR: SERGIO COVA
 	*/
	function MySQL_Captura_Diaria($FechaCaptura) {
		$sql = "SELECT COUNT(*) AS TotalRegistros
		FROM dias_ceros 
		WHERE dias_ceros.fecha_captura = '$FechaCaptura'
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Guardar_Captura_Diaria
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Guardar_Captura_Diaria($TotalRegistros,$FechaCaptura,$date) {
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
		TITULO: MySQL_Validar_Captura_Diaria
		FUNCION: valida que la fecha exista en la tabla captura diaria
		DESARROLLADO POR: SERGIO COVA
	*/
	function MySQL_Validar_Captura_Diaria($FechaCaptura) {
		$sql = "SELECT count(*) AS CuentaCaptura 
		FROM capturas_diarias WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Borrar_DiasCero
		FUNCION: borra los registros de dias en cero de la fecha seleccionada
		DESARROLLADO POR: SERGIO COVA
	*/
	function MySQL_Borrar_DiasCero($FechaCaptura) {
		$sql = "DELETE FROM dias_ceros WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Borrar_ProductosCaida
		FUNCION: borra los registros de dias en cero de la fecha seleccionada
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Borrar_ProductosCaida() {
		$sql = "DELETE FROM productos_caida";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Guardar_Productos_Caida
		FUNCION: guardar en la tabla productos en caida
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Guardar_Productos_Caida($IdArticulo,$CodigoArticulo,$Descripcion,$Precio,$Existencia,$Dia10,$Dia9,$Dia8,$Dia7,$Dia6,$Dia5,$Dia4,$Dia3,$Dia2,$Dia1,$UnidadesVendidas,$DiasRestantes,$fecha_captura,$user,$created_at,$updated_at) {
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
		TITULO: MySQL_Captura_Caida
		FUNCION: cuenta el total de registos de productos en caida
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Captura_Caida($FechaCaptura) {
		$sql = "SELECT COUNT(*) AS TotalRegistros
		FROM productos_caida 
		WHERE productos_caida.fecha_captura = '$FechaCaptura'
		";
		return $sql;
	}
	 /**********************************************************************************/
	/*
		TITULO: MySQL_Guardar_Captura_Caida
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Guardar_Captura_Caida($TotalRegistros,$FechaCaptura,$date) {
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
		TITULO: MySQL_Validar_Captura_Caida
		FUNCION: valida que la fecha exista en la tabla captura diaria
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Validar_Captura_Caida($FechaCaptura) {
		$sql = "SELECT count(*) AS CuentaCaptura
		FROM captura_caida WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
	 /**********************************************************************************/
	 /*
		TITULO: MySQL_Cuenta_Existencia
		FUNCION: cuenta el total de repeticiones de un articulo en dias en cero
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Cuenta_Existencia($IdArticulo,$FInicial,$FFinal) {
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
		TITULO: MySQL_Existencia_Dias_Cero
		FUNCION: cuenta el total de repeticiones de un articulo en dias en cero
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Existencia_Dias_Cero($IdArticulo,$FechaCaptura) {
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
		TITULO: MySQL_Guardar_Etiqueta_Articulo
		FUNCION: guarda la informacion en la tabla etiquetas
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Guardar_Etiqueta_Articulo($id_articulo,$codigo_articulo,$descripcion,$condicion,$clasificacion,$estatus,$user,$date) {
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
		TITULO: MySQL_Captura_Etiqueta
		FUNCION: cuenta el total de registos de dias en cero
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Captura_Etiqueta($FechaCaptura) {
		$sql = "SELECT COUNT(*) AS TotalRegistros 
		FROM etiquetas 
		WHERE CONVERT(etiquetas.created_at,date) = '$FechaCaptura'
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Guardar_Captura_Etiqueta
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Guardar_Captura_Etiqueta($TotalRegistros,$FechaCaptura,$date) {
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
		TITULO: MySQL_Validar_Captura_Etiqueta
		FUNCION: valida que la fecha exista en la tabla captura diaria
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Validar_Captura_Etiqueta($FechaCaptura) {
		$sql = "SELECT count(*) AS CuentaCaptura 
		FROM captura_etiqueta WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Borrar_Captura_Etiqueta
		FUNCION: borra los registros de dias en cero de la fecha seleccionada
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Borrar_Captura_Etiqueta($FechaCaptura) {
		$sql = "DELETE FROM captura_etiqueta WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
	/**********************************************************************************/
	/*
    TITULO: MySQL_DiasCero_PrecioAyer
    FUNCION: Query que genera el detalle del articulo solicitado
    DESARROLLADO POR: SERGIO COVA
   */
  	function MySQL_DiasCero_PrecioAyer($IdArticulo,$FechaCaptura) {
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
    TITULO: MySQL_DiasCero_PrecioAyer
    FUNCION: Query que genera el detalle del articulo solicitado
    DESARROLLADO POR: SERGIO COVA
   */
  	function MySQL_DiasCero_PrecioAyer_Dolar($IdArticulo,$FechaCaptura) {
			$sql = "
				SELECT precio_dolar
				FROM dias_ceros 
				WHERE dias_ceros.id_articulo = '$IdArticulo' 
				AND `fecha_captura` = '$FechaCaptura'
			";
			return $sql;
		}
	/****************************************************************************/
	/*
		TITULO: MySQL_Ver_CapturaDiaria
		FUNCION: consulta todos los datos de la captura diaria
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Ver_CapturaDiaria() {
		$sql = "SELECT * FROM capturas_diarias order by fecha_captura desc";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_RangoMinTasaVenta
		FUNCION: Armar la logica necesaria para ejecutar la query
		DESARROLLADO POR: MANUEL HENRIQUEZ
	 */
	function MySQL_RangoMinTasaVenta() {
		$sql = "
			SELECT * 
			FROM configuracions 
			WHERE configuracions.variable = 'RangoMinDolar'
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_RangoMaxTasaVenta
		FUNCION: Armar la logica necesaria para ejecutar la query
		DESARROLLADO POR: MANUEL HENRIQUEZ
	 */
	function MySQL_RangoMaxTasaVenta() {
		$sql = "
			SELECT * 
			FROM configuracions 
			WHERE configuracions.variable = 'RangoMaxDolar'
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Tasa_Conversion
		FUNCION: Buscar el valor de la tasa en un dia especifico
		DESARROLLADO POR: MANUEL HENRIQUEZ
	 */
	function MySQL_Tasa_Conversion($Fecha,$Moneda) {
		$conCP = FG_Conectar_CPharma();
		$consulta = "
			SELECT tasa 
			FROM tasa_ventas 
			WHERE fecha = '$Fecha'
			AND moneda = '$Moneda'
			AND estatus = 'ACTIVO'
		";
		$resultado = mysqli_query($conCP,$consulta);
		return $resultado;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Guardar_Catagoria_Articulo
		FUNCION: guarda la informacion en la tabla categorizacions
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Guardar_Catagoria_Articulo($IdArticulo,$CodigoInterno,$CodigoBarra,$Descripcion,$Marca,$codigo_categoria,$codigo_subcategoria,$estatus,$user,$date) {
		$sql = "
		INSERT INTO categorizacions
		(id_articulo,codigo_interno,codigo_barra,descripcion,marca,codigo_categoria,codigo_subcategoria,estatus,user,created_at,updated_at)
		VALUES 
		('$IdArticulo','$CodigoInterno','$CodigoBarra','$Descripcion','$Marca','$codigo_categoria','$codigo_subcategoria','$estatus','$user','$date','$date')
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Captura_Categoria
		FUNCION: cuenta el total de registos de dias en cero
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Captura_Categoria($FechaCaptura) {
		$sql = "SELECT COUNT(*) AS TotalRegistros 
		FROM categorizacions 
		WHERE CONVERT(categorizacions.created_at,date) = '$FechaCaptura'
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Guardar_Captura_Categoria
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Guardar_Captura_Categoria($TotalRegistros,$FechaCaptura,$date) {
		$sql = "
		INSERT INTO captura_categoria
		(total_registros,fecha_captura,created_at,updated_at)
		VALUES 
		('$TotalRegistros','$FechaCaptura','$date','$date')
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Validar_Captura_Categoria
		FUNCION: valida que la fecha exista en la tabla captura diaria
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Validar_Captura_Categoria($FechaCaptura) {
		$sql = "SELECT count(*) AS CuentaCaptura 
		FROM captura_categoria WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: MySQL_Borrar_Captura_Categoria
		FUNCION: borra los registros de dias en cero de la fecha seleccionada
		DESARROLLADO POR: SERGIO COVA
	 */
	function MySQL_Borrar_Captura_Categoria($FechaCaptura) {
		$sql = "DELETE FROM captura_categoria WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
?>
