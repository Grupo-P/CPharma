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
		Devoluciones de clientes
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

    R33_Devoluciones_Clientes($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin']);
    //FG_Guardar_Auditoria('CONSULTAR','REPORTE','Productos en falla');

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
  /*********************************************************************************/ 
  /*
    TITULO: R33_Devoluciones_Clientes
    FUNCION: Arma una lista de devoluciones de clientes
    RETORNO: No aplica
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R33_Devoluciones_Clientes($SedeConnection,$FInicial,$FFinal){
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    $sql5 = R33Q_Devoluciones_Clientes($FInicial,$FFinal);
    $result = sqlsrv_query($conn,$sql5);

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
          <th scope="col" class="CP-sticky">Nro.</th>
          <th scope="col" class="CP-sticky">Fecha Factura</th>
          <th scope="col" class="CP-sticky">Hora Factura</th>
          <th scope="col" class="CP-sticky">Nombre Cliente</th>
          <th scope="col" class="CP-sticky">Nro Factura</th>
          <th scope="col" class="CP-sticky">Caja Origen</th>
          <th scope="col" class="CP-sticky">Fecha Devolucion</td>
          <th scope="col" class="CP-sticky">Hora Devolucion</td>
          <th scope="col" class="CP-sticky">Numero Devolucion</td>
          <th scope="col" class="CP-sticky">Dias</td>
          <th scope="col" class="CP-sticky">Caja</th>
          <th scope="col" class="CP-sticky">Causa</th>
          <th scope="col" class="CP-sticky">Unidades</th>
          <th scope="col" class="CP-sticky">SKU</th>
          <th scope="col" class="CP-sticky">Monto Bs</th>
          <th scope="col" class="CP-sticky bg-warning">Tasa</th>
          <th scope="col" class="CP-sticky">Monto $</th>             
        </tr>
      </thead>
      <tbody>
    ';
    $contador = 1;

    $totalSku = 0;
    $totalBs = 0;
    $totalUnidades = 0;
    $totalDs = 0;

    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      /*$IdArticulo = $row["InvArticuloId"];
      $UnidadesVendidas = intval($row["TotalUnidadesVendidas"]);
      $TotalVenta = $row["TotalVenta"];

      $sql1 = R5Q_Detalle_Articulo($IdArticulo);
      $result1 = sqlsrv_query($conn,$sql1);
      $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

      $CodigoArticulo = $row1["CodigoInterno"];
      $CodigoBarra = $row1["CodigoBarra"];
      $Descripcion = FG_Limpiar_Texto($row1["Descripcion"]);
      $Existencia = $row1["Existencia"];
      $Tipo = FG_Tipo_Producto($row1["Tipo"]);
      $UltimaVenta = $row1["UltimaVenta"]; 
      $UltimoProveedorId =  $row1["UltimoProveedorID"];
      $UltimoProveedor =  FG_Limpiar_Texto($row1["UltimoProveedorNombre"]);
      $DiasEnFalla = $row1["TiempoSinVenta"];
      $Dolarizado = $row1["Dolarizado"];
      $IsIVA = $row1["Impuesto"];
      $UltimoLote = $row1["UltimoLote"];
      $UltimaCompra = $row1["UltimaCompra"];

      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
      $Gravado = FG_Producto_Gravado($IsIVA);

      $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
      $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
      $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
      $clasificacion = $RowCPharma['clasificacion'];
      $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";*/

      $fecha_factura = $row["fecha_factura"];
      $hora_factura = $row["hora_factura"];
      $nombre_cliente = $row["nombre_cliente"];
      $nro_factura = $row["nro_factura"];
      $caja_origen = $row["caja_origen"];
      $fecha_devolucion = $row["fecha_devolucion"];
      $hora_devolucion = $row["hora_devolucion"];
      $numero_devolucion = $row["numero_devolucion"];
      $dias = $row["dias"];
      $caja = $row["caja"];
      $causa = $row["causa"];
      $unidades = $row["unidades"];
      $unidades = intval($unidades);
      $totalUnidades = $totalUnidades + $unidades;
      $sku = $row["sku"];
      $totalSku = $totalSku + $row['sku'];

      $monto_bs = number_format($row["monto_bs"], 2, ',', '.');
      $totalBs = $totalBs + $row['monto_bs'];

      $TasaMercado = 
        DB::table('dolars')
        ->select('tasa')
        ->whereDate('fecha', '<=', $row['fecha_devolucion_sin_formato'])
        ->orderBy('fecha','desc')
        ->take(1)->get();


      if( (!empty($TasaMercado[0])) ) {
        $TM = $TasaMercado[0]->tasa;
        $TasaMercadoNumber = ($TasaMercado[0]->tasa);
        $TasaMercado = ($TasaMercado[0]->tasa);
        $TasaMercado = number_format($TasaMercado,2,"," ,"." );
      }
      else {
        $TM = 0.00;
        $TasaMercado = number_format(0.00,2,"," ,"." );
      }

      $montoDolares = floatval($row["monto_bs"]) / floatval($TasaMercadoNumber);

      $totalDs = $totalDs + $montoDolares;

      $montoDolares = number_format($montoDolares,2,"," ,"." );

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="center">'.$fecha_factura.'</td>';
      echo '<td align="center">'.$hora_factura.'</td>';
      echo '<td align="center">'.$nombre_cliente.'</td>';
      echo '<td align="center">'.$nro_factura.'</td>';
      echo '<td align="center">'.$caja_origen.'</td>';
      echo '<td align="center">'.$fecha_devolucion.'</td>';
      echo '<td align="center">'.$hora_devolucion.'</td>';
      echo '<td align="center">'.$numero_devolucion.'</td>';
      echo '<td align="center">'.$dias.'</td>';
      echo '<td align="center">'.$caja.'</td>';
      echo '<td align="center">'.$causa.'</td>';
      echo '<td align="center">'.$unidades.'</td>';
      echo '<td align="center">'.$sku.'</td>';
      echo '<td align="center">'.$monto_bs. ' ' . SigVe . '</td>';
      echo '<td align="center">'.$TasaMercado. ' ' . SigVe . '</td>';
      echo '<td align="center">'.SigDolar.$montoDolares.'</td>';

      /*echo
      '<td align="center" class="CP-barrido">
      <a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImp.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
        .intval($UnidadesVendidas).
      '</a>
      </td>';
      echo '<td align="center">'.number_format($TotalVenta,2,"," ,"." ).'</td>';

      if(($UltimaVenta)){
        echo '<td align="center">'.$UltimaVenta->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center"> - </td>';
      }

      echo '<td align="center">'.intval($DiasEnFalla).'</td>';

      if(!is_null($UltimoLote)){
        echo '<td align="center">'.$UltimoLote->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center"> - </td>';
      }

      if(!is_null($UltimaCompra)){
        echo '<td align="center" class="bg-warning">'.$UltimaCompra->format('d-m-Y').'</td>';
      }
      else{
        echo '<td align="center" class="bg-warning"> - </td>';
      }

      echo 
      '<td align="left" class="CP-barrido">
      <a href="/reporte7?Nombre='.$UltimoProveedor.'&Id='.$UltimoProveedorId.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
        .$UltimoProveedor.
      '</a>
      </td>';
      echo '</tr>';*/
      $contador++;
    }

    $totalBs = number_format($totalBs, 2, ',', '.');
    $totalDs = number_format($totalDs, 2, ',', '.');

    echo '<tr>';
    echo '<td align="center" colspan="12"><strong>Totales</strong></td>';
    echo '<td align="center"><strong>'.$totalUnidades.'</strong></td>';
    echo '<td align="center"><strong>'.$totalSku.'</strong></td>';
    echo '<td align="center"><strong>'.$totalBs.' '.SigVe.'</strong></td>';
    echo '<td align="center"><strong></strong></td>';
    echo '<td align="center"><strong>'.SigDolar.$totalDs.'</strong></td>';
    
    echo '<tr>';

    echo '
      </tbody>
    </table>';
    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R33Q_Devoluciones_Clientes
    FUNCION: Ubicar el lista de devoluciones
    RETORNO: Lista de devoluciones
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R33Q_Devoluciones_Clientes($FInicial,$FFinal) {
    $sql = "
        SELECT
          (SELECT FORMAT(VenFactura.FechaDocumento, 'dd/MM/yyyy') FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS fecha_factura,
          (SELECT FORMAT(VenFactura.FechaDocumento, 'hh:mm tt') FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS hora_factura,
          (SELECT CONCAT(Nombre, ' ', Apellido) FROM GenPersona WHERE id = ((SELECT GenPersonaId FROM VenCliente WHERE id = (SELECT VenFactura.VenClienteId FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId)))) AS nombre_cliente,
          (SELECT VenFactura.NumeroFactura FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS nro_factura,
          (SELECT CodigoCaja FROM VenCaja where id = (SELECT VenCajaId FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId)) AS caja_origen,
          FORMAT(VenDevolucion.FechaDocumento, 'dd/MM/yyyy') AS fecha_devolucion,
          VenDevolucion.FechaDocumento AS fecha_devolucion_sin_formato,
          FORMAT(VenDevolucion.FechaDocumento, 'hh:mm tt') AS hora_devolucion,
          VenDevolucion.ConsecutivoDevolucionSistema AS numero_devolucion,
          DATEDIFF(DAY, (SELECT VenFactura.FechaDocumento FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId), VenDevolucion.FechaDocumento) AS dias,
          (SELECT VenCaja.CodigoCaja FROM VenCaja where id = VenDevolucion.VenCajaId) AS caja,
          (SELECT VenCausaOperacionDevolucion.DescripcionOperacion FROM VenCausaOperacionDevolucion WHERE id = (SELECT TOP 1 VenCausaOperacionId FROM VenDevolucionDetalle WHERE VenDevolucionDetalle.VenDevolucionId = VenDevolucion.Id)) AS causa,
          (SELECT SUM(VenDevolucionDetalle.Cantidad) FROM VenDevolucionDetalle WHERE VenDevolucionDetalle.VenDevolucionId = VenDevolucion.Id) AS unidades,
          (SELECT COUNT(VenDevolucionDetalle.Cantidad) FROM VenDevolucionDetalle WHERE VenDevolucionDetalle.VenDevolucionId = VenDevolucion.Id) AS sku,
          VenDevolucion.M_MontoTotalDevolucion AS monto_bs
        FROM VenDevolucion
        WHERE VenDevolucion.FechaDocumento BETWEEN '$FInicial' AND '$FFinal'
        ORDER BY numero_devolucion ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R5Q_Detalle_Articulo   
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R5Q_Detalle_Articulo($IdArticulo) {
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
      ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre,
    -- Ultima Compra (Fecha de ultima compra)
      (SELECT TOP 1
      CONVERT(DATE,ComFactura.FechaRegistro)
      FROM ComFacturaDetalle
      INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
      INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
      WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
      ORDER BY ComFactura.FechaRegistro DESC) AS  UltimaCompra
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