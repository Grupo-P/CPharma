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

    $conn = FG_Conectar_Smartpharma($_GET['SEDE']);
    $result = sqlsrv_query($conn, "SELECT * FROM VenCaja ORDER BY CodigoCaja");
    $cajasHtml = '';

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $caja = $row['CodigoCaja'];

      $cajasHtml .= '
        <tr>
          <td>
            <td>&nbsp;</td>
          </td>
        </tr>

        <tr>
          <td colspan="3" style="text-align: right">
            '.$caja.':
          </td>
          <td>
            <input name="comision['.$caja.']" placeholder="Comisión" min="0.01" max="100" step="0.01" type="number" style="width:100%;">
          </td>
        </tr>
      ';
    }

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

          '.$cajasHtml.'

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

    $string = '';

    foreach ($_GET['comision'] as $caja => $key) {
      $string .= 'comision['.$caja.']='.$key . '&';
    }

    echo '<div class="text-center mb-3">';
    echo '<a href="/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'&caja=1&'.$string.'" class="btn btn-outline-success">Ventas por caja</a>';
    echo '<a href="/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'&'.$string.'" class="btn btn-outline-info ml-2">Ventas por cajero</a>';
    echo '</div>';

    echo'<h6 align="center">Ventas por cajero, periodo desde el <b>'.$FInicialImp.'</b> al <b>'.$FFinalImp.'</b></h6>';

    $comisiones = '<ul>';

    foreach ($_GET['comision'] as $key => $value) {
        $value = $value ? $value : 0;
        $comisiones .= '<li>'.$key.': '.$value.'%</li>';
    }

    $comisiones .= '</ul>';

    echo '
        <table class="table table-striped table-bordered col-12">
            <tr>
                <td>
                    <ul>
                        <li>La información de las devoluciones corresponde al usuario que originalmente hizo la venta que fue anulada y no el usuario que hizo la devolución.</li>
                        <li>Comisiones configuradas por el usuario:</li>
                        '.$comisiones.'
                    </ul>
                </td>
            </tr>
        </table>
    ';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
        <tr>
          <th scope="col" class="CP-sticky">#</th>
          <th scope="col" class="CP-sticky">Usuario</th>
          <th scope="col" class="CP-sticky">Nombre</th>
          <th scope="col" class="CP-sticky">C.I.</th>
          <th scope="col" class="CP-sticky">Monto total facturas Bs. (Sin IVA)</th>
          <th scope="col" class="CP-sticky">Monto total devoluciones Bs. (Sin IVA)</th>
          <th scope="col" class="CP-sticky">Diferencia montos</th>
          <th scope="col" class="CP-sticky">Cantidad facturas</th>
          <th scope="col" class="CP-sticky">Cantidad devoluciones</th>
          <th scope="col" class="CP-sticky">Diferencia cantidades</th>
          <th scope="col" class="CP-sticky">Comisión</th>
          <th scope="col" class="CP-sticky">Cantidad de días</th>
          <th scope="col" class="CP-sticky">Días con ventas</th>
          <th scope="col" class="CP-sticky">Cajas</th>
        </tr>
      </thead>
      <tbody>';

    $resultados = [];


    $sql1 = "
      SELECT
        VenFactura.Auditoria_Usuario AS usuario,
        SUM(VenFactura.MontoSubTotalDoc) AS monto_factura,
        COUNT(VenFactura.MontoSubTotalDoc) AS cantidad_factura,
        CONCAT(GenPersona.Nombre, ' ', GenPersona.Apellido) AS nombre,
        GenPersona.IdentificacionFiscal AS ci
      FROM
        VenFactura
          FULL JOIN VenCajero ON VenFactura.Auditoria_Usuario = VenCajero.CodigoUsuarioCaja
          LEFT JOIN GenPersona ON GenPersona.Id = VenCajero.GenPersonaId
      WHERE
          CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) BETWEEN '$FInicial' AND '$FFinal'
      GROUP BY
          VenFactura.Auditoria_Usuario,
          GenPersona.Nombre,
          GenPersona.Apellido,
          GenPersona.IdentificacionFiscal
      ORDER BY
          SUM(VenFactura.MontoSubTotalDoc) DESC
    ";

    $result1 = sqlsrv_query($conn, $sql1);

    while ($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
        $resultados[] = $row1;
    }


    $sql2 = "
      SELECT
        VenFactura.Auditoria_Usuario AS usuario,
        SUM(VenDevolucion.MontoSubTotalDoc) AS monto_devolucion,
        COUNT(VenDevolucion.MontoSubTotalDoc) AS cantidad_devolucion,
        CONCAT(GenPersona.Nombre, ' ', GenPersona.Apellido) AS nombre,
        GenPersona.IdentificacionFiscal AS ci
      FROM
        VenDevolucion
          LEFT JOIN VenFactura ON VenFactura.Id = VenDevolucion.VenFacturaId
          LEFT JOIN VenCajero ON VenFactura.Auditoria_Usuario = VenCajero.CodigoUsuarioCaja
          LEFT JOIN GenPersona ON GenPersona.Id = VenCajero.GenPersonaId
      WHERE
          CONVERT(DATE, VenDevolucion.Auditoria_FechaCreacion) BETWEEN '$FInicial' AND '$FFinal'
      GROUP BY
          VenFactura.Auditoria_Usuario,
          GenPersona.Nombre,
          GenPersona.Apellido,
          GenPersona.IdentificacionFiscal
      ORDER BY
          SUM(VenDevolucion.MontoSubTotalDoc) DESC
    ";

    $result2 = sqlsrv_query($conn, $sql2);

    while ($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
        if (in_array($row2['usuario'], array_column($resultados, 'usuario'))) {
            $i = array_search($row2['usuario'], array_column($resultados, 'usuario'));

            $resultados[$i]['monto_devolucion'] = $row2['monto_devolucion'];
            $resultados[$i]['cantidad_devolucion'] = $row2['cantidad_devolucion'];
        }

        else {
            $resultados[] = $row2;
        }
    }

    $contador = 1;

    foreach ($resultados as $item) {
      $monto_devolucion = isset($item['monto_devolucion']) ? $item['monto_devolucion'] : 0;
      $cantidad_devolucion = isset($item['cantidad_devolucion']) ? $item['cantidad_devolucion'] : 0;
      $monto_factura = isset($item['monto_factura']) ? $item['monto_factura'] : 0;
      $cantidad_factura = isset($item['cantidad_factura']) ? $item['cantidad_factura'] : 0;

      $diferencia_monto = number_format($monto_factura-$monto_devolucion, 2);

      $usuario = $item['usuario'];
      $nombre = $item['nombre'];
      $ci = $item['ci'];
      $monto_factura = number_format($monto_factura, 2);
      $monto_devolucion = number_format($monto_devolucion, 2);

      $cantidad_factura = intval($cantidad_factura);
      $cantidad_devolucion = isset($item['cantidad_devolucion']) ? intval($item['cantidad_devolucion']) : 0;
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
        ORDER BY dias ASC
      ";

      $result2 = sqlsrv_query($conn, $sql2);

      $dias = [];

      while ($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
        $dias[] = $row2['dias']->format('d/m/Y');
      }

      $cantidad = count($dias);
      $dias = implode('<br>', $dias);

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
        ORDER BY caja ASC
      ";

      $result3 = sqlsrv_query($conn, $sql3);

      $cajas = [];

      while ($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {
        $cajas[] = '<a href="/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'&caja='.$row3['caja'].'&'.$string.'" style="text-decoration: none; color: black" target="_blank">' . $row3['caja'] . '</a>';
      }

      $cajas = implode('<br>', $cajas);

      $link1 = '/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'&usuario='.$usuario.'&'.$string;



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
      echo '<td class="text-center">'.comision($usuario, $conn).'</td>';
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
          <th scope="col" class="CP-sticky">Monto total facturas Bs. (Sin IVA)</th>
          <th scope="col" class="CP-sticky">Monto total devoluciones Bs. (Sin IVA)</th>
          <th scope="col" class="CP-sticky">Diferencia montos</th>
          <th scope="col" class="CP-sticky">Comisión</th>
          <th scope="col" class="CP-sticky">Cantidad facturas</th>
          <th scope="col" class="CP-sticky">Cantidad devoluciones</th>
          <th scope="col" class="CP-sticky">Diferencia cantidades</th>
          <th scope="col" class="CP-sticky">Cajas</th>
        </tr>
      </thead>
      <tbody>';

    $string = '';

    foreach ($_GET['comision'] as $caja => $key) {
      $string .= 'comision['.$caja.']='.$key . '&';
    }


    $sql1 = "
        SELECT
            CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) AS fecha,
            SUM(VenFactura.MontoSubTotalDoc) AS monto_factura,
            COUNT(VenFactura.MontoSubTotalDoc) AS cantidad_factura
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

    $resultados = [];

    while ($row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
        $resultados[] = $row1;
    }

    $sql2 = "
        SELECT
            CONVERT(DATE, VenDevolucion.Auditoria_FechaCreacion) AS fecha,
            SUM(VenDevolucion.MontoSubTotalDoc) AS monto_devolucion,
            COUNT(VenDevolucion.MontoSubTotalDoc) AS cantidad_devolucion
        FROM
            VenDevolucion
                LEFT JOIN VenFactura ON VenFactura.Id = VenDevolucion.VenFacturaId
        WHERE
            (CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) BETWEEN '$FInicial' AND '$FFinal') AND
            VenFactura.Auditoria_Usuario = '$Usuario'
        GROUP BY
            CONVERT(DATE, VenDevolucion.Auditoria_FechaCreacion)
        ORDER BY
            CONVERT(DATE, VenDevolucion.Auditoria_FechaCreacion) ASC
    ";

    $result2 = sqlsrv_query($conn, $sql2);

    while ($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
        if (in_array($row2['fecha'], array_column($resultados, 'fecha'))) {
            $i = array_search($row2['fecha'], array_column($resultados, 'fecha'));

            $resultados[$i]['monto_devolucion'] = $row2['monto_devolucion'];
            $resultados[$i]['cantidad_devolucion'] = $row2['cantidad_devolucion'];
        }

        else {
            $resultados[] = $row2;
        }
    }

    $contador = 1;

    foreach ($resultados as $item) {

      $monto_devolucion = isset($item['monto_devolucion']) ? $item['monto_devolucion'] : 0;
      $cantidad_devolucion = isset($item['cantidad_devolucion']) ? $item['cantidad_devolucion'] : 0;
      $monto_factura = isset($item['monto_factura']) ? $item['monto_factura'] : 0;
      $cantidad_factura = isset($item['cantidad_factura']) ? $item['cantidad_factura'] : 0;

      $fecha = $item['fecha']->format('d/m/Y');
      $fechaSinFormato = $item['fecha']->format('Y-m-d');
      $diferencia_monto = number_format($monto_factura-$monto_devolucion, 2);
      $monto_factura = number_format($monto_factura, 2);
      $monto_devolucion = number_format($monto_devolucion, 2);

      $cantidad_factura = intval($cantidad_factura);
      $cantidad_devolucion = intval($cantidad_devolucion);
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
        ORDER BY caja ASC
      ";

      $result3 = sqlsrv_query($conn, $sql3);

      $cajas = [];

      while ($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {
        $cajas[] = '<a href="/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'&caja='.$row3['caja'].'&'.$string.'" style="text-decoration: none; color: black" target="_blank">' . $row3['caja'] . '</a>';
      }

      $cajas = implode('<br>', $cajas);



      echo '<tr>';
      echo '<td class="text-center">'.$contador.'</td>';
      echo '<td class="text-center">'.$fecha.'</td>';
      echo '<td class="text-center">'.$monto_factura.'</td>';
      echo '<td class="text-center">'.$monto_devolucion.'</td>';
      echo '<td class="text-center">'.$diferencia_monto.'</td>';
      echo '<td class="text-center">'.comision($_GET['usuario'], $conn, $fechaSinFormato).'</td>';
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

    $string = '';

    foreach ($_GET['comision'] as $caja => $key) {
      $string .= 'comision['.$caja.']='.$key . '&';
    }

    echo '<div class="text-center mb-3">';
    echo '<a href="/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'&caja=1" class="btn btn-outline-success">Ventas por caja</a>';
    echo '<a href="/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'&'.$string.'" class="btn btn-outline-info ml-2">Ventas por cajero</a>';
    echo '</div>';

    echo'<h6 align="center">Ventas por caja, periodo desde el <b>'.$FInicialImp.'</b> al <b>'.$FFinalImp.'</b></h6>';

    echo '
        <table class="table table-striped table-bordered col-12">
            <tr>
                <td>
                    <ul>
                        <li>La información de las devoluciones corresponde a la caja que hizo la devolución y no a la caja que emitió la venta original que fue anulada.</li>
                    </ul>
                </td>
            </tr>
        </table>
    ';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
        <tr>
          <th scope="col" class="CP-sticky">#</th>
          <th scope="col" class="CP-sticky">Caja</th>
          <th scope="col" class="CP-sticky">Monto total facturas Bs. (Sin IVA)</th>
          <th scope="col" class="CP-sticky">Monto total devoluciones Bs. (Sin IVA)</th>
          <th scope="col" class="CP-sticky">Diferencia montos</th>
          <th scope="col" class="CP-sticky">Cantidad facturas</th>
          <th scope="col" class="CP-sticky">Cantidad devoluciones</th>
          <th scope="col" class="CP-sticky">Diferencia cantidades</th>
          <th scope="col" class="CP-sticky">Cantidad de días</th>
          <th scope="col" class="CP-sticky">Días con ventas</th>
          <th scope="col" class="CP-sticky">Usuario que vendió</th>
        </tr>
      </thead>
      <tbody>';


    $sql1 = "
        SELECT
            VenCaja.CodigoCaja AS caja,
            VenCaja.Id AS id_caja,
            SUM(VenFactura.MontoSubTotalDoc) AS monto_factura,
            COUNT(VenFactura.MontoSubTotalDoc) AS cantidad_factura,
            SUM(VenDevolucion.MontoSubTotalDoc) AS monto_devolucion,
            COUNT(VenDevolucion.MontoSubTotalDoc) AS cantidad_devolucion
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
        ORDER BY dias ASC
      ";

      $result2 = sqlsrv_query($conn, $sql2);

      $dias = [];

      while ($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {
        $dias[] = $row2['dias']->format('d/m/Y');
      }

      $cantidad = count($dias);
      $dias = implode('<br>', $dias);

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
        ORDER BY usuario ASC
      ";

      $result3 = sqlsrv_query($conn, $sql3);

      $usuarios = [];

      while ($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {
        $usuarios[] = '<a href="/reporte51?fechaInicio='.$_GET['fechaInicio'].'&fechaFin='.$_GET['fechaFin'].'&SEDE='.$_GET['SEDE'].'&usuario='.$row3['usuario'].'&'.$string.'" style="text-decoration: none; color: black" target="_blank">' . $row3['usuario'] . '</a>';
      }

      $usuarios = implode('<br>', $usuarios);




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

  function comision($usuario, $conn, $fecha = '')
  {
    if ($fecha != '') {
      $where = "CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) = '$fecha' AND ";

    } else {
      $inicio = $_GET['fechaInicio'];
      $fin = $_GET['fechaFin'];
      $where = "CONVERT(DATE, VenFactura.Auditoria_FechaCreacion) BETWEEN '$inicio' AND '$fin' AND ";
    }

    $resultado = 0;

    $sql = "
      SELECT
        VenCaja.CodigoCaja AS caja,
        SUM(VenFactura.MontoSubTotalDoc) AS monto
      FROM
        VenFactura
          LEFT JOIN VenCaja ON VenCaja.Id = VenFactura.VenCajaId
      WHERE
        VenFactura.Id NOT IN (SELECT VenDevolucion.VenFacturaId FROM VenDevolucion) AND
        $where
        VenFactura.Auditoria_Usuario = '$usuario'
      GROUP BY
        VenCaja.CodigoCaja
    ";

    $result = sqlsrv_query($conn, $sql);

    while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $monto = $row['monto'];
      $caja = $row['caja'];
      $comision = $_GET['comision'][$caja] ? $_GET['comision'][$caja] : 0;

      $resultado = $resultado + ($monto * ($comision / 100));
    }

    return number_format($resultado, 2);
  }
?>
