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
  <script src="https://raw.githubusercontent.com/SamWM/jQuery-Plugins/master/numeric/jquery.numeric.js"></script>
  <script>
    $(document).ready(function () {
      //$('#diasUltimaVenta').numeric();

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
		Monitoreo de Inventarios
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

    R31_Monitoreo_Inventarios($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Monitoreo de Inventarios');

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
          </tr>

          <tr>
            <td align="center">
              Vida Util:
            </td>
            <td align="right">
              <input id="vidaUtil" name="vidaUtil" type="number" required style="width:100%;">
            </td>

            <td align="center">
              Dias de sobre-stock:
            </td>
            <td align="right">
              <input id="sobreStock" name="sobreStock" type="number" required style="width:100%;">
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
    TITULO: R31_Monitoreo_Inventarios
    FUNCION: Arma una lista de productos en falla
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R31_Monitoreo_Inventarios($SedeConnection,$FInicial,$FFinal){
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    $sql5 = R31Q_Monitoreo_Inventarios($FInicial,$FFinal);
    $result = sqlsrv_query($conn,$sql5);

    $sql6 = R31Q_Monitoreo_Correcciones($FInicial,$FFinal);    
    $result1 = sqlsrv_query($conn,$sql6);

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


    echo '
      <table>
      <tbody>
        <tr>
          <td style="width:11%;" align="center">
              <a data-seccion="cambios" href="#" class="secciones btn btn-outline-primary btn-sm">CAMBIOS DE PRECIO A LA BAJA</a>
          </td>

          <td style="width:11%;" align="center">
            <a data-seccion="entradas" href="#" class="secciones btn btn-outline-danger btn-sm">ENTRADAS Y SALIDAS DE INVENTARIO</a>
          </td>

          <td style="width:11%;" align="center">
            <a data-seccion="vencimiento" href="#" class="secciones btn btn-outline-warning btn-sm">SIN FECHA DE VENCIMIENTO</a>
          </td>

          <td style="width:11%;" align="center">
            <a data-seccion="corto" href="#" class="secciones btn btn-outline-success btn-sm">ARTICULOS CORTO VENCIMIENTO</a>
          </td>

          <td style="width:11%;" align="center">
            <a data-seccion="stock" href="#" class="secciones btn btn-outline-info btn-sm">ARTICULOS EN SOBRE STOCK</a>
          </td>
        </tr>
      </tbody>
    </table>
    ';

    if (!isset($_GET['seccion']) or $_GET['seccion'] == 'cambios') {
      echo'
      <hr>
      <h4 align="center">Cambios de precios a la baja</h4>
      <hr>
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>  
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Tipo de Movimiento</th>
            <th scope="col" class="CP-sticky">Origen de Movimiento</th>
            <th scope="col" class="CP-sticky">Numero de Movimiento</th>
            <th scope="col" class="CP-sticky">Fecha de Movimiento</th>
            <th scope="col" class="CP-sticky">Codigo Interno</th>
            <th scope="col" class="CP-sticky">Codigo de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Cantidad</th>          
            <th scope="col" class="CP-sticky">Numero de Lote (CC -)</th>
            <th scope="col" class="CP-sticky">Numero de Lote (CC +)</th>
            <th scope="col" class="CP-sticky">Almacen</td>          
            <th scope="col" class="CP-sticky">Costo Uniario (CC -)'.SigVe.'</td>
            <th scope="col" class="CP-sticky">Costo Uniario (CC +)'.SigVe.'</td>          
            <th scope="col" class="CP-sticky">Operador</th>         
          </tr>
        </thead>
        <tbody>
      ';

      $contador = 1;
      while($row = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC)) {
        $TipoMovimiento = FG_Limpiar_Texto($row["TipoMovimiento"]);
        $NumeroMovimiento = $row["NumeroMovimiento"];      
        $CostoUniario = $row["CostoUniario"];
        
        if($TipoMovimiento == 'Corrección costo -'){
          $NumeroMovCCNeg = $NumeroMovimiento;        
          $LoteCCNeg = $row["Lote"];        
          $CostoUniarioCCNeg = $CostoUniario;
        }
        else if( ($TipoMovimiento=='Corrección costo +') && ($NumeroMovimiento == $NumeroMovCCNeg) &&($CostoUniarioCCNeg > $CostoUniario) ) {

          $IdArticulo = $row["IdArticulo"];        
          $OrigenMovimiento = $row["OrigenMovimiento"];
          $OrigenMovimiento = determminar_Origen_Movimiento($OrigenMovimiento);        
          $FechaMovimiento = $row["FechaMovimiento"];
          $CodigoArticulo = $row["CodigoInterno"];
          $CodigoBarra = $row["CodigoBarra"];
          $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
          $Cantidad = $row["Cantidad"];
          $Lote = $row["Lote"];
          $Almacen = $row["Almacen"];        
          $Operador = $row["Operador"];
                          
          echo '<tr>';
          echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
          echo '<td align="left">'.($TipoMovimiento).'</td>';
          echo '<td align="left">'.FG_Limpiar_Texto($OrigenMovimiento).'</td>';
          echo '<td align="left">'.$NumeroMovimiento.'</td>';
          echo '<td align="center">'.$FechaMovimiento->format('d-m-Y h:i:s A').'</td>';
          echo '<td align="left">'.$CodigoArticulo.'</td>';
          echo '<td align="center">'.$CodigoBarra.'</td>';
          echo 
          '<td align="left" class="CP-barrido">
          <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
            .$Descripcion.
          '</a>
          </td>';        
          echo '<td align="center">'.intval($Cantidad).'</td>';
          echo '<td align="center">'.$LoteCCNeg.'</td>';
          echo '<td align="center">'.$Lote.'</td>';
          echo '<td align="center">'.FG_Limpiar_Texto($Almacen).'</td>';
          echo '<td align="center">'.number_format($CostoUniarioCCNeg,2,"," ,"." ).'</td>';        
          echo '<td align="center">'.number_format($CostoUniario,2,"," ,"." ).'</td>'; 
          echo '<td align="center">'.$Operador.'</td>';      
          echo '</tr>';
          $contador++;
        }       
      }
      echo '
        </tbody>
      </table>';

    }

    if (isset($_GET['seccion']) && $_GET['seccion'] == 'entradas') {

      echo'
      <br>
      <hr>
      <h4 align="center">Entradas y salidas de inventario</h4>
      <hr>
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>  
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Tipo de Movimiento</th>
            <th scope="col" class="CP-sticky">Origen de Movimiento</th>
            <th scope="col" class="CP-sticky">Numero de Movimiento</th>
            <th scope="col" class="CP-sticky">Fecha de Movimiento</th>
            <th scope="col" class="CP-sticky">Codigo Interno</th>
            <th scope="col" class="CP-sticky">Codigo de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Cantidad</th>
            <th scope="col" class="CP-sticky">Numero de Lote</th>
            <th scope="col" class="CP-sticky">Almacen</td>
            <th scope="col" class="CP-sticky">Costo Uniario '.SigVe.'</td>
            <th scope="col" class="CP-sticky">Comentario</td>
            <th scope="col" class="CP-sticky">Operador</th>          
          </tr>
        </thead>
        <tbody>
      ';
      $contador = 1;
      while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $IdArticulo = $row["IdArticulo"];
        $TipoMovimiento = $row["TipoMovimiento"];
        $OrigenMovimiento = $row["OrigenMovimiento"];
        $OrigenMovimiento = determminar_Origen_Movimiento($OrigenMovimiento);
        $NumeroMovimiento = $row["NumeroMovimiento"];
        $FechaMovimiento = $row["FechaMovimiento"];
        $CodigoArticulo = $row["CodigoInterno"];
        $CodigoBarra = $row["CodigoBarra"];
        $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
        $Cantidad = $row["Cantidad"];
        $Lote = $row["Lote"];
        $Almacen = $row["Almacen"];
        $CostoUniario = $row["CostoUniario"];
        $Operador = $row["Operador"];

        $ComentarioAjuste = $row["ComentarioAjuste"];
        $ComentarioTransferencia = $row["ComentarioTransferencia"];
        
        if(!is_null($ComentarioAjuste)){
          $comentario = $ComentarioAjuste;
        }
        else if(!is_null($ComentarioTransferencia)){
          $comentario = $ComentarioTransferencia;
        }
        else{
          $comentario = "-";
        }
         
        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
        echo '<td align="left">'.FG_Limpiar_Texto($TipoMovimiento).'</td>';
        echo '<td align="left">'.FG_Limpiar_Texto($OrigenMovimiento).'</td>';
        echo '<td align="left">'.$NumeroMovimiento.'</td>';
        echo '<td align="center">'.$FechaMovimiento->format('d-m-Y h:i:s A').'</td>';
        echo '<td align="left">'.$CodigoArticulo.'</td>';
        echo '<td align="center">'.$CodigoBarra.'</td>';
        echo 
        '<td align="left" class="CP-barrido">
        <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$Descripcion.
        '</a>
        </td>';
        echo '<td align="center">'.intval($Cantidad).'</td>';
        echo '<td align="center">'.$Lote.'</td>';
        echo '<td align="center">'.FG_Limpiar_Texto($Almacen).'</td>';
        echo '<td align="center">'.number_format($CostoUniario,2,"," ,"." ).'</td>';
        echo '<td align="center">'.FG_Limpiar_Texto($comentario).'</td>';
        echo '<td align="center">'.$Operador.'</td>';      
        echo '</tr>';
        $contador++;
      }
      echo '
        </tbody>
      </table>';
    }

    if (isset($_GET['seccion']) && $_GET['seccion'] == 'vencimiento') {

      echo'
      <br>
      <hr>
      <h4 align="center">Articulos sin fecha de vencimiento</h4>
      <hr>
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>  
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Tipo de Movimiento</th>
            <th scope="col" class="CP-sticky">Codigo</th>
            <th scope="col" class="CP-sticky">Codigo de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Fecha de Registro</th>
            <th scope="col" class="CP-sticky">Fecha de Vencimiento</th>
            <th scope="col" class="CP-sticky">Cantidad Recibida</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Operador</th>
            <th scope="col" class="CP-sticky">Proveedor</th>
            <th scope="col" class="CP-sticky">Numero Factura</th>
            <th scope="col" class="CP-sticky">Ultima Venta</th>
          </tr>
        </thead>
        <tbody>
      ';

      $sql7 = R31Q_Articulos_Sin_Vencimiento($_GET['fechaInicio'], $_GET['fechaFin']);
      $result7 = sqlsrv_query($conn,$sql7);

      $contador = 1;

      while ($row3 = sqlsrv_fetch_array($result7, SQLSRV_FETCH_ASSOC)) {
        $codigo = $row3['codigo_articulo'];
        $codigo_barra = $row3['codigo_barra'];
        $descripcion = FG_Limpiar_Texto($row3['descripcion']);
        $fecha_registro = $row3['fecha_registro']->format('d/m/Y');
        $cantidad_recibida = intval($row3['cantidad_recibida']);
        $existencia = intval($row3['existencia']);
        $operador = $row3['operador'];
        $id_articulo = $row3['id_articulo'];
        $proveedor = FG_Limpiar_Texto($row3['proveedor']);
        $numero_factura = $row3['numero_factura'];
        $ultima_venta = ($row3['ultima_venta']) ? $row3['ultima_venta']->format('d/m/Y') : '-';

        echo '<tr>';
        echo '<td>'.$contador.'</td>';
        echo '<td>Compras</td>';
        echo '<td>'.$codigo.'</td>';
        echo '<td>'.$codigo_barra.'</td>';
        echo 
        '<td align="left" class="CP-barrido">
        <a href="/reporte2?Id='.$id_articulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$descripcion.
        '</a>
        </td>';
        echo '<td>'.$fecha_registro.'</td>';
        echo '<td>-</td>';
        echo '<td>'.$cantidad_recibida.'</td>';
        echo '<td>'.$existencia.'</td>';
        echo '<td>'.$operador.'</td>';
        echo '<td>'.$proveedor.'</td>';
        echo '<td>'.$numero_factura.'</td>';
        echo '<td>'.$ultima_venta.'</td>';

        echo '</tr>';

        $contador++;
      }

      echo '</tbody>';
      echo '</table>';
    }


    if (isset($_GET['seccion']) && $_GET['seccion'] == 'corto') {
      echo'
      <br>
      <hr>
      <h4 align="center">Articulos corto vencimiento</h4>
      <hr>
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>  
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Tipo de Movimiento</th>
            <th scope="col" class="CP-sticky">Codigo</th>
            <th scope="col" class="CP-sticky">Codigo de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Fecha de Registro</th>
            <th scope="col" class="CP-sticky">Fecha de Vencimiento</th>
            <th scope="col" class="CP-sticky">Vida util</th>
            <th scope="col" class="CP-sticky">Cantidad Recibida</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Operador</th>
            <th scope="col" class="CP-sticky">Proveedor</th>
            <th scope="col" class="CP-sticky">Numero Factura</th>
            <th scope="col" class="CP-sticky">Ultima Venta</th>
          </tr>
        </thead>
        <tbody>
      ';

      $sql8 = R31Q_Articulos_Corto_Vencimiento($_GET['fechaInicio'], $_GET['fechaFin'], $_GET['vidaUtil']);
      $result8 = sqlsrv_query($conn,$sql8);

      $contador = 1;

      while ($row4 = sqlsrv_fetch_array($result8, SQLSRV_FETCH_ASSOC)) {
        $codigo = $row4['codigo_articulo'];
        $codigo_barra = $row4['codigo_barra'];
        $descripcion = FG_Limpiar_Texto($row4['descripcion']);
        $fecha_registro = $row4['fecha_registro']->format('d/m/Y');
        $fecha_vencimiento = $row4['fecha_vencimiento']->format('d/m/Y');
        $vida_util = intval($row4['vida_util']);
        $cantidad_recibida = intval($row4['cantidad_recibida']);
        $existencia = intval($row4['existencia']);
        $operador = $row4['operador'];
        $id_articulo = $row4['id_articulo'];
        $proveedor = FG_Limpiar_Texto($row4['proveedor']);
        $numero_factura = $row4['numero_factura'];
        $ultima_venta = ($row4['ultima_venta']) ? $row4['ultima_venta']->format('d/m/Y') : '-';

        echo '<tr>';
        echo '<td>'.$contador.'</td>';
        echo '<td>Compras</td>';
        echo '<td>'.$codigo.'</td>';
        echo '<td>'.$codigo_barra.'</td>';
        echo 
        '<td align="left" class="CP-barrido">
        <a href="/reporte2?Id='.$id_articulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$descripcion.
        '</a>
        </td>';
        echo '<td>'.$fecha_registro.'</td>';
        echo '<td>'.$fecha_vencimiento.'</td>';
        echo '<td>'.$vida_util.'</td>';
        echo '<td>'.$cantidad_recibida.'</td>';
        echo '<td>'.$existencia.'</td>';
        echo '<td>'.$operador.'</td>';
        echo '<td>'.$proveedor.'</td>';
        echo '<td>'.$numero_factura.'</td>';
        echo '<td>'.$ultima_venta.'</td>';
        echo '</tr>';

        $contador++;
      }

      echo '</tbody>';
      echo '</table>';
    }


    if (isset($_GET['seccion']) && $_GET['seccion'] == 'stock') {
      echo'
      <br>
      <hr>
      <h4 align="center">Articulos con sobre stock</h4>
      <hr>
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>  
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Tipo de Movimiento</th>
            <th scope="col" class="CP-sticky">Codigo</th>
            <th scope="col" class="CP-sticky">Codigo de Barra</th>
            <th scope="col" class="CP-sticky">Descripcion</th>
            <th scope="col" class="CP-sticky">Fecha de Registro</th>
            <th scope="col" class="CP-sticky">Fecha de Vencimiento</th>
            <th scope="col" class="CP-sticky">Dias Restantes</th>
            <th scope="col" class="CP-sticky">Cantidad Recibida</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Operador</th>
            <th scope="col" class="CP-sticky">Proveedor</th>
            <th scope="col" class="CP-sticky">Numero Factura</th>
            <th scope="col" class="CP-sticky">Ultima Venta</th>     
          </tr>
        </thead>
        <tbody>
      ';

      $sql9 = R31Q_Articulos_Sobre_Stock($_GET['fechaInicio'], $_GET['fechaFin'], $_GET['sobreStock']);
      $result9 = sqlsrv_query($conn,$sql9);

      if( $result9 === false ) {
        dd(sqlsrv_errors());
      }

      $contador = 1;

      while ($row5 = sqlsrv_fetch_array($result9, SQLSRV_FETCH_ASSOC)) {
        $codigo = $row5['codigo_articulo'];
        $codigo_barra = $row5['codigo_barra'];
        $descripcion = FG_Limpiar_Texto($row5['descripcion']);
        $fecha_registro = $row5['fecha_registro'] ? $row5['fecha_registro']->format('d/m/Y') : '';
        $fecha_vencimiento = $row5['fecha_vencimiento'] ? $row5['fecha_vencimiento']->format('d/m/Y') : '';
        $cantidad_recibida = intval($row5['cantidad_recibida']);
        $existencia = intval($row5['existencia']);
        $operador = $row5['operador'];
        $id_articulo = $row5['id_articulo'];
        $proveedor = FG_Limpiar_Texto($row5['proveedor']);
        $numero_factura = $row5['numero_factura'];
        $ultima_venta = ($row5['ultima_venta']) ? $row5['ultima_venta']->format('d/m/Y') : '-';

        $fechaInicioDiasCero = $row5['fecha_registro']->format('Y-m-d');
        $fechaInicioDiasCero = date_modify(date_create($fechaInicioDiasCero), '-30day');
        $fechaInicioDiasCero = date_format($fechaInicioDiasCero, 'Y-m-d');

        $sql2 = MySQL_Cuenta_Veces_Dias_Cero($id_articulo,$fechaInicioDiasCero,$FInicial);
        $result2 = mysqli_query($connCPharma,$sql2);
        $row2 = $result2->fetch_assoc();
        $RangoDiasQuiebre = $row2['Cuenta'];

        $sql3 = R31Q_Total_Venta($id_articulo, $fechaInicioDiasCero, $row5['fecha_registro']->format('Y-m-d'));

        $result3 = sqlsrv_query($conn,$sql3);

        $row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC);

        $VentaDiariaQuiebre = FG_Venta_Diaria($row3['TotalUnidadesVendidas'],$RangoDiasQuiebre);
        $DiasRestantesQuiebre = FG_Dias_Restantes($existencia,$VentaDiariaQuiebre);

        if ($DiasRestantesQuiebre >= $_GET['sobreStock']) {
          echo '<tr>';
          echo '<td>'.$contador.'</td>';
          echo '<td>Compras</td>';
          echo '<td>'.$codigo.'</td>';
          echo '<td>'.$codigo_barra.'</td>';
          echo 
          '<td align="left" class="CP-barrido">
          <a href="/reporte2?Id='.$id_articulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
            .$descripcion.
          '</a>
          </td>';
          echo '<td>'.$fecha_registro.'</td>';
          echo '<td>'.$fecha_vencimiento.'</td>';
          echo '<td>'.round($DiasRestantesQuiebre,2).'</td>';
          echo '<td>'.$cantidad_recibida.'</td>';
          echo '<td>'.$existencia.'</td>';
          echo '<td>'.$operador.'</td>';
          echo '<td>'.$proveedor.'</td>';
          echo '<td>'.$numero_factura.'</td>';
          echo '<td>'.$ultima_venta.'</td>';
          echo '</tr>';

          $contador++;
        }

        
      }

      echo '</tbody>';
      echo '</table>';
    }

    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R31Q_Monitoreo_Inventarios
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
    DESAROLLADO POR: SERGIO COVA
  */
  function R31Q_Monitoreo_Inventarios($FInicial,$FFinal) {
    $sql = "
      SELECT 
    --Tipo Movimiento
    InvCausa.Descripcion as TipoMovimiento,
    -- Origen Movimiento
    InvMovimiento.origenMovimiento as OrigenMovimiento,
    --Numero de Movimiento
    InvMovimiento.DocumentoOrigen as NumeroMovimiento,
    --Id Articulo
    InvArticulo.id as IdArticulo,
    --Codigo Interno
    InvArticulo.CodigoArticulo AS CodigoInterno,
    --Codigo de Barra
    (SELECT CodigoBarra
    FROM InvCodigoBarra 
    WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
    AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
    --Descripcion
    InvArticulo.Descripcion,
    --Cantidad
    InvMovimiento.Cantidad as Cantidad,
    --Lote
    InvLote.Numero as Lote,
    --Almacen
    InvAlmacen.Descripcion as Almacen,
    --Costo Unitario 
    InvMovimiento.M_CostoUnitario as CostoUniario,
    --Comentario Ajuste
    (SELECT InvAjuste.Comentario
    FROM InvAjuste 
    WHERE InvAjuste.NumeroAjuste = InvMovimiento.DocumentoOrigen AND (InvMovimiento.InvCausaId = '14' OR InvMovimiento.InvCausaId = '15') ) AS ComentarioAjuste,
    --Comentario Transferecia
    (SELECT InvTransferenciaAlmacen.Observaciones
    FROM InvTransferenciaAlmacen 
    WHERE InvTransferenciaAlmacen.NumeroTransferencia = InvMovimiento.DocumentoOrigen AND (InvMovimiento.InvCausaId = '5' OR InvMovimiento.InvCausaId = '6') ) AS ComentarioTransferencia,
    --Operador
    InvMovimiento.Auditoria_Usuario as Operador,
    --Fecha movimiento
    InvMovimiento.FechaMovimiento  as FechaMovimiento
    FROM InvMovimiento
    INNER JOIN InvCausa ON InvCausa.id = InvMovimiento.InvCausaId
    INNER JOIN InvArticulo ON InvArticulo.id = InvMovimiento.InvArticuloId
    INNER JOIN InvLote ON InvLote.id = InvMovimiento.InvLoteId
    INNER JOIN InvAlmacen ON InvAlmacen.id = InvMovimiento.InvAlmacenId
    WHERE (InvMovimiento.InvCausaId = '14' OR InvMovimiento.InvCausaId = '15' 
    OR InvMovimiento.InvCausaId = '5' OR InvMovimiento.InvCausaId = '6')
    AND(InvMovimiento.FechaMovimiento > '$FInicial' AND InvMovimiento.FechaMovimiento < '$FFinal')
    ORDER BY InvMovimiento.FechaMovimiento ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  function determminar_Origen_Movimiento($OrigenMovimiento){
    switch ($OrigenMovimiento) {
      case '1':
       $nombreMovimiento = "Ventas";
      break;
      case '2':
        $nombreMovimiento = "Ventas devolucion";
      break;
      case '3':
        $nombreMovimiento = "Compras";
      break;
      case '4':
        $nombreMovimiento = "Indefinido-4";
      break;
      case '5':
        $nombreMovimiento = "Ajuste de Inventario";
      break;
      case '6':
        $nombreMovimiento = "Indefinido-6";
      break;
      case '7':
        $nombreMovimiento = "Correccion de Costo";
      break;
      case '8':
        $nombreMovimiento = "Indefinido-8";
      break;
      case '9':
        $nombreMovimiento = "Toma de Inventario";
      break;
      case '10':
        $nombreMovimiento = "Transferencia de Almacen";
      break;
      default:
        $nombreMovimiento =  "-";
      break;
    }
    return $nombreMovimiento;
  }
  /**********************************************************************************/
  /*
    TITULO: R31Q_Monitoreo_Inventarios
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
    DESAROLLADO POR: SERGIO COVA
  */
  function R31Q_Monitoreo_Correcciones($FInicial,$FFinal) {
    $sql = "
    SELECT 
    InvArticulo.id as IdArticulo,
    InvArticulo.CodigoArticulo AS CodigoInterno,
    (SELECT CodigoBarra
    FROM InvCodigoBarra 
    WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
    AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
    InvArticulo.Descripcion,
    InvMovimiento.origenMovimiento as OrigenMovimiento,
    InvMovimiento.DocumentoOrigen as NumeroMovimiento,
    InvCausa.Descripcion as TipoMovimiento,          
    InvMovimiento.Cantidad as Cantidad,
    InvLote.Numero as Lote,
    InvAlmacen.Descripcion as Almacen,
    InvMovimiento.M_CostoUnitario as CostoUniario,
    InvMovimiento.Auditoria_Usuario as Operador,
    InvMovimiento.FechaMovimiento  as FechaMovimiento
    FROM InvMovimiento
    INNER JOIN InvCausa ON InvCausa.id = InvMovimiento.InvCausaId
    INNER JOIN InvArticulo ON InvArticulo.id = InvMovimiento.InvArticuloId
    INNER JOIN InvLote ON InvLote.id = InvMovimiento.InvLoteId
    INNER JOIN InvAlmacen ON InvAlmacen.id = InvMovimiento.InvAlmacenId
    WHERE (InvMovimiento.InvCausaId = '11' or InvMovimiento.InvCausaId = '12')
    AND(InvMovimiento.FechaMovimiento > '$FInicial' AND InvMovimiento.FechaMovimiento < '$FFinal')
    ORDER BY InvMovimiento.FechaMovimiento ASC, InvMovimiento.M_CostoUnitario ASC
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R31Q_Articulos_Sin_Vencimiento
    FUNCION: Ubicar productos sin fecha de vencimiento entre rango de fecha
    RETORNO: Lista de productos sin fecha de vencimiento entre rango de fecha
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R31Q_Articulos_Sin_Vencimiento($FInicial,$FFinal) {
    $sql = "
      SELECT
        (SELECT InvArticulo.CodigoArticulo FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS codigo_articulo,
        (SELECT InvArticulo.Id FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS id_articulo,
        (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = ComFacturaDetalle.InvArticuloId AND InvCodigoBarra.EsPrincipal = 1) AS codigo_barra,
        (SELECT InvArticulo.DescripcionLarga FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS descripcion,
        (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS fecha_registro,
        ComFacturaDetalle.CantidadRecibidaFactura AS cantidad_recibida,
        (SELECT SUM(InvLoteAlmacen.Existencia) FROM InvLoteAlmacen WHERE InvLoteAlmacen.InvAlmacenId IN (1, 2) AND InvLoteAlmacen.InvArticuloId = ComFacturaDetalle.InvArticuloId) AS existencia,
        (SELECT ComFactura.Auditoria_Usuario FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS operador,
        (SELECT GenPersona.Nombre FROM GenPersona WHERE GenPersona.Id = (SELECT ComProveedor.GenPersonaId FROM ComProveedor WHERE ComProveedor.Id = (SELECT ComFactura.ComProveedorId FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId))) AS proveedor,
        (SELECT ComFactura.NumeroFactura FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS numero_factura,
        (SELECT VenVenta.Auditoria_FechaCreacion FROM VenVenta WHERE VenVenta.Id = (SELECT TOP 1 VenVentaDetalle.VenVentaId FROM VenVentaDetalle WHERE VenVentaDetalle.InvArticuloId = ComFacturaDetalle.InvArticuloId ORDER BY VenVentaDetalle.VenVentaId DESC)) AS ultima_venta
      FROM
        ComFacturaDetalle
      WHERE
        ComFacturaDetalle.ComFacturaId IN (SELECT ComFactura.Id FROM ComFactura WHERE ComFactura.FechaRegistro BETWEEN '$FInicial' AND '$FFinal') AND 
        (ComFacturaDetalle.FechaVencimiento IS NULL OR ComFacturaDetalle.FechaVencimiento = '')
      ORDER BY (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId);
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R31Q_Articulos_Corto_Vencimiento
    FUNCION: Ubicar productos sin fecha de vencimiento entre rango de fecha y vida util
    RETORNO: Lista de productos sin fecha de vencimiento entre rango de fecha y vida util
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R31Q_Articulos_Corto_Vencimiento($FInicial,$FFinal, $VUtil) {
    $sql = "
      SELECT
        (SELECT InvArticulo.CodigoArticulo FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS codigo_articulo,
        (SELECT InvArticulo.Id FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS id_articulo,
        (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = ComFacturaDetalle.InvArticuloId AND InvCodigoBarra.EsPrincipal = 1) AS codigo_barra,
        (SELECT InvArticulo.DescripcionLarga FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS descripcion,
        (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS fecha_registro,
        ComFacturaDetalle.FechaVencimiento AS fecha_vencimiento,
        ComFacturaDetalle.CantidadRecibidaFactura AS cantidad_recibida,
        (SELECT SUM(InvLoteAlmacen.Existencia) FROM InvLoteAlmacen WHERE InvLoteAlmacen.InvAlmacenId IN (1, 2) AND InvLoteAlmacen.InvArticuloId = ComFacturaDetalle.InvArticuloId) AS existencia,
        (SELECT ComFactura.Auditoria_Usuario FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS operador,
        DATEDIFF(DAY, (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId), ComFacturaDetalle.FechaVencimiento) AS vida_util,
        (SELECT GenPersona.Nombre FROM GenPersona WHERE GenPersona.Id = (SELECT ComProveedor.GenPersonaId FROM ComProveedor WHERE ComProveedor.Id = (SELECT ComFactura.ComProveedorId FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId))) AS proveedor,
        (SELECT ComFactura.NumeroFactura FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS numero_factura,
        (SELECT VenVenta.Auditoria_FechaCreacion FROM VenVenta WHERE VenVenta.Id = (SELECT TOP 1 VenVentaDetalle.VenVentaId FROM VenVentaDetalle WHERE VenVentaDetalle.InvArticuloId = ComFacturaDetalle.InvArticuloId ORDER BY VenVentaDetalle.VenVentaId DESC)) AS ultima_venta
      FROM
        ComFacturaDetalle
      WHERE
        ComFacturaDetalle.ComFacturaId IN (SELECT ComFactura.Id FROM ComFactura WHERE ComFactura.FechaRegistro BETWEEN '$FInicial' AND '$FFinal') AND 
        DATEDIFF(DAY, (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId), ComFacturaDetalle.FechaVencimiento) <= '$VUtil'
      ORDER BY (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) ASC;
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R31Q_Articulos_Sobre_Stock
    FUNCION: Ubicar productos con sobre stock
    RETORNO: Lista de productos con sobre stock
    DESAROLLADO POR: NISAUL DELGADO
  */
  function R31Q_Articulos_Sobre_Stock($FInicial,$FFinal, $sobreStock) {
    $FInicial = str_replace('-', '', $FInicial);
    $FFinal = str_replace('-', '', $FFinal);

    $sql = "
      SELECT
        (SELECT InvArticulo.Id FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS id_articulo,
        (SELECT InvArticulo.CodigoArticulo FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS codigo_articulo,
        (SELECT InvCodigoBarra.CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = ComFacturaDetalle.InvArticuloId AND InvCodigoBarra.EsPrincipal = 1) AS codigo_barra,
        (SELECT InvArticulo.DescripcionLarga FROM InvArticulo WHERE InvArticulo.Id = ComFacturaDetalle.InvArticuloId) AS descripcion,
        (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS fecha_registro,
        ComFacturaDetalle.FechaVencimiento AS fecha_vencimiento,
        ComFacturaDetalle.CantidadRecibidaFactura AS cantidad_recibida,
        (SELECT SUM(InvLoteAlmacen.Existencia) FROM InvLoteAlmacen WHERE InvLoteAlmacen.InvAlmacenId IN (1, 2) AND InvLoteAlmacen.InvArticuloId = ComFacturaDetalle.InvArticuloId) AS existencia,
        (SELECT ComFactura.Auditoria_Usuario FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS operador,
        (SELECT GenPersona.Nombre FROM GenPersona WHERE GenPersona.Id = (SELECT ComProveedor.GenPersonaId FROM ComProveedor WHERE ComProveedor.Id = (SELECT ComFactura.ComProveedorId FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId))) AS proveedor,
        (SELECT SUM(VenFacturaDetalle.Cantidad) FROM VenFacturaDetalle WHERE VenFacturaDetalle.InvArticuloId = ComFacturaDetalle.InvArticuloId AND VenFacturaDetalle.VenFacturaId IN (SELECT VenFactura.Id FROM VenFactura WHERE VenFactura.FechaDocumento BETWEEN DATEADD(DAY, -30, (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId)) AND (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId))) AS ventas_totales,
        (SELECT ComFactura.NumeroFactura FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId) AS numero_factura,
        (SELECT VenVenta.Auditoria_FechaCreacion FROM VenVenta WHERE VenVenta.Id = (SELECT TOP 1 VenVentaDetalle.VenVentaId FROM VenVentaDetalle WHERE VenVentaDetalle.InvArticuloId = ComFacturaDetalle.InvArticuloId ORDER BY VenVentaDetalle.VenVentaId DESC)) AS ultima_venta
      FROM
          ComFacturaDetalle
      WHERE
          ComFacturaDetalle.ComFacturaId IN (SELECT ComFactura.Id FROM ComFactura WHERE ComFactura.FechaRegistro BETWEEN '$FInicial' AND '$FFinal') AND 
          (SELECT SUM(InvLoteAlmacen.Existencia) FROM InvLoteAlmacen WHERE InvLoteAlmacen.InvAlmacenId IN (1, 2) AND InvLoteAlmacen.InvArticuloId = ComFacturaDetalle.InvArticuloId) > ComFacturaDetalle.CantidadRecibidaFactura
      ORDER BY (SELECT ComFactura.FechaRegistro FROM ComFactura WHERE ComFactura.Id = ComFacturaDetalle.ComFacturaId)
    ";

    return $sql;
  }

  function R31Q_Total_Venta($IdArticulo,$FInicial,$FFinal) {
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
      AND VenFacturaDetalle.InvArticuloId = '$IdArticulo'
    --Agrupamientos
      GROUP BY VenFacturaDetalle.InvArticuloId 
    --Ordenamientos
      ORDER BY UnidadesVendidas DESC
    ";
    return $sql;
  }
?>