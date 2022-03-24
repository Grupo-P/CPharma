@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
  <style>
  @media print {
    @page {
      size: landscape;
    }
    table {
      width: 100%;
      font-size: 9px;
    }
  }
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

  <script>
      function mostrar_ocultar(that, elemento) {
            if (that.checked) {
                return $('.' + elemento).show();
            }

            return $('.' + elemento).hide();
        }

        campos = ['fecha_factura', 'hora_factura', 'nombre_cliente', 'numero_factura', 'fiscal_factura', 'serial_impresora', 'corte_z', 'caja_origen', 'fecha_devolucion', 'hora_devolucion', 'numero_devolucion', 'fiscal_devolucion', 'serial_impresora', 'dias', 'caja', 'causa', 'unidades', 'sku', 'monto_bs', 'tasa', 'monto_ds'];

        function mostrar_todas(that) {
            if (that.checked) {
                for (var i = campos.length - 1; i >= 0; i--) {
                    $('.' + campos[i]).show();
                    $('[name='+campos[i]+']').prop('checked', true);
                }
            } else {
                for (var i = campos.length - 1; i >= 0; i--) {
                    $('.' + campos[i]).hide();
                    $('[name='+campos[i]+']').prop('checked', false);
                }
            }
        }
  </script>
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Devoluciones de clientes
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

    R33_Devoluciones_Clientes($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Devoluciones de clientes');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
	}

  if (isset($_GET['numeroDevolucion'])) {

    $InicioCarga = new DateTime("now");

    R33_Devoluciones_Clientes_Detalles($_GET['SEDE'],$_GET['numeroDevolucion']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Devoluciones de clientes');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  } 

	if (!isset($_GET['fechaInicio']) && !isset($_GET['numeroDevolucion'])){
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
    TITULO: R33_Devoluciones_Clientes
    FUNCION: Arma una lista de devoluciones de clientes
    RETORNO: No aplica
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R33_Devoluciones_Clientes($SedeConnection,$FInicial,$FFinal){
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    $sql5 = R33Q_Devoluciones_Clientes($FInicial,$FFinal);
    $result = sqlsrv_query($conn,$sql5);

    echo '
        <div class="modal fade" id="ver_campos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mostrar u ocultar columnas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'fecha_factura\')" name="fecha_factura" checked>
                    Fecha factura
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'hora_factura\')" name="hora_factura" checked>
                    Hora factura
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'nombre_cliente\')" name="nombre_cliente" checked>
                    Nombre cliente
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'numero_factura\')" name="numero_factura" checked>
                    Número factura
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'fiscal_factura\')" name="fiscal_factura" checked>
                    Fiscal factura
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'serial_impresora\')" name="serial_impresora" checked>
                    Serial impresora
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'corte_z\')" name="corte_z" checked>
                    Corte Z
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'caja_origen\')" name="caja_origen" checked>
                    Caja origen
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'fecha_devolucion\')" name="fecha_devolucion" checked>
                    Fecha devolución
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'hora_devolucion\')" name="hora_devolucion" checked>
                    Hora devolución
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'numero_devolucion\')" name="numero_devolucion" checked>
                    Número devolución
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'fiscal_devolucion\')" name="fiscal_devolucion" checked>
                    Fiscal devolución
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'serial_impresora\')" name="serial_impresora" checked>
                    Serial impresora
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'dias\')" name="dias" checked>
                    Días
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'caja\')" name="caja" checked>
                    Caja
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'causa\')" name="causa" checked>
                    Causa
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'unidades\')" name="unidades" checked>
                    Unidades
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'sku\')" name="sku" checked>
                    SKU
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'monto_bs\')" name="monto_bs" checked>
                    Monto Bs.
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'tasa\')" name="tasa" checked>
                    Tasa
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'monto_ds\')" name="monto_ds" checked>
                    Monto $
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_todas(this)" name="Marcar todas" checked>
                    Marcar todas
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>';

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

    echo '<h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Mostrar u ocultar campos<a></h6>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
        <tr>  
          <th scope="col" class="CP-sticky">Nro.</th>
          <th scope="col" class="fecha_factura CP-sticky">Fecha Factura</th>
          <th scope="col" class="hora_factura CP-sticky">Hora Factura</th>
          <th scope="col" class="nombre_cliente CP-sticky">Nombre Cliente</th>
          <th scope="col" class="numero_factura CP-sticky">Nro Factura</th>
          <th scope="col" class="fiscal_factura CP-sticky">Fiscal Factura</th>
          <th scope="col" class="serial_impresora CP-sticky">Serial Impresora</th>
          <th scope="col" class="corte_z CP-sticky">Corte Z</th>
          <th scope="col" class="caja_origen CP-sticky">Caja Origen</th>
          <th scope="col" class="fecha_devolucion CP-sticky">Fecha Devolucion</td>
          <th scope="col" class="hora_devolucion CP-sticky">Hora Devolucion</td>
          <th scope="col" class="numero_devolucion CP-sticky">Numero Devolucion</td>
          <th scope="col" class="fiscal_devolucion CP-sticky">Fiscal Devolucion</td>
          <th scope="col" class="serial_impresora CP-sticky">Serial Impresora</td>
          <th scope="col" class="dias CP-sticky">Dias</td>
          <th scope="col" class="caja CP-sticky">Caja</th>
          <th scope="col" class="causa CP-sticky">Causa</th>
          <th scope="col" class="unidades CP-sticky">Unidades</th>
          <th scope="col" class="sku CP-sticky">SKU</th>
          <th scope="col" class="monto_bs CP-sticky">Monto Bs</th>
          <th scope="col" class="tasa CP-sticky bg-warning">Tasa</th>
          <th scope="col" class="monto_ds CP-sticky">Monto $</th>
        </tr>
      </thead>
      <tbody>
    ';
    $contador = 1;

    $totalSku = 0;
    $totalBs = 0;
    $totalUnidades = 0;
    $totalDs = 0;

    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $fecha_factura = $row["fecha_factura"];
      $hora_factura = $row["hora_factura"];
      $nombre_cliente = $row["nombre_cliente"];
      $nro_factura = $row["nro_factura"];
      $fiscal_factura = $row["fiscal_factura"];
      $serial_impresora_origen = $row["serial_impresora_origen"];
      $corte_z = $row["corte_z"];
      $caja_origen = $row["caja_origen"];
      $fecha_devolucion = $row["fecha_devolucion"];
      $hora_devolucion = $row["hora_devolucion"];
      $numero_devolucion = $row["numero_devolucion"];
      $fiscal_devolucion = $row["fiscal_devolucion"];
      $serial_impresora = $row["serial_impresora"];
      $dias = $row["dias"];
      $caja = $row["caja"];
      $causa = $row["causa"];
      $unidades = $row["unidades"];
      $unidades = intval($unidades);
      $totalUnidades = $totalUnidades + $unidades;
      $sku = $row["sku"];
      $totalSku = $totalSku + $row['sku'];

      $monto_bs = number_format($row["monto_bs"], 2, ',', '.');
      $totalBs = $totalBs + $row['monto_bs'];

      $query_cantidad_causas = sqlsrv_query($conn, "SELECT COUNT(1) AS quantity FROM VenDevolucionDetalle WHERE VenDevolucionId = (SELECT Id FROM VenDevolucion WHERE ConsecutivoDevolucionSistema = $numero_devolucion) GROUP BY VenCausaOperacionId");

      $cantidad_causas = 0;

      while ($row_cantidad_causas = sqlsrv_fetch_array($query_cantidad_causas, SQLSRV_FETCH_ASSOC)) {
        $cantidad_causas = $cantidad_causas + 1;
      }

      $TasaMercado = 
        DB::table('dolars')
        ->select('tasa')
        ->whereDate('fecha', '<=', $row['fecha_devolucion_sin_formato'])
        ->orderBy('fecha','desc')
        ->take(1)->get();


      if( (!empty($TasaMercado[0])) ) {
        $TM = $TasaMercado[0]->tasa;
        $TasaMercadoNumber = ($TasaMercado[0]->tasa);
        $TasaMercado = ($TasaMercado[0]->tasa);
        $TasaMercado = number_format($TasaMercado,2,"," ,"." );
      }
      else {
        $TM = 0.00;
        $TasaMercado = number_format(0.00,2,"," ,"." );
      }

      $montoDolares = floatval($row["monto_bs"]) / floatval($TasaMercadoNumber);

      $totalDs = $totalDs + $montoDolares;

      $montoDolares = number_format($montoDolares,2,"," ,"." );

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td class="fecha_factura" align="center">'.$fecha_factura.'</td>';
      echo '<td class="hora_factura" align="center">'.$hora_factura.'</td>';
      echo '<td class="nombre_cliente" align="center">'.$nombre_cliente.'</td>';
      echo '<td class="numero_factura" align="center">'.$nro_factura.'</td>';
      echo '<td class="fiscal_factura" align="center">'.$fiscal_factura.'</td>';
      echo '<td class="serial_impresora" align="center">'.$serial_impresora.'</td>';
      echo '<td class="corte_z" align="center">'.$corte_z.'</td>';
      echo '<td class="caja_origen" align="center">'.$caja_origen.'</td>';
      echo '<td class="fecha_devolucion" align="center">'.$fecha_devolucion.'</td>';
      echo '<td class="hora_devolucion" align="center">'.$hora_devolucion.'</td>';
      echo '<td align="center" class="CP-barrido numero_devolucion">
          <a href="/reporte33?SEDE='.$_GET['SEDE'].'&numeroDevolucion='.$numero_devolucion.'" style="text-decoration: none; color: black;" target="_blank">'.$numero_devolucion.'</a>
        </td>';
      echo '<td class="fiscal_devolucion" align="center">'.$fiscal_devolucion.'</td>';
      echo '<td class="serial_impresora" align="center">'.$serial_impresora.'</td>';
      echo '<td class="dias" align="center">'.$dias.'</td>';
      echo '<td class="caja" align="center">'.$caja.'</td>';
      if ($cantidad_causas == 1) {
        echo '<td class="causa" align="center">'.$causa.'</td>';
      } else {
        echo '<td class="causa" align="center">-</td>';
      }
      echo '<td class="unidades" align="center">'.$unidades.'</td>';
      echo '<td class="sku" align="center">'.$sku.'</td>';
      echo '<td class="monto_bs" align="center">'.$monto_bs.'</td>';
      echo '<td class="tasa" align="center">'.$TasaMercado.'</td>';
      echo '<td class="monto_ds" align="center">'.$montoDolares.'</td>';
      $contador++;
    }

    $totalBs = number_format($totalBs, 2, ',', '.');
    $totalDs = number_format($totalDs, 2, ',', '.');

    echo '
      </tbody>
    </table>';

    echo '<table class="table table-striped table-bordered col-12">';
    echo '<thead>';

    echo '<td align="center"><strong>Totales</strong></td>';
    echo '<td align="center"><strong>Unidades: '.$totalUnidades.'</strong></td>';
    echo '<td align="center"><strong>SKU: '.$totalSku.'</strong></td>';
    echo '<td align="center"><strong>Monto en Bs: '.$totalBs.'</strong></td>';
    echo '<td align="center"><strong>Monto en $: '.$totalDs.'</strong></td>';

    echo '</table>';
    echo '</thead>';

    sqlsrv_close($conn);
  }

  /*********************************************************************************/ 
  /*
    TITULO: R33_Devoluciones_Clientes_Detalles
    FUNCION: Arma una lista de los detalles de las devoluciones de clientes
    RETORNO: No aplica
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R33_Devoluciones_Clientes_Detalles($SedeConnection,$numeroDevolucion){
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $sql6 = R33Q_Devoluciones_Clientes_Id($numeroDevolucion);
    $result6 = sqlsrv_query($conn,$sql6);
    $row6 = sqlsrv_fetch_array($result6);

    $sql5 = R33Q_Devoluciones_Clientes_Detalles($numeroDevolucion);
    $result = sqlsrv_query($conn,$sql5);

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
    <br/>';

    echo'
    <table class="table table-striped table-bordered col-12">
      <thead class="thead-dark">
        <tr>
          <th scope="col" class="CP-sticky">Fecha Factura</th>
          <th scope="col" class="CP-sticky">Hora Factura</th>
          <th scope="col" class="CP-sticky">Nombre Cliente</th>
          <th scope="col" class="CP-sticky">Nro Factura</th>
          <th scope="col" class="CP-sticky">Fiscal Factura</th>
          <th scope="col" class="CP-sticky">Serial Impresora</th>
          <th scope="col" class="CP-sticky">Corte Z</th>
          <th scope="col" class="CP-sticky">Caja Origen</th>
          <th scope="col" class="CP-sticky">Fecha Devolucion</td>
          <th scope="col" class="CP-sticky">Hora Devolucion</td>
          <th scope="col" class="CP-sticky">Numero Devolucion</td>
          <th scope="col" class="CP-sticky">Fiscal Devolucion</td>
          <th scope="col" class="CP-sticky">Serial Impresora</td>
          <th scope="col" class="CP-sticky">Dias</td>
          <th scope="col" class="CP-sticky">Caja</th>
          <th scope="col" class="CP-sticky">Unidades</th>
          <th scope="col" class="CP-sticky">SKU</th>
          <th scope="col" class="CP-sticky">Monto Bs</th>
          <th scope="col" class="CP-sticky bg-warning">Tasa</th>
          <th scope="col" class="CP-sticky">Monto $</th>              
        </tr>
      </thead>
      <tbody>';

    $TasaMercado = 
      DB::table('dolars')
      ->select('tasa')
      ->whereDate('fecha', '<=', $row6['fecha_devolucion_sin_formato'])
      ->orderBy('fecha','desc')
      ->take(1)->get();

    if( (!empty($TasaMercado[0])) ) {
      $TM = $TasaMercado[0]->tasa;
      $TasaMercadoNumber = ($TasaMercado[0]->tasa);
      $TasaMercado = number_format($TasaMercado[0]->tasa, 2, ",", ".");
    }
    else {
      $TM = 0.00;
    }

    $montoDolares = floatval($row6["monto_bs"]) / floatval($TasaMercadoNumber);
    $montoDolares = number_format($montoDolares,2,"," ,"." );
    $monto_bs = number_format($row6['monto_bs'],2,"," ,"." );

    $unidades = intval($row6['unidades']);

    echo '<tr>';
    echo '<td align="center">'.$row6['fecha_factura'].'</td>';
    echo '<td align="center">'.$row6['hora_factura'].'</td>';
    echo '<td align="center">'.$row6['nombre_cliente'].'</td>';
    echo '<td align="center">'.$row6['nro_factura'].'</td>';
    echo '<td align="center">'.$row6['fiscal_factura'].'</td>';
    echo '<td align="center">'.$row6['serial_impresora_origen'].'</td>';
    echo '<td align="center">'.$row6['corte_z'].'</td>';
    echo '<td align="center">'.$row6['caja_origen'].'</td>';
    echo '<td align="center">'.$row6['fecha_devolucion'].'</td>';
    echo '<td align="center">'.$row6['hora_devolucion'].'</td>';
    echo '<td align="center" class="CP-barrido">
        <a href="/reporte33?SEDE='.$_GET['SEDE'].'&numeroDevolucion='.$row6['numero_devolucion'].'" style="text-decoration: none; color: black;" target="_blank">'.$row6['numero_devolucion'].'</a>
      </td>';
    echo '<td align="center">'.$row6['fiscal_devolucion'].'</td>';
    echo '<td align="center">'.$row6['serial_impresora'].'</td>';
    echo '<td align="center">'.$row6['dias'].'</td>';
    echo '<td align="center">'.$row6['caja'].'</td>';
    echo '<td align="center">'.$unidades.'</td>';
    echo '<td align="center">'.$row6['sku'].'</td>';
    echo '<td align="center">'.$monto_bs.'</td>';
    echo '<td align="center">'.$TasaMercado.'</td>';
    echo '<td align="center">'.$montoDolares.'</td>';

    echo   
      '</tbody>
    </table>
    ';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
        <tr>  
          <th scope="col" class="CP-sticky">Codigo Articulo</th>
          <th scope="col" class="CP-sticky">Codigo Barra</th>
          <th scope="col" class="CP-sticky">Descripcion</th>
          <th scope="col" class="CP-sticky">Causa</th>
          <th scope="col" class="CP-sticky">Precio con IVA</th>
          <th scope="col" class="CP-sticky">Cantidad</th>
          <th scope="col" class="CP-sticky">Total Bs.</td>
          <th scope="col" class="CP-sticky">Precio en $</td>
          <th scope="col" class="CP-sticky">Total en $</td>            
        </tr>
      </thead>
      <tbody>
    ';
    $contador = 1;

    $totalSku = 0;
    $totalBs = 0;
    $totalUnidades = 0;
    $totalDs = 0;

    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $TasaMercado = 
        DB::table('dolars')
        ->select('tasa')
        ->whereDate('fecha', '<=', $row['fecha_devolucion'])
        ->orderBy('fecha','desc')
        ->take(1)->get();

      if( (!empty($TasaMercado[0])) ) {
        $TM = $TasaMercado[0]->tasa;
        $TasaMercadoNumber = ($TasaMercado[0]->tasa);
        $TasaMercado = ($TasaMercado[0]->tasa);
      }
      else {
        $TM = 0.00;
      }

      $codigo_articulo = $row['codigo_articulo'];
      $codigo_barra = $row['codigo_barra'];
      $descripcion = FG_Limpiar_Texto($row['descripcion']);
      $causa = $row['causa'];
      $precio_iva = number_format($row['precio_iva'], 2, ',', '.');
      $cantidad = intval($row['cantidad']);
      $total_bs = number_format($row['precio_iva'] * $cantidad, 2, ',', '.');
      $precio_ds = number_format($row['precio_iva'] / $TasaMercado, 2, ',', '.');
      $total_ds = number_format(($row['precio_iva'] * $cantidad) / $TasaMercado, 2, ',', '.');
      $id_articulo = $row['id_articulo'];

      echo '<tr>';
      echo '<td align="center">'.$codigo_articulo.'</td>';
      echo '<td align="center">'.$codigo_barra.'</td>';
      echo '<td align="center" class="CP-barrido">
          <a href="/reporte2?SEDE='.$_GET['SEDE'].'&Id='.$id_articulo.'" style="text-decoration: none; color: black;" target="_blank">'.$descripcion.'</a>
        </td>';
      echo '<td align="center">'.$causa.'</td>';
      echo '<td align="center">'.$precio_iva.'</td>';
      echo '<td align="center">'.$cantidad.'</td>';
      echo '<td align="center">'.$total_bs.'</td>';
      echo '<td align="center">'.$precio_ds.'</td>';
      echo '<td align="center">'.$total_ds.'</td>';
      echo '</tr>';
    }

    echo '
      </tbody>
    </table>';

    sqlsrv_close($conn);
  }

  /**********************************************************************************/
  /*
    TITULO: R33Q_Devoluciones_Clientes
    FUNCION: Ubicar el lista de devoluciones
    RETORNO: Lista de devoluciones
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R33Q_Devoluciones_Clientes($FInicial,$FFinal) {
    $sql = "
      SELECT
        (SELECT FORMAT(VenFactura.FechaDocumento, 'dd/MM/yyyy') FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS fecha_factura,
        (SELECT FORMAT(VenFactura.FechaDocumento, 'H:mm') FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS hora_factura,
        (SELECT CONCAT(Nombre, ' ', Apellido) FROM GenPersona WHERE id = ((SELECT GenPersonaId FROM VenCliente WHERE id = (SELECT VenFactura.VenClienteId FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId)))) AS nombre_cliente,
        (SELECT VenFactura.NumeroFactura FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS nro_factura,
        (SELECT VenFactura.VE_NumeroControlImp FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS fiscal_factura,
        (SELECT VenFactura.VE_SerialImp FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS serial_impresora_origen,
        (SELECT VenFactura.VE_CorteZImp FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS corte_z,
        (SELECT CodigoCaja FROM VenCaja where id = (SELECT VenCajaId FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId)) AS caja_origen,
        FORMAT(VenDevolucion.FechaDocumento, 'dd/MM/yyyy') AS fecha_devolucion,
        VenDevolucion.FechaDocumento AS fecha_devolucion_sin_formato,
        FORMAT(VenDevolucion.FechaDocumento, 'H:mm') AS hora_devolucion,
        VenDevolucion.ConsecutivoDevolucionSistema AS numero_devolucion,
        VenDevolucion.VE_NumeroControlDevolucionImp AS fiscal_devolucion,
        VenDevolucion.VE_SerialImp AS serial_impresora,
        DATEDIFF(DAY, (SELECT VenFactura.FechaDocumento FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId), VenDevolucion.FechaDocumento) AS dias,
        (SELECT VenCaja.CodigoCaja FROM VenCaja where id = VenDevolucion.VenCajaId) AS caja,
        (SELECT VenCausaOperacionDevolucion.DescripcionOperacion FROM VenCausaOperacionDevolucion WHERE id = (SELECT TOP 1 VenCausaOperacionId FROM VenDevolucionDetalle WHERE VenDevolucionDetalle.VenDevolucionId = VenDevolucion.Id)) AS causa,
        (SELECT SUM(VenDevolucionDetalle.Cantidad) FROM VenDevolucionDetalle WHERE VenDevolucionDetalle.VenDevolucionId = VenDevolucion.Id) AS unidades,
        (SELECT COUNT(VenDevolucionDetalle.Cantidad) FROM VenDevolucionDetalle WHERE VenDevolucionDetalle.VenDevolucionId = VenDevolucion.Id) AS sku,
        VenDevolucion.M_MontoTotalDevolucion AS monto_bs
      FROM VenDevolucion
      WHERE VenDevolucion.FechaDocumento BETWEEN '$FInicial' AND '$FFinal' AND VenDevolucion.EstadoDevolucion = 2
      ORDER BY numero_devolucion ASC
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R33Q_Devoluciones_Clientes_Id
    FUNCION: Ubica una devolucion buscando por numero de devolucion
    RETORNO: Lista de devoluciones
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R33Q_Devoluciones_Clientes_Id($numero_devolucion) {
    $sql = "
      SELECT
        (SELECT FORMAT(VenFactura.FechaDocumento, 'dd/MM/yyyy') FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS fecha_factura,
        (SELECT FORMAT(VenFactura.FechaDocumento, 'H:mm') FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS hora_factura,
        (SELECT CONCAT(Nombre, ' ', Apellido) FROM GenPersona WHERE id = ((SELECT GenPersonaId FROM VenCliente WHERE id = (SELECT VenFactura.VenClienteId FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId)))) AS nombre_cliente,
        (SELECT VenFactura.NumeroFactura FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS nro_factura,
        (SELECT VenFactura.VE_NumeroControlImp FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS fiscal_factura,
        (SELECT VenFactura.VE_SerialImp FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS serial_impresora_origen,
        (SELECT VenFactura.VE_CorteZImp FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId) AS corte_z,
        (SELECT CodigoCaja FROM VenCaja where id = (SELECT VenCajaId FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId)) AS caja_origen,
        FORMAT(VenDevolucion.FechaDocumento, 'dd/MM/yyyy') AS fecha_devolucion,
        VenDevolucion.FechaDocumento AS fecha_devolucion_sin_formato,
        FORMAT(VenDevolucion.FechaDocumento, 'H:mm') AS hora_devolucion,
        VenDevolucion.ConsecutivoDevolucionSistema AS numero_devolucion,
        VenDevolucion.VE_NumeroControlDevolucionImp AS fiscal_devolucion,
        VenDevolucion.VE_SerialImp AS serial_impresora,
        DATEDIFF(DAY, (SELECT VenFactura.FechaDocumento FROM VenFactura WHERE VenFactura.Id = VenDevolucion.VenFacturaId), VenDevolucion.FechaDocumento) AS dias,
        (SELECT VenCaja.CodigoCaja FROM VenCaja where id = VenDevolucion.VenCajaId) AS caja,
        (SELECT VenCausaOperacionDevolucion.DescripcionOperacion FROM VenCausaOperacionDevolucion WHERE id = (SELECT TOP 1 VenCausaOperacionId FROM VenDevolucionDetalle WHERE VenDevolucionDetalle.VenDevolucionId = VenDevolucion.Id)) AS causa,
        (SELECT SUM(VenDevolucionDetalle.Cantidad) FROM VenDevolucionDetalle WHERE VenDevolucionDetalle.VenDevolucionId = VenDevolucion.Id) AS unidades,
        (SELECT COUNT(VenDevolucionDetalle.Cantidad) FROM VenDevolucionDetalle WHERE VenDevolucionDetalle.VenDevolucionId = VenDevolucion.Id) AS sku,
        VenDevolucion.M_MontoTotalDevolucion AS monto_bs
      FROM VenDevolucion
      WHERE VenDevolucion.ConsecutivoDevolucionSistema = '$numero_devolucion'
      ORDER BY numero_devolucion ASC
    ";
    return $sql;
  }

  /*
    TITULO: R33Q_Devoluciones_Clientes
    FUNCION: Ubicar el lista de devoluciones
    RETORNO: Lista de devoluciones
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R33Q_Devoluciones_Clientes_Detalles($numeroDevolucion) {
    $sql = "
      SELECT
        (SELECT InvArticulo.Id FROM InvArticulo WHERE InvArticulo.Id = VenDevolucionDetalle.InvArticuloId) AS id_articulo,
        (SELECT InvArticulo.CodigoArticulo FROM InvArticulo WHERE InvArticulo.Id = VenDevolucionDetalle.InvArticuloId) AS codigo_articulo,
        (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = VenDevolucionDetalle.InvArticuloId AND InvCodigoBarra.EsPrincipal = 1) AS codigo_barra,
        (SELECT InvArticulo.DescripcionLarga FROM InvArticulo WHERE InvArticulo.Id = VenDevolucionDetalle.InvArticuloId) AS descripcion,
        (SELECT VenCausaOperacionDevolucion.DescripcionOperacion FROM VenCausaOperacionDevolucion WHERE id = VenDevolucionDetalle.VenCausaOperacionId) AS causa,
        VenDevolucionDetalle.PrecioBruto AS precio_iva,
        VenDevolucionDetalle.Cantidad AS cantidad,
        (SELECT VenDevolucion.FechaDocumento FROM VenDevolucion WHERE Id = VenDevolucionDetalle.VenDevolucionId) AS fecha_devolucion
      FROM
          VenDevolucionDetalle
      WHERE VenDevolucionId = (SELECT Id FROM VenDevolucion WHERE VenDevolucion.ConsecutivoDevolucionSistema = '$numeroDevolucion')
    ";
    return $sql;
  }
?>
