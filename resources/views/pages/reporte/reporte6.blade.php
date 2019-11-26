@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
  <style>
    table, th, td, tr {
      padding-bottom: 10px;
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
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Pedido de productos
	</h1>
	<hr class="row align-items-start col-12">
	
<?php 
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  $ArtJson = "";
  $CodJson = "";

  if (isset($_GET['SEDE'])){      
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
  }
  echo '<hr class="row align-items-start col-12">';

  if ((isset($_GET['Descrip']))OR(isset($_GET['CodBar']))){

    $InicioCarga = new DateTime("now");

    if((isset($_GET['Descrip']))&&(!empty($_GET['Descrip']))) {
      R6_Pedido_Productos($_GET['SEDE'],$_GET['Descrip'],$_GET['fechaInicio'],$_GET['fechaFin'],$_GET['pedido'],'Descrip');
    }
    else if(isset($_GET['CodBar'])&&(!empty($_GET['CodBar']))){
      R6_Pedido_Productos($_GET['SEDE'],$_GET['IdCB'],$_GET['fechaInicio'],$_GET['fechaFin'],$_GET['pedido'],'CodBar');
    }
  
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Pedido de productos');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else{
    $InicioCarga = new DateTime("now");

    $sql = R6Q_Lista_Articulos();
    $ArtJson = FG_Armar_Json($sql,$_GET['SEDE']);

    $sql1 = R6Q_Lista_Articulos_CodBarra();
    $CodJson = FG_Armar_Json($sql1,$_GET['SEDE']);

    echo '
    <form id="form" autocomplete="off" action="" target="_blank">
        <table style="width:100%;">
          <tr>
          <td align="center">
              Pedido cant. dias
            </td>
            <td align="right" style="width:20%;">
              <input id="pedido" type="text" name="pedido" required style="width:100%;" autofocus>
            </td>
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
              <input id="SEDE" name="SEDE" type="hidden" value="';
              print_r($_GET['SEDE']);
              echo'">
            </td>
          </tr>
          <tr>
            <td colspan="6">
              <div class="autocomplete" style="width:90%;">
              <input id="myInput" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()">
              <input id="myId" name="IdD" type="hidden">
              </div>
              <input type="submit" value="Buscar" class="btn btn-outline-success">
            </td>
          </tr>
          <tr>
            <td colspan="6">
              <div class="autocomplete" style="width:90%;">
              <input id="myInputCB" type="text" name="CodBar" placeholder="Ingrese el codigo de barra del articulo " onkeyup="conteoCB()">
              <input id="myIdCB" name="IdCB" type="hidden">
              </div>
              <input type="submit" value="Buscar" class="btn btn-outline-success">
            </td>
          </tr>
        </table>
      </form>
      ';
    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  } 
?>
@endsection

@section('scriptsFoot')
  <?php
    if($ArtJson!=""){
  ?>
    <script type="text/javascript">
      ArrJs = eval(<?php echo $ArtJson ?>);
      autocompletado(document.getElementById("myInput"),document.getElementById("myId"), ArrJs);
    </script> 
  <?php
    }
  ?> 
  <?php
    if($CodJson!=""){
  ?>
    <script type="text/javascript">
      ArrJsCB = eval(<?php echo $CodJson ?>);
      autocompletadoCB(document.getElementById("myInputCB"),document.getElementById("myIdCB"), ArrJsCB);
    </script> 
  <?php
    }
  ?>  
@endsection

<?php
  /********************************************************************************/ 
  /*
    TITULO: R6_Pedido_Productos
    FUNCION: Arma una lista para el pedido de productos
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R6_Pedido_Productos($SedeConnection,$Valor,$FInicial,$FFinal,$DiasPedido,$Flag){

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

  /*INCIO PARA CALCULOS CON DIAS EN CERO*/
    $sql = MySQL_Rango_Dias_Cero();
    $result = mysqli_query($connCPharma,$sql);
    $row = $result->fetch_assoc();
    $DC_FInicialImp = date("d-m-Y", strtotime($row['Inicio']));
    $DC_FFinalImp = date("d-m-Y", strtotime($row['Fin']));
 /*FIN PARA CALCULOS CON DIAS EN CERO*/

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));
    $RangoDias = FG_Rango_Dias($FInicial,$FFinal);

    $DiasPedido = intval($DiasPedido);

    if($Flag=='Descrip') {
      $sql = R6Q_Descripcion_Like($Valor);
      $result = sqlsrv_query($conn,$sql);

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
      echo'<h6 align="center">Pedido en base a: '.$DiasPedido.' dias </h6>';
      echo'<h6 align="center">Periodo desde el '.$FInicialImp.' al '.$FFinalImp.' </h6>';
      echo'<h6 align="center">La data recolectada para el calculo <span style="color:red;">(Real)</span> va desde el <span style="color:red;">'.$DC_FInicialImp.'</span> al <span style="color:red;">'.$DC_FFinalImp.'</span> </h6>';
      echo'
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
          <thead class="thead-dark">
            <tr>
              <th scope="col" class="CP-sticky">#</th>
              <th scope="col" class="CP-sticky">Codigo</th>
              <th scope="col" class="CP-sticky">Codigo de Barra</th>
              <th scope="col" class="CP-sticky">Descripcion</th>              
              <th scope="col" class="CP-sticky">Existencia</th>
              <th scope="col" class="CP-sticky">Unidades vendidas</th>
              <th scope="col" class="CP-sticky">Unidades compradas</th>
              <th scope="col" class="CP-sticky">Venta diaria</th>
              <th scope="col" class="CP-sticky bg-danger text-white">Venta diaria (Real)</th>
              <th scope="col" class="CP-sticky">Dias restantes</th>
              <th scope="col" class="CP-sticky bg-danger text-white">Dias restantes (Real)</th>
              <th scope="col" class="CP-sticky">Precio (Con IVA) '.SigVe.'</th>
              <th scope="col" class="CP-sticky">Ultimo Precio (Sin IVA) '.SigVe.'</th>
              <th scope="col" class="CP-sticky">Ultimo Lote</th>
              <th scope="col" class="CP-sticky">Ultima Venta (En Rango)</th>
              <th scope="col" class="CP-sticky">Ultima Venta</th>
              <th scope="col" class="CP-sticky">Pedir</th> 
              <th scope="col" class="CP-sticky bg-danger text-white">Pedir (Real)</th>  
              <th scope="col" class="CP-sticky">Acciones</th>      
            </tr>
          </thead>
          <tbody>
      ';
      $contador = 1;
      while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
        $IdArticulo = $row["InvArticuloId"];

        $sql2 = MySQL_Cuenta_Veces_Dias_Cero($IdArticulo,$FInicial,$FFinal);
        $result2 = mysqli_query($connCPharma,$sql2);
        $row2 = $result2->fetch_assoc();
        $RangoDiasQuiebre = $row2['Cuenta'];

        $sql1 = R6Q_Integracion_Pedido_Productos($IdArticulo,$FInicial,$FFinal);
        $result1 = sqlsrv_query($conn,$sql1);
        $row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);

        $UnidadesVendidas = intval($row1["TotalUnidadesVendidas"]);
        $UnidadesCompradas = intval($row1["TotalUnidadesCompradas"]);
        $UltimaVentaRango = $row1["UltimaVentaRango"];

        $sql2 = R6Q_Detalle_Articulo($IdArticulo);
        $result2 = sqlsrv_query($conn,$sql2);
        $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

        $CodigoArticulo = $row2["CodigoInterno"];
        $CodigoBarra = $row2["CodigoBarra"];
        $Descripcion = FG_Limpiar_Texto($row2["Descripcion"]);
        $UltimoLote = $row2["UltimoLote"]; 
        $UltimoPrecio = $row2["UltimoPrecio"];
        $UltimaVenta = $row2["UltimaVenta"];
        $Existencia = intval($row2["Existencia"]);
        $IsIVA = $row2["Impuesto"];
        $Utilidad = $row2["Utilidad"];
        $TroquelAlmacen1 = $row2["TroquelAlmacen1"];
        $TroquelAlmacen2 = $row2["TroquelAlmacen2"];
        $PrecioCompraBruto = $row2["PrecioCompraBruto"];
        $VentaDiaria = FG_Venta_Diaria($UnidadesVendidas,$RangoDias);
        $DiasRestantes = FG_Dias_Restantes($Existencia,$VentaDiaria);

        $Precio = FG_Calculo_Precio($Existencia,$TroquelAlmacen1,$PrecioCompraBruto,$Utilidad,$IsIVA,$TroquelAlmacen2);
        $CantidadPedido = FG_Cantidad_Pedido($VentaDiaria,$DiasPedido,$Existencia);

        $VentaDiariaQuiebre = FG_Venta_Diaria($UnidadesVendidas,$RangoDiasQuiebre);
        $DiasRestantesQuiebre = FG_Dias_Restantes($Existencia,$VentaDiariaQuiebre);
        $CantidadPedidoQuiebre = FG_Cantidad_Pedido($VentaDiariaQuiebre,$DiasPedido,$Existencia);

        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
        echo '<td align="left">'.$CodigoArticulo.'</td>';
        echo '<td align="center">'.$CodigoBarra.'</td>';
        echo 
        '<td align="left" class="CP-barrido">
        <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$Descripcion.
        '</a>
        </td>';
        echo '<td align="center">'.intval($Existencia).'</td>';
        echo
        '<td align="center" class="CP-barrido">
        <a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImp.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
          .intval($UnidadesVendidas).
        '</a>
        </td>';
        echo
        '<td align="center" class="CP-barrido">
        <a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImp.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
          .intval($UnidadesCompradas).
        '</a>
        </td>';
        echo '<td align="center">'.round($VentaDiaria,2).'</td>';
        echo '<td align="center" class="bg-danger text-white">'.round($VentaDiariaQuiebre,2).'</td>';
        echo '<td align="center">'.round($DiasRestantes,2).'</td>';
        echo '<td align="center" class="bg-danger text-white">'.round($DiasRestantesQuiebre,2).'</td>';
        echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';

        if( ($Existencia==0) && (!is_null($UltimaVenta)) ){
          echo '<td align="center">'.number_format($UltimoPrecio,2,"," ,"." ).'</td>';
        }
        else{
          echo '<td align="center"> - </td>';
        }
        
        if(($UltimoLote)){
          echo '<td align="center">'.$UltimoLote->format('d-m-Y').'</td>';
        }
        else{
          echo '<td align="center"> - </td>';
        }

        if(!is_null($UltimaVentaRango)){
          echo '<td align="center">'.$UltimaVentaRango->format('d-m-Y').'</td>';
        }
        else{
          echo '<td align="center"> - </td>';
        }

        if(!is_null($UltimaVenta)){
          echo '<td align="center">'.$UltimaVenta->format('d-m-Y').'</td>';
        }
        else{
          echo '<td align="center"> - </td>';
        }

        echo '<td align="center">'.intval($CantidadPedido).'</td>';
         echo '<td align="center" class="bg-danger text-white">'.round($CantidadPedidoQuiebre,2).'</td>';

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
      echo'<input type="hidden" name="codigo_barra" value="'.$CodigoBarra.'">';
      echo'<input type="hidden" name="descripcion" value="'.$Descripcion.'">';
      echo'<input type="hidden" name="existencia_rpt" value="'.$Existencia.'">';
      echo'<input type="hidden" name="dias_restantes_rpt" value="'.$DiasRestantesQuiebre.'">';
      echo'<input type="hidden" name="origen_rpt" value="Pedido de productos">';
      echo'<input type="hidden" name="rango_rpt" value="Del: '.$FInicialImp.' Al: '.$FFinalImp.'">';
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
      /*BOTON PARA AGREGAR A LA ORDEN DE COMPRA*/

        echo '</tr>';
        $contador++;
      }
      echo '
        </tbody>
      </table>';
    }
    else if($Flag=='CodBar') {

      echo '
      <div class="input-group md-form form-sm form-1 pl-0">
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
      echo'<h6 align="center">Pedido en base a: '.$DiasPedido.' dias </h6>';
      echo'<h6 align="center">Periodo desde el '.$FInicialImp.' al '.$FFinalImp.' </h6>';
      echo'<h6 align="center">La data recolectada para el calculo <span style="color:red;">(Real)</span> va desde el <span style="color:red;">'.$DC_FInicialImp.'</span> al <span style="color:red;">'.$DC_FFinalImp.'</span> </h6>';
      echo'
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
          <thead class="thead-dark">
            <tr>
              <th scope="col" class="CP-sticky">#</th>
              <th scope="col" class="CP-sticky">Codigo</th>
              <th scope="col" class="CP-sticky">Codigo de Barra</th>
              <th scope="col" class="CP-sticky">Descripcion</th>              
              <th scope="col" class="CP-sticky">Existencia</th>
              <th scope="col" class="CP-sticky">Unidades vendidas</th>
              <th scope="col" class="CP-sticky">Unidades compradas</th>
              <th scope="col" class="CP-sticky">Venta diaria</th>
              <th scope="col" class="CP-sticky bg-danger text-white">Venta diaria (Real)</th>
              <th scope="col" class="CP-sticky">Dias restantes</th>
              <th scope="col" class="CP-sticky bg-danger text-white">Dias restantes (Real)</th>
              <th scope="col" class="CP-sticky">Precio (Con IVA) '.SigVe.'</th>
              <th scope="col" class="CP-sticky">Ultimo Lote</th>
              <th scope="col" class="CP-sticky">Ultima Venta (En Rango)</th>
              <th scope="col" class="CP-sticky">Ultima Venta</th>
              <th scope="col" class="CP-sticky">Pedir</th>
              <th scope="col" class="CP-sticky bg-danger text-white">Pedir (Real)</th>         
            </tr>
          </thead>
          <tbody>
      ';
        $contador = 1;
        $IdArticulo = $Valor;

        $sql2 = MySQL_Cuenta_Veces_Dias_Cero($IdArticulo,$FInicial,$FFinal);
        $result2 = mysqli_query($connCPharma,$sql2);
        $row2 = $result2->fetch_assoc();
        $RangoDiasQuiebre = $row2['Cuenta'];

        $sql1 = R6Q_Integracion_Pedido_Productos($IdArticulo,$FInicial,$FFinal);
        $result1 = sqlsrv_query($conn,$sql1);
        $row1 = sqlsrv_fetch_array($result1, SQLSRV_FETCH_ASSOC);

        $UnidadesVendidas = intval($row1["TotalUnidadesVendidas"]);
        $UnidadesCompradas = intval($row1["TotalUnidadesCompradas"]);
        $UltimaVentaRango = $row1["UltimaVentaRango"];

        $sql2 = R6Q_Detalle_Articulo($IdArticulo);
        $result2 = sqlsrv_query($conn,$sql2);
        $row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

        $CodigoArticulo = $row2["CodigoInterno"];
        $CodigoBarra = $row2["CodigoBarra"];
        $Descripcion = FG_Limpiar_Texto($row2["Descripcion"]);
        $UltimoLote = $row2["UltimoLote"];
        $UltimaVenta = $row2["UltimaVenta"];
        $Existencia = $row2["Existencia"];
        $IsIVA = $row2["Impuesto"];
        $Utilidad = $row2["Utilidad"];
        $TroquelAlmacen1 = $row2["TroquelAlmacen1"];
        $TroquelAlmacen2 = $row2["TroquelAlmacen2"];
        $PrecioCompraBruto = $row2["PrecioCompraBruto"];
        $VentaDiaria = FG_Venta_Diaria($UnidadesVendidas,$RangoDias);
        $DiasRestantes = FG_Dias_Restantes($Existencia,$VentaDiaria);

        $Precio = FG_Calculo_Precio($Existencia,$TroquelAlmacen1,$PrecioCompraBruto,$Utilidad,$IsIVA,$TroquelAlmacen2);
        $CantidadPedido = FG_Cantidad_Pedido($VentaDiaria,$DiasPedido,$Existencia);

        $VentaDiariaQuiebre = FG_Venta_Diaria($UnidadesVendidas,$RangoDiasQuiebre);
        $DiasRestantesQuiebre = FG_Dias_Restantes($Existencia,$VentaDiariaQuiebre);
        $CantidadPedidoQuiebre = FG_Cantidad_Pedido($VentaDiariaQuiebre,$DiasPedido,$Existencia);

        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
        echo '<td align="left">'.$CodigoArticulo.'</td>';
        echo '<td align="center">'.$CodigoBarra.'</td>';
        echo 
        '<td align="left" class="CP-barrido">
        <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$Descripcion.
        '</a>
        </td>';
        echo '<td align="center">'.intval($Existencia).'</td>';
        echo
        '<td align="center" class="CP-barrido">
        <a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImp.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
          .intval($UnidadesVendidas).
        '</a>
        </td>';
        echo
        '<td align="center" class="CP-barrido">
        <a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImp.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
          .intval($UnidadesCompradas).
        '</a>
        </td>';
        echo '<td align="center">'.round($VentaDiaria,2).'</td>';
        echo '<td align="center" class="bg-danger text-white">'.round($VentaDiariaQuiebre,2).'</td>';
        echo '<td align="center">'.round($DiasRestantes,2).'</td>';
        echo '<td align="center" class="bg-danger text-white">'.round($DiasRestantesQuiebre,2).'</td>';
        echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
        
        if(!is_null($UltimoLote)){
          echo '<td align="center">'.$UltimoLote->format('d-m-Y').'</td>';
        }
        else{
          echo '<td align="center"> - </td>';
        }

        if(!is_null($UltimaVentaRango)){
          echo '<td align="center">'.$UltimaVentaRango->format('d-m-Y').'</td>';
        }
        else{
          echo '<td align="center"> - </td>';
        }

        if(($UltimaVenta)){
          echo '<td align="center">'.$UltimaVenta->format('d-m-Y').'</td>';
        }
        else{
          echo '<td align="center"> - </td>';
        }
        echo '<td align="center">'.intval($CantidadPedido).'</td>';
        echo '<td align="center" class="bg-danger text-white">'.round($CantidadPedidoQuiebre,2).'</td>';

        /*BOTON PARA AGREGAR A LA ORDEN DE COMPRA*/

      $sqlDetalleOrden = "SELECT COUNT(*) AS CuentaOr FROM orden_compra_detalles WHERE id_articulo = '$IdArticulo'";
      $resultDetalleOrden = mysqli_query($connCPharma,$sqlDetalleOrden);
      $rowDetalleOrden = $resultDetalleOrden->fetch_assoc();
      $cuentaArticuloOrden = $rowDetalleOrden['CuentaOr'];

      echo'
      <td style="width:140px;">
        <form action="/ordenCompraDetalle/create" method="PRE" style="display: block; width:100%;" target="_blank">
      ';
      echo'<input type="hidden" name="id_articulo" value="'.$IdArticulo.'">';
      echo'<input type="hidden" name="codigo_articulo" value="'.$CodigoArticulo.'">';
      echo'<input type="hidden" name="codigo_barra" value="'.$CodigoBarra.'">';
      echo'<input type="hidden" name="descripcion" value="'.$Descripcion.'">';
      echo'<input type="hidden" name="existencia_rpt" value="'.$Existencia.'">';
      echo'<input type="hidden" name="dias_restantes_rpt" value="'.$DiasRestantesQuiebre.'">';
      echo'<input type="hidden" name="origen_rpt" value="Pedido de productos">';
      echo'<input type="hidden" name="rango_rpt" value="Del: '.$FInicialImp.' Al: '.$FFinalImp.'">';
      echo'
          <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm" value="SI" style="width:100%;">Agregar</button>
        </form>
      ';
      if( $cuentaArticuloOrden!=0 ) {
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
      /*BOTON PARA AGREGAR A LA ORDEN DE COMPRA*/
      
        echo '</tr>';
        echo '
          </tbody>
      </table>';
    }
    sqlsrv_close($conn);
  }
  /**********************************************************************************/
  /*
    TITULO: R6Q_Lista_Articulos
    FUNCION: Armar una lista de articulos
    RETORNO: Lista de articulos
    DESAROLLADO POR: SERGIO COVA
  */
  function R6Q_Lista_Articulos() {
    $sql = "
      SELECT
      InvArticulo.Descripcion,
      InvArticulo.Id
      FROM InvArticulo
      ORDER BY InvArticulo.Descripcion ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R6Q_Lista_Articulos_CodBarra
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R6Q_Lista_Articulos_CodBarra() {
    $sql = "
      SELECT
      (SELECT CodigoBarra
      FROM InvCodigoBarra 
      WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
      AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
      InvArticulo.Id
      FROM InvArticulo
      ORDER BY CodigoBarra ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R6Q_Descripcion_Like
    FUNCION: Consulta los articulos que conindicen con la palabra buscada
    RETORNO: lista de id de articulos en coincidencia
    DESAROLLADO POR: SERGIO COVA
  */
  function R6Q_Descripcion_Like($DescripLike) {   
    $sql = "
      SELECT
      InvArticulo.Id AS InvArticuloId
      FROM InvArticulo
      WHERE InvArticulo.Descripcion LIKE '%$DescripLike%'
    ";          
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R6Q_Integracion_Pedido_Productos
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
    DESAROLLADO POR: SERGIO COVA
  */
  function R6Q_Integracion_Pedido_Productos($IdArticulo,$FInicial,$FFinal) {
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
      AND VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId),CAST(0 AS INT)))) AS TotalVenta,
    -- Ultima Venta (Rango)
      (SELECT TOP 1
      CONVERT(DATE,VenFactura.FechaDocumento)
      FROM VenFactura
      INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
      WHERE (VenFacturaDetalle.InvArticuloId = '$IdArticulo')
      AND (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
      ORDER BY FechaDocumento DESC) AS UltimaVentaRango
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
      ORDER BY TotalUnidadesVendidas DESC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R6Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R6Q_Detalle_Articulo($IdArticulo) {
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
      ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen1,
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
    --Ultimo Precio Sin Iva
      (SELECT TOP 1
      (ROUND(CAST((VenVentaDetalle.M_PrecioNeto) AS DECIMAL(38,2)),2,0))
      FROM VenVenta
      INNER JOIN VenVentaDetalle ON VenVentaDetalle.VenVentaId = VenVenta.Id
      WHERE VenVentaDetalle.InvArticuloId = InvArticulo.Id
      ORDER BY VenVenta.FechaDocumentoVenta DESC) AS UltimoPrecio,
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
?>