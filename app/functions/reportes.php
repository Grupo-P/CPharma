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
	$result1 = sqlsrv_query($conn,$sql1);
	$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

	$IsIVA = $row["ConceptoImpuesto"];
	$Existencia = $row1["Existencia"];

	$Precio = CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia);

	$TasaActual = TasaFecha(date('Y-m-d'));

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

	if($TasaActual!=0){
		echo '<td align="center">'." ".$TasaActual." ".SigVe.'</td>';
		echo '<td align="center">'." ".round(($Precio/$TasaActual),2)." ".SigDolar.'</td>';
	}
	else{
		echo '<td align="center">0.00 '.SigVe.'</td>';
		echo '<td align="center">0.00 '.SigDolar.'</td>';
	}
	echo '
			</tr>
  		</tbody>
	</table>';

	$sql2 = QHistoricoArticulo($IdArticulo);
	$result2 = sqlsrv_query($conn,$sql2);

	echo'
	<table class="table table-striped table-bordered col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		    	<th scope="col">Proveedor</th>
		      	<th scope="col">Fecha de registo</th>
		      	<th scope="col">Cantidad recibida</th>
		      	<th scope="col">Costo bruto</th>		 
		      	<th scope="col">Tasa en historico</th>
				<th scope="col">Costo en divisa</th>
		    </tr>
	  	</thead>
	  	<tbody>
	';

	while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
			echo '<tr>';
			echo '<td>'.$row2["Nombre"].'</td>';	
			echo '<td align="center">'.$row2["FechaRegistro"]->format('Y-m-d').'</td>';
			echo '<td align="center">'.intval($row2['CantidadRecibidaFactura']).'</td>';
			echo '<td align="center">'." ".round($row2["M_PrecioCompraBruto"],2)." ".SigVe.'</td>';

			$FechaR = $row2["FechaRegistro"]->format('Y-m-d');
			$Tasa = TasaFecha($FechaR);
			
			if($Tasa != 0){
				echo '<td align="center">'." ".$Tasa." ".SigVe.'</td>';
				echo '<td align="center">'." ".round(($row2["M_PrecioCompraBruto"]/$Tasa),2)." ".SigDolar.'</td>';
			}
			else{
				echo '<td align="center">0.00 '.SigVe.'</td>';
				echo '<td align="center">0.00 '.SigDolar.'</td>';
			}	
  	}
  	echo '</tr>
  		</tbody>
	</table>';

	sqlsrv_close( $conn );
}
/************************************************************************************/
/************************ REPORTE 3 PRODUCTOS MAS VENDIDOS **************************/
/*
	TITULO: 
	PARAMETROS : 
	FUNCION: 
	RETORNO: 
 */
?>