<?php
/*OPTIMIZADO*/
//CONEXION CON XAMPP
function conectarXampp(){
	$conexion = mysqli_connect(serverCP,userCP,passCP);
	mysqli_select_db($conexion,nameCP);
    return $conexion;
}
/*OPTIMIZADO*/
//CONEXION CON SMARPHARMA
function conectarDB(){
	$connectionInfo = array(
		"Database"=>nameDB,
		"UID"=>userDB,
		"PWD"=>passDB
	);
	$conn = sqlsrv_connect( serverDB, $connectionInfo);
	return $conn;
}
/*OPTIMIZADO*/
//CONSULTA ESPECIAL A SMARTPHARMA
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
/*OPTIMIZADO*/
//ARREGLO RECURSIVO: CONSULTA ESPECIAL SMARTPHARMA
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
/*OPTIMIZADO*/
//ARMADO DE JSON DE CONSULTA ESPECIAL
function armarJson($sql){
	$result = ConsultaDB ($sql);
    $arrayJson = array_flatten_recursive($result);
    return json_encode($arrayJson);
}
/************REPORTE 1 Activacion de proveedores***********/
/*OPTIMIZADO*/
function ReporteActivacionProveedores(){
	$conn = conectarDB();

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
/**************REPORTE 2 Historico de Articulos***********/
/*OPTIMIZADO*/
//CONSULTA DE ARTICULOS DESCRIPCION Y ID
function QueryArticulosDescId(){
	$sql = "
	SELECT
	InvArticulo.Descripcion, 
	InvArticulo.Id 
	FROM InvArticulo";
	return $sql;
}
/*OPTIMIZADO*/
//CONSUTLA DE TASA SEGUN FECHA
function QueryTasa($QFecha){
	$conCP = conectarXampp();
	$consulta = "SELECT tasa FROM dolars where fecha='$QFecha'";
	$resultado = mysqli_query($conCP,$consulta);
	return $resultado;
}
/*OPTIMIZADO*/
//REPORTE HISTORICO DE ARTICULO
function ReporteHistoricoArticulo($IdArticuloQ){
	$conn = conectarDB();

	$sql="
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
	IF OBJECT_ID ('TablaTemp6', 'U') IS NOT NULL
	    DROP TABLE TablaTemp6;
	IF OBJECT_ID ('TablaTemp7', 'U') IS NOT NULL
		DROP TABLE TablaTemp7;
	IF OBJECT_ID ('TablaTemp8', 'U') IS NOT NULL
		DROP TABLE TablaTemp8;
	";

	//DATOS DEL PRODUCTO
	$sql1="
	SELECT
	InvArticulo.Id, 
	InvArticulo.CodigoArticulo,
	InvArticulo.Descripcion,
	InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
	INTO TablaTemp
	FROM InvArticulo
	WHERE InvArticulo.Id = '$IdArticuloQ'
	";

	$sql2="SELECT * FROM TablaTemp";

	//BUSQUEDA DE EXISTENCIA
	$sql3="
	SELECT
	SUM (InvLoteAlmacen.Existencia) As Existencia
	INTO TablaTemp1
	FROM InvLoteAlmacen
	INNER JOIN TablaTemp ON  TablaTemp.Id =InvLoteAlmacen.InvArticuloId
	WHERE (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
	GROUP BY TablaTemp.Id, TablaTemp.CodigoArticulo,TablaTemp.Descripcion,TablaTemp.ConceptoImpuesto
	";

	$sql4="SELECT * FROM TablaTemp1";

	//CASO PRECIO TROQUELADO INICIO
	$sql5="
	SELECT
	InvLoteAlmacen.InvLoteId
	into TablaTemp2
	FROM InvLoteAlmacen 
	WHERE (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) 
	AND (InvLoteAlmacen.InvArticuloId = '$IdArticuloQ')
	AND (InvLoteAlmacen.Existencia>0)
	";

	$sql6="
	SELECT
	InvLote.Id,
	invlote.M_PrecioCompraBruto,
	invlote.M_PrecioTroquelado
	INTO TablaTemp3
	FROM InvLote
	INNER JOIN TablaTemp2 ON TablaTemp2.InvLoteId = InvLote.Id
	ORDER BY invlote.M_PrecioTroquelado, invlote.M_PrecioCompraBruto DESC
	";

	$sql7="
	SELECT TOP 1
	TablaTemp3.M_PrecioTroquelado
	INTO TablaTemp4
	FROM TablaTemp3
	";

	$sql8="SELECT * FROM TablaTemp4";
	//FIN PRECIO TROQUELADO
	
	//CASO PRECIOS CALCULADO INICIO
	$sql9="
	SELECT
	InvLoteAlmacen.InvLoteId
	INTO TablaTemp5
	FROM InvLoteAlmacen 
	WHERE(InvLoteAlmacen.InvArticuloId = '$IdArticuloQ')
	AND (InvLoteAlmacen.Existencia>0)
	";

	$sql10="
	SELECT
	InvLote.Id,
	invlote.M_PrecioCompraBruto,
	invlote.M_PrecioTroquelado
	INTO TablaTemp6
	from InvLote
	INNER JOIN TablaTemp5 ON TablaTemp5.InvLoteId = InvLote.Id
	ORDER BY invlote.M_PrecioTroquelado, invlote.M_PrecioCompraBruto DESC
	";

	$sql11="
	SELECT TOP 1
	TablaTemp6.M_PrecioCompraBruto
	INTO TablaTemp7 
	FROM TablaTemp6
	";

	$sql12="SELECT * FROM TablaTemp7";
	//FIN PRECIO CALCULADO

	//INCIO CUERPO TABLA
	$sql13="
	SELECT
	GenPersona.Nombre,
	CONVERT(date,ComFactura.FechaRegistro) As FechaRegistro,
	ComFacturaDetalle.CantidadRecibidaFactura,
	ComFacturaDetalle.M_PrecioCompraBruto
	INTO TablaTemp8
	FROM InvArticulo
	inner join ComFacturaDetalle ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
	inner join ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
	inner join ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
	inner join GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
	WHERE InvArticulo.Id = '$IdArticuloQ'
	ORDER BY ComFactura.FechaRegistro DESC
	";

	$sql14="SELECT * FROM TablaTemp8";
	//FIN CUERPO TABLA


	sqlsrv_query($conn,$sql);
	sqlsrv_query($conn,$sql1);
	//Datos de producto
	$resultDP = sqlsrv_query($conn,$sql2);
	sqlsrv_query($conn,$sql3);
	//Existencia
	$result1E = sqlsrv_query($conn,$sql4);

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
	//INICIO ARMADO DE ENCABEZADO
 	while( $rowDP = sqlsrv_fetch_array( $resultDP, SQLSRV_FETCH_ASSOC)) {
 	//DATOS
		echo '<tr>';
		echo '<td>'.$rowDP["CodigoArticulo"].'</td>';	
		echo '<td>'.$rowDP["Descripcion"].'</td>';
		$ConceptoImpuesto = $rowDP["ConceptoImpuesto"];
	//EXISTENCIA
		$rowE = sqlsrv_fetch_array( $result1E, SQLSRV_FETCH_ASSOC);
		if($rowE){
			echo '<td align="center">'.intval($rowE["Existencia"]).'</td>';
		}
		else{
			echo '<td align="center">'.intval('0').'</td>';
		}			
	//INICIO PRECIO
		sqlsrv_query($conn,$sql5);
		sqlsrv_query($conn,$sql6);
		sqlsrv_query($conn,$sql7);
	//PRECIO TROQUELADO
		$resultPT = sqlsrv_query($conn,$sql8);
		$rowPT = sqlsrv_fetch_array( $resultPT, SQLSRV_FETCH_ASSOC);
		$Precio = $rowPT["M_PrecioTroquelado"];

		if($Precio){
			echo '<td align="center">'.SigVe." ".round($Precio,2).'</td>';
		}
		else{
			sqlsrv_query($conn,$sql9);
			sqlsrv_query($conn,$sql10);
			sqlsrv_query($conn,$sql11);
	//PRECIO CALCULADO
			$resultPC = sqlsrv_query($conn,$sql12);
			$rowPC = sqlsrv_fetch_array( $resultPC, SQLSRV_FETCH_ASSOC);
			$Precio = $rowPC["M_PrecioCompraBruto"];

			if($ConceptoImpuesto==1){
				$Precio = ($Precio/Utilidad)*Impuesto;
				echo '<td align="center">'.SigVe." ".round($Precio,2).'</td>';
			}
			else{
				$Precio = ($Precio/Utilidad);
				echo '<td align="center">'.SigVe." ".round($Precio,2).'</td>';
			}
		}
	//FIN PRECIO
	
	//INICIO TASA ACTUAL Y COSTO DIVISA 
		$resultTasa = QueryTasa(date('Y-m-d'));

		$resultTasa = mysqli_fetch_assoc($resultTasa);
		$Tasa=$resultTasa['tasa'];
		
		if($Tasa!=0){
			echo '<td align="center">'.SigDolar." ".$Tasa.'</td>';
			echo '<td align="center">'.SigDolar." ".round(($Precio/$Tasa),2).'</td>';
		}
		else{
			echo '<td align="center">'.SigDolar.' 0.00</td>';
			echo '<td align="center">'.SigDolar.' 0.00</td>';
		}
	//FIN TASA ACTUAL Y COSTO DIVISA 
	//FIN ARMADO DE ENCABEZADO
	//FIN TABLA
			echo '</tr>';		
  	}
  	echo '
  		</tbody>
	</table>';
	//INICIO ARMADO DE CUERPO
	sqlsrv_query($conn,$sql13);
	//CUERPO TABLA
	$resultCT = sqlsrv_query($conn,$sql14);

	echo'	
	  	</tbody>	
	</table>

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

	while( $row = sqlsrv_fetch_array( $resultCT, SQLSRV_FETCH_ASSOC)) {
			echo '<tr>';
			echo '<td>'.$row["Nombre"].'</td>';	
			echo '<td align="center">'.$row["FechaRegistro"]->format('Y-m-d').'</td>';
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
	//FIN ARMADO DE CUERPO
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
	Reporte4: Articulos menos vendidos
	Funcion: Query para la base de datos del Reporte4
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
/************REPORTE 5 Prouctos en falla***********/
/*
	Nombre: TableProductosFalla
	Reporte5: Prouctos en falla
	Funcion: Arma la tabla de los Prouctos en falla
 */
function TableProductosFalla($FInicial,$FFinal){

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
	SELECT
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
	SUM(TablaTemp2.Existencia) AS Existencia
	INTO TablaTemp3
	FROM TablaTemp2
	GROUP BY Id,CodigoArticulo, Descripcion, VecesFacturado, UnidadesVendidas
	ORDER BY UnidadesVendidas DESC
	DROP TABLE TablaTemp2
	";

	$sql5="
	SELECT * from TablaTemp3 where TablaTemp3.Existencia = 0
	";

	$conn = conectarDB();
	sqlsrv_query($conn,$sql);
	sqlsrv_query($conn,$sql1);
	sqlsrv_query($conn,$sql2);
	sqlsrv_query($conn,$sql3);
	sqlsrv_query($conn,$sql4);
	$result = sqlsrv_query($conn,$sql5);

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
		      	<th scope="col">Unidades Vendidas</th>
		      	<th scope="col">Existencia</th>
		    </tr>
	  	</thead>
	  	<tbody>
	';
	
	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
			echo '<tr>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';	
			echo '<td align="left">'.$row["Descripcion"].'</td>';
			echo '<td align="center">'.intval($row["UnidadesVendidas"]).'</td>';
			echo '<td align="center">'.intval($row["Existencia"]).'</td>';
			echo '</tr>';		
  	}
  	echo '
  		</tbody>
	</table>';

	sqlsrv_close( $conn );
}
/**********REPORTE 6 Reporte para pedidos***********/
/*
	Nombre: QueryArticulosDescId
	Funcion: Query que devuelve todos los articulos de la base de datos
 */
function QueryArticulosDescLike($VarLike){
	$sql = "SELECT 
		InvArticulo.Id,
		InvArticulo.CodigoArticulo,
		InvArticulo.Descripcion
		FROM
		InvArticulo
		WHERE 
		InvArticulo.Descripcion LIKE '%$VarLike%'
		order by InvArticulo.Descripcion ASC";
	return $sql;
}

function TablaReportePedido($VarLike,$FInicial,$FFinal){
	$conn = conectarDB();
	$sql = QueryArticulosDescLike($VarLike);
	$result = sqlsrv_query($conn,$sql);

	$FFinalRango = $FFinal;
	$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

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
		    </tr>
	  	</thead>
	  	<tbody>
	';
	
	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
			echo '<tr>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';	
			echo '<td align="left">'.$row["Descripcion"].'</td>';
			echo '</tr>';		
  	}
  	echo '
  		</tbody>
	</table>';

	sqlsrv_close( $conn );
}
?>
