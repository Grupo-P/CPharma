<?php
/*
	Nombre: conectarDB
	Funcion: Establecer la conexion con la base de datos
 */
function conectarDB(){
	$connectionInfo = array(
		"Database"=>nameDB,
		"UID"=>userDB,
		"PWD"=>passDB
	);
	$conn = sqlsrv_connect( serverDB, $connectionInfo);
	// if( $conn ) {
	//      echo "Conexión establecida.<br />";
	// }else{
	//      echo "Conexión no se pudo establecer.<br />";
	//      die( print_r( sqlsrv_errors(), true));
	// }
	return $conn;
}
/*
	Nombre: QueryDiasProveedores
	Reporte1: Dias de Activacion de Proveedores
	Funcion: Query para la base de datos del Reporte1
 */
function QueryDiasProveedores(){
	$sql = "SELECT ComProveedor.Id, GenPersona.Nombre, CONVERT(VARCHAR,ComFactura.FechaRegistro, 20) AS FechaRegistro 
		FROM ComProveedor
		INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
		INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
		ORDER BY ComProveedor.Id,ComFactura.FechaRegistro DESC";
		return $sql;
}
/*
	Nombre: ReporteDiasProveedores
	Reporte1: Dias de Activacion de Proveedores
	Funcion: Armado del Reporte1
 */
function ReporteDiasProveedores(){
	$conn = conectarDB();
	$tempId=0;
	//$cont=0;
	$FechaActual = date('Y-m-d');

	$sql = QueryDiasProveedores();
	$result = sqlsrv_query($conn,$sql);

	echo '
	<table class="table table-striped table-borderless col-12">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col">Proveedor</th>		      	
		      	<th scope="col">Ultima Facturacion</th>
		      	<th scope="col">Dias sin recibir facturas</th>
		    </tr>
	  	</thead>
	  	<tbody>
	';

	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ) {
		if($tempId == 0){
			echo '<tr>';
			//echo '<th>'.$row['Id'].'</th>';	
			echo '<td>'.$row['Nombre'].'</td>';
			echo '<td>'.($row['FechaRegistro']).'</td>';

			$FechaReg = date('Y-m-d',strtotime($row['FechaRegistro']));
			
		    $fecha1 = new DateTime($FechaReg);
		    $fecha2 = new DateTime($FechaActual);
		    $DifFecha = $fecha1->diff($fecha2);

			echo '<td>'.$DifFecha->format('%a días').'</td>';
			echo '</tr>';
			$tempId = $row['Id'];
			//$cont++;
		}
		if ($tempId != $row['Id']){
			echo '<tr>';
			//echo '<th>'.$row['Id'].'</th>';	
			echo '<td>'.$row['Nombre'].'</td>';
			echo '<td>'.($row['FechaRegistro']).'</td>';

			$FechaReg = date('Y-m-d',strtotime($row['FechaRegistro']));
			
		    $fecha1 = new DateTime($FechaReg);
		    $fecha2 = new DateTime($FechaActual);
		    $DifFecha = $fecha1->diff($fecha2);

			echo '<td>'.$DifFecha->format('%a días').'</td>';
			echo '</tr>';
			$tempId = $row['Id'];
			//$cont++;
		}
  	}
  	echo '
  		</tbody>
	</table>';
	//echo 'la cantidad de registros es: '.$cont;
}


/*
function QueryDiasProveedores(){
	$sql = "SELECT ComProveedor.Id, GenPersona.Nombre, CONVERT(VARCHAR,ComFactura.FechaRegistro, 20) AS FechaRegistro 
		FROM ComProveedor
		INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
		INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
		ORDER BY ComProveedor.Id,ComFactura.FechaRegistro DESC";
		return $sql;
}
 */
?>