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
	return $conn;
}
/************REPORTE 1 Activacion de proveedores***********/
/*
	Nombre: QueryDiasProveedores
	Reporte1: Dias de Activacion de Proveedores
	Funcion: Query para la base de datos del Reporte1
 */
function QueryDiasProveedores(){
	$sql = "SELECT 
		ComProveedor.Id, 
		GenPersona.Nombre, 
		CONVERT(VARCHAR,ComFactura.FechaRegistro, 20) AS FechaRegistro 
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
	$FechaActual = date('Y-m-d');

	$sql = QueryDiasProveedores();
	$result = sqlsrv_query($conn,$sql);

	echo '
	<div class="input-group md-form form-sm form-1 pl-0">
	  <div class="input-group-prepend">
	    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
	        aria-hidden="true"></i></span>
	  </div>
	  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterFirsTable()">
	</div>
	<br/>
	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
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

			echo '<td>'.$DifFecha->format('%a').'</td>';
			echo '</tr>';
			$tempId = $row['Id'];
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

			echo '<td>'.$DifFecha->format('%a').'</td>';
			echo '</tr>';
			$tempId = $row['Id'];
		}
  	}
  	echo '
  		</tbody>
	</table>';
	sqlsrv_close( $conn );
}
/**************REPORTE 2 Historico de Articulos***********/
/*
	Nombre: QueryArticulosDesc
	Funcion: Query que devuelve todos los articulos de la base de datos
 */
function QueryArticulosDesc(){
	$sql = "SELECT
		InvArticulo.Descripcion, 
		InvArticulo.Id 
		FROM InvArticulo";
	return $sql;
}
/*
	Nombre: QueryArticulos
	Funcion: Devuelve el articulo segun la descripcion
 */
function QueryArticulos($descrip){
	$sql = "SELECT  
		InvArticulo.Id 
		FROM InvArticulo
		WHERE InvArticulo.Descripcion 
		LIKE '%$descrip%'";
	return $sql;
}
/*
	Nombre: TableArticulos
	Funcion: Arma los articulos en tabla
 */
function TableArticulos(){
	$conn = conectarDB();
	
	$sql = QueryArticulos();
	$result = sqlsrv_query($conn,$sql);
	
	echo '
	<div class="input-group md-form form-sm form-1 pl-0">
	  <div class="input-group-prepend">
	    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
	        aria-hidden="true"></i></span>
	  </div>
	  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
	</div>
	<br/>
	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col">CodigoArticulo</th>
		      	<th scope="col">Descripcion</th>
		      	<th scope="col">Accion</th>
		    </tr>
	  	</thead>
	  	<tbody>
	';
	$cont = 0;

	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
	$cont++;
			echo '<tr>';
			// echo '<td>'.$row["Id"].'</td>';
			echo '<td>'.$row["CodigoArticulo"].'</td>';
			echo '<td>'.($row['Descripcion']).'</td>';
			echo' <td>
			<form action="" method="GET" style="display: inline;">   
		        <button type="submit" name="Historico" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-eye">&nbsp;Ver Historico</i>
		        </button>
		        <input id="IdArticulo" name="IdArticulo" type="hidden" value="'.$row["Id"].'">
		     	<input id="CodigoArticulo" name="CodigoArticulo" type="hidden" value="'.$row["CodigoArticulo"].'">
		     	<input id="DescripcionArticulo" name="DescripcionArticulo" type="hidden" value="'.$row["Descripcion"].'">   
		    </form>
      		</td>';
			echo '</tr>';

			/*Limite para acelerar el test quitar al final*/
			if($cont == 15){
				break;
			}
  	}
  	echo '
  		</tbody>
	</table>';
	sqlsrv_close( $conn );
}
/*
	Nombre: QueryHistoricoArticulos
	Funcion: Query que devuelve el historico del articulo seleccionado
 */
function QueryHistoricoArticulos($IdArticuloQ){

	$sql = "SELECT
		GenPersona.Nombre,
		ComFactura.FechaRegistro,
		ComFacturaDetalle.M_PrecioCompraBruto,
		ComFacturaDetalle.CantidadRecibidaFactura
		FROM InvArticulo
		inner join ComFacturaDetalle ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
		inner join ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
		inner join ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
		inner join GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
		WHERE InvArticulo.Id = '$IdArticuloQ'
		ORDER BY ComFactura.FechaRegistro DESC";
	return $sql;
}
/*
	Nombre: TableHistoricoArticulos
	Funcion: Arma el historico del articulo selecionado
 */
function TableHistoricoArticulos($IdArticuloQ,$DescripcionArticuloQ){
	$conn = conectarDB();
	$CodigoArticuloQ;
	
	$sql1 = "SELECT InvArticulo.CodigoArticulo 
			FROM InvArticulo
			WHERE InvArticulo.Id = '$IdArticuloQ'";
	$result1 = sqlsrv_query($conn,$sql1);

	$sql = QueryHistoricoArticulos($IdArticuloQ);
	$result = sqlsrv_query($conn,$sql);

	while( $row1 = sqlsrv_fetch_array( $result1, SQLSRV_FETCH_ASSOC)) {
		$CodigoArticuloQ = $row1["CodigoArticulo"];
	}
	
	echo '
	<div class="input-group md-form form-sm form-1 pl-0">
	  <div class="input-group-prepend">
	    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
	        aria-hidden="true"></i></span>
	  </div>
	  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
	</div>
	<br/>

	<table class="table table-striped table-borderless col-12 sortable">
		<thead class="thead-dark">
		    <tr>
		    	<th scope="col">Codigo</th>
		      	<th scope="col">Descripcion</td>
		    </tr>
	  	</thead>
	  	<tbody>
	  		<tr>
		    	<td scope="col">'.$CodigoArticuloQ.'</th>
		      	<td scope="col">'.$DescripcionArticuloQ.'</td>
		    </tr>	
	  	</tbody>	
	</table>

	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		    	<th scope="col">Proveedor</th>
		      	<th scope="col">Fecha de Registo</th>
		      	<th scope="col">Costo Bruto</th>
		      	<th scope="col">Cantidad Recibida</th>
		    </tr>
	  	</thead>
	  	<tbody>
	';
	
	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
			echo '<tr>';
			echo '<td>'.$row["Nombre"].'</td>';	
			echo '<td>'.$row["FechaRegistro"]->format('Y-m-d h:m:s').'</td>';
			echo '<td>'.$row["M_PrecioCompraBruto"].'</td>';
			echo '<td>'.($row['CantidadRecibidaFactura']).'</td>';
			echo '</tr>';		
  	}
  	echo '
  		</tbody>
	</table>';
	sqlsrv_close( $conn );
}
/*
	Nombre: ConsultaDB
	Funcion: Consulta en la base de datos y regresa un Json
 */
function ConsultaDB ( $sql ){
	 $conn = conectarDB(); 
	if( $conn ) {					
		$stmt = sqlsrv_query( $conn , $sql );
		if( $stmt === false) {
			die( print_r( sqlsrv_errors(), true) );
		}
		$i = 0;
		$final[$i] = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC);
		$i++;
		if (  $final[0] != NULL ) {	
			while( $result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
					$final[$i] = $result;
					$i++;
			}
		sqlsrv_free_stmt( $stmt);			
		sqlsrv_close( $conn );
		return $final;	
		} else {
			sqlsrv_free_stmt( $stmt);			
			sqlsrv_close( $conn );
			return NULL;
		}	
	}else{
		die( print_r( sqlsrv_errors(), true));
	}	
}
/*
	Nombre: array_flatten_recursive
	Funcion: Decodifica el arreglo a UTF8
 */
function array_flatten_recursive($array) { 
   if (!is_array($array)) {
	   return false;
   }
   $flat = array();
   $RII = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
   $i = 0;
   foreach ($RII as $key => $value) {
	   $flat[$i] = utf8_encode($value);
	   $i++;
   }
   return $flat;
}
/*
	Nombre: armarJson
	Funcion: recibe un sql y regrsa el array JsonAramado
 */
function armarJson($sql){
	$result = ConsultaDB ($sql);
    $arrayJson = array_flatten_recursive($result);
    print_r($arrayJson);
    return json_encode($arrayJson);
}
?>