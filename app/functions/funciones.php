<?php
	/*
		TITULO: ConectarXampp
		PARAMETROS: No Aplica
		FUNCION: Conexion con servidor de XAMPP
		RETORNO: Conexion a XAMPP
	*/
	function ConectarXampp() {
		$conexion = mysqli_connect(serverCP,userCP,passCP);
		mysqli_select_db($conexion,nameCP);
	    return $conexion;
	}
	/*
		TITULO: QLastRestoreDB
		PARAMETROS: [$nameDataBase] Nombre de la base de datos a buscar
		FUNCION: Busca la fecha de la ultima restauracion de la base de datos
		RETORNO: Fecha de ultima restauracion
	 */
	function LastRestoreDB($nameDataBase,$SedeConnection){
		$conn = ConectarSmartpharma($SedeConnection);
		$sql = QLastRestoreDB($nameDataBase);
		$result = sqlsrv_query($conn,$sql);
		$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$FechaRestauracion = $row["FechaRestauracion"]->format("Y-m-d h:i:s a");
		return $FechaRestauracion;
	}
	/*
		TITULO: ValidarConectividad
		PARAMETROS: [$conn] $conexion con la sede
		FUNCION: Valida la conexion con un servidor definido
		RETORNO: Estatus de la conectividad
	 */
	function ValidarConectividad($SedeConnection){
		$InicioCarga = new DateTime("now");

		$NombreSede = NombreSede($SedeConnection);
		$conn = ConectarSmartpharma($SedeConnection);
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
	/*
		TITULO: ConectarSmartpharma
		PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
		FUNCION: Conectar el sistema con la sede que se desea
		RETORNO: Conexion a Smartpharma
	 */
	function ConectarSmartpharma($SedeConnection) {
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
		}
	}
	/*
		TITULO: MiUbicacion
		PARAMETROS: No aplica
		FUNCION: Descubrir desde que sede estoy entrando a la aplicacion
		RETORNO: Sede en la que me encuentro
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

			case '10':
				return 'GP';
			break;
		/*FIN BLOQUE DE FTN*/
		/*INICIO BLOQUE DE FLL*/
			case '7':
				return 'FLL';
			break;
		/*FIN BLOQUE DE FTN*/
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
	/*
		TITULO: NombreSede
		PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
		FUNCION: Retornar el nombre de la sede con la que se esta conectando
		RETORNO: Nombre de la sede conectada
	 */
	function NombreSede($SedeConnection) {
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
			case 'DBs':
				$sede = SedeDBs;
				return $sede;
			break;

			case 'DBm':
				$sede = SedeDBm;
				return $sede;
			break;
		}
	}
	/*
		TITULO: ConsultaEspecialSmartpharma
		PARAMETROS: [$sql] Cadena de sql a ejecutar,
					[$SedeConnection] Siglas de la sede para la conexion
		FUNCION: Hacer una consulta a smartpharma
		RETORNO: Arreglo de datos
	 */
	function ConsultaEspecialSmartpharma($sql,$SedeConnection) {
		$conn = ConectarSmartpharma($SedeConnection);
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
	/*
		TITULO: array_flatten_recursive
		PARAMETROS: [$array] Arreglo para aplicar la recursividad
		FUNCION: Iterar en el arreglo para la busqueda de variables
		RETORNO: Palabra clave buscada
	 */
	function array_flatten_recursive($array) {
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
	/*
		TITULO: armarJson
		PARAMETROS: [$sql] Cadena de sql a ejecutar,
					[$SedeConnection] Siglas de la sede para la conexion
		FUNCION: Armar un arreglo Json con las palabras claves encontradas
		RETORNO: Arreglo Json
	 */
	function armarJson($sql,$SedeConnection) {
		$result = ConsultaEspecialSmartpharma ($sql,$SedeConnection);
	    $arrayJson = array_flatten_recursive($result);
	    return json_encode($arrayJson);
	}
	/*
		TITULO: CalculoPrecio
		PARAMETROS: [$conn] Cadena de conexion para la base de datos
					[$IdArticulo] Id del articulo
					[$IsIVA] Si aplica o no
		FUNCION: Calcular el precio del articulo
		RETORNO: Precio del articulo
	 */
	function CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia) {
		$Precio = 0;

		if($Existencia == 0) {
			$Precio = 0;
		}
		else {
		/*PRECIO TROQUELADO*/
			$sql0 = QCleanTable('CP_QLoteArticulo');
			sqlsrv_query($conn,$sql0);

			$sql = QLoteArticulo($IdArticulo,0);
			sqlsrv_query($conn,$sql);
			
			$sql1 = QLote();
			$result1 = sqlsrv_query($conn,$sql1);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
			$PrecioTroquelado = $row1["M_PrecioTroquelado"];
			
			if($PrecioTroquelado!=NULL) {
				$Precio = $PrecioTroquelado;
			}
		/*PRECIO CALCULADO*/
			else {
				$sql0 = QCleanTable('CP_QLoteArticulo');
				sqlsrv_query($conn,$sql0);

				$sql2 = QLoteArticulo($IdArticulo,1);
				sqlsrv_query($conn,$sql2);
				
				$sql3 = QLote();
				$result3 = sqlsrv_query($conn,$sql3);
				$row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
				$PrecioBruto = $row3["M_PrecioCompraBruto"];

				if($IsIVA == 1) {
					$PrecioCalculado = ($PrecioBruto/Utilidad)*Impuesto;
					$Precio = $PrecioCalculado;
				}
				else {
					$PrecioCalculado = ($PrecioBruto/Utilidad);
					$Precio = $PrecioCalculado;
				}
			}
		}
		$sql0 = QCleanTable('CP_QLoteArticulo');
		sqlsrv_query($conn,$sql0);

		return $Precio;
	}
	/*
		TITULO: CalculoPrecioDiasCero
		PARAMETROS: [$conn] Cadena de conexion para la base de datos
					[$IdArticulo] Id del articulo
					[$IsIVA] Si aplica o no
		FUNCION: Calcular el precio del articulo
		RETORNO: Precio del articulo
	 */
	function CalculoPrecioDiasCero($conn,$IdArticulo,$IsIVA,$Existencia) {
		$Precio = 0;

		if($Existencia == 0) {
			$Precio = 0;
		}
		else {
		/*PRECIO TROQUELADO*/
			$sql0 = QCleanTable('CP_QLoteArticuloDiasCero');
			sqlsrv_query($conn,$sql0);

			$sql = QLoteArticuloDiasCero($IdArticulo,0);
			sqlsrv_query($conn,$sql);
			
			$sql1 = QLoteDiasCero();
			$result1 = sqlsrv_query($conn,$sql1);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
			$PrecioTroquelado = $row1["M_PrecioTroquelado"];
			
			if($PrecioTroquelado!=NULL) {
				$Precio = $PrecioTroquelado;
			}
		/*PRECIO CALCULADO*/
			else {
				$sql0 = QCleanTable('CP_QLoteArticuloDiasCero');
				sqlsrv_query($conn,$sql0);

				$sql2 = QLoteArticuloDiasCero($IdArticulo,1);
				sqlsrv_query($conn,$sql2);
				
				$sql3 = QLoteDiasCero();
				$result3 = sqlsrv_query($conn,$sql3);
				$row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
				$PrecioBruto = $row3["M_PrecioCompraBruto"];

				if($IsIVA == 1) {
					$PrecioCalculado = ($PrecioBruto/Utilidad)*Impuesto;
					$Precio = $PrecioCalculado;
				}
				else {
					$PrecioCalculado = ($PrecioBruto/Utilidad);
					$Precio = $PrecioCalculado;
				}
			}
		}
		$sql0 = QCleanTable('CP_QLoteArticuloDiasCero');
		sqlsrv_query($conn,$sql0);

		return $Precio;
	}

	/*
		TITULO: CalculoPrecioDevaluado
		PARAMETROS: [$conn] Cadena de conexion para la base de datos
					[$IdArticulo] Id del articulo
					[$IsIVA] Si aplica o no
		FUNCION: Calcular el precio del articulo
		RETORNO: Precio del articulo
	 */
	function CalculoPrecioDevaluado($conn,$IdArticulo,$IsIVA,$Existencia) {
		$Precio = 0;

		if($Existencia == 0) {
			$Precio = 0;
		}
		else {
		/*PRECIO TROQUELADO*/
			$sql0 = QCleanTable('CP_QLoteArticuloDevaluado');
			sqlsrv_query($conn,$sql0);

			$sql = QLoteArticuloDevaluado($IdArticulo,0);
			sqlsrv_query($conn,$sql);
			
			$sql1 = QLoteDevaluado();
			$result1 = sqlsrv_query($conn,$sql1);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
			$PrecioTroquelado = $row1["M_PrecioTroquelado"];
			
			if($PrecioTroquelado!=NULL) {
				$Precio = $PrecioTroquelado;
			}
		/*PRECIO CALCULADO*/
			else {
				$sql0 = QCleanTable('CP_QLoteArticuloDevaluado');
				sqlsrv_query($conn,$sql0);

				$sql2 = QLoteArticuloDevaluado($IdArticulo,1);
				sqlsrv_query($conn,$sql2);
				
				$sql3 = QLoteDevaluado();
				$result3 = sqlsrv_query($conn,$sql3);
				$row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
				$PrecioBruto = $row3["M_PrecioCompraBruto"];

				if($IsIVA == 1) {
					$PrecioCalculado = ($PrecioBruto/Utilidad)*Impuesto;
					$Precio = $PrecioCalculado;
				}
				else {
					$PrecioCalculado = ($PrecioBruto/Utilidad);
					$Precio = $PrecioCalculado;
				}
			}
		}
		$sql0 = QCleanTable('CP_QLoteArticuloDevaluado');
		sqlsrv_query($conn,$sql0);

		return $Precio;
	}

	/*
		TITULO: TasaFechaConversion
		PARAMETROS: [$Fecha] Fecha de la que se buscara la tasa
		FUNCION: Buscar el valor de la tasa
		RETORNO: Valor de la tasa
	 */
	function TasaFechaConversion($Fecha,$Moneda) {
		$resultTasaConversion = QTasaConversion($Fecha,$Moneda);
		$resultTasaConversion = mysqli_fetch_assoc($resultTasaConversion);
		$Tasa = $resultTasaConversion['tasa'];
		return $Tasa;
	}
	/*
		TITULO: TasaFecha
		PARAMETROS: [$Fecha] Fecha de la que se buscara la tasa
		FUNCION: Buscar el valor de la tasa
		RETORNO: Valor de la tasa
	 */
	function TasaFecha($Fecha) {
		$resultTasa = QTasa($Fecha);
		$resultTasa = mysqli_fetch_assoc($resultTasa);
		$Tasa = $resultTasa['tasa'];
		return $Tasa;
	}
	/*
		TITULO: RangoDias
		PARAMETROS: [$FInicial] Fecha donde inicia el rango
					[$FFinal] Fecha donde termina el rango
		FUNCION: Calcular el rango de diferencia de dias entre el las fechas
		RETORNO: rango de dias
	 */
	function RangoDias($FInicial,$FFinal) {
		$FechaI = new DateTime($FInicial);
	    $FechaF = new DateTime($FFinal);
	    $Rango = $FechaI->diff($FechaF);
	    $Rango = $Rango->format('%a');
	    return $Rango;
	}
	/*
		TITULO: VentaDiaria
		PARAMETROS: [$Venta] Venta
					[$RangoDias] Rango de dias entre dos fechas
		FUNCION: Calcular la venta al diaria promedio en base al rango de dias
		RETORNO: Venta diaria promedio
	 */
	function VentaDiaria($Venta,$RangoDias) {
		$VentaDiaria = 0;
		if($RangoDias != 0){
			$VentaDiaria = $Venta/$RangoDias;
		}
		$VentaDiaria = round($VentaDiaria,2);
		return $VentaDiaria;
	}
	/*
		TITULO: DiasRestantes
		PARAMETROS: [$Existencia] Existencia de un producto
					[$VentaDiaria] Venta diaria promedio del prodcuto
		FUNCION: Calcular los dias restantes de inventario promedio
		RETORNO: Dias restantes promedio
	 */
	function DiasRestantes($Existencia,$VentaDiaria) {
		$DiasRestantes = 0;
		if($VentaDiaria != 0){
			$DiasRestantes = $Existencia/$VentaDiaria;
		}
		$DiasRestantes = round($DiasRestantes,2);
		return $DiasRestantes;
	}
	/*
		TITULO: ProductoUnico
		PARAMETROS: [$conn,$IdArticulo,$IdProveedor] conecion, id del articulo, id del provedor
		FUNCION: determinar si un prducto es unico
		RETORNO: SI o NO segun sea el caso
	 */
	function ProductoUnico($conn,$IdArticulo,$IdProveedor) {
		$Unico = '';
		
		$sql = QCleanTable('QFacturasProducto');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QProvedorUnico');
		sqlsrv_query($conn,$sql);			
		
		$sql1 = QFacturasProducto($IdArticulo);
		$sql2 = QProvedorUnico($IdProveedor);
		$sql3 = QProvedorUnicoLista();
		
		sqlsrv_query($conn,$sql1);
		sqlsrv_query($conn,$sql2);

		$params = array();
		$options =  array("Scrollable"=>SQLSRV_CURSOR_KEYSET);
		$result = sqlsrv_query($conn,$sql3,$params,$options);

		$row_count = sqlsrv_num_rows($result);
		
		if($row_count == 0) {
			$Unico = 'SI';
		}
		else {
			$Unico = 'NO';
		}

		$sql = QCleanTable('QFacturasProducto');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QProvedorUnico');
		sqlsrv_query($conn,$sql);

	  	return $Unico;
	}
	/*
		TITULO: CantidadPedido
		PARAMETROS: [$Existencia] Existencia de un producto
					[$VentaDiaria] Venta diaria promedio del prodcuto
					[$DiasPedido] Dias para el pedido
		FUNCION: calcular la cantidad a pedir
		RETORNO: cantidad a pedir
	 */
	function CantidadPedido($VentaDiaria,$DiasPedido,$Existencia) {
		$CantidadPedido = (($VentaDiaria * $DiasPedido)-$Existencia);
		return $CantidadPedido;
	}
	/*
		TITULO: ValidarFechas
		PARAMETROS: [$Fecha1] Fecha que se restaras
					[$Fecha2] Fecha a la que se le restara
		FUNCION: Calcula la diferencia entre ambas fechas restando la primera a la segunda
		RETORNO: 
			- Un numero positivo en caso de que la segunda fecha sea mayor
			- Un numero negativo en caso de que la segunda fecha sea menor
			- Cero en caso de que ambas fechas sean iguales
	 */
	function ValidarFechas($Fecha1,$Fecha2) {
		$fecha_inicial = new DateTime($Fecha1);
        $fecha_final = new DateTime($Fecha2);

		$diferencia = $fecha_inicial->diff($fecha_final);
		$diferencia_numero = (int)$diferencia->format('%R%a');

		return $diferencia_numero;
	}
	/*
		TITULO: ProductoDolarizado
		PARAMETROS: [$conn] cadena de conexion
					[$IdArticulo] id del articulo a buscar
		FUNCION: Determina si el producto esta dolarizado 
		RETORNO: Retorna si el producto esta dolarizado o no
 	*/
	function ProductoDolarizado($conn,$IdArticulo) {
		$Dolarizado = '';
		
		$sql = QDolarizados();
		$result = sqlsrv_query($conn,$sql);
		$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$IdDolarizado = $row["Id"];

		$sql1 = QArticuloDolarizado($IdArticulo,$IdDolarizado);
		$params = array();
		$options =  array("Scrollable"=>SQLSRV_CURSOR_KEYSET);
		$result1 = sqlsrv_query($conn,$sql1,$params,$options);
		$row_count = sqlsrv_num_rows($result1);
		
		if($row_count == 0) {
			$Dolarizado = 'NO';
		}
		else {
			$Dolarizado = 'SI';
		}

	  	return $Dolarizado;
	}
	/*
		TITULO: Pesca
		PARAMETROS: No aplica
		FUNCION: buscar la diferencia de datos entre dos bases de datos
		RETORNO: dierencias entre dos tablas de una base de datos
	 */
	function Pesca(){

		$conn = ConectarSmartpharma('DBm');
		$sql = QDiasEnCero();
		$Result_SQLServer = sqlsrv_query($conn,$sql);

		while ($Tabla_SQLServer = sqlsrv_fetch_array($Result_SQLServer, SQLSRV_FETCH_ASSOC)) {
			
			$IdArticulo_SqlServer = $Tabla_SQLServer["IdArticulo"];

			$connCP = ConectarXampp();
			$sqlCP = "SELECT id_articulo FROM dias_ceros where id_articulo ='$IdArticulo_SqlServer'";
			$Result_Dias_Cero = mysqli_query($connCP,$sqlCP);
			$Tabla_Dias_Cero = mysqli_fetch_assoc($Result_Dias_Cero);
			$IdArticulo_Dias_Cero = $Tabla_Dias_Cero['id_articulo'];

			if($IdArticulo_SqlServer != $IdArticulo_Dias_Cero){
				echo '
				<div class="input-group md-form form-sm form-1 pl-0">
				  <div class="input-group-prepend">
				    <span class="input-group-text purple lighten-3" id="basic-text1">
				    	<i class="fas fa-search text-white"
				        aria-hidden="true"></i>
				    </span>
				  </div>
				  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterFirsTable()">
				</div>
				<br/>
				<table class="table table-striped table-bordered col-12 sortable" id="myTable">
				  	<thead class="thead-dark">
					    <tr>
					      	<th scope="col">IdArticulo</th>
					      	<th scope="col">CodigoInterno</th>
					      	<th scope="col">Descripcion</th>
					      	<th scope="col">Existencia</th>
					    </tr>
				  	</thead>
				  	<tbody>
				';

				echo '<tr>';
				echo '<td>'.$Tabla_SQLServer['IdArticulo'].'</td>';
				echo '<td>'.$Tabla_SQLServer['CodigoInterno'].'</td>';
				echo '<td>'.$Tabla_SQLServer['Descripcion'].'</td>';
				echo '<td>'.$Tabla_SQLServer['Existencia'].'</td>';
				echo '</tr>';

				echo '
		  		</tbody>
			</table>';
			}
		}
		sqlsrv_close($conn);
	}
	/*
		TITULO: GuardarCapturaDiaria
		PARAMETROS: [$FechaCaptura] El dia de hoy
					[$date] valor para creacion y actualizacion
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		RETORNO: no aplica
	 */
	function GuardarCapturaDiaria($FechaCaptura,$date) {
		$conn = ConectarXampp();
		$sql = QCapturaDiaria($FechaCaptura);
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_assoc($result);
		$TotalRegistros = $row["TotalRegistros"];

		$sql1 = QGuardarCapturaDiaria($TotalRegistros,$FechaCaptura,$date);
		mysqli_query($conn,$sql1);

		mysqli_close($conn);
	}
	/*
		TITULO: GuardarAuditoria
		PARAMETROS: $accion,$tabla,$registro,$user
		FUNCION: capturar y guardar el evento en la auditoria
		RETORNO: no aplica
	 */
	function GuardarAuditoria($accion,$tabla,$registro) {
		$conn = ConectarXampp();
		$date = new DateTime('now');
		$date = $date->format("Y-m-d H:i:s");
		$user = auth()->user()->name;
		$sql = QGuardarAuditoria($accion,$tabla,$registro,$user,$date);
		mysqli_query($conn,$sql);
		mysqli_close($conn);
	}

	/*
		TITULO: ReporteDiasEnCero
		PARAMETROS: no aplica
		FUNCION: Captura y almacena la data para dias en cero
		RETORNO: no aplica
	 */
	function DiasEnCero() {
		$SedeConnection = MiUbicacion();
		$conn = ConectarSmartpharma($SedeConnection);
		$connCPharma = ConectarXampp();

		$sql = QDiasEnCero();
		$result = sqlsrv_query($conn,$sql);

		$FechaCaptura = new DateTime("now");
		$FechaCaptura = $FechaCaptura->format('Y-m-d');
		$user = 'SYSTEM';
		$date = '';

		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["IdArticulo"];
			$CodigoInterno = $row["CodigoInterno"];
			$Descripcion_User=$row["Descripcion"];
			$Descripcion = addslashes($Descripcion_User);//Escapa los caracteres especiales
			$Existencia=intval($row["Existencia"]);
			$IsIVA = $row["ConceptoImpuesto"];
			$Precio = CalculoPrecioDiasCero($conn,$IdArticulo,$IsIVA,$Existencia);
			$date = date('Y-m-d h:i:s',time());
			
			$sqlCPharma = QGuardarDiasEnCero($IdArticulo,$CodigoInterno,$Descripcion,$Existencia,$Precio,$FechaCaptura,$user,$date);
			mysqli_query($connCPharma,$sqlCPharma);
		}
		GuardarCapturaDiaria($FechaCaptura,$date);

		$sqlCC = QValidarCapturaDiaria($FechaCaptura);
		$resultCC = mysqli_query($connCPharma,$sqlCC);
		$rowCC = mysqli_fetch_assoc($resultCC);
		$CuentaCaptura = $rowCC["CuentaCaptura"];

		if($CuentaCaptura == 0){
			$sqlB = QBorrarDiasCero($FechaCaptura);
			mysqli_query($connCPharma,$sqlB);
			mysqli_close($connCPharma);
			sqlsrv_close($conn);
			DiasEnCero();
		}
		else{
			mysqli_close($connCPharma);
			sqlsrv_close($conn);
		}
	}
	/*
		TITULO: ProductoGravado
		PARAMETROS: $IsIVA
		FUNCION: determina si un producto es gravado o no
		RETORNO: retorna si el producto es gravado o no
 	*/
	function ProductoGravado($IsIVA){
		$EsGravado = '';

		if($IsIVA == 1) {
			$EsGravado = 'SI';
		}
		else {
			$EsGravado = 'NO';
		}
	  	return $EsGravado;
	}	
	/*
		TITULO: ProductoGravado
		PARAMETROS: $IsIVA
		FUNCION: determina si un producto es gravado o no
		RETORNO: retorna si el producto es gravado o no
	 */
	function CodigoBarra($conn,$IdArticulo){
		$CodigoBarra = '';
		$sql = QCodigoBarra($IdArticulo);
		$result = sqlsrv_query($conn,$sql);
		$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$CodigoBarra = $row["CodigoBarra"];
	  	return $CodigoBarra;
	}
	/*
		TITULO: ProductoMedicina
		PARAMETROS: [$conn] cadena de conexion
					[$IdArticulo] id del articulo a buscar
		FUNCION: Determina si el producto esta dolarizado 
		RETORNO: Retorna si el producto esta dolarizado o no
 	*/
	function ProductoMedicina($conn,$IdArticulo) {
		$Medicina = '';
		
		$sql = QMedicinas();
		$result = sqlsrv_query($conn,$sql);
		$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$IdMedicina = $row["Id"];

		$sql1 = QArticuloMedicina($IdArticulo,$IdMedicina);
		$params = array();
		$options =  array("Scrollable"=>SQLSRV_CURSOR_KEYSET);
		$result1 = sqlsrv_query($conn,$sql1,$params,$options);
		$row_count = sqlsrv_num_rows($result1);
		
		if($row_count == 0) {
			$Medicina = 'MISCELANEO';
		}
		else {
			$Medicina = 'MEDICINA';
		}

	  	return $Medicina;
	}
///////////////////////////////////////////////////////////////////////////
///				FUNCIONES EN DESARROLLO PARA PRODUCTOS EN CAIDA        ///
//////////////////////////////////////////////////////////////////////////
	/*
		TITULO: CuentaExistencia
		PARAMETROS: 
		FUNCION: 
		RETORNO: 
 	*/
	function CuentaExistencia($connCPharma,$IdArticulo) {
		
		$sql = QCuentaExistencia($IdArticulo);
		$result = mysqli_query($connCPharma,$sql);
		$row = mysqli_fetch_assoc($result);
		$Cuenta = $row["Cuenta"];
		
		return $Cuenta;
	}
	/*
		TITULO: CuentaVenta
		PARAMETROS: 
		FUNCION: 
		RETORNO: 
 	*/
	function CuentaVenta($conn,$IdArticulo,$FInicial,$FFinal) {
		
		$FFinalPivote = date("Y-m-d",strtotime($FInicial."+1 days"));
		$CuentaVenta = 0;

		while($FFinalPivote!=$FFinal){

			$sql = QCuentaVenta($IdArticulo,$FInicial,$FFinalPivote);
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
		$indice = 0;
		$ExistenciaDecreciente = array();

		while( ($FInicial!=$FFinal) && ($CuentaDecreciente==TRUE) ) {

			$sql = QExistenciaDiasCero($IdArticulo,$FInicial);
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
	/*
		TITULO: ProuctosEnCaida
		PARAMETROS: no aplica
		FUNCION: Captura y almacena la data para productos en caida
		RETORNO: no aplica
	 */
	function ProuctosEnCaida() {
		$SedeConnection = MiUbicacion();
		$conn = ConectarSmartpharma($SedeConnection);
		$connCPharma = ConectarXampp();

		$sqlB = QBorrarProductosCaida();
		mysqli_query($connCPharma,$sqlB);

		$FechaCaptura = new DateTime("now");
		$FechaCaptura = $FechaCaptura->format('Y-m-d');
		$user = 'SYSTEM';
		$date = date('Y-m-d h:i:s',time());

		/* Rangos de Fecha */
  		$FFinal = date("Y-m-d");
	  	$FInicial = date("Y-m-d",strtotime($FFinal."-10 days"));
	  	$RangoDias = intval(RangoDias($FInicial,$FFinal));

		$sql = QCleanTable('CP_FiltradoCaida');
		sqlsrv_query($conn,$sql);

		/* FILTRO 1: 
		*	Articulos con existencia actual mayor a 0 
		*	Articulos con veces vendidas en el rango
		* 	Articulos sin compra en el rango
		*/
		$sql1 = QFiltradoCaida($FInicial,$FFinal);
		sqlsrv_query($conn,$sql1);

		$sql2 = QIntegracionFiltradoCaida($RangoDias);
		$result = sqlsrv_query($conn,$sql2);

		/* Inicio while que itera en los articulos con existencia actual > 0*/
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["IdArticulo"];
			$CodigoArticulo = $row["CodigoArticulo"];
			$Descripcion_User = $row["Descripcion"];
			$Descripcion = addslashes($Descripcion_User);
			$IsIVA = $row["ConceptoImpuesto"];

			$sql1 = QExistenciaArticuloPC($IdArticulo,0);
			$result1 = sqlsrv_query($conn,$sql1);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
			$Existencia = $row1["Existencia"];

			/* FILTRO 2: 
			*	Dias restantes
			*	si dias restantes es menor de 10 entra en el rango sino es rechazado
			*/
			$sql = QCleanTable('CP_QUnidadesVendidasClienteIdPC');
			sqlsrv_query($conn,$sql);
			$sql = QCleanTable('CP_QUnidadesDevueltaClienteIdPC');
			sqlsrv_query($conn,$sql);
			$sql = QCleanTable('CP_QUnidadesCompradasProveedorIdPC');
			sqlsrv_query($conn,$sql);
			$sql = QCleanTable('CP_QUnidadesReclamoProveedorIdPC');
			sqlsrv_query($conn,$sql);
			$sql = QCleanTable('CP_QIntegracionProductosVendidosIdPC');
			sqlsrv_query($conn,$sql);		

			$sql6 = QUnidadesVendidasClienteIdPC($FInicial,$FFinal,$IdArticulo);
			$sql7 = QUnidadesDevueltaClienteIdPC($FInicial,$FFinal,$IdArticulo);
			$sql8 = QUnidadesCompradasProveedorIdPC($FInicial,$FFinal,$IdArticulo);
			$sql9 = QUnidadesReclamoProveedorIdPC($FInicial,$FFinal,$IdArticulo);
			$sql10 = QIntegracionProductosVendidosIdPC();
	
			sqlsrv_query($conn,$sql6);
			sqlsrv_query($conn,$sql7);
			sqlsrv_query($conn,$sql8);
			sqlsrv_query($conn,$sql9);
			sqlsrv_query($conn,$sql10);

			$result2 = sqlsrv_query($conn,'SELECT * FROM CP_QIntegracionProductosVendidosIdPC');
			$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
			$TotalUnidadesVendidasCliente = ($row2["UnidadesVendidasCliente"]-$row2["UnidadesDevueltaCliente"]);
			$TotalUnidadesCompradasProveedor = ($row2["UnidadesCompradasProveedor"]-$row2["UnidadesReclamoProveedor"]); 

			$Venta = intval($TotalUnidadesVendidasCliente);
			$VentaDiaria = VentaDiaria($Venta,$RangoDias);
			$DiasRestantes = DiasRestantes($Existencia,$VentaDiaria);

			if($DiasRestantes<11){

				/* FILTRO 3:
				*	Mantuvo Existencia en rango
				*	se cuenta las apariciones del articulo en la tabla de dias
				*	en cero, la misma debe ser igual la diferencia de dias +1 
				*/
				$CuentaExistencia = CuentaExistencia($connCPharma,$IdArticulo);
				if($CuentaExistencia==($RangoDias+1)){

					/*  FILTRO 4: 
					*	Venta en dia, esta se acumula 
					*	El articulo debe tener venta al menos la mita de dias del rango 
					*/
					$CuentaVenta = CuentaVenta($conn,$IdArticulo,$FInicial,$FFinal);

					if($CuentaVenta>=($RangoDias/2)){

						$ExistenciaDecreciente = ExistenciaDecreciente($connCPharma,$IdArticulo,$FInicial,$FFinal,$RangoDias);

						$CuentaDecreciente = array_pop($ExistenciaDecreciente);

						/*  FILTRO 5: 
						*	Si el articulo decrece su existencia rango 
						*/
						if($CuentaDecreciente==TRUE){
							
							$Precio = CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia);

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
							
						$sqlCPharma = QGuardarProductosCaida($IdArticulo,$CodigoArticulo,$Descripcion,$Precio,$Existencia,$Dia10,$Dia9,$Dia8,$Dia7,$Dia6,$Dia5,$Dia4,$Dia3,$Dia2,$Dia1,$Venta,$DiasRestantes,$FechaCaptura,$user,$date);

						mysqli_query($connCPharma,$sqlCPharma);

						}		
					}
				}
			}
		}
		GuardarCapturaCaida($FechaCaptura,$date);
		
		$sql = QCleanTable('CP_QUnidadesVendidasClienteIdPC');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesDevueltaClienteIdPC');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesCompradasProveedorIdPC');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesReclamoProveedorIdPC');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosVendidosIdPC');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_FiltradoCaida');
		sqlsrv_query($conn,$sql);

		$sqlCC = QValidarCapturaCaida($FechaCaptura);
		$resultCC = mysqli_query($connCPharma,$sqlCC);
		$rowCC = mysqli_fetch_assoc($resultCC);
		$CuentaCaptura = $rowCC["CuentaCaptura"];

		if($CuentaCaptura == 0){
			mysqli_close($connCPharma);
			sqlsrv_close($conn);
			ProuctosEnCaida();
		}
		else{
			mysqli_close($connCPharma);
			sqlsrv_close($conn);
		}
	}
	/*
		TITULO: GuardarCapturaDiaria
		PARAMETROS: [$FechaCaptura] El dia de hoy
					[$date] valor para creacion y actualizacion
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		RETORNO: no aplica
	 */
	function GuardarCapturaCaida($FechaCaptura,$date) {
		$conn = ConectarXampp();
		$sql = QCapturaCaida($FechaCaptura);
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_assoc($result);
		$TotalRegistros = $row["TotalRegistros"];

		$sql1 = QGuardarCapturaCaida($TotalRegistros,$FechaCaptura,$date);
		mysqli_query($conn,$sql1);

		mysqli_close($conn);
	}
	/****************/
	function ValidarEtiquetas() {
		$SedeConnection = MiUbicacion();
	    $conn = ConectarSmartpharma($SedeConnection);
	    $connCPharma = ConectarXampp();

	    $sql = QExistenciaActual();
	    $result = sqlsrv_query($conn,$sql);

	    $FechaCaptura = new DateTime("now");
		$FechaCaptura = $FechaCaptura->format('Y-m-d');
		$date = '';
	    
	    $contador = 0;
	     //Borrar el contador del while
	    while(($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))&&($contador<10)) {
	        $IdArticulo = $row["IdArticulo"];
	        $CodigoInterno = $row["CodigoInterno"];
	        $Descripcion=$row["Descripcion"];	        
	        $Existencia = intval($row["Existencia"]);
	 
	        $sqlCPharma = QEtiquetaArticulo($IdArticulo);
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

	            $sqlCP = QGuardarEtiquetaArticulo($IdArticulo,$CodigoInterno,$Descripcion,$condicion,$clasificacion,$estatus,$user,$date);
	            mysqli_query($connCPharma,$sqlCP);
	        }

	        $contador++;
	    }

	    GuardarCapturaEtiqueta($FechaCaptura,$date);

		$sqlCC = QValidarCapturaEtiqueta($FechaCaptura);
		$resultCC = mysqli_query($connCPharma,$sqlCC);
		$rowCC = mysqli_fetch_assoc($resultCC);
		$CuentaCaptura = $rowCC["CuentaCaptura"];

		if($CuentaCaptura == 0){
			$sqlB = QBorrarDiasEtiqueta($FechaCaptura);
			mysqli_query($connCPharma,$sqlB);			
			mysqli_close($connCPharma);
			sqlsrv_close($conn);
			ValidarEtiquetas();
		}
		else{
			mysqli_close($connCPharma);
			sqlsrv_close($conn);
		}
	}

	function GenererEtiquetas($clasificacion) {
		$SedeConnection = MiUbicacion();
	    $conn = ConectarSmartpharma($SedeConnection);
	    
		$connCPharma = ConectarXampp();	
		$resultado = $connCPharma->query("SELECT * FROM etiquetas 
			WHERE clasificacion = '$clasificacion'");

		$FHoy = date("Y-m-d");
	  	$FAyer = date("Y-m-d",strtotime($FHoy."-1 days"));
	  	$CuentaCard=0;

		while($row = $resultado->fetch_assoc()) {
			$IdArticulo = $row["id_articulo"];

			$sql = QArticulo($IdArticulo);
			sqlsrv_query($conn,$sql);
			$result = sqlsrv_query($conn,$sql);
			$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

			$sql1 = QExistenciaArticulo($IdArticulo,0);
			$result1 = sqlsrv_query($conn,$sql1);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

			$CodigoArticulo = $row["CodigoArticulo"];
			$Descripcion = utf8_encode(addslashes($row["Descripcion"]));
			$IsIVA = $row["ConceptoImpuesto"];		
			$Existencia = $row1["Existencia"];

			$PrecioHoy = CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia);

		    $sqlCC = QDiasCeroEtiqueta($IdArticulo,$FAyer);
			$resultCC = mysqli_query($connCPharma,$sqlCC);
			$rowCC = mysqli_fetch_assoc($resultCC);
			$PrecioAyer = $rowCC["precio"];

			if($PrecioHoy!=$PrecioAyer){

				if($IsIVA == 1){
					$PMVP = $PrecioHoy/1.16;
					$IVA = $PrecioHoy-$PMVP;
				}
				else{
					$PMVP = $PrecioHoy;
					$IVA = 0;
				}

				$Dolarizado = ProductoDolarizado($conn,$IdArticulo);
				$CodigoBarra = CodigoBarra($conn,$IdArticulo);
				
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
				if($CuentaCard == 3){
					echo'<br>';
					$CuentaCard=0;
				}
			}
		}
		mysqli_close($connCPharma);
	    sqlsrv_close($conn);
	}
	/*
		TITULO: GuardarCapturaEtiqueta
		PARAMETROS: [$FechaCaptura] El dia de hoy
					[$date] valor para creacion y actualizacion
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		RETORNO: no aplica
	 */
	function GuardarCapturaEtiqueta($FechaCaptura,$date) {
		$conn = ConectarXampp();
		$sql = QCapturaEtiqueta($FechaCaptura);
		$result = mysqli_query($conn,$sql);
		$row = mysqli_fetch_assoc($result);
		$TotalRegistros = $row["TotalRegistros"];

		$sql1 = QGuardarCapturaEtiqueta($TotalRegistros,$FechaCaptura,$date);
		mysqli_query($conn,$sql1);

		mysqli_close($conn);
	}
/**********************************************************************/
/******************  INICIO DE FUNCIONES GENERALES   ******************/
/**********************************************************************/
	/*
		TITULO: FG_Producto_Dolarizado
		PARAMETROS: [$conn] cadena de conexion
					[$IdArticulo] id del articulo a buscar
		FUNCION: Determina si el producto esta dolarizado 
		RETORNO: Retorna si el producto esta dolarizado o no
 	*/
	function FG_Producto_Dolarizado($conn,$IdArticulo) { 
		$sql = QG_Dolarizados($IdArticulo);
		$params = array();
		$options =  array("Scrollable"=>SQLSRV_CURSOR_KEYSET);
		$result = sqlsrv_query($conn,$sql,$params,$options);
		$row_count = sqlsrv_num_rows($result);
		
		if($row_count == 0) {
			$Dolarizado = 'NO';
		}
		else {
			$Dolarizado = 'SI';
		}
	  	return $Dolarizado;
	}
	/*
		TITULO: FG_Producto_Gravado
		PARAMETROS: [$IsIVA] campo que almacena el ConceptoImpuesto
		FUNCION: determina si un producto es gravado o no
		RETORNO: retorna si el producto es gravado o no
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
 	/*
		TITULO: FG_Tasa_Fecha
		PARAMETROS: [$connCPharma] cadena de conexion con xampp
					[$Fecha] Fecha de la que se buscara la tasa
		FUNCION: Buscar el valor de la tasa
		RETORNO: Valor de la tasa
	 */
	function FG_Tasa_Fecha($connCPharma,$Fecha) {
		$sql = QG_Tasa_Fecha($Fecha);
		$result = mysqli_query($connCPharma,$sql);
		$row = mysqli_fetch_assoc($result);
		$Tasa = $row['tasa'];
		return $Tasa;
	}
	/*
		TITULO: FG_Calculo_Precio
		PARAMETROS: [$conn] Cadena de conexion para la base de datos
					[$IdArticulo] Id del articulo
					[$IsIVA] Si aplica o no
					[$Existencia] existencia actual del producto
		FUNCION: Calcular el precio del articulo
		RETORNO: Precio del articulo
	 */
	function FG_Calculo_Precio($conn,$IdArticulo,$IsIVA,$Existencia) {		
		if($Existencia == 0) {
			$Precio = 0;
		}
		else {
		/*PRECIO TROQUELADO*/
			$sql = QG_Precio_Troquelado($IdArticulo);
			$result = sqlsrv_query($conn,$sql);
			$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
			$PrecioTroquelado = $row["M_PrecioTroquelado"];
			
			if($PrecioTroquelado!=NULL) {
				$Precio = $PrecioTroquelado;
			}		
			else {
			/*PRECIO CALCULADO*/ 
				$sql = QG_Precio_Calculado($IdArticulo);
				$result = sqlsrv_query($conn,$sql);
				$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
				$PrecioBruto = $row["M_PrecioCompraBruto"];

				if($IsIVA == 1) {
					$PrecioCalculado = ($PrecioBruto/Utilidad)*Impuesto;
					$Precio = $PrecioCalculado;
				}
				else { 
					$PrecioCalculado = ($PrecioBruto/Utilidad);
					$Precio = $PrecioCalculado;
				}
			}
		}
		return $Precio;
	}
	/*
		TITULO: FG_Rango_Dias
		PARAMETROS: [$FInicial] Fecha donde inicia el rango
					[$FFinal] Fecha donde termina el rango
		FUNCION: Calcular el rango de diferencia de dias entre el las fechas
		RETORNO: rango de dias
	 */
	function FG_Rango_Dias($FInicial,$FFinal) {
		$FechaI = new DateTime($FInicial);
	    $FechaF = new DateTime($FFinal);
	    $Rango = $FechaI->diff($FechaF);
	    $Rango = $Rango->format('%a');
	    return $Rango;
	}
	/*
		TITULO: FG_Tipo_Producto
		PARAMETROS: [$conn] cadena de conexion
					[$IdArticulo] id del articulo a buscar
		FUNCION: Determina si el producto esta dolarizado 
		RETORNO: Retorna si el producto esta dolarizado o no
 	*/
	function FG_Tipo_Producto($conn,$IdArticulo) { 
		$sql = QG_Tipo_Producto($IdArticulo);
		$params = array();
		$options =  array("Scrollable"=>SQLSRV_CURSOR_KEYSET);
		$result = sqlsrv_query($conn,$sql,$params,$options);
		$row_count = sqlsrv_num_rows($result);

		if($row_count == 0) {
			$Medicina = 'MISCELANEO';
		}
		else {
			$Medicina = 'MEDICINA';
		}
	  	return $Medicina;
	}
	/*
		TITULO: FG_Tipo_Producto
		PARAMETROS: [$conn] cadena de conexion
					[$IdArticulo] id del articulo a buscar
		FUNCION: Determina si el producto esta dolarizado 
		RETORNO: Retorna si el producto esta dolarizado o no
 	*/
	function FG_TotalVenta($conn,$FInicial,$FFinal,$IdArticulo) { 
		$sql = QG_SubTotalVenta_Articulo($FInicial,$FFinal,$IdArticulo);
		$result = sqlsrv_query($conn,$sql);
		$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$SubTotalVenta = $row["SubTotalVenta"];

		$sql = QG_SubTotalDevolucion_Articulo($FInicial,$FFinal,$IdArticulo);
		$result = sqlsrv_query($conn,$sql);
		$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$SubTotalDevolucion = $row["SubTotalDevolucion"];

		$TotalVenta = ($SubTotalVenta-$SubTotalDevolucion);
		return $TotalVenta;
	}
?>