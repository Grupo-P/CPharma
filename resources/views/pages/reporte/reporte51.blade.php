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

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Ventas por cajas/cajeros
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

    if (isset($_GET['usuario'])) {
        R51_Ventas_Cajero_Detallado($_GET['SEDE'], $_GET['fechaInicio'], $_GET['fechaFin'], $_GET['usuario']);
    }

    elseif (isset($_GET['caja'])) {
        R51_Ventas_Caja($_GET['SEDE'], $_GET['fechaInicio'], $_GET['fechaFin']);
    }

    else {
        R51_Ventas_Cajero($_GET['SEDE'], $_GET['fechaInicio'], $_GET['fechaFin']);
    }

    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Ventas por cajas/cajeros');

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
    TITULO: R51_Ventas_Cajero
    FUNCION: Arma una lista de ventas por cajero
    RETORNO: No aplica
    DESAROLLADO POR: NISA DELGADO
  */
  function R51_Ventas_Cajero($SedeConnection,$FInicial,$FFinal){
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $FInicialImp = date("d/m/Y", strtotime($FInicial));
    $FFinalImp= date("d/m/Y", strtotime($FFinal));

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

    echo '<div class="text-center mb-3">';
    echo '<a href="/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'&caja=1" class="btn btn-outline-success">Ventas por caja</a>';
    echo '<a href="/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'" class="btn btn-outline-info ml-2">Ventas por cajero</a>';
    echo '</div>';

    echo'<h6 align="center">Ventas por cajero, periodo desde el <b>'.$FInicialImp.'</b> al <b>'.$FFinalImp.'</b></h6>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
        <tr>
          <th scope="col" class="CP-sticky">#</th>
          <th scope="col" class="CP-sticky">Usuario</th>
          <th scope="col" class="CP-sticky">Nombre</th>
          <th scope="col" class="CP-sticky">C.I.</th>
          <th scope="col" class="CP-sticky">Monto total facturas Bs.</th>
          <th scope="col" class="CP-sticky">Monto total devoluciones Bs.</th>
          <th scope="col" class="CP-sticky">Diferencia montos</th>
          <th scope="col" class="CP-sticky">Cantidad facturas</th>
          <th scope="col" class="CP-sticky">Cantidad devoluciones</th>
          <th scope="col" class="CP-sticky">Diferencia cantidades</th>
          <th scope="col" class="CP-sticky">Cantidad de días</th>
          <th scope="col" class="CP-sticky">Días con ventas</th>
          <th scope="col" class="CP-sticky">Cajas</th>
        </tr>
      </thead>
      <tbody>';


    $sql1 = "
      SELECT
        VenFactura.Auditoria_Usuario AS usuario,
        SUM(VenFactura.M_MontoTotalFactura) AS monto_factura,
        COUNT(VenFactura.M_MontoTotalFactura) AS cantidad_factura,
        SUM(VenDevolucion.M_MontoTotalDevolucion) AS monto_devolucion,
        COUNT(VenDevolucion.M_MontoTotalDevolucion) AS cantidad_devolucion,
        CONCAT(GenPersona.Nombre, ' ', GenPersona.Apellido) AS nombre,
        GenPersona.IdentificacionFiscal AS ci
        FROM
        VenFactura LEFT JOIN VenDevolucion ON VenFactura.Id = VenDevolucion.VenFacturaId
          LEFT JOIN VenCajero ON VenFactura.Auditoria_Usuario = VenCajero.CodigoUsuarioCaja
          LEFT JOIN GenPersona ON GenPersona.Id = VenCajero.GenPersonaId
        WHERE
          CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) BETWEEN '$FInicial' AND '$FFinal'
        GROUP BY
          VenFactura.Auditoria_Usuario,
          GenPersona.Nombre,
          GenPersona.Apellido,
          GenPersona.IdentificacionFiscal
        ORDER BY
          SUM(VenFactura.M_MontoTotalFactura) DESC
    ";

    $result1 = sqlsrv_query($conn, $sql1);

    $contador = 1;

    while ($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
      $usuario = $row1['usuario'];
      $nombre = $row1['nombre'];
      $ci = $row1['ci'];
      $monto_factura = number_format($row1['monto_factura'], 2);
      $monto_devolucion = number_format($row1['monto_devolucion'], 2);
      $diferencia_monto = number_format($row1['monto_factura']-$row1['monto_devolucion'], 2);
      $cantidad_factura = intval($row1['cantidad_factura']);
      $cantidad_devolucion = intval($row1['cantidad_devolucion']);
      $diferencia_cantidad = $cantidad_factura-$cantidad_devolucion;


      $sql2 = "
        SELECT
          CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) AS dias
        FROM
          VenFactura
        WHERE
          (CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) BETWEEN '$FInicial' AND '$FFinal')
            AND
          VenFactura.Auditoria_Usuario = '$usuario'
        GROUP BY
          CONVERT(DATE, VenFactura.Auditoria_FechaCreacion)
      ";

      $result2 = sqlsrv_query($conn, $sql2);

      $dias = [];

      while ($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
        $dias[] = $row2['dias']->format('d/m/Y');
      }

      $cantidad = count($dias);
      $dias = implode(' - ', $dias);

      $sql3 = "
        SELECT
          VenCaja.CodigoCaja AS caja
        FROM
          VenFactura LEFT JOIN VenCaja ON VenFactura.VenCajaId = VenCaja.Id
        WHERE
          (CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) BETWEEN '$FInicial' AND '$FFinal')
            AND
          VenFactura.Auditoria_Usuario = '$usuario'
        GROUP BY
          VenCaja.CodigoCaja
      ";

      $result3 = sqlsrv_query($conn, $sql3);

      $cajas = [];

      while ($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {
        $cajas[] = '<a href="/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'&caja='.$row3['caja'].'" style="text-decoration: none; color: black" target="_blank">' . $row3['caja'] . '</a>';
      }

      $cajas = implode(' - ', $cajas);

      $link1 = '/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'&usuario='.$usuario;



      echo '<tr>';
      echo '<td class="text-center">'.$contador.'</td>';
      echo '<td class="text-center CP-barrido"><a href="'.$link1.'" style="text-decoration: none; color: black" target="_blank">'.$usuario.'</a></td>';
      echo '<td class="text-center CP-barrido"><a href="'.$link1.'" style="text-decoration: none; color: black" target="_blank">'.FG_Limpiar_Texto($nombre).'</a></td>';
      echo '<td class="text-center CP-barrido"><a href="'.$link1.'" style="text-decoration: none; color: black" target="_blank">'.$ci.'</a></td>';
      echo '<td class="text-center">'.$monto_factura.'</td>';
      echo '<td class="text-center">'.$monto_devolucion.'</td>';
      echo '<td class="text-center">'.$diferencia_monto.'</td>';
      echo '<td class="text-center">'.$cantidad_factura.'</td>';
      echo '<td class="text-center">'.$cantidad_devolucion.'</td>';
      echo '<td class="text-center">'.$diferencia_cantidad.'</td>';
      echo '<td class="text-center">'.$cantidad.'</td>';
      echo '<td class="text-center">'.$dias.'</td>';
      echo '<td class="text-center">'.$cajas.'</td>';
      echo '</tr>';

      $contador++;
    }

    echo '
      </tbody>
    </table>';
  }

  /*********************************************************************************/
  /*
    TITULO: R51_Ventas_Cajero
    FUNCION: Arma una lista detallado de ventas del cajero cada dia
    RETORNO: No aplica
    DESAROLLADO POR: NISA DELGADO
  */
  function R51_Ventas_Cajero_Detallado($SedeConnection, $FInicial, $FFinal, $Usuario){
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $FInicialImp = date("d/m/Y", strtotime($FInicial));
    $FFinalImp= date("d/m/Y", strtotime($FFinal));

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
    echo'<h6 align="center">Periodo desde el <b>'.$FInicialImp.'</b> al <b>'.$FFinalImp.'</b> del usuario <b>'.$Usuario.'</b></h6>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
        <tr>
          <th scope="col" class="CP-sticky">#</th>
          <th scope="col" class="CP-sticky">Fecha</th>
          <th scope="col" class="CP-sticky">Monto total facturas Bs.</th>
          <th scope="col" class="CP-sticky">Monto total devoluciones Bs.</th>
          <th scope="col" class="CP-sticky">Diferencia montos</th>
          <th scope="col" class="CP-sticky">Cantidad facturas</th>
          <th scope="col" class="CP-sticky">Cantidad devoluciones</th>
          <th scope="col" class="CP-sticky">Diferencia cantidades</th>
          <th scope="col" class="CP-sticky">Cajas</th>
        </tr>
      </thead>
      <tbody>';


    $sql1 = "
        SELECT
            CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) AS fecha,
            SUM(VenFactura.M_MontoTotalFactura) AS monto_factura,
            COUNT(VenFactura.M_MontoTotalFactura) AS cantidad_factura,
            SUM(VenDevolucion.M_MontoTotalDevolucion) AS monto_devolucion,
            COUNT(VenDevolucion.M_MontoTotalDevolucion) AS cantidad_devolucion
        FROM
            VenFactura LEFT JOIN VenDevolucion ON VenFactura.Id = VenDevolucion.VenFacturaId
        WHERE
            (CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) BETWEEN '$FInicial' AND '$FFinal') AND
            VenFactura.Auditoria_Usuario = '$Usuario'
        GROUP BY
            CONVERT(DATE, VenFactura.Auditoria_FechaCreacion)
        ORDER BY
            CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) ASC
    ";

    $result1 = sqlsrv_query($conn, $sql1);

    $contador = 1;

    while ($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {

      $fecha = $row1['fecha']->format('d/m/Y');
      $fechaSinFormato = $row1['fecha']->format('Y-m-d');
      $monto_factura = number_format($row1['monto_factura'], 2);
      $monto_devolucion = number_format($row1['monto_devolucion'], 2);
      $diferencia_monto = number_format($row1['monto_factura']-$row1['monto_devolucion'], 2);
      $cantidad_factura = intval($row1['cantidad_factura']);
      $cantidad_devolucion = intval($row1['cantidad_devolucion']);
      $diferencia_cantidad = $cantidad_factura-$cantidad_devolucion;

      $sql3 = "
        SELECT
          VenCaja.CodigoCaja AS caja
        FROM
          VenFactura LEFT JOIN VenCaja ON VenFactura.VenCajaId = VenCaja.Id
        WHERE
          CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) = '$fechaSinFormato'
            AND
          VenFactura.Auditoria_Usuario = '$Usuario'
        GROUP BY
          VenCaja.CodigoCaja
      ";

      $result3 = sqlsrv_query($conn, $sql3);

      $cajas = [];

      while ($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {
        $cajas[] = $row3['caja'];
      }

      $cajas = implode(' - ', $cajas);



      echo '<tr>';
      echo '<td class="text-center">'.$contador.'</td>';
      echo '<td class="text-center">'.$fecha.'</td>';
      echo '<td class="text-center">'.$monto_factura.'</td>';
      echo '<td class="text-center">'.$monto_devolucion.'</td>';
      echo '<td class="text-center">'.$diferencia_monto.'</td>';
      echo '<td class="text-center">'.$cantidad_factura.'</td>';
      echo '<td class="text-center">'.$cantidad_devolucion.'</td>';
      echo '<td class="text-center">'.$diferencia_cantidad.'</td>';
      echo '<td class="text-center">'.$cajas.'</td>';
      echo '</tr>';

      $contador++;
    }

    echo '
      </tbody>
    </table>';
  }

  /*********************************************************************************/
  /*
    TITULO: R51_Ventas_Caja
    FUNCION: Arma una lista de ventas por caja
    RETORNO: No aplica
    DESAROLLADO POR: NISA DELGADO
  */
  function R51_Ventas_Caja($SedeConnection,$FInicial,$FFinal){
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $FInicialImp = date("d/m/Y", strtotime($FInicial));
    $FFinalImp= date("d/m/Y", strtotime($FFinal));

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

    echo '<div class="text-center mb-3">';
    echo '<a href="/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'&caja=1" class="btn btn-outline-success">Ventas por caja</a>';
    echo '<a href="/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'" class="btn btn-outline-info ml-2">Ventas por cajero</a>';
    echo '</div>';

    echo'<h6 align="center">Ventas por caja, periodo desde el <b>'.$FInicialImp.'</b> al <b>'.$FFinalImp.'</b></h6>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
        <tr>
          <th scope="col" class="CP-sticky">#</th>
          <th scope="col" class="CP-sticky">Caja</th>
          <th scope="col" class="CP-sticky">Monto total facturas Bs.</th>
          <th scope="col" class="CP-sticky">Monto total devoluciones Bs.</th>
          <th scope="col" class="CP-sticky">Diferencia montos</th>
          <th scope="col" class="CP-sticky">Cantidad facturas</th>
          <th scope="col" class="CP-sticky">Cantidad devoluciones</th>
          <th scope="col" class="CP-sticky">Diferencia cantidades</th>
          <th scope="col" class="CP-sticky">Cantidad de días</th>
          <th scope="col" class="CP-sticky">Días con ventas</th>
          <th scope="col" class="CP-sticky">Usuarios</th>
        </tr>
      </thead>
      <tbody>';


    $sql1 = "
        SELECT
            VenCaja.CodigoCaja AS caja,
            VenCaja.Id AS id_caja,
            SUM(VenFactura.M_MontoTotalFactura) AS monto_factura,
            COUNT(VenFactura.M_MontoTotalFactura) AS cantidad_factura,
            SUM(VenDevolucion.M_MontoTotalDevolucion) AS monto_devolucion,
            COUNT(VenDevolucion.M_MontoTotalDevolucion) AS cantidad_devolucion
        FROM
            VenFactura LEFT JOIN VenCaja ON VenFactura.VenCajaId = VenCaja.Id
                LEFT JOIN VenDevolucion ON VenFactura.Id = VenDevolucion.VenFacturaId
        WHERE
            CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) BETWEEN '$FInicial' AND '$FFinal'
        GROUP BY
            VenCaja.CodigoCaja,
            VenCaja.Id;
    ";

    $result1 = sqlsrv_query($conn, $sql1);

    $contador = 1;

    while ($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {

      $id_caja = $row1['id_caja'];
      $caja = $row1['caja'];
      $monto_factura = number_format($row1['monto_factura'], 2);
      $monto_devolucion = number_format($row1['monto_devolucion'], 2);
      $diferencia_monto = number_format($row1['monto_factura']-$row1['monto_devolucion'], 2);
      $cantidad_factura = intval($row1['cantidad_factura']);
      $cantidad_devolucion = intval($row1['cantidad_devolucion']);
      $diferencia_cantidad = $cantidad_factura-$cantidad_devolucion;


      $sql2 = "
        SELECT
          CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) AS dias
        FROM
          VenFactura
        WHERE
          (CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) BETWEEN '$FInicial' AND '$FFinal')
            AND
          VenFactura.VenCajaId = '$id_caja'
        GROUP BY
          CONVERT(DATE, VenFactura.Auditoria_FechaCreacion)
      ";

      $result2 = sqlsrv_query($conn, $sql2);

      $dias = [];

      while ($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
        $dias[] = $row2['dias']->format('d/m/Y');
      }

      $cantidad = count($dias);
      $dias = implode(' - ', $dias);

      $sql3 = "
        SELECT
          VenFactura.Auditoria_Usuario AS usuario
        FROM
          VenFactura
        WHERE
          (CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) BETWEEN '$FInicial' AND '$FFinal')
            AND
          VenFactura.VenCajaId = '$id_caja'
        GROUP BY
          VenFactura.Auditoria_Usuario
      ";

      $result3 = sqlsrv_query($conn, $sql3);

      $usuarios = [];

      while ($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {
        $usuarios[] = '<a href="/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'&usuario='.$row3['usuario'].'" style="text-decoration: none; color: black" target="_blank">' . $row3['usuario'] . '</a>';
      }

      $usuarios = implode(' - ', $usuarios);




      echo '<tr>';
      echo '<td class="text-center">'.$contador.'</td>';
      echo '<td class="text-center">'.$caja.'</td>';
      echo '<td class="text-center">'.$monto_factura.'</td>';
      echo '<td class="text-center">'.$monto_devolucion.'</td>';
      echo '<td class="text-center">'.$diferencia_monto.'</td>';
      echo '<td class="text-center">'.$cantidad_factura.'</td>';
      echo '<td class="text-center">'.$cantidad_devolucion.'</td>';
      echo '<td class="text-center">'.$diferencia_cantidad.'</td>';
      echo '<td class="text-center">'.$cantidad.'</td>';
      echo '<td class="text-center">'.$dias.'</td>';
      echo '<td class="text-center">'.$usuarios.'</td>';
      echo '</tr>';

      $contador++;
    }

    echo '
      </tbody>
    </table>';
  }
?>
