@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
  <style>
  * {
    box-sizing: border-box;
  }
  .autocomplete {
    position: relative;
    display: inline-block;
  }
  input {
    border: 1px solid transparent;
    background-color: #f1f1f1;
    border-radius: 5px;
    padding: 10px;
    font-size: 16px;
  }
  input[type=text] {
    background-color: #f1f1f1;
    width: 100%;
  }
  .autocomplete-items {
    position: absolute;
    border: 1px solid #d4d4d4;
    border-bottom: none;
    border-top: none;
    z-index: 99;
    top: 100%;
    left: 0;
    right: 0;
  }
  .autocomplete-items div {
    padding: 10px;
    cursor: pointer;
    background-color: #fff;
    border-bottom: 1px solid #d4d4d4;
  }
  .autocomplete-items div:hover {
    background-color: #e9e9e9;
  }
  .autocomplete-active {
    background-color: DodgerBlue !important;
    color: #ffffff;
  }
  </style>
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Ultimas Entradas en Cero
	</h1>
	<hr class="row align-items-start col-12">

<?php
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  if (isset($_GET['SEDE'])){
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
  }
  echo '<hr class="row align-items-start col-12">';

	if (isset($_GET['fechaInicio'])) {
    $InicioCarga = new DateTime("now");

    R26_Ultimas_Entradas($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Ultimas Entradas en Cero');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
	}
	else{
		echo '
		<form autocomplete="off" action="" target="_blank">
        <table style="width:100%;">
          <tr>
            <td align="center">
              Fecha Inicio:
            </td>
            <td>
              <input id="fechaInicio" type="date" name="fechaInicio" required style="width:100%;">
            </td>
            <td align="center">
              Fecha Fin:
            </td>
            <td align="right">
              <input id="fechaFin" name="fechaFin" type="date" required style="width:100%;">
            </td>
            <input id="SEDE" name="SEDE" type="hidden" value="';
            print_r($_GET['SEDE']);
            echo'">
            <td align="right">
              <input type="submit" value="Buscar" class="btn btn-outline-success">
            </td>
          </tr>
		    </table>
	  	</form>
  	';
	}
?>
@endsection

<?php
  /**********************************************************************************/
  /*
    TITULO: R24_Articulos_Nuevos
    FUNCION: Arma una tabla con los productos mas vendidos
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R26_Ultimas_Entradas($SedeConnection,$FInicial,$FFinal){
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));
    $RangoDias = FG_Rango_Dias($FInicial,$FFinal);

    $sql = R26Q_Detalle_Articulo($FInicial,$FFinal);
    $result = sqlsrv_query($conn,$sql);

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
    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Codigo</th>
            <th scope="col" class="CP-sticky">Codigo de barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Precio</br>(Con IVA) '.SigVe.'</td>
            <th scope="col" class="CP-sticky">Dias en falla</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Cantidad recibida</td>
            <th scope="col" class="CP-sticky">Fecha Llegada</th>
            <th scope="col" class="CP-sticky">Tipo</th>
            <th scope="col" class="CP-sticky">Dolarizado?</td>
            <th scope="col" class="CP-sticky">Gravado?</td>
            <th scope="col" class="CP-sticky">Clasificacion</td>
            <th scope="col" class="CP-sticky">Ultima Venta</th>
            <th scope="col" class="CP-sticky">Ultimo Proveedor</th>
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["IdArticulo"];

      $FAntesRango = date("Y-m-d",strtotime($FInicial."- 1 days"));
      $sqlCPharma = "SELECT count(*) as cuenta FROM dias_ceros WHERE fecha_captura = '$FAntesRango' and id_articulo = '$IdArticulo'";
      $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
      $RowCPharma = mysqli_fetch_assoc($ResultCPharma);

      if($RowCPharma['cuenta'] == 0){
        $CodigoArticulo = $row["CodigoInterno"];
        $CodigoBarra = $row["CodigoBarra"];
        $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
        $CantidadRecibida = $row["CantidadRecibida"];
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
        $CondicionExistencia = 'CON_EXISTENCIA';
        $Tipo = FG_Tipo_Producto($row["Tipo"]);
        $Dolarizado = FG_Producto_Dolarizado($row["Dolarizado"]);
        $Gravado = FG_Producto_Gravado($IsIVA);
        $UltimaVenta = $row["UltimaVenta"];
        $UltimaVentaFueraRango = $row["UltimaVentaFueraRango"];
        $UltimoProveedorId = $row["UltimoProveedorID"];
        $UltimoProveedorNombre = $row["UltimoProveedorNombre"];
        $ultimaCompra = $row["UltimaCompra"];

        if(!is_null($UltimaVentaFueraRango)){
          $diasFalla = FG_Rango_Dias($UltimaVentaFueraRango->format('y-m-d'),date('y-m-d'));
        }
        else{
          $diasFalla = "-";
        }

        $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
        $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

        $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
        $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
        $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
        $clasificacion = $RowCPharma['clasificacion'];
        $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";

        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
        echo '<td>'.$CodigoArticulo.'</td>';
        echo '<td>'.$CodigoBarra.'</td>';

        echo '
          <td align="left" class="CP-barrido">
            <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
              .$Descripcion
            .'</a>
          </td>
        ';
        echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
        echo '<td align="center">'.$diasFalla.'</td>';
        echo '<td align="center">'.intval($Existencia).'</td>';
        echo '<td align="center">'.$CantidadRecibida.'</td>';
        echo '<td align="center">'.$ultimaCompra->format('d-m-Y').'</td>';
        echo '<td align="center">'.$Tipo.'</td>';
        echo '<td align="center">'.$Dolarizado.'</td>';
        echo '<td align="center">'.$Gravado.'</td>';
        echo '<td align="center">'.$clasificacion.'</td>';

        if(!is_null($UltimaVenta)){
          echo '<td align="center">'.$UltimaVenta->format('d-m-Y').'</td>';
        }
        else{
          echo '<td align="center"> - </td>';
        }

        if(!is_null($UltimoProveedorNombre)){
           echo
          '<td align="left" class="CP-barrido">
          <a href="/reporte7?Nombre='.$UltimoProveedorNombre.'&Id='.$UltimoProveedorId.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
            .$UltimoProveedorNombre.
          '</a>
          </td>';
        }
        else{
          echo '<td align="center"> - </td>';
        }

        echo '</tr>';
        $contador++;
      }
    }
    echo '
      </tbody>
    </table>';
    mysqli_close($connCPharma);
    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R26Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R26Q_Detalle_Articulo($FInicial,$FFinal) {
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
    WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadArticulo,
    --UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
    FROM VenCondicionVenta
    WHERE VenCondicionVenta.id = (
    SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id
    FROM VenCondicionVenta_VenCondicionVentaCategoria
    WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadCategoria,
    --Precio Troquel Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '1')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,4)),4,0)) AS TroquelAlmacen1,
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
    -- Ultima Venta Fueran del rango (Fecha)
    (SELECT TOP 1
    CONVERT(DATE,VenFactura.FechaDocumento)
    FROM VenFactura
    INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
    WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
    AND (VenFactura.FechaDocumento NOT BETWEEN '$FInicial' AND '$FFinal')
    ORDER BY FechaDocumento DESC) AS UltimaVentaFueraRango,
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
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre,
    --Cantidad Recibida
    (ROUND(CAST((SELECT SUM(ComFacturaDetalle.CantidadRecibidaFactura)
    FROM ComFacturaDetalle
    inner join ComFactura on ComFactura.Id = ComFacturaDetalle.ComFacturaId
    WHERE (ComFactura.FechaRegistro > '$FInicial')
    AND (ComFactura.FechaRegistro < '$FFinal')
    AND (ComFacturaDetalle.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) AS CantidadRecibida,
    -- Ultima compra (Fecha)
    (SELECT TOP 1
    CONVERT(DATE,ComFactura.FechaRegistro)
    FROM ComFactura
    INNER JOIN ComFacturaDetalle ON ComFacturaDetalle.ComFacturaId = ComFactura.Id
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY FechaRegistro DESC) AS UltimaCompra
    --Tabla principal
    FROM
    InvArticulo
    --Joins
    INNER JOIN ComFacturaDetalle on ComFacturaDetalle.InvArticuloId = InvArticulo.id
    INNER JOIN ComFactura on ComFactura.Id = ComFacturaDetalle.ComFacturaId
    --Condicionales
    WHERE
    (ComFactura.FechaRegistro BETWEEN '$FInicial' AND '$FFinal')
    AND (InvArticulo.Auditoria_FechaCreacion NOT BETWEEN '$FInicial' AND '$FFinal')
    --Agrupamientos
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
    --Ordanamiento
    ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }
?>

@section('scriptsFoot')
<script>
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();
  });
</script>
@endsection
