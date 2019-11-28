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
    Productos Por Fallar
  </h1>
  <hr class="row align-items-start col-12">

<?php 
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  if (isset($_GET['SEDE'])) {      
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
  }
  echo '<hr class="row align-items-start col-12">';

  if (isset($_GET['Existencia'])) {

    $InicioCarga = new DateTime("now");

    R13_Productos_PorFallar($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin'],$_GET['Existencia']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Productos Por Fallar');
    
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
              Existencia:
            </td>
            <td>
              <input id="Existencia" type="number" name="Existencia" required style="width:100%;" placeholder="Cantidad en numero">
            </td>
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
    TITULO: R13_Productos_PorFallar
    FUNCION: Arma una lista de productos por fallar
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R13_Productos_PorFallar($SedeConnection,$FInicial,$FFinal,$ExistenciaB){

    $conn = FG_Conectar_Smartpharma($SedeConnection);

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    $sql = R13Q_Productos_PorFallar($ExistenciaB,$FInicial,$FFinal);
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
    echo'<h6 align="center">Existencia menor a: '.$ExistenciaB.' productos </h6>';
    echo'<h6 align="center">Periodo desde el '.$FInicialImp.' al '.$FFinalImp.' </h6>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Codigo</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Tipo</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Unidades vendidas</th>
            <th scope="col" class="CP-sticky">Total de Venta '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Ultima Venta</th>
            <th scope="col" class="CP-sticky">Ultimo Proveedor</th>
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["InvArticuloId"];
      $TotalVenta = $row["TotalVenta"];
      $Venta = $row["TotalUnidadesVendidas"];

      $sql1 = R13Q_Detalle_Articulo($IdArticulo);
      $result1 = sqlsrv_query($conn,$sql1);
      $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

      $CodigoArticulo = $row1["CodigoInterno"];
      $Descripcion = FG_Limpiar_Texto($row1["Descripcion"]);
      $Existencia = $row1["Existencia"];
      $Tipo = FG_Tipo_Producto($row1["Tipo"]);
      $UltimaVenta = $row1["UltimaVenta"];
      $UltimoProveedorId =  $row1["UltimoProveedorID"];
      $UltimoProveedor =  FG_Limpiar_Texto($row1["UltimoProveedorNombre"]);
      
      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="left">'.$CodigoArticulo.'</td>';

      echo 
      '<td align="left" class="CP-barrido">
      <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';

      echo '<td align="center">'.$Tipo.'</td>';
      echo '<td align="center">'.intval($Existencia).'</td>';
      echo 
      '<td align="center" class="CP-barrido">
      <a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImp.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Venta.
      '</a>
      </td>';

      echo '<td align="center">'.number_format($TotalVenta,2,"," ,"." ).'</td>';

      if(($UltimaVenta)){
        echo '<td align="center">'.$UltimaVenta->format('Y-m-d').'</td>';
      }
      else{
        echo '<td align="center"> - </td>';
      }

      echo 
      '<td align="left" class="CP-barrido">
      <a href="/reporte7?Nombre='.$UltimoProveedor.'&Id='.$UltimoProveedorId.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
        .$UltimoProveedor.
      '</a>
      </td>';

      echo '</tr>';
      $contador++;
    }
    echo '
      </tbody>
    </table>';
    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R13Q_Productos_PorFallar
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
    DESAROLLADO POR: SERGIO COVA
  */
  function R13Q_Productos_PorFallar($Existencia,$FInicial,$FFinal) {
    $sql = "
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
    AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)))) AS TotalVenta,
--Existencia (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
    FROM InvLoteAlmacen
    WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
    AND (InvLoteAlmacen.InvArticuloId = VenFacturaDetalle.InvArticuloId)) AS DECIMAL(38,0)),2,0))  AS Existencia
--Tabla Principal
    FROM VenFacturaDetalle
--Joins
    INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
--Condicionales
    WHERE
    (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
    AND
    ((ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
    FROM InvLoteAlmacen
    WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
    AND (InvLoteAlmacen.InvArticuloId = VenFacturaDetalle.InvArticuloId)) AS DECIMAL(38,0)),2,0))>0)
    AND
    ((ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
    FROM InvLoteAlmacen
    WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
    AND (InvLoteAlmacen.InvArticuloId = VenFacturaDetalle.InvArticuloId)) AS DECIMAL(38,0)),2,0))<'$Existencia')
--Agrupamientos
    GROUP BY VenFacturaDetalle.InvArticuloId 
--Ordenamientos
    ORDER BY TotalUnidadesVendidas DESC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R13Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R13Q_Detalle_Articulo($IdArticulo) {
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