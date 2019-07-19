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
	function LastRestoreDB($nameDataBase){
		$SedeConnection = MiUbicacion();
		$conn = ConectarSmartpharma($SedeConnection);
		$sql = QLastRestoreDB($nameDataBase);
		$result = sqlsrv_query($conn,$sql);
		$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$FechaRestauracion = $row["FechaRestauracion"]->format("Y-m-d h:i:s");
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
?>