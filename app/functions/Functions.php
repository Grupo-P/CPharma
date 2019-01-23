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
/*
	Nombre: ConsultaDB
	Funcion: Consultar y armar el array de datos
*/
/*
function ConsultaDB ( $sql ) {
	$iso_sql = utf8_decode($sql);
	$conn = conectarDB();
				 
	if( $conn ) {					
		$stmt = sqlsrv_query( $conn, $iso_sql);
			if( $stmt === false) {
				die( print_r( sqlsrv_errors(), true) );
			}
			$i = 0;
			$final[$i] = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
			$i++;
				if (  $final[0] != NULL ) {	
					while( $result = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
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
*/
/************REPORTE 1 Activacion de proveedores***********/
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
function QueryHistoricoArticulos(){
	$sql = "SELECT InvArticulo.Id,InvArticulo.CodigoArticulo, InvArticulo.Descripcion 
		FROM InvArticulo";
		return $sql;
}



function ReporteHistoricoArticulos(){
	$conn = conectarDB();
	
	$sql = QueryHistoricoArticulos();
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
		    	<th scope="col">Id</th>
		      	<th scope="col">CodigoArticulo</th>
		      	<th scope="col">Descripcion</th>
		      	<th scope="col">Accion</th>
		    </tr>
	  	</thead>
	  	<tbody>
	';
	$cont = 0;
	$FechaAntes = date('Y-m-d h:m:s');

	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
	$cont++;
			echo '<tr>';
			echo '<td>'.$row["Id"].'</td>';	
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

	$FechaDespues = date('Y-m-d h:m:s');
	echo '<br/>cantidad de registros: '.$cont;
	echo '<br/>Fecha Antes: '.$FechaAntes;
	echo '<br/>Fecha Despues: '.$FechaDespues;

	$fecha1 = new DateTime($FechaDespues);
    $fecha2 = new DateTime($FechaAntes);
    $DifFecha = $fecha1->diff($fecha2);
	echo '<br/>Tardo: '.$DifFecha->format("%H:%I:%S");
}

/*
select * from InvArticulo where InvArticulo.Id = 7231;

SELECT ComFacturaDetalle.ComFacturaId,ComFacturaDetalle.CantidadRecibidaFactura,ComFacturaDetalle.M_PrecioCompraBruto,ComFacturaDetalle.InvArticuloId  from ComFacturaDetalle where ComFacturaDetalle.InvArticuloId = '7231';

select ComFactura.Id,ComFactura.ComProveedorId,ComFactura.FechaRegistro  from ComFactura where ComFactura.Id = 11701;

select ComProveedor.Id,ComProveedor.GenPersonaId from ComProveedor where ComProveedor.Id = 2;

select GenPersona.Id,GenPersona.Nombre from GenPersona where GenPersona.Id = 46429;



///////// Query Final del reporte 2 ///////
select 
InvArticulo.Id,
InvArticulo.CodigoArticulo,
InvArticulo.Descripcion,
ComFacturaDetalle.ComFacturaId,
ComFacturaDetalle.CantidadRecibidaFactura,
ComFacturaDetalle.M_PrecioCompraBruto,
ComFacturaDetalle.InvArticuloId,
ComFactura.Id,
ComFactura.ComProveedorId,
ComFactura.FechaRegistro,
ComProveedor.Id,
ComProveedor.GenPersonaId,
GenPersona.Id,
GenPersona.Nombre
from InvArticulo
inner join ComFacturaDetalle on InvArticulo.Id = ComFacturaDetalle.InvArticuloId
inner join ComFactura on ComFactura.Id = ComFacturaDetalle.ComFacturaId
inner join ComProveedor on ComProveedor.Id = ComFactura.ComProveedorId
inner join GenPersona on GenPersona.Id = ComProveedor.GenPersonaId
where InvArticulo.Id = 7231
order by InvArticulo.Id asc;

 */
?>