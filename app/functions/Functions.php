<?php
//Conectar DB

function conectarDB(){
	$connectionInfo = array(
		"Database"=>nameDB,
		"UID"=>userDB,
		"PWD"=>passDB
	);

	$conn = sqlsrv_connect( serverDB, $connectionInfo);

	if( $conn ) {
	     echo "Conexión establecida.<br />";
	}else{
	     echo "Conexión no se pudo establecer.<br />";
	     die( print_r( sqlsrv_errors(), true));
	}
	return $conn;
}

?>