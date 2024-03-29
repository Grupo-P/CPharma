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

  <link rel="stylesheet" href="/assets/jquery/jquery-ui-last.css">
  <script src="/assets/jquery/jquery-ui-last.js"></script>

  <script>
        @php
            include(app_path().'\functions\config.php');
            include(app_path().'\functions\functions.php');
            include(app_path().'\functions\querys_mysql.php');
            include(app_path().'\functions\querys_sqlserver.php');


            $SedeConnection = FG_Mi_Ubicacion();

            $conn = FG_Conectar_Smartpharma($SedeConnection);
            $descripcion = [];
            $codigo = [];
            $i = 0;

            $sql = "
                SELECT
                    InvArticulo.Id AS id,
                    InvArticulo.CodigoArticulo AS codigo,
                    InvArticulo.Descripcion AS descripcion,
                    (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id AND InvCodigoBarra.EsPrincipal = 1) AS codigo_barra
                FROM InvArticulo
            ";

            $query = sqlsrv_query($conn, $sql);

            while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                $descripcion[$i]['id'] = $row['id'];
                $descripcion[$i]['label'] = mb_convert_encoding($row['descripcion'], 'UTF-8', 'UTF-8');
                $descripcion[$i]['value'] = mb_convert_encoding($row['descripcion'], 'UTF-8', 'UTF-8');

                $descripcion[$i]['label'] = str_replace('???', '¡¡¡', $descripcion[$i]['label']);
                $descripcion[$i]['value'] = str_replace('???', '¡¡¡', $descripcion[$i]['value']);

                $descripcion[$i]['label'] = str_replace('?', 'Ñ', $descripcion[$i]['label']);
                $descripcion[$i]['value'] = str_replace('?', 'Ñ', $descripcion[$i]['value']);

                $codigo[$i]['id'] = $row['id'];
                $codigo[$i]['label'] = mb_convert_encoding($row['codigo_barra'], 'UTF-8', 'UTF-8');
                $codigo[$i]['value'] = mb_convert_encoding($row['codigo_barra'], 'UTF-8', 'UTF-8');

                $interno[$i]['id'] = $row['id'];
                $interno[$i]['label'] = mb_convert_encoding($row['codigo'], 'UTF-8', 'UTF-8');
                $interno[$i]['value'] = mb_convert_encoding($row['codigo'], 'UTF-8', 'UTF-8');

                $i++;
            }
        @endphp



        $(document).ready(function () {
            $('.inputDescripcion').autocomplete({
                source: {!! json_encode($descripcion) !!},
                autoFocus: true,
                minLength: 3,
                select: function (event, ui) {
                    $('#myId').val(ui.item.id);
                }
            });

            $('#myInputCB').autocomplete({
                source: {!! json_encode($codigo) !!},
                autoFocus: true,
                minLength: 5,
                select: function (event, ui) {
                    $('#myId').val(ui.item.id);
                }
            });

            $('#myInputCI').autocomplete({
                source: {!! json_encode($interno) !!},
                autoFocus: true,
                minLength: 3,
                select: function (event, ui) {
                    $('#myId').val(ui.item.id);
                }
            });
        });
  </script>
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Revisión de inventarios físicos
  </h1>
  <hr class="row align-items-start col-12">
  <?php


    if (isset($_GET['SEDE'])) {
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';

    if (isset($_GET['Id'])) {
      $InicioCarga = new DateTime("now");

      R39_Revision_Inventarios_Fisicos($_GET['SEDE'],$_GET['Id']);
      FG_Guardar_Auditoria('CONSULTAR','REPORTE','Revision de inventarios fisicos');

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else {
      $InicioCarga = new DateTime("now");

      echo '
        <form id="form" autocomplete="off" action="" target="_blank">
          <table style="width:100%;">
            <tr>
              <td align="center">Fecha Inicio:</td>
              <td>
                <input id="fechaInicio" type="date" name="fechaInicio" required style="width:100%;">
              </td>

              <td align="center">Fecha Fin:</td>
              <td align="right">
                <input id="fechaFin" name="fechaFin" type="date" required style="width:100%;">
                <input id="SEDE" name="SEDE" type="hidden" value="'; print_r($_GET['SEDE']);
                echo'">
              </td>
            </tr>

            <tr>
              <td colspan="4">&nbsp;</td>
            </tr>

            <tr>
              <td colspan="4">
                <div class="autocomplete" style="width:90%;">
                  <input id="myInput" class="inputDescripcion" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo..." >
                </div>

                <input id="myId" name="Id" type="hidden">

                <input type="submit" value="Buscar" class="btn btn-outline-success" style="width:9%;">
              </td>
            </tr>

            <tr>
              <td colspan="4">&nbsp;</td>
            </tr>

            <tr>
              <td colspan="4">
                <div class="autocomplete" style="width:90%;">
                  <input id="myInputCB" type="text" name="CodBar" placeholder="Ingrese el codigo de barra del articulo...">
                  <input id="myIdCB" name="IdCB" type="hidden">
                </div>
                <input id="SEDE" name="SEDE" type="hidden" value="';
                  print_r($_GET['SEDE']);
                  echo'">
                <input type="submit" value="Buscar" class="btn btn-outline-success" style="width:9%;">
              </td>
            </tr>

            <tr>
              <td colspan="4">&nbsp;</td>
            </tr>

            <tr>
              <td colspan="4">
                <div class="autocomplete" style="width:90%;">
                  <input id="myInputCI" type="text" name="CodInt" placeholder="Ingrese el codigo interno del articulo...">
                  <input id="myIdCI" name="IdCI" type="hidden">
                </div>
                <input id="SEDE" name="SEDE" type="hidden" value="';
                  print_r($_GET['SEDE']);
                  echo'">
                <input type="submit" value="Buscar" class="btn btn-outline-success" style="width:9%;">
              </td>
            </tr>
          </table>
        </form>
      ';

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
  ?>
@endsection

<?php
  /**********************************************************************************/
  /*
    TITULO: R39_Revision_Inventarios_Fisicos
    FUNCION: Armar una tabla del historico de compra del articulo
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R39_Revision_Inventarios_Fisicos($SedeConnection,$IdArticulo) {
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $sql4 = R39Q_Detalle_Articulo($_GET['Id']);
    $result1 = sqlsrv_query($conn,$sql4);
    $row1 = sqlsrv_fetch_array($result1);

    $codigo_interno = $row1['CodigoInterno'];
    $codigo_barra = $row1['CodigoBarra'];
    $descripcion = $row1['Descripcion'];
    $existencia = $row1['Existencia'];

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

    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Código Interno</th>
          <th scope="col">Código de barra</th>
          <th scope="col">Descripción</th>
          <th scope="col">Existencia actual</th>
          <th scope="col">Rango de búsqueda</th>
        </tr>
      </thead>
      <tbody>
        <tr>
            <td align="center">'.$codigo_interno.'</td>
            <td align="center">'.$codigo_barra.'</td>
            <td align="center">'.$descripcion.'</td>
            <td align="center">'.$existencia.'</td>
            <td align="center">'.date_create($_GET['fechaInicio'])->format('d/m/Y').' al '.date_create($_GET['fechaFin'])->format('d/m/Y').'</td>
        </tr>
      </tbody>
    </table>


    <br/>

    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nro. conteo</th>
          <th scope="col">Fecha y hora</th>
          <th scope="col">Origen</th>
          <th scope="col">Motivo</th>
          <th scope="col">Estado</th>
          <th scope="col">Operador</th>
          <th scope="col">Existencia en conteo</th>
          <th scope="col">Conteo</th>
          <th scope="col">Diferencia</th>
          <th scope="col">Reconteo</th>
          <th scope="col">Diferencia reconteo</th>
        </tr>
      </thead>
      <tbody>
    ';

    $result2 = mysqli_query($connCPharma, "SELECT inventarios.codigo AS numero_conteo, inventarios.fecha_generado AS fecha, inventarios.origen_conteo AS origen, inventarios.motivo_conteo AS motivo, inventarios.estatus AS estado, inventarios.operador_generado AS operador, inventario_detalles.existencia_actual AS existencia_conteo, inventario_detalles.conteo AS conteo, inventario_detalles.re_conteo AS reconteo FROM inventario_detalles LEFT JOIN inventarios ON inventario_detalles.codigo_conteo = inventarios.codigo WHERE inventario_detalles.id_articulo = '$IdArticulo'");

    $contador = intval(1);

    while ($row2 = mysqli_fetch_array($result2)) {
      $numero_conteo = $row2['numero_conteo'];
      $fecha = date_format(date_create($row2['fecha']), 'd-m-Y h:i:s a');
      $origen = $row2['origen'];
      $motivo = $row2['motivo'];
      $estado = $row2['estado'];
      $operador = $row2['operador'];
      $existencia_conteo = $row2['existencia_conteo'];
      $conteo = $row2['conteo'];
      $diferencia = ($row2['conteo'] != '') ? $conteo - $existencia_conteo : '';
      $reconteo = $row2['reconteo'];
      $diferencia_reconteo = ($row2['reconteo'] != '') ? $reconteo - $existencia_conteo : '';


      echo '<tr>';

      echo '<td align="center"><strong>'.$contador.'</strong></td>';
      echo '<td align="center">'.$numero_conteo.'</td>';
      echo '<td align="center">'.$fecha.'</td>';
      echo '<td align="center">'.$origen.'</td>';
      echo '<td align="center">'.$motivo.'</td>';
      echo '<td align="center">'.$estado.'</td>';
      echo '<td align="center">'.$operador.'</td>';
      echo '<td align="center">'.$existencia_conteo.'</td>';
      echo '<td align="center">'.$conteo.'</td>';
      echo '<td align="center">'.$diferencia.'</td>';
      echo '<td align="center">'.$reconteo.'</td>';
      echo '<td align="center">'.$diferencia_reconteo.'</td>';

      echo '</tr>';

      $contador++;
    }

    echo '</tbody>
    </table>';

    mysqli_close($connCPharma);
    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R39Q_Lista_Articulos
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R39Q_Lista_Articulos() {
    $sql = "
      SELECT
      InvArticulo.Descripcion,
      InvArticulo.Id
      FROM InvArticulo
      ORDER BY InvArticulo.Descripcion ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R39Q_Lista_Articulos_CodBarra
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R39Q_Lista_Articulos_CodBarra() {
    $sql = "
      SELECT
      (SELECT CodigoBarra
      FROM InvCodigoBarra
      WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
      AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
      InvArticulo.Id
      FROM InvArticulo
      ORDER BY CodigoBarra ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R39Q_Lista_Articulos_CodInterno
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R39Q_Lista_Articulos_CodInterno() {
    $sql = "
      SELECT
        InvArticulo.CodigoArticulo,
        InvArticulo.Id
      FROM InvArticulo
      ORDER BY InvArticulo.CodigoArticulo ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R39Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R39Q_Detalle_Articulo($IdArticulo) {
    $sql = "
      SELECT
--Id Articulo
    InvArticulo.Id AS IdArticulo,
--Categoria Articulo
  InvArticulo.InvCategoriaId ,
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
--Marca
    InvMarca.Nombre as Marca,
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
--Ultimo Precio Sin Iva
    (SELECT TOP 1
    (ROUND(CAST((VenVentaDetalle.M_PrecioNeto) AS DECIMAL(38,2)),2,0))
    FROM VenVenta
    INNER JOIN VenVentaDetalle ON VenVentaDetalle.VenVentaId = VenVenta.Id
    WHERE VenVentaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY VenVenta.FechaDocumentoVenta DESC) AS UltimoPrecio,
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
    LEFT JOIN InvMarca ON InvMarca.Id = InvArticulo.InvMarcaId
--Condicionales
    WHERE InvArticulo.Id = '$IdArticulo'
--Agrupamientos
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId, InvMarca.Nombre
--Ordanamiento
    ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }
?>
