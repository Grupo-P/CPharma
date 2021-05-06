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

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Historico de productos
	</h1>
	<hr class="row align-items-start col-12">
  <?php
  	include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');

    $ArtJson = "";
    $CodJson = "";

    if (isset($_GET['SEDE'])) {
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';

  	if (isset($_GET['Id'])) {
      $InicioCarga = new DateTime("now");

      R2_Historico_Producto($_GET['SEDE'],$_GET['Id']);
      FG_Guardar_Auditoria('CONSULTAR','REPORTE','Historico de productos');

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  	}
  	else {
      $InicioCarga = new DateTime("now");

      $sql = R2Q_Lista_Articulos();
      $ArtJson = FG_Armar_Json($sql,$_GET['SEDE']);

      $sql1 = R2Q_Lista_Articulos_CodBarra();
      $CodJson = FG_Armar_Json($sql1,$_GET['SEDE']);

  		echo '
  		<form autocomplete="off" action="" target="_blank">
  	    <div class="autocomplete" style="width:90%;">
          <input id="myInput" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()">
  	      <input id="myId" name="Id" type="hidden">
  	    </div>
        <input id="SEDE" name="SEDE" type="hidden" value="';
          print_r($_GET['SEDE']);
          echo'">
  	    <input type="submit" value="Buscar" class="btn btn-outline-success">
      </form>
      <br/>
      <form autocomplete="off" action="" target="_blank">
        <div class="autocomplete" style="width:90%;">
          <input id="myInputCB" type="text" name="CodBar" placeholder="Ingrese el codigo de barra del articulo " onkeyup="conteoCB()">
          <input id="myIdCB" name="Id" type="hidden">
        </div>
        <input id="SEDE" name="SEDE" type="hidden" value="';
          print_r($_GET['SEDE']);
          echo'">
        <input type="submit" value="Buscar" class="btn btn-outline-success">
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
  /**********************************************************************************/
  /*
    TITULO: R2_Historico_Producto
    FUNCION: Armar una tabla del historico de compra del articulo
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
  function R2_Historico_Producto($SedeConnection,$IdArticulo) {

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $sql = R2Q_Detalle_Articulo($IdArticulo);
    $result = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

    $ResultCPharma = mysqli_query($connCPharma,"SELECT * FROM unidads WHERE id_articulo = '$IdArticulo'");
    $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
    $unidadminima = $RowCPharma['divisor']." ".$RowCPharma['unidad_minima'];
    $unidadminima = ($unidadminima)?$unidadminima:"-";

    $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
    $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
    $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
    $clasificacion = $RowCPharma['clasificacion'];
    $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";

    $sql2 = R2Q_Historico_Articulo($IdArticulo);
    $result2 = sqlsrv_query($conn,$sql2);

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
    $UltimaVenta = $row["UltimaVenta"];
    $UltimoLote = $row["UltimoLote"];
    $UltimoPrecio = $row["UltimoPrecio"];
    $Marca = $row["Marca"];

    $Gravado = FG_Producto_Gravado($IsIVA);
    $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
    $TasaActual = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
    $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

    $Utilidad = FG_Utilidad_Alfa($UtilidadArticulo,$UtilidadCategoria);
    $Utilidad = (1 - $Utilidad)*100;


    $fechaActual = new DateTime('now');
    $fechaActual = date_format($fechaActual,'Y-m-d');

    $sqlUltimoConteo = "
    SELECT fecha_conteo FROM inventario_detalles WHERE id_articulo = '$IdArticulo' ORDER BY fecha_conteo DESC LIMIT 1;
   ";

    $ResultCPharma = mysqli_query($connCPharma,$sqlUltimoConteo);
    $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
    $ultimoConteo = $RowCPharma['fecha_conteo'];

    $sqlCategorizacion = "
    SELECT
    categorias.nombre as categoria,
    subcategorias.nombre as subcategoria
    FROM categorizacions
    INNER JOIN categorias ON categorias.codigo = codigo_categoria
    INNER JOIN subcategorias ON subcategorias.codigo = codigo_subcategoria
    WHERE id_articulo = '$IdArticulo';
   ";

    $ResultCategorizacion = mysqli_query($connCPharma,$sqlCategorizacion);
    $RowCategorizacion = mysqli_fetch_assoc($ResultCategorizacion);
    $categoria = $RowCategorizacion['categoria'];
    $subcategoria = $RowCategorizacion['subcategoria'];

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

    <table class="table table-striped table-bordered col-12 sortable">
      <thead class="thead-dark">
        <tr>
          <th scope="col">Codigo</th>
          <th scope="col">Codigo de barra</td>
          <th scope="col">Descripcion</td>
          <th scope="col">Existencia</td>
          <th scope="col">Clasificacion</td>
          <th scope="col">Precio</br>(Con IVA) '.SigVe.'</td>
          <th scope="col">Gravado?</td>
          <th scope="col">Utilidad Configurada</td>
          <th scope="col">Troquel</td>
          <th scope="col">Marca</td>
          <th scope="col">Categoria</td>
          <th scope="col">Subcategoria</td>
          <th scope="col">Dolarizado?</td>
          <th scope="col">Tasa actual '.SigVe.'</td>
          <th scope="col">Precio en divisa</br>(Con IVA) '.SigDolar.'</td>
          <th scope="col" class="bg-warning text-white">Venta diaria 15</th>
          <th scope="col" class="bg-warning text-white">Días restantes 15</th>
          <th scope="col">Ultima Venta</th>
          <th scope="col" class="bg-warning text-white">Ultimo Lote</th>
          <th scope="col">Unidad Minima</th>
          <th scope="col">Ultimo Conteo</th>
          <th scope="col">Ultimo Precio (Sin IVA) '.SigVe.'</th>
          <th scope="col">Transito</th>
        </tr>
      </thead>
      <tbody>
    ';
    echo '<tr>';
    echo '<td>'.$CodigoArticulo.'</td>';
    echo '<td align="center">'.$CodigoBarra.'</td>';
    echo
      '<td align="left" class="CP-barrido">
      <a href="/reporte10?Descrip='.$Descripcion.'&Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Descripcion.
      '</a>
      </td>';
    echo '<td align="center">'.intval($Existencia).'</td>';
    echo '<td align="center"> '.$clasificacion.'</td>';
    echo '<td align="center">'.number_format($Precio,2,"," ,"." ).'</td>';
    echo '<td align="center">'.$Gravado.'</td>';
    echo '<td align="center">'.number_format($Utilidad,2,"," ,"." ).' %</td>';

    if($TroquelAlmacen1!=NULL){
      echo '<td align="center">'.number_format($TroquelAlmacen1,2,"," ,"." ).'</td>';
    }
    else if($TroquelAlmacen2!=NULL){
      echo '<td align="center">'.number_format($TroquelAlmacen2,2,"," ,"." ).'</td>';
    }
    else{
      echo '<td align="center"> - </td>';
    }

    echo '<td align="center">'.$Marca.'</td>';

    echo '<td align="center">'.$categoria.'</td>';

    echo '<td align="center">'.$subcategoria.'</td>';

    echo '<td align="center">'.$Dolarizado.'</td>';

    /*TASA DOLAR VENTA*/
    $Tasa = DB::table('tasa_ventas')->where('moneda', 'Dolar')->value('tasa');

    $Tasa = ($Tasa) ? $Tasa : 0;

    if($TasaActual!=0){
      echo '<td align="center">'.number_format($TasaActual,2,"," ,"." ).'</td>';
      $PrecioDolar = $Precio/$Tasa;
      echo '<td align="center">'.number_format($PrecioDolar,2,"," ,"." ).'</td>';
    }
    else{
      echo '<td align="center">0,00</td>';
      echo '<td align="center">0,00</td>';
    }

    $fechaInicioDiasCero = date_modify(date_create(), '-15day');
    $fechaInicioDiasCero = date_format($fechaInicioDiasCero, 'Y-m-d');

    $sql3 = R31Q_Total_Venta($IdArticulo, $fechaInicioDiasCero, date_create()->format('Y-m-d'));

    $sql2 = MySQL_Cuenta_Veces_Dias_Cero($IdArticulo,$fechaInicioDiasCero,date_create()->format('Y-m-d'));
    $result22 = mysqli_query($connCPharma,$sql2);
    $row2 = $result22->fetch_assoc();
    $RangoDiasQuiebre = $row2['Cuenta'];

    $result3 = sqlsrv_query($conn,$sql3);
    $row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC);

    $VentaDiariaQuiebre = FG_Venta_Diaria($row3['TotalUnidadesVendidas'],$RangoDiasQuiebre);
    $DiasRestantesQuiebre = FG_Dias_Restantes(intval($Existencia),$VentaDiariaQuiebre);

    echo '<td align="center" class="bg-warning text-white">'.$VentaDiariaQuiebre.'</td>';
    echo '<td align="center" class="bg-warning text-white">'.$DiasRestantesQuiebre.'</td>';

    if(!is_null($UltimaVenta)){
      echo '<td align="center">'.$UltimaVenta->format('d-m-Y').'</td>';
    }
    else{
      echo '<td align="center"> - </td>';
    }

    if(!is_null($UltimoLote)){
      echo '<td align="center" class="bg-warning text-white">'.$UltimoLote->format('d-m-Y').'</td>';
    }
    else{
      echo '<td align="center" class="bg-warning text-white"> - </td>';
    }

    echo '<td>'.$unidadminima.'</td>';

    echo '<td align="center">'.$ultimoConteo.'</td>';

    if( ($Existencia==0) && (!is_null($UltimaVenta)) ){
      echo '<td align="center">'.number_format($UltimoPrecio,2,"," ,"." ).'</td>';
    }
    else{
      echo '<td align="center"> - </td>';
    }

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

    if($flag != true){
      echo'
      <td align="center">
          <form action="/ordenCompraDetalle/0" method="PRE" style="display: block; width:100%;" target="_blank">';
          echo'<input type="hidden" name="id_articulo" value="'.$IdArticulo.'">';
          echo'
            <button type="submit" role="button" class="btn btn-outline-danger btn-sm" style="width:100%;">En Transito</button>
          </form>
      </tr>
      ';
    } else {
        echo '<td></td>';
    }

    echo '
      </tr>
      </tbody>
    </table>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Proveedor</th>
            <th scope="col" class="CP-sticky">N° Factura</th>
            <th scope="col" class="CP-sticky">Fecha de documento</th>
            <th scope="col" class="CP-sticky">Fecha de registro</th>
            <th scope="col" class="CP-sticky bg-warning text-dark">Días llegada</th>
            <th scope="col" class="CP-sticky">Fecha de vencimiento</th>
            <th scope="col" class="CP-sticky">Vida util<br>(Dias)</th>
            <th scope="col" class="CP-sticky">Dias para vencer<br>(Dias)</th>
            <th scope="col" class="CP-sticky">Cantidad recibida</th>
            <th scope="col" class="CP-sticky bg-warning text-dark">Saldo</th>
            <th scope="col" class="CP-sticky">Costo bruto</br>(Sin IVA) '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Tasa en historico '.SigVe.'</br>(fecha documento)</th>
            <th scope="col" class="CP-sticky">Tasa en historico '.SigVe.'</br>(fecha registro)</th>
            <th scope="col" class="CP-sticky">Costo bruto</br>HOY (Sin IVA) '.SigVe.'</br>(fecha documento)</th>
            <th scope="col" class="CP-sticky">Costo bruto</br>HOY (Sin IVA) '.SigVe.'</br>(fecha registro)</th>
          <th scope="col" class="CP-sticky">Costo en divisa</br>(Sin IVA) '.SigDolar.'</br>(fecha documento)</th>
          <th scope="col" class="CP-sticky bg-danger text-white">Costo en divisa</br>(Sin IVA) '.SigDolar.'</br>(fecha registro)</th>
          <th scope="col" class="CP-sticky">Precio Lote en '.SigVe.'</th>
          <th scope="col" class="CP-sticky">Precio Lote en '.SigVe.'<br>Historico</th>
          <th scope="col" class="CP-sticky">Precio Lote en '.SigDolar.'</th>
          <th scope="col" class="CP-sticky">Operador</th>
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    $Saldo = 0;

    while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {

        $fechaDocumento = $row2["FechaDocumento"];
        $fechaDocumentoMostrar = $fechaDocumento->format('d-m-Y');
        $fechaDocumentoLink = $fechaDocumento->format('Y-m-d');

        $fechaVencimiento = ($row2["FechaVencimiento"]!=NULL)?$row2["FechaVencimiento"]->format('d-m-Y'):'-';
        $vidaUtil = ($row2['VidaUtil']!=NULL)?$row2['VidaUtil']:'-';
        $diasVencer = ($row2['DiasVecer']!=NULL)?$row2['DiasVecer']:'-';

        $diasLlegada = ($row2['FechaRegistro']) ? date_diff($row2['FechaRegistro'], date_create())->format('%a') : '';

        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
        echo
        '<td align="left" class="CP-barrido">
        <a href="/reporte7?Nombre='.FG_Limpiar_Texto($row2["Nombre"]).'&Id='.$row2["Id"].'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
          .FG_Limpiar_Texto($row2["Nombre"]).
        '</a>
        </td>';

        echo '<td align="center">
        <a class="CP-barrido" href="/reporte30?SEDE='.$SedeConnection.'&IdFact='.$row2["idFactura"].'&IdProv='.$row2["Id"].'&NombreProv='.FG_Limpiar_Texto($row2["Nombre"]).'" style="text-decoration: none; color: black" target="_blank">'
          .$row2["numero"].
        '</a>
        </td>';

        echo
        '<td align="center" class="CP-barrido">
        <a href="/reporte12?fechaInicio='.$fechaDocumentoLink.'&fechaFin='.$fechaActual.'&SEDE='.$SedeConnection.'&Descrip='.$Descripcion.'&Id='.$IdArticulo.'&CodBar=&IdCB=&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
          .$fechaDocumentoMostrar.
        '</a>
        </td>';

        echo '<td align="center">'.$row2["FechaRegistro"]->format('d-m-Y').'</td>';
        echo '<td align="center" class="bg-warning">'.$diasLlegada.'</td>';
        echo '<td align="center">'.$fechaVencimiento.'</td>';
        echo '<td align="center">'.$vidaUtil.'</td>';

        echo '<td align="center">'.$diasVencer.'</td>';

       echo
        '<td align="center" class="CP-barrido">
        <a href="/reporte6?pedido=15&fechaInicio='.$fechaDocumentoLink.'&fechaFin='.$fechaActual.'&SEDE='.$SedeConnection.'&flag=BsqDescrip&Descrip='.$Descripcion.'&IdD='.$IdArticulo.'&CodBar=&IdCB=" style="text-decoration: none; color: black;" target="_blank">'
          .intval($row2['CantidadRecibidaFactura']).
        '</a>
        </td>';

        $Saldo = $Saldo + $row2['CantidadRecibidaFactura'];

        echo '<td align="center" class="bg-warning">'.$Saldo.'</td>';

        echo '<td align="center">'.number_format($row2["M_PrecioCompraBruto"],2,"," ,"." ).'</td>';

        $FechaHistDoc = $row2["FechaDocumento"]->format('Y-m-d');
        $TasaDoc = FG_Tasa_Fecha($connCPharma,$FechaHistDoc);

        $FechaHistFR = $row2["FechaRegistro"]->format('Y-m-d');
        $TasaFR = FG_Tasa_Fecha($connCPharma,$FechaHistFR);

        if( ($TasaDoc != 0) && ($TasaFR != 0) ) {

          echo '<td align="center">'.number_format($TasaDoc,2,"," ,"." ).'</td>';
          echo '<td align="center">'.number_format($TasaFR,2,"," ,"." ).'</td>';

          $CostoDivisaDoc = $row2["M_PrecioCompraBruto"]/$TasaDoc;
          $CostoDivisaFR = $row2["M_PrecioCompraBruto"]/$TasaFR;

          if($TasaActual!=0){
            $CostoBrutoHoyDoc = $CostoDivisaDoc*$TasaActual;
            echo '<td align="center">'.number_format($CostoBrutoHoyDoc,2,"," ,"." ).'</td>';

            $CostoBrutoHoyFR = $CostoDivisaFR*$TasaActual;
            echo '<td align="center">'.number_format($CostoBrutoHoyFR,2,"," ,"." ).'</td>';
          }
          else{
            echo '<td align="center">0,00</td>';
            echo '<td align="center">0,00</td>';
          }

          echo '<td align="center">'.number_format($CostoDivisaDoc,2,"," ,"." ).'</td>';
          echo '<td class="bg-danger text-white" align="center">'.number_format($CostoDivisaFR,2,"," ,"." ).'</td>';

          $preciolote = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$row2["M_PrecioCompraBruto"]);
          echo '<td align="center">'.number_format($preciolote,2,"," ,"." ).'</td>';

          if($TasaActual!=0){
            $preciolotehist = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$CostoBrutoHoyFR);
            echo '<td align="center">'.number_format($preciolotehist,2,"," ,"." ).'</td>';

            $preciolotediv = FG_Precio_Calculado_Alfa($UtilidadArticulo,$UtilidadCategoria,$IsIVA,$CostoDivisaFR);
            echo '<td align="center">'.number_format($preciolotediv,2,"," ,"." ).'</td>';
          }
          else{
            echo '<td align="center">0,00</td>';
            echo '<td align="center">0,00</td>';
          }

        }
        else{
          echo '<td align="center">0,00</td>';
          echo '<td align="center">0,00</td>';
          echo '<td align="center">0,00</td>';
          echo '<td align="center">0,00</td>';
          echo '<td align="center">0,00</td>';
          echo '<td align="center">0,00</td>';
          echo '<td align="center">0,00</td>';
          echo '<td align="center">0,00</td>';
          echo '<td align="center">0,00</td>';
        }

        echo '<td align="center">'.$row2["operador"].'</td>';

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
    TITULO: R2Q_Lista_Articulos
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R2Q_Lista_Articulos() {
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
    TITULO: R2Q_Lista_Articulos_CodBarra
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R2Q_Lista_Articulos_CodBarra() {
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
    TITULO: R2Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R2Q_Detalle_Articulo($IdArticulo) {
    $sql = "
      SELECT
--Id Articulo
    InvArticulo.Id AS IdArticulo,
--Categoria Articulo
  InvArticulo.InvCategoriaId ,
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
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen1,
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
--Marca
    InvMarca.Nombre as Marca,
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
--Ultimo Precio Sin Iva
    (SELECT TOP 1
    (ROUND(CAST((VenVentaDetalle.M_PrecioNeto) AS DECIMAL(38,2)),2,0))
    FROM VenVenta
    INNER JOIN VenVentaDetalle ON VenVentaDetalle.VenVentaId = VenVenta.Id
    WHERE VenVentaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY VenVenta.FechaDocumentoVenta DESC) AS UltimoPrecio,
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
    LEFT JOIN InvMarca ON InvMarca.Id = InvArticulo.InvMarcaId
--Condicionales
    WHERE InvArticulo.Id = '$IdArticulo'
--Agrupamientos
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId, InvMarca.Nombre
--Ordanamiento
    ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }

  /**********************************************************************************/
  /*
    TITULO: R2Q_Historico_Articulo
    FUNCION: Armar la tabla del historico de articulos
    RETORNO: La tabla de historico del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R2Q_Historico_Articulo($IdArticulo) {
    $sql = "
      SELECT
      ComProveedor.Id,
      GenPersona.Nombre,
      CONVERT(DATE,ComFactura.FechaRegistro) As FechaRegistro,
      CONVERT(DATE,ComFactura.FechaDocumento) As FechaDocumento,
      CONVERT(DATE,ComFacturaDetalle.FechaVencimiento) As FechaVencimiento,
      DATEDIFF(DAY,CONVERT(DATE,ComFactura.FechaRegistro),CONVERT(DATE,ComFacturaDetalle.FechaVencimiento)) as VidaUtil,
      DATEDIFF(DAY,CONVERT(DATE,GETDATE()),CONVERT(DATE,ComFacturaDetalle.FechaVencimiento)) as DiasVecer,
      ComFacturaDetalle.CantidadRecibidaFactura,
      ComFacturaDetalle.M_PrecioCompraBruto,
      ComFactura.auditoria_usuario as operador,
      ComFactura.NumeroFactura as numero,
      ComFactura.Id as idFactura
      FROM InvArticulo
      INNER JOIN ComFacturaDetalle ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
      INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
      INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
      INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
      WHERE InvArticulo.Id = '$IdArticulo'
      ORDER BY ComFactura.FechaRegistro DESC
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
