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

    @page {size: landscape}

    @media print{
      .saltoDePagina {
        display:block;
        page-break-before:always;
      }

    }
    </style>
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Cruce de aplicación de consulta
  </h1>
  <hr class="row align-items-start col-12">

<?php
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');
  $_GET['SEDE'] = FG_Mi_Ubicacion();

  if (isset($_GET['SEDE'])){
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
  }

  echo '<hr class="row align-items-start col-12">';

  $InicioCarga = new DateTime("now");

  R47_Cruce_Aplicacion_Consultas($_GET['SEDE']);

  $FinCarga = new DateTime("now");
  $IntervalCarga = $InicioCarga->diff($FinCarga);
  echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
?>
@endsection


<?php
  function R47_Cruce_Aplicacion_Consultas($SedeConnection){

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R47Q_Cruce_Aplicacion_Consultas();
    $result = sqlsrv_query($conn,$sql1);

    $connCPharma = FG_Conectar_CPharma();

    echo '
    <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1">
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myFilter" onkeyup="FilterAllTableConflicto()">
    </div>
    <br/>
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
            <th scope="col" class="CP-sticky">Precio ' . SigVe . '</th>
            <th scope="col" class="CP-sticky">Ultima compra</th>
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $connMood = FG_Conectar_Mod_Atte_Clientes($_GET['SEDE']);

      // Verifica si existe cruce
      $codigo_barra = $row['codigo_barra'];

      $sql = "SELECT * FROM InAplArt WHERE InAplArt.CoBarra = '$codigo_barra'";
      $query = sqlsrv_query($connMood, $sql);

      if (sqlsrv_has_rows($query)) {
        continue;
      }

      $sql = "SELECT * FROM InComArt WHERE InComArt.CoBarra = '$codigo_barra'";
      $query = sqlsrv_query($connMood, $sql);

      if (sqlsrv_has_rows($query)) {
        continue;
      }

      $descripcion = FG_Limpiar_Texto($row['descripcion']);

      $ultima_compra = $row['ultima_compra']->format('d/m/Y');

      $existencia = $row['existencia'];
      $existencia_almacen_1 = $row['existencia_almacen_1'];
      $existencia_almacen_2 = $row['existencia_almacen_2'];
      $troquelado = $row['troquelado'];
      $utilidad_articulo = $row['utilidad_articulo'];
      $utilidad_categoria = $row['utilidad_categoria'];
      $troquel_almacen_1 = $row['troquel_almacen_1'];
      $precio_compra_bruto_almacen_1 = $row['precio_compra_bruto_almacen_1'];
      $troquel_almacen_2 = $row['troquel_almacen_2'];
      $precio_compra_bruto_almacen_2 = $row['precio_compra_bruto_almacen_2'];
      $precio_compra_bruto = $row['precio_compra_bruto'];
      $iva = $row['iva'];
      $condicion_existencia = 'CON_EXISTENCIA';

      $precio = FG_Calculo_Precio_Alfa($existencia, $existencia_almacen_1, $existencia_almacen_2, $troquelado, $utilidad_articulo, $utilidad_categoria, $troquel_almacen_1, $precio_compra_bruto_almacen_1, $troquel_almacen_2, $precio_compra_bruto_almacen_2, $precio_compra_bruto, $iva, $condicion_existencia);
      $precio = number_format($precio, 2, ',', '.');

      echo '<tr>';
      echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="left">'.$row['codigo_interno'].'</td>';
      echo '<td align="center">'.$codigo_barra.'</td>';
      echo '<td align="center">'.$descripcion.'</td>';
      echo '<td align="center">'.$row['existencia'].'</td>';
      echo '<td align="center">'.$precio.'</td>';
      echo '<td align="center">'.$ultima_compra.'</td>';
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
    TITULO: R47Q_Cruce_Aplicacion_Consultas
    FUNCION: Busca los ultimos 50 ajustes
    RETORNO: lista de facturas
    DESAROLLADO POR: NISA DELGADO
  */
  function R47Q_Cruce_Aplicacion_Consultas() {
    $sql = "
      SELECT
        InvArticulo.CodigoArticulo AS codigo_interno,
        (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id AND InvCodigoBarra.EsPrincipal = 1) AS codigo_barra,
        InvArticulo.DescripcionLarga AS descripcion,
        (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia FROM InvLoteAlmacen WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS existencia,
        (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia FROM InvLoteAlmacen WHERE(InvLoteAlmacen.InvAlmacenId = 1) AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS existencia_almacen_1,
        (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia FROM InvLoteAlmacen WHERE(InvLoteAlmacen.InvAlmacenId = 2) AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS existencia_almacen_2,
        (ISNULL((SELECT InvArticuloAtributo.InvArticuloId FROM InvArticuloAtributo WHERE InvArticuloAtributo.InvAtributoId = (SELECT InvAtributo.Id FROM InvAtributo WHERE InvAtributo.Descripcion = 'Troquelados' OR  InvAtributo.Descripcion = 'troquelados') AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS troquelado,
        ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad FROM VenCondicionVenta WHERE VenCondicionVenta.Id = (SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id FROM VenCondicionVenta_VenCondicionVentaArticulo WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS utilidad_articulo,
        ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad FROM VenCondicionVenta WHERE VenCondicionVenta.id = (SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id FROM VenCondicionVenta_VenCondicionVentaCategoria WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS utilidad_categoria,
        (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioTroquelado FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE(InvLoteAlmacen.InvAlmacenId = '1') AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,4)),4,0)) AS troquel_almacen_1,
        (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioCompraBruto FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) AND (InvLoteAlmacen.InvAlmacenId = '1') ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS precio_compra_bruto_almacen_1,
        (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioTroquelado FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE(InvLoteAlmacen.InvAlmacenId = '2') AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS troquel_almacen_2,
        (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioCompraBruto FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) AND (InvLoteAlmacen.InvAlmacenId = '2') ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS precio_compra_bruto_almacen_2,
        (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioCompraBruto FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS precio_compra_bruto,
        (ISNULL(InvArticulo.FinConceptoImptoIdCompra,CAST(0 AS INT))) AS iva,
        (SELECT TOP 1 (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) FROM ComFacturaDetalle WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id ORDER BY ComFacturaDetalle.ComFacturaId DESC) AS ultima_compra
      FROM
        InvArticulo
      WHERE
        (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia FROM InvLoteAlmacen WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) > 0;
    ";

    return $sql;
  }
?>
