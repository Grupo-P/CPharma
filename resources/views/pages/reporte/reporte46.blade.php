@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
  <style>
    * {box-sizing:border-box;}

    .autocomplete {position:relative; display:inline-block;}

    input {
      border:1px solid transparent;
      background-color:#f1f1f1;
      border-radius:5px;
      padding:10px;
      font-size:16px;
    }

    input[type=text] {background-color:#f1f1f1; width:100%;}

    .autocomplete-items {
      position:absolute;
      border:1px solid #d4d4d4;
      border-bottom:none;
      border-top:none;
      z-index:99;
      top:100%;
      left:0;
      right:0;
    }

    .autocomplete-items div {
      padding:10px;
      cursor:pointer;
      background-color:#fff;
      border-bottom:1px solid #d4d4d4;
    }

    .autocomplete-items div:hover {background-color:#e9e9e9;}
    .autocomplete-active {background-color:DodgerBlue !important; color:#fff;}
  </style>
@endsection


@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
      Compras por archivo
    </h1>
  <hr class="row align-items-start col-12">

  <?php
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');

    set_time_limit(10000);

    if(isset($_GET['SEDE'])) {
      echo '
        <h1 class="h5 text-success"  align="left">
          <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).
        '</h1>
      ';
    }
    echo '<hr class="row align-items-start col-12">';

    if (request()->hasFile('archivo') && (request()->file('archivo')->extension() != 'xlsx' && request()->file('archivo')->extension() != 'xls')) {
        echo '
            <form autocomplete="off" enctype="multipart/form-data" action="" method="POST" target="_blank">
              <div class="row">
                <div class="col"></div>

                <div class="col-6">
                  <div class="alert alert-danger text-center">Solo se admiten archivos Excel.</div>

                  <div class="alert alert-info">
                    <ul>
                        <li>La primera línea en archivo se reserva para el encabezado.</li>
                        <li>Los calumnas del Excel deben estar en el siguiente orden:<br>Código de barra, Descripción, Precio, Existencia.</li>
                        <li>El tiempo promedio por artículo es de 1.3 segundos, un Excel de 1000 artículos debería tardar 21 minutos.</li>
                    </ul>
                  </div>
                </div>

                <div class="col">
                </div>
              </div>

              <div class="row">
                <div class="col"></div>

                <div class="col">
                  <input style="background-color: white" required accept=".xls,.xlsx" type="file" name="archivo">
                </div>

                <div class="col form-inline">
                  <label for="opcion">Moneda:</label>

                  <select style="width: 70%" class="ml-3 form-control" id="opcion" name="opcion" required>
                    <option value""></option>
                    <option value"Dólares">Dólares</option>
                    <option value"Bolívares">Bolívares</option>
                  </select>
                </div>

                <div class="col">
                  <input id="SEDE" name="SEDE" type="hidden" value="';
                    print_r($_GET['SEDE']);
                  echo'">

                  <input type="submit" value="Buscar" class="mt-1 btn btn-outline-success">
                </div>
              </div>
            </form>
          ';
    }

    elseif(isset($_POST['opcion'])) {
      $InicioCarga = new DateTime("now");
      R46_Compras_Archivo($_POST['SEDE'],$_POST['opcion']);
      FG_Guardar_Auditoria('CONSULTAR','REPORTE','Compras por archivo');

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }

    else {
      echo '
        <form autocomplete="off" enctype="multipart/form-data" action="" method="POST" target="_blank">
          <div class="row">
            <div class="col"></div>

            <div class="col-6">
              <div class="alert alert-info">
                <ul>
                    <li>La primera línea en archivo se reserva para el encabezado.</li>
                    <li>Los calumnas del Excel deben estar en el siguiente orden:<br>Código de barra, Descripción, Precio, Existencia.</li>
                    <li>El tiempo promedio por artículo es de 1.3 segundos, un Excel de 1000 artículos debería tardar 21 minutos.</li>
                </ul>
              </div>
            </div>

            <div class="col">
            </div>
          </div>

          <div class="row">
            <div class="col"></div>

            <div class="col">
              <input style="background-color: white" required accept=".xls,.xlsx" type="file" name="archivo">
            </div>

            <div class="col form-inline">
              <label for="opcion">Moneda:</label>

              <select style="width: 70%" class="ml-3 form-control" id="opcion" name="opcion" required>
                <option value""></option>
                <option value"Dólares">Dólares</option>
                <option value"Bolívares">Bolívares</option>
              </select>
            </div>

            <div class="col">
              <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($_GET['SEDE']);
              echo'">

              <input type="submit" value="Buscar" class="mt-1 btn btn-outline-success">
            </div>
          </div>
        </form>
      ';
    }
  ?>
@endsection

<?php
  /*
    TITULO: R46_Compras_Archivo
    PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
                [$opcion] Lista de precios de articulos dolarizados o no
    FUNCION: Arma una lista de productos con su precio
    RETORNO: No aplica
  */
  function R46_Compras_Archivo($SedeConnection,$opcion) {

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();
    $Hoy = new DateTime('now');
    $Hoy = $Hoy->format('Y-m-d');

    $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['archivo']['tmp_name']);
    $data        = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    $i = 0;

    $procesados = '';
    $noProcesados = '';

    $contadorProcesados = 1;
    $contadorNoProcesados = 1;

    $codigos = [];

    foreach ($data as $item) {
      if ($i != 0) {
        if ($item['A'] == '' && $item['B'] == '' && $item['C'] == '' && $item['D'] == '') {
            continue;
        }

        if ($item['A'] != '') {
            $codigos[] = preg_replace('([^A-Za-z0-9])', '', $item['A']);
        } else {
              $noProcesados .= '
                <tr>
                  <td class="text-center">'.$contadorNoProcesados.'</td>
                  <td class="text-center">'.$item['A'].'</td>
                  <td class="text-center">'.FG_Limpiar_Texto($item['B']).'</td>
                  <td class="text-center">'.number_format((float) $item['C'], 2, ',', '.').'</td>
                  <td class="text-center">'.intval($item['D']).'</td>
                  <td class="text-center">Artículo incompleto</td>
                </tr>
              ';

              $contadorNoProcesados++;
          }
      }

      if ($i == 5000) {
        break;
      }

      $i = $i + 1;
    }

    $codigos = array_filter($codigos);
    $codigosChunk = array_chunk($codigos, 50);


    for ($j=0; $j <= count($codigosChunk) - 1; $j++) {
        $codigosSeparadoPorComa = implode("', '", $codigosChunk[$j]);

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
        --Marca
         (SELECT InvMarca.Nombre FROM InvMarca WHERE InvMarca.Id = InvArticulo.InvMarcaId) as Marca,
         --Fecha Creacion
         InvArticulo.Auditoria_FechaCreacion as FechaCreacion,
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
        WHERE (SELECT CodigoBarra
        FROM InvCodigoBarra
        WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
        AND InvCodigoBarra.EsPrincipal = 1) IN ('$codigosSeparadoPorComa')
        --Agrupamientos
        GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId,InvArticulo.InvMarcaId,InvArticulo.Auditoria_FechaCreacion
        --Ordanamiento
        ORDER BY InvArticulo.Id ASC
        ";

        $result = sqlsrv_query($conn, $sql);

        while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

            $precio = FG_Calculo_Precio_Alfa($row['Existencia'], $row['ExistenciaAlmacen1'], $row['ExistenciaAlmacen2'], $row['Troquelado'], $row['UtilidadArticulo'], $row['UtilidadCategoria'], $row['TroquelAlmacen1'], $row['PrecioCompraBrutoAlmacen1'], $row['TroquelAlmacen2'], $row['PrecioCompraBrutoAlmacen2'], $row['PrecioCompraBruto'], $row['Impuesto'], 'CON_EXISTENCIA');

            $tasa = DB::table('tasa_ventas')->where('moneda', 'Dolar')->value('tasa');
            $tasa = ($tasa) ? $tasa : 0;

            $precioBs = ($precio) ? number_format($precio, 2, ',', '.') : '';
            $precioDs = ($precio and $tasa) ? number_format($precio / $tasa, 2, ',', '.') : '';
            $ultimaVenta = ($row['UltimaVenta']) ? $row['UltimaVenta']->format('Y-m-d') : '';

            $codigosProcesados[] = $row;

            $indice = array_search($row['CodigoBarra'], array_column($data, 'A'));
            $indice = $indice + 1;

            $procesados .= '
                <tr>
                  <td class="text-center">'.$contadorProcesados.'</td>
                  <td class="text-center">'.$data[$indice]['A'].'</td>
                  <td class="text-center">'.FG_Limpiar_Texto($data[$indice]['B']).'</td>
                  <td class="text-center CP-barrido"><a href="/reporte2?Id='.$row['IdArticulo'].'&SEDE='.$_GET['SEDE'].'" style="text-decoration: none; color: black;" target="_blank">'.FG_Limpiar_Texto($row['Descripcion']).'</a></td>
                  <td class="text-center">'.intval($data[$indice]['D']).'</td>
                  <td class="text-center">'.intval($row['Existencia']).'</td>
                  <td class="text-center">'.number_format((float) $data[$indice]['C'], 2, ',', '.').'</td>
                  <td class="text-center">'.$precioBs.'</td>
                  <td class="text-center">'.$precioDs.'</td>
                  <td class="text-center">'.$ultimaVenta.'</td>
                  <td class="text-center CP-barrido"><a href="/reporte7?Nombre='.FG_Limpiar_Texto($row['UltimoProveedorNombre']).'&Id='.$row['UltimoProveedorID'].'&SEDE='.$_GET['SEDE'].'" style="text-decoration: none; color: black;" target="_blank">'.FG_Limpiar_Texto($row['UltimoProveedorNombre']).'</a></td>
                </tr>
            ';

            $contadorProcesados++;

        }

    }


    $codigosProcesados = array_column($codigosProcesados, 'CodigoBarra');
    $codigosNoProcesados = array_diff($codigos, $codigosProcesados);

    foreach ($codigosNoProcesados as $item) {
        $indice = array_search($item, array_column($data, 'A'));
        $indice = $indice + 1;

        $noProcesados .= '
            <tr>
              <td class="text-center">'.$contadorNoProcesados.'</td>
              <td class="text-center">'.$data[$indice]['A'].'</td>
              <td class="text-center">'.FG_Limpiar_Texto($data[$indice]['B']).'</td>
              <td class="text-center">'.number_format((float) trim($data[$indice]['C']), 2, ',', '.').'</td>
              <td class="text-center">'.intval($data[$indice]['D']).'</td>
              <td class="text-center">Artículo no codificado</td>
            </tr>
          ';

          $contadorNoProcesados++;
    }

    echo '
      <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
        <div class="input-group-prepend">
          <span class="input-group-text purple lighten-3" id="basic-text1">
            <i class="fas fa-search text-white" aria-hidden="true"></i>
          </span>
        </div>
        <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
      </div>
      <br/>
    ';

    echo '
      <table class="table table-striped table-bordered col-12 mt-5">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">Procesados</th>
          </tr>
        </thead>
      </table>

      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">Nro.</th>
            <th scope="col" class="CP-sticky">Codigo Excel</th>
            <th scope="col" class="CP-sticky">Descripcion Excel</th>
            <th scope="col" class="CP-sticky">Descripcion Farmacia</th>
            <th scope="col" class="CP-sticky">Existencia Excel</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Costo Excel</th>
            <th scope="col" class="CP-sticky">Precio '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Precio '.SigDolar.'</th>
            <th scope="col" class="CP-sticky">Ultima Venta</th>
            <th scope="col" class="CP-sticky">Ultimo proveedor</th>
          </tr>
        </thead>

        <tbody>
          '.$procesados.'
        </tbody>
      </table>
    ';



    echo '
      <table class="table table-striped table-bordered col-12 mt-5">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">No procesados</th>
          </tr>
        </thead>
      </table>

      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">Nro.</th>
            <th scope="col" class="CP-sticky">Codigo Excel</th>
            <th scope="col" class="CP-sticky">Descripcion Excel</th>
            <th scope="col" class="CP-sticky">Costo Excel</td>
            <th scope="col" class="CP-sticky">Existencia Excel</td>
            <th scope="col" class="CP-sticky">Causa</td>
          </tr>
        </thead>
        <tbody>
            '.$noProcesados.'
        </tbody>
      </table>
    ';
  }
?>
