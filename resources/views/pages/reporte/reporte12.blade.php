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
    Detalle de movimientos
  </h1>
  <hr class="row align-items-start col-12">
  
  <?php
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');

    $ArtJson = "";
    $CodJson = "";

    if(isset($_GET['SEDE'])) {
      echo '
        <h1 class="h5 text-success"  align="left">
          <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE'])
        .'</h1>
      ';
    }
    echo '<hr class="row align-items-start col-12">';
    
    if(isset($_GET['Id'])) {
      $InicioCarga = new DateTime("now");

      R12_Detalle_Movimientos($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin'],$_GET['Id']);
      FG_Guardar_Auditoria('CONSULTAR','REPORTE','Detalle de movimientos');

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else {
      $InicioCarga = new DateTime("now");

      $sql = R12Q_Lista_Articulos();
      $ArtJson = FG_Armar_Json($sql,$_GET['SEDE']);

      $sql1 = R12Q_Lista_Articulos_CodBarra();
      $CodJson = FG_Armar_Json($sql1,$_GET['SEDE']);

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
                  <input id="myInput" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()" required>
                </div>

                <input id="myId" name="Id" type="hidden">

                <input type="submit" value="Buscar" class="btn btn-outline-success" style="width:9%;">
              </td>
            </tr>

            <tr>
              <td colspan="4">&nbsp;</td>
            </tr>
          </table>
        </form>

        <form autocomplete="off" action="" target="_blank">
          <div class="autocomplete" style="width:90%;">
            <input id="myInputCB" type="text" name="CodBar" placeholder="Ingrese el codigo de barra del articulo " onkeyup="conteoCB()">
            <input id="myIdCB" name="Id" type="hidden">
          </div>
          <input id="SEDE" name="SEDE" type="hidden" value="'; 
            print_r($_GET['SEDE']);
            echo'">
          <input type="submit" value="Buscar" class="btn btn-outline-success" style="width:9%;">
        </form>
      ';

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
  ?>
@endsection

@section('scriptsFoot')
  <?php
    if($ArtJson!=""){
  ?>
    <script type="text/javascript">
      ArrJs = eval(<?php echo $ArtJson ?>);
      autocompletado(document.getElementById("myInput"),document.getElementById("myId"), ArrJs);
    </script> 
  <?php
    }
  ?> 
  <?php
    if($CodJson!=""){
  ?>
    <script type="text/javascript">
      ArrJsCB = eval(<?php echo $CodJson ?>);
      autocompletadoCB(document.getElementById("myInputCB"),document.getElementById("myIdCB"), ArrJsCB);
    </script> 
  <?php
    }
  ?>  
@endsection

<?php
  /**********************************************************************************/
  /*
    TITULO: R12Q_Lista_Articulos
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
    ACTUALIZADO POR: MANUEL HENRIQUEZ
   */
  function R12Q_Lista_Articulos() {
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
    TITULO: R12Q_Lista_Articulos_CodBarra
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
    ACTUALIZADO POR: MANUEL HENRIQUEZ
   */
  function R12Q_Lista_Articulos_CodBarra() {
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
    TITULO: R12_Detalle_Movimientos
    FUNCION: arma la lista del troquel segun el articulo
    RETORNO: no aplica
    AUTOR: Ing. Manuel Henriquez
  */
  function R12_Detalle_Movimientos($SedeConnection,$FInicial,$FFinal,$IdArticulo) {
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $sql = R12Q_Detalle_Articulo($IdArticulo);
    $result = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

    $CodigoArticulo = $row["CodigoInterno"];
    $CodigoBarra = $row["CodigoBarra"];
    $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
    $Dolarizado = $row["Dolarizado"];
    $Existencia = $row["Existencia"];
    $IsIVA = $row["Impuesto"];
    $Utilidad = $row["Utilidad"];
    $TroquelAlmacen1 = $row["TroquelAlmacen1"];
    $TroquelAlmacen2 = $row["TroquelAlmacen2"];
    $PrecioCompraBruto = $row["PrecioCompraBruto"];

    $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
    $TasaActual = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
    $Precio = FG_Calculo_Precio($Existencia,$TroquelAlmacen1,$PrecioCompraBruto,$Utilidad,$IsIVA,$TroquelAlmacen2);

    mysqli_close($connCPharma);

    $FFinalImpresion = $FFinal;
    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    /*$RangoDias = FG_Rango_Dias($FInicial,$FFinal);
    $UnidadesVendidas = intval($row1["UnidadesVendidas"]);
    $VentaDiaria = FG_Venta_Diaria($UnidadesVendidas,$RangoDias);
    $DiasRestantes = FG_Dias_Restantes($Existencia,$VentaDiaria);*/

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

    echo '
      <h6 align="center">Periodo desde el '.date("d-m-Y",strtotime($FInicial)).' al '.date("d-m-Y",strtotime($FFinalImpresion)).'</h6>

      <table class="table table-striped table-bordered col-12 sortable">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Codigo</th>
            <th scope="col">Codigo de barra</td>
            <th scope="col">Descripcion</th>
            <th scope="col">Existencia</th>
            <th scope="col">Unidades vendidas</th>
            <th scope="col">Dias restantes</th>
            <th scope="col">Precio</br>(Con IVA) '.SigVe.'</th>
            <th scope="col">Dolarizado</th>
            <th scope="col">Tasa actual '.SigVe.'</th>
            <th scope="col">Precio en divisa</br>(Con IVA) '.SigDolar.'</th>
          </tr>
        </thead>

        <tbody>
    ';

    echo '
      <tr>
        <td>'.$CodigoArticulo.'</td>
        <td align="center">'.$CodigoBarra.'</td>
        <td align="left" class="CP-barrido">
          <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
            .$Descripcion
          .'</a>
        </td>
        <td align="center">'.intval($Existencia).'</td>
        <td align="center">-</td>
        <td align="center">-</td>
        <td align="center">'.number_format($Precio,2,"," ,"." ).'</td>
        <td align="center">'.$Dolarizado.'</td>
    ';

    if($TasaActual != 0) {

      $PrecioDolar = $Precio / $TasaActual;

      echo '
        <td align="center">'.number_format($TasaActual,2,"," ,"." ).'</td>
        <td align="center">'.number_format($PrecioDolar,2,"," ,"." ).'</td>
      ';
    }
    else{
      echo '
        <td align="center">0,00</td>
        <td align="center">0,00</td>
      ';
    }

    echo '
          </tr>
        </tbody>
      </table>
    ';

    echo '
      <br>
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

        <tbody>
    ';

    $sql3 = R12Q_Resumen_Movimiento($IdArticulo,$FInicial,$FFinal);
    $result2 = sqlsrv_query($conn,$sql3);

    $contador = 1;
    $FechaComparativa = date('d/m/Y',strtotime($FFinal));

    while($row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC)) {

      if($row2["FechaMovimiento"] >= $FechaComparativa) {
        continue;
      }

      echo '
        <tr>
          <td align="center"><strong>'.intval($contador).'</strong></td>
          <td align="center">'.$row2["FechaMovimiento"].'</td>
          <td align="center">'.utf8_encode($row2["Movimiento"]).'</td>
          <td align="center">'.$row2["Cantidad"].'</td>
        </tr>
      ';

      $contador++;
    }

    echo '
          </tbody>
      </table>
    ';

    echo '
      <br>
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

    $contador = 1;
    $sql4 = R12Q_Detalle_Movimiento($IdArticulo,$FInicial,$FFinal);
    $result3 = sqlsrv_query($conn,$sql4);

    while($row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC)) {

      if($row3["FechaMovimiento"]->format("Y-m-d") == $FFinal) {
        break;
      }

      echo '
        <tr>
          <td align="center"><strong>'.intval($contador).'</strong></td>
          <td align="center">'.$row3["FechaMovimiento"]->format("d/m/Y").'</td>
      ';

      echo '          
          <td align="center">'
            .date('h:i a',strtotime($row3["FechaMovimiento"]->format("H:i:s")))
          .'</td>
          <td align="center">'.utf8_encode($row3["Movimiento"]).'</td>
          <td align="center">'.$row3["Cantidad"].'</td>
        </tr>
      ';

      $contador++;
    }

    echo '
        </tbody>
      </table>
    ';

    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R12Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    AUTOR: Ing. Manuel Henriquez
   */
  function R12Q_Detalle_Articulo($IdArticulo) {
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

  /*
    TITULO: R12Q_Resumen_Movimiento
    FUNCION: Construir la consulta para el despliegue el resumen del reporte
    RETORNO: Un String con las instrucciones de la consulta
    AUTOR: Ing. Manuel Henriquez
   */
  function R12Q_Resumen_Movimiento($IdArticulo,$FInicial,$FFinal) {
    $sql = "
      SELECT 
        CONVERT(VARCHAR(10), InvMovimiento.FechaMovimiento, 103) AS FechaMovimiento,
        InvCausa.Descripcion AS Movimiento,
        ROUND(CAST(SUM(InvMovimiento.Cantidad) AS DECIMAL(38,0)),2,0) AS Cantidad
      FROM InvMovimiento
      INNER JOIN InvCausa ON InvMovimiento.InvCausaId=InvCausa.Id
      WHERE InvMovimiento.InvArticuloId='$IdArticulo'
      AND (CONVERT(DATE,InvMovimiento.FechaMovimiento) >= '$FInicial' AND CONVERT(DATE,InvMovimiento.FechaMovimiento) <= '$FFinal')
      GROUP BY CONVERT(VARCHAR(10), InvMovimiento.FechaMovimiento, 103), InvCausa.Descripcion
      ORDER BY FechaMovimiento ASC
    ";
    return $sql;
  }

  /*
    TITULO: R12Q_Detalle_Movimiento
    PARAMETROS: [$IdArticulo] Id del articulo actual
                [$FInicial] Fecha inicial del rango
                [$FFinal] Fecha final del rango
    FUNCION: Construir la consulta para el despliegue del reporte DetalleDeMovimiento
    RETORNO: Un String con las instrucciones de la consulta
    AUTOR: Ing. Manuel Henriquez
   */
  function R12Q_Detalle_Movimiento($IdArticulo,$FInicial,$FFinal) {
    $sql = "
      SELECT 
        InvMovimiento.InvLoteId,
        InvMovimiento.FechaMovimiento,
        InvMovimiento.InvCausaId,
        InvCausa.Descripcion AS Movimiento,
        ROUND(CAST(InvMovimiento.Cantidad AS DECIMAL(38,0)),2,0) AS Cantidad
      FROM InvMovimiento
      INNER JOIN InvCausa ON InvMovimiento.InvCausaId=InvCausa.Id
      WHERE InvMovimiento.InvArticuloId='$IdArticulo'
      AND (CONVERT(DATE,InvMovimiento.FechaMovimiento) >= '$FInicial' AND CONVERT(DATE,InvMovimiento.FechaMovimiento) <= '$FFinal')
      GROUP BY InvMovimiento.InvLoteId,InvMovimiento.FechaMovimiento,InvMovimiento.InvCausaId,InvCausa.Descripcion,InvMovimiento.Cantidad
      ORDER BY InvMovimiento.FechaMovimiento ASC
    ";
    return $sql;
  }
?>