@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
<link rel="stylesheet" href="/assets/sweetalert2/sweetalert2.css">
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

@section('scriptsFoot')
  <script src="/assets/numeric/numeric.js"></script>
  <script src="/assets/sweetalert2/sweetalert2.js"></script>

  <script>
    $(document).ready(function () {
      //$('#diasUltimaVenta').numeric();

      params = new URLSearchParams(window.location.search);
      if (params.get('seccion') == 'stock') {
        Swal.showLoading();

        $.ajax({
          type: 'GET',
          url: '/api/sobrestock' + window.location.search,
          success: function (response) {
            html = '';

            for (var i = 0; i <= response.resultado.length - 1; i++) {
              html = html + `
                <tr>
                  <td>${response.resultado[i].contador}</td>
                  <td>${response.resultado[i].tipo_movimiento}</td>
                  <td>${response.resultado[i].codigo}</td>
                  <td>${response.resultado[i].codigo_barra}</td>
                  <td align="left" class="CP-barrido">${response.resultado[i].descripcion}</td>
                  <td>${response.resultado[i].fecha_registro}</td>
                  <td>${response.resultado[i].fecha_vencimiento}</td>
                  <td>${response.resultado[i].dias_restantes}</td>
                  <td>${response.resultado[i].cantidad_recibida}</td>
                  <td>${response.resultado[i].existencia}</td>
                  <td>${response.resultado[i].operador}</td>
                  <td>${response.resultado[i].proveedor}</td>
                  <td>${response.resultado[i].numero_factura}</td>
                  <td>${response.resultado[i].ultima_venta}</td>
                </tr>
              `;
            }

            $('#tbody_sobrestock').html(html);
            $('#tiempo_carga').html(response.tiempo_carga);

            Swal.hideLoading();
          },
          error: function (error) {
            cosole.log(error.responseText);
          }
        });
      }


      $('#vidaUtil').change(function () {
        value = $('#vidaUtil').val();

        if (value < 0) {
          alert('Debe ingresar un numero positivo');
          $('#vidaUtil').val('');
        }
      });

      $('#sobreStock').change(function () {
        value = $('#sobreStock').val();

        if (value < 0) {
          alert('Debe ingresar un numero positivo');
          $('#sobreStock').val('');
        }
      });


      $('.secciones').click(function (event) {
        event.preventDefault();

        params = new URLSearchParams(window.location.search);

        seccion = $(this).attr('data-seccion');

        if (params.has('seccion')) {
          params.delete('seccion');
          params.append('seccion', seccion);
        } else {
          params.append('seccion', seccion);
        }

        window.location.href = '?' + params.toString();
      });
    });
  </script>
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Monitoreo de Inventarios
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

  if (isset($_GET['fechaInicio'])) {

    $InicioCarga = new DateTime("now");

    R31_Monitoreo_Inventarios($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Monitoreo de Inventarios');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: <span id="tiempo_carga">'.$IntervalCarga->format("%Y-%M-%D %H:%I:%S") . '</span>';
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
          </tr>

          <tr>
            <td align="center">
              Vida Util:
            </td>
            <td align="right">
              <input id="vidaUtil" name="vidaUtil" type="number" required style="width:100%;">
            </td>

            <td align="center">
              Dias de sobre-stock:
            </td>
            <td align="right">
              <input id="sobreStock" name="sobreStock" type="number" required style="width:100%;">
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
    TITULO: R31_Monitoreo_Inventarios
    FUNCION: Arma una lista de productos en falla
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R31_Monitoreo_Inventarios($SedeConnection,$FInicial,$FFinal){
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    $sql5 = R31Q_Monitoreo_Inventarios($FInicial,$FFinal);
    $result = sqlsrv_query($conn,$sql5);

    $sql6 = R31Q_Monitoreo_Correcciones($FInicial,$FFinal);
    $result1 = sqlsrv_query($conn,$sql6);

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


    echo '
      <table>
      <tbody>
        <tr>
          <td style="width:11%;" align="center">
              <a data-seccion="cambios" href="#" class="secciones btn btn-outline-primary btn-sm btn-block">CAMBIOS DE PRECIO A LA BAJA</a>
          </td>

          <td style="width:11%;" align="center">
            <a data-seccion="entradas" href="#" class="secciones btn btn-outline-danger btn-sm btn-block">ENTRADAS Y SALIDAS DE INVENTARIO</a>
          </td>

          <td style="width:11%;" align="center">
            <a data-seccion="vencimiento" href="#" class="secciones btn btn-outline-warning btn-sm btn-block">SIN FECHA DE VENCIMIENTO</a>
          </td>
        </tr>

        <tr>
            <td style="width:11%;" align="center">
                <a data-seccion="corto" href="#" class="secciones btn btn-outline-success btn-sm btn-block">ARTICULOS CORTO VENCIMIENTO</a>
            </td>

            <td style="width:11%;" align="center">
                <a data-seccion="stock" href="#" class="secciones btn btn-outline-info btn-sm btn-block">ARTICULOS EN SOBRE STOCK</a>
            </td>

            <td style="width:11%;" align="center">
                <a data-seccion="troquel" href="#" class="secciones btn btn-outline-dark btn-sm btn-block">CAMBIOS DE PRECIO VÍA TROQUEL</a>
            </td>
        </tr>

        <tr>
            <td style="width:11%;" align="center">
                <a data-seccion="codificacion" href="#" class="secciones btn btn-outline-primary btn-sm btn-block">CODIFICACIONES</a>
            </td>
        </tr>
      </tbody>
    </table>
    ';

    if (!isset($_GET['seccion']) or $_GET['seccion'] == 'cambios') {
      echo'
      <hr>
      <h4 align="center">Cambios de precios a la baja</h4>
      <hr>
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Tipo de Movimiento</th>
            <th scope="col" class="CP-sticky">Origen de Movimiento</th>
            <th scope="col" class="CP-sticky">Numero de Movimiento</th>
            <th scope="col" class="CP-sticky">Fecha de Movimiento</th>
            <th scope="col" class="CP-sticky">Codigo Interno</th>
            <th scope="col" class="CP-sticky">Codigo de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Cantidad</th>
            <th scope="col" class="CP-sticky">Numero de Lote (CC -)</th>
            <th scope="col" class="CP-sticky">Numero de Lote (CC +)</th>
            <th scope="col" class="CP-sticky">Almacen</td>
            <th scope="col" class="CP-sticky">Costo Uniario (CC -)'.SigVe.'</td>
            <th scope="col" class="CP-sticky">Costo Uniario (CC +)'.SigVe.'</td>
            <th scope="col" class="CP-sticky">Operador</th>
          </tr>
        </thead>
        <tbody>
      ';

      $contador = 1;
      while($row = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
        $TipoMovimiento = FG_Limpiar_Texto($row["TipoMovimiento"]);
        $NumeroMovimiento = $row["NumeroMovimiento"];
        $CostoUniario = $row["CostoUniario"];

        if($TipoMovimiento == 'Corrección costo -'){
          $NumeroMovCCNeg = $NumeroMovimiento;
          $LoteCCNeg = $row["Lote"];
          $CostoUniarioCCNeg = $CostoUniario;
        }
        else if( ($TipoMovimiento=='Corrección costo +') && ($NumeroMovimiento == $NumeroMovCCNeg) &&($CostoUniarioCCNeg > $CostoUniario) ) {

          $IdArticulo = $row["IdArticulo"];
          $OrigenMovimiento = $row["OrigenMovimiento"];
          $OrigenMovimiento = determminar_Origen_Movimiento($OrigenMovimiento);
          $FechaMovimiento = $row["FechaMovimiento"];
          $CodigoArticulo = $row["CodigoInterno"];
          $CodigoBarra = $row["CodigoBarra"];
          $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
          $Cantidad = $row["Cantidad"];
          $Lote = $row["Lote"];
          $Almacen = $row["Almacen"];
          $Operador = $row["Operador"];

          echo '<tr>';
          echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
          echo '<td align="left">'.($TipoMovimiento).'</td>';
          echo '<td align="left">'.FG_Limpiar_Texto($OrigenMovimiento).'</td>';
          echo '<td align="left">'.$NumeroMovimiento.'</td>';
          echo '<td align="center">'.$FechaMovimiento->format('d-m-Y h:i:s A').'</td>';
          echo '<td align="left">'.$CodigoArticulo.'</td>';
          echo '<td align="center">'.$CodigoBarra.'</td>';
          echo
          '<td align="left" class="CP-barrido">
          <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
            .$Descripcion.
          '</a>
          </td>';
          echo '<td align="center">'.intval($Cantidad).'</td>';
          echo '<td align="center">'.$LoteCCNeg.'</td>';
          echo '<td align="center">'.$Lote.'</td>';
          echo '<td align="center">'.FG_Limpiar_Texto($Almacen).'</td>';
          echo '<td align="center">'.number_format($CostoUniarioCCNeg,2,"," ,"." ).'</td>';
          echo '<td align="center">'.number_format($CostoUniario,2,"," ,"." ).'</td>';
          echo '<td align="center">'.$Operador.'</td>';
          echo '</tr>';
          $contador++;
        }
      }
      echo '
        </tbody>
      </table>';

    }

    if (isset($_GET['seccion']) && $_GET['seccion'] == 'entradas') {

      echo'
      <br>
      <hr>
      <h4 align="center">Entradas y salidas de inventario</h4>
      <hr>
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Tipo de Movimiento</th>
            <th scope="col" class="CP-sticky">Origen de Movimiento</th>
            <th scope="col" class="CP-sticky">Numero de Movimiento</th>
            <th scope="col" class="CP-sticky">Traslado</th>
            <th scope="col" class="CP-sticky">Fecha de Movimiento</th>
            <th scope="col" class="CP-sticky">Codigo Interno</th>
            <th scope="col" class="CP-sticky">Codigo de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Cantidad</th>
            <th scope="col" class="CP-sticky">Numero de Lote</th>
            <th scope="col" class="CP-sticky">Almacen</td>
            <th scope="col" class="CP-sticky">Costo Uniario '.SigVe.'</td>
            <th scope="col" class="CP-sticky">Comentario</td>
            <th scope="col" class="CP-sticky">Operador</th>
          </tr>
        </thead>
        <tbody>
      ';
      $contador = 1;
      while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $IdArticulo = $row["IdArticulo"];
        $TipoMovimiento = $row["TipoMovimiento"];
        $OrigenMovimiento = $row["OrigenMovimiento"];
        $OrigenMovimiento = determminar_Origen_Movimiento($OrigenMovimiento);
        $NumeroMovimiento = $row["NumeroMovimiento"];
        $FechaMovimiento = $row["FechaMovimiento"];
        $CodigoArticulo = $row["CodigoInterno"];
        $CodigoBarra = $row["CodigoBarra"];
        $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
        $Cantidad = $row["Cantidad"];
        $Lote = $row["Lote"];
        $Almacen = $row["Almacen"];
        $CostoUniario = $row["CostoUniario"];
        $Operador = $row["Operador"];

        $ComentarioAjuste = $row["ComentarioAjuste"];
        $ComentarioTransferencia = $row["ComentarioTransferencia"];

        if(!is_null($ComentarioAjuste)){
          $comentario = $ComentarioAjuste;
        }
        else if(!is_null($ComentarioTransferencia)){
          $comentario = $ComentarioTransferencia;
        }
        else{
          $comentario = "-";
        }

        $traslado = DB::select("SELECT * FROM traslados WHERE numero_ajuste = '$NumeroMovimiento' LIMIT 1");

        if ($traslado) {
            $traslado = $traslado[0]->id;
            $traslado = '<a href="/traslado/'.$traslado.'" style="text-decoration: none; color: black" target="_blank">'.$traslado.'</a>';
        } else {
            $traslado = '';
        }

        if (FG_Limpiar_Texto($comentario) == '') {
            echo '<tr class="bg-warning">';
        } else {
            echo '<tr>';
        }


        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
        echo '<td align="left">'.FG_Limpiar_Texto($TipoMovimiento).'</td>';
        echo '<td align="left">'.FG_Limpiar_Texto($OrigenMovimiento).'</td>';
        echo '<td align="left">'.$NumeroMovimiento.'</td>';
        echo '<td align="left" class="CP-barrido">'.$traslado.'</td>';
        echo '<td align="center">'.$FechaMovimiento->format('d-m-Y h:i:s A').'</td>';
        echo '<td align="left">'.$CodigoArticulo.'</td>';
        echo '<td align="center">'.$CodigoBarra.'</td>';
        echo
        '<td align="left" class="CP-barrido">
        <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$Descripcion.
        '</a>
        </td>';
        echo '<td align="center">'.intval($Cantidad).'</td>';
        echo '<td align="center">'.$Lote.'</td>';
        echo '<td align="center">'.FG_Limpiar_Texto($Almacen).'</td>';
        echo '<td align="center">'.number_format($CostoUniario,2,"," ,"." ).'</td>';
        echo '<td align="center">'.FG_Limpiar_Texto($comentario).'</td>';
        echo '<td align="center">'.$Operador.'</td>';
        echo '</tr>';
        $contador++;
      }
      echo '
        </tbody>
      </table>';
    }

    if (isset($_GET['seccion']) && $_GET['seccion'] == 'vencimiento') {

      echo'
      <br>
      <hr>
      <h4 align="center">Articulos sin fecha de vencimiento</h4>
      <hr>
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Tipo de Movimiento</th>
            <th scope="col" class="CP-sticky">Codigo</th>
            <th scope="col" class="CP-sticky">Codigo de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Fecha de Registro</th>
            <th scope="col" class="CP-sticky">Fecha de Vencimiento</th>
            <th scope="col" class="CP-sticky">Cantidad Recibida</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Operador</th>
            <th scope="col" class="CP-sticky">Proveedor</th>
            <th scope="col" class="CP-sticky">Numero Factura</th>
            <th scope="col" class="CP-sticky">Ultima Venta</th>
          </tr>
        </thead>
        <tbody>
      ';

      $sql7 = R31Q_Articulos_Sin_Vencimiento($_GET['fechaInicio'], $_GET['fechaFin']);
      $result7 = sqlsrv_query($conn,$sql7);

      $contador = 1;

      while ($row3 = sqlsrv_fetch_array($result7, SQLSRV_FETCH_ASSOC)) {
        $codigo = $row3['codigo_articulo'];
        $codigo_barra = $row3['codigo_barra'];
        $descripcion = FG_Limpiar_Texto($row3['descripcion']);
        $fecha_registro = $row3['fecha_registro']->format('d/m/Y');
        $cantidad_recibida = intval($row3['cantidad_recibida']);
        $existencia = intval($row3['existencia']);
        $operador = $row3['operador'];
        $id_articulo = $row3['id_articulo'];
        $proveedor = FG_Limpiar_Texto($row3['proveedor']);
        $numero_factura = $row3['numero_factura'];
        $ultima_venta = ($row3['ultima_venta']) ? $row3['ultima_venta']->format('d/m/Y') : '-';

        echo '<tr>';
        echo '<td>'.$contador.'</td>';
        echo '<td>Compras</td>';
        echo
        '<td align="left" class="CP-barrido">
        <a href="/reporte10?Descrip='.$descripcion.'&Id='.$id_articulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$codigo.
        '</a>
        </td>';
        echo '<td>'.$codigo_barra.'</td>';
        echo
        '<td align="left" class="CP-barrido">
        <a href="/reporte2?Id='.$id_articulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$descripcion.
        '</a>
        </td>';
        echo '<td>'.$fecha_registro.'</td>';
        echo '<td>-</td>';
        echo '<td>'.$cantidad_recibida.'</td>';
        echo '<td>'.$existencia.'</td>';
        echo '<td>'.$operador.'</td>';
        echo '<td>'.$proveedor.'</td>';
        echo '<td>'.$numero_factura.'</td>';
        echo '<td>'.$ultima_venta.'</td>';

        echo '</tr>';

        $contador++;
      }

      echo '</tbody>';
      echo '</table>';
    }


    if (isset($_GET['seccion']) && $_GET['seccion'] == 'corto') {
      echo'
      <br>
      <hr>
      <h4 align="center">Articulos corto vencimiento</h4>
      <hr>
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Tipo de Movimiento</th>
            <th scope="col" class="CP-sticky">Codigo</th>
            <th scope="col" class="CP-sticky">Codigo de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Fecha de Registro</th>
            <th scope="col" class="CP-sticky">Fecha de Vencimiento</th>
            <th scope="col" class="CP-sticky">Vida util</th>
            <th scope="col" class="CP-sticky">Cantidad Recibida</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Operador</th>
            <th scope="col" class="CP-sticky">Proveedor</th>
            <th scope="col" class="CP-sticky">Numero Factura</th>
            <th scope="col" class="CP-sticky">Ultima Venta</th>
          </tr>
        </thead>
        <tbody>
      ';

      $sql8 = R31Q_Articulos_Corto_Vencimiento($_GET['fechaInicio'], $_GET['fechaFin'], $_GET['vidaUtil']);
      $result8 = sqlsrv_query($conn,$sql8);

      $contador = 1;

      while ($row4 = sqlsrv_fetch_array($result8, SQLSRV_FETCH_ASSOC)) {
        $codigo = $row4['codigo_articulo'];
        $codigo_barra = $row4['codigo_barra'];
        $descripcion = FG_Limpiar_Texto($row4['descripcion']);
        $fecha_registro = $row4['fecha_registro']->format('d/m/Y');
        $fecha_vencimiento = $row4['fecha_vencimiento']->format('d/m/Y');
        $vida_util = intval($row4['vida_util']);
        $cantidad_recibida = intval($row4['cantidad_recibida']);
        $existencia = intval($row4['existencia']);
        $operador = $row4['operador'];
        $id_articulo = $row4['id_articulo'];
        $proveedor = FG_Limpiar_Texto($row4['proveedor']);
        $numero_factura = $row4['numero_factura'];
        $ultima_venta = ($row4['ultima_venta']) ? $row4['ultima_venta']->format('d/m/Y') : '-';

        echo '<tr>';
        echo '<td>'.$contador.'</td>';
        echo '<td>Compras</td>';
        echo
        '<td align="left" class="CP-barrido">
        <a href="/reporte10?Descrip='.$descripcion.'&Id='.$id_articulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$codigo.
        '</a>
        </td>';
        echo '<td>'.$codigo_barra.'</td>';
        echo
        '<td align="left" class="CP-barrido">
        <a href="/reporte2?Id='.$id_articulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$descripcion.
        '</a>
        </td>';
        echo '<td>'.$fecha_registro.'</td>';
        echo '<td>'.$fecha_vencimiento.'</td>';
        echo '<td>'.$vida_util.'</td>';
        echo '<td>'.$cantidad_recibida.'</td>';
        echo '<td>'.$existencia.'</td>';
        echo '<td>'.$operador.'</td>';
        echo '<td>'.$proveedor.'</td>';
        echo '<td>'.$numero_factura.'</td>';
        echo '<td>'.$ultima_venta.'</td>';
        echo '</tr>';

        $contador++;
      }

      echo '</tbody>';
      echo '</table>';
    }


    if (isset($_GET['seccion']) && $_GET['seccion'] == 'stock') {
      echo'
      <br>
      <hr>
      <h4 align="center">Articulos con sobre stock</h4>
      <hr>
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Tipo de Movimiento</th>
            <th scope="col" class="CP-sticky">Codigo</th>
            <th scope="col" class="CP-sticky">Codigo de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Fecha de Registro</th>
            <th scope="col" class="CP-sticky">Fecha de Vencimiento</th>
            <th scope="col" class="CP-sticky">Dias Restantes</th>
            <th scope="col" class="CP-sticky">Cantidad Recibida</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Operador</th>
            <th scope="col" class="CP-sticky">Proveedor</th>
            <th scope="col" class="CP-sticky">Numero Factura</th>
            <th scope="col" class="CP-sticky">Ultima Venta</th>
          </tr>
        </thead>
        <tbody id="tbody_sobrestock">
        </tbody>
      </table>';
    }



    if (isset($_GET['seccion']) && $_GET['seccion'] == 'troquel') {
      echo'
      <br>
      <hr>
      <h4 align="center">Cambios de precio vía troquel</h4>
      <hr>
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Fecha</th>
            <th scope="col" class="CP-sticky">Hora</th>
            <th scope="col" class="CP-sticky">Detalle</th>
            <th scope="col" class="CP-sticky">Operador</th>
          </tr>
        </thead>
        <tbody>
      ';

      $auditorias = compras\Auditoria::whereDate('created_at', '>=', $_GET['fechaInicio'])
        ->whereDate('created_at', '<=', $_GET['fechaFin'])
        ->where('tabla', 'TROQUEL CLIENTE')
        ->get();

      $contador = 1;

      foreach ($auditorias as $auditoria) {
        echo '<tr>';
        echo '<td class="text-center">'.$contador.'</td>';
        echo '<td class="text-center">'.$auditoria->created_at->format('d/m/Y').'</td>';
        echo '<td class="text-center">'.$auditoria->created_at->format('h:i A').'</td>';
        echo '<td class="text-center">'.$auditoria->registro.'</td>';
        echo '<td class="text-center">'.$auditoria->user.'</td>';
        echo '</tr>';

        $contador++;
      }

      echo '</tbody>';
      echo '</table>';
    }



    if (isset($_GET['seccion']) && $_GET['seccion'] == 'codificacion') {
      $atributos = [];

      $result = sqlsrv_query($conn,"SELECT InvAtributo.Descripcion AS descripcion FROM InvAtributo");

      while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $atributos[] = FG_Limpiar_Texto($row['descripcion']);
      }

      $atributos = implode(', ', $atributos);

      echo'
      <br>
      <hr>
      <h4 align="center">Codificaciones</h4>
      <hr>
      <table class="table table-striped table-bordered col-12 sortable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">Reglamento de estandarización de nombres de misceláneos</th>
            <th scope="col" class="CP-sticky">Reglamento de estandarización de nombres de medicamentos</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Tipo de Producto (en singular) + Nombre Comercial  + Tipo o Variante + Peso o Contenido + Marca (Opcional)</td>
            <td>Molécula o Nombre Comercial + Concentración + Tipo o Variantes + Contenido o Cantidad + Marca o Laboratorio</td>
          </tr>
          <tr>
            <td>Ejemplo: Galleta Oreo Chocolate 36 gramos</td>
            <td>Ejemplo: Acetaminofén 500mg Tabletas x 10 Pfizer</td>
          </tr>
          <tr>
            <td width="50%">
                <b>NOTAS:</b><br>

                <ul>
                    <li>No abreviar palabras claves (tableta, chocolate, jarabe, etc.) y usar todos los conectores de palabras (de, con, para, etc.)</li>
                    <li>En el caso de artículos lideres se puede obviar el Tipo</li>
                    <li>En el caso de medicamentos es valido colocar entre paréntesis artículos referenciales</li>
                    <li>Obviar caracteres especiales y acentos exceptuando la ñ</li>
                    <li>Cualquier información adicional es aceptada siempre y cuando se cumpla con el objetivo de una descripción clara y completa</li>
                    <li>La descripción física del articulo siempre predomina sobre cualquier criterio</li>
                </ul>
            </td>

            <td>
                <b>ATRIBUTOS:</b><br>
                '.$atributos.'.
            </td>
          </tr>
        </tbody>
      </table>
      <br>
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Código interno</th>
            <th scope="col" class="CP-sticky">Código de barra</th>
            <th scope="col" class="CP-sticky">Descripción</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Marca</th>
            <th scope="col" class="CP-sticky">Unidad mínima</th>
            <th scope="col" class="CP-sticky">Clasificación de etiquetas</th>
            <th scope="col" class="CP-sticky">Categoría</th>
            <th scope="col" class="CP-sticky">Subcategoría</th>
            <th scope="col" class="CP-sticky">Atributo de medicinas y/o misceláneos</th>
            <th scope="col" class="CP-sticky">Atributos adicionales</th>
            <th scope="col" class="CP-sticky">Componente</th>
            <th scope="col" class="CP-sticky">Uso terapéutico</th>
            <th scope="col" class="CP-sticky">Último proveedor</th>
            <th scope="col" class="CP-sticky">Precio Bs.</th>
          </tr>
        </thead>
        <tbody>
      ';

      $sql9 = R31Q_Codificaciones($_GET['fechaInicio'],$_GET['fechaFin']);
      $result9 = sqlsrv_query($conn,$sql9);

      $contador = 1;

      while ($row9 = sqlsrv_fetch_array($result9, SQLSRV_FETCH_ASSOC)) {
        $id_articulo = $row9['id_articulo'];
        $codigo_interno = $row9['codigo_interno'];
        $codigo_barra = $row9['codigo_barra'];
        $descripcion = FG_Limpiar_Texto($row9['descripcion']);
        $existencia = $row9['existencia'];
        $marca = $row9['marca'];
        $ultimo_proveedor = $row9['ultimo_proveedor'];

        $sql11 = DB::select("
            SELECT
                (SELECT CONCAT(unidads.divisor, ' ', unidads.unidad_minima) FROM unidads WHERE unidads.id_articulo = '$id_articulo') AS unidad_minima,
                (SELECT etiquetas.clasificacion FROM etiquetas WHERE etiquetas.id_articulo = '$id_articulo') AS clasificacion,
                (SELECT categorias.nombre FROM categorias WHERE categorias.codigo = (SELECT categorizacions.codigo_categoria FROM categorizacions WHERE categorizacions.id_articulo = '$id_articulo')) AS categoria,
                (SELECT subcategorias.nombre FROM subcategorias WHERE subcategorias.codigo = (SELECT categorizacions.codigo_subcategoria FROM categorizacions WHERE categorizacions.id_articulo = '$id_articulo')) AS subcategoria
            ;
        ");

        $unidad_minima = isset($sql11[0]->unidad_minima) ? $sql11[0]->unidad_minima : '';
        $clasificacion = isset($sql11[0]->clasificacion) ? $sql11[0]->clasificacion : '';
        $categoria = isset($sql11[0]->categoria) ? $sql11[0]->categoria : '';
        $subcategoria = isset($sql11[0]->subcategoria) ? $sql11[0]->subcategoria : '';

        $tipo = FG_Tipo_Producto($row9['tipo']);

        $sql10 = R31Q_Atributos_Adicionales($row9['id_articulo']);
        $result10 = sqlsrv_query($conn,$sql10);

        $adicionales = [];

        while ($row10 = sqlsrv_fetch_array($result10, SQLSRV_FETCH_ASSOC)) {
            $adicionales[] = $row10['Descripcion'];
        }

        $adicionales = implode($adicionales, ', ');

        if (FG_Mi_Ubicacion() == 'FTN') {
            $componentes = FG_Limpiar_Texto(FG_Componente_Articulo($conn,$id_articulo));

            $uso_terapeutico = FG_Limpiar_Texto(FG_Aplicacion_Articulo($conn,$id_articulo));
        }
        else {
            $connModAtteClientes = FG_Conectar_Mod_Atte_Clientes(FG_Mi_Ubicacion());

            $componentes = '';
            $sql12 = "SELECT InCompon.Nombre FROM InCompon WHERE InCompon.CoCompo IN (SELECT InComArt.CoCompo FROM InComArt WHERE InComArt.CoBarra = '$codigo_barra')";
            $result12 = sqlsrv_query($connModAtteClientes,$sql12);

            while($row11 = sqlsrv_fetch_array($result12, SQLSRV_FETCH_ASSOC)) {
                $componentes = $componentes.' '.$row11['Nombre'];
            }


            $connModAtteClientes = FG_Conectar_Mod_Atte_Clientes(FG_Mi_Ubicacion());

            $uso_terapeutico = '';
            $sql13 = "SELECT InAplica.Descripcion FROM InAplica WHERE InAplica.CoAplica IN (SELECT InAplArt.CoAplica FROM InAplArt WHERE InAplArt.CoBarra = '$codigo_barra')";
            $result13 = sqlsrv_query($connModAtteClientes,$sql13);

            while($row13 = sqlsrv_fetch_array($result13, SQLSRV_FETCH_ASSOC)) {
                $uso_terapeutico = $uso_terapeutico.' '.$row13['Descripcion'];
            }
        }

        $existencia_almacen_1 = $row9['existencia_almacen_1'];
        $existencia_almacen_2 = $row9['existencia_almacen_2'];
        $troquelado = $row9['troquelado'];
        $utilidad_articulo = $row9['utilidad_articulo'];
        $utilidad_categoria = $row9['utilidad_categoria'];
        $troquel_almacen_1 = $row9['troquel_almacen_1'];
        $precio_compra_bruto_almacen_1 = $row9['precio_compra_bruto_almacen_1'];
        $troquel_almacen_2 = $row9['troquel_almacen_2'];
        $precio_compra_bruto_almacen_2 = $row9['precio_compra_bruto_almacen_2'];
        $precio_compra_bruto = $row9['precio_compra_bruto'];
        $iva = $row9['iva'];

        $precio = FG_Calculo_Precio_Alfa($existencia, $existencia_almacen_1, $existencia_almacen_2, $troquelado, $utilidad_articulo, $utilidad_categoria, $troquel_almacen_1, $precio_compra_bruto_almacen_1, $troquel_almacen_2, $precio_compra_bruto_almacen_2, $precio_compra_bruto, $iva, 'CON_EXISTENCIA');
        $precio = number_format($precio, 2, ',', '.');

        echo '<tr>';
        echo '<td class="text-center">'.$contador.'</td>';
        echo '<td class="text-center">'.$codigo_interno.'</td>';
        echo '<td class="text-center">'.$codigo_barra.'</td>';
        echo '<td class="text-center">'.$descripcion.'</td>';
        echo '<td class="text-center">'.$existencia.'</td>';
        echo '<td class="text-center">'.$marca.'</td>';
        echo '<td class="text-center">'.$unidad_minima.'</td>';
        echo '<td class="text-center">'.$clasificacion.'</td>';
        echo '<td class="text-center">'.$categoria.'</td>';
        echo '<td class="text-center">'.$subcategoria.'</td>';
        echo '<td class="text-center">'.$tipo.'</td>';
        echo '<td class="text-center">'.$adicionales.'</td>';
        echo '<td class="text-center">'.$componentes.'</td>';
        echo '<td class="text-center">'.$uso_terapeutico.'</td>';
        echo '<td class="text-center">'.$ultimo_proveedor.'</td>';
        echo '<td class="text-center">'.$precio.'</td>';
        echo '</tr>';

        $contador++;
      }

      echo '</tbody>';
      echo '</table>';
    }

    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R31Q_Monitoreo_Inventarios
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
    DESAROLLADO POR: SERGIO COVA
  */
  function R31Q_Monitoreo_Inventarios($FInicial,$FFinal) {
    $sql = "
      SELECT
    --Tipo Movimiento
    InvCausa.Descripcion as TipoMovimiento,
    -- Origen Movimiento
    InvMovimiento.origenMovimiento as OrigenMovimiento,
    --Numero de Movimiento
    InvMovimiento.DocumentoOrigen as NumeroMovimiento,
    --Id Articulo
    InvArticulo.id as IdArticulo,
    --Codigo Interno
    InvArticulo.CodigoArticulo AS CodigoInterno,
    --Codigo de Barra
    (SELECT CodigoBarra
    FROM InvCodigoBarra
    WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
    AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
    --Descripcion
    InvArticulo.Descripcion,
    --Cantidad
    InvMovimiento.Cantidad as Cantidad,
    --Lote
    InvLote.Numero as Lote,
    --Almacen
    InvAlmacen.Descripcion as Almacen,
    --Costo Unitario
    InvMovimiento.M_CostoUnitario as CostoUniario,
    --Comentario Ajuste
    (SELECT InvAjuste.Comentario
    FROM InvAjuste
    WHERE InvAjuste.NumeroAjuste = InvMovimiento.DocumentoOrigen AND (InvMovimiento.InvCausaId = '14' OR InvMovimiento.InvCausaId = '15') ) AS ComentarioAjuste,
    --Comentario Transferecia
    (SELECT InvTransferenciaAlmacen.Observaciones
    FROM InvTransferenciaAlmacen
    WHERE InvTransferenciaAlmacen.NumeroTransferencia = InvMovimiento.DocumentoOrigen AND (InvMovimiento.InvCausaId = '5' OR InvMovimiento.InvCausaId = '6') ) AS ComentarioTransferencia,
    --Operador
    InvMovimiento.Auditoria_Usuario as Operador,
    --Fecha movimiento
    InvMovimiento.FechaMovimiento  as FechaMovimiento
    FROM InvMovimiento
    INNER JOIN InvCausa ON InvCausa.id = InvMovimiento.InvCausaId
    INNER JOIN InvArticulo ON InvArticulo.id = InvMovimiento.InvArticuloId
    INNER JOIN InvLote ON InvLote.id = InvMovimiento.InvLoteId
    INNER JOIN InvAlmacen ON InvAlmacen.id = InvMovimiento.InvAlmacenId
    WHERE (InvMovimiento.InvCausaId = '14' OR InvMovimiento.InvCausaId = '15'
    OR InvMovimiento.InvCausaId = '5' OR InvMovimiento.InvCausaId = '6')
    AND(InvMovimiento.FechaMovimiento > '$FInicial' AND InvMovimiento.FechaMovimiento < '$FFinal')
    ORDER BY InvMovimiento.FechaMovimiento ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  function determminar_Origen_Movimiento($OrigenMovimiento){
    switch ($OrigenMovimiento) {
      case '1':
       $nombreMovimiento = "Ventas";
      break;
      case '2':
        $nombreMovimiento = "Ventas devolucion";
      break;
      case '3':
        $nombreMovimiento = "Compras";
      break;
      case '4':
        $nombreMovimiento = "Indefinido-4";
      break;
      case '5':
        $nombreMovimiento = "Ajuste de Inventario";
      break;
      case '6':
        $nombreMovimiento = "Indefinido-6";
      break;
      case '7':
        $nombreMovimiento = "Correccion de Costo";
      break;
      case '8':
        $nombreMovimiento = "Indefinido-8";
      break;
      case '9':
        $nombreMovimiento = "Toma de Inventario";
      break;
      case '10':
        $nombreMovimiento = "Transferencia de Almacen";
      break;
      default:
        $nombreMovimiento =  "-";
      break;
    }
    return $nombreMovimiento;
  }
  /**********************************************************************************/
  /*
    TITULO: R31Q_Monitoreo_Inventarios
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
    DESAROLLADO POR: SERGIO COVA
  */
  function R31Q_Monitoreo_Correcciones($FInicial,$FFinal) {
    $sql = "
    SELECT
    InvArticulo.id as IdArticulo,
    InvArticulo.CodigoArticulo AS CodigoInterno,
    (SELECT CodigoBarra
    FROM InvCodigoBarra
    WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
    AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
    InvArticulo.Descripcion,
    InvMovimiento.origenMovimiento as OrigenMovimiento,
    InvMovimiento.DocumentoOrigen as NumeroMovimiento,
    InvCausa.Descripcion as TipoMovimiento,
    InvMovimiento.Cantidad as Cantidad,
    InvLote.Numero as Lote,
    InvAlmacen.Descripcion as Almacen,
    InvMovimiento.M_CostoUnitario as CostoUniario,
    InvMovimiento.Auditoria_Usuario as Operador,
    InvMovimiento.FechaMovimiento  as FechaMovimiento
    FROM InvMovimiento
    INNER JOIN InvCausa ON InvCausa.id = InvMovimiento.InvCausaId
    INNER JOIN InvArticulo ON InvArticulo.id = InvMovimiento.InvArticuloId
    INNER JOIN InvLote ON InvLote.id = InvMovimiento.InvLoteId
    INNER JOIN InvAlmacen ON InvAlmacen.id = InvMovimiento.InvAlmacenId
    WHERE (InvMovimiento.InvCausaId = '11' or InvMovimiento.InvCausaId = '12')
    AND(InvMovimiento.FechaMovimiento > '$FInicial' AND InvMovimiento.FechaMovimiento < '$FFinal')
    ORDER BY InvMovimiento.FechaMovimiento ASC,  InvCausa.Descripcion ASC, InvMovimiento.M_CostoUnitario ASC
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R31Q_Articulos_Sin_Vencimiento
    FUNCION: Ubicar productos sin fecha de vencimiento entre rango de fecha
    RETORNO: Lista de productos sin fecha de vencimiento entre rango de fecha
    DESAROLLADO POR: NISA DELGADO
  */
  function R31Q_Articulos_Sin_Vencimiento($FInicial,$FFinal) {
    $sql = "
      SELECT
        (SELECT InvArticulo.CodigoArticulo FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS codigo_articulo,
        (SELECT InvArticulo.Id FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS id_articulo,
        (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = ComFacturaDetalle.InvArticuloId AND InvCodigoBarra.EsPrincipal = 1) AS codigo_barra,
        (SELECT InvArticulo.DescripcionLarga FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS descripcion,
        (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS fecha_registro,
        ComFacturaDetalle.CantidadRecibidaFactura AS cantidad_recibida,
        (SELECT SUM(InvLoteAlmacen.Existencia) FROM InvLoteAlmacen WHERE InvLoteAlmacen.InvAlmacenId IN (1, 2) AND InvLoteAlmacen.InvArticuloId = ComFacturaDetalle.InvArticuloId) AS existencia,
        (SELECT ComFactura.Auditoria_Usuario FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS operador,
        (SELECT GenPersona.Nombre FROM GenPersona WHERE GenPersona.Id = (SELECT ComProveedor.GenPersonaId FROM ComProveedor WHERE ComProveedor.Id = (SELECT ComFactura.ComProveedorId FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId))) AS proveedor,
        (SELECT ComFactura.NumeroFactura FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS numero_factura,
        (SELECT VenVenta.Auditoria_FechaCreacion FROM VenVenta WHERE VenVenta.Id = (SELECT TOP 1 VenVentaDetalle.VenVentaId FROM VenVentaDetalle WHERE VenVentaDetalle.InvArticuloId = ComFacturaDetalle.InvArticuloId ORDER BY VenVentaDetalle.VenVentaId DESC)) AS ultima_venta
      FROM
        ComFacturaDetalle
      WHERE
        ComFacturaDetalle.ComFacturaId IN (SELECT ComFactura.Id FROM ComFactura WHERE ComFactura.FechaRegistro BETWEEN '$FInicial' AND '$FFinal') AND
        (ComFacturaDetalle.FechaVencimiento IS NULL OR ComFacturaDetalle.FechaVencimiento = '')
      ORDER BY (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId);
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R31Q_Articulos_Corto_Vencimiento
    FUNCION: Ubicar productos sin fecha de vencimiento entre rango de fecha y vida util
    RETORNO: Lista de productos sin fecha de vencimiento entre rango de fecha y vida util
    DESAROLLADO POR: NISA DELGADO
  */
  function R31Q_Articulos_Corto_Vencimiento($FInicial,$FFinal, $VUtil) {
    $sql = "
      SELECT
        (SELECT InvArticulo.CodigoArticulo FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS codigo_articulo,
        (SELECT InvArticulo.Id FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS id_articulo,
        (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = ComFacturaDetalle.InvArticuloId AND InvCodigoBarra.EsPrincipal = 1) AS codigo_barra,
        (SELECT InvArticulo.DescripcionLarga FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS descripcion,
        (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS fecha_registro,
        ComFacturaDetalle.FechaVencimiento AS fecha_vencimiento,
        ComFacturaDetalle.CantidadRecibidaFactura AS cantidad_recibida,
        (SELECT SUM(InvLoteAlmacen.Existencia) FROM InvLoteAlmacen WHERE InvLoteAlmacen.InvAlmacenId IN (1, 2) AND InvLoteAlmacen.InvArticuloId = ComFacturaDetalle.InvArticuloId) AS existencia,
        (SELECT ComFactura.Auditoria_Usuario FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS operador,
        DATEDIFF(DAY, (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId), ComFacturaDetalle.FechaVencimiento) AS vida_util,
        (SELECT GenPersona.Nombre FROM GenPersona WHERE GenPersona.Id = (SELECT ComProveedor.GenPersonaId FROM ComProveedor WHERE ComProveedor.Id = (SELECT ComFactura.ComProveedorId FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId))) AS proveedor,
        (SELECT ComFactura.NumeroFactura FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS numero_factura,
        (SELECT VenVenta.Auditoria_FechaCreacion FROM VenVenta WHERE VenVenta.Id = (SELECT TOP 1 VenVentaDetalle.VenVentaId FROM VenVentaDetalle WHERE VenVentaDetalle.InvArticuloId = ComFacturaDetalle.InvArticuloId ORDER BY VenVentaDetalle.VenVentaId DESC)) AS ultima_venta
      FROM
        ComFacturaDetalle
      WHERE
        ComFacturaDetalle.ComFacturaId IN (SELECT ComFactura.Id FROM ComFactura WHERE ComFactura.FechaRegistro BETWEEN '$FInicial' AND '$FFinal') AND
        DATEDIFF(DAY, (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId), ComFacturaDetalle.FechaVencimiento) <= '$VUtil'
      ORDER BY (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) ASC;
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R31Q_Codificaciones
    FUNCION: Query que genera los detalles de los articulos codificados segun rango de fecha
    RETORNO: Detalle del articulo
    DESAROLLADO POR: NISA DELGADO
  */
  function R31Q_Codificaciones($fechaInicio, $fechaFin) {
    $sql = "
        SELECT
            InvArticulo.Id AS id_articulo,
            InvArticulo.CodigoArticulo AS codigo_interno,
            (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id And InvCodigoBarra.EsPrincipal = 1) AS codigo_barra,
            InvArticulo.DescripcionLarga AS descripcion,
            (CONVERT(INT, (SELECT SUM(InvLoteAlmacen.Existencia) FROM InvLoteAlmacen WHERE InvLoteAlmacen.InvAlmacenId IN (1, 2) AND InvLoteAlmacen.InvArticuloId = InvArticulo.Id))) AS existencia,
            (SELECT InvMarca.Nombre FROM InvMarca WHERE InvMarca.Id = InvArticulo.InvMarcaId) AS marca,
            (ISNULL((SELECT InvArticuloAtributo.InvArticuloId FROM InvArticuloAtributo WHERE InvArticuloAtributo.InvAtributoId = (SELECT InvAtributo.Id FROM InvAtributo WHERE InvAtributo.Descripcion = 'Medicina') AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS tipo,
            (SELECT TOP 1 GenPersona.Nombre FROM ComFacturaDetalle INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id ORDER BY ComFactura.FechaDocumento DESC) AS  ultimo_proveedor,
            (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As existencia FROM InvLoteAlmacen WHERE(InvLoteAlmacen.InvAlmacenId = 1) AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS existencia_almacen_1,
            (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As existencia FROM InvLoteAlmacen WHERE(InvLoteAlmacen.InvAlmacenId = 2) AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS existencia_almacen_2,
            (ISNULL((SELECT InvArticuloAtributo.InvArticuloId FROM InvArticuloAtributo WHERE InvArticuloAtributo.InvAtributoId = (SELECT InvAtributo.Id FROM InvAtributo WHERE InvAtributo.Descripcion = 'Troquelados' OR  InvAtributo.Descripcion = 'troquelados') AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS troquelado,
            ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad FROM VenCondicionVenta WHERE VenCondicionVenta.Id = (SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id FROM VenCondicionVenta_VenCondicionVentaArticulo WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS utilidad_articulo,
            ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad FROM VenCondicionVenta WHERE VenCondicionVenta.id = (SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id FROM VenCondicionVenta_VenCondicionVentaCategoria WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS utilidad_categoria,
            (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioTroquelado FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE(InvLoteAlmacen.InvAlmacenId = '1') AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS troquel_almacen_1,
            (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioCompraBruto FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) AND (InvLoteAlmacen.InvAlmacenId = '1') ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS precio_compra_bruto_almacen_1,
            (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioTroquelado FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE(InvLoteAlmacen.InvAlmacenId = '2') AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS troquel_almacen_2,
            (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioCompraBruto FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) AND (InvLoteAlmacen.InvAlmacenId = '2') ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS precio_compra_bruto_almacen_2,
            (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioCompraBruto FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS precio_compra_bruto,
            (ISNULL(InvArticulo.FinConceptoImptoIdCompra,CAST(0 AS INT))) AS iva
        FROM
            InvArticulo
        WHERE
            InvArticulo.Auditoria_FechaCreacion >= '$fechaInicio' AND InvArticulo.Auditoria_FechaCreacion <= '$fechaFin'
        ORDER BY InvArticulo.Id DESC;
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R31Q_Atributos_Adicionales
    FUNCION: Query que genera el listado de atributos adicionales de un producto dado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: NISA DELGADO
  */
  function R31Q_Atributos_Adicionales($id_articulo)
  {
    $sql = "
        SELECT
            InvAtributo.Descripcion
        FROM
            InvAtributo
        WHERE
            InvAtributo.Id IN ((SELECT InvArticuloAtributo.InvAtributoId FROM InvArticuloAtributo WHERE InvArticuloAtributo.InvArticuloId = '$id_articulo')) AND
            (InvAtributo.Descripcion != 'giordany' AND InvAtributo.Descripcion != 'Medicina' AND InvAtributo.Descripcion != 'Dolarizados');
    ";

    return $sql;
  }
?>
