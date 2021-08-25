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

    @page {size: landscape}

    @media print{
      .saltoDePagina{
        display:block;
        page-break-before:always;
      }
    </style>
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Ajustes de inventarios
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

  if (isset($_GET['numeroAjuste'])){

    $InicioCarga = new DateTime("now");

    R42_Ajuste_Detalle($_GET['SEDE'],$_GET['numeroAjuste']);

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else{

    $InicioCarga = new DateTime("now");

    $cantidad = isset($_GET['cantidad']) ? $_GET['cantidad'] : '';
    $fechaInicioUrl = isset($_GET['fechaInicio']) ? $_GET['fechaInicio'] : '';
    $fechaFinUrl = isset($_GET['fechaFin']) ? $_GET['fechaFin'] : '';

    $selected50 = ($cantidad == '50') ? 'selected' : '';
    $selected100 = ($cantidad == '100') ? 'selected' : '';
    $selected200 = ($cantidad == '200') ? 'selected' : '';
    $selected500 = ($cantidad == '500') ? 'selected' : '';
    $selected1000 = ($cantidad == '1000') ? 'selected' : '';
    $selectedTodos = ($cantidad == 'Todos') ? 'selected' : '';

    echo '
    <form autocomplete="off" action="">
        <div class="col"><input type="hidden" name="SEDE" value="'.$_GET['SEDE'].'"></div>

        <div class="row">
            <div class="col">Cantidad de registros</div>
            <div class="col">
                <select class="form-control form-control-sm" name="cantidad">
                    <option '.$selected50.' value="50">50</option>
                    <option '.$selected100.' value="100">100</option>
                    <option '.$selected200.' value="200">200</option>
                    <option '.$selected500.' value="500">500</option>
                    <option '.$selected1000.' value="1000">1000</option>
                    <option '.$selectedTodos.' value="Todos">Todos</option>
                </select>
            </div>

            <div class="col">Fecha inicio</div>
            <div class="col"><input type="date" value="'.$fechaInicioUrl.'" class="form-control form-control-sm" name="fechaInicio"></div>

            <div class="col">Fecha final</div>
            <div class="col"><input type="date" value="'.$fechaFinUrl.'" class="form-control form-control-sm" name="fechaFin"></div>

            <div class="col"><input type="submit" value="Buscar" class="btn btn-sm btn-block btn-outline-success"></div>
        </div>
    </form>
    <br>';

    R42_Ajuste_Inventarios_Top50($_GET['SEDE'],$cantidad,$fechaInicioUrl,$fechaFinUrl);

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
?>
@endsection


<?php
  function R42_Ajuste_Inventarios_Top50($SedeConnection,$cantidad,$inicio,$fin){

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $sql1 = R30Q_Ajustes_Top50($cantidad,$inicio,$fin);
    $result = sqlsrv_query($conn,$sql1);

    $connCPharma = FG_Conectar_CPharma();

    echo '
    <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1">
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myFilter" onkeyup="FilterAllTableConflicto()">
    </div>
    <br/>
    ';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Fecha</th>
            <th scope="col" class="CP-sticky">Hora</th>
            <th scope="col" class="CP-sticky">Numero ajuste</th>
            <th scope="col" class="CP-sticky">Estado</th>
            <th scope="col" class="CP-sticky">Comentario</th>
            <th scope="col" class="CP-sticky">SKU +</th>
            <th scope="col" class="CP-sticky">Unidades +</th>
            <th scope="col" class="CP-sticky">SKU -</th>
            <th scope="col" class="CP-sticky">Unidades -</th>
            <th scope="col" class="CP-sticky">Monto Bs</th>
            <th scope="col" class="CP-sticky">Tasa</th>
            <th scope="col" class="CP-sticky">Monto $</th>
            <th scope="col" class="CP-sticky">Operador</th>
            <th scope="col" class="CP-sticky"></th>
          </tr>
        </thead>
        <tbody>
     ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

      $tasa = FG_Tasa_Fecha_Venta($connCPharma, $row['fecha']->format('Y-m-d'));

      if ($tasa) {
        $monto_ds = number_format($row['monto'] / $tasa, 2, ',', '.');
        $tasa = number_format($tasa, 2, ',', '.');
      } else {
        $tasa = '';
        $monto_ds = '';
      }

      echo '<tr>';
      echo '<td align="left"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="left">'.$row['fecha']->format('d-m-Y').'</td>';
      echo '<td align="center">'.$row['fecha']->format('h:i A').'</td>';
      echo '<td align="center">'.$row['numero_ajuste'].'</td>';
      echo '<td align="center">'.$row["estado"].'</td>';
      echo '<td align="center">'.$row["comentario"].'</td>';
      echo '<td align="center">'.$row["skup"].'</td>';
      echo '<td align="center">'.intval($row["unidadesp"]).'</td>';
      echo '<td align="center">'.$row["skun"].'</td>';
      echo '<td align="center">'.intval($row["unidadesn"]).'</td>';
      echo '<td align="center">'.number_format($row["monto"], 2, ',', '.').'</td>';
      echo '<td align="center">'.$tasa.'</td>';
      echo '<td align="center">'.$monto_ds.'</td>';
      echo '<td align="center">'.$row["operador"].'</td>';
      echo '
      <td align="center">
        <form autocomplete="off" action="">
            <input id="SEDE" name="SEDE" type="hidden" value="';
                print_r($SedeConnection);
                echo'">

              <input type="submit" value="Ver detalles" class="btn btn-outline-success">

              <input id="numeroAjuste" name="numeroAjuste" type="hidden" value="'.$row['numero_ajuste'].'">
          </form>
          <br>
        ';
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
    TITULO: R42_Ajuste_Detalle
    FUNCION:  Detalle de ajuste
    RETORNO: no aplica
    DESAROLLADO POR: NISA DELGADO
  */
  function R42_Ajuste_Detalle($SedeConnection,$numeroAjuste){

    $conn = FG_Conectar_Smartpharma($SedeConnection);

    $sql1 = R42Q_Ajuste_Detalle($numeroAjuste);
    $result1 = sqlsrv_query($conn,$sql1);

    $sql2 = R42Q_Ajuste_Detalle_Listado($numeroAjuste);
    $result2 = sqlsrv_query($conn,$sql2);

    echo '
    <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1">
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
    </div>
    <br/>
    ';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">Fecha</th>
            <th scope="col" class="CP-sticky">Hora</th>
            <th scope="col" class="CP-sticky">Número ajuste</th>
            <th scope="col" class="CP-sticky">Monto</th>
            <th scope="col" class="CP-sticky">Operador</th>
          </tr>
        </thead>
        <tbody>
     ';

     $row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);

      echo '<tr>';
      echo '<td align="center">'.$row1['fecha']->format('d-m-Y').'</td>';
      echo '<td align="center">'.$row1['fecha']->format('h:i A').'</td>';
      echo '<td align="center">'.$row1['numero_ajuste'].'</td>';
      echo '<td align="center">'.number_format($row1['monto'], 2, ',', '.').'</td>';
      echo '<td align="center">'.$row1['operador'].'</td>';
      echo '</tr>';

      echo '
        </tbody>
    </table>';


    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Codigo</th>
            <th scope="col" class="CP-sticky">Codigo de barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Cantidad</th>
            <th scope="col" class="CP-sticky">Tipo</th>
            <th scope="col" class="CP-sticky">Costo unitario Bs.</th>
            <th scope="col" class="CP-sticky">Costo total Bs.</th>
            <th scope="col" class="CP-sticky">Almacen</th>
            <th scope="col" class="CP-sticky">Lote</th>
          </tr>
        </thead>
        <tbody>
     ';

     $contador = 1;

     while ($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
      echo '<tr>';
      echo '<td align="center">'.$contador.'</td>';
      echo '<td align="center">'.$row2['codigo'].'</td>';
      echo '<td align="center">'.$row2['codigo_barra'].'</td>';
      echo '<td align="center">'.$row2['descripcion'].'</td>';
      echo '<td align="center">'.$row2['cantidad'].'</td>';
      echo '<td align="center">'.$row2['tipo'].'</td>';
      echo '<td align="center">'.$row2['costo_unitario_bs'].'</td>';
      echo '<td align="center">'.$row2['costo_total_bs'].'</td>';
      echo '<td align="center">'.$row2['almacen'].'</td>';
      echo '<td align="center">'.$row2['lote'].'</td>';
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
    TITULO: R30Q_Ajustes_Top50
    FUNCION: Busca los ultimos 50 ajustes
    RETORNO: lista de facturas
    DESAROLLADO POR: NISA DELGADO
  */
  function R30Q_Ajustes_Top50($cantidad,$inicio,$fin) {

    if ($cantidad == '') {
        $cantidad = "TOP 50";
    }

    if ($cantidad == 'Todos') {
        $cantidad = '';
    }

    if (is_numeric($cantidad)) {
        $cantidad = "TOP $cantidad";
    }

    if ($inicio != '') {
        $where = " WHERE CONVERT(DATE,InvAjuste.Auditoria_FechaCreacion) >= '$inicio' ";
    }

    if ($fin != '') {
        $where = " WHERE CONVERT(DATE,InvAjuste.Auditoria_FechaCreacion) <= '$fin' ";
    }

    if ($inicio != '' && $fin != '') {
        $where = " WHERE CONVERT(DATE,InvAjuste.Auditoria_FechaCreacion) >= '$inicio' AND CONVERT(DATE,InvAjuste.Auditoria_FechaCreacion) <= '$fin' ";
    }

    if ($inicio == '' && $fin == '') {
        $where = "";
    }

    $sql = "
      SELECT $cantidad
        InvAjuste.Auditoria_FechaCreacion AS fecha,
        InvAjuste.NumeroAjuste AS numero_ajuste,
        (CASE InvAjuste.estado WHEN 2 THEN 'Procesado' ELSE 'Pendiente' END) AS estado,
        InvAjuste.Comentario AS comentario,
        (SELECT COUNT(1) FROM InvAjusteDetalle WHERE InvAjusteDetalle.InvAjusteId = InvAjuste.Id AND InvAjusteDetalle.InvCausaId IN (SELECT InvCausa.Id FROM InvCausa WHERE InvCausa.EsPositiva = 1)) AS skup,
        (SELECT SUM(InvAjusteDetalle.Cantidad) FROM InvAjusteDetalle WHERE InvAjusteDetalle.InvAjusteId = InvAjuste.Id AND InvAjusteDetalle.InvCausaId IN (SELECT InvCausa.Id FROM InvCausa WHERE InvCausa.EsPositiva = 1)) AS unidadesp,
        (SELECT COUNT(1) FROM InvAjusteDetalle WHERE InvAjusteDetalle.InvAjusteId = InvAjuste.Id AND InvAjusteDetalle.InvCausaId IN (SELECT InvCausa.Id FROM InvCausa WHERE InvCausa.EsPositiva = 0)) AS skun,
        (SELECT SUM(InvAjusteDetalle.Cantidad) FROM InvAjusteDetalle WHERE InvAjusteDetalle.InvAjusteId = InvAjuste.Id AND InvAjusteDetalle.InvCausaId IN (SELECT InvCausa.Id FROM InvCausa WHERE InvCausa.EsPositiva = 0)) AS unidadesn,
        InvAjuste.M_TotalCostoAjuste AS monto,
        Auditoria_Usuario AS operador
      FROM InvAjuste
      $where
      ORDER BY InvAjuste.Id DESC;
    ";

    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R42Q_Ajuste_Detalle
    FUNCION: Busca informacion general del ajuste
    DESAROLLADO POR: NISA DELGADO
  */
  function R42Q_Ajuste_Detalle($numeroAjuste) {
    $sql = "
        SELECT
            InvAjuste.Auditoria_FechaCreacion AS fecha,
            InvAjuste.NumeroAjuste AS numero_ajuste,
            InvAjuste.M_TotalCostoAjuste AS monto,
            Auditoria_Usuario AS operador
        FROM
            InvAjuste
        WHERE
            InvAjuste.NumeroAjuste = '$numeroAjuste'
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R42Q_Ajuste_Detalle_Listado
    FUNCION: Busca listado de articulos del ajuste
    DESAROLLADO POR: NISA DELGADO
  */
  function R42Q_Ajuste_Detalle_Listado($numeroAjuste) {
    $sql = "
        SELECT
            (SELECT InvArticulo.CodigoArticulo FROM InvArticulo WHERE InvArticulo.Id = InvAjusteDetalle.InvArticuloId) AS codigo,
            (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = InvAjusteDetalle.InvArticuloId) AS codigo_barra,
            (SELECT InvArticulo.DescripcionLarga FROM InvArticulo WHERE InvArticulo.Id = InvAjusteDetalle.InvArticuloId) AS descripcion,
            InvAjusteDetalle.Cantidad AS cantidad
        FROM
            InvAjusteDetalle
        WHERE InvAjusteDetalle.InvAjusteId = (SELECT InvAjuste.NumeroAjuste FROM InvAjuste WHERE InvAjuste.NumeroAjuste = '$numeroAjuste')
    ";
    return $sql;
  }
?>
