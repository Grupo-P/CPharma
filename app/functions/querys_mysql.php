<?php
/*Querys que son ejecutadas en el manejador de base de datos MYSQL*/
	/**********************************************************************************/
	/*
		TITULO: MySQL_Guardar_Auditoria
		PARAMETROS: $accion,$tabla,$registro,$user
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
		TITULO: QG_Tasa_Fecha
		PARAMETROS: [$Fecha] Fecha de la que se quiere la tasa
		FUNCION: Buscar el valor de la tasa en un dia especifico
		RETORNO: Valor de la tasa al dia que se solicito
		DESARROLLADO POR: SERGIO COVA
	 */
	function QG_Tasa_Fecha($Fecha) {
		$sql = "SELECT tasa FROM dolars where fecha = '$Fecha'";
		return $sql;
	}
?>