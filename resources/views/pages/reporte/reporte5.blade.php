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
@endsection


@section('scriptsFoot')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        function agregarTraslado(codigo_barra, sede, existencia, bandera = '') {
            cantidad = prompt('Ingrese la cantidad' + bandera + ': ');

            if (cantidad) {
                if (!parseInt(cantidad)) {
                    agregarTraslado(codigo_barra, sede, existencia, ' (sólo números enteros)');
                    return false;
                }

                if (parseInt(cantidad) <= 0) {
                    agregarTraslado(codigo_barra, sede, existencia, ' (número mayor a cero)');
                    return false;
                }

                if (parseInt(cantidad) > parseInt(existencia)) {
                    agregarTraslado(codigo_barra, sede, existencia, ' (número menor a existencia)');
                    return false;
                }

                cantidad = parseInt(cantidad);

                $.ajax({
                    type: 'POST',
                    url: '/trasladoRecibir',
                    data: {
                        codigo_barra: codigo_barra,
                        sede: sede,
                        cantidad: cantidad,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        toastr.success('Articulo agregado a traslado');
                    }
                })
            }
        }

        function confirmarEliminacion(url) {
            respuesta = confirm('¿Está seguro que desea eliminar este artículo?');

            if (respuesta) {
                $.ajax({
                    type: 'GET',
                    url: url,
                    success: function (response) {
                        console.log(response);
                        window.location.href = window.location.href;
                    }
                })
            }
        }

        function mostrar_ocultar(that, elemento) {
            if (that.checked) {
                return $('.' + elemento).show();
            }

            return $('.' + elemento).hide();
        }

        campos = ['codigo', 'codigo_barra', 'descripcion', 'tipo', 'existencia', 'dolarizado', 'gravado', 'clasificacion', 'unidades_vendidas', 'total_venta', 'ultima_venta', 'dias_falla', 'ultimo_lote', 'ultima_compra', 'ultimo_proveedor', 'sede1', 'sede2', 'sede3', 'sede4', 'descripcion_sede1', 'descripcion_sede2', 'descripcion_sede3', 'descripcion_sede4' ,'traslado_transito'];

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
		Productos en falla
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

    R5_Productos_Falla($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Productos en falla');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
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
    TITULO: R5_Productos_Falla
    FUNCION: Arma una lista de productos en falla
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R5_Productos_Falla($SedeConnection,$FInicial,$FFinal){
    session()->forget('traslado');

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $conectividad_ftn = FG_Validar_Conectividad('FTN');
    $conectividad_fau = FG_Validar_Conectividad('FAU');
    $conectividad_fll = FG_Validar_Conectividad('FLL');
    $conectividad_fsm = FG_Validar_Conectividad('FSM');
    $conectividad_fec = FG_Validar_Conectividad('FEC');

    $connFAU = FG_Conectar_Smartpharma('FAU');
    $connFTN = FG_Conectar_Smartpharma('FTN');
    $connFLL = FG_Conectar_Smartpharma('FLL');
    $connFSM = FG_Conectar_Smartpharma('FSM');
    $connFEC = FG_Conectar_Smartpharma('FEC');

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    $sql5 = R5Q_Productos_Falla($FInicial,$FFinal);
    $result = sqlsrv_query($conn,$sql5);

    echo '
    <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
      <div class="input-group-prepend">
        <span class="input-group-text purple lighten-3" id="basic-text1">
          <i class="fas fa-search text-white"
            aria-hidden="true"></i>
        </span>
      </div>
      <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTableWithoutFooter()" autofocus="autofocus">
    </div>
    <br/>
    ';
    echo '<h6 align="center">Periodo desde el '.$FInicialImp.' al '.$FFinalImp.'</h6>';
    echo '<h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Ocultar o mostrar columnas<a></h6>';

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
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'codigo\')" name="codigo" checked>
                    Código
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'codigo_barra\')" name="codigo_barra" checked>
                    Código de barra
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'descripcion\')" name="descripcion" checked>
                    Descripción
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'tipo\')" name="tipo" checked>
                    Tipo
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'existencia\')" name="existencia" checked>
                    Existencia
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'dolarizado\')" name="dolarizado" checked>
                    Dolarizado
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'gravado\')" name="gravado" checked>
                    Gravado
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'clasificacion\')" name="clasificacion" checked>
                    Clasificacion
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'unidades_vendidas\')" name="unidades_vendidas" checked>
                    Unidades vendidas
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'total_venta\')" name="total_venta" checked>
                    Total de venta
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultima_venta\')" name="ultima_venta" checked>
                    Última venta
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'dias_falla\')" name="dias_falla" checked>
                    Días en falla
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultimo_lote\')" name="ultimo_lote" checked>
                    Último lote
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultima_compra\')" name="ultima_compra" checked>
                    Última compra
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultimo_proveedor\')" name="ultimo_proveedor" checked>
                    Último proveedor
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'traslado_transito\')" name="traslado_transito" checked>
                    Traslado en transito
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'sede1\')" name="sede1" checked>
                    Existencia Sede 1
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'sede2\')" name="sede2" checked>
                    Existencia Sede 2
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'sede3\')" name="sede3" checked>
                    Existencia Sede 3
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'sede4\')" name="sede4" checked>
                    Existencia Sede 4
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'descripcion_sede1\')" name="sede1" checked>
                    Descripción sede 1
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'descripcion_sede2\')" name="sede2" checked>
                    Descripción sede 2
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'descripcion_sede3\')" name="sede3" checked>
                    Descripción sede 3
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'descripcion_sede4\')" name="sede4" checked>
                    Descripción sede 4
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

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
        <tr>
          <th scope="col" class="CP-sticky">#</th>
          <th scope="col" class="codigo CP-sticky">Codigo</th>
          <th scope="col" class="codigo_barra CP-sticky">Codigo de Barra</th>
          <th scope="col" class="descripcion CP-sticky">Descripcion</th>
          <th scope="col" class="tipo CP-sticky">Tipo</th>
          <th scope="col" class="existencia CP-sticky">Existencia</th>
          <th scope="col" class="dolarizado CP-sticky">Dolarizado?</td>
          <th scope="col" class="gravado CP-sticky">Gravado?</td>
          <th scope="col" class="clasificacion CP-sticky">Clasificacion</td>
          <th scope="col" class="unidades_vendidas CP-sticky">Unidades vendidas</th>
          <th scope="col" class="total_venta CP-sticky">Total de Venta '.SigVe.'</th>
          <th scope="col" class="ultima_venta CP-sticky">Ultima Venta</th>
          <th scope="col" class="dias_falla CP-sticky">Dias en Falla</th>
          <th scope="col" class="ultimo_lote CP-sticky">Ultimo Lote</th>
          <th scope="col" class="ultima_compra CP-sticky">Ultima Compra</th>
          <th scope="col" class="ultimo_proveedor CP-sticky">Ultimo Proveedor</th>
          <th scope="col" class="traslado_transito CP-sticky bg-warning">Traslado en transito</th>';

              if (isset($_GET['SEDE']) & ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
                echo '<th scope="col" class="descripcion_sede1 sede1 CP-sticky">Descripción FTN</th>
                    <th scope="col" class="sede1 CP-sticky">Existencia FTN</td>
                    <th scope="col" class="descripcion_sede2 sede2 CP-sticky">Descripción FLL</th>
                    <th scope="col" class="sede2 CP-sticky">Existencia FLL</td>
                    <th scope="col" class="descripcion_sede3 sede3 CP-sticky">Descripción FSM</th>
                    <th scope="col" class="sede3 CP-sticky">Existencia FSM</td>
                    <th scope="col" class="descripcion_sede4 sede4 CP-sticky">Descripción FEC</th>
                    <th scope="col" class="sede4 CP-sticky">Existencia FEC</td>
                ';
              }

              if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FTN') {
                echo '<th scope="col" class="descripcion_sede1 sede1 CP-sticky">Descripción FAU</th>
                    <th scope="col" class="sede1 CP-sticky">Existencia FAU</td>
                    <th scope="col" class="descripcion_sede2 sede2 CP-sticky">Descripción FLL</th>
                    <th scope="col" class="sede2 CP-sticky">Existencia FLL</td>
                    <th scope="col" class="descripcion_sede3 sede3 CP-sticky">Descripción FSM</th>
                    <th scope="col" class="sede3 CP-sticky">Existencia FSM</td>
                        <th scope="col" class="descripcion_sede4 sede4 CP-sticky">Descripción FEC</th>
                    <th scope="col" class="sede4 CP-sticky">Existencia FEC</td>
                ';
              }

              if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FLL') {
                echo '<th scope="col" class="descripcion_sede1 sede1 CP-sticky">Descripción FTN</th>
                    <th scope="col" class="sede1 CP-sticky">Existencia FTN</td>
                    <th scope="col" class="descripcion_sede2 sede2 CP-sticky">Descripción FAU</th>
                    <th scope="col" class="sede2 CP-sticky">Existencia FAU</td>
                    <th scope="col" class="descripcion_sede3 sede3 CP-sticky">Descripción FSM</th>
                    <th scope="col" class="sede3 CP-sticky">Existencia FSM</td>
                    <th scope="col" class="descripcion_sede4 sede4 CP-sticky">Descripción FEC</th>
                    <th scope="col" class="sede4 CP-sticky">Existencia FEC</td>
                ';
              }

              if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FSM') {
                echo '<th scope="col" class="descripcion_sede1 CP-sticky">Descripción FTN</th>
                    <th scope="col" class="sede1 CP-sticky">Existencia FTN</td>
                    <th scope="col" class="descripcion_sede2 sede2 CP-sticky">Descripción FAU</th>
                    <th scope="col" class="sede2 CP-sticky">Existencia FAU</td>
                    <th scope="col" class="descripcion_sede3 sede3 CP-sticky">Descripción FLL</th>
                    <th scope="col" class="sede3 CP-sticky">Existencia FLL</td>
                    <th scope="col" class="descripcion_sede4 sede4 CP-sticky">Descripción FEC</th>
                    <th scope="col" class="sede4 CP-sticky">Existencia FEC</td>
                ';
              }

              if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FEC') {
                echo '<th scope="col" class="descripcion_sede1 CP-sticky">Descripción FTN</th>
                    <th scope="col" class="sede1 CP-sticky">Existencia FTN</td>
                    <th scope="col" class="descripcion_sede2 sede2 CP-sticky">Descripción FAU</th>
                    <th scope="col" class="sede2 CP-sticky">Existencia FAU</td>
                    <th scope="col" class="descripcion_sede3 sede3 CP-sticky">Descripción FLL</th>
                    <th scope="col" class="sede3 CP-sticky">Existencia FLL</td>
                    <th scope="col" class="descripcion_sede4 sede4 CP-sticky">Descripción FSM</th>
                    <th scope="col" class="sede4 CP-sticky">Existencia FSM</td>
                ';
              }

              echo '<th scope="col" class="CP-sticky" colspan="4">Acciones traslado</th>';

    echo '</tr>
      </thead>
      <tbody>
    ';
    $contador = 1;

    $traslado = Traslado_Transito();

    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["InvArticuloId"];
      $UnidadesVendidas = intval($row["TotalUnidadesVendidas"]);
      $TotalVenta = $row["TotalVenta"];

      $sql1 = R5Q_Detalle_Articulo($IdArticulo);
      $result1 = sqlsrv_query($conn,$sql1);
      $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

      $CodigoArticulo = $row1["CodigoInterno"];
      $CodigoBarra = $row1["CodigoBarra"];
      $Descripcion = FG_Limpiar_Texto($row1["Descripcion"]);
      $Existencia = $row1["Existencia"];
      $Tipo = FG_Tipo_Producto($row1["Tipo"]);
      $UltimaVenta = $row1["UltimaVenta"];
      $UltimoProveedorId =  $row1["UltimoProveedorID"];
      $UltimoProveedor =  FG_Limpiar_Texto($row1["UltimoProveedorNombre"]);
      $DiasEnFalla = $row1["TiempoSinVenta"];
      $Dolarizado = $row1["Dolarizado"];
      $IsIVA = $row1["Impuesto"];
      $UltimoLote = $row1["UltimoLote"];
      $UltimaCompra = $row1["UltimaCompra"];

      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
      $Gravado = FG_Producto_Gravado($IsIVA);

      $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
      $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
      $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
      $clasificacion = $RowCPharma['clasificacion'];
      $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td class="codigo" align="left">'.$CodigoArticulo.'</td>';
      echo '<td class="codigo_barra" align="center">'.$CodigoBarra.'</td>';
      echo
      '<td align="left" class="descripcion CP-barrido">
      <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';
      echo '<td class="tipo" align="center">'.$Tipo.'</td>';
      echo '<td class="existencia" align="center">'.intval($Existencia).'</td>';
      echo '<td class="dolarizado" align="center">'.$Dolarizado.'</td>';
      echo '<td class="gravado" align="center">'.$Gravado.'</td>';
      echo '<td class="clasificacion" align="center">'.$clasificacion.'</td>';
      echo
      '<td align="center" class="unidades_vendidas CP-barrido">
      <a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImp.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
        .intval($UnidadesVendidas).
      '</a>
      </td>';
      echo '<td class="total_venta" align="center">'.number_format($TotalVenta,2,"," ,"." ).'</td>';

      if(($UltimaVenta)){
        echo '<td class="ultima_venta" align="center">'.$UltimaVenta->format('d-m-Y').'</td>';
      }
      else{
        echo '<td class="ultima_venta" align="center"> - </td>';
      }

      echo '<td class="dias_falla" align="center">'.intval($DiasEnFalla).'</td>';

      if(!is_null($UltimoLote)){
        echo '<td class="ultimo_lote" align="center">'.$UltimoLote->format('d-m-Y').'</td>';
      }
      else{
        echo '<td class="ultimo_lote" align="center"> - </td>';
      }

      if(!is_null($UltimaCompra)){
        echo '<td class="ultima_compra" align="center" class="bg-warning">'.$UltimaCompra->format('d-m-Y').'</td>';
      }
      else{
        echo '<td class="ultima_compra" align="center" class="bg-warning"> - </td>';
      }

      echo
      '<td align="left" class="ultimo_proveedor CP-barrido">
      <a href="/reporte7?Nombre='.$UltimoProveedor.'&Id='.$UltimoProveedorId.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
        .$UltimoProveedor.
      '</a>
      </td>';

      $transito = in_array($CodigoBarra, $traslado) ? 'Si' : 'No';

      echo '<td class="text-center traslado_transito bg-warning">'.$transito.'</td>';

      if (isset($_GET['SEDE']) & ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
        if ($conectividad_ftn == 1) {
          $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
          $result3 = sqlsrv_query($connFTN,$sql3);

          $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

          $descripcion_ftn = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
          $existencia_ftn = ($row3['existencia']) ? intval($row3['existencia']) : '-';
        }

        if ($conectividad_fll == 1) {
          $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
          $result3 = sqlsrv_query($connFLL,$sql3);
          $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

          $descripcion_fll = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
          $existencia_fll = ($row3['existencia']) ? intval($row3['existencia']) : '-';
        }

        if ($conectividad_fsm == 1) {
          $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
          $result3 = sqlsrv_query($connFSM,$sql3);
          $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

          $descripcion_fsm = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
          $existencia_fsm = ($row3['existencia']) ? intval($row3['existencia']) : '-';
        }

        if ($conectividad_fec == 1) {
          $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
          $result3 = sqlsrv_query($connFEC,$sql3);
          $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

          $descripcion_fec = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
          $existencia_fec = ($row3['existencia']) ? intval($row3['existencia']) : '-';
        }
      }

      if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FTN') {
        if ($conectividad_fau == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFAU,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fau = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fau = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }

          if ($conectividad_fll == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFLL,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fll = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fll = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }

          if ($conectividad_fsm == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFSM,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fsm = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fsm = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }

          if ($conectividad_fec == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFEC,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fec = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fec = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }
      }

      if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FLL') {
         if ($conectividad_ftn == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFTN,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_ftn = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_ftn = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }

          if ($conectividad_fau == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFAU,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fau = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fau = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }

          if ($conectividad_fsm == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFSM,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fsm = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fsm = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }

          if ($conectividad_fec == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFEC,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fec = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fec = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }
      }

      if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FSM') {
         if ($conectividad_ftn == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFTN,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_ftn = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_ftn = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }

          if ($conectividad_fau == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFAU,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fau = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fau = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }

          if ($conectividad_fll == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFLL,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fll = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fll = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }

          if ($conectividad_fec == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFEC,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fec = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fec = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }
      }

      if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FEC') {
         if ($conectividad_ftn == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFTN,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_ftn = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_ftn = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }

          if ($conectividad_fau == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFAU,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fau = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fau = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }

          if ($conectividad_fll == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFLL,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fll = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fll = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }

          if ($conectividad_fsm == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFSM,$sql3);
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fsm = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fsm = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }
      }

      if (isset($_GET['SEDE']) & ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
            if ($conectividad_ftn == 1) {
                echo '<td class="descripcion_sede1 sede1" align="center">'.$descripcion_ftn.'</td>
                      <td class="sede1" align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td class="descripcion_sede1 sede1">-</td>';
                echo '<td class="sede1">-</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td class="descripcion_sede2 sede2" align="center">'.$descripcion_fll.'</td>
                      <td class="sede2" align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td class="descripcion_sede2 sede2">-</td>';
                echo '<td class="sede2">-</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td class="descripcion_sede3 sede3" align="center">'.$descripcion_fsm.'</td>
                      <td class="sede3" align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td class="descripcion_sede3 sede3">-</td>';
                echo '<td class="sede3">-</td>';
            }

            if ($conectividad_fec == 1) {
                echo '<td class="descripcion_sede4 sede4" align="center">'.$descripcion_fec.'</td>
                      <td class="sede4" align="center">'.$existencia_fec.'</td>';
            } else {
                echo '<td class="descripcion_sede4 sede4">-</td>';
                echo '<td class="sede4">-</td>';
            }
        }

        if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FTN') {
            if ($conectividad_fau == 1) {
                echo '<td class="descripcion_sede1 sede1" align="center">'.$descripcion_fau.'</td>
                      <td class="sede1" align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td class="descripcion_sede1 sede1">-</td>';
                echo '<td class="sede1">-</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td class="descripcion_sede2 sede2" align="center">'.$descripcion_fll.'</td>
                      <td class="sede2" align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td class="descripcion_sede2 sede2">-</td>';
                echo '<td class="sede2">-</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td class="descripcion_sede3" align="center">'.$descripcion_fsm.'</td>
                      <td class="sede3" align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td class="descripcion_sede3">-</td>';
                echo '<td class="sede3">-</td>';
            }

            if ($conectividad_fec == 1) {
                echo '<td class="descripcion_sede4 sede4" align="center">'.$descripcion_fec.'</td>
                      <td class="sede4" align="center">'.$existencia_fec.'</td>';
            } else {
                echo '<td class="descripcion_sede4 sede3">-</td>';
                echo '<td class="sede4">-</td>';
            }
        }

        if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FLL') {
            if ($conectividad_ftn == 1) {
                echo '<td class="descripcion_sede1 sede1" align="center">'.$descripcion_ftn.'</td>
                      <td class="sede1" align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td class="descripcion_sede1 sede1">-</td>';
                echo '<td class="sede1">-</td>';
            }

            if ($conectividad_fau == 1) {
                echo '<td class="descripcion_sede2 sede2" align="center">'.$descripcion_fau.'</td>
                      <td class="sede2" align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td class="descripcion_sede2 sede2">-</td>';
                echo '<td class="sede2">-</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td class="descripcion_sede3 sede3" align="center">'.$descripcion_fsm.'</td>
                      <td class="sede3" align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td class="descripcion_sede3 sede3">-</td>';
                echo '<td class="sede3">-</td>';
            }

            if ($conectividad_fec == 1) {
                echo '<td class="descripcion_sede4 sede4" align="center">'.$descripcion_fec.'</td>
                      <td class="sede4" align="center">'.$existencia_fec.'</td>';
            } else {
                echo '<td class="descripcion_sede4 sede4">-</td>';
                echo '<td class="sede4">-</td>';
            }
        }

        if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FSM') {
            if ($conectividad_ftn == 1) {
                echo '<td class="descripcion_sede1 sede1" align="center">'.$descripcion_ftn.'</td>
                      <td class="sede1" align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td class="descripcion_sede1 sede1">-</td>';
                echo '<td class="sede1">-</td>';
            }

            if ($conectividad_fau == 1) {
                echo '<td class="descripcion_sede2 sede2" align="center">'.$descripcion_fau.'</td>
                      <td class="sede2" align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td class="descripcion_sede2 sede2">-</td>';
                echo '<td class="sede2">-</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td class="descripcion_sede3 sede3" align="center">'.$descripcion_fll.'</td>
                      <td class="sede3" align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td class="descripcion_sede3 sede3">-</td>';
                echo '<td class="sede3">-</td>';
            }

            if ($conectividad_fec == 1) {
                echo '<td class="descripcion_sede4 sede4" align="center">'.$descripcion_fec.'</td>
                      <td class="sede4" align="center">'.$existencia_fec.'</td>';
            } else {
                echo '<td class="descripcion_sede4 sede4">-</td>';
                echo '<td class="sede4">-</td>';
            }
        }

        if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FEC') {
            if ($conectividad_ftn == 1) {
                echo '<td class="descripcion_sede1 sede1" align="center">'.$descripcion_ftn.'</td>
                      <td class="sede1" align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td class="descripcion_sede1 sede1">-</td>';
                echo '<td class="sede1">-</td>';
            }

            if ($conectividad_fau == 1) {
                echo '<td class="descripcion_sede2 sede2" align="center">'.$descripcion_fau.'</td>
                      <td class="sede2" align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td class="descripcion_sede2 sede2">-</td>';
                echo '<td class="sede2">-</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td class="descripcion_sede3 sede3" align="center">'.$descripcion_fll.'</td>
                      <td class="sede3" align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td class="descripcion_sede3 sede3">-</td>';
                echo '<td class="sede3">-</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td class="descripcion_sede4 sede4" align="center">'.$descripcion_fsm.'</td>
                      <td class="sede4" align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td class="descripcion_sede4 sede4">-</td>';
                echo '<td class="sede4">-</td>';
            }
        }


        // Botones de agregar a traslado

        if (isset($_GET['SEDE']) & ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
            if ($conectividad_ftn == 1 && $descripcion_ftn != '-' && $existencia_ftn > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FTN\', \'' . $existencia_ftn . '\')" class="btn btn-outline-info btn-sm">Agregar FTN</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fll == 1 && $descripcion_fll != '-' && $existencia_fll > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FLL\', \'' . $existencia_fll . '\')" class="btn btn-outline-info btn-sm">Agregar FLL</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fsm == 1 && $descripcion_fsm != '-' && $existencia_fsm > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FSM\', \'' . $existencia_fsm . '\')" class="btn btn-outline-info btn-sm">Agregar FSM</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fec == 1 && $descripcion_fec != '-' && $existencia_fec > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FEC\', \'' . $existencia_fsm . '\')" class="btn btn-outline-info btn-sm">Agregar FEC</td>';
            } else {
                echo '<td>-</td>';
            }
        }

        if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FTN') {
            if ($conectividad_fau == 1 && $descripcion_fau != '-' && $existencia_fau > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FAU\', \'' . $existencia_fau . '\')" class="btn btn-outline-info btn-sm">Agregar FAU</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fll == 1 && $descripcion_fll != '-' && $existencia_fll > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FLL\', \'' . $existencia_fll . '\')" class="btn btn-outline-info btn-sm">Agregar FLL</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fsm == 1 && $descripcion_fsm != '-' && $existencia_fsm > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FSM\', \'' . $existencia_fsm . '\')" class="btn btn-outline-info btn-sm">Agregar FSM</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fec == 1 && $descripcion_fec != '-' && $existencia_fec > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FEC\', \'' . $existencia_fsm . '\')" class="btn btn-outline-info btn-sm">Agregar FEC</td>';
            } else {
                echo '<td>-</td>';
            }
        }

        if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FLL') {
            if ($conectividad_ftn == 1 && $descripcion_ftn != '-' && $existencia_ftn > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FTN\', \'' . $existencia_ftn . '\')" class="btn btn-outline-info btn-sm">Agregar FTN</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fau == 1 && $descripcion_fau != '-' && $existencia_fau > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FAU\', \'' . $existencia_fau . '\')" class="btn btn-outline-info btn-sm">Agregar FAU</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fsm == 1 && $descripcion_fsm != '-' && $existencia_fsm > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FSM\', \'' . $existencia_fsm . '\')" class="btn btn-outline-info btn-sm">Agregar FSM</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fec == 1 && $descripcion_fec != '-' && $existencia_fec > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FEC\', \'' . $existencia_fsm . '\')" class="btn btn-outline-info btn-sm">Agregar FEC</td>';
            } else {
                echo '<td>-</td>';
            }
        }

        if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FSM') {
            if ($conectividad_ftn == 1 && $descripcion_ftn != '-' && $existencia_ftn > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FTN\', \'' . $existencia_ftn . '\')" class="btn btn-outline-info btn-sm">Agregar FTN</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fau == 1 && $descripcion_fau != '-' && $existencia_fau > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FAU\', \'' . $existencia_fau . '\')" class="btn btn-outline-info btn-sm">Agregar FAU</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fll == 1 && $descripcion_fll != '-' && $existencia_fll > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FLL\', \'' . $existencia_fll . '\')" class="btn btn-outline-info btn-sm">Agregar FLL</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fec == 1 && $descripcion_fec != '-' && $existencia_fec > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FEC\', \'' . $existencia_fsm . '\')" class="btn btn-outline-info btn-sm">Agregar FEC</td>';
            } else {
                echo '<td>-</td>';
            }
        }

        if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FEC') {
            if ($conectividad_ftn == 1 && $descripcion_ftn != '-' && $existencia_ftn > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FTN\', \'' . $existencia_ftn . '\')" class="btn btn-outline-info btn-sm">Agregar FTN</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fau == 1 && $descripcion_fau != '-' && $existencia_fau > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FAU\', \'' . $existencia_fau . '\')" class="btn btn-outline-info btn-sm">Agregar FAU</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fll == 1 && $descripcion_fll != '-' && $existencia_fll > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FLL\', \'' . $existencia_fll . '\')" class="btn btn-outline-info btn-sm">Agregar FLL</td>';
            } else {
                echo '<td>-</td>';
            }

            if ($conectividad_fsm == 1 && $descripcion_fsm != '-' && $existencia_fsm > 0) {
                echo '<td align="center"><button type="button" onclick="agregarTraslado(\'' . $CodigoBarra . '\', \'FSM\', \'' . $existencia_fsm . '\')" class="btn btn-outline-info btn-sm">Agregar FSM</td>';
            } else {
                echo '<td>-</td>';
            }
        }

      echo '</tr>';
      $contador++;
    }

     echo '
      </tbody>';

    echo '<tfoot>';
    echo '<tr>';
    echo '<td colspan="22">';
    echo '<td colspan="3" class="nowrap"><a href="/trasladoRecibir" target="_blank" class="btn btn-outline-info btn-sm">Ver soporte</a>';
    echo '<button type="button" onclick="confirmarEliminacion(\'/trasladoRecibir/limpiar\')" class="btn btn-outline-danger btn-sm">Limpiar todo</button></td>';
    echo '</tr>';
    echo '</tfoot>';

    echo '</table>';

    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R5Q_Productos_Falla
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
    DESAROLLADO POR: SERGIO COVA
  */
  function R5Q_Productos_Falla($FInicial,$FFinal) {
    $sql = "
      SELECT
    -- Id Articulo
      VenFacturaDetalle.InvArticuloId,
    --Veces Vendidas (En Rango)
      ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesVendidas,
    --Unidades Vendidas (En Rango)
      (ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesVendidas,
    --Veces Devueltas (En Rango)
      ISNULL((SELECT
      ISNULL(COUNT(*),CAST(0 AS INT))
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      GROUP BY VenDevolucionDetalle.InvArticuloId
      ),CAST(0 AS INT)) AS VecesDevueltas,
    --Unidades Devueltas (En Rango)
      ISNULL((SELECT
      (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      GROUP BY VenDevolucionDetalle.InvArticuloId
      ),CAST(0 AS INT)) AS UnidadesDevueltas,
    --Total Veces Vendidas (En Rango)
      ((ISNULL(COUNT(*),CAST(0 AS INT)))
      -
      (ISNULL((SELECT
      ISNULL(COUNT(*),CAST(0 AS INT))
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      GROUP BY VenDevolucionDetalle.InvArticuloId
      ),CAST(0 AS INT)))) AS TotalVecesVendidas,
    --Total Unidades Vendidas (En Rango)
      (((ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)))
      -
      (ISNULL((SELECT
      (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      GROUP BY VenDevolucionDetalle.InvArticuloId
      ),CAST(0 AS INT)))) AS TotalUnidadesVendidas,
    --Veces Conpradas (En Rango)
      ISNULL((SELECT
      ISNULL(COUNT(*),CAST(0 AS INT))
      FROM ComFacturaDetalle
      INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
      WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
      GROUP BY ComFacturaDetalle.InvArticuloId
      ),CAST(0 AS INT)) AS VecesCompradas,
    --Unidades Conpradas (En Rango)
      ISNULL((SELECT
      (ROUND(CAST(SUM(ComFacturaDetalle.CantidadFacturada) AS DECIMAL(38,0)),2,0))
      FROM ComFacturaDetalle
      INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
      WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
      GROUP BY ComFacturaDetalle.InvArticuloId
      ),CAST(0 AS INT)) AS UnidadesCompradas,
    --Veces Reclamadas (En Rango)
      ISNULL((SELECT
      ISNULL(COUNT(*),CAST(0 AS INT))
      FROM ComReclamoDetalle
      INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
      WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
      GROUP BY ComReclamoDetalle.InvArticuloId
      ),CAST(0 AS INT)) AS VecesReclamadas,
    --Unidades Reclamadas (En Rango)
      ISNULL((SELECT
      (ROUND(CAST(SUM(ComReclamoDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
      FROM ComReclamoDetalle
      INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
      WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
      GROUP BY ComReclamoDetalle.InvArticuloId
      ),CAST(0 AS INT)) AS UnidadesReclamadas,
    --Total Veces Compradas (En Rango)
      ((ISNULL((SELECT
      ISNULL(COUNT(*),CAST(0 AS INT))
      FROM ComFacturaDetalle
      INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
      WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
      GROUP BY ComFacturaDetalle.InvArticuloId
      ),CAST(0 AS INT)))
      -
      (ISNULL((SELECT
      ISNULL(COUNT(*),CAST(0 AS INT))
      FROM ComReclamoDetalle
      INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
      WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
      GROUP BY ComReclamoDetalle.InvArticuloId
      ),CAST(0 AS INT)))) AS TotalVecesCompradas,
    --Total de Unidades Compradas (En Rango)
      ((ISNULL((SELECT
      (ROUND(CAST(SUM(ComFacturaDetalle.CantidadFacturada) AS DECIMAL(38,0)),2,0))
      FROM ComFacturaDetalle
      INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
      WHERE ComFacturaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
      GROUP BY ComFacturaDetalle.InvArticuloId
      ),CAST(0 AS INT)))
      -
      (ISNULL((SELECT
      (ROUND(CAST(SUM(ComReclamoDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
      FROM ComReclamoDetalle
      INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
      WHERE ComReclamoDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
      AND(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
      GROUP BY ComReclamoDetalle.InvArticuloId
      ),CAST(0 AS INT)))) AS TotalUnidadesCompradas,
    -- SubTotal Venta (En Rango)
      ISNULL((SELECT
      (ROUND(CAST(SUM (VenVentaDetalle.PrecioBruto * VenVentaDetalle.Cantidad) AS DECIMAL(38,2)),2,0))
      FROM VenVentaDetalle
      INNER JOIN VenVenta ON VenVenta.Id = VenVentaDetalle.VenVentaId
      WHERE (VenVenta.FechaDocumentoVenta > '$FInicial' AND VenVenta.FechaDocumentoVenta < '$FFinal')
      AND VenVentaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)) AS SubTotalVenta,
    --SubTotal Devolucion (En Rango)
      ISNULL((SELECT
      (ROUND(CAST(SUM (VenDevolucionDetalle.PrecioBruto * VenDevolucionDetalle.Cantidad) AS DECIMAL(38,2)),2,0)) as SubTotalDevolucion
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)) as SubTotalDevolucion,
    --TotalVenta (En Rango)
      ((ISNULL((SELECT
      (ROUND(CAST(SUM (VenVentaDetalle.PrecioBruto * VenVentaDetalle.Cantidad) AS DECIMAL(38,2)),2,0))
      FROM VenVentaDetalle
      INNER JOIN VenVenta ON VenVenta.Id = VenVentaDetalle.VenVentaId
      WHERE (VenVenta.FechaDocumentoVenta > '$FInicial' AND VenVenta.FechaDocumentoVenta < '$FFinal')
      AND VenVentaDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)))
      -
      (ISNULL((SELECT
      (ROUND(CAST(SUM (VenDevolucionDetalle.PrecioBruto * VenDevolucionDetalle.Cantidad) AS DECIMAL(38,2)),2,0)) as SubTotalDevolucion
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
      AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)))) AS TotalVenta
    --Tabla Principal
      FROM VenFacturaDetalle
    --Joins
      INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
    --Condicionales
      WHERE
      (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
      AND (--Existencia (Segun el almacen del filtro)
      (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
      FROM InvLoteAlmacen
      WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
      AND (InvLoteAlmacen.InvArticuloId = VenFacturaDetalle.InvArticuloId)) AS DECIMAL(38,0)),2,0))=0)
    --Agrupamientos
      GROUP BY VenFacturaDetalle.InvArticuloId
    --Ordenamientos
      ORDER BY TotalUnidadesVendidas DESC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R5Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R5Q_Detalle_Articulo($IdArticulo) {
    $sql = "
      SELECT
    --Id Articulo
      InvArticulo.Id AS IdArticulo,
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
    --Utilidad (Utilidad del articulo, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
      ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
          FROM VenCondicionVenta
          WHERE VenCondicionVenta.Id = (
            SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id
            FROM VenCondicionVenta_VenCondicionVentaArticulo
            WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS Utilidad,
    --Precio Troquel Almacen 1
      (ROUND(CAST((SELECT TOP 1
      InvLote.M_PrecioTroquelado
      FROM InvLoteAlmacen
      INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
      WHERE(InvLoteAlmacen.InvAlmacenId = '1')
      AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
      AND (InvLoteAlmacen.Existencia>0)
      ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,4)),4,0)) AS TroquelAlmacen1,
    --Precio Troquel Almacen 2
      (ROUND(CAST((SELECT TOP 1
      InvLote.M_PrecioTroquelado
      FROM InvLoteAlmacen
      INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
      WHERE(InvLoteAlmacen.InvAlmacenId = '2')
      AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
      AND (InvLoteAlmacen.Existencia>0)
      ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen2,
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
      ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre,
    -- Ultima Compra (Fecha de ultima compra)
      (SELECT TOP 1
      CONVERT(DATE,ComFactura.FechaRegistro)
      FROM ComFacturaDetalle
      INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
      INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
      WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
      ORDER BY ComFactura.FechaRegistro DESC) AS  UltimaCompra
    --Tabla principal
      FROM InvArticulo
    --Joins
      LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
      LEFT JOIN InvArticuloAtributo ON InvArticuloAtributo.InvArticuloId = InvArticulo.Id
      LEFT JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId
    --Condicionales
      WHERE InvArticulo.Id = '$IdArticulo'
    --Agrupamientos
      GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra
    --Ordanamiento
      ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }

  /*
    TITULO: R14_Q_Descripcion_Existencia_Articulo
    FUNCION: Query que genera descripcion y existencia de articulo
    RETORNO: Descripcion/existencia de articulo
    DESAROLLADO POR: NISA DELGADO
  */
  function R14_Q_Descripcion_Existencia_Articulo($CodigoBarra)
  {

    $CodigoBarra = str_replace("'", "''", $CodigoBarra);

    $sql = "
      SELECT
        InvArticulo.DescripcionLarga AS descripcion,
        (SELECT SUM(Existencia) FROM InvLoteAlmacen WHERE (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) AND InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AS existencia
      FROM
      InvArticulo
      WHERE InvArticulo.Id = (SELECT InvCodigoBarra.InvArticuloId FROM InvCodigoBarra WHERE InvCodigoBarra.CodigoBarra = '$CodigoBarra' AND InvCodigoBarra.EsPrincipal = 1)
    ";

    return $sql;
  }
?>
