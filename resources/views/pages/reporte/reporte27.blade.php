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

  <script>
    function mostrar_ocultar(that, elemento) {
        if (that.checked) {
            return $('.' + elemento).show();
        }

        return $('.' + elemento).hide();
    }

    campos = ['codigo', 'codigo_barra', 'descripcion', 'precio', 'dias_restantes', 'ultima_compra', 'fecha_lote', 'fecha_vencimiento', 'vida_util', 'dias_vencer', 'existencia_total', 'existencia_lote', 'valor_lote', 'valor_lote_ds', 'numero_lote', 'lote_fabricante', 'tipo', 'dolarizado', 'gravado', 'clasificacion', 'ultima_venta', 'ultimo_proveedor', 'sede1', 'sede2', 'sede3'];

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
		Artículos por Vencer
	</h1>
	<hr class="row align-items-start col-12">

<?php
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');
  use Illuminate\Http\Request;

  if (isset($_GET['SEDE'])){
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
  }
  echo '<hr class="row align-items-start col-12">';

	if (isset($_GET['fechaInicio'])) {
    $InicioCarga = new DateTime("now");

    R27_Productos_PorVencer($_GET['SEDE'],$_GET['fechaInicio']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Artículos por Vencer');

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
              Fecha de vencimiento:
            </td>
            <td>
              <input id="fechaInicio" type="date" name="fechaInicio" required style="width:100%;">
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
  /**********************************************************************************/
  /*
    TITULO: R27_Productos_PorVencer
    FUNCION: Arma una tabla con los productos mas vendidos
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R27_Productos_PorVencer($SedeConnection,$FInicial){
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $sql5 = R27Q_Productos_PorVencer($FInicial);
    $result = sqlsrv_query($conn,$sql5);

    $conectividad_ftn = FG_Validar_Conectividad('FTN');
    $conectividad_fau = FG_Validar_Conectividad('FAU');
    $conectividad_fll = FG_Validar_Conectividad('FLL');
    $conectividad_fsm = FG_Validar_Conectividad('FSM');

    $connFAU = FG_Conectar_Smartpharma('FAU');
    $connFTN = FG_Conectar_Smartpharma('FTN');
    $connFLL = FG_Conectar_Smartpharma('FLL');
    $connFSM = FG_Conectar_Smartpharma('FSM');

    $TasaActual = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));

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

    echo '<h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Ocultar u mostrar columnas<a></h6>';

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
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'descripcion\')" name="descripcion" checked>
                    Descripción
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'precio\')" name="precio" checked>
                    Precio
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'dias_restantes\')" name="dias_restantes" checked>
                    Días restantes 30
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultima_compra\')" name="ultima_compra" checked>
                    Última compra
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'fecha_lote\')" name="fecha_lote" checked>
                    Fecha lote
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'fecha_vencimiento\')" name="fecha_vencimiento" checked>
                    Fecha de vencimiento
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'vida_util\')" name="vida_util" checked>
                    Vida útil (Días)
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'dias_vencer\')" name="dias_vencer" checked>
                    Días para vencer (Días)
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'existencia_total\')" name="existencia_total" checked>
                    Existencia total
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'existencia_lote\')" name="existencia_lote" checked>
                    Existencia lote
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'valor_lote\')" name="valor_lote" checked>
                    Valor lote '.SigVe.'
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'valor_lote_ds\')" name="valor_lote_ds" checked>
                    Valor lote '.SigDolar.'
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'numero_lote\')" name="numero_lote" checked>
                    Número de lote
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'lote_fabricante\')" name="lote_fabricante" checked>
                    Lote fabricante
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'tipo\')" name="tipo" checked>
                    Tipo
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'dolarizado\')" name="dolarizado" checked>
                    Dolarizado?
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'gravado\')" name="gravado" checked>
                    Gravado?
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'clasificacion\')" name="clasificacion" checked>
                    Clasificación
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultima_venta\')" name="ultima_venta" checked>
                    Última venta
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, \'ultimo_proveedor\')" name="ultimo_proveedor" checked>
                    Último proveedor
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


    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="codigo CP-sticky">Codigo</th>
            <th scope="col" class="codigo_barra CP-sticky">Codigo de barra</th>
            <th scope="col" class="descripcion CP-sticky">Descripcion</th>
            <th scope="col" class="precio CP-sticky">Precio</br>(Con IVA) '.SigVe.'</td>
            <th scope="col" class="dias_restantes CP-sticky bg-warning">Días restantes 30</td>
            <th scope="col" class="ultima_compra CP-sticky">Ultima Compra</th>
            <th scope="col" class="fecha_lote CP-sticky">Fecha Lote</th>
            <th scope="col" class="fecha_vencimiento CP-sticky">Fecha de <br> Vencimiento</th>
            <th scope="col" class="vida_util CP-sticky">Vida Util <br> (Dias)</th>
            <th scope="col" class="dias_vencer CP-sticky">Dias para vencer <br> (Dias)</th>
            <th scope="col" class="existencia_total CP-sticky">Existencia Total</th>
            <th scope="col" class="existencia_lote CP-sticky">Existencia Lote</th>
            <th scope="col" class="valor_lote CP-sticky">Valor Lote '.SigVe.'</th>
            <th scope="col" class="valor_lote_ds CP-sticky">Valor Lote '.SigDolar.'</th>
            <th scope="col" class="numero_lote CP-sticky">Numero de Lote</th>
            <th scope="col" class="lote_fabricante CP-sticky">Lote Fabricante</th>
            <th scope="col" class="tipo CP-sticky">Tipo</th>
            <th scope="col" class="dolarizado CP-sticky">Dolarizado?</td>
            <th scope="col" class="gravado CP-sticky">Gravado?</td>
            <th scope="col" class="clasificacion CP-sticky">Clasificacion</td>
            <th scope="col" class="ultima_venta CP-sticky">Ultima Venta</th>
            <th scope="col" class="ultimo_proveedor CP-sticky">Ultimo Proveedor</th>';

            if (isset($_GET['SEDE']) & ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
                echo '<th scope="col" class="bg-warning sede1 CP-sticky">Descripción FTN</th>
                    <th scope="col" class="bg-warning sede1 CP-sticky">Existencia FTN</td>
                    <th scope="col" class="bg-warning sede2 CP-sticky">Descripción FLL</th>
                    <th scope="col" class="bg-warning sede2 CP-sticky">Existencia FLL</td>
                    <th scope="col" class="bg-warning sede3 CP-sticky">Descripción FSM</th>
                    <th scope="col" class="bg-warning sede3 CP-sticky">Existencia FSM</td>
                ';
              }

              if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FTN') {
                echo '<th scope="col" class="bg-warning sede1 CP-sticky">Descripción FAU</th>
                    <th scope="col" class="bg-warning sede1 CP-sticky">Existencia FAU</td>
                    <th scope="col" class="bg-warning sede2 CP-sticky">Descripción FLL</th>
                    <th scope="col" class="bg-warning sede2 CP-sticky">Existencia FLL</td>
                    <th scope="col" class="bg-warning sede3 CP-sticky">Descripción FSM</th>
                    <th scope="col" class="bg-warning sede3 CP-sticky">Existencia FSM</td>
                ';
              }

              if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FLL') {
                echo '<th scope="col" class="bg-warning sede1 CP-sticky">Descripción FTN</th>
                    <th scope="col" class="bg-warning sede1 CP-sticky">Existencia FTN</td>
                    <th scope="col" class="bg-warning sede2 CP-sticky">Descripción FAU</th>
                    <th scope="col" class="bg-warning sede2 CP-sticky">Existencia FAU</td>
                    <th scope="col" class="bg-warning sede3 CP-sticky">Descripción FSM</th>
                    <th scope="col" class="bg-warning sede3 CP-sticky">Existencia FSM</td>
                ';
              }

              if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FSM') {
                echo '<th scope="col" class="bg-warning sede1 CP-sticky">Descripción FTN</th>
                    <th scope="col" class="bg-warning sede1 CP-sticky">Existencia FTN</td>
                    <th scope="col" class="bg-warning sede2 CP-sticky">Descripción FAU</th>
                    <th scope="col" class="bg-warning sede2 CP-sticky">Existencia FAU</td>
                    <th scope="col" class="bg-warning sede3 CP-sticky">Descripción FLL</th>
                    <th scope="col" class="bg-warning sede3 CP-sticky">Existencia FLL</td>
                ';
              }

        echo '
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["IdArticulo"];
      $CodigoArticulo = $row["CodigoInterno"];
      $CodigoBarra = $row["CodigoBarra"];
      $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
      $Existencia = $row["Existencia"];
      $ExistenciaAlmacen1 = $row["ExistenciaAlmacen1"];
      $ExistenciaAlmacen2 = $row["ExistenciaAlmacen2"];
      $IsTroquelado = $row["Troquelado"];
      $IsIVA = $row["Impuesto"];
      $UtilidadArticulo = $row["UtilidadArticulo"];
      $UtilidadCategoria = $row["UtilidadCategoria"];
      $TroquelAlmacen1 = $row["TroquelAlmacen1"];
      $PrecioCompraBrutoAlmacen1 = $row["PrecioCompraBrutoAlmacen1"];
      $TroquelAlmacen2 = $row["TroquelAlmacen2"];
      $PrecioCompraBrutoAlmacen2 = $row["PrecioCompraBrutoAlmacen2"];
      $PrecioCompraBruto = $row["PrecioCompraBruto"];
      $Dolarizado = $row["Dolarizado"];
      $CondicionExistencia = 'CON_EXISTENCIA';
      $UltimoProveedorNombre = $row["UltimoProveedorNombre"];
      $UltimoProveedorID = $row["UltimoProveedorID"];
      $Tipo = FG_Tipo_Producto($row["Tipo"]);
      $UltimaCompra = $row["UltimaCompra"];

      $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
      $Gravado = FG_Producto_Gravado($IsIVA);
      $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

      $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
      $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
      $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
      $clasificacion = $RowCPharma['clasificacion'];
      $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";

      $fechaInicioDiasCero = date_modify(date_create(), '-15day');
      $fechaInicioDiasCero = date_format($fechaInicioDiasCero, 'Y-m-d');
      $sql2 = MySQL_Cuenta_Veces_Dias_Cero($IdArticulo,$fechaInicioDiasCero,date_create()->format('Y-m-d'));
      $result22 = mysqli_query($connCPharma,$sql2);
      $row2 = $result22->fetch_assoc();
      $RangoDiasQuiebre = $row2['Cuenta'];
      $VentaDiaria = FG_Venta_Diaria($row['TotalUnidadesVendidas'], $RangoDiasQuiebre);
      $DiasRestantes30 = FG_Dias_Restantes(intval($Existencia), $VentaDiaria);

      $diasVencer = FG_Validar_Fechas(date('Y-m-d H:i:s'),$row["FechaVencimiento"]->format('d-m-Y'));

      $background = ($DiasRestantes30 > $diasVencer) ? 'bg-warning' : '';

      echo '<tr class="'.$background.'">';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td class="codigo">'.$CodigoArticulo.'</td>';
      echo '<td class="codigo_barra">'.$CodigoBarra.'</td>';
      echo
      '<td align="left" class="descripcion CP-barrido">
      <a href="/reporte2?Descrip='.$Descripcion.'&Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';
      echo '<td class="precio" align="center">'.(number_format($Precio,2,"," ,"." )).'</td>';

      echo '<td align="center" class="dias_restantes bg-warning">'.$DiasRestantes30.'</td>';

      if(!is_null($UltimaCompra)){
        echo '<td class="ultima_compra" align="center">'.$UltimaCompra->format('d-m-Y').'</td>';
      }
      else{
        echo '<td class="ultima_compra" align="center"> - </td>';
      }

      echo '<td class="fecha_lote" align="center">'.$row["FechaLote"]->format('d-m-Y').'</td>';
      echo '<td class="fecha_vencimiento" align="center">'.$row["FechaVencimiento"]->format('d-m-Y').'</td>';

      $vidaUtil = FG_Rango_Dias($row["FechaVencimiento"]->format('d-m-Y'),$row["FechaLote"]->format('d-m-Y'));
      echo '<td class="vida_util" align="center">'.$vidaUtil.'</td>';

      echo '<td class="dias_vencer" align="center">'.$diasVencer.'</td>';

      echo '<td class="existencia_total" align="center">'.intval($Existencia).'</td>';
      echo '<td class="existencia_lote" align="center">'.intval($row["ExistenciaLote"]).'</td>';

      $precioLoteVE = intval($row["ExistenciaLote"]) * $Precio;
      echo '<td class="valor_lote" align="center">'.(number_format($precioLoteVE,2,"," ,"." )).'</td>';

      if($TasaActual!=0){
        $PrecioDolar = $Precio/$TasaActual;
        $precioLoteDolar = intval($row["ExistenciaLote"]) * $PrecioDolar;
        echo '<td class="valor_lote_ds" align="center">'.(number_format($precioLoteDolar,2,"," ,"." )).'</td>';
      }
      else{
        echo '<td class="valor_lote_ds" align="center">0,00</td>';
      }

      echo '<td class="numero_lote" align="center">'.$row["Numero"].'</td>';
      echo '<td class="lote_fabricante" align="center">'.$row["LoteFabricante"].'</td>';
      echo '<td class="tipo" align="center">'.$Tipo.'</td>';
      echo '<td class="dolarizado" align="center">'.$Dolarizado.'</td>';
      echo '<td class="gravado" align="center">'.$Gravado.'</td>';
      echo '<td class="clasificacion" align="center">'.$clasificacion.'</td>';

      if($row["UltimaVenta"]!=null){
        echo '<td class="ultima_venta" align="center">'.$row["UltimaVenta"]->format('d-m-Y').'</td>';
      }
      else{
        echo '<td class="ultima_venta" align="center"> - </td>';
      }

      if(!is_null($UltimoProveedorNombre)){
        echo
        '<td align="left" class="ultimo_proveedor CP-barrido">
        <a href="/reporte7?Nombre='.$UltimoProveedorNombre.'&Id='.$UltimoProveedorID.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
          .$UltimoProveedorNombre.
        '</a>
        </td>';
      }
      else{
        echo '<td align="center"> - </td>';
      }

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
      }

      if (isset($_GET['SEDE']) & ($_GET['SEDE'] == 'FAU' || $_GET['SEDE'] == 'DBs')) {
            if ($conectividad_ftn == 1) {
                echo '<td class="bg-warning sede1" align="center">'.$descripcion_ftn.'</td>
                      <td class="bg-warning sede1" align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td class="bg-warning sede1">-</td>';
                echo '<td class="bg-warning sede1">-</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td class="bg-warning sede2" align="center">'.$descripcion_fll.'</td>
                      <td class="bg-warning sede2" align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td class="bg-warning sede2">-</td>';
                echo '<td class="bg-warning sede2">-</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td class="bg-warning sede3" align="center">'.$descripcion_fsm.'</td>
                      <td class="bg-warning sede3" align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td class="bg-warning sede3">-</td>';
                echo '<td class="bg-warning sede3">-</td>';
            }
        }

        if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FTN') {
            if ($conectividad_fau == 1) {
                echo '<td class="bg-warning sede1" align="center">'.$descripcion_fau.'</td>
                      <td class="bg-warning sede1" align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td class="bg-warning sede1">-</td>';
                echo '<td class="bg-warning sede1">-</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td class="bg-warning sede2" align="center">'.$descripcion_fll.'</td>
                      <td class="bg-warning sede2" align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td class="bg-warning sede2">-</td>';
                echo '<td class="bg-warning sede2">-</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td class="bg-warning sede3" align="center">'.$descripcion_fsm.'</td>
                      <td class="bg-warning sede3" align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td class="bg-warning sede3">-</td>';
                echo '<td class="bg-warning sede3">-</td>';
            }
        }

        if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FLL') {
            if ($conectividad_ftn == 1) {
                echo '<td class="bg-warning sede1" align="center">'.$descripcion_ftn.'</td>
                      <td class="bg-warning sede1" align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td class="bg-warning sede1">-</td>';
                echo '<td class="bg-warning sede1">-</td>';
            }

            if ($conectividad_fau == 1) {
                echo '<td class="bg-warning sede2" align="center">'.$descripcion_fau.'</td>
                      <td class="bg-warning sede2" align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td class="bg-warning sede2">-</td>';
                echo '<td class="bg-warning sede2">-</td>';
            }

            if ($conectividad_fsm == 1) {
                echo '<td class="bg-warning sede3" align="center">'.$descripcion_fsm.'</td>
                      <td class="bg-warning sede3" align="center">'.$existencia_fsm.'</td>';
            } else {
                echo '<td class="bg-warning sede3">-</td>';
                echo '<td class="bg-warning sede3">-</td>';
            }
        }

        if (isset($_GET['SEDE']) & $_GET['SEDE'] == 'FSM') {
            if ($conectividad_ftn == 1) {
                echo '<td class="bg-warning sede1" align="center">'.$descripcion_ftn.'</td>
                      <td class="bg-warning sede1" align="center">'.$existencia_ftn.'</td>';
            } else {
                echo '<td class="bg-warning sede1">-</td>';
                echo '<td class="bg-warning sede1">-</td>';
            }

            if ($conectividad_fau == 1) {
                echo '<td class="bg-warning sede2" align="center">'.$descripcion_fau.'</td>
                      <td class="bg-warning sede2" align="center">'.$existencia_fau.'</td>';
            } else {
                echo '<td class="bg-warning sede2">-</td>';
                echo '<td class="bg-warning sede2">-</td>';
            }

            if ($conectividad_fll == 1) {
                echo '<td class="bg-warning sede3" align="center">'.$descripcion_fll.'</td>
                      <td class="bg-warning sede3" align="center">'.$existencia_fll.'</td>';
            } else {
                echo '<td class="bg-warning sede3">-</td>';
                echo '<td class="bg-warning sede3">-</td>';
            }
        }

      echo '</tr>';
      $contador++;
    }
    echo '
      </tbody>
    </table>';
    mysqli_close($connCPharma);
    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R3Q_TOP_MasVendidos
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
    DESAROLLADO POR: SERGIO COVA
  */
  function R27Q_Productos_PorVencer($FInicial) {
    $Inicio = date_modify(date_create(), '-30day');
    $Inicio = $Inicio->format('Y-m-d');
    $Fin = date_create()->format('Y-m-d');

    $sql = "
    SELECT
    -- TotalUnidadesVendidas
    (SELECT (((ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)))
      -
      (ISNULL((SELECT
      (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
      FROM VenDevolucionDetalle
      INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
      WHERE VenDevolucionDetalle.InvArticuloId = InvArticulo.Id
      AND(VenDevolucion.FechaDocumento > '$Inicio' AND VenDevolucion.FechaDocumento < '$Fin')
      GROUP BY VenDevolucionDetalle.InvArticuloId
      ),CAST(0 AS INT)))) FROM VenFacturaDetalle) AS TotalUnidadesVendidas,
    --Id Articulo
    InvArticulo.Id AS IdArticulo,
    --Categoria Articulo
    InvArticulo.InvCategoriaId,
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
    --Troquelado (0 NO es Troquelado, Id Articulo SI es Troquelado)
    (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'Troquelados'
    OR  InvAtributo.Descripcion = 'troquelados')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Troquelado,
    --UtilidadArticulo (Utilidad del articulo, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
    FROM VenCondicionVenta
    WHERE VenCondicionVenta.Id = (
    SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id
    FROM VenCondicionVenta_VenCondicionVentaArticulo
    WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadArticulo,
    --UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
    FROM VenCondicionVenta
    WHERE VenCondicionVenta.id = (
    SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id
    FROM VenCondicionVenta_VenCondicionVentaCategoria
    WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadCategoria,
    --Precio Troquel Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '1')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,4)),4,0)) AS TroquelAlmacen1,
    --Precio Compra Bruto Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    AND (InvLoteAlmacen.InvAlmacenId = '1')
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen1,
    --Precio Troquel Almacen 2
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '2')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen2,
    --Precio Compra Bruto Almacen 2
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    AND (InvLoteAlmacen.InvAlmacenId = '2')
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen2,
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
    --ExistenciaAlmacen1 (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
    FROM InvLoteAlmacen
    WHERE(InvLoteAlmacen.InvAlmacenId = 1)
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen1,
    --ExistenciaAlmacen2 (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
    FROM InvLoteAlmacen
    WHERE(InvLoteAlmacen.InvAlmacenId = 2)
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen2,
    --ExistenciaLote (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
    FROM InvLoteAlmacen
    WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND InvLoteAlmacen.InvLoteId = InvLote.Id) AS DECIMAL(38,0)),2,0))  AS ExistenciaLote,
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
    -- Ultima Compra (Fecha de ultima compra)
    (SELECT TOP 1
    CONVERT(DATE,ComFactura.FechaRegistro)
    FROM ComFacturaDetalle
    INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY ComFactura.FechaRegistro DESC) AS  UltimaCompra,
    --Tiempo Tienda (En dias)
    (SELECT TOP 1
    DATEDIFF(DAY,CONVERT(DATE,InvLote.FechaEntrada),GETDATE())
    FROM InvLoteAlmacen
    INNER JOIN invlote on invlote.id = InvLoteAlmacen.InvLoteId
    WHERE InvLotealmacen.InvArticuloId = InvArticulo.Id
    ORDER BY InvLote.Auditoria_FechaCreacion DESC) AS TiempoTienda,
    --Ultimo Proveedor (Id Proveedor)
    (SELECT TOP 1
    ComProveedor.Id
    FROM ComFacturaDetalle
    INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorID,
    --Ultimo Proveedor (Nombre Proveedor)
    (SELECT TOP 1
    GenPersona.Nombre
    FROM ComFacturaDetalle
    INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre,
    InvLote.Auditoria_FechaCreacion as FechaLote,
    InvLote.FechaVencimiento as FechaVencimiento,
    InvLote.Numero,
    InvLote.LoteFabricante,
    InvLote.Id as LoteId
    FROM InvLote
    INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvLoteId = InvLote.Id
    INNER JOIN InvArticulo ON InvArticulo.Id = InvLoteAlmacen.InvArticuloId
    WHERE InvLote.FechaVencimiento < '$FInicial'
    AND InvLote.FechaVencimiento <> ''
    AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
    AND InvLoteAlmacen.Existencia > 0
    ORDER BY InvLote.FechaVencimiento DESC
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

@section('scriptsFoot')
<script>
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();
  });
</script>
@endsection
