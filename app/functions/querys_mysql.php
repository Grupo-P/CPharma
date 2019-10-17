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
?>