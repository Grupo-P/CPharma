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
  .barrido{
    text-decoration: none;
    transition: width 1s, height 1s, transform 1s;
  }
  .barrido:hover{
    text-decoration: none;
    transition: width 1s, height 1s, transform 1s;
    transform: translate(20px,0px);
  }
  .alerta{
    color: red;
    text-transform: uppercase;
  }
  </style>
@endsection




@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Traslados por llegar
  </h1>
  <hr class="row align-items-start col-12">

<?php
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  $InicioCarga = new DateTime("now");

  if (isset($_GET['SEDE'])){
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
  }
  echo '<hr class="row align-items-start col-12">';

  R44_Traslados_Por_Llegar($_GET['SEDE']);
  FG_Guardar_Auditoria('CONSULTAR','REPORTE','Traslados por llegar');

  $FinCarga = new DateTime("now");
  $IntervalCarga = $InicioCarga->diff($FinCarga);
  echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
?>
@endsection

<?php
  /**********************************************************************************/
  /*
    TITULO: ReporteActivacionProveedores
    FUNCION: Armar el reporte de activacion de proveedores
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R44_Traslados_Por_Llegar($SedeConnection) {

    $FFinal = date("Y-m-d");
    $FInicial = date("Y-m-d",strtotime($FFinal."-10 days"));

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

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

    $token = isset($_GET['_token']) ? $_GET['_token'] : '';

    echo '
    <table class="table table-striped table-borderless col-12 sortable">
      <thead class="thead-dark">
        <tr>
            <th scope="col" colspan="5" style="text-align: center;">CLASIFICACION</th>
        </tr>
      </thead>
      <tbody>
        <tr>
        <td style="width:20%;" align="center">
          <form action="?Tipo=TODO" method="GET" style="display: inline;">
            <input type="hidden" name="SEDE" value="'.$_GET['SEDE'].'">
            <input type="hidden" name="_token" value="'.$token.'">
            <button type="submit" name="Tipo" role="button" class="btn btn-outline-success btn-sm" value="3">TODO</button>
          </form>
        </td>

        <td style="width:20%;" align="center">
          <form action="" method="GET" style="display: inline;">
            <input type="hidden" name="estado" value="PROCESADO">
            <input type="hidden" name="SEDE" value="'.$_GET['SEDE'].'">
            <input type="hidden" name="_token" value="'.$token.'">
            <button type="submit" role="button" class="btn btn-outline-dark btn-sm" value="0">PROCESADO</button>
          </form>
        </td>

        <td style="width:20%;" align="center">
          <form action="" method="GET" style="display: inline;">
            <input type="hidden" name="estado" value="EMBALADO">
            <input type="hidden" name="_token" value="'.$token.'">
            <input type="hidden" name="SEDE" value="'.$_GET['SEDE'].'">
            <button type="submit" role="button" class="btn btn-outline-danger btn-sm" value="1">EMBALADO</button>
            </form>
        </td>

        <td style="width:20%;" align="center">
          <form action="" method="GET" style="display: inline;">
            <input type="hidden" name="estado" value="ENTREGADO">
            <input type="hidden" name="_token" value="'.$token.'">
            <input type="hidden" name="SEDE" value="'.$_GET['SEDE'].'">
            <button type="submit" role="button" class="btn btn-outline-info btn-sm" value="2">ENTREGADO</button>
          </form>
        </td>
        </tr>
      </tbody>
    </table>
    ';

    echo '
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Código barra</th>
            <th scope="col" class="CP-sticky">Código interno</th>
            <th scope="col" class="CP-sticky">Descripción</th>
            <th scope="col" class="CP-sticky">Existencia actual</th>
            <th scope="col" class="CP-sticky">Sede origen</th>
            <th scope="col" class="CP-sticky">Descripción origen</th>
            <th scope="col" class="CP-sticky">Cantidad origen</th>
            <th scope="col" class="CP-sticky">Número traslado origen</th>
            <th scope="col" class="CP-sticky">Fecha traslado</th>
            <th scope="col" class="CP-sticky">Días desde traslado</th>
            <th scope="col" class="CP-sticky">Estado</th>
          </tr>
        </thead>
        <tbody>
      ';

    $estado = isset($_GET['estado']) ? $_GET['estado'] : 'PROCESADO';
    $traslados = [];

    if (FG_Mi_Ubicacion() == 'FAU' || FG_Mi_Ubicacion() == 'DBs') {

      try {
        $trasladoFM = DB::connection('fm')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA AVENIDA UNIVERSIDAD, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFM as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FSM';
      }

      try {
        $trasladoFLL = DB::connection('fll')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA AVENIDA UNIVERSIDAD, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFLL as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FLL';
      }

      try {
        $trasladoFTN = DB::connection('ftn')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA AVENIDA UNIVERSIDAD, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFTN as $traslado) {
          $traslados[] = $traslado;
        }
      }catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FTN';
      }

      try {
        $trasladoFEC = DB::connection('fec')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA AVENIDA UNIVERSIDAD, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FEC';
      }

      try {
        $trasladoFEC = DB::connection('flf')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA AVENIDA UNIVERSIDAD, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FLF';
      }

      try {
        $trasladoFEC = DB::connection('cdd')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'CENTRO DE DISTRIBUCION GP' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a CDD';
      }
    }

    if (FG_Mi_Ubicacion() == 'FTN') {
      try {
        $trasladoFM = DB::connection('fm')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA TIERRA NEGRA, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFM as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FSM';
      }

      try {
        $trasladoFLL = DB::connection('fll')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA TIERRA NEGRA, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFLL as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FLL';
      }

      try {
        $trasladoFAU = DB::connection('fau')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA TIERRA NEGRA, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFAU as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FAU';
      }

      try {
        $trasladoFEC = DB::connection('fec')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA TIERRA NEGRA, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FEC';
      }

      try {
        $trasladoFEC = DB::connection('flf')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA TIERRA NEGRA, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FLF';
      }

      try {
        $trasladoFEC = DB::connection('cdd')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'CENTRO DE DISTRIBUCION GP' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a CDD';
      }
    }

    if (FG_Mi_Ubicacion() == 'FLL') {

        try {
            $trasladoFM = DB::connection('fm')->select("
                SELECT
                    traslados_detalle.codigo_barra AS codigo_barra,
                    traslados_detalle.codigo_interno AS codigo_interno,
                    (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
                    traslados_detalle.descripcion AS descripcion,
                    traslados_detalle.cantidad AS cantidad,
                    (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
                    (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
                    (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
                FROM
                    traslados_detalle
                WHERE
                    (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA LAGO,C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
                ORDER BY
                    traslados_detalle.id DESC;
            ");

            foreach ($trasladoFM as $traslado) {
                $traslados[] = $traslado;
            }
        } catch (Exception $excepcion) {
            $errores[] = 'No se pudo conectar a FSM';
        }

        try {
            $trasladoFTN = DB::connection('ftn')->select("
                SELECT
                    traslados_detalle.codigo_barra AS codigo_barra,
                    traslados_detalle.codigo_interno AS codigo_interno,
                    (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
                    traslados_detalle.descripcion AS descripcion,
                    traslados_detalle.cantidad AS cantidad,
                    (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
                    (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
                    (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
                FROM
                    traslados_detalle
                WHERE
                    (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA LAGO,C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
                ORDER BY
                    traslados_detalle.id DESC;
            ");

            foreach ($trasladoFTN as $traslado) {
                $traslados[] = $traslado;
            }
        } catch (Exception $excepcion) {
            $errores[] = 'No se pudo conectar a FTN';
        }

        try {
            $trasladoFAU = DB::connection('fau')->select("
                SELECT
                    traslados_detalle.codigo_barra AS codigo_barra,
                    traslados_detalle.codigo_interno AS codigo_interno,
                    (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
                    traslados_detalle.descripcion AS descripcion,
                    traslados_detalle.cantidad AS cantidad,
                    (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
                    (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
                    (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
                FROM
                    traslados_detalle
                WHERE
                    (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA LAGO,C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
                ORDER BY
                    traslados_detalle.id DESC;
            ");

            foreach ($trasladoFAU as $traslado) {
                $traslados[] = $traslado;
            }
        } catch (Exception $excepcion) {
            $errores[] = 'No se pudo conectar a FAU';
        }

      try {
        $trasladoFEC = DB::connection('fec')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA LAGO,C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FEC';
      }

      try {
        $trasladoFEC = DB::connection('flf')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA LAGO,C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FLF';
      }

      try {
        $trasladoFEC = DB::connection('cdd')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'CENTRO DE DISTRIBUCION GP' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a CDD';
      }
    }

    if (FG_Mi_Ubicacion() == 'FSM') {
      try {
        $trasladoFLL = DB::connection('fll')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA MILLENNIUM 2000, C.A' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFLL as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FLL';
      }

      try {
        $trasladoFTN = DB::connection('ftn')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA MILLENNIUM 2000, C.A' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFTN as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FTN';
      }

      try {
        $trasladoFAU = DB::connection('fau')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA MILLENNIUM 2000, C.A' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFAU as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FAU';
      }

      try {
        $trasladoFEC = DB::connection('fec')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA MILLENNIUM 2000, C.A' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FEC';
      }

      try {
        $trasladoFEC = DB::connection('flf')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA MILLENNIUM 2000, C.A' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FLF';
      }

      try {
        $trasladoFEC = DB::connection('cdd')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'CENTRO DE DISTRIBUCION GP' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a CDD';
      }
    }

    if (FG_Mi_Ubicacion() == 'FEC') {

      try {
          $trasladoFM = DB::connection('fm')->select("
              SELECT
                  traslados_detalle.codigo_barra AS codigo_barra,
                  traslados_detalle.codigo_interno AS codigo_interno,
                  (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
                  traslados_detalle.descripcion AS descripcion,
                  traslados_detalle.cantidad AS cantidad,
                  (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
                  (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
                  (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
              FROM
                  traslados_detalle
              WHERE
                  (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA EL CALLEJON, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
              ORDER BY
                  traslados_detalle.id DESC;
          ");

          foreach ($trasladoFM as $traslado) {
              $traslados[] = $traslado;
          }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FSM';
      }

      try {
        $trasladoFLL = DB::connection('fll')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA EL CALLEJON, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFLL as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FLL';
      }

      try {
        $trasladoFTN = DB::connection('ftn')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA EL CALLEJON, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFTN as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FTN';
      }

      try {
        $trasladoFAU = DB::connection('fau')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA EL CALLEJON, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFAU as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FAU';
      }

      try {
        $trasladoFEC = DB::connection('flf')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA EL CALLEJON, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FLF';
      }

      try {
        $trasladoFEC = DB::connection('cdd')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'CENTRO DE DISTRIBUCION GP' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a CDD';
      }
    }

    if (FG_Mi_Ubicacion() == 'FLF') {
      try {
          $trasladoFM = DB::connection('fm')->select("
              SELECT
                  traslados_detalle.codigo_barra AS codigo_barra,
                  traslados_detalle.codigo_interno AS codigo_interno,
                  (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
                  traslados_detalle.descripcion AS descripcion,
                  traslados_detalle.cantidad AS cantidad,
                  (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
                  (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
                  (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
              FROM
                  traslados_detalle
              WHERE
                  (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA FUSTA' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
              ORDER BY
                  traslados_detalle.id DESC;
          ");

          foreach ($trasladoFM as $traslado) {
              $traslados[] = $traslado;
          }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FSM';
      }

      try {
        $trasladoFLL = DB::connection('fll')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA FUSTA' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFLL as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FLL';
      }

      try {
        $trasladoFTN = DB::connection('ftn')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA FUSTA' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFTN as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FTN';
      }

      try {
        $trasladoFAU = DB::connection('fau')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA FUSTA' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFAU as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FAU';
      }

      try {
        $trasladoFEC = DB::connection('fec')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA FUSTA' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FEC';
      }

      try {
        $trasladoFEC = DB::connection('cdd')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'CENTRO DE DISTRIBUCION GP' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a CDD';
      }
    }

    if (FG_Mi_Ubicacion() == 'CDD') {
      try {
          $trasladoFM = DB::connection('fm')->select("
              SELECT
                  traslados_detalle.codigo_barra AS codigo_barra,
                  traslados_detalle.codigo_interno AS codigo_interno,
                  (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
                  traslados_detalle.descripcion AS descripcion,
                  traslados_detalle.cantidad AS cantidad,
                  (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
                  (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
                  (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
              FROM
                  traslados_detalle
              WHERE
                  (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA FUSTA' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
              ORDER BY
                  traslados_detalle.id DESC;
          ");

          foreach ($trasladoFM as $traslado) {
              $traslados[] = $traslado;
          }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FSM';
      }

      try {
        $trasladoFLL = DB::connection('fll')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA FUSTA' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFLL as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FLL';
      }

      try {
        $trasladoFTN = DB::connection('ftn')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA FUSTA' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFTN as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FTN';
      }

      try {
        $trasladoFAU = DB::connection('fau')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA FUSTA' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFAU as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FAU';
      }

      try {
        $trasladoFEC = DB::connection('fec')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA LA FUSTA' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FEC';
      }

      try {
        $trasladoFEC = DB::connection('flf')->select("
          SELECT
              traslados_detalle.codigo_barra AS codigo_barra,
              traslados_detalle.codigo_interno AS codigo_interno,
              (SELECT traslados.sede_emisora FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS sede_emisora,
              traslados_detalle.descripcion AS descripcion,
              traslados_detalle.cantidad AS cantidad,
              (SELECT traslados.numero_ajuste FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS numero_traslado,
              (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS estado,
              (SELECT traslados.fecha_traslado FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) AS fecha_traslado
          FROM
              traslados_detalle
          WHERE
              (SELECT traslados.sede_destino FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = 'FARMACIA EL CALLEJON, C.A.' AND (SELECT traslados.estatus FROM traslados WHERE traslados.numero_ajuste = traslados_detalle.id_traslado) = '$estado'
          ORDER BY
              traslados_detalle.id DESC;
        ");

        foreach ($trasladoFEC as $traslado) {
          $traslados[] = $traslado;
        }
      } catch (Exception $excepcion) {
        $errores[] = 'No se pudo conectar a FLF';
      }
    }

    $contador = 1;

    if (isset($errores)) {
      echo '<div class="alert alert-warning text-center">';
      foreach ($errores as $error) {
        echo $error.'<br>';
      }
      echo '</div>';
    }

    foreach ($traslados as $traslado) {
      $codigo_barra = $traslado->codigo_barra;
      $codigo_interno = $traslado->codigo_interno;
      $sede = $traslado->sede_emisora;
      $descripcionOrigen = $traslado->descripcion;
      $cantidad = $traslado->cantidad;
      $numero_traslado = $traslado->numero_traslado;
      $estado = $traslado->estado;
      $fecha_traslado = $traslado->fecha_traslado;

      $conn = FG_Conectar_Smartpharma(FG_Mi_Ubicacion());

      $sql = "
        SELECT
          InvArticulo.DescripcionLarga AS descripcion,
          (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) FROM InvLoteAlmacen WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38, 0)), 2, 0))  AS existencia
        FROM
          InvArticulo
        WHERE
          InvArticulo.Id = (SELECT InvCodigoBarra.InvArticuloId FROM InvCodigoBarra WHERE InvCodigoBarra.CodigoBarra = '$codigo_barra')
      ";

      $query = sqlsrv_query($conn, $sql);

      $row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);

      $descripcion = ($row['descripcion']) ? FG_Limpiar_Texto($row['descripcion']) : '-';
      $existencia = ($row['existencia']) ? $row['existencia'] : '-';

      $dias = Carbon\Carbon::parse($fecha_traslado)->diffInDays();

      echo '<tr>';
      echo '<td class="text-center">'.$contador.'</td>';
      echo '<td class="text-center">'.$codigo_barra.'</td>';
      echo '<td class="text-center">'.$codigo_interno.'</td>';
      echo '<td class="text-center">'.$descripcion.'</td>';
      echo '<td class="text-center">'.$existencia.'</td>';
      echo '<td class="text-center">'.$sede.'</td>';
      echo '<td class="text-center">'.$descripcionOrigen.'</td>';
      echo '<td class="text-center">'.$cantidad.'</td>';
      echo '<td class="text-center">'.$numero_traslado.'</td>';
      echo '<td class="text-center">'.$fecha_traslado.'</td>';
      echo '<td class="text-center">'.$dias.'</td>';
      echo '<td class="text-center">'.$estado.'</td>';
      echo '</tr>';

      $contador++;
    }

    echo '
        </tbody>
    </table>';
  }
?>
