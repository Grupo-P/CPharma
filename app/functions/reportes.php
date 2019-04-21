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
	TITULO: ReporteHistoricoArticulo
	PARAMETROS : [$SedeConnection] Siglas de la sede para la conexion
				 [$IdArticuloQ] Id del articulo a buscar
	FUNCION: Armar una tabla de historico de compra del articulo
	RETORNO: No aplica
 */
function ReporteHistoricoArticulo($SedeConnection,$IdArticulo){
	
	$conn = ConectarSmartpharma($SedeConnection);

	$sql = QArticulo($IdArticulo);
	sqlsrv_query($conn,$sql);
	$result = sqlsrv_query($conn,$sql);
	$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

	$sql1 = QExistenciaArticulo($IdArticulo,0);
	sqlsrv_query($conn,$sql);
	$result1 = sqlsrv_query($conn,$sql1);
	$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
	$Existencia = $row1["Existencia"];

	$IsIVA = $row["ConceptoImpuesto"];
	$Precio = CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia);

	echo '
	<div class="input-group md-form form-sm form-1 pl-0">
	  <div class="input-group-prepend">
	    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
	        aria-hidden="true"></i></span>
	  </div>
	  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
	</div>
	<br/>

	<table class="table table-striped table-bordered col-12 sortable">
		<thead class="thead-dark">
		    <tr>
		    	<th scope="col">Codigo</th>
		      	<th scope="col">Descripcion</td>
		      	<th scope="col">Existencia</td>
		      	<th scope="col">Precio</td>
		      	<th scope="col">Tasa actual</td>
		      	<th scope="col">Costo en divisa</td>
		    </tr>
	  	</thead>
	  	<tbody>
  	';
	echo '<tr>';
	echo '<td>'.$row["CodigoArticulo"].'</td>';	
	echo '<td>'.$row["Descripcion"].'</td>';
	echo '<td align="center">'.intval($Existencia).'</td>';
	echo '<td align="center">'." ".round($Precio,2)." ".SigVe.'</td>';
	echo '</tr>';
	echo '
  		</tbody>
	</table>';
	sqlsrv_close( $conn );
}
?>