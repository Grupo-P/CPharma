<?php
/*Funciones Generales al sistema CPharma*/
	/**********************************************************************************/
	/*
		TITULO: FG_Armar_Json
		PARAMETROS: [$sql] Cadena de sql a ejecutar,
					[$SedeConnection] Siglas de la sede para la conexion
		FUNCION: Armar un arreglo Json con las palabras claves encontradas
		RETORNO: Arreglo Json
		DESAROLLADO POR: SERGIO COVA
	 */
	function FG_Armar_Json($sql,$SedeConnection) {
		$result = FG_Consulta_Especial_Smartpharma ($sql,$SedeConnection);
	    $arrayJson = FG_array_flatten_recursive($result);
	    return json_encode($arrayJson);
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Consulta_Especial_Smartpharma
		PARAMETROS: [$sql] Cadena de sql a ejecutar,
					[$SedeConnection] Siglas de la sede para la conexion
		FUNCION: Hacer una consulta a smartpharma
		RETORNO: Arreglo de datos
		DESAROLLADO POR: SERGIO COVA
	 */
	function FG_Consulta_Especial_Smartpharma($sql,$SedeConnection) {
		$conn = FG_Conectar_Smartpharma($SedeConnection);
		if($conn) {
			$stmt = sqlsrv_query($conn,$sql);
			if($stmt === false) {
				die(print_r(sqlsrv_errors(),true));
			}
			$i = 0;
			$final[$i] = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_NUMERIC);
			$i++;
			if($final[0] != NULL) {
				while($result = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_NUMERIC)) {
					$final[$i] = $result;
					$i++;
				}
				sqlsrv_free_stmt($stmt);
				sqlsrv_close($conn);
				return $final;
			}
			else {
				sqlsrv_free_stmt( $stmt);
				sqlsrv_close($conn);
				return NULL;
			}
		}
		else {
			die(print_r(sqlsrv_errors(),true));
		}
	}
	/**********************************************************************************/
	/*
		TITULO: FG_array_flatten_recursive
		PARAMETROS: [$array] Arreglo para aplicar la recursividad
		FUNCION: Iterar en el arreglo para la busqueda de variables
		RETORNO: Palabra clave buscada
		DESAROLLADO POR: SERGIO COVA
	 */
	function FG_array_flatten_recursive($array) {
	   if(!is_array($array)) {
		   return false;
	   }
	   $flat = array();
	   $RII = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
	   $i = 0;
	   foreach($RII as $key => $value) {
		   $flat[$i] = utf8_encode($value);
		   $i++;
	   }
	   return $flat;
	}
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
	/**********************************************************************************/
	/*
		TITULO: FG_Producto_Gravado
		PARAMETROS: [$IsIVA] campo que almacena el ConceptoImpuesto
		FUNCION: determina si un producto es gravado o no
		RETORNO: retorna si el producto es gravado o no
		DESARROLLADO POR: SERGIO COVA
 	*/
	function FG_Producto_Gravado($IsIVA){
		if($IsIVA == 1) {
			$EsGravado = 'SI';
		}
		else {
			$EsGravado = 'NO';
		}
		return $EsGravado;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Tipo_Producto
		PARAMETROS: [$TipoArticulo] tipo del producto
		FUNCION: Determina si el producto esta dolarizado 
		RETORNO: Retorna si el producto esta dolarizado o no
		DESARROLLADO POR: SERGIO COVA
 	*/
	function FG_Tipo_Producto($TipoArticulo) { 
		if($TipoArticulo == 0) {
			$Tipo = 'MISCELANEO';
		}
		else {
			$Tipo = 'MEDICINA';
		}
		return $Tipo;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Producto_Dolarizado
		PARAMETROS: [$conn] cadena de conexion
					[$IdArticulo] id del articulo a buscar
		FUNCION: Determina si el producto esta dolarizado 
		RETORNO: Retorna si el producto esta dolarizado o no
		DESARROLLADO POR: SERGIO COVA
 	*/
	function FG_Producto_Dolarizado($IsDolarizado) { 
		if($IsDolarizado == 0) {
			$EsDolarizado = 'NO';
		}
		else {
			$EsDolarizado = 'SI';
		}
		return $EsDolarizado;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Tasa_Fecha
		PARAMETROS: [$connCPharma] cadena de conexion con xampp
					[$Fecha] Fecha de la que se buscara la tasa
		FUNCION: Buscar el valor de la tasa
		RETORNO: Valor de la tasa
		DESARROLLADO POR: SERGIO COVA
	 */
	function FG_Tasa_Fecha($connCPharma,$Fecha) {
		$sql = QG_Tasa_Fecha($Fecha);
		$result = mysqli_query($connCPharma,$sql);
		$row = mysqli_fetch_assoc($result);
		$Tasa = $row['tasa'];
		return $Tasa;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Calculo_Precio
		PARAMETROS: [$conn] Cadena de conexion para la base de datos
					[$IdArticulo] Id del articulo
					[$IsIVA] Si aplica o no
					[$Existencia] existencia actual del producto
		FUNCION: Calcular el precio del articulo
		RETORNO: Precio del articulo
		DESARROLLADO POR: SERGIO COVA
	 */
	function FG_Calculo_Precio($Existencia,$TroquelAlmacen1,$PrecioCompraBruto,$Utilidad,$IsIVA,$TroquelAlmacen2) {		
		if($Existencia==0) {
			$Precio = 0;
		}
		else {
		/*CASO ALMACEN 1*/
			/*PRECIO TROQUELADO ALMACEN 1*/
			if($TroquelAlmacen1!=NULL){
				$Precio = $TroquelAlmacen1;
			}
			/*PRECIO CALCULADO*/
			else{
				if($PrecioCompraBruto!=NULL){

					if($Utilidad==1){
						$Precio = 0;
					}
					else{					
						if($IsIVA == 1){
							$PrecioCalculado = ($PrecioCompraBruto/$Utilidad)*Impuesto;
							$Precio = $PrecioCalculado;
						}
						else {
							$PrecioCalculado = ($PrecioCompraBruto/$Utilidad);
							$Precio = $PrecioCalculado;
						}
					}
				}
				else{
					/*PRECIO TROQUELADO ALMACEN 2*/
					if($TroquelAlmacen2!=NULL){
						$Precio = $TroquelAlmacen2;
					}
					else{
						$Precio = 0;
					}
				}
			}
		}
		return $Precio;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Rango_Dias
		PARAMETROS: [$FInicial] Fecha donde inicia el rango
					[$FFinal] Fecha donde termina el rango
		FUNCION: Calcular el rango de diferencia de dias entre el las fechas
		RETORNO: rango de dias
		DESARROLLADO POR: SERGIO COVA
	 */
	function FG_Rango_Dias($FInicial,$FFinal) {
		$FechaI = new DateTime($FInicial);
    $FechaF = new DateTime($FFinal);
    $Rango = $FechaI->diff($FechaF);
    $Rango = $Rango->format('%a');
    return $Rango;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Venta_Diaria
		PARAMETROS: [$Venta] Venta
					[$RangoDias] Rango de dias entre dos fechas
		FUNCION: Calcular la venta al diaria promedio en base al rango de dias
		RETORNO: Venta diaria promedio
	 */
	function FG_Venta_Diaria($Venta,$RangoDias) {
		$VentaDiaria = 0;
		if($RangoDias != 0){
			$VentaDiaria = $Venta/$RangoDias;
		}
		$VentaDiaria = round($VentaDiaria,2);
		return $VentaDiaria;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Dias_Restantes
		PARAMETROS: [$Existencia] Existencia de un producto
					[$VentaDiaria] Venta diaria promedio del prodcuto
		FUNCION: Calcular los dias restantes de inventario promedio
		RETORNO: Dias restantes promedio
	 */
	function FG_Dias_Restantes($Existencia,$VentaDiaria) {
		$DiasRestantes = 0;
		if($VentaDiaria != 0){
			$DiasRestantes = $Existencia/$VentaDiaria;
		}
		$DiasRestantes = round($DiasRestantes,2);
		return $DiasRestantes;
	}
?>