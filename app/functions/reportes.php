<?php
/************************************************************************************/
/************************ REPORTE 1 ACTIVACION DE PROVEEDORES ***********************/
/*
	TITULO: ReporteActivacionProveedores
	PARAMETROS : [$SedeConnection] Siglas de la sede para la conexion
	FUNCION: Armar el reporte de activacion de proveedores
	RETORNO: No aplica
 */
function ReporteActivacionProveedores($SedeConnection){

	$conn = ConectarSmartpharma($SedeConnection);
	$sql = QTablaTemp(1);
	$sql1 = QFRegProveedor();
	$sql2 = QFRegRangoProveedor();

	sqlsrv_query($conn,$sql);
	sqlsrv_query($conn,$sql1);
	$result = sqlsrv_query($conn,$sql2);

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
		      	<th scope="col">Ultimo registro</th>
		      	<th scope="col">Dias sin facturar</th>
		    </tr>
	  	</thead>
	  	<tbody>
	';
	
	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ) {
		echo '<tr>';	
		echo '<td>'.$row['Nombre'].'</td>'; 
		echo '<td align="center">'.($row['FechaRegistro'])->format('Y-m-d').'</td>';
		echo '<td align="center">'.$row['RangoDias'].'</td>';
		echo '</tr>';
  	}
  	echo '
  		</tbody>
	</table>';
	sqlsrv_close( $conn );
}
/************************************************************************************/
/************************ REPORTE 2 HISTORICO DE ARTICULO ***************************/
/*
	TITULO:
	PARAMETROS : [$SedeConnection] Siglas de la sede para la conexion
	FUNCION:
	RETORNO:
 */
?>