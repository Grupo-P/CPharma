<style> 
  .barrido{
    	text-decoration: none;
    	transition: width 1s, height 1s, transform 1s;
    }
  .barrido:hover{
    	text-decoration: none;
      transition: width 1s, height 1s, transform 1s;
      transform: translate(20px,0px);
    }
</style>

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

		$sql = QCleanTable('CP_QFRegProveedor');
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
		  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
		</div>
		<br/>
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">#</th>
			      	<th scope="col">Proveedor</th>
			      	<th scope="col">Ultimo registro</th>
			      	<th scope="col">Dias sin facturar</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';
		$contador = 1;
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdProveedor = $row['Id'];
			$NombreProveedor = $row['Nombre'];

			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
			echo 
			'<td align="left" class="barrido">
			<a href="/reporte7?Nombre='.$NombreProveedor.'&Id='.$IdProveedor.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
				.$NombreProveedor.
			'</a>
			</td>';

			echo '<td align="center">'.($row['FechaRegistro'])->format('Y-m-d').'</td>';
			echo '<td align="center">'.$row['RangoDias'].'</td>';
			echo '</tr>';
			$contador++;
	  	}
	  	echo '
	  		</tbody>
		</table>';

		$sql3 = QCleanTable('CP_QFRegProveedor');
		sqlsrv_query($conn,$sql3);
		
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
		$CodigoBarra = CodigoBarra($conn,$IdArticulo);
		$Dolarizado = ProductoDolarizado($conn,$IdArticulo);
		$Gravado = ProductoGravado($IsIVA);
		$TasaActual = TasaFecha(date('Y-m-d'));
		$Descripcion = $row["Descripcion"];

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
			      	<th scope="col">Precio</br>(Con IVA)</td>
			      	<th scope="col">Codigo de Barra</td>
			      	<th scope="col">Gravado?</td>
			      	<th scope="col">Dolarizado?</td>
			      	<th scope="col">Tasa actual</td>
			      	<th scope="col">Precio en divisa</br>(Con IVA)</td>
			    </tr>
		  	</thead>
		  	<tbody>
	  	';
		echo '<tr>';
		echo '<td>'.$row["CodigoArticulo"].'</td>';

		echo 
			'<td align="left" class="barrido">
			<a href="/reporte10?Descrip='.$Descripcion.'&Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
				.$Descripcion.
			'</a>
			</td>';

		echo '<td align="center">'.intval($Existencia).'</td>';
		echo '<td align="center">'." ".round($Precio,2)." ".SigVe.'</td>';
		echo '<td align="center">'.$CodigoBarra.'</td>';
		echo '<td align="center">'.$Gravado.'</td>';
		echo '<td align="center">'.$Dolarizado.'</td>';

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
			    	<th scope="col">#</th>
			    	<th scope="col">Proveedor</th>
			      	<th scope="col">Fecha de documento</th>
			      	<th scope="col">Cantidad recibida</th>
			      	<th scope="col">Costo bruto</br>(Sin IVA)</th>
			      	<th scope="col">Tasa en historico</th>
			      	<th scope="col">Costo bruto</br>(Sin IVA) HOY</th>
					<th scope="col">Costo en divisa</br>(Sin IVA)</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';
		$contador = 1;
		while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
				echo '<tr>';
				echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
				echo '<td>'.$row2["Nombre"].'</td>';
				echo '<td align="center">'.$row2["FechaDocumento"]->format('Y-m-d').'</td>';
				echo '<td align="center">'.intval($row2['CantidadRecibidaFactura']).'</td>';
				echo '<td align="center">'." ".round($row2["M_PrecioCompraBruto"],2)." ".SigVe.'</td>';

				$FechaD = $row2["FechaDocumento"]->format('Y-m-d');
				$Tasa = TasaFecha($FechaD);
				
				if($Tasa != 0){
					$CostoDivisa = round(($row2["M_PrecioCompraBruto"]/$Tasa),2);
					

					echo '<td align="center">'." ".$Tasa." ".SigVe.'</td>';

					if($TasaActual!=0){
						$CostoBrutoHoy = $CostoDivisa*$TasaActual;
						echo '<td align="center">'." ".$CostoBrutoHoy." ".SigVe.'</td>';
					}
					else{
						echo '<td align="center">0.00 '.SigVe.'</td>';
					}

					echo '<td align="center">'." ".$CostoDivisa." ".SigDolar.'</td>';
				}
				else{
					echo '<td align="center">0.00 '.SigVe.'</td>';
					echo '<td align="center">0.00 '.SigVe.'</td>';
					echo '<td align="center">0.00 '.SigDolar.'</td>';
				}
				echo '</tr>';
			$contador++;
	  	}
	  	echo '
	  		</tbody>
		</table>';

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

		$sql = QCleanTable('CP_QUnidadesVendidasCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesDevueltaCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesCompradasProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesReclamoProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosVendidos');
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
			    	<th scope="col">#</th>
			    	<th scope="col">Codigo</th>
			      	<th scope="col">Descripcion</th>
			      	<th scope="col">Existencia</th>
			      	<th scope="col">Tipo</th>
			      	<th scope="col">Total de Venta</th>
			      	<th scope="col">Veces Facturado</th>			      
			      	<th scope="col">Unidades vendidas</th>			      	
			      	<th scope="col">Unidades Compradas</th>		      			      
			      	<th scope="col">Venta diaria</th>
			      	<th scope="col">Dias restantes</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';
		$contador = 1;
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["Id"];

			$sql7 = QExistenciaArticulo($IdArticulo,0);
			$result1 = sqlsrv_query($conn,$sql7);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
			$Existencia = $row1["Existencia"];

			$Tipo = ProductoMedicina($conn,$IdArticulo);

			$sql = QCleanTable('CP_QVentasParcial');
			sqlsrv_query($conn,$sql);
			$sql = QCleanTable('CP_QDevolucionParcial');
			sqlsrv_query($conn,$sql);

			$sql8 = QVentasParcial($FInicial,$FFinal,$IdArticulo);
			sqlsrv_query($conn,$sql8);
			$sql9 = QDevolucionParcial($FInicial,$FFinal,$IdArticulo);
			sqlsrv_query($conn,$sql9);

			$sql10 = QIntegracionVentasParcial();
			$result2 = sqlsrv_query($conn,$sql10);
			$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
			$TotalVenta = $row2["TotalVenta"];

			$sql11 = QIntegracionDevolucionParcial();
			$result3 = sqlsrv_query($conn,$sql11);
			$row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
			$TotalDevolucion = $row3["TotalDevolucion"];

			$TotalVenta = $TotalVenta - $TotalDevolucion;

			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';

			echo 
			'<td align="left" class="barrido">
			<a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
				.$row["Descripcion"].
			'</a>
			</td>';

			echo '<td align="center">'.intval($Existencia).'</td>';
			echo '<td align="center">'.$Tipo.'</td>';
			echo '<td align="center">'." ".intval(round($TotalVenta,2))." ".SigVe.'</td>';
			
			echo 
			'<td align="center" class="barrido">
			<a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
				.$row["TotalVecesVendidasCliente"].
			'</a>
			</td>';

			$Venta = intval($row["TotalUnidadesVendidasCliente"]);
			$VentaDiaria = VentaDiaria($Venta,$RangoDias);
			$DiasRestantes = DiasRestantes($Existencia,$VentaDiaria);

			echo 
			'<td align="center" class="barrido">
			<a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
				.$Venta.
			'</a>
			</td>';

			echo 
			'<td align="center" class="barrido">
			<a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
				.intval($row["TotalUnidadesCompradasProveedor"]).
			'</a>
			</td>';

			echo '<td align="center">'.round($VentaDiaria,2).'</td>';
			echo '<td align="center">'.round($DiasRestantes,2).'</td>';
			echo '</tr>';
		$contador++;
	  	}
	  	echo '
	  		</tbody>
		</table>';
		
		$sql = QCleanTable('CP_QUnidadesVendidasCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesDevueltaCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesCompradasProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesReclamoProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosVendidos');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QVentasParcial');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QDevolucionParcial');
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

		$sql = QCleanTable('CP_QUnidadesVendidasCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesDevueltaCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesCompradasProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesReclamoProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosVendidos');
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
			    	<th scope="col">#</th>
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
		$contador = 1;
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
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';
			
			echo 
			'<td align="left" class="barrido">
			<a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
				.$row["Descripcion"].
			'</a>
			</td>';

			echo '<td align="center">'.intval($Existencia).'</td>';
			echo '<td align="center">'." ".round($Precio,2)." ".SigVe.'</td>';
			
			if($Existencia==0){
				echo '<td align="center">0</td>';
			}
			else{
				echo '<td align="center">'.$row4["TiempoTienda"].'</td>';
			}
			
			echo 
			'<td align="center" class="barrido">
			<a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
				.intval($row["TotalVecesVendidasCliente"]).
			'</a>
			</td>';

			$Venta = intval($row["TotalUnidadesVendidasCliente"]);
			$VentaDiaria = VentaDiaria($Venta,$RangoDias);
			$DiasRestantes = DiasRestantes($Existencia,$VentaDiaria);
			
			echo 
			'<td align="center" class="barrido">
			<a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
				.$Venta.
			'</a>
			</td>';

			echo 
			'<td align="center" class="barrido">
			<a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
				.intval($row["TotalUnidadesCompradasProveedor"]).
			'</a>
			</td>';

			echo '<td align="center">'.round($VentaDiaria,2).'</td>';
			echo '<td align="center">'.round($DiasRestantes,2).'</td>';
			echo '</tr>';
		$contador++;
	  	}
	  	echo '
	  		</tbody>
		</table>';
		
		$sql = QCleanTable('CP_QUnidadesVendidasCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesDevueltaCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesCompradasProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesReclamoProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosVendidos');
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

		$sql = QCleanTable('CP_QUnidadesVendidasCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesDevueltaCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesCompradasProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesReclamoProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosVendidos');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosFalla');
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
			    	<th scope="col">#</th>
			    	<th scope="col">Codigo</th>
			      	<th scope="col">Descripcion</th>
			      	<th scope="col">Tipo</th>
			      	<th scope="col">Existencia</th>
			      	<th scope="col">Unidades vendidas</th>
			      	<th scope="col">Total de Venta</th>
			      	<th scope="col">Ultima Venta</th>
			      	<th scope="col">Dias en Falla</th>
			      	<th scope="col">Ultimo Proveedor</th>			      	
			    </tr>
		  	</thead>
		  	<tbody>
		';
		$contador = 1;
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["Id"];

			$sql7 = QExistenciaArticulo($IdArticulo,0);
			$result1 = sqlsrv_query($conn,$sql7);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
			$Existencia = $row1["Existencia"];

			$Tipo = ProductoMedicina($conn,$IdArticulo);

			$sql = QCleanTable('CP_QVentasParcial');
			sqlsrv_query($conn,$sql);
			$sql = QCleanTable('CP_QDevolucionParcial');
			sqlsrv_query($conn,$sql);

			$sql8 = QVentasParcial($FInicial,$FFinal,$IdArticulo);
			sqlsrv_query($conn,$sql8);
			$sql9 = QDevolucionParcial($FInicial,$FFinal,$IdArticulo);
			sqlsrv_query($conn,$sql9);

			$sql10 = QIntegracionVentasParcial();
			$result2 = sqlsrv_query($conn,$sql10);
			$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
			$TotalVenta = $row2["TotalVenta"];

			$sql11 = QIntegracionDevolucionParcial();
			$result3 = sqlsrv_query($conn,$sql11);
			$row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
			$TotalDevolucion = $row3["TotalDevolucion"];

			$TotalVenta = $TotalVenta - $TotalDevolucion;

			$sql12 = QUltimaVentaSR($IdArticulo);
			$result4 = sqlsrv_query($conn,$sql12);
			$row4 = sqlsrv_fetch_array($result4,SQLSRV_FETCH_ASSOC);
			$UltimaVentaSR = $row4["UltimaVenta"];

			$sql13 = QUltimoProveedor($IdArticulo);
			$result5 = sqlsrv_query($conn,$sql13);
			$row5 = sqlsrv_fetch_array($result5,SQLSRV_FETCH_ASSOC);
			$UltimoProveedor = $row5["Nombre"];
			$IdProveedor = $row5["Id"];

			$Hoy = new DateTime('now');			
			$DiasEnFalla = ValidarFechas($UltimaVentaSR->format("Y-m-d"),$Hoy->format("Y-m-d"));

			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';

			echo 
			'<td align="left" class="barrido">
			<a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
				.$row["Descripcion"].
			'</a>
			</td>';

			echo '<td align="center">'.$Tipo.'</td>';
			echo '<td align="center">'.intval($Existencia).'</td>';

			$Venta = intval($row["TotalUnidadesVendidasCliente"]);

			echo
			'<td align="center" class="barrido">
			<a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
				.$Venta.
			'</a>
			</td>';

			echo '<td align="center">'." ".intval(round($TotalVenta,2))." ".SigVe.'</td>';

			if(($UltimaVentaSR)){
				echo '<td align="center">'.$UltimaVentaSR->format('Y-m-d').'</td>';
			}
			else{
				echo '<td align="center"> - </td>';
			}

			echo '<td align="center">'.intval($DiasEnFalla).'</td>';

			echo 
			'<td align="left" class="barrido">
			<a href="/reporte7?Nombre='.$UltimoProveedor.'&Id='.$IdProveedor.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
				.$UltimoProveedor.
			'</a>
			</td>';

			echo '</tr>';
			$contador++;
	  	}
	  	echo '
	  		</tbody>
		</table>';
		
		$sql = QCleanTable('CP_QUnidadesVendidasCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesDevueltaCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesCompradasProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesReclamoProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosVendidos');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosFalla');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QVentasParcial');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QDevolucionParcial');
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
	function ReportePedidoProductos($SedeConnection,$Descripcion,$FInicial,$FFinal,$DiasPedido){

		$conn = ConectarSmartpharma($SedeConnection);

		$FFinalImpresion= $FFinal;
		$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

		$sql = QCleanTable('CP_QUnidadesVendidasCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesDevueltaCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesCompradasProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesReclamoProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosVendidos');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosFalla');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QArticuloDescLike');
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

		echo'<h6 align="center">Pedido en base a: '.$DiasPedido.' dias </h6>';
		echo'<h6 align="center">Periodo desde el '.$FInicial.' al '.$FFinalImpresion.' </h6>';

		echo'
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">#</th>
			    	<th scope="col">Codigo</th>
			      	<th scope="col">Descripcion</th>
			      	<th scope="col">Codigo de Barra</th>
			      	<th scope="col">Existencia</th>
			      	<th scope="col">Unidades vendidas</th>
			      	<th scope="col">Unidades compradas</th>
			      	<th scope="col">Venta diaria</th>
					<th scope="col">Dias restantes</th>
			      	<th scope="col">Precio (Con IVA)</th>
			      	<th scope="col">Ultimo Lote</th>
			      	<th scope="col">Ultima Venta (En Rango)</th>
			      	<th scope="col">Ultima Venta</th>
			      	<th scope="col">Pedir</th>			   
			    </tr>
		  	</thead>
		  	<tbody>
		';
		$contador = 1;
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["Id"];
			$IsIVA = $row["ConceptoImpuesto"];

			$sql8 = QExistenciaArticulo($IdArticulo,0);
			$result1 = sqlsrv_query($conn,$sql8);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
			$Existencia = $row1["Existencia"];

			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';
			
			echo 
			'<td align="left" class="barrido">
			<a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
				.$row["Descripcion"].
			'</a>
			</td>';

			$CodigoBarra = CodigoBarra($conn,$IdArticulo);
				echo '<td align="center">'.$CodigoBarra.'</td>';

			echo '<td align="center">'.intval($Existencia).'</td>';

			$Venta = intval($row["TotalUnidadesVendidasCliente"]);
			$VentaDiaria = VentaDiaria($Venta,$RangoDias);
			$DiasRestantes = DiasRestantes($Existencia,$VentaDiaria);

			$Precio = CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia);

			$sql9 = QUltimoLote($IdArticulo);
			$result2 = sqlsrv_query($conn,$sql9);
			$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
			$UltimoLote = $row2["UltimoLote"];

			$sql10 = QUltimaVentaCR($IdArticulo,$FInicial,$FFinal);
			$result3 = sqlsrv_query($conn,$sql10);
			$row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
			$UltimaVentaCR = $row3["UltimaVenta"];

			$sql11 = QUltimaVentaSR($IdArticulo);
			$result4 = sqlsrv_query($conn,$sql11);
			$row4 = sqlsrv_fetch_array($result4,SQLSRV_FETCH_ASSOC);
			$UltimaVentaSR = $row4["UltimaVenta"];				

			echo
			'<td align="center" class="barrido">
			<a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
				.$Venta.
			'</a>
			</td>';

			echo
			'<td align="center" class="barrido">
			<a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
				.intval($row["TotalUnidadesCompradasProveedor"]).
			'</a>
			</td>';

			echo '<td align="center">'.round($VentaDiaria,2).'</td>';
			echo '<td align="center">'.round($DiasRestantes,2).'</td>';
			echo '<td align="center">'." ".round($Precio,2)." ".SigVe.'</td>';
			
			if(($UltimoLote)){
				echo '<td align="center">'.$UltimoLote->format('Y-m-d').'</td>';
			}
			else{
				echo '<td align="center"> - </td>';
			}

			if(($UltimaVentaCR)){
				echo '<td align="center">'.$UltimaVentaCR->format('Y-m-d').'</td>';
			}
			else{
				echo '<td align="center"> - </td>';
			}

			if(($UltimaVentaSR)){
				echo '<td align="center">'.$UltimaVentaSR->format('Y-m-d').'</td>';
			}
			else{
				echo '<td align="center"> - </td>';
			}

			$CantidadPedido = CantidadPedido($VentaDiaria,$DiasPedido,$Existencia);

				echo '<td align="center">'.intval($CantidadPedido).'</td>';

			echo '</tr>';
		$contador++;
		}
		echo '
	  		</tbody>
		</table>';

		$sql = QCleanTable('CP_QUnidadesVendidasCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesDevueltaCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesCompradasProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesReclamoProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosVendidos');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosFalla');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QArticuloDescLike');
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

		$sql = QCleanTable('QFacturasProducto');
    	sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QfacturaProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QDetalleFactura');
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
			    	<th scope="col">#</th>
			    	<th scope="col">Codigo</th>
			      	<th scope="col">Descripcion</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';
		$contador = 1;
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["Id"];
			echo '<tr>';
			echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';
			
			echo 
			'<td align="left" class="barrido">
			<a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
				.$row["Descripcion"].
			'</a>
			</td>';

			echo '</tr>';
		$contador++;
	  	}
	  	echo '
	  		</tbody>
		</table>';

		$sql = QCleanTable('CP_QfacturaProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QDetalleFactura');
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

		$sql = QCleanTable('QFacturasProducto');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QfacturaProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QDetalleFactura');
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
			    	<th scope="col">#</th>
			    	<th scope="col">Codigo</th>
			      	<th scope="col">Descripcion</th>
			      	<th scope="col">Codigo de Barra</td>
			      	<th scope="col">Producto Unico</th>
			      	<th scope="col">Precio (Con IVA)</th>
			      	<th scope="col">Existencia</th>
			      	<th scope="col">Unidades vendidas</th>
			      	<th scope="col">Unidades compradas</th>
			      	<th scope="col">Venta diaria</th>
			      	<th scope="col">Dias restantes</th>
			      	<th scope="col">Ultima Venta (En rango)</th>
			      	<th scope="col">Ultima Venta</th>
			      	<th scope="col">Pedir</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';
		$contador = 1;
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["Id"];
			$IsIVA = $row["ConceptoImpuesto"];

			$sql4 = QExistenciaArticulo($IdArticulo,0);
			$result1 = sqlsrv_query($conn,$sql4);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
			$Existencia = $row1["Existencia"];

				echo '<tr>';
				echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
				echo '<td align="left">'.$row["CodigoArticulo"].'</td>';
				
				echo 
				'<td align="left" class="barrido">
				<a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
					.$row["Descripcion"].
				'</a>
				</td>';
			$CodigoBarra = CodigoBarra($conn,$IdArticulo);
				echo '<td align="center">'.$CodigoBarra.'</td>';
		
			$Unico = ProductoUnico($conn,$IdArticulo,$IdProveedor);
				echo '<td align="center">'.$Unico.'</td>';
		
			$Precio = CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia);
				echo '<td align="center">'." ".round($Precio,2)." ".SigVe.'</td>';
				echo '<td align="center">'.intval($Existencia).'</td>';

			$sql = QCleanTable('CP_QUnidadesVendidasClienteId');
			sqlsrv_query($conn,$sql);
			$sql = QCleanTable('CP_QUnidadesDevueltaClienteId');
			sqlsrv_query($conn,$sql);
			$sql = QCleanTable('CP_QUnidadesCompradasProveedorId');
			sqlsrv_query($conn,$sql);
			$sql = QCleanTable('CP_QUnidadesReclamoProveedorId');
			sqlsrv_query($conn,$sql);
			$sql = QCleanTable('CP_QIntegracionProductosVendidosId');
			sqlsrv_query($conn,$sql);		

			$sql6 = QUnidadesVendidasClienteId($FInicial,$FFinal,$IdArticulo);
			$sql7 = QUnidadesDevueltaClienteId($FInicial,$FFinal,$IdArticulo);
			$sql8 = QUnidadesCompradasProveedorId($FInicial,$FFinal,$IdArticulo);
			$sql9 = QUnidadesReclamoProveedorId($FInicial,$FFinal,$IdArticulo);
			$sql10 = QIntegracionProductosVendidosId();
	
			sqlsrv_query($conn,$sql6);
			sqlsrv_query($conn,$sql7);
			sqlsrv_query($conn,$sql8);
			sqlsrv_query($conn,$sql9);
			sqlsrv_query($conn,$sql10);

			$result2 = sqlsrv_query($conn,'SELECT * FROM CP_QIntegracionProductosVendidosId');
			$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
			$TotalUnidadesVendidasCliente = ($row2["UnidadesVendidasCliente"]-$row2["UnidadesDevueltaCliente"]);
			$TotalUnidadesCompradasProveedor = ($row2["UnidadesCompradasProveedor"]-$row2["UnidadesReclamoProveedor"]);

			echo
			'<td align="center" class="barrido">
			<a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
				.intval($TotalUnidadesVendidasCliente).
			'</a>
			</td>';

			echo
			'<td align="center" class="barrido">
			<a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
				.intval($TotalUnidadesCompradasProveedor).
			'</a>
			</td>';

			$VentaDiaria = VentaDiaria($TotalUnidadesVendidasCliente,$RangoDias);
			$DiasRestantes = DiasRestantes($Existencia,$VentaDiaria);

				echo '<td align="center">'.round($VentaDiaria,2).'</td>';
				echo '<td align="center">'.round($DiasRestantes,2).'</td>';

			$sql11 = QUltimaVentaCR($IdArticulo,$FInicial,$FFinal);
			$result3 = sqlsrv_query($conn,$sql11);
			$row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
			$UltimaVenta = $row3["UltimaVenta"];

			$sql12 = QUltimaVentaSR($IdArticulo);
			$result4 = sqlsrv_query($conn,$sql12);
			$row4 = sqlsrv_fetch_array($result4,SQLSRV_FETCH_ASSOC);
			$UltimaVentaSR = $row4["UltimaVenta"];

			if(($UltimaVenta)){
				echo '<td align="center">'.$UltimaVenta->format('Y-m-d').'</td>';
			}
			else{
				echo '<td align="center"> - </td>';
			}

			if(($UltimaVentaSR)){
				echo '<td align="center">'.$UltimaVentaSR->format('Y-m-d').'</td>';
			}
			else{
				echo '<td align="center"> - </td>';
			}

			$CantidadPedido = CantidadPedido($VentaDiaria,$DiasPedido,$Existencia);

				echo '<td align="center">'.intval($CantidadPedido).'</td>';

			echo '</tr>';
		$contador++;
	  	}
	  	echo '
	  		</tbody>
		</table>';

		$sql = QCleanTable('QFacturasProducto');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QfacturaProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QDetalleFactura');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesVendidasClienteId');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesDevueltaClienteId');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesCompradasProveedorId');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesReclamoProveedorId');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosVendidosId');
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
			    	<th scope="col">#</th>
			    	<th scope="col">Numero de Factura</th>
			    	<th scope="col">Fecha Documento</th>
			    	<th scope="col">Usuario Auditor</th>
			    	<th scope="col">Seleccion</th>
			    </tr>
		  	</thead>
		  	<tbody>
		 ';
	 	$contador = 1;
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			
			echo '<tr>';
			echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
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
		$contador++;
		}
	  	echo '
	  		</tbody>
		</table>';

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
			    	<th scope="col">#</th>
			    	<th scope="col">Codigo</th>
			    	<th scope="col">Descripcion</th>
			    	<th scope="col">Seleccion</th>
			    </tr>
		  	</thead>
		  	<tbody>
		 ';
		 $contador = 1;
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			
			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
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
		$contador++;
		}
	  	echo '
	  		</tbody>
		</table>';

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
		
		$sql = QCleanTable('CP_QUnidadesCompradasProveedor');
		sqlsrv_query($conn,$sql);

		$sql1 = QUnidadesCompradasProveedor($FInicial,$FFinal);
		sqlsrv_query($conn, $sql1);
		
		$result = sqlsrv_query($conn,"SELECT * FROM CP_QUnidadesCompradasProveedor ORDER BY FechaRegistro DESC");

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
				    	<th scope="col">#</th>
				    	<th scope="col">Codigo</th>
				      	<th scope="col">Descripcion</th>
				      	<th scope="col">Existencia</th>
				      	<th scope="col">Ultimo Lote</br>(En Rango)</th>
				      	<th scope="col">Tiempo en Tienda</br>(Dias)</th>
				    </tr>
		  		</thead>
		  		<tbody>
		';
		$contador = 1;
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
				echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
				echo '<td align="left">'.$row["CodigoArticulo"].'</td>';

				echo 
				'<td align="left" class="barrido">
				<a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
					.$row["Descripcion"].
				'</a>
				</td>';
				
				echo '<td align="center">'.intval($Existencia).'</td>';
				echo '<td align="center">'.($FechaRegistro)->format("Y-m-d").'</td>';
				echo '<td align="center">'.$TiempoTienda.'</td>';
				echo '</tr>';
			}
		$contador++;
	  	}
	  	echo '
	  		</tbody>
		</table>';

		$sql = QCleanTable('CP_QUnidadesCompradasProveedor');
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
	function ReporteAnaliticoDePrecios($SedeConnection,$IdArticulo) {
		$conn=ConectarSmartpharma($SedeConnection);

		$sql=QArticulo($IdArticulo);
		sqlsrv_query($conn,$sql);
		$result=sqlsrv_query($conn,$sql);
		$row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

		$sql1=QExistenciaArticulo($IdArticulo,0);
		$result1=sqlsrv_query($conn,$sql1);
		$row1=sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

		$IsIVA=$row["ConceptoImpuesto"];
		$Existencia=$row1["Existencia"];

		$Precio=CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia);

		$Dolarizado = ProductoDolarizado($conn,$IdArticulo);

		$TasaActual=TasaFecha(date('Y-m-d'));

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
			      	<th scope="col">Descripcion</th>
			      	<th scope="col">Existencia</th>
			      	<th scope="col">Precio (Con IVA)</th>
			      	<th scope="col">Dolarizado</th>
			      	<th scope="col">Tasa actual</th>
			      	<th scope="col">Precio en divisa (Con IVA)</th>
			    </tr>
		  	</thead>
		  	<tbody>
	  	';
		echo '<tr>';
		echo '<td>'.$row["CodigoArticulo"].'</td>';
		
		echo 
			'<td align="left" class="barrido">
			<a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
				.$row["Descripcion"].
			'</a>
			</td>';

		echo '<td align="center">'.intval($Existencia).'</td>';
		echo '<td align="center">'." ".round($Precio,2)." ".SigVe.'</td>';
		echo '<td align="center">'.$Dolarizado.'</td>';

		if($TasaActual!=0){
			echo '<td align="center">'." ".$TasaActual." ".SigVe.'</td>';
			echo '<td align="center">'.
					round(($Precio/$TasaActual),2).
					" ".SigDolar.
				 '</td>';
		}
		else{
			echo '<td align="center">0.00 '.SigVe.'</td>';
			echo '<td align="center">0.00 '.SigDolar.'</td>';
		}
		echo '
				</tr>
	  		</tbody>
		</table>';

		$sql2=QLoteAlmacenAnalitico($IdArticulo);
		$result2=sqlsrv_query($conn,$sql2);

		echo'
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">#</th>
			    	<th scope="col">Origen</th>
			    	<th scope="col">Detalles</th>
			      	<th scope="col">Fecha creacion de lote</th>
			      	<th scope="col">Cantidad recibida</th>
			      	<th scope="col">Almacen</th>
			      	<th scope="col">Existencia</th>
			      	<th scope="col">Costo bruto (Sin IVA)</th>
			      	<th scope="col">Tasa en historico</th>
					<th scope="col">Costo en divisa (Sin IVA)</th>
			    	<th scope="col">Responsable</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';
		$contador = 1;
		while($row2=sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC)) {
			$FechaVariable=$row2["FechaLote"]->format("Y-m-d");
			$PrecioBruto=$row2["M_PrecioCompraBruto"];
			echo '<tr>';
			//VALIDACION PARA CASO DE COMPRA
				if($row2["InvCausaId"]==1) {
					echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
					echo '<td align="center">Compra</td>';

					$sql3=QProveedorYCantidadComprada2($IdArticulo,$FechaVariable,$PrecioBruto);
					$result3=sqlsrv_query($conn,$sql3);
					$row3=sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

					echo '<td align="center">'.$row3["Nombre"].'</td>';
					echo '<td align="center">'.$FechaVariable.'</td>';
					echo '<td align="center">'.intval($row3["CantidadRecibidaFactura"]).'</td>';
				}
				else {
					echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
					echo '<td align="center">Inventario</td>';
					echo '<td align="center">'.$row2["Descripcion"].'</td>';
					echo '<td align="center">'.$FechaVariable.'</td>';

					$sql3=QProveedorYCantidadComprada($IdArticulo,$FechaVariable);
					$result3=sqlsrv_query($conn,$sql3);
					$row3=sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

					echo '<td align="center">'.intval($row3["CantidadRecibidaFactura"]).'</td>';
				}
				echo '<td align="center">'.$row2["CodigoAlmacen"].'</td>';
				echo '<td align="center">'.intval($row2["Existencia"]).'</td>';
				echo '<td align="center">'.
						round($PrecioBruto,2)." ".SigVe.
					 '</td>';

				$TasaVariable=TasaFecha($FechaVariable);
				if($TasaVariable!=0){
					echo '<td align="center">'.$TasaVariable." ".SigVe.'</td>';
					echo '<td align="center">'.
							round(($PrecioBruto/$TasaVariable),2).
							" ".SigDolar.
						 '</td>';
				}
				else{
					echo '<td align="center">0.00 '.SigVe.'</td>';
					echo '<td align="center">0.00 '.SigDolar.'</td>';
				}
				echo '<td align="center">'.$row2["Responsable"].'</td>';
			echo '</tr>';
		$contador++;
		}

	  	echo '
	  		</tbody>
		</table>';

		sqlsrv_close($conn);
	}

	/*****************************************************************************/
	/************************ REPORTE 11 CARTA DE COMPROMISO *******************/

	/*
		TITULO: CartaDeCompromiso
		PARAMETROS: [$SedeConnection] sede donde se hara la conexion
					[$IdProveedor] ID del proveedor a buscar
					[$NombreProveedor] Nombre del proveedor a buscar
					[$IdFatura] id de la factura
					[$IdArticulo] ide del articulo
		FUNCION: arma la lista del troquel segun el articulo
		RETORNO: no aplica
	 */
	function CartaDeCompromiso($SedeConnection,$IdProveedor,$NombreProveedor,$IdFatura,$IdArticulo) {
		$conn = ConectarSmartpharma($SedeConnection);

		$sql = QCartaDeCompromiso($IdProveedor,$IdFatura,$IdArticulo);
		$result = sqlsrv_query($conn,$sql);
		$result1 = sqlsrv_query($conn,$sql);
		$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

		$NumeroFactura = $row["NumeroFactura"];
		$CodigoArticulo = $row["CodigoArticulo"];
		$Articulo=$row["Descripcion"];
		$FechaDocumento=$row["FechaDocumento"];
		$FechaRecepcion=$row["FechaRecepcion"];
		$FechaVencimiento=$row["FechaVencimiento"];
		$Lote=$row["NumeroLote"];
		$LoteFabricante=$row["LoteFabricante"];
		$LoteFabricanteAnterior="";

		$flag = array();
		$flag2 = array();//Array LoteFabricante
		$i = 0;
		while($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
			$Lote1=$row1["NumeroLote"];
			$LoteFabricante1=$row1["LoteFabricante"];
			$FechaVencimiento1=$row1["FechaVencimiento"];

			$flag[$i] = utf8_encode($Lote1);

			//LoteFabricante
			$flag2[$i] = utf8_encode($LoteFabricante1);

			if(is_null($FechaVencimiento1)){
				$flag[$i+1] = utf8_encode('');
				$flag2[$i+1] = utf8_encode('');
			}
			else{
				$flag[$i+1] = utf8_encode($FechaVencimiento1->format('Y-m-d'));
				$flag2[$i+1] = utf8_encode($FechaVencimiento1->format('Y-m-d'));
			}
 			
 			$i+=2;
	  	}
		$ArrFinal = json_encode($flag);
		$ArrFinal2 = json_encode($flag2);
		?>

		<script>
			function capturarLote(e,loteNulo) {
				if(isNaN(e.value) || loteNulo == '1') {
					var ArrJs2 = eval(<?php echo $ArrFinal2 ?>);
					var indice = ArrJs2.indexOf(e.value);
					var indiceFecha = indice+1;
					var FechaVencimientoJs = ArrJs2[indiceFecha];
				}
				else {
					var ArrJs = eval(<?php echo $ArrFinal ?>);
					var indice = ArrJs.indexOf(e.value);
					var indiceFecha = indice+1;
					var FechaVencimientoJs = ArrJs[indiceFecha];
				}

				if(FechaVencimientoJs == '') {
					document.getElementById('input_fechaV').value = '00/00/0000';
				}
				else {
					document.getElementById('input_fechaV').value = formato(FechaVencimientoJs);
				}

				document.getElementById('fecha_vencimiento').value = FechaVencimientoJs;
			}

			/**
			 * Convierte un texto de la forma 2017-01-10 a la forma
			 * 10/01/2017
			 *
			 * @param {string} texto Texto de la forma 2017-01-10
			 * @return {string} texto de la forma 10/01/2017
			 *
			 */
			function formato(texto){
			  return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
			}
		</script>

		<?php
		echo '
		<div class="input-group md-form form-sm form-1 pl-0">
			<div class="input-group-prepend">
		    	<span class="input-group-text purple lighten-3" id="basic-text1">
					<i class="fas fa-search text-white" aria-hidden="true"></i>
		    	</span>
		  	</div>

		  	<input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
		</div><br/>';

		echo '
		<form autocomplete="off" action="">
		<table class="table table-striped table-bordered col-12 sortable">
			<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Numero de Factura</th>
			    	<th scope="col">Proveedor</th>
			    	<th scope="col">Lote</th>
			    </tr>
		  	</thead>

		  	<tbody>
		  		<tr>
		  			<td>'.$NumeroFactura.'</td>
					<td>'.$NombreProveedor.'</td>
					<td>';
					if($LoteFabricante == null) { echo '
						<select name="lote" id="lote" style="width:100%;" onchange="capturarLote(this,1);">
							<option value="'.$Lote.'">'
								.$Lote.
							'</option>';
					}
					else { echo '
						<select name="lote" id="lote" style="width:100%;" onchange="capturarLote(this,2);">
							<option value="'.$LoteFabricante.'">'
								.$LoteFabricante.
							'</option>';
						$LoteFabricanteAnterior = $LoteFabricante;
					}

				while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
					$Lote=$row["NumeroLote"];
					$LoteFabricante=$row["LoteFabricante"];

					if($LoteFabricante == null) { echo '
						<option value="'.$Lote.'">'
							.$Lote.
						'</option>';
					}
					else if($LoteFabricanteAnterior != $LoteFabricante) { echo '
						<option value="'.$LoteFabricante.'">'
							.$LoteFabricante.
						'</option>';
					}
					
				}
			  	   echo '</select>
			  		</td>
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
				<tr> 
					<td align="center">'.$CodigoArticulo.'</td>
					<td align="center">'.$Articulo.'</td>
			 	</tr>
	  		</tbody>
		</table>';

		echo '
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Fecha de factura</th>
			    	<th scope="col">Fecha de recepcion (Articulo)</th>
			    	<th scope="col">Fecha de vencimiento (Articulo)</th>
			    	<th scope="col">Fecha tope (Carta compromiso)</th>
			    </tr>
		  	</thead>

		  	<tbody>
				<tr>
					<td align="center">
						<input type="text" value="'.$FechaDocumento->format('d/m/Y').'" disabled>
					</td>
					<td align="center">
						<input type="text" value="'.$FechaRecepcion->format('d/m/Y').'" disabled>
					</td>';
					if(!is_null($FechaVencimiento)) {
						echo '
					<td align="center">
						<input type="text" id="input_fechaV" value="'.$FechaVencimiento->format('d/m/Y').'" disabled>
					</td>';
					}
					else {
						echo '
					<td align="center">
						<input type="text" id="input_fechaV" value="00/00/0000" disabled>
					</td>';
					}
					echo '
					<td align="center">
						<input id="fecha_tope" name="fecha_tope" type="date" required>
					</td>
			 	</tr>
	  		</tbody>
		</table>';

		echo '
			<table class="table table-striped table-bordered col-12 sortable">
				<thead class="thead-dark">
				    <tr>
				    	<th scope="col">Causa</th>
				    	<th scope="col">Nota</th>
				    </tr>
			  	</thead>
		  	
			  	<tbody>
			  		<tr>
						<td>
							<textarea name="causa" id="causa" class="form-control" rows="4" required></textarea>
						</td>

	  					<td>
	  						<textarea name="nota" id="nota" class="form-control" rows="4" required></textarea>
	  					</td>
	  				</tr>
	  			</tbody>
			</table>

			<div class="text-center">
				<input id="articulo" name="articulo" type="hidden" value="'.$Articulo.'">
				<input id="proveedor" name="proveedor" type="hidden" value="'.$NombreProveedor.'">
				<input id="SEDE" name="SEDE" type="hidden" value="'.$SedeConnection.'">
				<input id="IdProv" name="IdProv" type="hidden" value="'.$IdProveedor.'">
				<input id="IdFact" name="IdFact" type="hidden" value="'.$IdFatura.'">
				<input id="IdArt" name="IdArt" type="hidden" value="'.$IdArticulo.'">
				<input id="fecha_documento" name="fecha_documento" type="hidden" value="'.$FechaDocumento->format('Y-m-d').'">
				<input id="fecha_recepcion" name="fecha_recepcion" type="hidden" value="'.$FechaRecepcion->format('Y-m-d').'">';

					if(!is_null($FechaVencimiento)) {
						echo '
						<input id="fecha_vencimiento" name="fecha_vencimiento" type="hidden" value="'.$FechaVencimiento->format('Y-m-d').'">';
					}
					else {
						echo '
						<input id="fecha_vencimiento" name="fecha_vencimiento" type="hidden" value="'.$FechaVencimiento.'">';
					}
					echo '
				<input type="submit" value="Guardar" class="btn btn-outline-success">
			</div>
		</form>';

		sqlsrv_close($conn);
	}
	/*****************************************************************************/
	/************************ REPORTE 12 DETALLE DE MOVIMIENTO *******************/
	/*
		TITULO: ReporteDetalleDeMovimiento
		PARAMETROS: [$SedeConnection] sede donde se hara la conexion
					[$FInicial] Fecha inicial del rango a buscar
					[$FFinal] Fecha final del rango a buscar
					[$IdArticulo] ide del articulo
		FUNCION: arma la lista del troquel segun el articulo
		RETORNO: no aplica
	 */
	function ReporteDetalleDeMovimiento($SedeConnection,$FInicial,$FFinal,$IdArticulo) {
		$conn=ConectarSmartpharma($SedeConnection);

		$sql=QArticulo($IdArticulo);
		sqlsrv_query($conn,$sql);
		$result=sqlsrv_query($conn,$sql);
		$row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

		$sql1=QExistenciaArticulo($IdArticulo,0);
		$result1=sqlsrv_query($conn,$sql1);
		$row1=sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

		$IsIVA=$row["ConceptoImpuesto"];
		$Existencia=$row1["Existencia"];

		$Precio=CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia);

		$Dolarizado = ProductoDolarizado($conn,$IdArticulo);

		$TasaActual=TasaFecha(date('Y-m-d'));

		//-------------------- Inicio Rangos --------------------
		$FechaDeHoy = new DateTime('now');
		$Diferencia = ValidarFechas($FechaDeHoy->format('Y-m-d'),$FFinal);
		$FFinalImpresion = $FFinal;

		if($Diferencia == 0) {
			$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));
		}

		$sql = QCleanTable('CP_QUnidadesVendidasCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesDevueltaCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesCompradasProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesReclamoProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosVendidos');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosFalla');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QArticuloDescLike');
		sqlsrv_query($conn,$sql);

		$sql1 = QUnidadesVendidasCliente($FInicial,$FFinal);
		$sql2 = QUnidadesDevueltaCliente($FInicial,$FFinal);
		$sql3 = QUnidadesCompradasProveedor($FInicial,$FFinal);
		$sql4 = QUnidadesReclamoProveedor($FInicial,$FFinal);
		$sql5 = QIntegracionProductosVendidos();
		$sql6 = QIntegracionProductosFalla();
		$sql61 = QArticuloDescLike($row["Descripcion"],0);
		$sql7 = QPedidoProductos();
		
		sqlsrv_query($conn,$sql1);
		sqlsrv_query($conn,$sql2);
		sqlsrv_query($conn,$sql3);
		sqlsrv_query($conn,$sql4);
		sqlsrv_query($conn,$sql5);
		sqlsrv_query($conn,$sql6);
		sqlsrv_query($conn,$sql61);
		$result2 = sqlsrv_query($conn,$sql7);

		$RangoDias = RangoDias($FInicial,$FFinal);
		//-------------------- Fin Rangos --------------------
		
		$row2=sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
		$Venta = intval($row2["TotalUnidadesVendidasCliente"]);
		$VentaDiaria = VentaDiaria($Venta,$RangoDias);
		$DiasRestantes = DiasRestantes($Existencia,$VentaDiaria);

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
		<br/>';

		echo'<h6 align="center">Periodo desde el '.$FInicial.' al '.$FFinalImpresion.' </h6>';

		echo '
		<table class="table table-striped table-bordered col-12 sortable">
			<thead class="thead-dark">
			    <tr>
			    	<th scope="col">Codigo</th>
			      	<th scope="col">Descripcion</th>
			      	<th scope="col">Existencia</th>
			      	<th scope="col">Unidades vendidas</th>
			      	<th scope="col">Dias restantes</th>
			      	<th scope="col">Precio (Con IVA)</th>
			      	<th scope="col">Dolarizado</th>
			      	<th scope="col">Tasa actual</th>
			      	<th scope="col">Precio en divisa (Con IVA)</th>
			    </tr>
		  	</thead>

		  	<tbody>
	  	';

		echo '
				<tr>
					<td>'.$row["CodigoArticulo"].'</td>
		
				<td align="left" class="barrido">
					<a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
						.$row["Descripcion"].
					'</a>
					</td>

					<td align="center">'.intval($Existencia).'</td>
					<td align="center">'.$Venta.'</td>
					<td align="center">'.$DiasRestantes.'</td>
					<td align="center">'." ".round($Precio,2)." ".SigVe.'</td>
					<td align="center">'.$Dolarizado.'</td>
		';

		if($TasaActual!=0){
			echo '
					<td align="center">'." ".$TasaActual." ".SigVe.'</td>
					<td align="center">'.round(($Precio/$TasaActual),2)." ".SigDolar.'</td>
			';
		}
		else{
			echo '
					<td align="center">0.00 '.SigVe.'</td>
					<td align="center">0.00 '.SigDolar.'</td>
			';
		}
		echo '
				</tr>
	  		</tbody>
		</table>';

		echo '<br>';

		echo '
		<h6 align="center">Resumen de movimientos</h6>

		<table class="table table-striped table-bordered col-12 sortable">
			<thead class="thead-dark">
			    <tr>
			    	<th scope="col">#</th>
			    	<th scope="col" class="text-center">Fecha</th>
			      	<th scope="col" class="text-center">Tipo de movimiento</th>
			      	<th scope="col" class="text-center">Cantidad</th>
			    </tr>
		  	</thead>

		  	<tbody>';

		  	$sql9 = QCleanTable('CP_QResumenDeMovimientos');
			sqlsrv_query($conn,$sql9);
			$sql10 = QIntegracionResumenDeMovimientos($IdArticulo,$FInicial,$FFinal);
			sqlsrv_query($conn,$sql10);
			$sql11 = QAgruparDetalleDeMovimientos();
			$result4 = sqlsrv_query($conn,$sql11);

			$contador = 1;
		  	while($row4 = sqlsrv_fetch_array($result4,SQLSRV_FETCH_ASSOC)) {
		  		echo '
	  			<tr>
	  				<td align="center"><strong>'.intval($contador).'</strong></td>
					<td align="center">'.$row4["FechaMovimiento"].'</td>
			      	<td align="center">'.utf8_encode($row4["Movimiento"]).'</td>
			      	<td align="center">'.intval($row4["Cantidad"]).'</td>
			    </tr>
		  		';
		  	$contador++;
		  	}
		echo '
			</tbody>
		</table>
	  	';

		echo '<br>';

		echo '
		<h6 align="center">Detalle de movimientos</h6>

		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
			<thead class="thead-dark">
			    <tr>
			    	<th scope="col">#</th>
			    	<th scope="col" class="text-center">Fecha</th>
			      	<th scope="col" class="text-center">Hora</th>
			      	<th scope="col" class="text-center">Tipo de movimiento</th>
			      	<th scope="col" class="text-center">Cantidad</th>
			    </tr>
		  	</thead>

		  	<tbody>
		';

	  	$sql8=QDetalleDeMovimiento($IdArticulo,$FInicial,$FFinal);
		$result3=sqlsrv_query($conn,$sql8);

		$contador = 1;
	  	while($row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC)) {
	  		echo '
	  			<tr>
	  				<td align="center"><strong>'.intval($contador).'</strong></td>
			    	<td align="center">'.$row3["FechaMovimiento"]->format("d/m/Y").'</td>';

			$Hora = date('h:i a',strtotime($row3["FechaMovimiento"]->format("H:m:s")));

			echo '					
			      	<td align="center">'.$Hora.'</td>
			      	<td align="center">'.utf8_encode($row3["Movimiento"]).'</td>
			      	<td align="center">'.intval($row3["Cantidad"]).'</td>
			    </tr>
			';
		$contador++;
	  	}

	  	echo '
	  		</tbody>
	  	</table>
	  	';

		$sql = QCleanTable('CP_QUnidadesVendidasCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesDevueltaCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesCompradasProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesReclamoProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosVendidos');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosFalla');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QArticuloDescLike');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QResumenDeMovimientos');
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}
	/*****************************************************************************/
	/************************ REPORTE 13 PRODUCTOS POR FALLAR ***********************/
	/*
		TITULO: ReporteProductosPorFallar
		PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion		
					[$FInicial] Fecha inicial del rango donde se buscara
					[$FFinal] Fecha final del rango donde se buscara
					[$Existencia] Existencia para validar
		FUNCION: Arma una lista de productos por fallar
		RETORNO: No aplica
	 */
	function ReporteProductosPorFallar($SedeConnection,$FInicial,$FFinal,$ExistenciaB){

		$conn = ConectarSmartpharma($SedeConnection);

		$FFinalImpresion= $FFinal;
		$FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

		$sql = QCleanTable('CP_QUnidadesVendidasCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesDevueltaCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesCompradasProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesReclamoProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosVendidos');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosFalla');
		sqlsrv_query($conn,$sql);

		$sql1 = QUnidadesVendidasCliente($FInicial,$FFinal);
		$sql2 = QUnidadesDevueltaCliente($FInicial,$FFinal);
		$sql3 = QUnidadesCompradasProveedor($FInicial,$FFinal);
		$sql4 = QUnidadesReclamoProveedor($FInicial,$FFinal);
		$sql5 = QIntegracionProductosVendidos();
		$sql6 = QIntegracionProductosFalla();
		$sql7 = QProductosPorFallar($ExistenciaB);
		
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
		echo'<h6 align="center">Existencia menor a: '.$ExistenciaB.' productos </h6>';
		echo'<h6 align="center">Periodo desde el '.$FInicial.' al '.$FFinalImpresion.' </h6>';

		echo'
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">#</th>
			    	<th scope="col">Codigo</th>
			      	<th scope="col">Descripcion</th>
			      	<th scope="col">Tipo</th>
			      	<th scope="col">Existencia</th>
			      	<th scope="col">Unidades vendidas</th>
			      	<th scope="col">Total de Venta</th>
			      	<th scope="col">Ultima Venta</th>
			      	<th scope="col">Ultimo Proveedor</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';
		$contador = 1;
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row["Id"];

			$sql7 = QExistenciaArticulo($IdArticulo,0);
			$result1 = sqlsrv_query($conn,$sql7);
			$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
			$Existencia = $row1["Existencia"];

			$Tipo = ProductoMedicina($conn,$IdArticulo);

			$sql = QCleanTable('CP_QVentasParcial');
			sqlsrv_query($conn,$sql);
			$sql = QCleanTable('CP_QDevolucionParcial');
			sqlsrv_query($conn,$sql);

			$sql8 = QVentasParcial($FInicial,$FFinal,$IdArticulo);
			sqlsrv_query($conn,$sql8);
			$sql9 = QDevolucionParcial($FInicial,$FFinal,$IdArticulo);
			sqlsrv_query($conn,$sql9);

			$sql10 = QIntegracionVentasParcial();
			$result2 = sqlsrv_query($conn,$sql10);
			$row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
			$TotalVenta = $row2["TotalVenta"];

			$sql11 = QIntegracionDevolucionParcial();
			$result3 = sqlsrv_query($conn,$sql11);
			$row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
			$TotalDevolucion = $row3["TotalDevolucion"];

			$TotalVenta = $TotalVenta - $TotalDevolucion;

			$sql12 = QUltimaVentaSR($IdArticulo);
			$result4 = sqlsrv_query($conn,$sql12);
			$row4 = sqlsrv_fetch_array($result4,SQLSRV_FETCH_ASSOC);
			$UltimaVentaSR = $row4["UltimaVenta"];

			$sql13 = QUltimoProveedor($IdArticulo);
			$result5 = sqlsrv_query($conn,$sql13);
			$row5 = sqlsrv_fetch_array($result5,SQLSRV_FETCH_ASSOC);
			$UltimoProveedor = $row5["Nombre"];
			$IdProveedor = $row5["Id"];

			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
			echo '<td align="left">'.$row["CodigoArticulo"].'</td>';

			echo 
			'<td align="left" class="barrido">
			<a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
				.$row["Descripcion"].
			'</a>
			</td>';

			echo '<td align="center">'.$Tipo.'</td>';
			echo '<td align="center">'.intval($Existencia).'</td>';

			$Venta = intval($row["TotalUnidadesVendidasCliente"]);
			
			echo 
			'<td align="center" class="barrido">
			<a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
				.$Venta.
			'</a>
			</td>';

			echo '<td align="center">'." ".intval(round($TotalVenta,2))." ".SigVe.'</td>';

			if(($UltimaVentaSR)){
				echo '<td align="center">'.$UltimaVentaSR->format('Y-m-d').'</td>';
			}
			else{
				echo '<td align="center"> - </td>';
			}

			echo 
			'<td align="left" class="barrido">
			<a href="/reporte7?Nombre='.$UltimoProveedor.'&Id='.$IdProveedor.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
				.$UltimoProveedor.
			'</a>
			</td>';

			echo '</tr>';
			$contador++;
	  	}
	  	echo '
	  		</tbody>
		</table>';
		
		$sql = QCleanTable('CP_QUnidadesVendidasCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesDevueltaCliente');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesCompradasProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QUnidadesReclamoProveedor');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosVendidos');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QIntegracionProductosFalla');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QVentasParcial');
		sqlsrv_query($conn,$sql);
		$sql = QCleanTable('CP_QDevolucionParcial');
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}

	/*****************************************************************************/
	/********************** REPORTE 14 ARTICULOS DEVALUADOS **********************/
	/*
		TITULO: ReporteArticulosDevaluados
		PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion		
					[$FInicial] Fecha inicial del rango donde se buscara
		FUNCION: Arma una lista de productos por fallar
		RETORNO: No aplica
	 */
	function ReporteArticulosDevaluados($SedeConnection,$FInicial) {
		$conn = ConectarSmartpharma($SedeConnection);
		$Hoy = new DateTime('now');
		$Hoy = $Hoy->format('Y-m-d');
		$DiasSolicitados = ValidarFechas($FInicial,$Hoy);

		echo '
			<div class="input-group md-form form-sm form-1 pl-0">
				<div class="input-group-prepend">
					<span class="input-group-text purple lighten-3" id="basic-text1">
						<i class="fas fa-search text-white" aria-hidden="true"></i>
					</span>
				</div>

				<input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
			</div><br/>
		';

		echo'
			<table class="table table-striped table-bordered col-12 sortable" id="myTable">
			  	<thead class="thead-dark">
				    <tr>
				    	<th scope="col">#</th>
				    	<th scope="col">Codigo</th>
				      	<th scope="col">Descripcion</th>
				      	<th scope="col">Precio</th>
				      	<th scope="col">Existencia</th>
				      	<th scope="col">Valor lote</th>
				      	<th scope="col">Ultimo lote</th>
						<th scope="col">Tasa historico</th>
						<th scope="col">Precio en $</th>
						<th scope="col">Valor lote $</th>
						<th scope="col">Dias en tienda</th>
						<th scope="col">Ultimo proveedor</th>
				    </tr>
			  	</thead>

			  	<tbody>
		';

		$sql = QCleanTable('CP_ArticulosDevaluados');
		sqlsrv_query($conn,$sql);

		$contador = 1;
		$sql1 = QArticulosDevaluados($FInicial);
		sqlsrv_query($conn,$sql1);

		$sql2 = QFiltrarArticulosDevaluados();
		$result2 = sqlsrv_query($conn,$sql2);

		while($row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC)) {
			$IdArticulo = $row2["InvArticuloId"];
			$Dolarizado = ProductoDolarizado($conn,$IdArticulo);

			if($Dolarizado == 'NO') {
				$sql3 = QExistenciaArticulo($IdArticulo,0);
				$result3 = sqlsrv_query($conn,$sql3);
				$row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

				$IsIVA = $row2["ConceptoImpuesto"];
				$Existencia = $row3["Existencia"];
				$Precio = CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia);
				$ValorLote = $Precio * intval($Existencia);

				$sql4 = QUltimoLote($IdArticulo);
				$result4 = sqlsrv_query($conn,$sql4);
				$row4 = sqlsrv_fetch_array($result4,SQLSRV_FETCH_ASSOC);

				$UltimoLote = $row4["UltimoLote"];
				$UltimoLote = $UltimoLote->format('Y-m-d');
				$Tasa = TasaFecha($UltimoLote);
				$Descripcion = utf8_encode($row2["Descripcion"]);

				echo '
					<tr>
						<td align="center"><strong>'.intval($contador).'</strong></td>
				    	<td align="center">'.$row2["CodigoArticulo"].'</td>
				    	<td align="left" class="barrido">
				    		<a href="/reporte10?Descrip='.$Descripcion.'&Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
				    			.$Descripcion
				    		.'</a>
				    	</td>
				      	<td align="center">'." ".round($Precio,2)." ".SigVe.'</td>
				      	<td align="center">'.intval($Existencia).'</td>
				      	<td align="center">'." ".round($ValorLote,2)." ".SigVe.'</td>
				      	<td align="center">'.$UltimoLote.'</td>
				';

				if($Tasa!=0){
					$PrecioDolarizado = round(($Precio/$Tasa),2);
					$ValorLoteDolarizado = $PrecioDolarizado * intval($Existencia);

					echo '
						<td align="center">'." ".$Tasa." ".SigVe.'</td>
						<td align="center">'." ".$PrecioDolarizado." ".SigDolar.'</td>
						<td align="center">'." ".$ValorLoteDolarizado." ".SigDolar.'</td>
					';
				}
				else{
					echo '
						<td align="center">0.00 '.SigVe.'</td>
						<td align="center">0.00 '.SigDolar.'</td>
						<td align="center">0.00 '.SigDolar.'</td>
					';
				}

				$sql5 = QTiempoEnTienda($IdArticulo);
				$result5=sqlsrv_query($conn,$sql5);
				$row5=sqlsrv_fetch_array($result5,SQLSRV_FETCH_ASSOC);

				$sql6 = QUltimoProveedor($IdArticulo);
				$result6 = sqlsrv_query($conn,$sql6);
				$row6 = sqlsrv_fetch_array($result6,SQLSRV_FETCH_ASSOC);
				$NombreProveedor = utf8_encode($row6["Nombre"]);
				$IdProveedor = $row6["Id"];

				echo '
				      	<td align="center">'.$row5["TiempoTienda"].'</td>
				      	<td align="left" class="barrido">
				      		<a href="/reporte7?Nombre='.$NombreProveedor.'&Id='.$IdProveedor.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
				      			.$NombreProveedor
				      		.'</a>
				      	</td>
				    </tr>
				';

				$contador++;
			}
		}

		echo '

			  	</tbody>
			</table>
		';

		$sql = QCleanTable('CP_ArticulosDevaluados');
		sqlsrv_query($conn,$sql);

		sqlsrv_close($conn);
	}
?>