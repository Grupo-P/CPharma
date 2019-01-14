<?php
	
	include(app_path().'\functions\config.php');
	include(app_path().'\functions\Functions.php');

	$conn = conectarDB();

	$sql = "SELECT Nombre, Apellido, IdentificacionFiscal, Telefono, DireccionCorta, TipoPersona FROM GenPersona where TipoPersona = '1'";
	$result = sqlsrv_query($conn,$sql);

	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ) {
      echo $row['Nombre'].", ".$row['Apellido'].", ".$row['IdentificacionFiscal'].", ".$row['Telefono'].", ".$row['DireccionCorta'].", ".$row['TipoPersona']."<br />";


/*
      $sql = "SELECT CodigoArticulo, Descripcion, M_CostoUnitarioBruto FROM InvArticulo where Auditoria_FechaActualizacion > '2018-01-01 00:00:00.0000000' ORDER BY M_CostoUnitarioBruto ASC";
	$result = sqlsrv_query($conn,$sql);

	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ) {
      echo $row['CodigoArticulo'].", ".$row['Descripcion'].", ".$row['M_CostoUnitarioBruto']."<br />";*/
}
?>