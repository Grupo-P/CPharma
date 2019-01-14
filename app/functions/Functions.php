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

function GenPersona(){
	$conn = conectarDB();

	$sql = "SELECT Id, Nombre FROM GenPersona where TipoPersona = '2' ORDER BY 'Id' ASC";
	$result = sqlsrv_query($conn,$sql);

	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ) {
      echo $row['Id'].", ".$row['Nombre']."<br />";
  	}
}

function ComProveedor(){
	$conn = conectarDB();

	$sql = "SELECT Id, GenPersonaId, CodigoProveedor FROM ComProveedor ORDER BY 'Id' ASC";
	$result = sqlsrv_query($conn,$sql);

	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ) {
      echo $row['Id'].", ".$row['GenPersonaId'].", ".$row['CodigoProveedor']."<br />";
  	}
}

function ComFactura(){
	$conn = conectarDB();

	$sql = "SELECT Id, ComProveedorId, CONVERT(VARCHAR, FechaRegistro, 20) AS FechaRegistro FROM ComFactura ORDER BY 'Id' ASC";
	$result = sqlsrv_query($conn,$sql);

	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ) {
      echo $row['Id'].", ".$row['ComProveedorId'].", ".$row['FechaRegistro']."<br />";
  	}
}

?>