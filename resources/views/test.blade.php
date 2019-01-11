<?php
	
	include(app_path().'\functions\config.php');
	include(app_path().'\functions\Functions.php');

	$conn = conectarDB();

	$sql = "SELECT Nombre, Apellido, IdentificacionFiscal, Telefono, DireccionCorta, TipoPersona FROM GenPersona where TipoPersona = '3'";
	$result = sqlsrv_query($conn,$sql);

	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ) {
      echo $row['Nombre'].", ".$row['Apellido'].", ".$row['IdentificacionFiscal'].", ".$row['Telefono'].", ".$row['DireccionCorta'].", ".$row['TipoPersona']."<br />";
}
?>