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

                $i++;
            }
        @endphp



        $(document).ready(function () {
            $('#myInput').autocomplete({
                source: {!! json_encode($descripcion) !!},
                autoFocus: true,
                minLength: 3,
                select: function (event, ui) {
                    $('#myId').val(ui.item.id);
                }
            });

            $('#myInput2').autocomplete({
                source: {!! json_encode($descripcion) !!},
                autoFocus: true,
                minLength: 3,
                select: function (event, ui) {
                    $('#myId2').val(ui.item.id);
                }
            });

            $('#myInput3').autocomplete({
                source: {!! json_encode($descripcion) !!},
                autoFocus: true,
                minLength: 3,
                select: function (event, ui) {
                    $('#myId3').val(ui.item.id);
                }
            });
        });
  </script>
@endsection


@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Articulos competidos
  </h1>
  <hr class="row align-items-start col-12">
  <?php

    $ArtJson = "";
    $CodJson = "";
    $CodIntJson = "";

    if (isset($_GET['SEDE'])) {
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';

    if (isset($_GET['Id1'])) {
      $InicioCarga = new DateTime("now");

      R41_Articulos_Competidos($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin'],$_GET['Id1'],$_GET['Id2'],$_GET['Id3']);
      FG_Guardar_Auditoria('CONSULTAR','REPORTE','Articulos competidos');

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else {
      $InicioCarga = new DateTime("now");

      $sql2 = R41Q_Lista_Articulos_CodBarra();
      $CodJson = FG_Armar_Json($sql2,$_GET['SEDE']);

      $sql3 = R41Q_Lista_Articulos_CodInterno();
      $CodIntJson = FG_Armar_Json($sql3,$_GET['SEDE']);

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
              <td colspan="4">Producto 1</td>
            </tr>
            <tr>
              <td colspan="2">
                <div class="autocomplete" style="width:90%;">
                  <input id="myInput" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()">
                </div>

                <input id="myId" name="Id1" type="hidden">
              </td>

              <td>
                <div class="autocomplete" style="width:90%;">
                  <input id="myInputCB" type="text" name="CodBar" placeholder="Ingrese el codigo de barra del articulo " onkeyup="conteoCBInput(\'myInputCB\')"><input id="myIdCB" name="IdCB" type="hidden">
                </div>
              </td>

              <td>
                <div class="autocomplete" style="width:90%;">
                  <input id="myInputCI" type="text" name="CodInt" placeholder="Ingrese el codigo interno del articulo " onkeyup="conteoCIInput(\'myInputCI\')">
                </div>
              </td>
            </tr>

            <tr>
              <td colspan="4">&nbsp;</td>
            </tr>

            <tr>
              <td colspan="4">Producto 2</td>
            </tr>
            <tr>
              <td colspan="2">
                <div class="autocomplete" style="width:90%;">
                  <input id="myInput2" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo " onkeyup="conteoInput(\'myInput2\')">
                </div>

                <input id="myId2" name="Id2" type="hidden">
              </td>

              <td>
                <div class="autocomplete" style="width:90%;">
                  <input id="myInputCB2" type="text" name="CodBar" placeholder="Ingrese el codigo de barra del articulo " onkeyup="conteoCBInput(\'myInputCB2\')"><input id="myIdCB" name="IdCB" type="hidden">
                </div>
              </td>

              <td>
                <div class="autocomplete" style="width:90%;">
                  <input id="myInputCI2" type="text" name="CodInt" placeholder="Ingrese el codigo interno del articulo " onkeyup="conteoCIInput(\'myInputCI2\')">

                </div>
              </td>
            </tr>

            <tr>
              <td colspan="4">&nbsp;</td>
            </tr>

            <tr>
              <td colspan="4">Producto 3</td>
            </tr>
            <tr>
              <td colspan="2">
                <div class="autocomplete" style="width:90%;">
                  <input id="myInput3" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo " onkeyup="conteoInput(\'myInput3\')">
                </div>

                <input id="myId3" name="Id3" type="hidden">
              </td>

              <td>
                <div class="autocomplete" style="width:90%;">
                  <input id="myInputCB3" type="text" name="CodBar" placeholder="Ingrese el codigo de barra del articulo " onkeyup="conteoCBInput(\'myInputCB3\')">
                </div>
              </td>

              <td>
                <div class="autocomplete" style="width:90%;">
                  <input id="myInputCI3" type="text" name="CodInt" placeholder="Ingrese el codigo interno del articulo " onkeyup="conteoCIInput(\'myInputCI3\')">
                </div>
              </td>
            </tr>

            <tr>
              <td colspan="4">&nbsp;</td>
            </tr>

            <tr>
              <td colspan="4">
                <button type="submit" class="btn btn-outline-success">Buscar</button>
              </td>
            </tr>

            <tr>
              <td colspan="4">&nbsp;</td>
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
  <script src="{{asset('assets/chart.js/chart.v2.js')}}"></script>

  <script>
    $('#form').submit(function (event) {
        id1 = $('[name=Id1]').val();
        id2 = $('[name=Id2]').val();

        if (id1 == '' || id2 == '') {
            alert('Debe seleccionar al menos dos productos');
            event.preventDefault();
        }
    });

    descripcion1 = $('[name=descripcion1]').val();
    descripcion2 = $('[name=descripcion2]').val();

    @if (isset($_GET['Id3']) && $_GET['Id3'] != '')
        descripcion3 = $('[name=descripcion3]').val();
    @endif

    json1 = $('[name=json1]').val();
    json2 = $('[name=json2]').val();

    @if (isset($_GET['Id3']) && $_GET['Id3'] != '')
        json3 = $('[name=json3]').val();
    @endif

    jsonAcumulado1 = $('[name=jsonAcumulado1]').val();
    jsonAcumulado2 = $('[name=jsonAcumulado2]').val();

    @if (isset($_GET['Id3']) && $_GET['Id3'] != '')
        jsonAcumulado3 = $('[name=jsonAcumulado3]').val();
    @endif

    json1 = JSON.parse(json1);
    json2 = JSON.parse(json2);

    @if (isset($_GET['Id3']) && $_GET['Id3'] != '')
        json3 = JSON.parse(json3);
    @endif

    jsonAcumulado1 = JSON.parse(jsonAcumulado1);
    jsonAcumulado2 = JSON.parse(jsonAcumulado2);

    @if (isset($_GET['Id3']) && $_GET['Id3'] != '')
        jsonAcumulado3 = JSON.parse(jsonAcumulado3);
    @endif

    fechas = $('[name=fechas]').val();
    fechas = JSON.parse(fechas);

    const labels = fechas;
    const data = {
      labels: labels,
      datasets: [
        {
          label: descripcion1,
          data: json1,
          borderColor: '#17a2b8',
          backgroundColor: '#17a2b8',
        },
        {
          label: descripcion2,
          data: json2,
          borderColor: '#28a745',
          backgroundColor: '#28a745',
        },
        @if(isset($_GET['Id3']) && $_GET['Id3'] != '')
        {
          label: descripcion3,
          data: json3,
          borderColor: '#343a40',
          backgroundColor: '#343a40',
        }
        @endif
      ]
    };

    const dataAcumulado = {
      labels: labels,
      datasets: [
        {
          label: descripcion1,
          data: jsonAcumulado1,
          borderColor: '#17a2b8',
          backgroundColor: '#17a2b8',
        },
        {
          label: descripcion2,
          data: jsonAcumulado2,
          borderColor: '#28a745',
          backgroundColor: '#28a745',
        },
        @if(isset($_GET['Id3']) && $_GET['Id3'] != '')
        {
          label: descripcion3,
          data: jsonAcumulado3,
          borderColor: '#343a40',
          backgroundColor: '#343a40',
        }
        @endif
      ]
    };

    const config = {
      type: 'line',
      data: data,
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Ventas por día'
          }
        }
      },
    };

    const configAcumulado = {
      type: 'line',
      data: dataAcumulado,
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: 'Ventas acumuladas'
          }
        }
      },
    };

    var chart = new Chart(
        document.getElementById('canvas'),
        config
    );

    var chartAcumulado = new Chart(
        document.getElementById('canvasAcumulado'),
        configAcumulado
    );
  </script>


  <?php
    if($ArtJson!=""){
  ?>
    <script type="text/javascript">
      ArrJs = eval(<?php echo $ArtJson ?>);
      autocompletado(document.getElementById("myInput"),document.getElementById("myId"), ArrJs);
      autocompletado2(document.getElementById("myInput2"),document.getElementById("myId2"), ArrJs);
      autocompletado3(document.getElementById("myInput3"),document.getElementById("myId3"), ArrJs);
    </script>
  <?php
    }
  ?>

  <?php
    if($CodJson!=""){
  ?>
    <script type="text/javascript">
      ArrJsCB = eval(<?php echo $CodJson ?>);
      autocompletadoCB(document.getElementById("myInputCB"),document.getElementById("myId"), ArrJsCB);
      autocompletadoCB2(document.getElementById("myInputCB2"),document.getElementById("myId2"), ArrJsCB);
      autocompletadoCB3(document.getElementById("myInputCB3"),document.getElementById("myId3"), ArrJsCB);
    </script>
  <?php
    }

    if($CodIntJson!=""){
  ?>
    <script type="text/javascript">
      ArrJsInt = eval(<?php echo $CodIntJson ?>);
      autocompletadoCI(document.getElementById("myInputCI"),document.getElementById("myId"), ArrJsInt);
      autocompletadoCI2(document.getElementById("myInputCI2"),document.getElementById("myId2"), ArrJsInt);
      autocompletadoCI3(document.getElementById("myInputCI3"),document.getElementById("myId3"), ArrJsInt);
    </script>
  <?php
    }
  ?>
@endsection

<?php
  /**********************************************************************************/
  /*
    TITULO: R41_Articulos_Competidos
    FUNCION: Armar una tabla del historico de compra del articulo
    RETORNO: No aplica
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R41_Articulos_Competidos($SedeConnection,$fechaInicio,$fechaFin,$Id1,$Id2,$Id3) {
    $conn = FG_Conectar_Smartpharma($SedeConnection);

    $sql1 = R41Q_Detalle_Articulo($Id1);
    $result1 = sqlsrv_query($conn,$sql1);
    $row1 = sqlsrv_fetch_array($result1);
    $descripcion1 = $row1['Descripcion'];

    $sql2 = R41Q_Detalle_Articulo($Id2);
    $result2 = sqlsrv_query($conn,$sql2);
    $row2 = sqlsrv_fetch_array($result2);
    $descripcion2 = $row2['Descripcion'];

    if ($Id3 != '') {
        $sql3 = R41Q_Detalle_Articulo($Id3);
        $result3 = sqlsrv_query($conn,$sql3);
        $row3 = sqlsrv_fetch_array($result3);
        $descripcion3 = $row3['Descripcion'];
    }


    echo '
    <input type="hidden" name="descripcion1" value="'.$descripcion1.'">
    <input type="hidden" name="descripcion2" value="'.$descripcion2.'">';

    if (isset($descripcion3)) {
        echo '<input type="hidden" name="descripcion3" value="'.$descripcion3.'">';
    }

    echo '
    <div class="row">
        <div class="col-md-6">
            <canvas id="canvas"></canvas>
        </div>

        <div class="col-md-6">
            <canvas id="canvasAcumulado"></canvas>
        </div>
    </div>

    <br/>

    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
        <tr>
          <th class="CP-stickyBar" scope="col" colspan="2"></th>
          <th class="CP-stickyBar" scope="col" colspan="2">Articulo 1</th>
          <th class="CP-stickyBar" scope="col" colspan="2">Articulo 2</th>';

    if (isset($descripcion3)) {
        echo '<th class="CP-stickyBar" scope="col" colspan="2">Articulo 3</th>';
    }

    echo '
        </tr>
        <tr>
          <th class="CP-stickyBar" scope="col" colspan="2"></th>
          <th class="CP-stickyBar" scope="col" colspan="2">'.$descripcion1.'</th>
          <th class="CP-stickyBar" scope="col" colspan="2">'.$descripcion2.'</th>';

    if (isset($descripcion3)) {
        echo '<th class="CP-stickyBar" scope="col" colspan="2">'.$descripcion3.'</th>';
    }

    echo '
        </tr>
        <tr>
          <th class="CP-stickyBar" scope="col">#</th>
          <th class="CP-stickyBar" scope="col">Fecha</th>
          <th class="CP-stickyBar" scope="col">Ventas</th>
          <th class="CP-stickyBar" scope="col">Acumulado</th>
          <th class="CP-stickyBar" scope="col">Ventas</th>
          <th class="CP-stickyBar" scope="col">Acumulado</th>';

    if (isset($descripcion3)) {
        echo '
            <th class="CP-stickyBar" scope="col">Ventas</th>
            <th class="CP-stickyBar" scope="col">Acumulado</th>
        ';
    }

    echo '
        </tr>
      </thead>
      <tbody>';

    $fechaInicio = new \DateTime($_GET['fechaInicio']);
    $fechaFin = new \DateTime($_GET['fechaFin']);

    $diff = date_diff($fechaInicio, $fechaFin)->format('%a');

    $contador = 1;

    $acumulado1 = 0;
    $acumulado2 = 0;
    $acumulado3 = 0;

    for ($i=0; $i < $diff + 1; $i++) {

        $fechaBucle = $fechaInicio;

        $sqlVentas1 = R41Q_Ventas($Id1,$fechaBucle->format('Y-m-d'));
        $resultVentas1 = sqlsrv_query($conn,$sqlVentas1);
        $rowVentas1 = sqlsrv_fetch_array($resultVentas1);
        $ventas1 = $rowVentas1['ventas'];

        $sqlVentas2 = R41Q_Ventas($Id2,$fechaBucle->format('Y-m-d'));
        $resultVentas2 = sqlsrv_query($conn,$sqlVentas2);
        $rowVentas2 = sqlsrv_fetch_array($resultVentas2);
        $ventas2 = $rowVentas2['ventas'];

        $sqlVentas3 = R41Q_Ventas($Id3,$fechaBucle->format('Y-m-d'));
        $resultVentas3 = sqlsrv_query($conn,$sqlVentas3);
        $rowVentas3 = sqlsrv_fetch_array($resultVentas3);
        $ventas3 = $rowVentas3['ventas'];

        $acumulado1 += $ventas1;
        $acumulado2 += $ventas2;
        $acumulado3 += $ventas3;

        $json1[] = $ventas1;
        $json2[] = $ventas2;
        $json3[] = $ventas3;

        $jsonAcumulado1[] = $acumulado1;
        $jsonAcumulado2[] = $acumulado2;
        $jsonAcumulado3[] = $acumulado3;

        $fechas[] = $fechaBucle->format('d/m/Y');

        echo '
            <tr>
                <td>'.$contador++.'</td>
                <td>'.$fechaBucle->format('d/m/Y').'</td>
                <td>'.$ventas1.'</td>
                <td>'.$acumulado1.'</td>
                <td>'.$ventas2.'</td>
                <td>'.$acumulado2.'</td>';

        if (isset($descripcion3)) {

        echo   '<td>'.$ventas3.'</td>
                <td>'.$acumulado3.'</td>';
        }

        echo '</tr>

        ';

        $fechaInicio->modify('+1day');
    }

    echo '
        <tr>
            <td></td>
            <td></td>
            <td><b>'.$acumulado1.'</b></td>
            <td></td>
            <td><b>'.$acumulado2.'</b></td>
            <td></td>';

    if (isset($descripcion3)) {
    echo '
            <td><b>'.$acumulado3.'</b></td>
            <td></td>';
    }

    echo '</tr>
    ';


    echo '
      </tbody>
    </table>

    <input type="hidden" name="json1" value="'.json_encode($json1).'">
    <input type="hidden" name="json2" value="'.json_encode($json2).'">
    <input type="hidden" name="json3" value="'.json_encode($json3).'">

    <input type="hidden" name="jsonAcumulado1" value="'.json_encode($jsonAcumulado1).'">
    <input type="hidden" name="jsonAcumulado2" value="'.json_encode($jsonAcumulado2).'">';

    if (isset($descripcion3)) {
        echo '<input type="hidden" name="jsonAcumulado3" value="'.json_encode($jsonAcumulado3).'">';
    }

    echo '<input type="hidden" name="fechas" value=\''.json_encode($fechas).'\'>';
  }
  /**********************************************************************************/
  /*
    TITULO: R41Q_Lista_Articulos
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R41Q_Lista_Articulos() {
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
    TITULO: R41Q_Lista_Articulos_CodBarra
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R41Q_Lista_Articulos_CodBarra() {
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
    TITULO: R41Q_Lista_Articulos_CodInterno
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R41Q_Lista_Articulos_CodInterno() {
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
    TITULO: R41Q_Ventas
    FUNCION: Cantidad de ventas del articulo
    RETORNO: Cantidad de ventas del articulo
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R41Q_Ventas($IdArticulo,$Fecha) {
    $sql = "
      SELECT
        COUNT(1) AS ventas
      FROM VenFacturaDetalle
      WHERE
        VenFacturaDetalle.InvArticuloId = '$IdArticulo' AND
        VenFacturaDetalle.VenFacturaId IN (SELECT VenFactura.Id FROM VenFactura WHERE CONVERT(DATE, VenFactura.FechaDocumento) = '$Fecha')
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R41Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R41Q_Detalle_Articulo($IdArticulo) {
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
