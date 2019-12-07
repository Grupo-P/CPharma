@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Articulos Estrella
	</h1>
	<hr class="row align-items-start col-12">

	<?php	
		include(app_path().'\functions\config.php');
		include(app_path().'\functions\functions.php');
		include(app_path().'\functions\querys_mysql.php');
		include(app_path().'\functions\querys_sqlserver.php');

		$InicioCarga = new DateTime("now");

		if (isset($_GET['SEDE'])){			
			echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
		}
		echo '<hr class="row align-items-start col-12">';

		R16_Acticulos_Estrella($_GET['SEDE']);
		//FG_Guardar_Auditoria('CONSULTAR','REPORTE','Articulos Estrella');

		$FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
	?>
@endsection

<?php
	/**********************************************************************************/
	/*
		TITULO: R16_Acticulos_Estrella
		FUNCION: Arma el reporte de articulos estrellas
		RETORNO: Lista de articulos estrella y su comportamiento
		DESAROLLADO POR: SERGIO COVA
 	*/
	function R16_Acticulos_Estrella($SedeConnection) {
		$conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

   	$sql = R16Q_Detalle_Articulo_Estrella();
    $result = sqlsrv_query($conn,$sql);

    $FFinal = $FFinalEn = date("Y-m-d");
    $FInicial = $FInicialEn = date("Y-m-d",strtotime($FFinal."-5 days"));
   	$RangoDias = FG_Rango_Dias($FInicial,$FFinal)+1;

   	$DiasPedido = 20;

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    echo '
    <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1">
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
    </div>
    <br/>
    ';
    echo'<h6 align="center">Periodo desde el '.$FInicialImp.' al '.$FFinalImp.' </h6>';
    echo'<h6 align="center">El calculo de dias a pedir se esta calculando en pedidos para 20 dias de inventario</h6>';
    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
        <tr>  
          <th scope="col" class="CP-sticky">#</th>
          <th scope="col" class="CP-sticky">Codigo Interno</th>
          <th scope="col" class="CP-sticky">Descripcion</th>
          <th scope="col" class="CP-sticky">Precio (Con IVA)</th>
          <th scope="col" class="CP-sticky">Existencia</th>
    ';
    while($FFinalEn!=$FInicialEn){
    	$Dia = date("D", strtotime($FInicialEn));
    	$FImpresion = date("d-m-Y", strtotime($FInicialEn));
    	echo'<th scope="col" class="CP-sticky">'.$Dia.' '.$FImpresion.'</th>';
    	$FInicialEn = date("Y-m-d",strtotime($FInicialEn."+1 days"));
    }
         
    echo'
          <th scope="col" class="CP-sticky">Ventas Hoy</th> 
          <th scope="col" class="CP-sticky">Ventas Totales</th> 
          <th scope="col" class="CP-sticky">Dias en Quiebre</th> 
          <th scope="col" class="CP-sticky">Dias Restantes</th>
          <th scope="col" class="CP-sticky">Cantidad a Pedir</th>          
        </tr>
      </thead>
      <tbody>
    ';
   	$contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

    	$FInicialBo = $FInicial;
    	$FFinalBo = $FFinal;

    	$IdArticulo = $row["IdArticulo"];
    	$Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
    	$Existencia = $row["Existencia"];
      $ExistenciaAlmacen1 = $row["ExistenciaAlmacen1"];
      $ExistenciaAlmacen2 = $row["ExistenciaAlmacen2"];
      $IsTroquelado = $row["Troquelado"];
      $IsIVA = $row["Impuesto"];
      $UtilidadArticulo = $row["UtilidadArticulo"];
      $UtilidadCategoria = $row["UtilidadCategoria"];
      $TroquelAlmacen1 = $row["TroquelAlmacen1"];
      $PrecioCompraBrutoAlmacen1 = $row["PrecioCompraBrutoAlmacen1"];
      $TroquelAlmacen2 = $row["TroquelAlmacen2"];
      $PrecioCompraBrutoAlmacen2 = $row["PrecioCompraBrutoAlmacen2"];
      $PrecioCompraBruto = $row["PrecioCompraBruto"];
      $Dolarizado = $row["Dolarizado"];
      $CondicionExistencia = 'CON_EXISTENCIA';

      $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

    	echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="left">'.$row['CodigoInterno'].'</td>';
      echo 
      '<td align="left" class="CP-barrido">
      <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';
      echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
      echo '<td align="center">'.intval($Existencia).'</td>';

      while($FFinalBo!=$FInicialBo){
      	$FPivoteBo = date("Y-m-d",strtotime($FInicialBo."+1 days"));
	    	$sql1 = R16Q_Detalle_Venta($IdArticulo,$FInicialBo,$FPivoteBo);
   			$result1 = sqlsrv_query($conn,$sql1);
   			$row1= sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);
   			$TotalUnidadesVendidas = $row1['TotalUnidadesVendidas'];
   			echo '<td align="center">'.intval($TotalUnidadesVendidas).'</td>';
	    	$FInicialBo = date("Y-m-d",strtotime($FInicialBo."+1 days"));
	    }

	    $FPivoteBo = date("Y-m-d",strtotime($FInicialBo."+1 days"));
    	$sql1 = R16Q_Detalle_Venta($IdArticulo,$FInicialBo,$FPivoteBo);
 			$result1 = sqlsrv_query($conn,$sql1);
 			$row1= sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);
 			$TotalUnidadesVendidas = $row1['TotalUnidadesVendidas'];
 			echo '<td align="center">'.intval($TotalUnidadesVendidas).'</td>';

 			$FPivoteBo = date("Y-m-d",strtotime($FFinal."+1 days"));
 			$sql1 = R16Q_Detalle_Venta($IdArticulo,$FInicial,$FPivoteBo);
 			$result1 = sqlsrv_query($conn,$sql1);
 			$row1= sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);
 			$TotalUnidadesVendidasG = $row1['TotalUnidadesVendidas'];
 			echo '<td align="center">'.intval($TotalUnidadesVendidasG).'</td>';

 			$FPivoteIDC = date("Y-m-d",strtotime($FInicial."-1 days"));
 			$FPivoteFDC = date("Y-m-d",strtotime($FFinal."+1 days"));

 			$sql2 = "SELECT COUNT(*) AS Cuenta FROM `dias_ceros` WHERE `fecha_captura` > '$FPivoteIDC' AND `fecha_captura` < '$FPivoteFDC' AND `id_articulo` = '$IdArticulo'";
 			$result2 = mysqli_query($connCPharma,$sql2);
			$row2 = mysqli_fetch_assoc($result2);
			$Cuenta = $row2['Cuenta'];
			$DiasEnQuiebre = $RangoDias - $Cuenta;

			echo '<td align="center">'.intval($DiasEnQuiebre).'</td>';

			$VentaDiaria = FG_Venta_Diaria($TotalUnidadesVendidasG,$RangoDias);
      $DiasRestantes = FG_Dias_Restantes($Existencia,$VentaDiaria);
      $CantidadPedido = FG_Cantidad_Pedido($VentaDiaria,$DiasPedido,$Existencia);

      echo '<td align="center">'.round($DiasRestantes,2).'</td>';
      echo '<td align="center">'.intval($CantidadPedido).'</td>';

      echo '</tr>';
      $contador++;
    }
    echo '
        </tbody>
      </table>';
	}
	/**********************************************************************************/
  /*
    TITULO: R16Q_Detalle_Articulo_Estrella
    FUNCION: Query que genera listado de articulos estralla con sus detalles
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R16Q_Detalle_Articulo_Estrella() {
    $sql = " 
   	SELECT
    --Id Articulo
    InvArticulo.Id AS IdArticulo,
    --Categoria Articulo
    InvArticulo.InvCategoriaId,
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
    --Troquelado (0 NO es Troquelado, Id Articulo SI es Troquelado)
    (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo 
    WHERE InvArticuloAtributo.InvAtributoId = 
    (SELECT InvAtributo.Id
    FROM InvAtributo 
    WHERE 
    InvAtributo.Descripcion = 'Troquelados'
    OR  InvAtributo.Descripcion = 'troquelados') 
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Troquelado,
    --UtilidadArticulo (Utilidad del articulo, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
    FROM VenCondicionVenta 
    WHERE VenCondicionVenta.Id = (
    SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id
    FROM VenCondicionVenta_VenCondicionVentaArticulo 
    WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS UtilidadArticulo,
    --UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad 
    FROM VenCondicionVenta 
    WHERE VenCondicionVenta.id = (
    SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id 
    FROM VenCondicionVenta_VenCondicionVentaCategoria 
    WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS UtilidadCategoria,
    --Precio Troquel Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '1')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen1,
    --Precio Compra Bruto Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    AND (InvLoteAlmacen.InvAlmacenId = '1')
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen1,
    --Precio Troquel Almacen 2
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '2')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen2,
    --Precio Compra Bruto Almacen 2
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    AND (InvLoteAlmacen.InvAlmacenId = '2')
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen2,
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
    --ExistenciaAlmacen1 (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
    FROM InvLoteAlmacen
    WHERE(InvLoteAlmacen.InvAlmacenId = 1)
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen1,
    --ExistenciaAlmacen2 (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
    FROM InvLoteAlmacen
    WHERE(InvLoteAlmacen.InvAlmacenId = 2)
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen2,
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
    --Ultimo Proveedor (Id Proveedor)
    (SELECT TOP 1
    ComProveedor.Id
    FROM ComFacturaDetalle
    INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorID,
    --Ultimo Proveedor (Nombre Proveedor)
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
    WHERE 
		(
		(ISNULL((SELECT
		InvArticuloAtributo.InvArticuloId 
		FROM InvArticuloAtributo 
		WHERE InvArticuloAtributo.InvAtributoId = 
		(SELECT InvAtributo.Id
		FROM InvAtributo 
		WHERE 
		InvAtributo.Descripcion = 'Articulo Estrella') 
		AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT)))<>0
		)
    --Agrupamientos
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
    --Ordanamiento
    ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R16Q_Detalle_Venta
    FUNCION: Busca los datos de las ventas para el articulo seleccionado
    RETORNO: Detalle de la venta del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R16Q_Detalle_Venta($IdArticulo,$FInicial,$FFinal){
  $sql="
  SELECT
	-- Id Articulo
	VenFacturaDetalle.InvArticuloId,
	--Veces Vendidas (En Rango)
	ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesVendidas,
	--Unidades Vendidas (En Rango)
	(ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesVendidas,
	--Veces Devueltas (En Rango)
	ISNULL((SELECT
	ISNULL(COUNT(*),CAST(0 AS INT))
	FROM VenDevolucionDetalle
	INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
	WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
	AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
	GROUP BY VenDevolucionDetalle.InvArticuloId
	),CAST(0 AS INT)) AS VecesDevueltas,
	--Unidades Devueltas (En Rango)
	ISNULL((SELECT
	(ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
	FROM VenDevolucionDetalle
	INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
	WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
	AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
	GROUP BY VenDevolucionDetalle.InvArticuloId
	),CAST(0 AS INT)) AS UnidadesDevueltas,
	--Total Veces Vendidas (En Rango)
	((ISNULL(COUNT(*),CAST(0 AS INT)))
	-
	(ISNULL((SELECT
	ISNULL(COUNT(*),CAST(0 AS INT))
	FROM VenDevolucionDetalle
	INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
	WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
	AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
	GROUP BY VenDevolucionDetalle.InvArticuloId
	),CAST(0 AS INT)))) AS TotalVecesVendidas,
	--Total Unidades Vendidas (En Rango)
	(((ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)))
	-
	(ISNULL((SELECT
	(ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
	FROM VenDevolucionDetalle
	INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
	WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
	AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
	GROUP BY VenDevolucionDetalle.InvArticuloId
	),CAST(0 AS INT)))) AS TotalUnidadesVendidas,
	--Veces Conpradas (En Rango) 
	ISNULL((SELECT
	ISNULL(COUNT(*),CAST(0 AS INT))
	FROM ComFacturaDetalle
	INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
	WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
	AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
	GROUP BY ComFacturaDetalle.InvArticuloId
	),CAST(0 AS INT)) AS VecesCompradas,
	--Unidades Conpradas (En Rango) 
	ISNULL((SELECT
	(ROUND(CAST(SUM(ComFacturaDetalle.CantidadFacturada) AS DECIMAL(38,0)),2,0))
	FROM ComFacturaDetalle
	INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
	WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
	AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
	GROUP BY ComFacturaDetalle.InvArticuloId
	),CAST(0 AS INT)) AS UnidadesCompradas,
	--Veces Reclamadas (En Rango) 
	ISNULL((SELECT
	ISNULL(COUNT(*),CAST(0 AS INT))
	FROM ComReclamoDetalle
	INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
	WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
	AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
	GROUP BY ComReclamoDetalle.InvArticuloId
	),CAST(0 AS INT)) AS VecesReclamadas,
	--Unidades Reclamadas (En Rango) 
	ISNULL((SELECT
	(ROUND(CAST(SUM(ComReclamoDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
	FROM ComReclamoDetalle
	INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
	WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
	AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
	GROUP BY ComReclamoDetalle.InvArticuloId
	),CAST(0 AS INT)) AS UnidadesReclamadas,
	--Total Veces Compradas (En Rango)
	((ISNULL((SELECT
	ISNULL(COUNT(*),CAST(0 AS INT))
	FROM ComFacturaDetalle
	INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
	WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
	AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
	GROUP BY ComFacturaDetalle.InvArticuloId
	),CAST(0 AS INT)))
	-
	(ISNULL((SELECT
	ISNULL(COUNT(*),CAST(0 AS INT))
	FROM ComReclamoDetalle
	INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
	WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
	AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
	GROUP BY ComReclamoDetalle.InvArticuloId
	),CAST(0 AS INT)))) AS TotalVecesCompradas,
	--Total de Unidades Compradas (En Rango)
	((ISNULL((SELECT
	(ROUND(CAST(SUM(ComFacturaDetalle.CantidadFacturada) AS DECIMAL(38,0)),2,0))
	FROM ComFacturaDetalle
	INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
	WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
	AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
	GROUP BY ComFacturaDetalle.InvArticuloId
	),CAST(0 AS INT)))
	-
	(ISNULL((SELECT
	(ROUND(CAST(SUM(ComReclamoDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
	FROM ComReclamoDetalle
	INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
	WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
	AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
	GROUP BY ComReclamoDetalle.InvArticuloId
	),CAST(0 AS INT)))) AS TotalUnidadesCompradas,
	-- SubTotal Venta (En Rango)
	ISNULL((SELECT
	(ROUND(CAST(SUM (VenVentaDetalle.PrecioBruto * VenVentaDetalle.Cantidad) AS DECIMAL(38,2)),2,0)) 
	FROM VenVentaDetalle
	INNER JOIN VenVenta ON VenVenta.Id = VenVentaDetalle.VenVentaId 
	WHERE (VenVenta.FechaDocumentoVenta > '$FInicial' AND VenVenta.FechaDocumentoVenta < '$FFinal')
	AND VenVentaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)) AS SubTotalVenta,
	--SubTotal Devolucion (En Rango)
	ISNULL((SELECT
	(ROUND(CAST(SUM (VenDevolucionDetalle.PrecioBruto * VenDevolucionDetalle.Cantidad) AS DECIMAL(38,2)),2,0)) as SubTotalDevolucion
	FROM VenDevolucionDetalle
	INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId 
	WHERE (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal') 
	AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)) as SubTotalDevolucion,
	--TotalVenta (En Rango)
	((ISNULL((SELECT
	(ROUND(CAST(SUM (VenVentaDetalle.PrecioBruto * VenVentaDetalle.Cantidad) AS DECIMAL(38,2)),2,0)) 
	FROM VenVentaDetalle
	INNER JOIN VenVenta ON VenVenta.Id = VenVentaDetalle.VenVentaId 
	WHERE (VenVenta.FechaDocumentoVenta > '$FInicial' AND VenVenta.FechaDocumentoVenta < '$FFinal')
	AND VenVentaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)))
	-
	(ISNULL((SELECT
	(ROUND(CAST(SUM (VenDevolucionDetalle.PrecioBruto * VenDevolucionDetalle.Cantidad) AS DECIMAL(38,2)),2,0)) as SubTotalDevolucion
	FROM VenDevolucionDetalle
	INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId 
	WHERE (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal') 
	AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)))) AS TotalVenta
	--Tabla Principal
	FROM VenFacturaDetalle
	--Joins
	INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
	--Condicionales
	WHERE
	(VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
	AND VenFacturaDetalle.InvArticuloId = '$IdArticulo'
	--Agrupamientos
	GROUP BY VenFacturaDetalle.InvArticuloId 
	--Ordenamientos
	ORDER BY UnidadesVendidas DESC
  ";
  return $sql;
  }
?>