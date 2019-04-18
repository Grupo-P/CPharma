<?php
//CONEXION CON XAMPP
function ConectarXampp(){
	$conexion = mysqli_connect(serverCP,userCP,passCP);
	mysqli_select_db($conexion,nameCP);
    return $conexion;
}
//CONEXION CON SMARPHARMA SEGUN LA SEDE
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
//CONSULTA ESPECIAL A SMARTPHARMA
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
//ARREGLO RECURSIVO: CONSULTA ESPECIAL SMARTPHARMA
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
//ARMADO DE JSON DE CONSULTA ESPECIAL
function armarJson($sql,$SedeConnection){
	$result = ConsultaEspecialSmartpharma ($sql,$SedeConnection);
    $arrayJson = array_flatten_recursive($result);
    return json_encode($arrayJson);
}
?>