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
      Artículos estancados en tienda
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

    if(isset($_GET['fechaLote'])) {
      $InicioCarga = new DateTime("now");
      R34_Articulos_Estancados($_GET['SEDE'],$_GET['fechaLote']);
      FG_Guardar_Auditoria('CONSULTAR','REPORTE','Articulos estancados en tienda');

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
                <label for="fechaLote">Fecha de lote:</label>
              </td>

              <td>
                <input id="fechaLote" type="date" name="fechaLote" required style="width:100%;">
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
    TITULO: R34_Articulos_Estancados
    PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
                [$fechaLote] Fecha indica generacion de articulos previos a fecha
    FUNCION: Arma una lista de productos estancados
    RETORNO: No aplica
  */
  function R34_Articulos_Estancados($SedeConnection,$fechaLote) {
    
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();
    $Hoy = new DateTime('now');
    $Hoy = $Hoy->format('Y-m-d');

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

    echo'<h6 align="center">Periodo hasta el '.$fechaLote.'</h6>';

    echo '
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Codigo Articulo</th>
            <th scope="col" class="CP-sticky">Codigo de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Precio</td>
            <th scope="col" class="CP-sticky">Lote Mas Antiguo</td>
            <th scope="col" class="CP-sticky">Ultima Compra</td>
            <th scope="col" class="CP-sticky">Ultima Venta</td>
            <th scope="col" class="CP-sticky">Dias en Tienda</td>
            <th scope="col" class="CP-sticky">Dias Ultima Venta</td>
            <th scope="col" class="CP-sticky">Ultimo Proveedor</td>
          </tr>
        </thead>

        <tbody>
    ';

    $contador = 1;

    $sql2 = R12_Q_Articulos_Estancados($fechaLote);
    $result2 = sqlsrv_query($conn,$sql2);

    while($row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC)) {

    	$id_producto = $row2['id_producto'];
    	$codigo_articulo = $row2['codigo_articulo'];
    	$codigo_barra = $row2['codigo_barra'];
    	$descripcion = FG_Limpiar_Texto($row2['descripcion']);
    	$existencia = intval($row2['existencia']);
    	$lote_mas_antiguo = $row2['lote_mas_antiguo']->format('d/m/Y');
    	$ultima_compra = $row2['ultima_compra']->format('d/m/Y');
    	$ultima_venta = ($row2['ultima_venta']) ? $row2['ultima_venta']->format('d/m/Y') : '';
    	$nombre_ultimo_proveedor = FG_Limpiar_Texto($row2['nombre_ultimo_proveedor']);
      $id_ultimo_proveedor = $row2['id_ultimo_proveedor'];

      if ($existencia > 0) {
      	$sql = R34Q_Detalle_Articulo($id_producto);
  	    $result = sqlsrv_query($conn,$sql);
  	    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

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

  	    $precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
  	    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);
        $precio = number_format($precio,2,"," ,"." );

        $dias_tienda = date_diff(date_create($row2['lote_mas_antiguo']->format('Y-m-d')), date_create(''));
        $dias_tienda = $dias_tienda->format('%a');

        if ($row2['ultima_venta']) {
          $dias_ultima_venta = date_diff(date_create($row2['ultima_venta']->format('Y-m-d')), date_create(''));
          $dias_ultima_venta = $dias_ultima_venta->format('%a');        
        } else {
          $dias_ultima_venta = '-';
        }

      	echo '<tr>';
        echo '<td align="center"><b>'.$contador.'</b></td>';
      	echo '<td align="center">'.$codigo_articulo.'</td>';
      	echo '<td align="center">'.$codigo_barra.'</td>';
      	echo '<td align="center" class="CP-barrido"><a href="/reporte2?SEDE='.$_GET['SEDE'].'&Id='.$id_producto.'" style="text-decoration: none; color: black;" target="_blank">'.$descripcion.'</a></td>';
      	echo '<td align="center">'.$existencia.'</td>';
      	echo '<td align="center">'.$precio.'</td>';
        echo '<td align="center" class="CP-barrido"><a href="/reporte12?fechaInicio='.$row2['lote_mas_antiguo']->format('Y-m-d').'&fechaFin='.date_create()->format('Y-m-d').'&SEDE='.$_GET['SEDE'].'&Descrip='.$descripcion.'&Id='.$id_producto.'" style="text-decoration: none; color: black;" target="_blank">'.$lote_mas_antiguo.'</a></td>';
      	echo '<td align="center">'.$ultima_compra.'</td>';
      	echo '<td align="center">'.$ultima_venta.'</td>';
      	echo '<td align="center">'.$dias_tienda.'</td>';
      	echo '<td align="center">'.$dias_ultima_venta.'</td>';
      	echo '<td align="center" class="CP-barrido"><a href="/reporte7?Nombre='.$nombre_ultimo_proveedor.'&SEDE='.$_GET['SEDE'].'&Id='.$id_ultimo_proveedor.'" style="text-decoration: none; color: black;" target="_blank">'.$nombre_ultimo_proveedor.'</a></td>';
      	echo '</tr>';

      }


      $contador++;

  	}

  	echo '
	    </tbody>
	  </table>
	';

	mysqli_close($connCPharma);
	sqlsrv_close($conn);
  }

  /*
  TITULO: R12_Q_Articulos_Estancados
  PARAMETROS: [$fechaLote] Fecha indica generacion de articulos previos a fecha
  RETORNO: Un String con la query pertinente
   */
  function R12_Q_Articulos_Estancados($fechaLote) {
    $sql = "
      SELECT
        DISTINCT (SELECT InvArticulo.Id FROM InvArticulo WHERE InvArticulo.Id = InvLoteAlmacen.InvArticuloId) AS id_producto,
        (SELECT InvArticulo.CodigoArticulo FROM InvArticulo WHERE InvArticulo.Id = InvLoteAlmacen.InvArticuloId) AS codigo_articulo,
        (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = InvLoteAlmacen.InvArticuloId AND InvCodigoBarra.EsPrincipal = 1) AS codigo_barra,
        (SELECT InvArticulo.DescripcionLarga FROM InvArticulo WHERE InvArticulo.Id = InvLoteAlmacen.InvArticuloId) AS descripcion,
        (SELECT SUM(T.Existencia) FROM InvLoteAlmacen T WHERE T.InvAlmacenId IN (1, 2) AND T.InvArticuloId = InvLoteAlmacen.InvArticuloId) AS existencia,
        (SELECT TOP 1 T.Auditoria_FechaCreacion FROM InvLoteAlmacen T WHERE T.InvAlmacenId IN (1, 2) AND T.InvArticuloId = InvLoteAlmacen.InvArticuloId AND Existencia > 0) AS lote_mas_antiguo,
        (SELECT TOP 1 (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) FROM ComFacturaDetalle WHERE ComFacturaDetalle.InvArticuloId = InvLoteAlmacen.InvArticuloId ORDER BY ComFacturaDetalle.ComFacturaId DESC) AS ultima_compra,
        (SELECT TOP 1 VenFactura.FechaDocumento FROM VenFactura WHERE VenFactura.Id = (SELECT TOP 1 VenFacturaDetalle.VenFacturaId FROM VenFacturaDetalle WHERE VenFacturaDetalle.InvArticuloId = InvLoteAlmacen.InvArticuloId ORDER BY VenFacturaDetalle.VenFacturaId DESC)) AS ultima_venta,
        (SELECT TOP 1 ComProveedor.Id FROM ComFacturaDetalle INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId WHERE ComFacturaDetalle.InvArticuloId = InvLoteAlmacen.InvArticuloId ORDER BY ComFactura.FechaDocumento DESC) AS  id_ultimo_proveedor,
        (SELECT TOP 1 GenPersona.Nombre FROM ComFacturaDetalle INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId WHERE ComFacturaDetalle.InvArticuloId = InvLoteAlmacen.InvArticuloId ORDER BY ComFactura.FechaDocumento DESC) AS  nombre_ultimo_proveedor
      FROM InvLoteAlmacen
      WHERE
        (InvLoteAlmacen.Auditoria_FechaCreacion <= '$fechaLote') AND 
        (SELECT SUM(T.Existencia) FROM InvLoteAlmacen T WHERE T.InvAlmacenId IN (1, 2) AND T.InvArticuloId = InvLoteAlmacen.InvArticuloId) > 0
      ORDER BY lote_mas_antiguo ASC
    ";
    return $sql;
  }

  
  /**********************************************************************************/
  /*
    TITULO: R2Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R34Q_Detalle_Articulo($IdArticulo) {
    $sql = "
      SELECT
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
--Impuesto (1 SI aplica impuesto, 0 NO aplica impuesto)
    (ISNULL(InvArticulo.FinConceptoImptoIdCompra,CAST(0 AS INT))) AS Impuesto
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