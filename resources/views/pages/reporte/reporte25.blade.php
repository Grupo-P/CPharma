@extends('layouts.model')

@section('title')
  Articulos en Cero
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
        Articulos en Cero
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

    R25_Articulos_Cero($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Articulos en Cero');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
    }
    else{
        echo '
        <form autocomplete="off" action="">
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

@section('scriptsFoot')
<script>
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();
  });
</script>
@endsection

<?php
  /**********************************************************************************/
  /*
    TITULO: R25_Articulos_Cero
    FUNCION: armar el reporte de articulos en cero
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R25_Articulos_Cero($SedeConnection,$FInicio,$FFin){

    $conn = FG_Conectar_Mod_Atte_Clientes($SedeConnection);

    $FInicialImp = date("d-m-Y", strtotime($FInicio));
    $FFinalImp= date("d-m-Y", strtotime($FFin));

    //$FFin = date("Y-m-d",strtotime($FFin."+ 1 days"));

    $sql = R24Q_Articulos_Cero_Agrupados($FInicio,$FFin);
    $result = sqlsrv_query($conn,$sql);

    $sql1 = R24Q_Articulos_Cero_Detallados($FInicio,$FFin);
    $result1 = sqlsrv_query($conn,$sql1);

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
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Componente/Uso</th>
            <th scope="col" class="CP-sticky">Repeticiones</th>
            <th scope="col" class="CP-sticky">Tipo</th>
          </tr>
        </thead>
        <tbody>
    ';

    $contador = 1;
    while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td>'.FG_Limpiar_Texto($row["componente"]).'</td>';
      echo '<td align="center"><strong>'.intval($row["repeticiones"]).'</strong></td>';
      echo '<td align="center">'.$row["tipo"].'</td>';
      echo '</tr>';
      $contador++;
    }
    echo '
      </tbody>
    </table>';

    echo("</br></br>");

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Componente/Uso</th>
            <th scope="col" class="CP-sticky">Fecha</th>
            <th scope="col" class="CP-sticky">Hora</th>
            <th scope="col" class="CP-sticky">Tipo</th>
            <th scope="col" class="CP-sticky">Estacion</th>
          </tr>
        </thead>
        <tbody>
    ';

    $contador = 1;
    while($row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC)) {

      $fecha = $row1["fecha"];
      $hora = $row1["hora"];

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td>'.FG_Limpiar_Texto($row1["componente"]).'</td>';
      echo '<td align="center"><strong>'.$fecha->format("d-m-Y").'</strong></td>';
      echo '<td align="center"><strong>'.$hora->format("h:i:s a").'</strong></td>';
      echo '<td align="center">'.$row1["tipo"].'</td>';
      echo '<td>'.FG_Limpiar_Texto($row1["estacion"]).'</td>';
      echo '</tr>';
      $contador++;
    }
    echo '
      </tbody>
    </table>';
    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R24Q_Articulos_Cero_Agrupados
    FUNCION: Armar una lista de articulos en cero agrupados
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R24Q_Articulos_Cero_Agrupados($FInicio,$FFin) {
    if ($FInicio == $FFin) {
        $where = "CONVERT(DATE, fecha) = '$FInicio'";
    }
    else {
        $where = "CONVERT(DATE, fecha) > '$FInicio' AND CONVERT(DATE, fecha) < '$FFin'";
    }

    $sql = "
      SELECT
      compo as componente,
      COUNT(*) as repeticiones,
      cat as tipo
      FROM faltantes
      WHERE
      $where
      GROUP BY faltantes.compo, faltantes.cat
      ORDER BY repeticiones DESC
    ";

    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R24Q_Articulos_Cero_Agrupados
    FUNCION: Armar una lista de articulos en cero agrupados
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R24Q_Articulos_Cero_Detallados($FInicio,$FFin) {
    if ($FInicio == $FFin) {
        $where = "CONVERT(DATE, fecha) = '$FInicio'";
    }
    else {
        $where = "CONVERT(DATE, fecha) > '$FInicio' AND CONVERT(DATE, fecha) < '$FFin'";
    }

    $sql = "
      SELECT
      compo as componente,
      CONVERT(DATE, fecha) as fecha,
      CONVERT(TIME, fecha) as hora,
      estacion,
      cat as tipo
      FROM faltantes
      WHERE
        $where
      ORDER BY hora ASC
    ";

    return $sql;
  }
?>
