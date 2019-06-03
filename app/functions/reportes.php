<?php
	/*****************************************************************************/
	/************************ REPORTE 1 ACTIVACION DE PROVEEDORES ****************/
	/*
		TITULO: ReporteActivacionProveedores
		PARAMETROS : [$SedeConnection] Siglas de la sede para la conexion
		FUNCION: Armar el reporte de activacion de proveedores
		RETORNO: No aplica
	 */
	function ReporteActivacionProveedores($SedeConnection){

		$conn = ConectarSmartpharma($SedeConnection);

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		$sql1 = QFRegProveedor();
		sqlsrv_query($conn,$sql1);

		$sql2 = QFRegRangoProveedor();
		$result = sqlsrv_query($conn,$sql2);

		echo '
		<div class="input-group md-form form-sm form-1 pl-0">
		  <div class="input-group-prepend">
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
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
		
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			echo '<tr>';
			echo '<td>'.$row['Nombre'].'</td>';
			echo '<td align="center">'.($row['FechaRegistro'])->format('Y-m-d').'</td>';
			echo '<td align="center">'.$row['RangoDias'].'</td>';
			echo '</tr>';
	  	}
	  	echo '
	  		</tbody>
		</table>';

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}
	/*****************************************************************************/
	/************************ REPORTE 2 HISTORICO DE PRODUCTOS *******************/
	/*
		TITULO: ReporteHistoricoArticulo
		PARAMETROS : [$SedeConnection] Siglas de la sede para la conexion
					 [$IdArticuloQ] Id del articulo a buscar
		FUNCION: Armar una tabla de historico de compra del articulo
		RETORNO: No aplica
	 */
	function ReporteHistoricoProducto($SedeConnection,$IdArticulo){
		
		$conn = ConectarSmartpharma($SedeConnection);

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

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
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
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
			      	<th scope="col">Precio (Con IVA)</td>
			      	<th scope="col">Tasa actual</td>
			      	<th scope="col">Precio en divisa (Con IVA)</td>
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
			      	<th scope="col">Fecha de documento</th>
			      	<th scope="col">Cantidad recibida</th>
			      	<th scope="col">Costo bruto (Sin IVA)</th>
			      	<th scope="col">Tasa en historico</th>
					<th scope="col">Costo en divisa (Sin IVA)</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';

		while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
				echo '<tr>';
				echo '<td>'.$row2["Nombre"].'</td>';
				echo '<td align="center">'.$row2["FechaDocumento"]->format('Y-m-d').'</td>';
				echo '<td align="center">'.intval($row2['CantidadRecibidaFactura']).'</td>';
				echo '<td align="center">'." ".round($row2["M_PrecioCompraBruto"],2)." ".SigVe.'</td>';

				$FechaD = $row2["FechaDocumento"]->format('Y-m-d');
				$Tasa = TasaFecha($FechaD);
				
				if($Tasa != 0){
					echo '<td align="center">'." ".$Tasa." ".SigVe.'</td>';
					echo '<td align="center">'." ".round(($row2["M_PrecioCompraBruto"]/$Tasa),2)." ".SigDolar.'</td>';
				}
				else{
					echo '<td align="center">0.00 '.SigVe.'</td>';
					echo '<td align="center">0.00 '.SigDolar.'</td>';
				}
				echo '</tr>';
	  	}
	  	echo '
	  		</tbody>
		</table>';

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}
	/*****************************************************************************/
	/************************ REPORTE 3 PRODUCTOS MAS VENDIDOS *******************/
	/*
		TITULO: ReporteProductosMasVendidos
		PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
					[$Top] Top de articulos a buscar
					[$FInicial] Fecha inicial del rango donde se buscara
					[$FFinal] Fecha final del rango donde se buscara
		FUNCION: Arma una tabla con los productos mas vendidos
		RETORNO: No aplica
	 */
	function ReporteProductosMasVendidos($SedeConnection,$Top,$FInicial,$FFinal){

		$conn = ConectarSmartpharma($SedeConnection);

		$FFinalImpresion= $FFinal;
		$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		$sql1 = QUnidadesVendidasCliente($FInicial,$FFinal);
		$sql2 = QUnidadesDevueltaCliente($FInicial,$FFinal);
		$sql3 = QUnidadesCompradasProveedor($FInicial,$FFinal);
		$sql4 = QUnidadesReclamoProveedor($FInicial,$FFinal);
		$sql5 = QIntegracionProductosVendidos();
		$sql6 = QProductoMasVendido($Top);
		
		sqlsrv_query($conn,$sql1);
		sqlsrv_query($conn,$sql2);
		sqlsrv_query($conn,$sql3);
		sqlsrv_query($conn,$sql4);
		sqlsrv_query($conn,$sql5);
		$result = sqlsrv_query($conn,$sql6);

		$RangoDias = RangoDias($FInicial,$FFinal);
		
		echo '
		<div class="input-group md-form form-sm form-1 pl-0">
		  <div class="input-group-prepend">
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
		  </div>
		  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
		</div>
		<br/>
		';

		echo'<h6 align="center">Periodo desde el '.$FInicial.' al '.$FFinalImpresion.' </h6>';

		echo'
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Codigo</th>
			      	<th scope="col">Descripcion</th>
			      	<th scope="col">Existencia</th>
			      	<th scope="col">Veces Facturado</th>
			      	<th scope="col">Unidades vendidas</th>
			      	<th scope="col">Unidades Compradas</th>
			      	<th scope="col">Venta diaria</th>
			      	<th scope="col">Dias restantes</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';

		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["Id"];

			$sql7 = QExistenciaArticulo($IdArticulo,0);
			$result1 = sqlsrv_query($conn,$sql7);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
			$Existencia = $row1["Existencia"];

			echo '<tr>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';
			echo '<td align="left">'.$row["Descripcion"].'</td>';
			echo '<td align="center">'.intval($Existencia).'</td>';
			echo '<td align="center">'.intval($row["TotalVecesVendidasCliente"]).'</td>';

			$Venta = intval($row["TotalUnidadesVendidasCliente"]);
			$VentaDiaria = VentaDiaria($Venta,$RangoDias);
			$DiasRestantes = DiasRestantes($Existencia,$VentaDiaria);

			echo '<td align="center">'.$Venta.'</td>';
			echo '<td align="center">'.intval($row["TotalUnidadesCompradasProveedor"]).'</td>';
			echo '<td align="center">'.round($VentaDiaria,2).'</td>';
			echo '<td align="center">'.round($DiasRestantes,2).'</td>';
			echo '</tr>';
	  	}
	  	echo '
	  		</tbody>
		</table>';
		
		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}
	/*****************************************************************************/
	/************************ REPORTE 4 PRODUCTOS MENOS VENDIDOS *****************/
	/*
		TITULO: ReporteProductosMenosVendidos
		PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
					[$Top] Top de articulos a buscar
					[$FInicial] Fecha inicial del rango donde se buscara
					[$FFinal] Fecha final del rango donde se buscara
		FUNCION: Arma una tabla con los productos menos vendidos
		RETORNO: No aplica
	 */
	function ReporteProductosMenosVendidos($SedeConnection,$Top,$FInicial,$FFinal){

		$conn = ConectarSmartpharma($SedeConnection);

		$FFinalImpresion= $FFinal;
		$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		$sql1 = QUnidadesVendidasCliente($FInicial,$FFinal);
		$sql2 = QUnidadesDevueltaCliente($FInicial,$FFinal);
		$sql3 = QUnidadesCompradasProveedor($FInicial,$FFinal);
		$sql4 = QUnidadesReclamoProveedor($FInicial,$FFinal);
		$sql5 = QIntegracionProductosVendidos();
		$sql6 = QProductoMenosVendido($Top);
		
		sqlsrv_query($conn,$sql1);
		sqlsrv_query($conn,$sql2);
		sqlsrv_query($conn,$sql3);
		sqlsrv_query($conn,$sql4);
		sqlsrv_query($conn,$sql5);
		$result = sqlsrv_query($conn,$sql6);

		$RangoDias = RangoDias($FInicial,$FFinal);
		
		echo '
		<div class="input-group md-form form-sm form-1 pl-0">
		  <div class="input-group-prepend">
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
		  </div>
		  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
		</div>
		<br/>
		';

		echo'<h6 align="center">Periodo desde el '.$FInicial.' al '.$FFinalImpresion.' </h6>';

		echo'
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Codigo</th>
			      	<th scope="col">Descripcion</th>
			      	<th scope="col">Existencia</th>
			      	<th scope="col">Precio (Con IVA)</th>
			      	<th scope="col">Tiempo en Tienda (Dias)</th>
			      	<th scope="col">Veces Facturado</th>
			      	<th scope="col">Unidades vendidas</th>
			      	<th scope="col">Unidades Compradas</th>
			      	<th scope="col">Venta diaria</th>
			      	<th scope="col">Dias restantes</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';

		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["Id"];

			$sql7 = QExistenciaArticulo($IdArticulo,0);
			$result1 = sqlsrv_query($conn,$sql7);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
			$Existencia = $row1["Existencia"];

			$sql8 = QArticulo($IdArticulo);
			sqlsrv_query($conn,$sql8);
			$result2 = sqlsrv_query($conn,$sql8);
			$row3 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);

			$IsIVA = $row3["ConceptoImpuesto"];
			$Precio = CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia);

			$sql9 = QTiempoEnTienda($IdArticulo);
			sqlsrv_query($conn,$sql9);
			$result3=sqlsrv_query($conn,$sql9);
			$row4=sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

			echo '<tr>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';
			echo '<td align="left">'.$row["Descripcion"].'</td>';
			echo '<td align="center">'.intval($Existencia).'</td>';
			echo '<td align="center">'." ".round($Precio,2)." ".SigVe.'</td>';
			
			if($Existencia==0){
				echo '<td align="center">0</td>';
			}
			else{
				echo '<td align="center">'.$row4["TiempoTienda"].'</td>';
			}
			
			echo '<td align="center">'.intval($row["TotalVecesVendidasCliente"]).'</td>';

			$Venta = intval($row["TotalUnidadesVendidasCliente"]);
			$VentaDiaria = VentaDiaria($Venta,$RangoDias);
			$DiasRestantes = DiasRestantes($Existencia,$VentaDiaria);

			echo '<td align="center">'.$Venta.'</td>';
			echo '<td align="center">'.intval($row["TotalUnidadesCompradasProveedor"]).'</td>';
			echo '<td align="center">'.round($VentaDiaria,2).'</td>';
			echo '<td align="center">'.round($DiasRestantes,2).'</td>';
			echo '</tr>';
	  	}
	  	echo '
	  		</tbody>
		</table>';
		
		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}
	/*****************************************************************************/
	/************************ REPORTE 5 PRODUCTOS EN FALLA ***********************/
	/*
		TITULO: ReporteProductosFalla
		PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion		
					[$FInicial] Fecha inicial del rango donde se buscara
					[$FFinal] Fecha final del rango donde se buscara
		FUNCION: Arma una lista de productos en falla
		RETORNO: No aplica
	 */
	function ReporteProductosFalla($SedeConnection,$FInicial,$FFinal){

		$conn = ConectarSmartpharma($SedeConnection);

		$FFinalImpresion= $FFinal;
		$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		$sql1 = QUnidadesVendidasCliente($FInicial,$FFinal);
		$sql2 = QUnidadesDevueltaCliente($FInicial,$FFinal);
		$sql3 = QUnidadesCompradasProveedor($FInicial,$FFinal);
		$sql4 = QUnidadesReclamoProveedor($FInicial,$FFinal);
		$sql5 = QIntegracionProductosVendidos();
		$sql6 = QIntegracionProductosFalla();
		$sql7 = QProductosFalla();
		
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
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
		  </div>
		  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
		</div>
		<br/>
		';

		echo'<h6 align="center">Periodo desde el '.$FInicial.' al '.$FFinalImpresion.' </h6>';

		echo'
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Codigo</th>
			      	<th scope="col">Descripcion</th>
			      	<th scope="col">Existencia</th>
			      	<th scope="col">Unidades vendidas</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';

		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["Id"];

			$sql7 = QExistenciaArticulo($IdArticulo,0);
			$result1 = sqlsrv_query($conn,$sql7);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
			$Existencia = $row1["Existencia"];

			echo '<tr>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';
			echo '<td align="left">'.$row["Descripcion"].'</td>';
			echo '<td align="center">'.intval($Existencia).'</td>';

			$Venta = intval($row["TotalUnidadesVendidasCliente"]);

			echo '<td align="center">'.$Venta.'</td>';
			echo '</tr>';
	  	}
	  	echo '
	  		</tbody>
		</table>';
		
		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}
	/*****************************************************************************/
	/************************ REPORTE 6 PEDIDO DE PRODUCTOS **********************/
	/*
		TITULO: ReportePedidoProductos
		PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
					[$Descripcion] Descripcion del articulo
					[$FInicial] Fecha inicial del rango donde se buscara
					[$FFinal] Fecha final del rango donde se buscara
		FUNCION: Arma una lista para el pedido de productos
		RETORNO: No aplica
	 */
	function ReportePedidoProductos($SedeConnection,$Descripcion,$FInicial,$FFinal){

		$conn = ConectarSmartpharma($SedeConnection);

		$FFinalImpresion= $FFinal;
		$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		$sql1 = QUnidadesVendidasCliente($FInicial,$FFinal);
		$sql2 = QUnidadesDevueltaCliente($FInicial,$FFinal);
		$sql3 = QUnidadesCompradasProveedor($FInicial,$FFinal);
		$sql4 = QUnidadesReclamoProveedor($FInicial,$FFinal);
		$sql5 = QIntegracionProductosVendidos();
		$sql6 = QIntegracionProductosFalla();
		$sql61 = QArticuloDescLike($Descripcion,0);
		$sql7 = QPedidoProductos();
		
		sqlsrv_query($conn,$sql1);
		sqlsrv_query($conn,$sql2);
		sqlsrv_query($conn,$sql3);
		sqlsrv_query($conn,$sql4);
		sqlsrv_query($conn,$sql5);
		sqlsrv_query($conn,$sql6);
		sqlsrv_query($conn,$sql61);
		$result = sqlsrv_query($conn,$sql7);

		$RangoDias = RangoDias($FInicial,$FFinal);

		echo '
		<div class="input-group md-form form-sm form-1 pl-0">
		  <div class="input-group-prepend">
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
		  </div>
		  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
		</div>
		<br/>
		';

		echo'<h6 align="center">Periodo desde el '.$FInicial.' al '.$FFinalImpresion.' </h6>';

		echo'
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Codigo</th>
			      	<th scope="col">Descripcion</th>
			      	<th scope="col">Existencia</th>
			      	<th scope="col">Unidades vendidas</th>
			      	<th scope="col">Unidades compradas</th>
			      	<th scope="col">Venta diaria</th>
					<th scope="col">Dias restantes</th>
			      	<th scope="col">Precio (Con IVA)</th>
			      	<th scope="col">Ultimo Lote</th>
			      	<th scope="col">Ultima Venta (En rango)</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';

		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["Id"];
			$IsIVA = $row["ConceptoImpuesto"];

			$sql8 = QExistenciaArticulo($IdArticulo,0);
			$result1 = sqlsrv_query($conn,$sql8);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
			$Existencia = $row1["Existencia"];

			echo '<tr>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';
			echo '<td align="left">'.$row["Descripcion"].'</td>';
			echo '<td align="center">'.intval($Existencia).'</td>';

			$Venta = intval($row["TotalUnidadesVendidasCliente"]);
			$VentaDiaria = VentaDiaria($Venta,$RangoDias);
			$DiasRestantes = DiasRestantes($Existencia,$VentaDiaria);

			$Precio = CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia);

			$sql9 = QUltimoLote($IdArticulo);
			$result2 = sqlsrv_query($conn,$sql9);
			$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
			$UltimoLote = $row2["UltimoLote"];

			$sql10 = QUltimaVenta($IdArticulo,$FInicial,$FFinal);
			$result3 = sqlsrv_query($conn,$sql10);
			$row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
			$UltimaVenta = $row3["UltimaVenta"];

			echo '<td align="center">'.$Venta.'</td>';
			echo '<td align="center">'.intval($row["TotalUnidadesCompradasProveedor"]).'</td>';
			echo '<td align="center">'.round($VentaDiaria,2).'</td>';
			echo '<td align="center">'.round($DiasRestantes,2).'</td>';
			echo '<td align="center">'." ".round($Precio,2)." ".SigVe.'</td>';
			
			if(($UltimoLote)){
				echo '<td align="center">'.$UltimoLote->format('Y-m-d').'</td>';
			}
			else{
				echo '<td align="center"> - </td>';
			}

			if(($UltimaVenta)){
				echo '<td align="center">'.$UltimaVenta->format('Y-m-d').'</td>';
			}
			else{
				echo '<td align="center"> - </td>';
			}

			echo '</tr>';
		}
		echo '
	  		</tbody>
		</table>';

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}
	/*****************************************************************************/
	/************************ REPORTE 7 CATALOGO PROVEEDOR ***********************/
	/*
		TITULO: ReporteCatalogoProveedor
		PARAMETROS: [$SedeConnection] sede donde se hara la conexion
					[$IdProveedor] ID del proveedor a buscar
					[$NombreProveedor] Nombre del proveedor a buscar
		FUNCION: Armar el reporte catalogo de proveedor
		RETORNO: No aplica
	 */
	function ReporteCatalogoProveedor($SedeConnection,$IdProveedor,$NombreProveedor){

		$conn = ConectarSmartpharma($SedeConnection);

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		$sql1 = QfacturaProveedor($IdProveedor);
		$sql2 = QDetalleFactura();
		$sql3 = QCatalogoProductosProveedor();

		sqlsrv_query($conn,$sql1);
		sqlsrv_query($conn,$sql2);
		$result = sqlsrv_query($conn,$sql3);

		echo '
		<div class="input-group md-form form-sm form-1 pl-0">
		  <div class="input-group-prepend">
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
		  </div>
		  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
		</div>
		<br/>

		<table class="table table-striped table-bordered col-12 sortable">
			<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Proveedor</th>
			    </tr>
		  	</thead>
		  	<tbody>
		  	<tr>
	  	';
		echo '<td>'.$NombreProveedor.'</td>';
	  	echo '
	  		</tr>
	  		</tbody>
		</table>';

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
		
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				echo '<tr>';
				echo '<td align="left">'.$row["CodigoArticulo"].'</td>';
				echo '<td align="left">'.$row["Descripcion"].'</td>';
				echo '</tr>';
	  	}
	  	echo '
	  		</tbody>
		</table>';

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}
	/*
		TITULO: ReporteCatalogoProveedor
		PARAMETROS: [$SedeConnection] sede donde se hara la conexion
					[$IdProveedor] ID del proveedor a buscar
					[$NombreProveedor] Nombre del proveedor a buscar
		FUNCION: Armar el reporte catalogo de proveedor
		RETORNO: No aplica
	 */
	function ReporteCatalogoProveedorR($SedeConnection,$IdProveedor,$NombreProveedor,$FInicial,$FFinal,$DiasPedido){

		$FFinalImpresion= $FFinal;
		$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

		$conn = ConectarSmartpharma($SedeConnection);

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		$sql1 = QfacturaProveedor($IdProveedor);
		$sql2 = QDetalleFactura();
		$sql3 = QCatalogoProductosProveedor();

		sqlsrv_query($conn,$sql1);
		sqlsrv_query($conn,$sql2);
		$result = sqlsrv_query($conn,$sql3);

		$RangoDias = RangoDias($FInicial,$FFinal);

		echo '
		<div class="input-group md-form form-sm form-1 pl-0">
		  <div class="input-group-prepend">
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
		  </div>
		  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
		</div>
		<br/>
		';
		echo'<h6 align="center">Pedido en base a: '.$DiasPedido.' dias </h6>';
		echo'<h6 align="center">Periodo desde el '.$FInicial.' al '.$FFinalImpresion.' </h6>';

		echo '
		<table class="table table-striped table-bordered col-12 sortable">
			<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Proveedor</th>
			    </tr>
		  	</thead>
		  	<tbody>
		  	<tr>
	  	';
		echo '<td>'.$NombreProveedor.'</td>';
	  	echo '
	  		</tr>
	  		</tbody>
		</table>';

		echo'
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Codigo</th>
			      	<th scope="col">Descripcion</th>
			      	<th scope="col">Producto Unico</th>
			      	<th scope="col">Precio (Con IVA)</th>
			      	<th scope="col">Existencia</th>
			      	<th scope="col">Unidades vendidas</th>
			      	<th scope="col">Unidades compradas</th>
			      	<th scope="col">Venta diaria</th>
			      	<th scope="col">Dias restantes</th>
			      	<th scope="col">Ultima Venta (En rango)</th>
			      	<th scope="col">Pedir</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';

		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["Id"];
			$IsIVA = $row["ConceptoImpuesto"];

			$sql4 = QExistenciaArticulo($IdArticulo,0);
			$result1 = sqlsrv_query($conn,$sql4);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
			$Existencia = $row1["Existencia"];

				echo '<tr>';
				echo '<td align="left">'.$row["CodigoArticulo"].'</td>';
				echo '<td align="left">'.$row["Descripcion"].'</td>';
			
			$Unico = ProductoUnico($conn,$IdArticulo,$IdProveedor);
				echo '<td align="center">'.$Unico.'</td>';

			$Precio = CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia);
				echo '<td align="center">'." ".round($Precio,2)." ".SigVe.'</td>';
				echo '<td align="center">'.intval($Existencia).'</td>';

			$sql5 = QTablaTemp(5);
			$sql6 = QUnidadesVendidasClienteId($FInicial,$FFinal,$IdArticulo);
			$sql7 = QUnidadesDevueltaClienteId($FInicial,$FFinal,$IdArticulo);
			$sql8 = QUnidadesCompradasProveedorId($FInicial,$FFinal,$IdArticulo);
			$sql9 = QUnidadesReclamoProveedorId($FInicial,$FFinal,$IdArticulo);
			$sql10 = QIntegracionProductosVendidos();

			sqlsrv_query($conn,$sql5);
			sqlsrv_query($conn,$sql6);
			sqlsrv_query($conn,$sql7);
			sqlsrv_query($conn,$sql8);
			sqlsrv_query($conn,$sql9);
			sqlsrv_query($conn,$sql10);

			$result2 = sqlsrv_query($conn,'SELECT * FROM TablaTemp4');
			$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
			$TotalUnidadesVendidasCliente = ($row2["UnidadesVendidasCliente"]-$row2["UnidadesDevueltaCliente"]);
			$TotalUnidadesCompradasProveedor = ($row2["UnidadesCompradasProveedor"]-$row2["UnidadesReclamoProveedor"]);

				echo '<td align="center">'.intval($TotalUnidadesVendidasCliente).'</td>';
				echo '<td align="center">'.intval($TotalUnidadesCompradasProveedor).'</td>';

			$VentaDiaria = VentaDiaria($TotalUnidadesVendidasCliente,$RangoDias);
			$DiasRestantes = DiasRestantes($Existencia,$VentaDiaria);

				echo '<td align="center">'.round($VentaDiaria,2).'</td>';
				echo '<td align="center">'.round($DiasRestantes,2).'</td>';

			$sql11 = QUltimaVenta($IdArticulo,$FInicial,$FFinal);
			$result3 = sqlsrv_query($conn,$sql11);
			$row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
			$UltimaVenta = $row3["UltimaVenta"];

			if(($UltimaVenta)){
				echo '<td align="center">'.$UltimaVenta->format('Y-m-d').'</td>';
			}
			else{
				echo '<td align="center"> - </td>';
			}

			$CantidadPedido = CantidadPedido($VentaDiaria,$DiasPedido,$Existencia);

				echo '<td align="center">'.intval($CantidadPedido).'</td>';

			echo '</tr>';
	  	}
	  	echo '
	  		</tbody>
		</table>';

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}
	/****************************************************************************/
	/********************* REPORTE 8 ACTUALIZAR TROQUEL *************************/
	/*
		TITULO: ReporteProveedorFactura
		PARAMETROS: [$SedeConnection] sede donde se hara la conexion
					[$IdProveedor] ID del proveedor a buscar
					[$NombreProveedor] Nombre del proveedor a buscar
		FUNCION:  Arma la lista de factura por proveedores
		RETORNO: no aplica
	 */
	function ReporteProveedorFactura($SedeConnection,$IdProveedor,$NombreProveedor){
		
		$conn = ConectarSmartpharma($SedeConnection);

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		$sql1 = QfacturaProveedorToquel($IdProveedor);
		$result = sqlsrv_query($conn,$sql1);

		echo '
		<div class="input-group md-form form-sm form-1 pl-0">
		  <div class="input-group-prepend">
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
		  </div>
		  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
		</div>
		<br/>
		';

		echo '
		<table class="table table-striped table-bordered col-12 sortable">
			<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Proveedor</th>
			    </tr>
		  	</thead>
		  	<tbody>
		  	<tr>
	  	';
		echo '<td>'.$NombreProveedor.'</td>';
	  	echo '
	  		</tr>
	  		</tbody>
		</table>';

		echo'
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Numero de Factura</th>
			    	<th scope="col">Fecha Documento</th>
			    	<th scope="col">Usuario Auditor</th>
			    	<th scope="col">Seleccion</th>
			    </tr>
		  	</thead>
		  	<tbody>
		 ';

		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			
			echo '<tr>';
			echo '<td align="center">'.$row["NumeroFactura"].'</td>';
			echo '<td align="center">'.$row["FechaDocumento"]->format('Y-m-d').'</td>';
			echo '<td align="center">'.$row["Auditoria_Usuario"].'</td>';
			echo '
			<td align="center">
		    <form autocomplete="off" action="">
		        <input id="SEDE" name="SEDE" type="hidden" value="';
		            print_r($SedeConnection);
		            echo'">
	           
	          	<input type="submit" value="Selecionar" class="btn btn-outline-success">
	            
	            <input id="IdFact" name="IdFact" type="hidden" value="'.$row["FacturaId"].'">
		        <input id="IdProv" name="IdProv" type="hidden" value="'.$IdProveedor.'">
		        <input id="NombreProv" name="NombreProv" type="hidden" value="'.$NombreProveedor.'">
		      </form>
		      <br>
		    ';

			echo '</tr>';
		}
	  	echo '
	  		</tbody>
		</table>';

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}
	/*
		TITULO: ReporteFacturaArticulo
		PARAMETROS: [$SedeConnection] sede donde se hara la conexion
					[$IdProveedor] ID del proveedor a buscar
					[$NombreProveedor] Nombre del proveedor a buscar
					[$IdFatura] id de la factura a buscar
		FUNCION: arma la lista de articulos por factura
		RETORNO: no aplica
	 */
	function ReporteFacturaArticulo($SedeConnection,$IdProveedor,$NombreProveedor,$IdFatura){
		
		$conn = ConectarSmartpharma($SedeConnection);

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		$sql1 = QFacturaArticulo($IdFatura);
		$result = sqlsrv_query($conn,$sql1);

		$sqlNFact = QNumeroFactura($IdFatura);
		$resultNFact = sqlsrv_query($conn,$sqlNFact);
		$rowNFact = sqlsrv_fetch_array($resultNFact,SQLSRV_FETCH_ASSOC);
		$NumeroFactura = $rowNFact["NumeroFactura"];

		echo '
		<div class="input-group md-form form-sm form-1 pl-0">
		  <div class="input-group-prepend">
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
		  </div>
		  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
		</div>
		<br/>
		';

		echo '
		<table class="table table-striped table-bordered col-12 sortable">
			<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Numero de Factura</th>
			    	<th scope="col">Proveedor</th>
			    </tr>
		  	</thead>
		  	<tbody>
		  	<tr>
	  	';
	  	echo '<td>'.$NumeroFactura.'</td>';
		echo '<td>'.$NombreProveedor.'</td>';
	  	echo '
	  		</tr>
	  		</tbody>
		</table>';

		echo'
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Codigo</th>
			    	<th scope="col">Descripcion</th>
			    	<th scope="col">Seleccion</th>
			    </tr>
		  	</thead>
		  	<tbody>
		 ';

		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			
			echo '<tr>';
			echo '<td align="center">'.$row["CodigoArticulo"].'</td>';
			echo '<td align="center">'.$row["Descripcion"].'</td>';
			echo '
			<td align="center">
		    <form autocomplete="off" action="">
		        <input id="SEDE" name="SEDE" type="hidden" value="';
		            print_r($SedeConnection);
		            echo'">
	           
	          	<input type="submit" value="Selecionar" class="btn btn-outline-success">
	            
	            <input id="IdArt" name="IdArt" type="hidden" value="'.$row["Id"].'">
	            <input id="IdFact" name="IdFact" type="hidden" value="'.$IdFatura.'">
		        <input id="IdProv" name="IdProv" type="hidden" value="'.$IdProveedor.'">
		        <input id="NombreProv" name="NombreProv" type="hidden" value="'.$NombreProveedor.'">
		      </form>
		      <br>
		    ';

			echo '</tr>';
		}
	  	echo '
	  		</tbody>
		</table>';

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}
	/*
		TITULO: ReporteArticuloTroquel
		PARAMETROS: [$SedeConnection] sede donde se hara la conexion
					[$IdProveedor] ID del proveedor a buscar
					[$NombreProveedor] Nombre del proveedor a buscar
					[$IdFatura] id de la factura
					[$IdArticulo] ide del articulo
		FUNCION: arma la lista del troquel segun el articulo
		RETORNO: no aplica
	 */
	function ReporteArticuloTroquel($SedeConnection,$IdProveedor,$NombreProveedor,$IdFatura,$IdArticulo){
		
		$conn = ConectarSmartpharma($SedeConnection);

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		$sql1 = QArticulo($IdArticulo);
		$result = sqlsrv_query($conn,$sql1);

		$sql2 = QTroquelArticuloFactura($IdFatura,$IdArticulo);
		$result2 = sqlsrv_query($conn,$sql2);
		$row2 = sqlsrv_fetch_array( $result2, SQLSRV_FETCH_ASSOC);

		$sqlNFact = QNumeroFactura($IdFatura);
		$resultNFact = sqlsrv_query($conn,$sqlNFact);
		$rowNFact = sqlsrv_fetch_array($resultNFact,SQLSRV_FETCH_ASSOC);
		$NumeroFactura = $rowNFact["NumeroFactura"];

		echo '
		<div class="input-group md-form form-sm form-1 pl-0">
		  <div class="input-group-prepend">
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
		  </div>
		  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
		</div>
		<br/>
		';

		echo '
		<table class="table table-striped table-bordered col-12 sortable">
			<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Numero de Factura</th>
			    	<th scope="col">Proveedor</th>
			    </tr>
		  	</thead>
		  	<tbody>
		  	<tr>
	  	';
	  	echo '<td>'.$NumeroFactura.'</td>';
		echo '<td>'.$NombreProveedor.'</td>';
	  	echo '
	  		</tr>
	  		</tbody>
		</table>';

		echo'
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Codigo</th>
			    	<th scope="col">Descripcion</th>
			    	<th scope="col">Precio Troquelado Actual</th>
			    	<th scope="col">Precio Troquelado Nuevo</th>
			    	<th scope="col">Seleccion</th>
			    </tr>
		  	</thead>
		  	<tbody>
		 ';

		while($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) {
			
			echo '<tr>';
			echo '<td align="center">'.$row["CodigoArticulo"].'</td>';
			echo '<td align="center">'.$row["Descripcion"].'</td>';
			echo '<td align="center">'.round($row2["M_PrecioTroquelado"],2).' '.SigVe.'</td>';

			echo '
				<form autocomplete="off" action="">
				<td align="center">
					<input id="TroquelN" type="text" name="TroquelN" placeholder="Ingrese el nuevo precio troquelado" required autofocus>
				</td>';

			echo '
			<td align="center">
		        <input id="SEDE" name="SEDE" type="hidden" value="';
	            print_r($SedeConnection);
	            echo'">
	           
	          	<input type="submit" value="Selecionar" class="btn btn-outline-success">
	            
	            <input id="IdArt" name="IdArt" type="hidden" value="'.$row["Id"].'">
	            <input id="IdFact" name="IdFact" type="hidden" value="'.$IdFatura.'">
		        <input id="IdProv" name="IdProv" type="hidden" value="'.$IdProveedor.'">
		        <input id="NombreProv" name="NombreProv" type="hidden" value="'.$NombreProveedor.'">
		    </td>
		      </form>
		      <br>
		    ';

			echo '</tr>';
		}
	  	echo '
	  		</tbody>
		</table>';

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}
	/*
		TITULO: ReporteTroquel
		PARAMETROS: [$SedeConnection] sede donde se hara la conexion
					[$IdProveedor] ID del proveedor a buscar
					[$NombreProveedor] Nombre del proveedor a buscar
					[$IdFatura] id de la factura
					[$IdArticulo] ide del articulo
					[$PrecioTroquel] precio del troquel del articulo
		FUNCION: arma la lista definitiva con el troquel actualizado
		RETORNO: no aplica
	 */
	function ReporteTroquel($SedeConnection,$IdProveedor,$NombreProveedor,$IdFatura,$IdArticulo,$PrecioTroquel){
		
		$conn = ConectarSmartpharma($SedeConnection);

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		$sql2 = QActualizarTroquel($IdFatura,$IdArticulo,$PrecioTroquel);
		sqlsrv_query($conn,$sql2);
		
		$sql1 = QArticulo($IdArticulo);
		$result = sqlsrv_query($conn,$sql1);

		$sql3 = QTroquelArticuloFactura($IdFatura,$IdArticulo);
		$result3 = sqlsrv_query($conn,$sql3);
		$row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC);

		$sqlNFact = QNumeroFactura($IdFatura);
		$resultNFact = sqlsrv_query($conn,$sqlNFact);
		$rowNFact = sqlsrv_fetch_array($resultNFact,SQLSRV_FETCH_ASSOC);
		$NumeroFactura = $rowNFact["NumeroFactura"];

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

		echo '
		<table class="table table-striped table-bordered col-12 sortable">
			<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Numero de Factura</th>
			    	<th scope="col">Proveedor</th>
			    </tr>
		  	</thead>
		  	<tbody>
		  	<tr>
	  	';
	  	echo '<td>'.$NumeroFactura.'</td>';
		echo '<td>'.$NombreProveedor.'</td>';
	  	echo '
	  		</tr>
	  		</tbody>
		</table>';

		echo'
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Codigo</th>
			    	<th scope="col">Descripcion</th>
			    	<th scope="col">Precio Troquelado</th>
			    </tr>
		  	</thead>
		  	<tbody>
		 ';

		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			echo '<tr>';
			echo '<td align="center">'.$row["CodigoArticulo"].'</td>';
			echo '<td align="center">'.$row["Descripcion"].'</td>';
			echo '<td align="center">'.round($row3["M_PrecioTroquelado"],2).' '.SigVe.'</td>';
			echo '</tr>';
		}
	  	echo '
	  		</tbody>
		</table>';

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);
		
		sqlsrv_close($conn);
	}

	/********************** REPORTE 9 PRODUCTOS PARA SURTIR **********************/
	/*
		TITULO: ReporteProductosParaSurtir
		PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
					[$FInicial] Fecha inicial del rango donde se buscara
					[$FFinal] Fecha final del rango donde se buscara
		FUNCION: Arma una lista con los productos surtidos y sus fechas
		RETORNO: No aplica
	 */
	function ReporteProductosParaSurtir($SedeConnection,$FInicial,$FFinal) {
		$conn = ConectarSmartpharma($SedeConnection);

		$FFinalImpresion = $FFinal;
		$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		$sql1 = QUnidadesCompradasProveedor($FInicial,$FFinal);
		sqlsrv_query($conn, $sql1);
		
		$result = sqlsrv_query($conn,"SELECT * FROM TablaTemp2 ORDER BY FechaRegistro DESC");

		echo '
			<div class="input-group md-form form-sm form-1 pl-0">
				<div class="input-group-prepend">
					<span class="input-group-text purple lighten-3" id="basic-text1">
						<i class="fas fa-search text-white" aria-hidden="true"></i>
		        	</span>
		  		</div>
		  		<input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
			</div>
			<br/>
		';

		echo'<h6 align="center">
				Periodo desde el '.$FInicial.' al '.$FFinalImpresion.
			' </h6>';

		echo'
			<table class="table table-striped table-bordered col-12 sortable" id="myTable">
				<thead class="thead-dark">
				    <tr>
				    	<th scope="col">Codigo</th>
				      	<th scope="col">Descripcion</th>
				      	<th scope="col">Existencia</th>
				      	<th scope="col">Ultimo Lote (En Rango)</th>
				      	<th scope="col">Tiempo en Tienda (Dias)</th>
				    </tr>
		  		</thead>
		  		<tbody>
		';
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["Id"];
			$FechaRegistro = $row["FechaRegistro"];
			
			$sql2 = QExistenciaArticulo($IdArticulo,0);
			$result2 = sqlsrv_query($conn,$sql2);
			$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
			$Existencia = $row2["Existencia"];

			$sql3 = QTiempoEnTienda($IdArticulo);
			$result3 = sqlsrv_query($conn,$sql3);
			$row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
			$TiempoTienda = $row3["TiempoTienda"];

			if($Existencia > 0){
				echo '<tr>';
				echo '<td align="left">'.$row["CodigoArticulo"].'</td>';
				echo '<td align="left">'.$row["Descripcion"].'</td>';
				echo '<td align="center">'.intval($Existencia).'</td>';
				echo '<td align="center">'.($FechaRegistro)->format("Y-m-d").'</td>';
				echo '<td align="center">'.$TiempoTienda.'</td>';
				echo '</tr>';
			}
	  	}
	  	echo '
	  		</tbody>
		</table>';

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}

	/*****************************************************************************/
	/************************ REPORTE 10 ANALITICO DE PRECIOS *******************/
	/*
		TITULO: ReporteAnaliticoDePrecios
		PARAMETROS : [$SedeConnection] Siglas de la sede para la conexion
					 [$IdArticuloQ] Id del articulo a buscar
		FUNCION: Armar una tabla de historico de compra del articulo
		RETORNO: No aplica
	 */
	function ReporteAnaliticoDePrecios($SedeConnection,$IdArticulo){
		$conn = ConectarSmartpharma($SedeConnection);

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

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
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
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
			      	<th scope="col">Precio (Con IVA)</td>
			      	<th scope="col">Tasa actual</td>
			      	<th scope="col">Precio en divisa (Con IVA)</td>
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
			    	<th scope="col">Origen</th>
			    	<th scope="col">Detalles</th>
			      	<th scope="col">Fecha de documento</th>
			      	<th scope="col">Almacen</th>
			      	<th scope="col">Existencia</th>
			      	<th scope="col">Costo bruto (Sin IVA)</th>
			      	<th scope="col">Tasa en historico</th>
					<th scope="col">Costo en divisa (Sin IVA)</th>
					<th scope="col">Precio troquel</th>
			    	<th scope="col">Responsable</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';

		while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
				echo '<tr>';
				echo '<td align="center">-</td>';
				echo '<td align="center">-</td>';
				echo '<td align="center">'.$row2["FechaDocumento"]->format('Y-m-d').'</td>';
				echo '<td align="center">-</td>';
				echo '<td align="center">-</td>';
				echo '<td align="center">'." ".round($row2["M_PrecioCompraBruto"],2)." ".SigVe.'</td>';

				$FechaD = $row2["FechaDocumento"]->format('Y-m-d');
				$Tasa = TasaFecha($FechaD);
				
				if($Tasa != 0){
					echo '<td align="center">'." ".$Tasa." ".SigVe.'</td>';
					echo '<td align="center">'." ".round(($row2["M_PrecioCompraBruto"]/$Tasa),2)." ".SigDolar.'</td>';
				}
				else{
					echo '<td align="center">0.00 '.SigVe.'</td>';
					echo '<td align="center">0.00 '.SigDolar.'</td>';
				}
				echo '<td align="center">-</td>';
				echo '<td align="center">-</td>';
				echo '</tr>';
	  	}
	  	echo '
	  		</tbody>
		</table>';

		$sql = QTablaTemp(ClenTable);
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}

?>