@extends('layouts.etiquetas')

@section('title')
    Orden de compra
@endsection

@section('content')
<style>
	table{
		border-collapse: collapse;
		text-align: center;
	}
	thead{
		border: 1px solid black;
		border-radius: 0px;
		background-color: #e3e3e3;
	}
	tbody{
		border: 1px solid black;
		border-radius: 0px;
	}
	td{
		border: 1px solid black;
		border-radius: 0px;
	}
	th{
		border: 1px solid black;
		border-radius: 0px;
	}
	.alinear-der{
		text-align: right;
		padding-right: 20px;
	}
	.alinear-izq{
		text-align: left;
		padding-left: 20px;
	}
	.aumento{
		font-size: 1.2em;
		text-transform: uppercase;
	}
</style>

	<?php
    use Illuminate\Http\Request;
    use compras\OrdenCompra;
    
    $id = $_GET['id'];

    $OrdenCompra = OrdenCompra::find($id);
  ?>

 	<h1 class="h5 text-info" style="display: inline;">
		<i class="fa fa-search"></i>
		Soporte de orden de compra
	</h1>
	<form action="/ordenCompra/" method="POST" style="display: inline; margin-left: 50px;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<hr class="row align-items-start col-12">
	<table>
		<thead>
		    <tr>
		    		<th scope="row" colspan="7">
		    			<span class="navbar-brand text-info CP-title-NavBar">
		    				<b><i class="fas fa-syringe text-success"></i>CPharma</b>
  						</span>
		    		</th>
		    		<th scope="row" colspan="8" class="aumento">Soporte de orden de compra</th>
		    </tr>
  	</thead>
	  	<tbody>
		    <tr>
	      	<td colspan="7" class="alinear-der">Codigo:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->codigo}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Fecha De Orden:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->created_at}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Destino:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->sede_destino}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Proveedor:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->proveedor}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Fecha Despacho:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->fecha_estimada_despacho}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Condicion Crediticia:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->condicion_crediticia}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Dias Credito:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->dias_credito}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Operador Orden:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->user}}</td>
		    </tr>
	  		<tr>
	      	<td colspan="7" class="alinear-der">Estatus:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->estado}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Observacion:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->observacion}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Fecha De Aprobacion:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->fecha_aprobacion}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Operador De Aprobacion:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->operador_aprobacion}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Fecha De Recepcion:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->fecha_recepcion}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Operador De Recepcion:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->operador_recepcion}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Fecha De Ingreso:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->fecha_ingreso}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Operador De Ingreso:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->operador_ingreso}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Calificacion:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->calificacion}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Factura:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->numero_factura}}</td>
		    </tr>
		    <tr>
	      	<th colspan="7" class="alinear-der">Monto Real:</th>
	      	<th colspan="8" class="alinear-izq">{{number_format($OrdenCompra->montoTotalReal,2,"," ,"." )}} {{$OrdenCompra->moneda}}</th>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Fecha De Cierre:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->fecha_cierre}}</td>
		    </tr>
		    <tr>
	      	<td colspan="7" class="alinear-der">Operador De Cierre:</td>
	      	<td colspan="8" class="alinear-izq">{{$OrdenCompra->operador_cierre}}</td>
		    </tr>
			<thead>
		    <tr>
		    		<th scope="row">#</th>
		    		<th scope="row">Codigo Interno</th>
		    		<th scope="row">Descripcion</th>
		    		<th scope="row">Unidades</th>
		    		<th scope="row">Costo {{$OrdenCompra->moneda}}</th>
		    		<th scope="row">Total {{$OrdenCompra->moneda}}</th>
		    		<th scope="row">FTN</th>
		    		<th scope="row">FLL</th>
		    		<th scope="row">FAU</th>
		    		<th scope="row">MC</th>
		    		<th scope="row">Reporte Origen</th>
		    		<th scope="row">Existencia Origen</th>
		    		<th scope="row">Existencia Actual</th>
		    		<th scope="row">Dias Restantes Origen</th>
		    		<th scope="row">Rango Origen</th>
		    </tr>
	    </thead>
			<?php
				Imprimir_Orden_Detalle($OrdenCompra->codigo,$OrdenCompra->moneda);
			?>
		</tbody>
	</table>
	<br/>
	<span>Nota: 
	<br/>
	*La informacion aqui contenida es propiedad exclusiva de {{$OrdenCompra->sede_origen}}
	<br/>
	* Para cualquier aclaratoria llamar al 0412-1217294 o escribir al correo compras@farmacia72.com.ve 
	<br/>
	* Para cualquier informacion de pagos contactar al 0412-7646518 o escribir al correo administracion@farmacia72.com.ve
	</span>
@endsection

<?php
	/**********************************************************************************/
	/*
		TITULO: Imprimir_Traslado_Detalle
		FUNCION: Busca e imprime las lineas del detalle del traslado
		RETORNO: no aplica
		DESAROLLADO POR: SERGIO COVA
	 */
	function Imprimir_Orden_Detalle($codigo_orden,$moneda) {
			include(app_path().'\functions\config.php');
      include(app_path().'\functions\functions.php');
      include(app_path().'\functions\querys_mysql.php');
      include(app_path().'\functions\querys_sqlserver.php');

      $conn = FG_Conectar_Smartpharma('FTN');
			$connCPharma = FG_Conectar_CPharma();
			$sql = MySQL_Buscar_Orden_Detalle($codigo_orden);
			$result = mysqli_query($connCPharma,$sql);

			$total_unidades = 0;
			$costo_total = 0;
			$total_sede1 = 0;
			$total_sede2 = 0;
			$total_sede3 = 0;
			$total_sede4 = 0;
			$cont = 1;

			while($row = $result->fetch_assoc()) {

				if($row['id_articulo'] != ''){
					$sql1 = Q_Detalle_Articulo($row['id_articulo']);
					$result1 = sqlsrv_query($conn,$sql1);
	    		$row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
	    		$Existencia = $row1["Existencia"];
				}
				else{
					$Existencia = '-';
				}

				echo '<tr>';
				echo '<th>'.intval($cont).'</th>';
				echo '<td>'.$row['codigo_articulo'].'</td>';
				echo '<td>'.$row['descripcion'].'</td>';
				echo '<td>'.$row['total_unidades'].'</td>';
				echo '<td>'.number_format(floatval($row['costo_unitario']),2,"," ,"." ).'</td>';
				echo '<td>'.number_format(floatval($row['costo_total']),2,"," ,"." ).'</td>';
				echo '<td>'.$row['sede1'].'</td>';
				echo '<td>'.$row['sede2'].'</td>';
				echo '<td>'.$row['sede3'].'</td>';
				echo '<td>'.$row['sede4'].'</td>';
				echo '<td>'.$row['origen_rpt'].'</td>';
				echo '<td>'.$row['existencia_rpt'].'</td>';
				echo '<td>'.$Existencia.'</td>';
				echo '<td>'.$row['dias_restantes_rpt'].'</td>';
				echo '<td>'.$row['rango_rpt'].'</td>';
				echo '</tr>';

				$total_unidades += floatval($row['total_unidades']);
				$costo_total += floatval($row['costo_total']);
				$total_sede1 += floatval($row['sede1']);
				$total_sede2 += floatval($row['sede2']);
				$total_sede3 += floatval($row['sede3']);
				$total_sede4 += floatval($row['sede4']);
				$cont++;
	  	}
	  	
	  	echo '<tr>';
	  	echo '<td colspan="3" class="alinear-der"><strong>Totales</strong></td>';
	  	echo '<td><strong>'.$total_unidades.'</strong></td>';
	  	echo '<td><strong></strong></td>';
	  	echo '<td><strong>'.number_format($costo_total,2,"," ,"." ).' '.$moneda.'</strong></td>';
	  	echo '<td><strong>'.$total_sede1.'</strong></td>';
	  	echo '<td><strong>'.$total_sede2.'</strong></td>';
	  	echo '<td><strong>'.$total_sede3.'</strong></td>';
	  	echo '<td><strong>'.$total_sede4.'</strong></td>';
	  	echo '</tr>';
			mysqli_close($connCPharma);
	}
/*********************************************************************************/
  /*
    TITULO: Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function Q_Detalle_Articulo($IdArticulo) {
    $sql = " 
      SELECT
    --Id Articulo
      InvArticulo.Id AS IdArticulo,
    --Codigo Interno
      InvArticulo.CodigoArticulo AS CodigoInterno,
    --Codigo de Barra
      (SELECT CodigoBarra
      FROM InvCodigoBarra 
      WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
      AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
    --Descripcion
      InvArticulo.Descripcion,
    --Impuesto (1 SI aplica impuesto, 0 NO aplica impuesto)
      (ISNULL(InvArticulo.FinConceptoImptoIdCompra,CAST(0 AS INT))) AS Impuesto,
    --Utilidad (Utilidad del articulo, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
      ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
          FROM VenCondicionVenta 
          WHERE VenCondicionVenta.Id = (
            SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id
            FROM VenCondicionVenta_VenCondicionVentaArticulo 
            WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS Utilidad,
    --Precio Troquel Almacen 1
      (ROUND(CAST((SELECT TOP 1
      InvLote.M_PrecioTroquelado
      FROM InvLoteAlmacen
      INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
      WHERE(InvLoteAlmacen.InvAlmacenId = '1')
      AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
      AND (InvLoteAlmacen.Existencia>0)
      ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen1,
    --Precio Troquel Almacen 2
      (ROUND(CAST((SELECT TOP 1
      InvLote.M_PrecioTroquelado
      FROM InvLoteAlmacen
      INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
      WHERE(InvLoteAlmacen.InvAlmacenId = '2')
      AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
      AND (InvLoteAlmacen.Existencia>0)
      ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen2,
    --Precio Compra Bruto
      (ROUND(CAST((SELECT TOP 1
      InvLote.M_PrecioCompraBruto
      FROM InvLoteAlmacen
      INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
      WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
      AND (InvLoteAlmacen.Existencia>0)
      ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBruto,
    --Existencia (Segun el almacen del filtro)
      (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                  FROM InvLoteAlmacen
                  WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
                  AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS Existencia,
    --Dolarizado (0 NO es dolarizado, Id Articulo SI es dolarizado)
      (ISNULL((SELECT
      InvArticuloAtributo.InvArticuloId
      FROM InvArticuloAtributo 
      WHERE InvArticuloAtributo.InvAtributoId = 
        (SELECT InvAtributo.Id
        FROM InvAtributo 
        WHERE 
        InvAtributo.Descripcion = 'Dolarizados'
        OR  InvAtributo.Descripcion = 'Giordany'
        OR  InvAtributo.Descripcion = 'giordany') 
      AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Dolarizado,
    --Tipo Producto (0 Miscelaneos, Id Articulo Medicinas)
      (ISNULL((SELECT
      InvArticuloAtributo.InvArticuloId 
      FROM InvArticuloAtributo 
      WHERE InvArticuloAtributo.InvAtributoId = 
        (SELECT InvAtributo.Id
        FROM InvAtributo 
        WHERE 
        InvAtributo.Descripcion = 'Medicina') 
      AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Tipo,
    --Articulo Estrella (0 NO es Articulo Estrella , Id SI es Articulo Estrella)
      (ISNULL((SELECT
      InvArticuloAtributo.InvArticuloId 
      FROM InvArticuloAtributo 
      WHERE InvArticuloAtributo.InvAtributoId = 
        (SELECT InvAtributo.Id
        FROM InvAtributo 
        WHERE 
        InvAtributo.Descripcion = 'Articulo Estrella') 
      AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS ArticuloEstrella,
    -- Ultima Venta (Fecha)
      (SELECT TOP 1
      CONVERT(DATE,VenFactura.FechaDocumento)
      FROM VenFactura
      INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
      WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
      ORDER BY FechaDocumento DESC) AS UltimaVenta,
    --Tiempo sin Venta (En dias)
      (SELECT TOP 1
      DATEDIFF(DAY,CONVERT(DATE,VenFactura.FechaDocumento),GETDATE())
      FROM VenFactura
      INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
      WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
      ORDER BY FechaDocumento DESC) AS TiempoSinVenta,
    --Ultimo Lote (Fecha)
      (SELECT TOP 1
      CONVERT(DATE,InvLote.FechaEntrada) AS UltimoLote
      FROM InvLote
      WHERE InvLote.InvArticuloId  = InvArticulo.Id
      ORDER BY UltimoLote DESC) AS UltimoLote,
    --Tiempo Tienda (En dias)
      (SELECT TOP 1 
      DATEDIFF(DAY,CONVERT(DATE,InvLote.FechaEntrada),GETDATE())
      FROM InvLoteAlmacen 
      INNER JOIN invlote on invlote.id = InvLoteAlmacen.InvLoteId
      WHERE InvLotealmacen.InvArticuloId = InvArticulo.Id
      ORDER BY InvLote.Auditoria_FechaCreacion DESC) AS TiempoTienda,
    -- Ultimo Proveedor (Id Proveedor)
      (SELECT TOP 1
      ComProveedor.Id
      FROM ComFacturaDetalle
      INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
      INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
      INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
      WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
      ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorID,
    -- Ultimo Proveedor (Nombre Proveedor)
      (SELECT TOP 1
      GenPersona.Nombre
      FROM ComFacturaDetalle
      INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
      INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
      INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
      WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
      ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre
    --Tabla principal
      FROM InvArticulo
    --Joins
      LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
      LEFT JOIN InvArticuloAtributo ON InvArticuloAtributo.InvArticuloId = InvArticulo.Id
      LEFT JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId 
    --Condicionales
      WHERE InvArticulo.Id = '$IdArticulo'
    --Agrupamientos
      GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra
    --Ordanamiento
      ORDER BY InvArticulo.Id ASC 
    ";
    return $sql;
  }
?>