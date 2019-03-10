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
/************REPORTE 1 ACTIVACION DE PROVEEDORES***********/
/*OPTIMIZADO*/
//REPORTE ACTIVACION DE PROVEEDORES
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
/**************REPORTE 2 HISTORICO DE ARTICULO***********/
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
		}
		else{
			echo '<td align="center">'.intval('0').'</td>';
			echo '<td align="center">'.SigVe.' 0 </td>';
		}			

	
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
/************REPORTE 3 ARTICULOS MAS VENDIDOS***********/
/*OPTIMIZADO*/
//REPORTE ARTICULOS MAS VENDIDOS
function ReporteArticulosMasVendidos($Top,$FInicial,$FFinal){

	$FFinalRango = $FFinal;
	$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

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
	";

	//TOP DE UNIDADES VENDIDAS Y VECES FACTURADO
	$sql1="
	SELECT TOP $Top
	InvArticulo.Id,
	InvArticulo.CodigoArticulo,
	InvArticulo.Descripcion, 
	COUNT(*) AS VecesFacturadoCliente, 
	SUM(VenFacturaDetalle.Cantidad) AS UnidadesVendidasCliente 
	INTO TablaTemp
	FROM VenFacturaDetalle
	INNER JOIN InvArticulo ON InvArticulo.Id = VenFacturaDetalle.InvArticuloId
	INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
	WHERE
	(VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
	GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion
	ORDER BY UnidadesVendidasCliente DESC
	";

	//UNIDADES DEVULTAS POR CLIENTES Y VECES DEVUELTAS
	$sql2="
	SELECT
	TablaTemp.Id,
	TablaTemp.CodigoArticulo,
	TablaTemp.Descripcion,
	COUNT(*) AS VecesDevueltaCliente,
	SUM(VenDevolucionDetalle.Cantidad) AS UnidadesDevueltaCliente
	INTO TablaTemp1
	FROM VenDevolucionDetalle
	INNER JOIN TablaTemp ON TablaTemp.Id = VenDevolucionDetalle.InvArticuloId
	INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
	WHERE
	(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
	GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
	ORDER BY TablaTemp.Id DESC
	";

	//CANTIDAD COMPRADA A PROVEEDORES
	$sql3="
	SELECT
	TablaTemp.Id,
	TablaTemp.CodigoArticulo,
	TablaTemp.Descripcion, 
	COUNT(*) AS VecesFacturadaProveedor,
	SUM(ComFacturaDetalle.CantidadFacturada) AS CantidadFacturadaProveedor
	INTO TablaTemp2
	FROM ComFactura
	INNER JOIN ComFacturaDetalle ON ComFacturaDetalle.ComFacturaId = ComFactura.Id
	INNER JOIN TablaTemp ON TablaTemp.Id = ComFacturaDetalle.InvArticuloId
	WHERE
	(ComFactura.FechaDocumento > '$FInicial' AND ComFactura.FechaDocumento < '$FFinal')
	GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
	ORDER BY TablaTemp.Id DESC
	";

	//CANTIDAD DEVUELTA PROVEEDOR
	$sql4="
	SELECT
	TablaTemp.Id,
	TablaTemp.CodigoArticulo,
	TablaTemp.Descripcion,
	COUNT(*) AS VecesReclamoProveedor,
	SUM(ComReclamoDetalle.Cantidad) AS CantidadReclamoProveedor
	INTO TablaTemp3
	FROM ComReclamoDetalle
	INNER JOIN TablaTemp ON TablaTemp.Id = ComReclamoDetalle.InvArticuloId
	INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
	WHERE
	(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
	GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
	ORDER BY TablaTemp.Id DESC
	";

	//EXIETENCIA Y RANGO DIAS
	$sql5="
	SELECT
	TablaTemp.Id, 
	TablaTemp.CodigoArticulo, 
	TablaTemp.Descripcion,  
	SUM(InvLoteAlmacen.Existencia) AS Existencia,
	DATEDIFF(day,'$FInicial','$FFinalRango') As RangoDias
	INTO TablaTemp4
	FROM TablaTemp
	INNER JOIN InvArticulo ON InvArticulo.CodigoArticulo = TablaTemp.CodigoArticulo
	INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
	WHERE (InvLoteAlmacen.InvAlmacenId = 1 or InvLoteAlmacen.InvAlmacenId = 2)
	GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo, TablaTemp.Descripcion
	";

	//INTEGRACION DE TABLAS
	$sql6="
	SELECT 
	TablaTemp.Id,
	TablaTemp.CodigoArticulo,
	TablaTemp.Descripcion,
	TablaTemp4.Existencia,
	TablaTemp4.RangoDias,
	TablaTemp.VecesFacturadoCliente,
	TablaTemp.UnidadesVendidasCliente,
	TablaTemp1.VecesDevueltaCliente,
	TablaTemp1.UnidadesDevueltaCliente,
	TablaTemp2.VecesFacturadaProveedor,
	TablaTemp2.CantidadFacturadaProveedor,
	TablaTemp3.VecesReclamoProveedor,
	TablaTemp3.CantidadReclamoProveedor
	INTO TablaTemp5
	FROM TablaTemp
	LEFT JOIN TablaTemp1 ON TablaTemp1.Id = TablaTemp.Id
	LEFT JOIN TablaTemp2 ON TablaTemp2.Id = TablaTemp.Id
	LEFT JOIN TablaTemp3 ON TablaTemp3.Id = TablaTemp.Id
	LEFT JOIN TablaTemp4 ON TablaTemp4.Id = TablaTemp.Id
	";

	$sql7="SELECT * FROM TablaTemp5";

	$conn = conectarDB();
	sqlsrv_query($conn,$sql);
	sqlsrv_query($conn,$sql1);
	sqlsrv_query($conn,$sql2);
	sqlsrv_query($conn,$sql3);
	sqlsrv_query($conn,$sql4);
	sqlsrv_query($conn,$sql5);
	sqlsrv_query($conn,$sql6);
	$result = sqlsrv_query($conn,$sql7);

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
		      	<th scope="col">Existencia</th>
		      	<th scope="col">Cant. veces Facturado</th>
		      	<th scope="col">Unidades vendidas</th>
		      	<th scope="col">Venta diaria</th>
		      	<th scope="col">Dias restantes</th>
		      	<th scope="col">Cant. comprado Proveedor</th>
		    </tr>
	  	</thead>
	  	<tbody>
	';
	
	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
			echo '<tr>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';

			echo '<td align="left">'.$row["Descripcion"].'</td>';

			echo '<td align="center">'.intval($row["Existencia"]).'</td>';

			echo '<td align="center">'.intval($row["VecesFacturadoCliente"]-$row["VecesDevueltaCliente"]).'</td>';

			echo '<td align="center">'.intval($row["UnidadesVendidasCliente"]-$row["UnidadesDevueltaCliente"]).'</td>';

			$Venta = ($row["UnidadesVendidasCliente"]-$row["UnidadesDevueltaCliente"]);

			echo '<td align="center">'.round(($Venta/$row["RangoDias"]),2).'</td>';

			$VentaDiaria = $Venta/$row["RangoDias"];

			echo '<td align="center">'.round(($row["Existencia"]/$VentaDiaria),2).'</td>';

			echo '<td align="center">'.intval($row["CantidadFacturadaProveedor"]-$row["CantidadReclamoProveedor"]).'</td>';
			echo '</tr>';		
  	}
  	echo '
  		</tbody>
	</table>';

	sqlsrv_close( $conn );
}
/************REPORTE 4 Articulos menos vendidos***********/
/*OPTIMIZADO*/
/*
	Nombre: QueryArticulosMenosVendidos
	Reporte4: Articulos menos vendidos
	Funcion: Query para la base de datos del Reporte4
 */
function ReporteArticulosMenosVendidos($Top,$FInicial,$FFinal){

	$FFinalRango = $FFinal;
	$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

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
	";

	//TOP DE UNIDADES VENDIDAS Y VECES FACTURADO
	$sql1="
	SELECT TOP $Top
	InvArticulo.Id,
	InvArticulo.CodigoArticulo,
	InvArticulo.Descripcion, 
	COUNT(*) AS VecesFacturadoCliente, 
	SUM(VenFacturaDetalle.Cantidad) AS UnidadesVendidasCliente 
	INTO TablaTemp
	FROM VenFacturaDetalle
	INNER JOIN InvArticulo ON InvArticulo.Id = VenFacturaDetalle.InvArticuloId
	INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
	WHERE
	(VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
	GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion
	ORDER BY UnidadesVendidasCliente ASC
	";

	//UNIDADES DEVULTAS POR CLIENTES Y VECES DEVUELTAS
	$sql2="
	SELECT
	TablaTemp.Id,
	TablaTemp.CodigoArticulo,
	TablaTemp.Descripcion,
	COUNT(*) AS VecesDevueltaCliente,
	SUM(VenDevolucionDetalle.Cantidad) AS UnidadesDevueltaCliente
	INTO TablaTemp1
	FROM VenDevolucionDetalle
	INNER JOIN TablaTemp ON TablaTemp.Id = VenDevolucionDetalle.InvArticuloId
	INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
	WHERE
	(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
	GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
	ORDER BY TablaTemp.Id DESC
	";

	//CANTIDAD COMPRADA A PROVEEDORES
	$sql3="
	SELECT
	TablaTemp.Id,
	TablaTemp.CodigoArticulo,
	TablaTemp.Descripcion, 
	COUNT(*) AS VecesFacturadaProveedor,
	SUM(ComFacturaDetalle.CantidadFacturada) AS CantidadFacturadaProveedor
	INTO TablaTemp2
	FROM ComFactura
	INNER JOIN ComFacturaDetalle ON ComFacturaDetalle.ComFacturaId = ComFactura.Id
	INNER JOIN TablaTemp ON TablaTemp.Id = ComFacturaDetalle.InvArticuloId
	WHERE
	(ComFactura.FechaDocumento > '$FInicial' AND ComFactura.FechaDocumento < '$FFinal')
	GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
	ORDER BY TablaTemp.Id DESC
	";

	//CANTIDAD DEVUELTA PROVEEDOR
	$sql4="
	SELECT
	TablaTemp.Id,
	TablaTemp.CodigoArticulo,
	TablaTemp.Descripcion,
	COUNT(*) AS VecesReclamoProveedor,
	SUM(ComReclamoDetalle.Cantidad) AS CantidadReclamoProveedor
	INTO TablaTemp3
	FROM ComReclamoDetalle
	INNER JOIN TablaTemp ON TablaTemp.Id = ComReclamoDetalle.InvArticuloId
	INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
	WHERE
	(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
	GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
	ORDER BY TablaTemp.Id DESC
	";

	//EXIETENCIA Y RANGO DIAS
	$sql5="
	SELECT
	TablaTemp.Id, 
	TablaTemp.CodigoArticulo, 
	TablaTemp.Descripcion,  
	SUM(InvLoteAlmacen.Existencia) AS Existencia,
	DATEDIFF(day,'$FInicial','$FFinalRango') As RangoDias
	INTO TablaTemp4
	FROM TablaTemp
	INNER JOIN InvArticulo ON InvArticulo.CodigoArticulo = TablaTemp.CodigoArticulo
	INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
	WHERE (InvLoteAlmacen.InvAlmacenId = 1 or InvLoteAlmacen.InvAlmacenId = 2)
	GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo, TablaTemp.Descripcion
	";

	//INTEGRACION DE TABLAS
	$sql6="
	SELECT 
	TablaTemp.Id,
	TablaTemp.CodigoArticulo,
	TablaTemp.Descripcion,
	TablaTemp4.Existencia,
	TablaTemp4.RangoDias,
	TablaTemp.VecesFacturadoCliente,
	TablaTemp.UnidadesVendidasCliente,
	TablaTemp1.VecesDevueltaCliente,
	TablaTemp1.UnidadesDevueltaCliente,
	TablaTemp2.VecesFacturadaProveedor,
	TablaTemp2.CantidadFacturadaProveedor,
	TablaTemp3.VecesReclamoProveedor,
	TablaTemp3.CantidadReclamoProveedor
	INTO TablaTemp5
	FROM TablaTemp
	LEFT JOIN TablaTemp1 ON TablaTemp1.Id = TablaTemp.Id
	LEFT JOIN TablaTemp2 ON TablaTemp2.Id = TablaTemp.Id
	LEFT JOIN TablaTemp3 ON TablaTemp3.Id = TablaTemp.Id
	LEFT JOIN TablaTemp4 ON TablaTemp4.Id = TablaTemp.Id
	";

	$sql7="SELECT * FROM TablaTemp5";

	$conn = conectarDB();
	sqlsrv_query($conn,$sql);
	sqlsrv_query($conn,$sql1);
	sqlsrv_query($conn,$sql2);
	sqlsrv_query($conn,$sql3);
	sqlsrv_query($conn,$sql4);
	sqlsrv_query($conn,$sql5);
	sqlsrv_query($conn,$sql6);
	$result = sqlsrv_query($conn,$sql7);

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
		      	<th scope="col">Existencia</th>
		      	<th scope="col">Cant. veces Facturado</th>
		      	<th scope="col">Unidades vendidas</th>
		      	<th scope="col">Venta diaria</th>
		      	<th scope="col">Dias restantes</th>
		      	<th scope="col">Cant. comprado Proveedor</th>
		    </tr>
	  	</thead>
	  	<tbody>
	';
	
	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
			echo '<tr>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';

			echo '<td align="left">'.$row["Descripcion"].'</td>';

			echo '<td align="center">'.intval($row["Existencia"]).'</td>';

			echo '<td align="center">'.intval($row["VecesFacturadoCliente"]-$row["VecesDevueltaCliente"]).'</td>';

			echo '<td align="center">'.intval($row["UnidadesVendidasCliente"]-$row["UnidadesDevueltaCliente"]).'</td>';

			$Venta = ($row["UnidadesVendidasCliente"]-$row["UnidadesDevueltaCliente"]);

			echo '<td align="center">'.round(($Venta/$row["RangoDias"]),2).'</td>';

			$VentaDiaria = $Venta/$row["RangoDias"];

			echo '<td align="center">'.round(($row["Existencia"]/$VentaDiaria),2).'</td>';

			echo '<td align="center">'.intval($row["CantidadFacturadaProveedor"]-$row["CantidadReclamoProveedor"]).'</td>';
			echo '</tr>';		
  	}
  	echo '
  		</tbody>
	</table>';

	sqlsrv_close( $conn );
}
/************REPORTE 5 Prouctos en falla***********/
/*OPTIMIZADO*/
/*
	Nombre: ReporteProductosFalla
	Reporte5: Prouctos en falla
	Funcion: Arma la tabla de los Prouctos en falla
 */
function ReporteProductosFalla($FInicial,$FFinal){

	$FFinalRango = $FFinal;
	$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

	$sql = "
	IF OBJECT_ID ('TablaTemp', 'U') IS NOT NULL
		DROP TABLE TablaTemp;
	IF OBJECT_ID ('TablaTemp1', 'U') IS NOT NULL
		DROP TABLE TablaTemp1;
	";

	$sql1="
	SELECT
	InvArticulo.Id,
	InvArticulo.CodigoArticulo,
	InvArticulo.Descripcion, 
	COUNT(*) AS VecesFacturadoCliente, 
	SUM(VenFacturaDetalle.Cantidad) AS UnidadesVendidasCliente 
	INTO TablaTemp
	FROM VenFacturaDetalle
	INNER JOIN InvArticulo ON InvArticulo.Id = VenFacturaDetalle.InvArticuloId
	INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
	WHERE
	(VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
	GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion
	ORDER BY UnidadesVendidasCliente DESC
	";

	$sql2="
	SELECT
	TablaTemp.Id, 
	TablaTemp.CodigoArticulo, 
	TablaTemp.Descripcion,
	TablaTemp.VecesFacturadoCliente,
	TablaTemp.UnidadesVendidasCliente,  
	SUM(InvLoteAlmacen.Existencia) AS Existencia
	INTO TablaTemp1
	FROM TablaTemp
	INNER JOIN InvArticulo ON InvArticulo.CodigoArticulo = TablaTemp.CodigoArticulo
	INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
	WHERE (InvLoteAlmacen.InvAlmacenId = 1 or InvLoteAlmacen.InvAlmacenId = 2)
	GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo, TablaTemp.Descripcion, TablaTemp.UnidadesVendidasCliente, TablaTemp.VecesFacturadoCliente
	ORDER BY TablaTemp.UnidadesVendidasCliente DESC
	";

	$sql3="
	SELECT * from TablaTemp1 
	WHERE TablaTemp1.Existencia = 0 
	ORDER BY TablaTemp1.UnidadesVendidasCliente DESC
	";

	$conn = conectarDB();
	sqlsrv_query($conn,$sql);
	sqlsrv_query($conn,$sql1);
	sqlsrv_query($conn,$sql2);
	$result = sqlsrv_query($conn,$sql3);

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
			echo '<td align="center">'.intval($row["UnidadesVendidasCliente"]).'</td>';
			echo '<td align="center"> 0 </td>';
			echo '</tr>';		
  	}
  	echo '
  		</tbody>
	</table>';

	sqlsrv_close( $conn );
}
/**********REPORTE 6 Reporte para pedidos***********/
/*AQUI VOY*/
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
/*
	Nombre: ReportePedido
 */
function ReportePedido($VarLike,$FInicial,$FFinal){
	
	$FFinalRango = $FFinal;
	$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

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
	";

	//BUSQUEDA DE PROUCTOS POR DESCRIPCION
	$sql1="
	SELECT 
	InvArticulo.Id, 
	InvArticulo.CodigoArticulo,
	InvArticulo.Descripcion,
	InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
	INTO TablaTemp
	FROM InvArticulo
	WHERE 
	InvArticulo.Descripcion LIKE '%$VarLike%'
	ORDER BY InvArticulo.Descripcion ASC
	";

	//UNIDADES VENDIDAS Y VECES FACTURADO
	$sql2="
	SELECT
	TablaTemp.Id,
	TablaTemp.CodigoArticulo,
	TablaTemp.Descripcion, 
	COUNT(*) AS VecesFacturadoCliente, 
	SUM(VenFacturaDetalle.Cantidad) AS UnidadesVendidasCliente 
	INTO TablaTemp1
	FROM VenFacturaDetalle
	INNER JOIN TablaTemp ON TablaTemp.Id = VenFacturaDetalle.InvArticuloId
	INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
	WHERE
	(VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
	GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
	ORDER BY TablaTemp.Descripcion ASC
	";

	//UNIDADES DEVULTAS POR CLIENTES Y VECES DEVUELTAS
	$sql3="
	SELECT
	TablaTemp.Id,
	TablaTemp.CodigoArticulo,
	TablaTemp.Descripcion,
	COUNT(*) AS VecesDevueltaCliente,
	SUM(VenDevolucionDetalle.Cantidad) AS UnidadesDevueltaCliente
	INTO TablaTemp2
	FROM VenDevolucionDetalle
	INNER JOIN TablaTemp ON TablaTemp.Id = VenDevolucionDetalle.InvArticuloId
	INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
	WHERE
	(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
	GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
	ORDER BY TablaTemp.Descripcion ASC
	";

	//CANTIDAD COMPRADA A PROVEEDORES
	$sql4="
	SELECT
	TablaTemp.Id,
	TablaTemp.CodigoArticulo,
	TablaTemp.Descripcion, 
	COUNT(*) AS VecesFacturadaProveedor,
	SUM(ComFacturaDetalle.CantidadFacturada) AS CantidadFacturadaProveedor
	INTO TablaTemp3
	FROM ComFactura
	INNER JOIN ComFacturaDetalle ON ComFacturaDetalle.ComFacturaId = ComFactura.Id
	INNER JOIN TablaTemp ON TablaTemp.Id = ComFacturaDetalle.InvArticuloId
	WHERE
	(ComFactura.FechaDocumento > '$FInicial' AND ComFactura.FechaDocumento < '$FFinal')
	GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
	ORDER BY TablaTemp.Descripcion ASC
	";

	//CANTIDAD DEVUELTA PROVEEDOR
	$sql5="
	SELECT
	TablaTemp.Id,
	TablaTemp.CodigoArticulo,
	TablaTemp.Descripcion,
	COUNT(*) AS VecesReclamoProveedor,
	SUM(ComReclamoDetalle.Cantidad) AS CantidadReclamoProveedor
	INTO TablaTemp4
	FROM ComReclamoDetalle
	INNER JOIN TablaTemp ON TablaTemp.Id = ComReclamoDetalle.InvArticuloId
	INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
	WHERE
	(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
	GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
	ORDER BY TablaTemp.Descripcion ASC
	";

	//EXIETENCIA Y RANGO DIAS
	$sql6="
	SELECT
	TablaTemp.Id, 
	TablaTemp.CodigoArticulo, 
	TablaTemp.Descripcion,  
	SUM(InvLoteAlmacen.Existencia) AS Existencia,
	DATEDIFF(day,'$FInicial','$FFinalRango') As RangoDias
	INTO TablaTemp5
	FROM TablaTemp
	INNER JOIN InvArticulo ON InvArticulo.CodigoArticulo = TablaTemp.CodigoArticulo
	INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
	WHERE (InvLoteAlmacen.InvAlmacenId = 1 or InvLoteAlmacen.InvAlmacenId = 2)
	GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo, TablaTemp.Descripcion
	";

	//INTEGRACION DE TABLAS
	$sql7="
	SELECT 
	TablaTemp.Id,
	TablaTemp.CodigoArticulo,
	TablaTemp.Descripcion,
	TablaTemp.ConceptoImpuesto,
	TablaTemp5.Existencia,
	TablaTemp5.RangoDias,
	TablaTemp1.VecesFacturadoCliente,
	TablaTemp1.UnidadesVendidasCliente,
	TablaTemp2.VecesDevueltaCliente,
	TablaTemp2.UnidadesDevueltaCliente,
	TablaTemp3.VecesFacturadaProveedor,
	TablaTemp3.CantidadFacturadaProveedor,
	TablaTemp4.VecesReclamoProveedor,
	TablaTemp4.CantidadReclamoProveedor
	INTO TablaTemp6
	FROM TablaTemp
	LEFT JOIN TablaTemp1 ON TablaTemp1.Id = TablaTemp.Id
	LEFT JOIN TablaTemp2 ON TablaTemp2.Id = TablaTemp.Id
	LEFT JOIN TablaTemp3 ON TablaTemp3.Id = TablaTemp.Id
	LEFT JOIN TablaTemp4 ON TablaTemp4.Id = TablaTemp.Id
	LEFT JOIN TablaTemp5 ON TablaTemp5.Id = TablaTemp.Id
	";

	$sql8="SELECT * FROM TablaTemp6";

	$conn = conectarDB();
	sqlsrv_query($conn,$sql);
	sqlsrv_query($conn,$sql1);
	sqlsrv_query($conn,$sql2);
	sqlsrv_query($conn,$sql3);
	sqlsrv_query($conn,$sql4);
	sqlsrv_query($conn,$sql5);
	sqlsrv_query($conn,$sql6);
	sqlsrv_query($conn,$sql7);
	$result = sqlsrv_query($conn,$sql8);

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
		      	<th scope="col">Existencia</th>
		      	<th scope="col">Unidades vendidas</th>
		      	<th scope="col">Unidades compradas</th>
		      	<th scope="col">Precio</th>
		      	<th scope="col">Ultimo Lote</th>
		    </tr>
	  	</thead>
	  	<tbody>
	';
	
	while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
		echo '<tr>';
		echo '<td align="left">'.$row["CodigoArticulo"].'</td>';	
		echo '<td align="left">'.$row["Descripcion"].'</td>';
		echo '<td align="center">'.intval($row["Existencia"]).'</td>';
		$UnidadesVendidasCliente = $row["UnidadesVendidasCliente"]-$row["UnidadesDevueltaCliente"];
		echo '<td align="center">'.($UnidadesVendidasCliente).'</td>';
		$CantidadFacturadaProveedor = $row["CantidadFacturadaProveedor"]-$row["CantidadReclamoProveedor"];
		echo '<td align="center">'.($CantidadFacturadaProveedor).'</td>';
		
	//DATOS GLOBALES
		$IdArticuloD = $row["Id"];
		$ConceptoImpuesto = $row["ConceptoImpuesto"];
		$Existencia = $row["Existencia"];

	if($Existencia != 0){
	//INICIO PRECIO
	//INICIO PRECIO TROQUELADO
		$sql9="
		SELECT
		InvLoteAlmacen.InvLoteId
		into TablaTemp7
		FROM InvLoteAlmacen 
		WHERE (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) 
		AND (InvLoteAlmacen.InvArticuloId = '$IdArticuloD')
		AND (InvLoteAlmacen.Existencia>0)
		";

		$sql10="
		SELECT
		InvLote.Id,
		invlote.M_PrecioCompraBruto,
		invlote.M_PrecioTroquelado,
		invlote.FechaEntrada
		INTO TablaTemp8
		FROM InvLote
		INNER JOIN TablaTemp7 ON TablaTemp7.InvLoteId = InvLote.Id
		ORDER BY invlote.M_PrecioTroquelado, invlote.M_PrecioCompraBruto DESC
		";

		$sql11="
		SELECT TOP 1
		TablaTemp8.M_PrecioTroquelado
		INTO TablaTemp9
		FROM TablaTemp8
		";

		$sql12="SELECT * FROM TablaTemp9";

		$sql13="
		IF OBJECT_ID ('TablaTemp7', 'U') IS NOT NULL
			DROP TABLE TablaTemp7;
		IF OBJECT_ID ('TablaTemp8', 'U') IS NOT NULL
			DROP TABLE TablaTemp8;
		IF OBJECT_ID ('TablaTemp9', 'U') IS NOT NULL
			DROP TABLE TablaTemp9;
		IF OBJECT_ID ('TablaTemp10', 'U') IS NOT NULL
			DROP TABLE TablaTemp10;
		IF OBJECT_ID ('TablaTemp11', 'U') IS NOT NULL
			DROP TABLE TablaTemp11;
		IF OBJECT_ID ('TablaTemp12', 'U') IS NOT NULL
			DROP TABLE TablaTemp12;
		";

		sqlsrv_query($conn,$sql13);
		sqlsrv_query($conn,$sql9);
		sqlsrv_query($conn,$sql10);
		sqlsrv_query($conn,$sql11);

		$resultPT = sqlsrv_query($conn,$sql12);
		$rowPT = sqlsrv_fetch_array( $resultPT, SQLSRV_FETCH_ASSOC);
		$Precio = $rowPT["M_PrecioTroquelado"];

		if($Precio){
			echo '<td align="center">'.SigVe." ".round($Precio,2).'</td>';

			$sql17="
			SELECT TOP 1 
			CONVERT(DATE,TablaTemp8.FechaEntrada) AS UltimoLote 
			FROM TablaTemp8 
			ORDER BY TablaTemp8.FechaEntrada DESC
			";

			$resultUL = sqlsrv_query($conn,$sql17);
			$rowUL = sqlsrv_fetch_array( $resultUL, SQLSRV_FETCH_ASSOC);
			$UltimoLote = $rowUL["UltimoLote"];

		//ULTIMO LOTE
			if($UltimoLote){
				echo '<td align="center">'.$UltimoLote->format('Y-m-d').'</td>';
			}
			else{
				echo '<td align="center"> - </td>';
			}
			
		}
	//FIN PRECIO TROQUELADO
	//INICIO PRECIO CALCULADO
		else{
			$sql13="
			SELECT
			InvLoteAlmacen.InvLoteId
			INTO TablaTemp10
			FROM InvLoteAlmacen 
			WHERE(InvLoteAlmacen.InvArticuloId = '$IdArticuloD')
			AND (InvLoteAlmacen.Existencia>0)
			";

			$sql14="
			SELECT
			InvLote.Id,
			invlote.M_PrecioCompraBruto,
			invlote.M_PrecioTroquelado,
			invlote.FechaEntrada
			INTO TablaTemp11
			from InvLote
			INNER JOIN TablaTemp10 ON TablaTemp10.InvLoteId = InvLote.Id
			ORDER BY invlote.M_PrecioTroquelado, invlote.M_PrecioCompraBruto DESC
			";

			$sql15="
			SELECT TOP 1
			TablaTemp11.M_PrecioCompraBruto
			INTO TablaTemp12 
			FROM TablaTemp11
			";

			$sql16="SELECT * FROM TablaTemp12";

			sqlsrv_query($conn,$sql13);
			sqlsrv_query($conn,$sql14);
			sqlsrv_query($conn,$sql15);
	
			$resultPC = sqlsrv_query($conn,$sql16);
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

		//ULTIMO LOTE
			$sql18="
			SELECT TOP 1 
			CONVERT(DATE,TablaTemp11.FechaEntrada) AS UltimoLote 
			FROM TablaTemp11 
			ORDER BY TablaTemp11.FechaEntrada DESC
			";

			$resultUL = sqlsrv_query($conn,$sql18);
			$rowUL = sqlsrv_fetch_array( $resultUL, SQLSRV_FETCH_ASSOC);
			$UltimoLote = $rowUL["UltimoLote"];

			if($UltimoLote){
				echo '<td align="center">'.$UltimoLote->format('Y-m-d').'</td>';
			}
			else{
				echo '<td align="center"> - </td>';
			}
		}
	}
	else{
		echo '<td align="center">'.SigVe.'0 </td>';
		echo '<td align="center"> - </td>';
	}
		//FIN PRECIO CALCULADO
		//FIN PRECIO

	echo '</tr>';		
  	}
  	echo '
  		</tbody>
	</table>';

	sqlsrv_close( $conn );
}
?>
