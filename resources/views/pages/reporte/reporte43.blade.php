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
    Reporte de traslados
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

    R43_Traslados($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Reporte de traslados');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: <span id="tiempo_carga">'.$IntervalCarga->format("%Y-%M-%D %H:%I:%S") . '</span>';
  }
  else{
    echo '
    <form autocomplete="off" action="" target="_blank">
        <table style="width:100%;">
          <tr>
            <td>
              Fecha Inicio:
            </td>
            <td>
              <input id="fechaInicio" type="date" name="fechaInicio" required style="width:100%;">
            </td>

            <td>&nbsp;</td>

            <td>
              Fecha Fin:
            </td>
            <td>
              <input id="fechaFin" name="fechaFin" type="date" required style="width:100%;">
            </td>

            <td>&nbsp;</td>

            <td>
              <input type="submit" value="Buscar" class="btn btn-outline-success">
            </td>
          </tr>

          <input id="SEDE" name="SEDE" type="hidden" value="';
            print_r($_GET['SEDE']);
            echo'">
        </table>
      </form>
    ';
  }
?>
@endsection

<?php
  /*********************************************************************************/
  /*
    TITULO: R43_Traslados
    FUNCION: Arma una lista de traslados segun rango de fecha
    RETORNO: No aplica
    DESAROLLADO POR: NISA DELGADO
  */
  function R43_Traslados($SedeConnection,$FInicial,$FFinal){
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $FInicialImp = date("d/m/Y", strtotime($FInicial));
    $FFinalImp= date("d/m/Y", strtotime($FFinal));

    $estado = (isset($_GET['seccion'])) ? $_GET['seccion'] : 'PROCESADO';

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
    echo'<h6 align="center">Periodo desde el <b>'.$FInicialImp.'</b> al <b>'.$FFinalImp.'</b> con estado <b>'.$estado.'</b></h6>';


    echo '
      <table width="100%">
      <tbody>
        <tr>
          <td align="center">
              <a data-seccion="PROCESADO" href="#" class="secciones btn btn-outline-primary btn-sm btn-block">PROCESADO</a>
          </td>

          <td>&nbsp;</td><td>&nbsp;</td>

          <td align="center">
            <a data-seccion="EMBALADO" href="#" class="secciones btn btn-outline-danger btn-sm btn-block">EMBALADO</a>
          </td>

          <td>&nbsp;</td><td>&nbsp;</td>

          <td align="center">
            <a data-seccion="ENTREGADO" href="#" class="secciones btn btn-outline-warning btn-sm btn-block">ENTREGADO</a>
          </td>
        </tr>
      </tbody>
    </table>
    ';

    echo'
    <hr>
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
        <tr>
          <th scope="col" class="CP-sticky">#</th>
          <th scope="col" class="CP-sticky">Código de barra</th>
          <th scope="col" class="CP-sticky">Código interno</th>
          <th scope="col" class="CP-sticky">Descripción</th>
          <th scope="col" class="CP-sticky">Cantidad</th>
          <th scope="col" class="CP-sticky">Sede destino</th>
          <th scope="col" class="CP-sticky">Numero de traslado</th>
        </tr>
      </thead>
      <tbody>';

    $fechaInicio = $_GET['fechaInicio'];
    $fechaFin = $_GET['fechaFin'];

    $traslados = compras\TrasladoDetalle::whereDate('created_at', '>=', $fechaInicio)
      ->whereDate('created_at', '<=', $fechaFin)
      ->whereHas('traslado', function ($query) use ($estado) {
        $query->where('estatus', $estado);
      })
      ->get();


    $contador = 1;

    foreach ($traslados as $traslado) {
      echo '<tr>';
      echo '<td class="text-center">'.$contador.'</td>';
      echo '<td class="text-center">'.$traslado->codigo_barra.'</td>';
      echo '<td class="text-center">'.$traslado->codigo_interno.'</td>';
      echo '<td class="text-center">'.$traslado->descripcion.'</td>';
      echo '<td class="text-center">'.$traslado->cantidad.'</td>';
      echo '<td class="text-center">'.$traslado->traslado->sede_destino.'</td>';
      echo '<td class="text-center">'.$traslado->traslado->numero_ajuste.'</td>';
      echo '</tr>';

      $contador++;
    }

    echo '
      </tbody>
    </table>';
  }
?>
