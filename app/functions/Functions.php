<?php
/*
	Nombre: conectarXampp
	Funcion: Establecer la conexion con la base de datos cpharma
 */
function conectarXampp(){
	$conexion = mysqli_connect(serverCP,userCP,passCP);
	mysqli_select_db($conexion,nameCP);
    return $conexion;
}
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
    return json_encode($arrayJson);
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
	<table class="table table-striped table-bordered col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col">Proveedor</th>		      	
		      	<th scope="col">Ultima facturacion</th>
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
			echo '<td align="center">'.($row['FechaRegistro']).'</td>';

			$FechaReg = date('Y-m-d',strtotime($row['FechaRegistro']));
			
		    $fecha1 = new DateTime($FechaReg);
		    $fecha2 = new DateTime($FechaActual);
		    $DifFecha = $fecha1->diff($fecha2);

			echo '<td align="center">'.$DifFecha->format('%a').'</td>';
			echo '</tr>';
			$tempId = $row['Id'];
		}
		if ($tempId != $row['Id']){
			echo '<tr>';
			//echo '<th>'.$row['Id'].'</th>';	
			echo '<td>'.$row['Nombre'].'</td>';
			echo '<td align="center">'.($row['FechaRegistro']).'</td>';

			$FechaReg = date('Y-m-d',strtotime($row['FechaRegistro']));
			
		    $fecha1 = new DateTime($FechaReg);
		    $fecha2 = new DateTime($FechaActual);
		    $DifFecha = $fecha1->diff($fecha2);

			echo '<td align="center">'.$DifFecha->format('%a').'</td>';
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
		InvArticulo.FinConceptoImptoIdCompra
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
	<table class="table table-striped table-bordered col-12 sortable" id="myTable">
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
	Nombre: QueryTasa
	Funcion: Devuelve el valor de la tasa segun la fecha especificada
 */
function QueryTasa($QFecha){
	$conCP = conectarXampp();
	$consulta = "SELECT tasa FROM dolars where fecha='$QFecha'";
	$resultado = mysqli_query($conCP,$consulta);
	return $resultado;
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
	
	$sql = QueryHistoricoArticulos($IdArticuloQ);
	$result = sqlsrv_query($conn,$sql);

	$sql1="
	IF OBJECT_ID ('TablaTemp', 'U') IS NOT NULL
    	DROP TABLE TablaTemp;
	";

	$sql2="
	SELECT
	InvArticulo.Id, 
	InvArticulo.CodigoArticulo,
	InvArticulo.Descripcion,
	InvArticulo.FinConceptoImptoIdCompra
	INTO TablaTemp
	FROM InvArticulo
	where InvArticulo.Id = '$IdArticuloQ'
	";

	$sql3="
	SELECT
	TablaTemp.Id, 
	TablaTemp.CodigoArticulo,
	TablaTemp.Descripcion,
	TablaTemp.FinConceptoImptoIdCompra,
	SUM (InvLoteAlmacen.Existencia) As Existencia
	FROM TablaTemp
	INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = TablaTemp.Id
	WHERE (InvLoteAlmacen.InvAlmacenId = 1 or InvLoteAlmacen.InvAlmacenId = 2)
	GROUP BY TablaTemp.Id, TablaTemp.CodigoArticulo,TablaTemp.Descripcion,TablaTemp.FinConceptoImptoIdCompra
	";
	
	sqlsrv_query($conn,$sql1);
	sqlsrv_query($conn,$sql2);
	$result1 = sqlsrv_query($conn,$sql3);

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
		      	<th scope="col">Tasa Actual</td>
		      	<th scope="col">Costo Divisa</td>
		    </tr>
	  	</thead>
	  	<tbody>
	  		';
		while( $row1 = sqlsrv_fetch_array( $result1, SQLSRV_FETCH_ASSOC)) {

		echo '<tr>';
		$IdQPT = $row1["Id"];
		$ConcepIVA = $row1["FinConceptoImptoIdCompra"];
		echo '<td align="left">'.$row1["CodigoArticulo"].'</td>';
		echo '<td align="left">'.$row1["Descripcion"].'</td>';
		echo '<td align="center">'.intval($row1["Existencia"]).'</td>';
/*Inicio de calculos de precios*/
	$QPT = "
	IF OBJECT_ID ('TablaTemp', 'U') IS NOT NULL
    	DROP TABLE TablaTemp;
	IF OBJECT_ID ('TablaTemp1', 'U') IS NOT NULL
    	DROP TABLE TablaTemp1;
	";
	$QPT1 = "SELECT
	InvLoteAlmacen.InvLoteId
	into TablaTemp
	FROM InvLoteAlmacen 
	WHERE (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) 
	AND (InvLoteAlmacen.InvArticuloId = '$IdQPT')
	AND (InvLoteAlmacen.Existencia>0)
	";
	$QPT2 = "SELECT
	InvLote.Id,
	invlote.M_PrecioCompraBruto,
	invlote.M_PrecioTroquelado
	into TablaTemp1
	from InvLote
	inner join TablaTemp on TablaTemp.InvLoteId = InvLote.Id
	order by invlote.M_PrecioTroquelado, invlote.M_PrecioCompraBruto desc
	";
	$QPT3 = "SELECT top 1
	TablaTemp1.Id,
	TablaTemp1.M_PrecioCompraBruto,
	TablaTemp1.M_PrecioTroquelado 
	from TablaTemp1
	";

	sqlsrv_query($conn,$QPT);
	sqlsrv_query($conn,$QPT1);
	sqlsrv_query($conn,$QPT2);
	$resultAQPT = sqlsrv_query($conn,$QPT3);
	$FlagPrecio = 0;

	while( $rowQPT = sqlsrv_fetch_array( $resultAQPT, SQLSRV_FETCH_ASSOC)){
		$FlagPrecio = $rowQPT['M_PrecioTroquelado'];
	}

	if($FlagPrecio){
		echo '<td align="center">'.SigVe." ".round($FlagPrecio,2).'</td>';
	}
	else{
		$QPB="
		IF OBJECT_ID ('TablaTemp', 'U') IS NOT NULL
    	DROP TABLE TablaTemp;
		IF OBJECT_ID ('TablaTemp1', 'U') IS NOT NULL
    	DROP TABLE TablaTemp1;
		";
		$QPB1="SELECT *
		into TablaTemp
		from InvLoteAlmacen 
		where(InvLoteAlmacen.InvArticuloId = '$IdQPT')
		and (InvLoteAlmacen.Existencia>0)
		";
		$QPB2="SELECT
		InvLote.Id,
		invlote.M_PrecioCompraBruto,
		invlote.M_PrecioTroquelado
		into TablaTemp1
		from InvLote
		inner join TablaTemp on TablaTemp.InvLoteId = InvLote.Id
		order by invlote.M_PrecioTroquelado, invlote.M_PrecioCompraBruto desc
		";
		$QPB3="SELECT top 1
		TablaTemp1.Id,
		TablaTemp1.M_PrecioCompraBruto,
		TablaTemp1.M_PrecioTroquelado 
		from TablaTemp1
		";

		sqlsrv_query($conn,$QPB);
		sqlsrv_query($conn,$QPB1);
		sqlsrv_query($conn,$QPB2);
		$resultAQPB = sqlsrv_query($conn,$QPB3);
		$FlagPrecio = 0;

		while( $rowQPB = sqlsrv_fetch_array( $resultAQPB, SQLSRV_FETCH_ASSOC)){
			$FlagPrecio = $rowQPB['M_PrecioCompraBruto'];
		}

		if($ConcepIVA==1){
			$FlagPrecio = ($FlagPrecio/Utilidad)*Impuesto;
			echo '<td align="center">'.SigVe." ".round($FlagPrecio,2).'</td>';
		}
		else{
			$FlagPrecio = ($FlagPrecio/Utilidad);
			echo '<td align="center">'.SigVe." ".round($FlagPrecio,2).'</td>';
		}
	}
/*Fin de calculos de precios*/
			$resultTasaG = QueryTasa(date('Y-m-d'));
			$flaG = 0;
			
			while( $rowTasaG = mysqli_fetch_assoc($resultTasaG)) {
				$flaG=$rowTasaG['tasa'];
			}
			if($flaG!=0){
				echo '<td align="center">'.SigDolar." ".$flaG.'</td>';
				echo '<td align="center">'.SigDolar." ".round(($FlagPrecio/$flaG),2).'</td>';
			}
			else{
				echo '<td align="center">'.SigDolar.' 0.00</td>';
				echo '<td align="center">'.SigDolar.' 0.00</td>';
			}
  		}
	  	echo'	
	  	</tbody>	
	</table>

	<table class="table table-striped table-bordered col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		    	<th scope="col">Proveedor</th>
		      	<th scope="col">Fecha de Registo</th>
		      	<th scope="col">Cantidad Recibida</th>
		      	<th scope="col">Costo Bruto</th>		 
		      	<th scope="col">Tasa</th>
				<th scope="col">Costo Divisa</th>
		    </tr>
	  	</thead>
	  	<tbody>
	';
	
	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
			echo '<tr>';
			echo '<td>'.$row["Nombre"].'</td>';	
			echo '<td align="center">'.$row["FechaRegistro"]->format('Y-m-d h:m:s').'</td>';
			echo '<td align="center">'.intval($row['CantidadRecibidaFactura']).'</td>';
			echo '<td align="center">'.SigVe." ".round($row["M_PrecioCompraBruto"],2).'</td>';

			$FechaR = $row["FechaRegistro"]->format('Y-m-d');
			$resultTasa = QueryTasa($FechaR);
			$flag = 0;

			while( $rowTasa = mysqli_fetch_assoc($resultTasa)) {
				echo '<td align="center">'.SigDolar." ".$rowTasa['tasa'].'</td>';
				$flag=$rowTasa['tasa'];
			}
			if($flag!=0){
				echo '<td align="center">'.SigDolar." ".round($row["M_PrecioCompraBruto"]/$flag,2).'</td>';
			}
			else{
				echo '<td align="center">'.SigDolar.' 0.00</td>';
				echo '<td align="center">'.SigDolar.' 0.00</td>';
			}
			echo '</tr>';		
  	}
  	echo '
  		</tbody>
	</table>';
	sqlsrv_close( $conn );
}
/************REPORTE 3 Articulos mas vendidos***********/
/*
	Nombre: QueryArticulosMasVendidos
	Reporte3: Articulos mas vendidos
	Funcion: Query para la base de datos del Reporte3
 */
function TableArticulosMasVendidos($Top,$FInicial,$FFinal){

	$FFinalRango = $FFinal;
	$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

	$sql = "
	IF OBJECT_ID ('TablaTemp', 'U') IS NOT NULL
    	DROP TABLE TablaTemp;
	IF OBJECT_ID ('TablaTemp1', 'U') IS NOT NULL
	    DROP TABLE TablaTemp1;
	IF OBJECT_ID ('TablaTemp2', 'U') IS NOT NULL
	    DROP TABLE TablaTemp2;
	IF OBJECT_ID ('TablaTemp3', 'U') IS NOT NULL
	    DROP TABLE TablaTemp3;
	IF OBJECT_ID ('TablaTemp4', 'U') IS NOT NULL
	    DROP TABLE TablaTemp4;
	IF OBJECT_ID ('TablaTemp5', 'U') IS NOT NULL
	    DROP TABLE TablaTemp5;
	IF OBJECT_ID ('TablaTem6', 'U') IS NOT NULL
	    DROP TABLE TablaTemp6;
	IF OBJECT_ID ('TablaTem6', 'U') IS NULL
	    DROP TABLE TablaTemp6;
	IF OBJECT_ID ('TablaTem7', 'U') IS NULL
	    DROP TABLE TablaTemp7;
	IF OBJECT_ID ('TablaTem8', 'U') IS NULL
	    DROP TABLE TablaTemp8;
	IF OBJECT_ID ('TablaTem9', 'U') IS NULL
	    DROP TABLE TablaTemp9;
	";

	$sql1="
	SELECT
	InvArticulo.Id,
	InvArticulo.CodigoArticulo,
	InvArticulo.Descripcion,
	VenFacturaDetalle.Cantidad
	INTO TablaTemp
	FROM VenFacturaDetalle
	INNER JOIN InvArticulo ON InvArticulo.Id = VenFacturaDetalle.InvArticuloId
	INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
	WHERE
	(VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
	";

	$sql2="
	SELECT TOP $Top
	TablaTemp.Id, 
	TablaTemp.CodigoArticulo, 
	TablaTemp.Descripcion, 
	COUNT(*) AS VecesFacturado, 
	SUM(TablaTemp.Cantidad) AS UnidadesVendidas 
	INTO TablaTemp1
	FROM TablaTemp
	GROUP BY Id,CodigoArticulo, Descripcion
	ORDER BY UnidadesVendidas DESC
	DROP TABLE TablaTemp
	";

	$sql3="
	SELECT
	TablaTemp1.Id, 
	TablaTemp1.CodigoArticulo,
	TablaTemp1.Descripcion,
	TablaTemp1.VecesFacturado,
	UnidadesVendidas,
	InvLoteAlmacen.Existencia
	INTO TablaTemp2
	FROM TablaTemp1
	INNER JOIN InvArticulo ON InvArticulo.CodigoArticulo = TablaTemp1.CodigoArticulo
	INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
	WHERE (InvLoteAlmacen.InvAlmacenId = 1 or InvLoteAlmacen.InvAlmacenId = 2)
	DROP TABLE TablaTemp1
	";

	$sql4="
	SELECT
	TablaTemp2.Id, 
	TablaTemp2.CodigoArticulo, 
	TablaTemp2.Descripcion, 
	TablaTemp2.VecesFacturado, 
	TablaTemp2.UnidadesVendidas, 
	SUM(TablaTemp2.Existencia) AS Existencia,
	DATEDIFF(day,'$FInicial','$FFinalRango') As RangoDias
	INTO TablaTemp3
	FROM TablaTemp2
	GROUP BY Id,CodigoArticulo, Descripcion, VecesFacturado, UnidadesVendidas
	ORDER BY UnidadesVendidas DESC
	DROP TABLE TablaTemp2
	";

	$sql5="
	SELECT
	TablaTemp3.Id, 
	TablaTemp3.CodigoArticulo, 
	TablaTemp3.Descripcion, 
	TablaTemp3.VecesFacturado, 
	TablaTemp3.UnidadesVendidas,
	TablaTemp3.Existencia,
	(TablaTemp3.UnidadesVendidas/TablaTemp3.RangoDias) As VentaDiaria,
	(TablaTemp3.Existencia/(TablaTemp3.UnidadesVendidas/TablaTemp3.RangoDias)) As DiasRestantes
	INTO TablaTemp4
	FROM TablaTemp3
	DROP TABLE TablaTemp3
	";

	$sql6="
	SELECT
	TablaTemp4.Id,
	TablaTemp4.Descripcion, 
	SUM(ComFacturaDetalle.CantidadFacturada) AS CantidadFacturada
	INTO TablaTemp5
	FROM ComFactura
	INNER JOIN ComFacturaDetalle ON ComFacturaDetalle.ComFacturaId = ComFactura.Id
	INNER JOIN TablaTemp4 ON TablaTemp4.Id = ComFacturaDetalle.InvArticuloId
	WHERE
	(ComFactura.FechaDocumento > '$FInicial' AND ComFactura.FechaDocumento < '$FFinal')
	GROUP BY TablaTemp4.Id,TablaTemp4.Descripcion
	ORDER BY TablaTemp4.Id DESC
	";

	$sql7="
	SELECT
	TablaTemp4.Id,
	TablaTemp4.CodigoArticulo, 
	TablaTemp4.Descripcion, 
	TablaTemp4.VecesFacturado, 
	TablaTemp4.UnidadesVendidas,
	TablaTemp4.Existencia,
	TablaTemp4.VentaDiaria,
	TablaTemp4.DiasRestantes,
	TablaTemp5.CantidadFacturada
	INTO TablaTemp6
	from TablaTemp4
	LEFT JOIN TablaTemp5 ON TablaTemp5.Id = TablaTemp4.Id
	DROP TABLE TablaTemp4
	DROP TABLE TablaTemp5
	";

	$sql8="
	SELECT
	TablaTemp6.Id,
	TablaTemp6.CodigoArticulo,
	TablaTemp6.Descripcion,
	COUNT(*) AS VecesDevueltaCliente,
	SUM(VenDevolucionDetalle.Cantidad) AS CantidadDevueltaCliente
	INTO TablaTemp7
	FROM VenDevolucionDetalle
	INNER JOIN TablaTemp6 ON TablaTemp6.Id = VenDevolucionDetalle.InvArticuloId
	INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
	WHERE
	(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
	GROUP BY TablaTemp6.Id,TablaTemp6.CodigoArticulo,TablaTemp6.Descripcion
	ORDER BY TablaTemp6.Descripcion DESC
	";

	$sql9="
	SELECT
	TablaTemp6.Id,
	TablaTemp6.CodigoArticulo,
	TablaTemp6.Descripcion,
	COUNT(*) AS VecesReclamoProveedor,
	SUM(ComReclamoDetalle.Cantidad) AS CantidadReclamoProveedor
	INTO TablaTemp8
	FROM ComReclamoDetalle
	INNER JOIN TablaTemp6 ON TablaTemp6.Id = ComReclamoDetalle.InvArticuloId
	INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
	WHERE
	(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
	GROUP BY TablaTemp6.Id,TablaTemp6.CodigoArticulo,TablaTemp6.Descripcion
	ORDER BY TablaTemp6.Descripcion DESC
	";

	$sql10="
	SELECT 
	TablaTemp6.Id,
	TablaTemp6.CodigoArticulo, 
	TablaTemp6.Descripcion, 
	TablaTemp6.VecesFacturado, 
	TablaTemp6.UnidadesVendidas,
	TablaTemp6.Existencia,
	TablaTemp6.VentaDiaria,
	TablaTemp6.DiasRestantes,
	TablaTemp6.CantidadFacturada,
	TablaTemp7.VecesDevueltaCliente,
	TablaTemp7.CantidadDevueltaCliente,
	TablaTemp8.VecesReclamoProveedor,
	TablaTemp8.CantidadReclamoProveedor
	into TablaTemp9
	from TablaTemp6
	left join TablaTemp7 ON TablaTemp7.Id = TablaTemp6.Id
	left join TablaTemp8 ON TablaTemp8.Id = TablaTemp6.Id
	";

	$sql11="
	SELECT * from TablaTemp9
	";

	$conn = conectarDB();
	sqlsrv_query($conn,$sql);
	sqlsrv_query($conn,$sql1);
	sqlsrv_query($conn,$sql2);
	sqlsrv_query($conn,$sql3);
	sqlsrv_query($conn,$sql4);
	sqlsrv_query($conn,$sql5);
	sqlsrv_query($conn,$sql6);
	sqlsrv_query($conn,$sql7);
	sqlsrv_query($conn,$sql8);
	sqlsrv_query($conn,$sql9);
	sqlsrv_query($conn,$sql10);
	$result = sqlsrv_query($conn,$sql11);

	echo '
	<div class="input-group md-form form-sm form-1 pl-0">
	  <div class="input-group-prepend">
	    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
	        aria-hidden="true"></i></span>
	  </div>
	  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
	</div>
	<br/>
	';

	echo'<h6 align="center">Periodo desde el '.$FInicial.' al '.$FFinalRango.' </h6>';

	echo'
	<table class="table table-striped table-bordered col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		    	<th scope="col">Codigo</th>
		      	<th scope="col">Descripcion</th>
		      	<th align="center" scope="col">Cant. veces Facturado</th>
		      	<th scope="col">Unidades Vendidas</th>
		      	<th scope="col">Existencia</th>
		      	<th scope="col">Venta Diaria</th>
		      	<th scope="col">Dias Restantes</th>
		      	<th scope="col">Cant. comprado Proveedor</th>
		    </tr>
	  	</thead>
	  	<tbody>
	';
	
	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
			echo '<tr>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';	
			echo '<td align="left">'.$row["Descripcion"].'</td>';
			echo '<td align="center">'.intval($row["VecesFacturado"]-$row["VecesDevueltaCliente"]).'</td>';
			echo '<td align="center">'.intval($row["UnidadesVendidas"]-$row["CantidadDevueltaCliente"]).'</td>';
			echo '<td align="center">'.intval($row["Existencia"]).'</td>';
			echo '<td align="center">'.round($row["VentaDiaria"],2).'</td>';
			echo '<td align="center">'.round($row["DiasRestantes"],2).'</td>';
			echo '<td align="center">'.intval($row["CantidadFacturada"]-$row["CantidadReclamoProveedor"]).'</td>';
			echo '</tr>';		
  	}
  	echo '
  		</tbody>
	</table>';

	sqlsrv_close( $conn );
}
/************REPORTE 4 Articulos menos vendidos***********/
/*
	Nombre: QueryArticulosMenosVendidos
	Reporte3: Articulos mas vendidos
	Funcion: Query para la base de datos del Reporte3
 */
function TableArticulosMenosVendidos($Top,$FInicial,$FFinal){

	$FFinalRango = $FFinal;
	$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

	$sql = "
	IF OBJECT_ID ('TablaTemp', 'U') IS NOT NULL
    	DROP TABLE TablaTemp;
	IF OBJECT_ID ('TablaTemp1', 'U') IS NOT NULL
	    DROP TABLE TablaTemp1;
	IF OBJECT_ID ('TablaTemp2', 'U') IS NOT NULL
	    DROP TABLE TablaTemp2;
	IF OBJECT_ID ('TablaTemp3', 'U') IS NOT NULL
	    DROP TABLE TablaTemp3;
	IF OBJECT_ID ('TablaTemp4', 'U') IS NOT NULL
	    DROP TABLE TablaTemp4;
	IF OBJECT_ID ('TablaTemp5', 'U') IS NOT NULL
	    DROP TABLE TablaTemp5;
	IF OBJECT_ID ('TablaTem6', 'U') IS NOT NULL
	    DROP TABLE TablaTemp6;
	IF OBJECT_ID ('TablaTem6', 'U') IS NULL
	    DROP TABLE TablaTemp6;
	IF OBJECT_ID ('TablaTem7', 'U') IS NULL
	    DROP TABLE TablaTemp7;
	IF OBJECT_ID ('TablaTem8', 'U') IS NULL
	    DROP TABLE TablaTemp8;
	IF OBJECT_ID ('TablaTem9', 'U') IS NULL
	    DROP TABLE TablaTemp9;
	";

	$sql1="
	SELECT
	InvArticulo.Id,
	InvArticulo.CodigoArticulo,
	InvArticulo.Descripcion,
	VenFacturaDetalle.Cantidad
	INTO TablaTemp
	FROM VenFacturaDetalle
	INNER JOIN InvArticulo ON InvArticulo.Id = VenFacturaDetalle.InvArticuloId
	INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
	WHERE
	(VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
	";

	$sql2="
	SELECT TOP $Top
	TablaTemp.Id, 
	TablaTemp.CodigoArticulo, 
	TablaTemp.Descripcion, 
	COUNT(*) AS VecesFacturado, 
	SUM(TablaTemp.Cantidad) AS UnidadesVendidas 
	INTO TablaTemp1
	FROM TablaTemp
	GROUP BY Id,CodigoArticulo, Descripcion
	ORDER BY UnidadesVendidas ASC
	DROP TABLE TablaTemp
	";

	$sql3="
	SELECT
	TablaTemp1.Id, 
	TablaTemp1.CodigoArticulo,
	TablaTemp1.Descripcion,
	TablaTemp1.VecesFacturado,
	UnidadesVendidas,
	InvLoteAlmacen.Existencia
	INTO TablaTemp2
	FROM TablaTemp1
	INNER JOIN InvArticulo ON InvArticulo.CodigoArticulo = TablaTemp1.CodigoArticulo
	INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
	WHERE (InvLoteAlmacen.InvAlmacenId = 1 or InvLoteAlmacen.InvAlmacenId = 2)
	DROP TABLE TablaTemp1
	";

	$sql4="
	SELECT
	TablaTemp2.Id, 
	TablaTemp2.CodigoArticulo, 
	TablaTemp2.Descripcion, 
	TablaTemp2.VecesFacturado, 
	TablaTemp2.UnidadesVendidas, 
	SUM(TablaTemp2.Existencia) AS Existencia,
	DATEDIFF(day,'$FInicial','$FFinalRango') As RangoDias
	INTO TablaTemp3
	FROM TablaTemp2
	GROUP BY Id,CodigoArticulo, Descripcion, VecesFacturado, UnidadesVendidas
	ORDER BY UnidadesVendidas DESC
	DROP TABLE TablaTemp2
	";

	$sql5="
	SELECT
	TablaTemp3.Id, 
	TablaTemp3.CodigoArticulo, 
	TablaTemp3.Descripcion, 
	TablaTemp3.VecesFacturado, 
	TablaTemp3.UnidadesVendidas,
	TablaTemp3.Existencia,
	(TablaTemp3.UnidadesVendidas/TablaTemp3.RangoDias) As VentaDiaria,
	(TablaTemp3.Existencia/(TablaTemp3.UnidadesVendidas/TablaTemp3.RangoDias)) As DiasRestantes
	INTO TablaTemp4
	FROM TablaTemp3
	DROP TABLE TablaTemp3
	";

	$sql6="
	SELECT
	TablaTemp4.Id,
	TablaTemp4.Descripcion, 
	SUM(ComFacturaDetalle.CantidadFacturada) AS CantidadFacturada
	INTO TablaTemp5
	FROM ComFactura
	INNER JOIN ComFacturaDetalle ON ComFacturaDetalle.ComFacturaId = ComFactura.Id
	INNER JOIN TablaTemp4 ON TablaTemp4.Id = ComFacturaDetalle.InvArticuloId
	WHERE
	(ComFactura.FechaDocumento > '$FInicial' AND ComFactura.FechaDocumento < '$FFinal')
	GROUP BY TablaTemp4.Id,TablaTemp4.Descripcion
	ORDER BY TablaTemp4.Id DESC
	";

	$sql7="
	SELECT
	TablaTemp4.Id,
	TablaTemp4.CodigoArticulo, 
	TablaTemp4.Descripcion, 
	TablaTemp4.VecesFacturado, 
	TablaTemp4.UnidadesVendidas,
	TablaTemp4.Existencia,
	TablaTemp4.VentaDiaria,
	TablaTemp4.DiasRestantes,
	TablaTemp5.CantidadFacturada
	INTO TablaTemp6
	from TablaTemp4
	LEFT JOIN TablaTemp5 ON TablaTemp5.Id = TablaTemp4.Id
	DROP TABLE TablaTemp4
	DROP TABLE TablaTemp5
	";

	$sql8="
	SELECT
	TablaTemp6.Id,
	TablaTemp6.CodigoArticulo,
	TablaTemp6.Descripcion,
	COUNT(*) AS VecesDevueltaCliente,
	SUM(VenDevolucionDetalle.Cantidad) AS CantidadDevueltaCliente
	INTO TablaTemp7
	FROM VenDevolucionDetalle
	INNER JOIN TablaTemp6 ON TablaTemp6.Id = VenDevolucionDetalle.InvArticuloId
	INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
	WHERE
	(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
	GROUP BY TablaTemp6.Id,TablaTemp6.CodigoArticulo,TablaTemp6.Descripcion
	ORDER BY TablaTemp6.Descripcion DESC
	";

	$sql9="
	SELECT
	TablaTemp6.Id,
	TablaTemp6.CodigoArticulo,
	TablaTemp6.Descripcion,
	COUNT(*) AS VecesReclamoProveedor,
	SUM(ComReclamoDetalle.Cantidad) AS CantidadReclamoProveedor
	INTO TablaTemp8
	FROM ComReclamoDetalle
	INNER JOIN TablaTemp6 ON TablaTemp6.Id = ComReclamoDetalle.InvArticuloId
	INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
	WHERE
	(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
	GROUP BY TablaTemp6.Id,TablaTemp6.CodigoArticulo,TablaTemp6.Descripcion
	ORDER BY TablaTemp6.Descripcion DESC
	";

	$sql10="
	SELECT 
	TablaTemp6.Id,
	TablaTemp6.CodigoArticulo, 
	TablaTemp6.Descripcion, 
	TablaTemp6.VecesFacturado, 
	TablaTemp6.UnidadesVendidas,
	TablaTemp6.Existencia,
	TablaTemp6.VentaDiaria,
	TablaTemp6.DiasRestantes,
	TablaTemp6.CantidadFacturada,
	TablaTemp7.VecesDevueltaCliente,
	TablaTemp7.CantidadDevueltaCliente,
	TablaTemp8.VecesReclamoProveedor,
	TablaTemp8.CantidadReclamoProveedor
	into TablaTemp9
	from TablaTemp6
	left join TablaTemp7 ON TablaTemp7.Id = TablaTemp6.Id
	left join TablaTemp8 ON TablaTemp8.Id = TablaTemp6.Id
	";

	$sql11="
	SELECT * from TablaTemp9
	";

	$conn = conectarDB();
	sqlsrv_query($conn,$sql);
	sqlsrv_query($conn,$sql1);
	sqlsrv_query($conn,$sql2);
	sqlsrv_query($conn,$sql3);
	sqlsrv_query($conn,$sql4);
	sqlsrv_query($conn,$sql5);
	sqlsrv_query($conn,$sql6);
	sqlsrv_query($conn,$sql7);
	sqlsrv_query($conn,$sql8);
	sqlsrv_query($conn,$sql9);
	sqlsrv_query($conn,$sql10);
	$result = sqlsrv_query($conn,$sql11);

	echo '
	<div class="input-group md-form form-sm form-1 pl-0">
	  <div class="input-group-prepend">
	    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
	        aria-hidden="true"></i></span>
	  </div>
	  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
	</div>
	<br/>
	';

	echo'<h6 align="center">Periodo desde el '.$FInicial.' al '.$FFinalRango.' </h6>';

	echo'
	<table class="table table-striped table-bordered col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		    	<th scope="col">Codigo</th>
		      	<th scope="col">Descripcion</th>
		      	<th align="center" scope="col">Cant. veces Facturado</th>
		      	<th scope="col">Unidades Vendidas</th>
		      	<th scope="col">Existencia</th>
		      	<th scope="col">Venta Diaria</th>
		      	<th scope="col">Dias Restantes</th>
		      	<th scope="col">Cant. comprado Proveedor</th>
		    </tr>
	  	</thead>
	  	<tbody>
	';
	
	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
			echo '<tr>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';	
			echo '<td align="left">'.$row["Descripcion"].'</td>';
			echo '<td align="center">'.intval($row["VecesFacturado"]-$row["VecesDevueltaCliente"]).'</td>';
			echo '<td align="center">'.intval($row["UnidadesVendidas"]-$row["CantidadDevueltaCliente"]).'</td>';
			echo '<td align="center">'.intval($row["Existencia"]).'</td>';
			echo '<td align="center">'.round($row["VentaDiaria"],2).'</td>';
			echo '<td align="center">'.round($row["DiasRestantes"],2).'</td>';
			echo '<td align="center">'.intval($row["CantidadFacturada"]-$row["CantidadReclamoProveedor"]).'</td>';
			echo '</tr>';		
  	}
  	echo '
  		</tbody>
	</table>';

	sqlsrv_close( $conn );
}
?>