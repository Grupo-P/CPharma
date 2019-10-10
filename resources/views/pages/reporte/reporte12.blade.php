@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
  <style>
    * {box-sizing:border-box;}

    /*the container must be positioned relative:*/
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
      /*position the autocomplete items to be the same width as the container:*/
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

    /*when hovering an item:*/
    .autocomplete-items div:hover {background-color:#e9e9e9;}

    /*when navigating through the items using the arrow keys:*/
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
    include(app_path().'\functions\querys.php');
    include(app_path().'\functions\funciones.php');

    $ArtJson="";
    
    if(isset($_GET['Id'])) {
      $InicioCarga = new DateTime("now");

      if(isset($_GET['SEDE'])) {
        echo '
          <h1 class="h5 text-success"  align="left">
            <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE'])
          .'</h1>
        ';
      }
      echo '<hr class="row align-items-start col-12">';

      R12_Detalle_Movimientos($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin'],$_GET['Id']);
      GuardarAuditoria('CONSULTAR','REPORTE','Detalle de movimientos');

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else {
      if(isset($_GET['SEDE'])) {
        echo '
          <h1 class="h5 text-success" align="left">
            <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE'])
          .'</h1>
        ';
      }
      echo '<hr class="row align-items-start col-12">';

      $InicioCarga = new DateTime("now");
      $sql = QListaArticulos();
      $ArtJson = armarJson($sql,$_GET['SEDE']);

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
          </table>
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
    if($ArtJson!="") {
  ?>
  <script type="text/javascript">
    ArrJs = eval(<?php echo $ArtJson ?>);
    autocompletado(document.getElementById("myInput"),document.getElementById("myId"), ArrJs);
  </script>
  <?php
    }
  ?>
@endsection

<?php

  /*
    TITULO: R12_Detalle_Movimientos
    PARAMETROS: [$SedeConnection] sede donde se hara la conexion
          [$FInicial] Fecha inicial del rango a buscar
          [$FFinal] Fecha final del rango a buscar
          [$IdArticulo] ide del articulo
    FUNCION: arma la lista del troquel segun el articulo
    RETORNO: no aplica
    AUTOR: Ing. Manuel Henriquez
  */
  function R12_Detalle_Movimientos($SedeConnection,$FInicial,$FFinal,$IdArticulo) {
    $conn = ConectarSmartpharma($SedeConnection);
    $connCPharma = ConectarXampp();

    $sql = R12Q_Detalle_Articulo($IdArticulo);
    sqlsrv_query($conn,$sql);
    $result = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

    $IsIVA = $row["ConceptoImpuesto"];
    $Existencia = $row["Existencia"];
    $Descripcion = utf8_encode($row["Descripcion"]);

    $Precio = FG_Calculo_Precio($conn,$IdArticulo,$IsIVA,$Existencia);
    $Dolarizado = FG_Producto_Dolarizado($conn,$IdArticulo);
    $TasaActual = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));

    mysqli_close($connCPharma);

    $FFinalImpresion = $FFinal;
    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    $sql1 = QG_CleanTable('CP_R12Q_Unidades_Vendidas');
    sqlsrv_query($conn,$sql1);
    $sql1 = QG_CleanTable('CP_R12Q_Unidades_Devueltas');
    sqlsrv_query($conn,$sql1);

    $sql2 = R12Q_Unidades_Vendidas($FInicial,$FFinal);
    $sql3 = R12Q_Unidades_Devueltas($FInicial,$FFinal);
    $sql4 = R12Q_Integracion_Unidades_Vendidas($IdArticulo);

    sqlsrv_query($conn,$sql2);
    sqlsrv_query($conn,$sql3);
    $result1 = sqlsrv_query($conn,$sql4);

    $sql1 = QG_CleanTable('CP_R12Q_Unidades_Vendidas');
    sqlsrv_query($conn,$sql1);
    $sql1 = QG_CleanTable('CP_R12Q_Unidades_Devueltas');
    sqlsrv_query($conn,$sql1);

    $row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);

    $RangoDias = FG_Rango_Dias($FInicial,$FFinal);
    $UnidadesVendidas = intval($row1["UnidadesVendidas"]);
    $VentaDiaria = FG_Venta_Diaria($UnidadesVendidas,$RangoDias);
    $DiasRestantes = FG_Dias_Restantes($Existencia,$VentaDiaria);

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
      <h6 align="center">Periodo desde el '.$FInicial.' al '.$FFinalImpresion.'</h6>

      <table class="table table-striped table-bordered col-12 sortable">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Codigo</th>
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
        <td>'.$row["CodigoArticulo"].'</td>
        <td align="left" class="barrido">
          <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
            .$Descripcion
          .'</a>
        </td>
        <td align="center">'.intval($Existencia).'</td>
        <td align="center">'.$UnidadesVendidas.'</td>
        <td align="center">'.round($DiasRestantes,2).'</td>
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

    $sql5 = R12Q_Resumen_Movimiento($IdArticulo,$FInicial,$FFinal);
    $result2 = sqlsrv_query($conn,$sql5);

    $contador = 1;
    $FechaComparativa = date('d/m/Y',strtotime($FFinal));

    while($row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC)) {

      if($FechaComparativa == $row2["FechaMovimiento"]) {
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
    $sql6 = R12Q_Detalle_Movimiento($IdArticulo,$FInicial,$FFinal);
    $result3 = sqlsrv_query($conn,$sql6);

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

  /*
    TITULO: R12Q_Detalle_Articulo
    PARAMETROS: [$IdArticulo] $IdArticulo del articulo a buscar
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    AUTOR: Ing. Manuel Henriquez
   */
  function R12Q_Detalle_Articulo($IdArticulo) {
    $sql = " 
      SELECT
      InvArticulo.Id,
      InvArticulo.CodigoArticulo,
      (SELECT CodigoBarra
          FROM InvCodigoBarra 
          WHERE InvCodigoBarra.InvArticuloId = '$IdArticulo'
          AND InvCodigoBarra.EsPrincipal = 1) As CodigoBarra,
      InvArticulo.Descripcion,
        (SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
          FROM InvLoteAlmacen
          WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
          AND (InvLoteAlmacen.InvArticuloId = '$IdArticulo')) AS Existencia,
      InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
      FROM InvArticulo
      WHERE InvArticulo.Id = '$IdArticulo'
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

  /*
    TITULO: R12Q_Resumen_Movimiento
    PARAMETROS: [$IdArticulo] Id del articulo actual
                [$FInicial] Fecha inicial del rango
                [$FFinal] Fecha final del rango
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
    TITULO: R12Q_Unidades_Vendidas
    PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
                [$FFinal] Fecha final del rango a consutar
    FUNCION: Consulta las Veces vendidas a clientes y las unidades vendidas de un producto
    RETORNO: Tabla con los articulos, las veces vendidas y las unidades vendidas
    AUTOR: Ing. Manuel Henriquez
   */
  function R12Q_Unidades_Vendidas($FInicial,$FFinal) {   
    $sql = "
      SELECT
      VenFacturaDetalle.InvArticuloId,
      ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesVendidas,
      (ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesVendidas
      INTO CP_R12Q_Unidades_Vendidas
      FROM VenFacturaDetalle
      INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
      WHERE
      (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
      GROUP BY VenFacturaDetalle.InvArticuloId
      ORDER BY UnidadesVendidas DESC
    ";          
    return $sql;
  }

  /*
    TITULO: R12Q_Unidades_Devueltas
    PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
                [$FFinal] Fecha final del rango a consutar
    FUNCION: Consulta las Veces devuelta a clientes y las unidades devuelta de un producto
    RETORNO: Tabla con los articulos, las veces devuelta y las unidades devuelta
    AUTOR: Ing. Manuel Henriquez
  */
  function R12Q_Unidades_Devueltas($FInicial,$FFinal) {  
    $sql = "
      SELECT
      VenDevolucionDetalle.InvArticuloId,
      ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesDevueltas,
      (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesDevueltas
      INTO CP_R12Q_Unidades_Devueltas
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE
      (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      GROUP BY VenDevolucionDetalle.InvArticuloId
      ORDER BY UnidadesDevueltas DESC
    ";          
    return $sql;
  }

  /*
    TITULO: R12Q_Integracion_Unidades_Vendidas
    PARAMETROS: [$IdArticulo] El Id del articulo solicitado
    FUNCION: Integrar y calcular las unidades vendidas
    RETORNO: String con la query
    AUTOR: Ing. Manuel Henriquez
   */
  function R12Q_Integracion_Unidades_Vendidas($IdArticulo) {
    $sql = "
      SELECT
      InvArticulo.Id,
      InvArticulo.Descripcion,
      ((ISNULL(CP_R12Q_Unidades_Vendidas.UnidadesVendidas,CAST(0 AS INT))) -
      (ISNULL(CP_R12Q_Unidades_Devueltas.UnidadesDevueltas,CAST(0 AS INT))) 
      ) AS UnidadesVendidas
      FROM InvArticulo
      LEFT JOIN CP_R12Q_Unidades_Vendidas ON CP_R12Q_Unidades_Vendidas.InvArticuloId = InvArticulo.Id
      LEFT JOIN CP_R12Q_Unidades_Devueltas ON CP_R12Q_Unidades_Devueltas.InvArticuloId = InvArticulo.Id
      WHERE InvArticulo.Id = '$IdArticulo'
      ORDER BY UnidadesVendidas DESC
    ";
    return $sql;
  }
?>