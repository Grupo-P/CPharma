<?php
/*Funciones Generales al sistema CPharma*/
	/**********************************************************************************/
	/*
		TITULO: FG_Nombre_Sede
		PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
		FUNCION: Retornar el nombre de la sede con la que se esta conectando
		RETORNO: Nombre de la sede conectada
		DESAROLLADO POR: SERGIO COVA
	 */
	function FG_Nombre_Sede($SedeConnection) {
		switch($SedeConnection) {
		/*INICIO BLOQUE DE FTN*/
			case 'FTN':
				$sede = SedeFTN;
				return $sede;
			break;
			case 'FTNFLL':
				$sede = SedeFLLOFF;
				return $sede;
			break;
			case 'FTNFAU':
				$sede = SedeFAUOFF;
				return $sede;
			break;
		/*FIN BLOQUE DE FTN*/
		/*INICIO BLOQUE DE FLL*/
			case 'FLL':
				$sede = SedeFLL;
				return $sede;
			break;
			case 'FLLFTN':
				$sede = SedeFTNOFF;
				return $sede;
			break;
			case 'FLLFAU':
				$sede = SedeFAUOFF;
				return $sede;
			break;
		/*FIN BLOQUE DE FLL*/
		/*INICIO BLOQUE DE FAU*/
			case 'FAU':
				$sede = SedeFAU;
				return $sede;
			break;
			case 'FAUFTN':
				$sede = SedeFTNOFF;
				return $sede;
			break;
			case 'FAUFLL':
				$sede = SedeFLLOFF;
				return $sede;
			break;
		/*FIN BLOQUE DE FAU*/
		/*INICIO BLOQUE DE GRUPO P*/ 
			case 'GP':
				$sede = SedeGP;
				return $sede;
			break;
			case 'GPFTN':
				$sede = SedeFTNOFF;
				return $sede;
			break;
			case 'GPFLL':
				$sede = SedeFLLOFF;
				return $sede;
			break;
			case 'GPFAU':
				$sede = SedeFAUOFF;
				return $sede;
			break;
		/*FIN BLOQUE DE GRUPO P*/
		/*INICIO BLOQUE DE TEST*/  
			case 'DBs':
				$sede = SedeDBs;
				return $sede;
			break;
			case 'DBm':
				$sede = SedeDBm;
				return $sede;
			break;
		/*FIN BLOQUE DE TEST*/
		}
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Conectar_Smartpharma
		PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
		FUNCION: Conectar el sistema con la sede que se desea
		RETORNO: Conexion a Smartpharma
		DESAROLLADO POR: SERGIO COVA
	 */
	function FG_Conectar_Smartpharma($SedeConnection) {
		switch($SedeConnection) {
		/*INICIO BLOQUE DE FTN*/
			case 'FTN':
				$connectionInfo = array(
					"Database"=>nameFTN,
					"UID"=>userFTN,
					"PWD"=>passFTN
				);
				$conn = sqlsrv_connect(serverFTN,$connectionInfo);
				return $conn;
			break;
			case 'FTNFLL':
				$connectionInfo = array(
					"Database"=>nameFLLOFF,
					"UID"=>userFTN,
					"PWD"=>passFTN
				);
				$conn = sqlsrv_connect(serverFTN,$connectionInfo);
				return $conn;
			break;
			case 'FTNFAU':
				$connectionInfo = array(
					"Database"=>nameFAUOFF,
					"UID"=>userFTN,
					"PWD"=>passFTN
				);
				$conn = sqlsrv_connect(serverFTN,$connectionInfo);
				return $conn;
			break;
		/*FIN BLOQUE DE FTN*/
		/*INICIO BLOQUE DE FLL*/
			case 'FLL':
				$connectionInfo = array(
					"Database"=>nameFLL,
					"UID"=>userFLL,
					"PWD"=>passFLL
				);
				$conn = sqlsrv_connect(serverFLL,$connectionInfo);
				return $conn;
			break;
			case 'FLLFTN':
				$connectionInfo = array(
					"Database"=>nameFTNOFF,
					"UID"=>userFLL,
					"PWD"=>passFLL
				);
				$conn = sqlsrv_connect(serverFLL,$connectionInfo);
				return $conn;
			break;
			case 'FLLFAU':
				$connectionInfo = array(
					"Database"=>nameFAUOFF,
					"UID"=>userFLL,
					"PWD"=>passFLL
				);
				$conn = sqlsrv_connect(serverFLL,$connectionInfo);
				return $conn;
			break;
		/*FIN BLOQUE DE FLL*/
		/*INICIO BLOQUE DE FAU*/
			case 'FAU':
				$connectionInfo = array(
					"Database"=>nameFAU,
					"UID"=>userFAU,
					"PWD"=>passFAU
				);
				$conn = sqlsrv_connect(serverFAU,$connectionInfo);
				return $conn;
			break;
			case 'FAUFTN':
				$connectionInfo = array(
					"Database"=>nameFTNOFF,
					"UID"=>userFAU,
					"PWD"=>passFAU
				);
				$conn = sqlsrv_connect(serverFAU,$connectionInfo);
				return $conn;
			break;
			case 'FAUFLL':
				$connectionInfo = array(
					"Database"=>nameFLLOFF,
					"UID"=>userFAU,
					"PWD"=>passFAU
				);
				$conn = sqlsrv_connect(serverFAU,$connectionInfo);
				return $conn;
			break;
		/*FIN BLOQUE DE FAU*/ 
		/*INICIO BLOQUE DE GRUPO P*/
			case 'GP':
				$connectionInfo = array(
					"Database"=>nameGP,
					"UID"=>userGP,
					"PWD"=>passGP
				);
				$conn = sqlsrv_connect(serverGP,$connectionInfo);
				return $conn;
			break;
			case 'GPFTN':
				$connectionInfo = array(
					"Database"=>nameFTNOFF,
					"UID"=>userGP,
					"PWD"=>passGP
				);
				$conn = sqlsrv_connect(serverGP,$connectionInfo);
				return $conn;
			break;
			case 'GPFLL':
				$connectionInfo = array(
					"Database"=>nameFLLOFF,
					"UID"=>userGP,
					"PWD"=>passGP
				);
				$conn = sqlsrv_connect(serverGP,$connectionInfo);
				return $conn;
			break;
			case 'GPFAU':
				$connectionInfo = array(
					"Database"=>nameFAUOFF,
					"UID"=>userGP,
					"PWD"=>passGP
				);
				$conn = sqlsrv_connect(serverGP,$connectionInfo);
				return $conn;
			break;
		/*FIN BLOQUE DE GRUPO P*/ 
		/*INICIO BLOQUE DE TEST*/ 
			case 'DBs':
				$connectionInfo = array(
					"Database"=>nameDBs,
					"UID"=>userDBs,
					"PWD"=>passDBs
				);
				$conn = sqlsrv_connect(serverDBs,$connectionInfo);
				return $conn;
			break;
			case 'DBm':
				$connectionInfo = array(
					"Database"=>nameDBm,
					"UID"=>userDBm,
					"PWD"=>passDBm
				);
				$conn = sqlsrv_connect(serverDBm,$connectionInfo);
				return $conn;
			break;
			/*FIN BLOQUE DE TEST*/
		}
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Conectar_CPharma
		PARAMETROS: No Aplica
		FUNCION: Conexion con servidor de XAMPP
		RETORNO: Conexion a XAMPP
		DESARROLLADO POR: SERGIO COVA
	*/
	function FG_Conectar_CPharma() {
		$conexion = mysqli_connect(serverCP,userCP,passCP);
		mysqli_select_db($conexion,nameCP);
    return $conexion;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Limpiar_Texto
		PARAMETROS: [$texto] String que sera formateado
		FUNCION: limpia el texto de caracteres no imprimibles
		RETORNO: Texto limpio
		DESARROLLADO POR: SERGIO COVA
		ACTUALIZADO POR:  MANUEL HENRIQUEZ 07/10/2019
	 */
	function FG_Limpiar_Texto($texto) {
		$texto = utf8_encode($texto);	
		$texto = preg_replace("/[^A-Za-z0-9_\-\&\ñ\Ñ\'\(\)\.\,\s][á|é|í|ó|ú|Á|É|Í|Ó|Ú]/",'',$texto);
		return $texto;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Guardar_Auditoria
		PARAMETROS: [$accion,$tabla,$registro,$user]
		FUNCION: capturar y guardar el evento en la auditoria
		RETORNO: no aplica
		DESARROLLADO POR: SERGIO COVA
	 */
	function FG_Guardar_Auditoria($accion,$tabla,$registro) {
		$connCPharma = FG_Conectar_CPharma();
		$date = new DateTime('now');
		$date = $date->format("Y-m-d H:i:s");
		$user = auth()->user()->name;
		$sql = MySQL_Guardar_Auditoria($accion,$tabla,$registro,$user,$date);
		mysqli_query($connCPharma,$sql);
		mysqli_close($connCPharma);
	}
?>