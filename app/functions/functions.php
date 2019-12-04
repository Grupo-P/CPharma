<?php
/*Funciones Generales al sistema CPharma*/
	/**********************************************************************************/
	/*
		TITULO: FG_Armar_Json
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
		TITULO: QLastRestoreDB
		PARAMETROS: [$nameDataBase] Nombre de la base de datos a buscar
		FUNCION: Busca la fecha de la ultima restauracion de la base de datos
		RETORNO: Fecha de ultima restauracion
	 */
	function LastRestoreDB($nameDataBase,$SedeConnection){
		$conn = FG_Conectar_Smartpharma($SedeConnection);
		$sql = SQL_Last_RestoreDB($nameDataBase);
		$result = sqlsrv_query($conn,$sql);
		$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$FechaRestauracion = $row["FechaRestauracion"]->format("Y-m-d h:i:s a");
		return $FechaRestauracion;
	}
	/**********************************************************************************/
	/*
		TITULO: ValidarConectividad
		PARAMETROS: [$conn] $conexion con la sede
		FUNCION: Valida la conexion con un servidor definido
		RETORNO: Estatus de la conectividad
	 */
	function FG_Validar_Conectividad($SedeConnection){
		$InicioCarga = new DateTime("now");

		$NombreSede = FG_Nombre_Sede($SedeConnection);
		$conn = FG_Conectar_Smartpharma($SedeConnection);
		echo('Sede: '.$NombreSede.'<br/>');

		if($conn) {
		     echo "Conexión establecida<br/>";
		}else{
		     echo "Conexión no se pudo establecer<br/>";
		     die( print_r( sqlsrv_errors(), true));
		}

		$FinCarga = new DateTime("now");
    	$IntervalCarga = $InicioCarga->diff($FinCarga);
    	echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
	}
	/**********************************************************************************/
	/*
		TITULO: MiUbicacion
		FUNCION: Descifrar desde que sede estoy entrando a la aplicacion
		RETORNO: Sede en la que me encuentro
		DESAROLLADO POR: SERGIO COVA
	 */
	function MiUbicacion(){
		$NombreCliente = gethostname();
		$IpCliente = gethostbyname($NombreCliente);
		$Octeto = explode(".", $IpCliente);
		
		switch ($Octeto[2]) {
		/*INICIO BLOQUE DE FTN*/
			case '1':
				return 'FTN';
			break;

			case '2':
				return 'FTN';
			break;
		/*FIN BLOQUE DE FTN*/
		/*INICIO BLOQUE DE GP*/
			case '10':
				return 'GP';
			break;
		/*FIN BLOQUE DE GP*/
		/*INICIO BLOQUE DE FLL*/
			case '7':
				return 'FLL';
			break;
		/*FIN BLOQUE DE FLL*/
		/*INICIO BLOQUE DE FAU*/
			case '12':
				return 'FAU';
			break;
		/*INICIO BLOQUE DE FAU*/
			default:
				return ''.$Octeto[2];
			break;
		}
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Nombre_Sede
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
		FUNCION: Determina si el producto es miscelaneo o medicina
		RETORNO: Retorna si el producto es miscelaneo o medicina
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
		FUNCION: Buscar el valor de la tasa
		RETORNO: Valor de la tasa
		DESARROLLADO POR: SERGIO COVA
	 */
	function FG_Tasa_Fecha($connCPharma,$Fecha) {
		$sql = MySQL_Tasa_Fecha($Fecha);
		$result = mysqli_query($connCPharma,$sql);
		$row = mysqli_fetch_assoc($result);
		$Tasa = $row['tasa'];
		return $Tasa;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Calculo_Precio
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
		TITULO: FG_Calculo_Precio_Sin_Existencia
		FUNCION: Calcular el precio del articulo
		RETORNO: Precio del articulo
		DESARROLLADO POR: SERGIO COVA
	 */
	function FG_Calculo_Precio_Sin_Existencia($TroquelAlmacen1,$PrecioCompraBruto,$Utilidad,$IsIVA,$TroquelAlmacen2) {		
		
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
		return $Precio;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Rango_Dias
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
		FUNCION: Calcular la venta al diaria promedio en base al rango de dias
		RETORNO: Venta diaria promedio
		DESARROLLADO POR: SERGIO COVA
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
		FUNCION: Calcular los dias restantes de inventario promedio
		RETORNO: Dias restantes promedio
		DESARROLLADO POR: SERGIO COVA
	 */
	function FG_Dias_Restantes($Existencia,$VentaDiaria) {
		$DiasRestantes = 0;
		if($VentaDiaria != 0){
			$DiasRestantes = $Existencia/$VentaDiaria;
		}
		$DiasRestantes = round($DiasRestantes,2);
		return $DiasRestantes;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Cantidad_Pedido
		FUNCION: calcular la cantidad a pedir
		RETORNO: cantidad a pedir
		DESARROLLADO POR: SERGIO COVA
	 */
	function FG_Cantidad_Pedido($VentaDiaria,$DiasPedido,$Existencia) {
		$CantidadPedido = (($VentaDiaria * $DiasPedido)-$Existencia);
		return $CantidadPedido;
	}
	/**********************************************************************************/
	/*
		TITULO: ProductoUnico
		FUNCION: determinar si un prducto es unico
		RETORNO: SI o NO segun sea el caso
		DESARROLLADO POR: SERGIO COVA
	 */
	function FG_Producto_Unico($conn,$IdArticulo,$IdProveedor) {
		$sql = SQL_Provedor_Unico($IdProveedor,$IdArticulo);
		$params = array();
		$options =  array("Scrollable"=>SQLSRV_CURSOR_KEYSET);
		$result = sqlsrv_query($conn,$sql,$params,$options);
		$row_count = sqlsrv_num_rows($result);

		if($row_count == 0) {
			$Unico = 'SI';
		}
		else {
			$Unico = 'NO';
		}
	  	return $Unico;
	}
  /**********************************************************************************/
	/*
		TITULO: FG_Validar_Fechas
		FUNCION: Calcula la diferencia entre ambas fechas restando la primera a la segunda
		RETORNO: 
			- Un numero positivo en caso de que la segunda fecha sea mayor
			- Un numero negativo en caso de que la segunda fecha sea menor
			- Cero en caso de que ambas fechas sean iguales
		DESARROLLADO POR: MANUEL HENRIQUEZ
	 */
	function FG_Validar_Fechas($Fecha1,$Fecha2) {
		$fecha_inicial = new DateTime($Fecha1);
        $fecha_final = new DateTime($Fecha2);

		$diferencia = $fecha_inicial->diff($fecha_final);
		$diferencia_numero = (int)$diferencia->format('%R%a');

		return $diferencia_numero;
  }
  /**********************************************************************************/
  /*
		TITULO: FG_Traslado_Detalle
		FUNCION: Ejecuta las validaciones para el traslado detalle
		RETORNO: no aplica
		DESARROLLADO POR: SERGIO COVA
	 */
	function FG_Traslado_Detalle($SedeConnection,$NumeroAjuste,$IdAjuste) {
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $sql = SQL_Articulos_Ajuste($IdAjuste);
    $result = sqlsrv_query($conn,$sql);
   
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["InvArticuloId"];
      $Cantidad = $row["Cantidad"];
      $IdLote = $row["lote"];
      $IdTraslado = $NumeroAjuste;

      $sql1 = SQG_Detalle_Articulo($IdArticulo);
      $result1 = sqlsrv_query($conn,$sql1);
      $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

      $CodigoArticulo = $row1["CodigoInterno"];
      $CodigoBarra = $row1["CodigoBarra"];
      $Descripcion = FG_Limpiar_Texto($row1["Descripcion"]);
      $Existencia = $row1["Existencia"];
	    $ExistenciaAlmacen1 = $row1["ExistenciaAlmacen1"];
	    $ExistenciaAlmacen2 = $row1["ExistenciaAlmacen2"];
	    $IsTroquelado = $row1["Troquelado"];
	    $IsIVA = $row1["Impuesto"];
	    $UtilidadArticulo = $row1["UtilidadArticulo"];
	    $UtilidadCategoria = $row1["UtilidadCategoria"];
	    $TroquelAlmacen1 = $row1["TroquelAlmacen1"];
	    $PrecioCompraBrutoAlmacen1 = $row1["PrecioCompraBrutoAlmacen1"];
	    $TroquelAlmacen2 = $row1["TroquelAlmacen2"];
	    $PrecioCompraBrutoAlmacen2 = $row1["PrecioCompraBrutoAlmacen2"];
	    $PrecioCompraBruto = $row1["PrecioCompraBruto"];
      $Dolarizado = $row1["Dolarizado"];
      $Gravado = FG_Producto_Gravado($IsIVA);
      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
      $CondicionExistencia = 'CON_EXISTENCIA';

      $Utilidad = FG_Utilidad_Alfa($UtilidadArticulo,$UtilidadCategoria);

      if($Existencia==0){

      	$sql3 = SQG_Detalle_Articulo_Lote($IdLote,$IdArticulo);
	      $result3 = sqlsrv_query($conn,$sql3);
	      $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

	      $ExistenciaSE = $row3["Existencia"];
		    $ExistenciaAlmacen1SE = $row3["ExistenciaAlmacen1"];
		    $ExistenciaAlmacen2SE = $row3["ExistenciaAlmacen2"];
		    $IsTroqueladoSE = $row3["Troquelado"];
		    $IsIVASE = $row3["Impuesto"];
		    $UtilidadArticuloSE = $row3["UtilidadArticulo"];
		    $UtilidadCategoriaSE = $row3["UtilidadCategoria"];
		    $TroquelAlmacen1SE = $row3["TroquelAlmacen1"];
		    $PrecioCompraBrutoAlmacen1SE = $row3["PrecioCompraBrutoAlmacen1"];
		    $TroquelAlmacen2SE = $row3["TroquelAlmacen2"];
		    $PrecioCompraBrutoAlmacen2SE = $row3["PrecioCompraBrutoAlmacen2"];
		    $PrecioCompraBrutoSE = $row3["PrecioCompraBruto"];
		    $CondicionExistenciaSE = 'SIN_EXISTENCIA';

		    $UtilidadSE = FG_Utilidad_Alfa($UtilidadArticuloSE,$UtilidadCategoriaSE);

      	if($Dolarizado=='SI') {
        	$TasaActualSE = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
        	$PrecioSE = FG_Calculo_Precio_Alfa($ExistenciaSE,$ExistenciaAlmacen1SE,$ExistenciaAlmacen2SE,$IsTroqueladoSE,$UtilidadArticuloSE,$UtilidadCategoriaSE,$TroquelAlmacen1SE,$PrecioCompraBrutoAlmacen1SE,$TroquelAlmacen2SE, $PrecioCompraBrutoAlmacen2SE,$PrecioCompraBrutoSE,$IsIVASE,$CondicionExistenciaSE);

	        if($Gravado=='SI' && $UtilidadSE!= 1){
	          $costo_unit_bs_sin_iva = ($PrecioSE/Impuesto)*$UtilidadSE;
	          $costo_unit_usd_sin_iva = round(($costo_unit_bs_sin_iva/$TasaActualSE),2);
	          $total_imp_usd = round(($costo_unit_usd_sin_iva*$Cantidad)*(Impuesto-1),2);
	        }
	        else if($Gravado== 'NO' && $UtilidadSE!= 1){
	          $costo_unit_bs_sin_iva = ($PrecioSE)*$UtilidadSE;
	          $costo_unit_usd_sin_iva = round(($costo_unit_bs_sin_iva/$TasaActualSE),2);
	          $total_imp_usd = 0.00;
	        }
	        $total_usd = round((($costo_unit_usd_sin_iva*$Cantidad)+$total_imp_usd),2);
	        $costo_unit_bs_sin_iva = '-';
	        $total_imp_bs = '-';
	        $total_bs = '-';
	      }
	      else if($Dolarizado=='NO') {
	        $costo_unit_bs_sin_iva = round($PrecioCompraBrutoSE,2);

	        if($Gravado== 'SI' && $UtilidadSE!= 1){
	          $total_imp_bs = round((($costo_unit_bs_sin_iva*$Cantidad)*(Impuesto-1)),2);
	        }
	        else if($Gravado== 'NO' && $UtilidadSE!= 1){
	          $total_imp_bs = 0.00; 
	        }
	        $total_bs = round((($costo_unit_bs_sin_iva*$Cantidad)+$total_imp_bs),2);
	        $costo_unit_usd_sin_iva = '-';
	        $total_imp_usd = '-';
	        $total_usd = '-';
	      }
	      $date = new DateTime('now');
	      $date = $date->format("Y-m-d H:i:s");

	      $sqlCP = MySQL_Guardar_Traslado_Detalle($IdTraslado,$IdArticulo,$CodigoArticulo,$CodigoBarra,$Descripcion,$Gravado,$Dolarizado,$Cantidad,$costo_unit_bs_sin_iva,$costo_unit_usd_sin_iva,$total_imp_bs,$total_imp_usd,$total_bs,$total_usd,$date);
	      mysqli_query($connCPharma,$sqlCP);
      }
      else{

      	$sql3 = SQG_Detalle_Articulo_Lote($IdLote,$IdArticulo);
	      $result3 = sqlsrv_query($conn,$sql3);
	      $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

	      $ExistenciaSE = $row3["Existencia"];
		    $ExistenciaAlmacen1SE = $row3["ExistenciaAlmacen1"];
		    $ExistenciaAlmacen2SE = $row3["ExistenciaAlmacen2"];
		    $IsTroqueladoSE = $row3["Troquelado"];
		    $IsIVASE = $row3["Impuesto"];
		    $UtilidadArticuloSE = $row3["UtilidadArticulo"];
		    $UtilidadCategoriaSE = $row3["UtilidadCategoria"];
		    $TroquelAlmacen1SE = $row3["TroquelAlmacen1"];
		    $PrecioCompraBrutoAlmacen1SE = $row3["PrecioCompraBrutoAlmacen1"];
		    $TroquelAlmacen2SE = $row3["TroquelAlmacen2"];
		    $PrecioCompraBrutoAlmacen2SE = $row3["PrecioCompraBrutoAlmacen2"];
		    $PrecioCompraBrutoSE = $row3["PrecioCompraBruto"];
		    $CondicionExistenciaSE = 'SIN_EXISTENCIA';

		    $UtilidadSE = FG_Utilidad_Alfa($UtilidadArticuloSE,$UtilidadCategoriaSE);

	      if( $PrecioCompraBrutoSE > $PrecioCompraBruto ){

	      	if($Dolarizado=='SI') {
	        	$TasaActualSE = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
	        	$PrecioSE = FG_Calculo_Precio_Alfa($ExistenciaSE,$ExistenciaAlmacen1SE,$ExistenciaAlmacen2SE,$IsTroqueladoSE,$UtilidadArticuloSE,$UtilidadCategoriaSE,$TroquelAlmacen1SE,$PrecioCompraBrutoAlmacen1SE,$TroquelAlmacen2SE, $PrecioCompraBrutoAlmacen2SE,$PrecioCompraBrutoSE,$IsIVASE,$CondicionExistenciaSE);

	        if($Gravado=='SI' && $UtilidadSE!= 1){
	          $costo_unit_bs_sin_iva = ($PrecioSE/Impuesto)*$UtilidadSE;
	          $costo_unit_usd_sin_iva = round(($costo_unit_bs_sin_iva/$TasaActualSE),2);
	          $total_imp_usd = round(($costo_unit_usd_sin_iva*$Cantidad)*(Impuesto-1),2);
	        }
	        else if($Gravado== 'NO' && $UtilidadSE!= 1){
	          $costo_unit_bs_sin_iva = ($PrecioSE)*$UtilidadSE;
	          $costo_unit_usd_sin_iva = round(($costo_unit_bs_sin_iva/$TasaActualSE),2);
	          $total_imp_usd = 0.00;
	        }
	        $total_usd = round((($costo_unit_usd_sin_iva*$Cantidad)+$total_imp_usd),2);
	        $costo_unit_bs_sin_iva = '-';
	        $total_imp_bs = '-';
	        $total_bs = '-';
		      }
		      else if($Dolarizado=='NO') {
		        $costo_unit_bs_sin_iva = round($PrecioCompraBrutoSE,2);

		        if($Gravado== 'SI' && $UtilidadSE!= 1){
		          $total_imp_bs = round((($costo_unit_bs_sin_iva*$Cantidad)*(Impuesto-1)),2);
		        }
		        else if($Gravado== 'NO' && $UtilidadSE!= 1){
		          $total_imp_bs = 0.00; 
		        }
		        $total_bs = round((($costo_unit_bs_sin_iva*$Cantidad)+$total_imp_bs),2);
		        $costo_unit_usd_sin_iva = '-';
		        $total_imp_usd = '-';
		        $total_usd = '-';
		      }
		      $date = new DateTime('now');
		      $date = $date->format("Y-m-d H:i:s");

		      $sqlCP = MySQL_Guardar_Traslado_Detalle($IdTraslado,$IdArticulo,$CodigoArticulo,$CodigoBarra,$Descripcion,$Gravado,$Dolarizado,$Cantidad,$costo_unit_bs_sin_iva,$costo_unit_usd_sin_iva,$total_imp_bs,$total_imp_usd,$total_bs,$total_usd,$date);
		      mysqli_query($connCPharma,$sqlCP);
	      }
	      else{

	      	if($Dolarizado=='SI') {
	        $TasaActual = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
	        $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

	        if($Gravado=='SI' && $Utilidad!= 1){
	          $costo_unit_bs_sin_iva = ($Precio/Impuesto)*$Utilidad;
	          $costo_unit_usd_sin_iva = round(($costo_unit_bs_sin_iva/$TasaActual),2);
	          $total_imp_usd = round(($costo_unit_usd_sin_iva*$Cantidad)*(Impuesto-1),2);
	        }
	        else if($Gravado== 'NO' && $Utilidad!= 1){
	          $costo_unit_bs_sin_iva = ($Precio)*$Utilidad;
	          $costo_unit_usd_sin_iva = round(($costo_unit_bs_sin_iva/$TasaActual),2);
	          $total_imp_usd = 0.00;
	        }
	        $total_usd = round((($costo_unit_usd_sin_iva*$Cantidad)+$total_imp_usd),2);
	        $costo_unit_bs_sin_iva = '-';
	        $total_imp_bs = '-';
	        $total_bs = '-';
		      }
		      else if($Dolarizado=='NO') {
		        $costo_unit_bs_sin_iva = round($PrecioCompraBruto,2);

		        if($Gravado== 'SI' && $Utilidad!= 1){
		          $total_imp_bs = round((($costo_unit_bs_sin_iva*$Cantidad)*(Impuesto-1)),2);
		        }
		        else if($Gravado== 'NO' && $Utilidad!= 1){
		          $total_imp_bs = 0.00;
		        }
		        $total_bs = round((($costo_unit_bs_sin_iva*$Cantidad)+$total_imp_bs),2);
		        $costo_unit_usd_sin_iva = '-';
		        $total_imp_usd = '-';
		        $total_usd = '-';
		      }
		      $date = new DateTime('now');
		      $date = $date->format("Y-m-d H:i:s");

		      $sqlCP = MySQL_Guardar_Traslado_Detalle($IdTraslado,$IdArticulo,$CodigoArticulo,$CodigoBarra,$Descripcion,$Gravado,$Dolarizado,$Cantidad,$costo_unit_bs_sin_iva,$costo_unit_usd_sin_iva,$total_imp_bs,$total_imp_usd,$total_bs,$total_usd,$date);
		      mysqli_query($connCPharma,$sqlCP);
	      }
	    }
    }
    sqlsrv_close($conn);
    mysqli_close($connCPharma);
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Calculo_Precio_Alfa
		FUNCION: Calcular el precio del articulo
		RETORNO: Precio del articulo
		DESARROLLADO POR: SERGIO COVA
	 */
	function FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
		$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia) {		
		$FlagPrecio = FALSE;

		if( ($Existencia==0) && ($CondicionExistencia=='CON_EXISTENCIA') ) {
			$Precio = 0;
		}
		else if(($Existencia!=0) && ($CondicionExistencia=='CON_EXISTENCIA')) {

			if($IsTroquelado!=0) {
				/*CASO 1: Cuando el articulo tiene el atributo troquelado*/
				if( ($TroquelAlmacen1!=NULL) && ($FlagPrecio==FALSE) ){
					$Precio = $TroquelAlmacen1;
					$FlagPrecio=TRUE;
				}
				else if( ($TroquelAlmacen1==NULL) && ($ExistenciaAlmacen1!=0) && ($FlagPrecio==FALSE)) {
					$Precio = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$PrecioCompraBrutoAlmacen1);
					$FlagPrecio=TRUE;
				}
				else if( ($TroquelAlmacen2!=NULL) && ($FlagPrecio==FALSE) ){
					$Precio = $TroquelAlmacen2;
					$FlagPrecio=TRUE;
				}
				else if( ($TroquelAlmacen2==NULL) && ($ExistenciaAlmacen2!=0) && ($FlagPrecio==FALSE) ) {
					$Precio = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$PrecioCompraBrutoAlmacen2);
					$FlagPrecio=TRUE;
				}
			}
			else if($IsTroquelado==0) {
				/*CASO 2: Cuando el articulo NO tiene el atributo troquelado*/
				$PrecioCalculado = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$PrecioCompraBruto);

				$Precio = max($PrecioCalculado,$TroquelAlmacen1,$TroquelAlmacen2);
			}
		}
		else if(($Existencia==0) && ($CondicionExistencia=='SIN_EXISTENCIA')) {

			if($IsTroquelado!=0) {
				/*CASO 1: Cuando el articulo tiene el atributo troquelado*/
				if( ($TroquelAlmacen1!=NULL) && ($FlagPrecio==FALSE) ){
					$Precio = $TroquelAlmacen1;
					$FlagPrecio=TRUE;
				}
				else if( ($TroquelAlmacen1==NULL) && ($ExistenciaAlmacen1!=0) && ($FlagPrecio==FALSE)) {
					$Precio = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$PrecioCompraBrutoAlmacen1);
					$FlagPrecio=TRUE;
				}
				else if( ($TroquelAlmacen2!=NULL) && ($FlagPrecio==FALSE) ){
					$Precio = $TroquelAlmacen2;
					$FlagPrecio=TRUE;
				}
				else if( ($TroquelAlmacen2==NULL) && ($ExistenciaAlmacen2!=0) && ($FlagPrecio==FALSE) ) {
					$Precio = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$PrecioCompraBrutoAlmacen2);
					$FlagPrecio=TRUE;
				}
			}
			else if($IsTroquelado==0) {
				/*CASO 2: Cuando el articulo NO tiene el atributo troquelado*/
				$PrecioCalculado = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$PrecioCompraBruto);

				$Precio = max($PrecioCalculado,$TroquelAlmacen1,$TroquelAlmacen2);
			}
		}

		if(!isset($Precio)){
			$Precio = 0.01;
		}

		return $Precio;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Precio_Calculado_Alfa
		FUNCION: Calcular el precio del articulo
		RETORNO: Precio del articulo
		DESARROLLADO POR: SERGIO COVA
	 */
	function FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$PrecioCompraBruto){
			if($IsIVA==1){
				/*Caso 1: Aplica Impuesto*/
				if($UtilidadArticulo!=1) {
					$PrecioCalculado = ($PrecioCompraBruto/$UtilidadArticulo)*Impuesto;
				}
				else if($UtilidadArticulo==1){

					if($UtilidadCategoria!=1){
						$PrecioCalculado = ($PrecioCompraBruto/$UtilidadCategoria)*Impuesto;
					}
					else if($UtilidadCategoria==1){
						/*$PrecioCalculado = 0;*/
						$PrecioCalculado = ($PrecioCompraBruto/Utilidad)*Impuesto;
					}
				}
			}
			if($IsIVA==0){
				/*Caso 1: NO Aplica Impuesto*/
				if($UtilidadArticulo!=1) {
					$PrecioCalculado = ($PrecioCompraBruto/$UtilidadArticulo);
				}
				else if($UtilidadArticulo==1){

					if($UtilidadCategoria!=1){
						$PrecioCalculado = ($PrecioCompraBruto/$UtilidadCategoria);
					}
					else if($UtilidadCategoria==1){
						/*$PrecioCalculado = 0;*/
						$PrecioCalculado = ($PrecioCompraBruto/Utilidad);
					}
				}
			}
		return $PrecioCalculado;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Utilidad_Alfa
		FUNCION: Decide la utilidad a usar para la regresion o calculo de precio
		RETORNO: retorna la utilidad
		DESARROLLADO POR: SERGIO COVA
	 */
	function FG_Utilidad_Alfa($UtilidadArticulo,$UtilidadCategoria){
		if($UtilidadArticulo!=1) {
			$utilidad = $UtilidadArticulo;
		}
		else if($UtilidadArticulo==1){

			if($UtilidadCategoria!=1){
				$utilidad = $UtilidadCategoria;
			}
			else if($UtilidadCategoria==1){
				$utilidad = Utilidad;
			}
		}
		return $utilidad;
	}
  /**********************************************************************************/
  /*
		TITULO: FG_Ruta_Reporte
		FUNCION:Busca la ruta que le pertenece al nombre del reporte
		RETORNO: url
		DESARROLLADO POR: SERGIO COVA
 	*/
	function FG_Ruta_Reporte($NombreReporte){
		switch ($NombreReporte) {
			case 'Activacion de proveedores':
				$ruta = '/reporte1';
			break;
			case 'Historico de productos':
				$ruta = '/reporte2';
			break;
			case 'Productos mas vendidos':
				$ruta = '/reporte3';
			break;
			case 'Productos menos vendidos':
				$ruta = '/reporte4';
			break;
			case 'Productos en falla':
				$ruta = '/reporte5';
			break;
			case 'Pedido de productos':
				$ruta = '/reporte6';
			break;
			case 'Catalogo de proveedor':
				$ruta = '/reporte7';
			break;
			case 'Productos para surtir':
				$ruta = '/reporte9';
			break;
			case 'Analitico de precios':
				$ruta = '/reporte10';
			break;
			case 'Detalle de movimientos':
				$ruta = '/reporte12';
			break;
			case 'Productos Por Fallar':
				$ruta = '/reporte13';
			break;
			case 'Productos en Caida':
				$ruta = '/reporte14';
			break;
			case 'Articulos Devaluados':
				$ruta = '/reporte15';
			break;
			default:
				$ruta = '#';
			break;
		}
		return $ruta;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Reportes_Departamento
		FUNCION: Consigue el numero de reportes disponibles para el departamento
		RETORNO: cantidad de reportes
		DESAROLLADO POR: SERGIO COVA
	 */
	function FG_Reportes_Departamento($Departamento) {
		switch ($Departamento) {
			case 'COMPRAS':
				$Numero_Reportes = 12;
			break;
			case 'OPERACIONES':
				$Numero_Reportes = 3;
			break;
			case 'ALMACEN':
				$Numero_Reportes = 3;
			break;
			case 'DEVOLUCIONES':
				$Numero_Reportes = 2;
			break;
			case 'SURTIDO':
				$Numero_Reportes = 3;
			break;
			case 'ADMINISTRACION':
				$Numero_Reportes = 1;
			break;
			case 'LÍDER DE TIENDA':
				$Numero_Reportes = 13;
			break;
			case 'GERENCIA':
				$Numero_Reportes = 13;
			break;
			case 'TECNOLOGIA':
				$Numero_Reportes = 13;
			break;
			default:
				$Numero_Reportes = 0;
			break;
		}
		return $Numero_Reportes;
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Sede_OnLine
		FUNCION: Determina si la sede es on-line
		RETORNO: verdadero o falso
		DESAROLLADO POR: SERGIO COVA
	 */
	function FG_Sede_OnLine($Sede) {
		switch ($Sede) {
			case 'FTN':
				$Flag = TRUE;
			break;
			case 'FLL':
				$Flag = TRUE;
			break;
			case 'FAU':
				$Flag = TRUE;
			break;
			default:
				$Flag = FALSE;
			break;
		}
		return $Flag;
  }
  /**********************************************************************************/
  /*
		TITULO: FG_Dias_EnCero
		FUNCION: Captura y almacena la data para dias en cero
		RETORNO: no aplica
		DESARROLLADO POR: SERGIO COVA
	 */
	function FG_Dias_EnCero() {
		$SedeConnection = MiUbicacion();
		$conn = FG_Conectar_Smartpharma($SedeConnection);
		$connCPharma = FG_Conectar_CPharma();

		$sql = SQL_Dias_EnCero();
		$result = sqlsrv_query($conn,$sql);

		$FechaCaptura = new DateTime("now");
		$FechaCaptura = $FechaCaptura->format('Y-m-d');
		$user = 'SYSTEM';
		$date = '';

		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["IdArticulo"];
			$CodigoInterno = $row["CodigoInterno"];
			$CodigoBarra = $row["CodigoBarra"];
      $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
      $Existencia = $row["Existencia"];
	    $ExistenciaAlmacen1 = $row["ExistenciaAlmacen1"];
	    $ExistenciaAlmacen2 = $row["ExistenciaAlmacen2"];
	    $IsTroquelado = $row["Troquelado"];
	    $IsIVA = $row["Impuesto"];
	    $UtilidadArticulo = $row["UtilidadArticulo"];
	    $UtilidadCategoria = $row["UtilidadCategoria"];
	    $TroquelAlmacen1 = $row["TroquelAlmacen1"];
	    $PrecioCompraBrutoAlmacen1 = $row["PrecioCompraBrutoAlmacen1"];
	    $TroquelAlmacen2 = $row["TroquelAlmacen2"];
	    $PrecioCompraBrutoAlmacen2 = $row["PrecioCompraBrutoAlmacen2"];
	    $PrecioCompraBruto = $row["PrecioCompraBruto"];
      $Dolarizado = $row["Dolarizado"];
      $Gravado = FG_Producto_Gravado($IsIVA);
      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
      $CondicionExistencia = 'CON_EXISTENCIA';

     	$Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);
     	
			$date = date('Y-m-d h:i:s',time());
			
			$sqlCPharma = MySQL_Guardar_Dias_EnCero($IdArticulo,$CodigoInterno,$Descripcion,$Existencia,$Precio,$FechaCaptura,$user,$date);
			mysqli_query($connCPharma,$sqlCPharma);
		}
		FG_Guardar_Captura_Diaria($connCPharma,$FechaCaptura,$date);

		$sqlCC = MySQL_Validar_Captura_Diaria($FechaCaptura);
		$resultCC = mysqli_query($connCPharma,$sqlCC);
		$rowCC = mysqli_fetch_assoc($resultCC);
		$CuentaCaptura = $rowCC["CuentaCaptura"];

		if($CuentaCaptura == 0){
			$sqlB = MySQL_Borrar_DiasCero($FechaCaptura);
			mysqli_query($connCPharma,$sqlB);
			mysqli_close($connCPharma);
			sqlsrv_close($conn);
			FG_Dias_EnCero();
		}
		else{
			mysqli_close($connCPharma);
			sqlsrv_close($conn);
		}
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Guardar_Captura_Diaria
		PARAMETROS: [$FechaCaptura] El dia de hoy
					[$date] valor para creacion y actualizacion
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		RETORNO: no aplica
	 */
	function FG_Guardar_Captura_Diaria($connCPharma,$FechaCaptura,$date) {
		$sql = MySQL_Captura_Diaria($FechaCaptura);
		$result = mysqli_query($connCPharma,$sql);
		$row = mysqli_fetch_assoc($result);
		$TotalRegistros = $row["TotalRegistros"];

		$sql1 = MySQL_Guardar_Captura_Diaria($TotalRegistros,$FechaCaptura,$date);
		mysqli_query($connCPharma,$sql1);
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Prouctos_EnCaida
		FUNCION: Captura y almacena la data para productos en caida
		RETORNO: no aplica
		DESAROLLADO POR: SERGIO COVA
	 */
	function FG_Prouctos_EnCaida() {
		$SedeConnection = MiUbicacion();
		$conn = FG_Conectar_Smartpharma($SedeConnection);
		$connCPharma = FG_Conectar_CPharma();

		$sqlB = MySQL_Borrar_ProductosCaida();
		mysqli_query($connCPharma,$sqlB);

		$FechaCaptura = new DateTime("now");
		$FechaCaptura = $FechaCaptura->format('Y-m-d');
		$user = 'SYSTEM';
		$date = date('Y-m-d h:i:s',time());

		/* Rangos de Fecha */
  		$FFinal = date("Y-m-d");
	  	$FInicial = date("Y-m-d",strtotime($FFinal."-10 days"));
	  	$RangoDias = intval(FG_Rango_Dias($FInicial,$FFinal));

		/* FILTRO 1:
		*  Articulos con veces vendidas en el rango
		*  Articulos sin compra en el rango
		*/
		$sql = SQL_Clean_Table('CP_QG_Unidades_Vendidas');
		sqlsrv_query($conn,$sql);
		$sql = SQL_Clean_Table('CP_QG_Unidades_Devueltas');
		sqlsrv_query($conn,$sql);
		$sql = SQL_Clean_Table('CP_QG_Unidades_Compradas');
		sqlsrv_query($conn,$sql);
		$sql = SQL_Clean_Table('CP_QG_Unidades_Reclamadas');
		sqlsrv_query($conn,$sql);
		
		$sql1 = SQL_ArtVendidos_ProductoCaida($FInicial,$FFinal);
		sqlsrv_query($conn,$sql1);
		$sql1 = SQL_ArtDevueltos_ProductoCaida($FInicial,$FFinal);
		sqlsrv_query($conn,$sql1);
		$sql1 = SQL_ArtComprados_ProductoCaida($FInicial,$FFinal);
		sqlsrv_query($conn,$sql1);
		$sql1 = SQL_ArtReclamados_ProductoCaida($FInicial,$FFinal);
		sqlsrv_query($conn,$sql1);

		$sql3 = SQL_Integracion_ProductoCaida($RangoDias);
		$result = sqlsrv_query($conn,$sql3);

		$sql = SQL_Clean_Table('CP_QG_Unidades_Vendidas');
		sqlsrv_query($conn,$sql);
		$sql = SQL_Clean_Table('CP_QG_Unidades_Devueltas');
		sqlsrv_query($conn,$sql);
		$sql = SQL_Clean_Table('CP_QG_Unidades_Compradas');
		sqlsrv_query($conn,$sql);
		$sql = SQL_Clean_Table('CP_QG_Unidades_Reclamadas');
		sqlsrv_query($conn,$sql);

		/* Inicio while que itera en los articulos con existencia actual > 0*/
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

			/*COMANDOS PARA TEST
    	$IdArticulo = 54806;
			$flag = FALSE;
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				$IdArticuloR = $row['InvArticuloId'];
				if($IdArticuloR == $IdArticulo){
					$flag = TRUE;
				}
			}

			if($flag==TRUE){
				echo'----Filtro 1 * Sin compra rango * Con venta en rango: SI----';
			}
			else{
				echo'----Filtro 1 * Sin compra rango * Con venta en rango: NO----';
			}
			COMANDOS PARA TEST*/	
			
			$IdArticulo = $row["InvArticuloId"];
			$sql1 = SQG_Detalle_Articulo($IdArticulo);
    	$result1 = sqlsrv_query($conn,$sql1);
    	$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

    	$CodigoArticulo = $row1["CodigoInterno"];
      $CodigoBarra = $row1["CodigoBarra"];
      $Descripcion = FG_Limpiar_Texto($row1["Descripcion"]);
      $Existencia = $row1["Existencia"];
      $ExistenciaAlmacen1 = $row1["ExistenciaAlmacen1"];
      $ExistenciaAlmacen2 = $row1["ExistenciaAlmacen2"];
      $IsTroquelado = $row1["Troquelado"];
      $IsIVA = $row1["Impuesto"];
      $UtilidadArticulo = $row1["UtilidadArticulo"];
      $UtilidadCategoria = $row1["UtilidadCategoria"];
      $TroquelAlmacen1 = $row1["TroquelAlmacen1"];
      $PrecioCompraBrutoAlmacen1 = $row1["PrecioCompraBrutoAlmacen1"];
      $TroquelAlmacen2 = $row1["TroquelAlmacen2"];
      $PrecioCompraBrutoAlmacen2 = $row1["PrecioCompraBrutoAlmacen2"];
      $PrecioCompraBruto = $row1["PrecioCompraBruto"];
      $CondicionExistencia = 'CON_EXISTENCIA';

    	$UnidadesVendidas = $row["UnidadesVendidas"];
    	$VentaDiaria = FG_Venta_Diaria($UnidadesVendidas,$RangoDias);
    	$DiasRestantes = FG_Dias_Restantes($Existencia,$VentaDiaria);

    	/*COMANDOS PARA TEST*/
    	//echo'----- Articulo: '.$Descripcion.' -----';
    	/*COMANDOS PARA TEST*/

			/* FILTRO 2: 
			*	Dias restantes
			*	si dias restantes es menor de 10 entra en el rango sino es rechazado
			*/
			if($DiasRestantes<11){

				/*COMANDOS PARA TEST*/
				//echo'----Filtro 2 * Dias restantes < 11: SI '.$DiasRestantes.' ----';
				/*COMANDOS PARA TEST*/

				/* FILTRO 3:
				*	Mantuvo Existencia en rango
				*	se cuenta las apariciones del articulo en la tabla de dias
				*	en cero, la misma debe ser igual la diferencia de dias +1 
				*/
				$CuentaExistencia = CuentaExistencia($connCPharma,$IdArticulo,$FInicial,$FFinal);
				
				if($CuentaExistencia==$RangoDias){

					/*COMANDOS PARA TEST*/
					//echo'----Filtro 3 * Existencia en rango: SI '.$CuentaExistencia.' ----';
					/*COMANDOS PARA TEST*/

					/*  FILTRO 4: 
					*	Venta en dia, esta se acumula 
					*	El articulo debe tener venta al menos la mita de dias del rango 
					*/
					$CuentaVenta = CuentaVenta($conn,$IdArticulo,$FInicial,$FFinal);

					if($CuentaVenta>=($RangoDias/2)){

						/*COMANDOS PARA TEST*/
						//echo'----Filtro 4 * Venta en dias: SI '.$CuentaVenta.' ----';
						/*COMANDOS PARA TEST*/		

						$ExistenciaDecreciente = ExistenciaDecreciente($connCPharma,$IdArticulo,$FInicial,$FFinal,$RangoDias);

						$CuentaDecreciente = array_pop($ExistenciaDecreciente);

						/*  FILTRO 5: 
						*	Si el articulo decrece su existencia rango 
						*/
						if($CuentaDecreciente==TRUE){

							/*COMANDOS PARA TEST*/
							//echo'----Filtro 5 * Decrece su existencia: SI ----';
							/*COMANDOS PARA TEST*/

							$Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

							$Dia1 = array_pop($ExistenciaDecreciente);
							$Dia2 = array_pop($ExistenciaDecreciente);
							$Dia3 = array_pop($ExistenciaDecreciente);
							$Dia4 = array_pop($ExistenciaDecreciente);
							$Dia5 = array_pop($ExistenciaDecreciente);
							$Dia6 = array_pop($ExistenciaDecreciente);
							$Dia7 = array_pop($ExistenciaDecreciente);
							$Dia8 = array_pop($ExistenciaDecreciente);
							$Dia9 = array_pop($ExistenciaDecreciente);
							$Dia10 = array_pop($ExistenciaDecreciente);

							$sqlCPharma = MySQL_Guardar_Productos_Caida($IdArticulo,$CodigoArticulo,$Descripcion,$Precio,$Existencia,$Dia10,$Dia9,$Dia8,$Dia7,$Dia6,$Dia5,$Dia4,$Dia3,$Dia2,$Dia1,$UnidadesVendidas,$DiasRestantes,$FechaCaptura,$user,$date,$date);

							mysqli_query($connCPharma,$sqlCPharma);
						}	
						//else{
							/*COMANDOS PARA TEST*/
							//echo'----Filtro 5 * Decrece su existencia: NO ----';
							/*COMANDOS PARA TEST*/
						//}					
					}
					//else{
						/*COMANDOS PARA TEST*/
						//echo'----Filtro 4 * Venta en dias: NO '.$CuentaVenta.' ----';
						/*COMANDOS PARA TEST*/
					//}	
				}
				//else{
					/*COMANDOS PARA TEST*/
					//echo'----Filtro 3 * Existencia en rango: NO '.$CuentaExistencia.' ----';
					/*COMANDOS PARA TEST*/
				//}
			}
			//else{
				/*COMANDOS PARA TEST*/
				//echo'----Filtro 2 * Dias restantes < 11: NO '.$DiasRestantes.' ----';
				/*COMANDOS PARA TEST*/
			//}	
		}
		GuardarCapturaCaida($connCPharma,$FechaCaptura,$date);
		
		$sqlCC = MySQL_Validar_Captura_Caida($FechaCaptura);
		$resultCC = mysqli_query($connCPharma,$sqlCC);
		$rowCC = mysqli_fetch_assoc($resultCC);
		$CuentaCaptura = $rowCC["CuentaCaptura"];

		if($CuentaCaptura == 0){
			mysqli_close($connCPharma);
			sqlsrv_close($conn);
			FG_Prouctos_EnCaida();
		}
		else{
			mysqli_close($connCPharma);
			sqlsrv_close($conn);
		}
	}
	/**********************************************************************************/
	/*
		TITULO: CuentaExistencia
		PARAMETROS: 
		FUNCION: 
		RETORNO: 
 	*/
	function CuentaExistencia($connCPharma,$IdArticulo,$FInicial,$FFinal) {
		$sql = MySQL_Cuenta_Existencia($IdArticulo,$FInicial,$FFinal);
		$result = mysqli_query($connCPharma,$sql);
		$row = mysqli_fetch_assoc($result);
		$Cuenta = $row["Cuenta"];
		return $Cuenta;
	}
	/**********************************************************************************/
	/*
		TITULO: CuentaVenta
		PARAMETROS: 
		FUNCION: 
		RETORNO: 
 	*/
	function CuentaVenta($conn,$IdArticulo,$FInicial,$FFinal) {
		
		$FFinalPivote = date("Y-m-d",strtotime($FInicial."+1 days"));
		$FFinal = date("Y-m-d",strtotime($FFinal."+1 days"));
		$CuentaVenta = 0;

		while($FFinalPivote!=$FFinal){

			$sql = SQL_CuentaVenta($IdArticulo,$FInicial,$FFinalPivote);
			$result = sqlsrv_query($conn,$sql);
			$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
			$VecesVendida = $row["Cuenta"];

			if($VecesVendida>0){
				$CuentaVenta++;
			}

			$FInicial = date("Y-m-d",strtotime($FInicial."+1 days"));
  		$FFinalPivote = date("Y-m-d",strtotime($FFinalPivote."+1 days"));
		}
		return $CuentaVenta;
	}
	/**********************************************************************************/
	/*
		TITULO: ExistenciaDecreciente
		PARAMETROS: 
		FUNCION: 
		RETORNO: 
 	*/
	function ExistenciaDecreciente($connCPharma,$IdArticulo,$FInicial,$FFinal,$RangoDias) {
		
		$PrimerCiclo = TRUE;
		$ExistenciaAyer = 0;
		$CuentaDecreciente = TRUE;
		$CuentaDecrece = 0;
		$ExistenciaDecreciente = array();

		while( ($FInicial!=$FFinal) && ($CuentaDecreciente==TRUE) ) {

			$sql = MySQL_Existencia_Dias_Cero($IdArticulo,$FInicial);
			$result = mysqli_query($connCPharma,$sql);
			$row = mysqli_fetch_assoc($result);
			$ExistenciaHoy = $row["existencia"];

			if($PrimerCiclo == TRUE){
				$PrimerCiclo = FALSE;
				$ExistenciaAyer = $ExistenciaHoy;
			}

			if($ExistenciaAyer>=$ExistenciaHoy){
				$CuentaDecreciente = TRUE;
				array_push($ExistenciaDecreciente,$ExistenciaHoy);

				if($ExistenciaAyer>$ExistenciaHoy){
					$CuentaDecrece++;
				}
			}
			else{
				$CuentaDecreciente = FALSE;
			}

			$FInicial = date("Y-m-d",strtotime($FInicial."+1 days"));
		}

		if(($CuentaDecrece>=($RangoDias/2))&&($CuentaDecreciente==TRUE)){
			$CuentaDecreciente = TRUE;
		}
		else{
			$CuentaDecreciente = FALSE;
		}
		array_push($ExistenciaDecreciente,$CuentaDecreciente);
		return $ExistenciaDecreciente;
	}
	/**********************************************************************************/
	/*
		TITULO: GuardarCapturaDiaria
		PARAMETROS: [$FechaCaptura] El dia de hoy
					[$date] valor para creacion y actualizacion
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		RETORNO: no aplica
	 */
	function GuardarCapturaCaida($connCPharma,$FechaCaptura,$date) {
		$sql = MySQL_Captura_Caida($FechaCaptura);
		$result = mysqli_query($connCPharma,$sql);
		$row = mysqli_fetch_assoc($result);
		$TotalRegistros = $row["TotalRegistros"];

		$sql1 = MySQL_Guardar_Captura_Caida($TotalRegistros,$FechaCaptura,$date);
		mysqli_query($connCPharma,$sql1);
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Validar_Etiquetas
		PARAMETROS: [$conn,$IdArticulo,$IdProveedor] conecion, id del articulo, id del provedor
		FUNCION: determinar si un prducto es unico
		RETORNO: SI o NO segun sea el caso
	 */
	function FG_Validar_Etiquetas() {
			$SedeConnection = MiUbicacion();
	    $conn = FG_Conectar_Smartpharma($SedeConnection);
	    $connCPharma = FG_Conectar_CPharma();

	    $sql = SQL_Existencia_Actual();
	    $result = sqlsrv_query($conn,$sql);

	    $FechaCaptura = new DateTime("now");
		$FechaCaptura = $FechaCaptura->format('Y-m-d');
		$date = '';
	    
	    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
	        $IdArticulo = $row["IdArticulo"];
	        $CodigoInterno = $row["CodigoInterno"];
	        $Descripcion=$row["Descripcion"];
	 
	        $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
	        $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
	        $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
	        $IdArticuloCPharma = $RowCPharma['id_articulo'];

	        if(is_null($IdArticuloCPharma)){

	            $condicion = 'NO CLASIFICADO';
	            $clasificacion = 'PENDIENTE';
	            $estatus = 'ACTIVO';
	            $user = 'SYSTEM';
	            $date = new DateTime('now');
	            $date = $date->format("Y-m-d H:i:s");

	            $sqlCP = MySQL_Guardar_Etiqueta_Articulo($IdArticulo,$CodigoInterno,$Descripcion,$condicion,$clasificacion,$estatus,$user,$date);
	            mysqli_query($connCPharma,$sqlCP);
	        }
	    }
	    FG_Guardar_Captura_Etiqueta($connCPharma,$FechaCaptura,$date);
		$sqlCC = MySQL_Validar_Captura_Etiqueta($FechaCaptura);
		$resultCC = mysqli_query($connCPharma,$sqlCC);
		$rowCC = mysqli_fetch_assoc($resultCC);
		$CuentaCaptura = $rowCC["CuentaCaptura"];

		if($CuentaCaptura == 0){
			$sqlB = MySQL_Borrar_Captura_Etiqueta($FechaCaptura);
			mysqli_query($connCPharma,$sqlB);			
			mysqli_close($connCPharma);
			sqlsrv_close($conn);
			FG_Validar_Etiquetas();
		}
		else{
			mysqli_close($connCPharma);
			sqlsrv_close($conn);
		}
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Guardar_Captura_Etiqueta
		PARAMETROS: [$FechaCaptura] El dia de hoy
					[$date] valor para creacion y actualizacion
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		RETORNO: no aplica
 	*/
	function FG_Guardar_Captura_Etiqueta($connCPharma,$FechaCaptura,$date) {
		$sql = MySQL_Captura_Etiqueta($FechaCaptura);
		$result = mysqli_query($connCPharma,$sql);
		$row = mysqli_fetch_assoc($result);
		$TotalRegistros = $row["TotalRegistros"];

		$sql1 = MySQL_Guardar_Captura_Etiqueta($TotalRegistros,$FechaCaptura,$date);
		mysqli_query($connCPharma,$sql1);
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Generer_Etiquetas_Todo
		PARAMETROS: [$clasificacion] Parametro que indica el tipo de clasificacion, solo puede recibir ETIQUETABLE o OBLIGATORIO ETIQUETAR
					[$tipo] Parametro que indica la dolarizacion, solo pude recibir DOLARIZADO, NO DOLARIZADO o TODO
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		RETORNO: no aplica
 	*/
	function FG_Generer_Etiquetas_Todo($clasificacion,$tipo) {
		$SedeConnection = MiUbicacion();
  	$conn = FG_Conectar_Smartpharma($SedeConnection);
  	$connCPharma = FG_Conectar_CPharma();	

		$CuentaCard = 0;
		$CuentaEtiqueta = 0;

		$result = $connCPharma->query("SELECT id_articulo FROM etiquetas WHERE clasificacion = '$clasificacion'");

		while($row = $result->fetch_assoc()){
			$IdArticulo = $row['id_articulo'];
			$Dolarizado = FG_Producto_Dolarizado($conn,$IdArticulo);

			if(($Dolarizado=='SI')&&($tipo=='DOLARIZADO')){

				$sql2 = SQG_Detalle_Articulo($IdArticulo);
				$result2 = sqlsrv_query($conn,$sql2);
				$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);

				$CodigoArticulo = $row2["CodigoInterno"];
	      $CodigoBarra = $row2["CodigoBarra"];
	      $Descripcion = FG_Limpiar_Texto($row2["Descripcion"]);
	      $Existencia = $row2["Existencia"];
		    $ExistenciaAlmacen1 = $row2["ExistenciaAlmacen1"];
		    $ExistenciaAlmacen2 = $row2["ExistenciaAlmacen2"];
		    $IsTroquelado = $row2["Troquelado"];
		    $IsIVA = $row2["Impuesto"];
		    $UtilidadArticulo = $row2["UtilidadArticulo"];
		    $UtilidadCategoria = $row2["UtilidadCategoria"];
		    $TroquelAlmacen1 = $row2["TroquelAlmacen1"];
		    $PrecioCompraBrutoAlmacen1 = $row2["PrecioCompraBrutoAlmacen1"];
		    $TroquelAlmacen2 = $row2["TroquelAlmacen2"];
		    $PrecioCompraBrutoAlmacen2 = $row2["PrecioCompraBrutoAlmacen2"];
		    $PrecioCompraBruto = $row2["PrecioCompraBruto"];
	      $Dolarizado = $row2["Dolarizado"];
	      $Gravado = FG_Producto_Gravado($IsIVA);
	      $Dolarizado = FG_Producto_Dolarizado($conn,$Dolarizado);
	      $CondicionExistencia = 'CON_EXISTENCIA';

				if(intval($Existencia)>0){

					$PrecioHoy = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

					if($IsIVA == 1){
						$PMVP = $PrecioHoy/Impuesto;
						$IVA = $PrecioHoy-$PMVP;
					}
					else{
						$PMVP = $PrecioHoy;
						$IVA = 0;
					}

					$simbolo = '*';
					
					echo'
						<table>
							<thead>
								<tr>
									<td class="centrado titulo rowCenter" colspan="2">
										Código: '.$CodigoBarra.'
									</td>
								</tr>	
							</thead>
							<tbody>
								<tr rowspan="2">
									<td class="centrado descripcion aumento rowCenter" colspan="2">
										<strong>'.$Descripcion.'</strong> 
									</td>
								</tr>
								<tr>
									<td class="izquierda rowIzq rowIzqA">
										PMVP Bs.
									</td>
									<td class="derecha rowDer rowDerA rowDer rowDerA">
										'.number_format ($PMVP,2,"," ,"." ).'
									</td>
								</tr>
								<tr>
									<td class="izquierda rowIzq rowIzqA">
										IVA 16% Bs.
									</td>
									<td class="derecha rowDer rowDerA">
										'.number_format ($IVA,2,"," ,"." ).'
									</td>
								</tr>
								<tr>
									<td class="izquierda rowIzq rowIzqA aumento">
										<strong>Total a Pagar Bs.</strong>
									</td>
									<td class="derecha rowDer rowDerA aumento">
										<strong>
										'.number_format ($PrecioHoy,2,"," ,"." ).'
										</strong>
									</td>
								</tr>
								<tr>
									<td class="izquierda dolarizado rowIzq rowIzqA">
										<strong>'.$simbolo.'</strong>
									</td>
									<td class="derecha rowDer rowDerA">
										'.date("d-m-Y").'
									</td>
								</tr>				
							</tbody>
						</table>
					';
					$CuentaCard++;
					$CuentaEtiqueta++;
					if($CuentaCard == 3){
						echo'<br>';
						$CuentaCard=0;
					}
				}
			}
			else if(($Dolarizado=='NO')&&($tipo=='NO DOLARIZADO')){

				$sql2 = SQG_Detalle_Articulo($IdArticulo);
				$result2 = sqlsrv_query($conn,$sql2);
				$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);

				$CodigoArticulo = $row2["CodigoInterno"];
	      $CodigoBarra = $row2["CodigoBarra"];
	      $Descripcion = FG_Limpiar_Texto($row2["Descripcion"]);
	      $Existencia = $row2["Existencia"];
		    $ExistenciaAlmacen1 = $row2["ExistenciaAlmacen1"];
		    $ExistenciaAlmacen2 = $row2["ExistenciaAlmacen2"];
		    $IsTroquelado = $row2["Troquelado"];
		    $IsIVA = $row2["Impuesto"];
		    $UtilidadArticulo = $row2["UtilidadArticulo"];
		    $UtilidadCategoria = $row2["UtilidadCategoria"];
		    $TroquelAlmacen1 = $row2["TroquelAlmacen1"];
		    $PrecioCompraBrutoAlmacen1 = $row2["PrecioCompraBrutoAlmacen1"];
		    $TroquelAlmacen2 = $row2["TroquelAlmacen2"];
		    $PrecioCompraBrutoAlmacen2 = $row2["PrecioCompraBrutoAlmacen2"];
		    $PrecioCompraBruto = $row2["PrecioCompraBruto"];
	      $Dolarizado = $row2["Dolarizado"];
	      $Gravado = FG_Producto_Gravado($IsIVA);
	      $Dolarizado = FG_Producto_Dolarizado($conn,$Dolarizado);
	      $CondicionExistencia = 'CON_EXISTENCIA';

				if(intval($Existencia)>0){

					$PrecioHoy = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

					if($IsIVA == 1){
						$PMVP = $PrecioHoy/Impuesto;
						$IVA = $PrecioHoy-$PMVP;
					}
					else{
						$PMVP = $PrecioHoy;
						$IVA = 0;
					}

					$simbolo = '';
					
					echo'
						<table>
							<thead>
								<tr>
									<td class="centrado titulo rowCenter" colspan="2">
										Código: '.$CodigoBarra.'
									</td>
								</tr>	
							</thead>
							<tbody>
								<tr rowspan="2">
									<td class="centrado descripcion aumento rowCenter" colspan="2">
										<strong>'.$Descripcion.'</strong> 
									</td>
								</tr>
								<tr>
									<td class="izquierda rowIzq rowIzqA">
										PMVP Bs.
									</td>
									<td class="derecha rowDer rowDerA rowDer rowDerA">
										'.number_format ($PMVP,2,"," ,"." ).'
									</td>
								</tr>
								<tr>
									<td class="izquierda rowIzq rowIzqA">
										IVA 16% Bs.
									</td>
									<td class="derecha rowDer rowDerA">
										'.number_format ($IVA,2,"," ,"." ).'
									</td>
								</tr>
								<tr>
									<td class="izquierda rowIzq rowIzqA aumento">
										<strong>Total a Pagar Bs.</strong>
									</td>
									<td class="derecha rowDer rowDerA aumento">
										<strong>
										'.number_format ($PrecioHoy,2,"," ,"." ).'
										</strong>
									</td>
								</tr>
								<tr>
									<td class="izquierda dolarizado rowIzq rowIzqA">
										<strong>'.$simbolo.'</strong>
									</td>
									<td class="derecha rowDer rowDerA">
										'.date("d-m-Y").'
									</td>
								</tr>				
							</tbody>
						</table>
					';
					$CuentaCard++;
					$CuentaEtiqueta++;
					if($CuentaCard == 3){
						echo'<br>';
						$CuentaCard=0;
					}
				}
			}
			else if($tipo=='TODO'){

				$sql2 = SQG_Detalle_Articulo($IdArticulo);
				$result2 = sqlsrv_query($conn,$sql2);
				$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);

				$CodigoArticulo = $row2["CodigoInterno"];
	      $CodigoBarra = $row2["CodigoBarra"];
	      $Descripcion = FG_Limpiar_Texto($row2["Descripcion"]);
	      $Existencia = $row2["Existencia"];
		    $ExistenciaAlmacen1 = $row2["ExistenciaAlmacen1"];
		    $ExistenciaAlmacen2 = $row2["ExistenciaAlmacen2"];
		    $IsTroquelado = $row2["Troquelado"];
		    $IsIVA = $row2["Impuesto"];
		    $UtilidadArticulo = $row2["UtilidadArticulo"];
		    $UtilidadCategoria = $row2["UtilidadCategoria"];
		    $TroquelAlmacen1 = $row2["TroquelAlmacen1"];
		    $PrecioCompraBrutoAlmacen1 = $row2["PrecioCompraBrutoAlmacen1"];
		    $TroquelAlmacen2 = $row2["TroquelAlmacen2"];
		    $PrecioCompraBrutoAlmacen2 = $row2["PrecioCompraBrutoAlmacen2"];
		    $PrecioCompraBruto = $row2["PrecioCompraBruto"];
	      $Dolarizado = $row2["Dolarizado"];
	      $Gravado = FG_Producto_Gravado($IsIVA);
	      $Dolarizado = FG_Producto_Dolarizado($conn,$Dolarizado);
	      $CondicionExistencia = 'CON_EXISTENCIA';

				if(intval($Existencia)>0){

					$PrecioHoy = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

					if($IsIVA == 1){
						$PMVP = $PrecioHoy/Impuesto;
						$IVA = $PrecioHoy-$PMVP;
					}
					else{
						$PMVP = $PrecioHoy;
						$IVA = 0;
					}

					if($Dolarizado=='SI'){
						$simbolo = '*';
					}
					else{
						$simbolo = '';
					}
					
					echo'
						<table>
							<thead>
								<tr>
									<td class="centrado titulo rowCenter" colspan="2">
										Código: '.$CodigoBarra.'
									</td>
								</tr>	
							</thead>
							<tbody>
								<tr rowspan="2">
									<td class="centrado descripcion aumento rowCenter" colspan="2">
										<strong>'.$Descripcion.'</strong> 
									</td>
								</tr>
								<tr>
									<td class="izquierda rowIzq rowIzqA">
										PMVP Bs.
									</td>
									<td class="derecha rowDer rowDerA rowDer rowDerA">
										'.number_format ($PMVP,2,"," ,"." ).'
									</td>
								</tr>
								<tr>
									<td class="izquierda rowIzq rowIzqA">
										IVA 16% Bs.
									</td>
									<td class="derecha rowDer rowDerA">
										'.number_format ($IVA,2,"," ,"." ).'
									</td>
								</tr>
								<tr>
									<td class="izquierda rowIzq rowIzqA aumento">
										<strong>Total a Pagar Bs.</strong>
									</td>
									<td class="derecha rowDer rowDerA aumento">
										<strong>
										'.number_format ($PrecioHoy,2,"," ,"." ).'
										</strong>
									</td>
								</tr>
								<tr>
									<td class="izquierda dolarizado rowIzq rowIzqA">
										<strong>'.$simbolo.'</strong>
									</td>
									<td class="derecha rowDer rowDerA">
										'.date("d-m-Y").'
									</td>
								</tr>				
							</tbody>
						</table>
					';
					$CuentaCard++;
					$CuentaEtiqueta++;
					if($CuentaCard == 3){
						echo'<br>';
						$CuentaCard=0;
					}
				}
			}
		}
		echo "<br/>Se imprimiran ".$CuentaEtiqueta." etiquetas<br/>";
		mysqli_close($connCPharma);
    sqlsrv_close($conn);
	}
	/**********************************************************************************/
	/*
		TITULO: FG_Generer_Etiquetas
		PARAMETROS: [$clasificacion] Parametro que indica el tipo de clasificacion, solo puede recibir ETIQUETABLE o OBLIGATORIO ETIQUETAR
					[$tipo] Parametro que indica la dolarizacion, solo pude recibir DOLARIZADO, NO DOLARIZADO o TODO
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		RETORNO: no aplica
 	*/
	function FG_Generer_Etiquetas($clasificacion,$tipo,$dia) {
		$SedeConnection = MiUbicacion();
  	$conn = FG_Conectar_Smartpharma($SedeConnection);
  	$connCPharma = FG_Conectar_CPharma();	

  	$FHoy = date("Y-m-d");
		$FManana = date("Y-m-d",strtotime($FHoy."+1 days"));
		$FAyer = date("Y-m-d",strtotime($FHoy."-1 days"));

		$sql = SQL_Clean_Table('CP_Etiqueta_C1');
		sqlsrv_query($conn,$sql);
		$sql = SQL_Clean_Table('CP_Etiqueta_C2');
		sqlsrv_query($conn,$sql);
		$sql = SQL_Clean_Table('CP_Etiqueta_C3');
		sqlsrv_query($conn,$sql);
		$sql = SQL_Clean_Table('CP_Etiqueta_C4');
		sqlsrv_query($conn,$sql);

  	if($dia=='HOY'){
  		$sql1 = SQL_CP_Etiqueta_C1($FHoy,$FManana);
			sqlsrv_query($conn,$sql1);
			$sql2 = SQL_CP_Etiqueta_C2($FHoy,$FManana);
			sqlsrv_query($conn,$sql2);
			$sql2 = SQL_CP_Etiqueta_C3($FHoy,$FManana);
			sqlsrv_query($conn,$sql2);
			$sql2 = SQL_CP_Etiqueta_C4($FHoy,$FManana);
			sqlsrv_query($conn,$sql2);
			$FchaCambio = $FHoy;
  	}
  	else if($dia=='AYER'){
  		$sql1 = SQL_CP_Etiqueta_C1($FAyer,$FHoy);
			sqlsrv_query($conn,$sql1);
			$sql2 = SQL_CP_Etiqueta_C2($FAyer,$FHoy);
			sqlsrv_query($conn,$sql2);
			$sql2 = SQL_CP_Etiqueta_C3($FAyer,$FHoy);
			sqlsrv_query($conn,$sql2);
			$sql2 = SQL_CP_Etiqueta_C4($FAyer,$FHoy);
			sqlsrv_query($conn,$sql2);
			$FchaCambio = $FAyer;
  	}

  	if ($tipo=='NO DOLARIZADO'){
  		$result = sqlsrv_query($conn,"SELECT * FROM CP_Etiqueta_C4 WHERE CP_Etiqueta_C4.Dolarizado = 0 ORDER BY IdArticulo ASC");
  	}
  	else if ($tipo=='DOLARIZADO'){
  		$result = sqlsrv_query($conn,"SELECT * FROM CP_Etiqueta_C4 WHERE CP_Etiqueta_C4.Dolarizado <> 0 ORDER BY IdArticulo ASC");
  	}
  	else {
  		$result = sqlsrv_query($conn,"SELECT * FROM CP_Etiqueta_C4 ORDER BY IdArticulo ASC");
  	}

		
		$sql = SQL_Clean_Table('CP_Etiqueta_C1');
		sqlsrv_query($conn,$sql);
		$sql = SQL_Clean_Table('CP_Etiqueta_C2');
		sqlsrv_query($conn,$sql);
		$sql = SQL_Clean_Table('CP_Etiqueta_C3');
		sqlsrv_query($conn,$sql);
		$sql = SQL_Clean_Table('CP_Etiqueta_C4');
		sqlsrv_query($conn,$sql);

		$CuentaCard = 0;
		$CuentaEtiqueta = 0;

		while( $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC) ){
			$IdArticulo = $row['IdArticulo'];
			
			$result1 = $connCPharma->query("SELECT id_articulo FROM etiquetas WHERE id_articulo = '$IdArticulo' AND clasificacion = '$clasificacion'");
			$row1= $result1->fetch_assoc();
			$IdArticuloP = $row1['id_articulo'];

			if(!is_null($IdArticuloP)){

				$Dolarizado = FG_Producto_Dolarizado($conn,$IdArticulo);

				if(($Dolarizado=='SI')&&($tipo=='DOLARIZADO')){

					$sql2 = SQG_Detalle_Articulo($IdArticulo);
					$result2 = sqlsrv_query($conn,$sql2);
					$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);

					$CodigoArticulo = $row2["CodigoInterno"];
		      $CodigoBarra = $row2["CodigoBarra"];
		      $Descripcion = FG_Limpiar_Texto($row2["Descripcion"]);
		      $Existencia = $row2["Existencia"];
			    $ExistenciaAlmacen1 = $row2["ExistenciaAlmacen1"];
			    $ExistenciaAlmacen2 = $row2["ExistenciaAlmacen2"];
			    $IsTroquelado = $row2["Troquelado"];
			    $IsIVA = $row2["Impuesto"];
			    $UtilidadArticulo = $row2["UtilidadArticulo"];
			    $UtilidadCategoria = $row2["UtilidadCategoria"];
			    $TroquelAlmacen1 = $row2["TroquelAlmacen1"];
			    $PrecioCompraBrutoAlmacen1 = $row2["PrecioCompraBrutoAlmacen1"];
			    $TroquelAlmacen2 = $row2["TroquelAlmacen2"];
			    $PrecioCompraBrutoAlmacen2 = $row2["PrecioCompraBrutoAlmacen2"];
			    $PrecioCompraBruto = $row2["PrecioCompraBruto"];
		      $Dolarizado = $row2["Dolarizado"];
		      $Gravado = FG_Producto_Gravado($IsIVA);
		      $Dolarizado = FG_Producto_Dolarizado($conn,$Dolarizado);
		      $CondicionExistencia = 'CON_EXISTENCIA';

					if(intval($Existencia)>0){

						$PrecioHoy = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

						$sqlCC = MySQL_DiasCero_PrecioAyer($IdArticulo,$FchaCambio);
						$resultCC = mysqli_query($connCPharma,$sqlCC);
						$rowCC = mysqli_fetch_assoc($resultCC);
						$PrecioAyer = $rowCC["precio"];

						if( floatval(round($PrecioHoy,2)) != floatval($PrecioAyer) ){
							if($IsIVA == 1){
								$PMVP = $PrecioHoy/Impuesto;
								$IVA = $PrecioHoy-$PMVP;
							}
							else{
								$PMVP = $PrecioHoy;
								$IVA = 0;
							}

							$simbolo = '*';
							
							echo'
								<table>
									<thead>
										<tr>
											<td class="centrado titulo rowCenter" colspan="2">
												Código: '.$CodigoBarra.'
											</td>
										</tr>	
									</thead>
									<tbody>
										<tr rowspan="2">
											<td class="centrado descripcion aumento rowCenter" colspan="2">
												<strong>'.$Descripcion.'</strong> 
											</td>
										</tr>
										<tr>
											<td class="izquierda rowIzq rowIzqA">
												PMVP Bs.
											</td>
											<td class="derecha rowDer rowDerA rowDer rowDerA">
												'.number_format ($PMVP,2,"," ,"." ).'
											</td>
										</tr>
										<tr>
											<td class="izquierda rowIzq rowIzqA">
												IVA 16% Bs.
											</td>
											<td class="derecha rowDer rowDerA">
												'.number_format ($IVA,2,"," ,"." ).'
											</td>
										</tr>';
										if( floatval(round($PrecioHoy,2)) < floatval($PrecioAyer) ){
											echo'
												<tr>
													<td class="izquierda rowIzq rowIzqA" style="color:red;">
														Precio Bs. Antes
													</td>
													<td class="derecha rowDer rowDerA" style="color:red;">
														<del>
														'.number_format ($PrecioAyer,2,"," ,"." ).'
														</del>
													</td>
												</tr>
											';
										}
										echo'
										<tr>
											<td class="izquierda rowIzq rowIzqA aumento">
												<strong>Total a Pagar Bs.</strong>
											</td>
											<td class="derecha rowDer rowDerA aumento">
												<strong>
												'.number_format ($PrecioHoy,2,"," ,"." ).'
												</strong>
											</td>
										</tr>
										<tr>
											<td class="izquierda dolarizado rowIzq rowIzqA">
												<strong>'.$simbolo.'</strong>
											</td>
											<td class="derecha rowDer rowDerA">
												'.date("d-m-Y").'
											</td>
										</tr>				
									</tbody>
								</table>
							';
							$CuentaCard++;
							$CuentaEtiqueta++;
							if($CuentaCard == 3){
								echo'<br>';
								$CuentaCard=0;
							}
						}
					}
				}
				else if(($Dolarizado=='NO')&&($tipo=='NO DOLARIZADO')){

					$sql2 = SQG_Detalle_Articulo($IdArticulo);
					$result2 = sqlsrv_query($conn,$sql2);
					$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);

					$CodigoArticulo = $row2["CodigoInterno"];
		      $CodigoBarra = $row2["CodigoBarra"];
		      $Descripcion = FG_Limpiar_Texto($row2["Descripcion"]);
		      $Existencia = $row2["Existencia"];
			    $ExistenciaAlmacen1 = $row2["ExistenciaAlmacen1"];
			    $ExistenciaAlmacen2 = $row2["ExistenciaAlmacen2"];
			    $IsTroquelado = $row2["Troquelado"];
			    $IsIVA = $row2["Impuesto"];
			    $UtilidadArticulo = $row2["UtilidadArticulo"];
			    $UtilidadCategoria = $row2["UtilidadCategoria"];
			    $TroquelAlmacen1 = $row2["TroquelAlmacen1"];
			    $PrecioCompraBrutoAlmacen1 = $row2["PrecioCompraBrutoAlmacen1"];
			    $TroquelAlmacen2 = $row2["TroquelAlmacen2"];
			    $PrecioCompraBrutoAlmacen2 = $row2["PrecioCompraBrutoAlmacen2"];
			    $PrecioCompraBruto = $row2["PrecioCompraBruto"];
		      $Dolarizado = $row2["Dolarizado"];
		      $Gravado = FG_Producto_Gravado($IsIVA);
		      $Dolarizado = FG_Producto_Dolarizado($conn,$Dolarizado);
		      $CondicionExistencia = 'CON_EXISTENCIA';

					if(intval($Existencia)>0){

						$PrecioHoy = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

						$sqlCC = MySQL_DiasCero_PrecioAyer($IdArticulo,$FchaCambio);
						$resultCC = mysqli_query($connCPharma,$sqlCC);
						$rowCC = mysqli_fetch_assoc($resultCC);
						$PrecioAyer = $rowCC["precio"];

						if( floatval(round($PrecioHoy,2)) != floatval($PrecioAyer) ){
							if($IsIVA == 1){
								$PMVP = $PrecioHoy/Impuesto;
								$IVA = $PrecioHoy-$PMVP;
							}
							else{
								$PMVP = $PrecioHoy;
								$IVA = 0;
							}

							$simbolo = '';
							
							echo'
								<table>
									<thead>
										<tr>
											<td class="centrado titulo rowCenter" colspan="2">
												Código: '.$CodigoBarra.'
											</td>
										</tr>	
									</thead>
									<tbody>
										<tr rowspan="2">
											<td class="centrado descripcion aumento rowCenter" colspan="2">
												<strong>'.$Descripcion.'</strong> 
											</td>
										</tr>
										<tr>
											<td class="izquierda rowIzq rowIzqA">
												PMVP Bs.
											</td>
											<td class="derecha rowDer rowDerA rowDer rowDerA">
												'.number_format ($PMVP,2,"," ,"." ).'
											</td>
										</tr>
										<tr>
											<td class="izquierda rowIzq rowIzqA">
												IVA 16% Bs.
											</td>
											<td class="derecha rowDer rowDerA">
												'.number_format ($IVA,2,"," ,"." ).'
											</td>
										</tr>';
										if( floatval(round($PrecioHoy,2)) < floatval($PrecioAyer) ){
											echo'
												<tr>
													<td class="izquierda rowIzq rowIzqA" style="color:red;">
														Precio Bs. Antes
													</td>
													<td class="derecha rowDer rowDerA" style="color:red;">
														<del>
														'.number_format ($PrecioAyer,2,"," ,"." ).'
														</del>
													</td>
												</tr>
											';
										}
										echo'
										<tr>
											<td class="izquierda rowIzq rowIzqA aumento">
												<strong>Total a Pagar Bs.</strong>
											</td>
											<td class="derecha rowDer rowDerA aumento">
												<strong>
												'.number_format ($PrecioHoy,2,"," ,"." ).'
												</strong>
											</td>
										</tr>
										<tr>
											<td class="izquierda dolarizado rowIzq rowIzqA">
												<strong>'.$simbolo.'</strong>
											</td>
											<td class="derecha rowDer rowDerA">
												'.date("d-m-Y").'
											</td>
										</tr>				
									</tbody>
								</table>
							';
							$CuentaCard++;
							$CuentaEtiqueta++;
							if($CuentaCard == 3){
								echo'<br>';
								$CuentaCard=0;
							}
						}
					}
				}
				else if($tipo=='TODO'){

					$sql2 = SQG_Detalle_Articulo($IdArticulo);
					$result2 = sqlsrv_query($conn,$sql2);
					$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);

					$CodigoArticulo = $row2["CodigoInterno"];
		      $CodigoBarra = $row2["CodigoBarra"];
		      $Descripcion = FG_Limpiar_Texto($row2["Descripcion"]);
		      $Existencia = $row2["Existencia"];
			    $ExistenciaAlmacen1 = $row2["ExistenciaAlmacen1"];
			    $ExistenciaAlmacen2 = $row2["ExistenciaAlmacen2"];
			    $IsTroquelado = $row2["Troquelado"];
			    $IsIVA = $row2["Impuesto"];
			    $UtilidadArticulo = $row2["UtilidadArticulo"];
			    $UtilidadCategoria = $row2["UtilidadCategoria"];
			    $TroquelAlmacen1 = $row2["TroquelAlmacen1"];
			    $PrecioCompraBrutoAlmacen1 = $row2["PrecioCompraBrutoAlmacen1"];
			    $TroquelAlmacen2 = $row2["TroquelAlmacen2"];
			    $PrecioCompraBrutoAlmacen2 = $row2["PrecioCompraBrutoAlmacen2"];
			    $PrecioCompraBruto = $row2["PrecioCompraBruto"];
		      $Dolarizado = $row2["Dolarizado"];
		      $Gravado = FG_Producto_Gravado($IsIVA);
		      $Dolarizado = FG_Producto_Dolarizado($conn,$Dolarizado);
		      $CondicionExistencia = 'CON_EXISTENCIA';

					if(intval($Existencia)>0){

						$PrecioHoy = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

						$sqlCC = MySQL_DiasCero_PrecioAyer($IdArticulo,$FchaCambio);
						$resultCC = mysqli_query($connCPharma,$sqlCC);
						$rowCC = mysqli_fetch_assoc($resultCC);
						$PrecioAyer = $rowCC["precio"];

						if( floatval(round($PrecioHoy,2)) != floatval($PrecioAyer) ){
							if($IsIVA == 1){
								$PMVP = $PrecioHoy/Impuesto;
								$IVA = $PrecioHoy-$PMVP;
							}
							else{
								$PMVP = $PrecioHoy;
								$IVA = 0;
							}

							if($Dolarizado=='SI'){
								$simbolo = '*';
							}
							else{
								$simbolo = '';
							}
							
							echo'
								<table>
									<thead>
										<tr>
											<td class="centrado titulo rowCenter" colspan="2">
												Código: '.$CodigoBarra.'
											</td>
										</tr>	
									</thead>
									<tbody>
										<tr rowspan="2">
											<td class="centrado descripcion aumento rowCenter" colspan="2">
												<strong>'.$Descripcion.'</strong> 
											</td>
										</tr>
										<tr>
											<td class="izquierda rowIzq rowIzqA">
												PMVP Bs.
											</td>
											<td class="derecha rowDer rowDerA rowDer rowDerA">
												'.number_format ($PMVP,2,"," ,"." ).'
											</td>
										</tr>
										<tr>
											<td class="izquierda rowIzq rowIzqA">
												IVA 16% Bs.
											</td>
											<td class="derecha rowDer rowDerA">
												'.number_format ($IVA,2,"," ,"." ).'
											</td>
										</tr>';
										if( floatval(round($PrecioHoy,2)) < floatval($PrecioAyer) ){
											echo'
												<tr>
													<td class="izquierda rowIzq rowIzqA" style="color:red;">
														Precio Bs. Antes
													</td>
													<td class="derecha rowDer rowDerA" style="color:red;">
														<del>
														'.number_format ($PrecioAyer,2,"," ,"." ).'
														</del>
													</td>
												</tr>
											';
										}
										echo'
										<tr>
											<td class="izquierda rowIzq rowIzqA aumento">
												<strong>Total a Pagar Bs.</strong>
											</td>
											<td class="derecha rowDer rowDerA aumento">
												<strong>
												'.number_format ($PrecioHoy,2,"," ,"." ).'
												</strong>
											</td>
										</tr>
										<tr>
											<td class="izquierda dolarizado rowIzq rowIzqA">
												<strong>'.$simbolo.'</strong>
											</td>
											<td class="derecha rowDer rowDerA">
												'.date("d-m-Y").'
											</td>
										</tr>				
									</tbody>
								</table>
							';
							$CuentaCard++;
							$CuentaEtiqueta++;
							if($CuentaCard == 3){
								echo'<br>';
								$CuentaCard=0;
							}
						}
					}
				}
			}
		}
		echo "<br/>Se imprimiran ".$CuentaEtiqueta." etiquetas<br/>";
		mysqli_close($connCPharma);
    sqlsrv_close($conn);
	}
	/**********************************************************************************/
	/*
		TITULO: TasaFechaConversion
		PARAMETROS: [$Fecha] Fecha de la que se buscara la tasa
		FUNCION: Buscar el valor de la tasa
		RETORNO: Valor de la tasa
	 */
	function TasaFechaConversion($Fecha,$Moneda) {
		$resultTasaConversion = MySQL_Tasa_Conversion($Fecha,$Moneda);
		$resultTasaConversion = mysqli_fetch_assoc($resultTasaConversion);
		$Tasa = $resultTasaConversion['tasa'];
		return $Tasa;
	}
?>