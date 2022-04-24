@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

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

        campos = ['codigo', 'codigo_barra', 'descripcion', 'precio', 'existencia', 'unidades_vendidas', 'dias_restantes', 'ultimo_proveedor', 'sede1', 'sede2', 'sede3', 'traslado_transito'];

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
    Productos en Caida
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

  R14_Productos_EnCaida($_GET['SEDE']);
  FG_Guardar_Auditoria('CONSULTAR','REPORTE','Productos en Caida');

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
  function R14_Productos_EnCaida($SedeConnection) {
    session()->forget('traslado');

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $conectividad_ftn = FG_Validar_Conectividad('FTN');
    $conectividad_fau = FG_Validar_Conectividad('FAU');
    $conectividad_fll = FG_Validar_Conectividad('FLL');
    $conectividad_fsm = FG_Validar_Conectividad('FSM');

    $connFAU = FG_Conectar_Smartpharma('FAU');
    $connFTN = FG_Conectar_Smartpharma('FTN');
    $connFLL = FG_Conectar_Smartpharma('FLL');
    $connFSM = FG_Conectar_Smartpharma('FSM');

    $FFinal = date("Y-m-d");
    $FInicial = date("Y-m-d",strtotime($FFinal."-10 days"));

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $sql = "SELECT * FROM productos_caida ORDER BY CAST(UnidadesVendidas AS INT) DESC";
    $result = mysqli_query($connCPharma,$sql);

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
    echo'<h6 align="center">Periodo desde el '.$FInicialImp.' al '.$FFinalImp.' </h6>';
    echo'<h6 align="center">Los productos de este reporte cumplen con los siguientes criterios</h6>';
    echo'<h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Ocultar u mostrar columnas<a></h6>';

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
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'precio\')" name="precio" checked>
                    Precio
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'existencia\')" name="existencia" checked>
                    Existencia
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'unidades_vendidas\')" name="unidades_vendidas" checked>
                    Unidades vendidas
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'dias_restantes\')" name="dias_restantes" checked>
                    Días restantes
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
                    Sede 1
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'sede2\')" name="sede2" checked>
                    Sede 2
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'sede3\')" name="sede3" checked>
                    Sede 3
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
    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Compra</th>
          <th scope="col">Venta</th>
          <th scope="col">Existencia</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td scope="col">
              <ul>
                <li><span class="alerta">NO</span> tener <span class="alerta">compras</span> en el <span class="alerta">rango</span></li>
              </ul>
          </td>
          <td scope="col">
              <ul>
                <li>Tener <span class="alerta">venta al menos 5 dias</span> en el rango</li>
              </ul>
          </td>
          <td scope="col">
              <ul>
                <li>Tener <span class="alerta">existencia</span> el dia de <span class="alerta">hoy</span></li>
                <li>Dias restantes <span class="alerta">menor a 11</span></li>
                <li>Tener existencia <span class="alerta">TODOS</span> los dias del rango</li>
                <li>Que la <span class="alerta">existencia decrezca</span> al menos 5 dias</li>
              </ul>
          </td>
        </tr>
      </tbody
    </table>';

    echo '
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
      <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="codigo CP-sticky">Codigo</th>
            <th scope="col" class="codigo_barra CP-sticky">Codigo barra</th>
              <th scope="col" class="CP-sticky descripcion">Descripcion</td>
              <th scope="col" class="CP-sticky precio">Precio</br>(Con IVA) '.SigVe.'</td>
              <th scope="col" class="CP-sticky existencia">Existencia</td>
              <th scope="col" class="CP-sticky unidades_vendidas">Unidades Vendidas</td>
              <th scope="col" class="CP-sticky dias_restantes">Dias Restantes</td>
              <th scope="col" class="CP-sticky ultimo_proveedor">Ultimo Proveedor</td>
              <th scope="col" class="CP-sticky bg-warning traslado_transito">Traslado en transito</th>
              <th scope="col" class="CP-sticky">Acciones orden de compra</th>';

              if (isset($_GET['SEDE']) && ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
                echo '<th scope="col" class="sede1 CP-sticky">Descripción FTN</th>
                    <th scope="col" class="sede1 CP-sticky">Existencia FTN</td>
                    <th scope="col" class="sede2 CP-sticky">Descripción FLL</th>
                    <th scope="col" class="sede2 CP-sticky">Existencia FLL</td>
                    <th scope="col" class="sede3 CP-sticky">Descripción FSM</th>
                    <th scope="col" class="sede3 CP-sticky">Existencia FSM</td>
                ';
              }

              if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FTN') {
                echo '<th scope="col" class="sede1 CP-sticky">Descripción FAU</th>
                    <th scope="col" class="sede1 CP-sticky">Existencia FAU</td>
                    <th scope="col" class="sede2 CP-sticky">Descripción FLL</th>
                    <th scope="col" class="sede2 CP-sticky">Existencia FLL</td>
                    <th scope="col" class="sede3 CP-sticky">Descripción FSM</th>
                    <th scope="col" class="sede3 CP-sticky">Existencia FSM</td>
                ';
              }

              if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FLL') {
                echo '<th scope="col" class="sede1 CP-sticky">Descripción FTN</th>
                    <th scope="col" class="sede1 CP-sticky">Existencia FTN</td>
                    <th scope="col" class="sede2 CP-sticky">Descripción FAU</th>
                    <th scope="col" class="sede2 CP-sticky">Existencia FAU</td>
                    <th scope="col" class="sede3 CP-sticky">Descripción FSM</th>
                    <th scope="col" class="sede3 CP-sticky">Existencia FSM</td>
                ';
              }


              if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FSM') {
                echo '<th scope="col" class="sede1 CP-sticky">Descripción FTN</th>
                    <th scope="col" class="sede1 CP-sticky">Existencia FTN</td>
                    <th scope="col" class="sede2 CP-sticky">Descripción FAU</th>
                    <th scope="col" class="sede2 CP-sticky">Existencia FAU</td>
                    <th scope="col" class="sede3 CP-sticky">Descripción FLL</th>
                    <th scope="col" class="sede3 CP-sticky">Existencia FLL</td>
                ';
              }


              echo '<th scope="col" class="CP-sticky" colspan="3">Acciones traslado</th>';


        echo '
          </tr>
        </thead>
        <tbody>
      ';

    $contador = 1;
    while($row = mysqli_fetch_assoc($result)){
      $IdArticulo = $row['IdArticulo'];
      $CodigoArticulo = $row['CodigoArticulo'];
      $Descripcion = FG_Limpiar_Texto($row['Descripcion']);
      $Precio = $row['Precio'];
      $Existencia = $row['Existencia'];
      $Dia10 = $row['Dia10'];
      $Dia9 = $row['Dia9'];
      $Dia8 = $row['Dia8'];
      $Dia7 = $row['Dia7'];
      $Dia6 = $row['Dia6'];
      $Dia5 = $row['Dia5'];
      $Dia4 = $row['Dia4'];
      $Dia3 = $row['Dia3'];
      $Dia2 = $row['Dia2'];
      $Dia1 = $row['Dia1'];
      $UnidadesVendidas = $row['UnidadesVendidas'];
      $DiasRestantes = $row['DiasRestantes'];

      $sql1 = QPC_Detalle_Articulo($IdArticulo);
      $result1 = sqlsrv_query($conn,$sql1);
      $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
      $CodigoBarra = $row1["CodigoBarra"];
      $UltimoProveedorNombre = FG_Limpiar_Texto($row1["UltimoProveedorNombre"]);
      $UltimoProveedorID = $row1["UltimoProveedorID"];
      $IsIVA = $row1["Impuesto"];
      $Dolarizado = $row1["Dolarizado"];

      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
      $Gravado = FG_Producto_Gravado($IsIVA);

      $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
      $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
      $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
      $clasificacion = $RowCPharma['clasificacion'];
      $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td class="codigo">'.$row["CodigoArticulo"].'</td>';
      echo '<td class="codigo_barra">'.$row1["CodigoBarra"].'</td>';

      echo
      '<td align="left" class="descripcion CP-barrido">
      <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';

      echo '<td align="center" class="precio">'.number_format($Precio,2,"," ,"." ).'</td>';
      echo '<td align="center" class="existencia">'.intval($Existencia).'</td>';

      echo
      '<td align="center" class="unidades_vendidas CP-barrido">
      <a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinal.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
        .intval($UnidadesVendidas).
      '</a>
      </td>';

      echo '<td class="dias_restantes" align="center">'.round($DiasRestantes,2).'</td>';

      if(!is_null($UltimoProveedorNombre)){
        echo
        '<td align="left" class="ultimo_proveedor CP-barrido">
        <a href="/reporte7?Nombre='.$UltimoProveedorNombre.'&Id='.$UltimoProveedorID.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
          .$UltimoProveedorNombre.
        '</a>
        </td>';
      }
      else{
        echo '<td class="ultimo_proveedor" align="center"> - </td>';
      }

      echo '<td class=" bg-warning traslado_transito text-center">'.Traslado_Transito($CodigoBarra).'</td>';

      /*BOTON PARA AGREGAR A LA ORDEN DE COMPRA*/

      $sqlCuentaOrden = "SELECT COUNT(*) AS Cuenta FROM orden_compra_detalles WHERE id_articulo = '$IdArticulo' AND estatus = 'ACTIVO'";
      $resultCuentaOrden = mysqli_query($connCPharma,$sqlCuentaOrden);
      $rowCuentaOrden = $resultCuentaOrden->fetch_assoc();

      $sqlDetalleOrden = "SELECT codigo_orden FROM orden_compra_detalles WHERE id_articulo = '$IdArticulo' AND estatus = 'ACTIVO'";
      $resultDetalleOrden = mysqli_query($connCPharma,$sqlDetalleOrden);

      $flag = false;
      if($rowCuentaOrden['Cuenta']==0){
        $flag = true;
      }

      while($rowDetalleOrden = $resultDetalleOrden->fetch_assoc()){
        $codigo_orden = $rowDetalleOrden['codigo_orden'];
        $sqlOrden = "SELECT estado FROM orden_compras WHERE codigo = '$codigo_orden'";
        $resultOrden = mysqli_query($connCPharma,$sqlOrden);
        $rowOrden = $resultOrden->fetch_assoc();

        if( ($rowOrden['estado']=='INGRESADA')
          || ($rowOrden['estado']=='CERRADA')
          || ($rowOrden['estado']=='RECHAZADA')
          || ($rowOrden['estado']=='ANULADA')
        ){
          $flag = true;
        }
        else{
          $flag = false;
        }
      }

      echo'
      <td style="width:140px;">
        <form action="/ordenCompraDetalle/create" method="PRE" style="display: block; width:100%;" target="_blank">
      ';
      echo'<input type="hidden" name="id_articulo" value="'.$IdArticulo.'">';
      echo'<input type="hidden" name="codigo_articulo" value="'.$CodigoArticulo.'">';
      echo'<input type="hidden" name="CodigoBarra" value="'.$CodigoBarra.'">';
      echo'<input type="hidden" name="descripcion" value="'.$Descripcion.'">';
      echo'<input type="hidden" name="existencia_rpt" value="'.$Existencia.'">';
      echo'<input type="hidden" name="dias_restantes_rpt" value="'.$DiasRestantes.'">';
      echo'<input type="hidden" name="origen_rpt" value="Productos en Caida">';
      echo'<input type="hidden" name="rango_rpt" value="Del: '.$FInicialImp.' Al: '.$FFinalImp.'">';
      echo'<input type="hidden" name="unidades_vendidas" value="'.intval($UnidadesVendidas).'">';
      echo'<input type="hidden" name="venta_diaria_real" value="-">';
      echo'<input type="hidden" name="pedir_real" value="-">';
      echo'<input type="hidden" name="dias_pedir" value="-">';
      echo'
          <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm" value="SI" style="width:100%;">Agregar</button>
        </form>
        ';

        if($flag != true){
          echo'
          <br/>
          <form action="/ordenCompraDetalle/0" method="PRE" style="display: block; width:100%;" target="_blank">';
          echo'<input type="hidden" name="id_articulo" value="'.$IdArticulo.'">';
          echo'
            <button type="submit" role="button" class="btn btn-outline-danger btn-sm" style="width:100%;">En Transito</button>
          </form>
          ';
        }
      echo'
      </td>
      ';

      if (isset($_GET['SEDE']) && ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
        if ($conectividad_ftn == 1) {
          $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
          $result3 = sqlsrv_query($connFTN,$sql3);

          if (sqlsrv_errors()) {
            $descripcion_ftn = '-';
            $existencia_ftn = '-';
          } else {
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_ftn = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_ftn = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }
        }

        if ($conectividad_fll == 1) {
          $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
          $result3 = sqlsrv_query($connFLL,$sql3);

          if (sqlsrv_errors()) {
            $descripcion_fll = '-';
            $existencia_fll = '-';
          } else {
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fll = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fll = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }
        }

        if ($conectividad_fsm == 1) {
          $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
          $result3 = sqlsrv_query($connFSM,$sql3);

          if (sqlsrv_errors()) {
            $descripcion_fsm = '-';
            $existencia_fsm = '-';
          } else {
            $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

            $descripcion_fsm = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
            $existencia_fsm = ($row3['existencia']) ? intval($row3['existencia']) : '-';
          }
        }
      }

      if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FTN') {
        if ($conectividad_fau == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFAU,$sql3);

            if (sqlsrv_errors()) {
              $descripcion_fau = '-';
              $existencia_fau = '-';              
            } else {
              $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

              $descripcion_fau = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
              $existencia_fau = ($row3['existencia']) ? intval($row3['existencia']) : '-';
            }

          }

          if ($conectividad_fll == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFLL,$sql3);

            if (sqlsrv_errors()) {
              $descripcion_fll = '-';
              $existencia_fll = '-';            
            } else {              
              $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

              $descripcion_fll = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
              $existencia_fll = ($row3['existencia']) ? intval($row3['existencia']) : '-';
            }

          }

          if ($conectividad_fsm == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFSM,$sql3);

            if (sqlsrv_errors()) {
              $descripcion_fsm = '-';
              $existencia_fsm = '-';
            } else {
              $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

              $descripcion_fsm = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
              $existencia_fsm = ($row3['existencia']) ? intval($row3['existencia']) : '-';
            }

          }
      }

      if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FLL') {
         if ($conectividad_ftn == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFTN,$sql3);

            if (sqlsrv_errors()) {
              $descripcion_ftn = '-';
              $existencia_ftn = '-';             
            } else {              
              $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

              $descripcion_ftn = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
              $existencia_ftn = ($row3['existencia']) ? intval($row3['existencia']) : '-';
            }
          }

          if ($conectividad_fau == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFAU,$sql3);

            if (sqlsrv_errors()) {
              $descripcion_fau = '-';
              $existencia_fau = '-';
            } else {
              $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

              $descripcion_fau = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
              $existencia_fau = ($row3['existencia']) ? intval($row3['existencia']) : '-';
            }
          }

          if ($conectividad_fsm == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFSM,$sql3);

            if (sqlsrv_errors()) {
              $descripcion_fsm = '-';
              $existencia_fsm = '-';
            } else {
              $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

              $descripcion_fsm = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
              $existencia_fsm = ($row3['existencia']) ? intval($row3['existencia']) : '-';
            }
          }
      }

      if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FSM') {
         if ($conectividad_ftn == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFTN,$sql3);

            if (sqlsrv_errors()) {
              $descripcion_ftn = '-';
              $existencia_ftn = '-';
            } else {
              $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

              $descripcion_ftn = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
              $existencia_ftn = ($row3['existencia']) ? intval($row3['existencia']) : '-';
            }
          }

          if ($conectividad_fau == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFAU,$sql3);

            if (sqlsrv_errors()) {
              $descripcion_fau = '-';
              $existencia_fau = '-';
            } else {
              $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

              $descripcion_fau = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
              $existencia_fau = ($row3['existencia']) ? intval($row3['existencia']) : '-';
            }
          }

          if ($conectividad_fll == 1) {
            $sql3 = R14_Q_Descripcion_Existencia_Articulo($CodigoBarra);
            $result3 = sqlsrv_query($connFLL,$sql3);

            if (sqlsrv_errors()) {
              $descripcion_fll = '-';
              $existencia_fll = '-';
            } else {
              $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);

              $descripcion_fll = ($row3['descripcion']) ? FG_Limpiar_Texto($row3['descripcion']) : '-';
              $existencia_fll = ($row3['existencia']) ? intval($row3['existencia']) : '-';
            }
          }
      }

      if (isset($_GET['SEDE']) && ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
            if ($conectividad_ftn == 1) {
                echo '<td class="sede1" align="center">'.$descripcion_ftn.'</td>
                      <td class="sede1" align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td class="sede1">-</td>';
                echo '<td class="sede1">-</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td class="sede2" align="center">'.$descripcion_fll.'</td>
                      <td class="sede2" align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td class="sede2">-</td>';
                echo '<td class="sede2">-</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td class="sede3" align="center">'.$descripcion_fsm.'</td>
                      <td class="sede3" align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td class="sede3">-</td>';
                echo '<td class="sede3">-</td>';
            }
        }

        if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FTN') {
            if ($conectividad_fau == 1) {
                echo '<td class="sede1" align="center">'.$descripcion_fau.'</td>
                      <td class="sede1" align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td class="sede1">-</td>';
                echo '<td class="sede1">-</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td class="sede2" align="center">'.$descripcion_fll.'</td>
                      <td class="sede2" align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td class="sede2">-</td>';
                echo '<td class="sede2">-</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td class="sede3" align="center">'.$descripcion_fsm.'</td>
                      <td class="sede3" align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td class="sede3">-</td>';
                echo '<td class="sede3">-</td>';
            }
        }

        if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FLL') {
            if ($conectividad_ftn == 1) {
                echo '<td class="sede1" align="center">'.$descripcion_ftn.'</td>
                      <td class="sede1" align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td class="sede1">-</td>';
                echo '<td class="sede1">-</td>';
            }

            if ($conectividad_fau == 1) {
                echo '<td class="sede2" align="center">'.$descripcion_fau.'</td>
                      <td class="sede2" align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td class="sede2">-</td>';
                echo '<td class="sede2">-</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td class="sede3" align="center">'.$descripcion_fsm.'</td>
                      <td class="sede3" align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td class="sede3">-</td>';
                echo '<td class="sede3">-</td>';
            }
        }

        if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FSM') {
            if ($conectividad_ftn == 1) {
                echo '<td align="center" class="sede1">'.$descripcion_ftn.'</td>
                      <td align="center" class="sede1">'.$existencia_ftn.'</td>';
            } else {
                echo '<td class="sede1">-</td>';
                echo '<td class="sede1">-</td>';
            }

            if ($conectividad_fau == 1) {
                echo '<td class="sede2" align="center">'.$descripcion_fau.'</td>
                      <td class="sede2" align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td class="sede2">-</td>';
                echo '<td class="sede2">-</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td class="sede1" align="center">'.$descripcion_fll.'</td>
                      <td class="sede1" align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td class="sede1">-</td>';
                echo '<td class="sede1">-</td>';
            }
        }


        // Botones de agregar a traslado

        if (isset($_GET['SEDE']) && ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
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
        }

        if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FTN') {
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

        if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FLL') {
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
        }

        if (isset($_GET['SEDE']) && $_GET['SEDE'] == 'FSM') {
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
        }

      echo '</tr>';

    $contador++;
    }

    echo '
        </tbody>';

    echo '<tfoot>';

    echo '<tr>';
    echo '<td colspan="16"></td>';
    echo '<td colspan="3"><a href="/trasladoRecibir" target="_blank" class="btn btn-outline-info btn-sm">Ver soporte</a>';
    echo '<button type="button" onclick="confirmarEliminacion(\'/trasladoRecibir/limpiar\')" class="btn btn-outline-danger btn-sm">Limpiar todo</button></td>';
    echo '</tr>';

    echo '</tfoot>

    </table>';

    mysqli_close($connCPharma);
    sqlsrv_close($conn);
  }
?>

<?php
/**********************************************************************************/
  /*
    TITULO: QPC_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function QPC_Detalle_Articulo($IdArticulo) {
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
      ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre
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
