<?php
//CONEXION CON XAMPP
function conectarXampp(){
	$conexion = mysqli_connect(serverCP,userCP,passCP);
	mysqli_select_db($conexion,nameCP);
    return $conexion;
}
//CONEXION CON SMARPHARMA SEGUN LA SEDE
function conectarDB($SedeConnection){
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
	}




	$connectionInfo = array(
		"Database"=>nameDB,
		"UID"=>userDB,
		"PWD"=>passDB
	);
	$conn = sqlsrv_connect( serverDB, $connectionInfo);
	return $conn;
}
?>