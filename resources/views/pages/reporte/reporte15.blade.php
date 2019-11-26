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
      Artículos Devaluados
    </h1>
  <hr class="row align-items-start col-12">

  <?php
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');

    if(isset($_GET['SEDE'])) {
      echo '
        <h1 class="h5 text-success"  align="left">
          <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).
        '</h1>
      ';
    }
    echo '<hr class="row align-items-start col-12">';

    if(isset($_GET['fechaInicio'])) {
      $InicioCarga = new DateTime("now");

      R15_Articulos_Devaluados($_GET['SEDE'],$_GET['fechaInicio']);
      FG_Guardar_Auditoria('CONSULTAR','REPORTE','Articulos Devaluados');

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else {
      echo '
        <form autocomplete="off" action="" target="_blank">
          <table style="width:100%;">
            <tr>
              <td align="center">
                <label for="fechaInicio">Fecha de inicio del reporte:</label>
              </td>

              <td>
                <input id="fechaInicio" type="date" name="fechaInicio" required style="width:100%;">
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
  /*
    TITULO: R15_Articulos_Devaluados
    PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
                [$FInicial] Fecha inicial del rango donde se buscara
    FUNCION: Arma una lista de productos por fallar
    RETORNO: No aplica
  */
  function R15_Articulos_Devaluados($SedeConnection,$FInicial) {
    
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();
    $Hoy = new DateTime('now');
    $Hoy = $Hoy->format('Y-m-d');
    $FInicialImpresion = date('d-m-Y',strtotime($FInicial));

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

    echo '<h6 align="center">Registro de articulos con fecha menor al '.$FInicialImpresion.'</h6>';

    echo '
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Codigo</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Precio '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Valor lote '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Ultimo lote</th>
            <th scope="col" class="CP-sticky">Tasa historico  '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Precio en  '.SigDolar.'</th>
            <th scope="col" class="CP-sticky">Valor lote  '.SigDolar.'</th>
            <th scope="col" class="CP-sticky">Dias en tienda</th>
            <th scope="col" class="CP-sticky">Ultimo proveedor</th>
          </tr>
        </thead>

        <tbody>
    ';

    $contador = 1;

    $sql2 = R12_Q_Articulos_Devaluados($FInicial);
    $result2 = sqlsrv_query($conn,$sql2);

    while($row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC)) {

      $IdArticulo = $row2["InvArticuloId"];
      $Dolarizado = FG_Producto_Dolarizado($row2["Dolarizado"]);
      
      if($Dolarizado == 'NO') {
        $UltimoLote = $row2['FechaLote'];

        if(!is_null($UltimoLote)) {
          $UltimoLote = $UltimoLote->format('Y-m-d');
          $Diferencia = FG_Validar_Fechas($FInicial,$UltimoLote);

          if($Diferencia < 0) {

            $Existencia = $row2["Existencia"];
            $TroquelAlmacen1 = $row2["TroquelAlmacen1"];
            $PrecioCompraBruto = $row2["PrecioCompraBruto"];
            $Utilidad = $row2["Utilidad"];
            $IsIVA = $row2["ConceptoImpuesto"];
            $TroquelAlmacen2 = $row2["TroquelAlmacen2"];

            $Precio = FG_Calculo_Precio($Existencia,$TroquelAlmacen1,$PrecioCompraBruto,$Utilidad,$IsIVA,$TroquelAlmacen2);
            $ValorLote = $Precio * intval($Existencia);

            $Tasa = FG_Tasa_Fecha($connCPharma,$UltimoLote);
            $Descripcion = FG_Limpiar_Texto($row2["Descripcion"]);

            echo '
              <tr>
                <td align="center"><strong>'.intval($contador).'</strong></td>
                  <td align="center">'.$row2["CodigoArticulo"].'</td>
                  <td align="left" class="CP-barrido">
                    <a href="/reporte10?Descrip='.$Descripcion.'&Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
                      .$Descripcion
                    .'</a>
                  </td>
                  <td align="center">'.number_format($Precio,2,"," ,"." ).'</td>
                  <td align="center">'.intval($Existencia).'</td>
                  <td align="center">'.number_format($ValorLote,2,"," ,"." ).'</td>
                  <td align="center">'.$row2['FechaLote']->format('d-m-Y').'</td>
            ';

            if($Tasa != 0) {
              $PrecioDolarizado = round(($Precio/$Tasa),2);
              $ValorLoteDolarizado = $PrecioDolarizado * intval($Existencia);

              echo '
                <td align="center">'." ".$Tasa." ".SigVe.'</td>
                <td align="center">'.number_format($PrecioDolarizado,2,"," ,"." ).'</td>
                <td align="center">'.number_format($ValorLoteDolarizado,2,"," ,"." ).'</td>
              ';
            }
            else {
              echo '
                <td align="center">0,00</td>
                <td align="center">0,00</td>
                <td align="center">0,00</td>
              ';
            }

            $UltimoProveedorNombre = FG_Limpiar_Texto($row2["UltimoProveedorNombre"]);
            $IdProveedor = $row2["Id"];

            $TiempoTienda = FG_Validar_Fechas($UltimoLote,$Hoy);

            echo '
                <td align="center">'.$TiempoTienda.'</td>
                <td align="left" class="CP-barrido">
                  <a href="/reporte7?Nombre='.$UltimoProveedorNombre.'&Id='.$IdProveedor.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
                    .$UltimoProveedorNombre
                  .'</a>
                </td>
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

    mysqli_close($connCPharma);
    sqlsrv_close($conn);
  }

  /*
  TITULO: R12_Q_Articulos_Devaluados
  PARAMETROS: [$FechaBandera] Fecha minima de dias en la tienda
  FUNCION: Armar una tabla temporal con los registros solicitados 
  RETORNO: Un String con la query pertinente
   */
  function R12_Q_Articulos_Devaluados($FechaBandera) {
    $sql = "
      SELECT DISTINCT
      --Campos
      (SELECT TOP 1
      InvLote.Id
      FROM InvLote
      WHERE InvLote.InvArticuloId = InvArticulo.Id
      ORDER BY InvLote.FechaEntrada DESC) AS Id,--Id del lote

      InvArticulo.Id AS InvArticuloId,--Id del articulo
      InvArticulo.CodigoArticulo,--Codigo interno

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

      --Precio Compra Bruto
      (ROUND(CAST((SELECT TOP 1
      InvLote.M_PrecioCompraBruto
      FROM InvLoteAlmacen
      INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
      WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
      AND (InvLoteAlmacen.Existencia>0)
      ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBruto,

      --Precio Troquel Almacen 2
      (ROUND(CAST((SELECT TOP 1
      InvLote.M_PrecioTroquelado
      FROM InvLoteAlmacen
      INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
      WHERE(InvLoteAlmacen.InvAlmacenId = '2')
      AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
      AND (InvLoteAlmacen.Existencia>0)
      ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen2,

      CONVERT(DATE, 
      (SELECT TOP 1
      InvLote.Auditoria_FechaCreacion
      FROM InvLoteAlmacen
      INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
      WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
      AND (InvLoteAlmacen.Existencia > 0)
      AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
      ORDER BY InvLote.Auditoria_FechaCreacion DESC)) AS FechaLote,--Fecha de creacion del lote

      (SELECT SUM(InvLoteAlmacen.Existencia)
      FROM InvLoteAlmacen
      WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
      AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)) AS Existencia,--Existencia

      InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto,
      InvArticulo.Descripcion,
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
      --Tabla de origen
      FROM InvLote
      --Tablas relacionadas
      INNER JOIN InvArticulo ON InvArticulo.Id = InvLote.InvArticuloId
      INNER JOIN InvLoteAlmacen ON InvLote.Id = InvLoteAlmacen.InvLoteId
      --Condiciones
      WHERE (InvLoteAlmacen.Existencia > 0) 
      AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
      AND (CONVERT(DATE,InvLoteAlmacen.Auditoria_FechaCreacion) < '$FechaBandera')
      --Ordenado
      ORDER BY FechaLote, InvArticulo.Descripcion DESC
    ";
    return $sql;
  }
?>