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
		$sql = QG_Provedor_Unico($IdProveedor,$IdArticulo);
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

    $sql = QG_Articulos_Ajuste($IdAjuste);
    $result = sqlsrv_query($conn,$sql);
   
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["InvArticuloId"];
      $Cantidad = $row["Cantidad"];
      $IdLote = $row["lote"];
      $IdTraslado = $NumeroAjuste;

      $sql1 = QG_Detalle_Articulo($IdArticulo);
      $result1 = sqlsrv_query($conn,$sql1);
      $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

      $CodigoArticulo = $row1["CodigoInterno"];
      $CodigoBarra = $row1["CodigoBarra"];
      $Descripcion = FG_Limpiar_Texto($row1["Descripcion"]);
      $Existencia = $row1["Existencia"];
      $Dolarizado = $row1["Dolarizado"];
      $IsIVA = $row1["Impuesto"];
      $Utilidad = $row1["Utilidad"];
      $TroquelAlmacen1 = $row1["TroquelAlmacen1"];
      $TroquelAlmacen2 = $row1["TroquelAlmacen2"];
      $PrecioCompraBruto = $row1["PrecioCompraBruto"];
      $Gravado = FG_Producto_Gravado($IsIVA);
      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);


      if($Existencia==0){

      	$sql3 = QG_Lote_Sin_Existencia($IdLote,$IdArticulo);
	      $result3 = sqlsrv_query($conn,$sql3);
	      $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

	      $IsIVASE = $row3["Impuesto"];
	      $UtilidadSE = $row3["Utilidad"];
	      $TroquelAlmacen1SE = $row3["TroquelAlmacen1"];
	      $TroquelAlmacen2SE = $row3["TroquelAlmacen2"];
	      $PrecioCompraBrutoSE = $row3["PrecioCompraBruto"];

      	if($Dolarizado=='SI') {
	        	$TasaActualSE = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
	        	$PrecioSE = FG_Calculo_Precio_Sin_Existencia($TroquelAlmacen1SE,$PrecioCompraBrutoSE,$UtilidadSE,$IsIVASE,$TroquelAlmacen2SE);

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
	        $Precio = FG_Calculo_Precio($Existencia,$TroquelAlmacen1,$PrecioCompraBruto,$Utilidad,$IsIVA,$TroquelAlmacen2);

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
    sqlsrv_close($conn);
    mysqli_close($connCPharma);
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
			case 'Productos en caida':
				$ruta = '/reporte14';
			break;
			case 'Articulos devaluados':
				$ruta = '/reporte15';
			break;
			default:
				$ruta = '#';
			break;
		}
		return $ruta;
	}
?>