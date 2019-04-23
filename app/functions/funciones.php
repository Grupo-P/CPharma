<?php
/*
	TITULO: ConectarXampp
	PARAMETROS: No Aplica
	FUNCION: Conexion con servidor de XAMPP
	RETORNO: Conexion a XAMPP
*/
function ConectarXampp(){
	$conexion = mysqli_connect(serverCP,userCP,passCP);
	mysqli_select_db($conexion,nameCP);
    return $conexion;
}
/*
	TITULO: ConectarSmartpharma
	PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
	FUNCION: Conectar el sistema con la sede que se desea
	RETORNO: Conexion a Smartpharma
 */
function ConectarSmartpharma($SedeConnection){
	switch ($SedeConnection) {
		case 'FTN':
			$connectionInfo = array(
				"Database"=>nameFTN,
				"UID"=>userFTN,
				"PWD"=>passFTN
			);
			$conn = sqlsrv_connect( serverFTN, $connectionInfo);
			return $conn;
		break;

		case 'FLL':
			$connectionInfo = array(
				"Database"=>nameFLL,
				"UID"=>userFLL,
				"PWD"=>passFLL
			);
			$conn = sqlsrv_connect( serverFLL, $connectionInfo);
			return $conn;
		break;

		case 'FAU':
			$connectionInfo = array(
				"Database"=>nameFAU,
				"UID"=>userFAU,
				"PWD"=>passFAU
			);
			$conn = sqlsrv_connect( serverFAU, $connectionInfo);
			return $conn;
		break;	

		case 'DB':
			$connectionInfo = array(
				"Database"=>nameDB,
				"UID"=>userDB,
				"PWD"=>passDB
			);
			$conn = sqlsrv_connect( serverDB, $connectionInfo);
			return $conn;
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
function ConsultaEspecialSmartpharma ($sql,$SedeConnection){
	$conn = ConectarSmartpharma($SedeConnection);
	if( $conn ) {					
		$stmt = sqlsrv_query( $conn , $sql );
		if( $stmt === false) {
			die( print_r( sqlsrv_errors(), true) );
		}
		$i = 0;
		$final[$i] = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
		$i++;
		if (  $final[0] != NULL ) {	
			while( $result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
					$final[$i] = $result;
					$i++;
			}
		sqlsrv_free_stmt( $stmt);			
		sqlsrv_close( $conn );
		return $final;	
		} else {
			sqlsrv_free_stmt( $stmt);			
			sqlsrv_close( $conn );
			return NULL;
		}	
	}else{
		die( print_r( sqlsrv_errors(), true));
	}	
}
/*
	TITULO: array_flatten_recursive
	PARAMETROS: [$array] Arreglo para aplicar la recursividad
	FUNCION: Iterar en el arreglo para la busqueda de variables
	RETORNO: Palabra clave buscada
 */
function array_flatten_recursive($array) { 
   if (!is_array($array)) {
	   return false;
   }
   $flat = array();
   $RII = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
   $i = 0;
   foreach ($RII as $key => $value) {
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
function armarJson($sql,$SedeConnection){
	$result = ConsultaEspecialSmartpharma ($sql,$SedeConnection);
    $arrayJson = array_flatten_recursive($result);
    return json_encode($arrayJson);
}
/*
	TITULO: NombreSede
	PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
	FUNCION: Retornar el nombre de la sede con la que se esta conectando
	RETORNO: Nombre de la sede conectada
 */
function NombreSede($SedeConnection){
	switch ($SedeConnection) {
		case 'FTN':
			$sede = SedeFTN;
			return $sede;
		break;

		case 'FLL':
			$sede = SedeFLL;
			return $sede;
		break;

		case 'FAU':
			$sede = SedeFAU;
			return $sede;
		break;	

		case 'DB':
			$sede = SedeDB;
			return $sede;
		break;	
	}
}
/*
	TITULO: CalculoPrecio
	PARAMETROS: [$conn] Cadena de conexion para la base de datos
				[$IdArticulo] Id del articulo
				[$IsIVA] Si aplica o no
	FUNCION: Calcular el precio del articulo
	RETORNO: Precio del articulo
 */
function CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia){

	$Precio = 0;

	if($Existencia == 0){
		$Precio = 0;
	}
	else{		
	/*PRECIO TROQUELADO*/
		$sql0 = QTablaTemp(1);
		sqlsrv_query($conn,$sql0);

		$sql = QLoteArticulo($IdArticulo,0);		
		sqlsrv_query($conn,$sql);
		
		$sql1 = QLote();
		$result1 = sqlsrv_query($conn,$sql1);
		$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
		$PrecioTroquelado = $row1["M_PrecioTroquelado"];
		
		if($PrecioTroquelado!=NULL){
			$Precio = $PrecioTroquelado;
		}
	/*PRECIO CALCULADO*/
		else{
			$sql0 = QTablaTemp(1);
			sqlsrv_query($conn,$sql0);

			$sql2 = QLoteArticulo($IdArticulo,1);		
			sqlsrv_query($conn,$sql2);	
			
			$sql3 = QLote();
			$result3 = sqlsrv_query($conn,$sql3);
			$row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
			$PrecioBruto = $row3["M_PrecioCompraBruto"];

			if($IsIVA == 1){
				$PrecioCalculado = ($PrecioBruto/Utilidad)*Impuesto;
				$Precio = $PrecioCalculado;				
			}
			else{
				$PrecioCalculado = ($PrecioBruto/Utilidad);
				$Precio = $PrecioCalculado;
			}
		}
	}
	return $Precio;
}
/*
	TITULO: TasaFecha
	PARAMETROS: [$Fecha] Fecha de la que se buscara la tasa				
	FUNCION: Buscar el valor de la tasa
	RETORNO: Valor de la tasa
 */
function TasaFecha($Fecha){
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
function RangoDias($FInicial,$FFinal){
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
function VentaDiaria($Venta,$RangoDias){
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
function DiasRestantes($Existencia,$VentaDiaria){
	$DiasRestantes = 0;
	if($VentaDiaria != 0){
		$DiasRestantes = $Existencia/$VentaDiaria;
	}
	$DiasRestantes = round($DiasRestantes,2);
	return $DiasRestantes;
}
?>