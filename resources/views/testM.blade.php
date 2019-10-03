@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
    <script type="text/javascript" src="{{ asset('assets/js/sortTable.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/filter.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/functions.js') }}"></script>

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

    //--------- BORRAR ESTA LINEA ---------//
    $_GET['SEDE'] = 'DBm';
    //--------- BORRAR ESTA LINEA ---------//
    
    if(isset($_GET['Id'])) {
      $InicioCarga = new DateTime("now");

      if(isset($_GET['SEDE'])) {
        echo '
            <h1 class="h5 text-success"  align="left">
              <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).
            '</h1>';
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
            <h1 class="h5 text-success"  align="left">
              <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).
            '</h1>';
      }
      echo '<hr class="row align-items-start col-12">';

      $InicioCarga = new DateTime("now");
      $sql = QListaArticulos();
      $ArtJson = armarJson($sql,$_GET['SEDE']);

      echo '
        <form id="form" autocomplete="off" action="" target="_blank">
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

              <td>
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

    $sql = R12Q_Detalle_Articulo($IdArticulo);
    sqlsrv_query($conn,$sql);
    $result = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

    $IsIVA = $row["ConceptoImpuesto"];
    $Existencia = $row["Existencia"];
    $Descripcion = $row["Descripcion"];

    $Precio = FG_Calculo_Precio($conn,$IdArticulo,$IsIVA,$Existencia);
    $Dolarizado = ProductoDolarizado($conn,$IdArticulo);
    $TasaActual = TasaFecha(date('Y-m-d'));

    //-------------------- Inicio Rangos --------------------
    $FFinalImpresion = $FFinal;
    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));
    //-------------------- Fin Rangos --------------------

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
                  <th scope="col">Precio (Con IVA)</th>
                  <th scope="col">Dolarizado</th>
                  <th scope="col">Tasa actual</th>
                  <th scope="col">Precio en divisa (Con IVA)</th>
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
              
              <td align="center">'." ".round($Precio,2)." ".SigVe.'</td>
              <td align="center">'.$Dolarizado.'</td>
    ';

    if($TasaActual!=0){
      echo '
              <td align="center">'." ".$TasaActual." ".SigVe.'</td>
              <td align="center">'.round(($Precio/$TasaActual),2)." ".SigDolar.'</td>
      ';
    }
    else{
      echo '
              <td align="center">0.00 '.SigVe.'</td>
              <td align="center">0.00 '.SigDolar.'</td>
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

        $sql9 = QCleanTable('CP_QResumenDeMovimientos');
        sqlsrv_query($conn,$sql9);
        $sql10 = QIntegracionResumenDeMovimientos($IdArticulo,$FInicial,$FFinal);
        sqlsrv_query($conn,$sql10);
        $sql11 = QAgruparDetalleDeMovimientos();
        $result4 = sqlsrv_query($conn,$sql11);

    $sql = QCleanTable('CP_QResumenDeMovimientos');
    sqlsrv_query($conn,$sql);

    $contador = 1;
        $FechaComparativa = date('d/m/Y',strtotime($FFinal));

      while($row4 = sqlsrv_fetch_array($result4,SQLSRV_FETCH_ASSOC)) {

        if($FechaComparativa == $row4["FechaMovimiento"]) {
          continue;
        }

        echo '
                    <tr>
                <td align="center"><strong>'.intval($contador).'</strong></td>
              <td align="center">'.$row4["FechaMovimiento"].'</td>
                  <td align="center">'.utf8_encode($row4["Movimiento"]).'</td>
                  <td align="center">'.intval($row4["Cantidad"]).'</td>
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

        $sql8 = QDetalleDeMovimiento($IdArticulo,$FInicial,$FFinal);
    $result3 = sqlsrv_query($conn,$sql8);

    $contador = 1;

      while($row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC)) {

        if($row3["FechaMovimiento"]->format("Y-m-d") == $FFinal) {
          break;
        }

        echo '
              <tr>
                <td align="center"><strong>'.intval($contador).'</strong></td>
                <td align="center">'.$row3["FechaMovimiento"]->format("d/m/Y").'</td>
            ';

      $Hora = date('h:i a',strtotime($row3["FechaMovimiento"]->format("H:m:s")));

      echo '          
                  <td align="center">'.$Hora.'</td>
                  <td align="center">'.utf8_encode($row3["Movimiento"]).'</td>
                  <td align="center">'.intval($row3["Cantidad"]).'</td>
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

?>