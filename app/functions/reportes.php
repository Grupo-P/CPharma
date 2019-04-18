<?php
/************REPORTE 1 ACTIVACION DE PROVEEDORES***********/
//REPORTE ACTIVACION DE PROVEEDORES
function ReporteActivacionProveedores(){
	$conn = ConectarSmartpharma('DB');

	//DATOS DE PROVEEDORES Y DIAS DE ACTIVACION
	$sql = "
	SELECT 
	ComProveedor.Id, 
	GenPersona.Nombre, 
	CONVERT(VARCHAR,ComFactura.FechaRegistro, 20) AS FechaRegistro 
	FROM ComProveedor
	INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
	INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
	ORDER BY ComProveedor.Id,ComFactura.FechaRegistro DESC
	";

	$result = sqlsrv_query($conn,$sql);

	$IdTemp=0;
	$FechaActual = date('Y-m-d');

	echo '
	<div class="input-group md-form form-sm form-1 pl-0">
	  <div class="input-group-prepend">
	    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
	        aria-hidden="true"></i></span>
	  </div>
	  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterFirsTable()">
	</div>
	<br/>
	<table class="table table-striped table-bordered col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col">Proveedor</th>		      	
		      	<th scope="col">Fecha de ultima factura</th>
		      	<th scope="col">Dias sin recibir factura</th>
		    </tr>
	  	</thead>
	  	<tbody>
	';
	//ARMADO DE LA TABLA
	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ) {
		if($IdTemp == 0){
			echo '<tr>';
			echo '<td>'.$row['Nombre'].'</td>';
			echo '<td align="center">'.date('Y-m-d',strtotime($row['FechaRegistro'])).'</td>';

			$FechaReg = date('Y-m-d',strtotime($row['FechaRegistro']));
			
		    $fecha1 = new DateTime($FechaReg);
		    $fecha2 = new DateTime($FechaActual);
		    $DifFecha = $fecha1->diff($fecha2);

			echo '<td align="center">'.$DifFecha->format('%a').'</td>';
			echo '</tr>';
			$IdTemp = $row['Id'];
		}
		if ($IdTemp != $row['Id']){
			echo '<tr>';	
			echo '<td>'.$row['Nombre'].'</td>';
			echo '<td align="center">'.date('Y-m-d',strtotime($row['FechaRegistro'])).'</td>';

			$FechaReg = date('Y-m-d',strtotime($row['FechaRegistro']));
			
		    $fecha1 = new DateTime($FechaReg);
		    $fecha2 = new DateTime($FechaActual);
		    $DifFecha = $fecha1->diff($fecha2);

			echo '<td align="center">'.$DifFecha->format('%a').'</td>';
			echo '</tr>';
			$IdTemp = $row['Id'];
		}
  	}
  	echo '
  		</tbody>
	</table>';
	sqlsrv_close( $conn );
}

?>