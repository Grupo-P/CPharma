@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
  <style>
    * {
      box-sizing: border-box;
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
  </style>
@endsection

@section('scriptsFoot')
  <script>
    $('.secciones').click(function (event) {
        event.preventDefault();

        params = new URLSearchParams(window.location.search);

        seccion = $(this).attr('data-seccion');

        if (params.has('seccion')) {
          params.delete('seccion');
          params.append('seccion', seccion);
        } else {
          params.append('seccion', seccion);
        }

        window.location.href = '?' + params.toString();
      });
  </script>
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Productos para surtir
  </h1>
  <hr class="row align-items-start col-12">
  
<?php
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  if(isset($_GET['SEDE'])) {
    echo '
      <h1 class="h5 text-success" align="left">
        <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE'])
      .'</h1>
    ';
  }
  echo '<hr class="row align-items-start col-12">';

  if(isset($_GET['fechaInicio'])) {
    $InicioCarga = new DateTime("now");

    R9_Productos_Surtir($_GET['SEDE'], $_GET['fechaInicio'], $_GET['fechaFin']);
    FG_Guardar_Auditoria('CONSULTAR', 'REPORTE', 'Productos para surtir');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else {
    echo '
      <form autocomplete="off" action="" target="_blank">
        <table style="width:100%;">
          <tr>
            <td align="center">Fecha Inicio:</td>
            <td>
              <input id="fechaInicio" type="date" name="fechaInicio" required style="width:100%;">
            </td>
            <td align="center">Fecha Fin:</td>
            <td align="right">
              <input id="fechaFin" name="fechaFin" type="date" required style="width:100%;">
            </td>
            <td align="right">
              <input id="SEDE" name="SEDE" type="hidden" value="'; print_r($_GET['SEDE']); echo'">
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
    TITULO: R9_Productos_Surtir
    FUNCION: Arma una lista con los productos surtidos y sus fechas
    RETORNO: No aplica
    AUTOR: Ing. Manuel Henriquez
  */
  function R9_Productos_Surtir($SedeConnection, $FInicial, $FFinal) {
    $conn = FG_Conectar_Smartpharma($SedeConnection);

    $FFinalImpresion = $FFinal;
    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    $sql = R9Q_Productos_Surtir($FInicial,$FFinal);
    $result = sqlsrv_query($conn, $sql);

    if (sqlsrv_errors()) {
      dd(sqlsrv_errors());
    }

    echo '
      <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
        <div class="input-group-prepend">
          <span class="input-group-text purple lighten-3" id="basic-text1">
            <i class="fas fa-search text-white" aria-hidden="true"></i>
          </span>
        </div>
        <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
      </div>
      <br/>
    ';
    echo'
      <h6 align="center">Periodo desde el ' . date("d-m-Y", strtotime($FInicial)) . ' al '
        . date("d-m-Y", strtotime($FFinalImpresion))
      . '</h6>
    ';

    echo '
      <table width="100%">
      <tbody>
        <tr>
          <td align="center">
              <a data-seccion="MEDICINA" href="#" class="secciones btn btn-outline-primary btn-sm btn-block">MEDICINAS</a>
          </td>

          <td align="center">
            <a data-seccion="MISCELANEO" href="#" class="secciones btn btn-outline-danger btn-sm btn-block">MISCELÁNEOS</a>
          </td>
        </tr>
      </tbody>
    </table>
    </br>
    ';

    echo'
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Codigo interno</th>
            <th scope="col" class="CP-sticky">Codigo de barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Ultimo proveedor</th>
            <th scope="col" class="CP-sticky">Ultimo Lote</br>(En Rango)</th>
            <th scope="col" class="CP-sticky">Tiempo en Tienda</br>(Dias)</th>
            <th scope="col" class="CP-sticky">Precio Bs.</th>
            <th scope="col" class="CP-sticky">Última venta</th>
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["Id"];
      $FechaRegistro = $row["FechaTienda"];
      $Existencia = $row["Existencia"];
      $TiempoTienda = $row["TiempoTienda"];

      if(!is_null($FechaRegistro)) {
        $Diferencia1 = FG_Validar_Fechas($FechaRegistro->format("Y-m-d"), $FInicial);
        $Diferencia2 = FG_Validar_Fechas($FechaRegistro->format("Y-m-d"), $FFinalImpresion);
        $tipo = FG_Tipo_Producto($row['tipo']);
        $seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'MEDICINA';
        
        if(($Diferencia1 <= 0) && ($Diferencia2 >= 0)) {
          if ($tipo == $seccion) {

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

            $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);
            $Precio = number_format($Precio, 2, ',', '.');

            $UltimaVenta = ($row['UltimaVenta']) ? $row['UltimaVenta']->format('d-m-Y') : '-';


            echo '
              <tr>
                <td align="center"><strong>'.intval($contador).'</strong></td>
                <td align="left">'.$row["CodigoArticulo"].'</td>
                <td align="left">'.$row["CodigoBarra"].'</td>
                <td align="left" class="CP-barrido">
                    <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
                        .FG_Limpiar_Texto($row["Descripcion"])
                    .'</a>
                </td>
                <td align="center">'.intval($Existencia).'</td>
                <td align="left">'.FG_Limpiar_Texto($row["UltimoProveedorNombre"]).'</td>
                <td align="center">'.$FechaRegistro->format("d-m-Y").'</td>
                <td align="center">'.$TiempoTienda.'</td>
                <td align="center">'.$Precio.'</td>
                <td align="center">'.$UltimaVenta.'</td>
              </tr>
            ';
            $contador++;
          }
        }
      }
    }
    echo '
      </tbody>
    </table>
    ';
    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R9Q_Productos_Surtir
    FUNCION: Consulta las Veces Compradas a proveedores y las unidades compradas
    RETORNO: Tabla con los articulos, las veces compradas y las unidades compradas
    Autos: Ing. Manuel Henriquez
  */
  function R9Q_Productos_Surtir($FInicial, $FFinal) {
    $sql = "
      SELECT
      InvArticulo.Id,
      (ISNULL((SELECT InvArticuloAtributo.InvArticuloId FROM InvArticuloAtributo WHERE InvArticuloAtributo.InvAtributoId = (SELECT InvAtributo.Id FROM InvAtributo WHERE InvAtributo.Descripcion = 'Medicina') AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS tipo,
      InvArticulo.CodigoArticulo,
      (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
      (SELECT TOP 1
        GenPersona.Nombre
        FROM ComFacturaDetalle
        INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
        INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
        INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
        WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
        ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre,
        (SELECT TOP 1 CONVERT(DATE,VenFactura.FechaDocumento) FROM VenFactura INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id ORDER BY FechaDocumento DESC) AS UltimaVenta,
      InvArticulo.Descripcion,
      (SELECT
      (ROUND(CAST(SUM (InvLoteAlmacen.Existencia) AS DECIMAL(38,0)),2,0))
      FROM InvLoteAlmacen
      WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
      AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) as Existencia,
      (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia FROM InvLoteAlmacen WHERE(InvLoteAlmacen.InvAlmacenId = 1) AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen1,
      (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia FROM InvLoteAlmacen WHERE(InvLoteAlmacen.InvAlmacenId = 2) AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen2, (ISNULL((SELECT InvArticuloAtributo.InvArticuloId FROM InvArticuloAtributo WHERE InvArticuloAtributo.InvAtributoId = (SELECT InvAtributo.Id FROM InvAtributo WHERE InvAtributo.Descripcion = 'Troquelados' OR  InvAtributo.Descripcion = 'troquelados') AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Troquelado,
      (ISNULL(InvArticulo.FinConceptoImptoIdCompra,CAST(0 AS INT))) AS Impuesto,
      ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad FROM VenCondicionVenta WHERE VenCondicionVenta.Id = (SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id FROM VenCondicionVenta_VenCondicionVentaArticulo WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadArticulo,
      ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad FROM VenCondicionVenta WHERE VenCondicionVenta.id = (SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id FROM VenCondicionVenta_VenCondicionVentaCategoria WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadCategoria,
      (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioTroquelado FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE(InvLoteAlmacen.InvAlmacenId = '1') AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen1,
      (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioCompraBruto FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) AND (InvLoteAlmacen.InvAlmacenId = '1') ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen1,
      (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioTroquelado FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE(InvLoteAlmacen.InvAlmacenId = '2') AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen2,
      (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioCompraBruto FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) AND (InvLoteAlmacen.InvAlmacenId = '2') ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen2,
      (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioCompraBruto FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBruto,
      (SELECT TOP 1
      CONVERT(DATE, Invlote.Auditoria_FechaCreacion) AS FechaTienda
      FROM InvLoteAlmacen
      INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
      WHERE
      Invlote.InvArticuloId = InvArticulo.Id 
      AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) 
      AND InvLoteAlmacen.Existencia > 0
      ORDER BY CONVERT(DATE, Invlote.Auditoria_FechaCreacion) DESC)  AS FechaTienda,
      (SELECT TOP 1
      DATEDIFF(DAY, CONVERT(DATE, Invlote.Auditoria_FechaCreacion), GETDATE())
      FROM InvLoteAlmacen
      INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
      WHERE
      Invlote.InvArticuloId = InvArticulo.Id 
      AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) 
      AND InvLoteAlmacen.Existencia > 0
      ORDER BY CONVERT(DATE, Invlote.Auditoria_FechaCreacion) DESC) AS TiempoTienda
      FROM ComFacturaDetalle
      INNER JOIN InvArticulo ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
      INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
      WHERE (ComFactura.FechaRegistro > '$FInicial') 
      AND (ComFactura.FechaRegistro < '$FFinal')
      AND (((SELECT
      (ROUND(CAST(SUM (InvLoteAlmacen.Existencia) AS DECIMAL(38,0)),2,0))
      FROM InvLoteAlmacen
      WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
      AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)))>0)
      GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, FechaRegistro, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
      ORDER BY FechaTienda ASC
    ";
    return $sql;
  }
?>
