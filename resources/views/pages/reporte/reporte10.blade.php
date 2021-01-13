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
		Analítico de precios
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

    $sql = R10Q_Detalle_Articulo($IdArticulo);
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

    $sql2 = R10Q_Analitico_Productos($IdArticulo);
    $result2 = sqlsrv_query($conn,$sql2);

    $sql3 = R10Q_Analitico_Productos_SE($IdArticulo);
    $result3 = sqlsrv_query($conn,$sql3);

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
          <th scope="col">Ultima Venta</th>
          <th scope="col">Unidad Minima</th>
          <th scope="col">Ultimo Conteo</th>
          <th scope="col">Ultimo Precio (Sin IVA) '.SigVe.'</th>
        </tr>
      </thead>
      <tbody>
    ';
    echo '<tr>';
    echo '<td>'.$CodigoArticulo.'</td>';
    echo '<td align="center">'.$CodigoBarra.'</td>';
    
    echo '        
      <td align="left" class="CP-barrido">
          <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
              .$Descripcion
          .'</a>
      </td>        
    ';

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

    if($TasaActual!=0){
      echo '<td align="center">'.number_format($TasaActual,2,"," ,"." ).'</td>';
      $PrecioDolar = $Precio/$TasaActual;
      echo '<td align="center">'.number_format($PrecioDolar,2,"," ,"." ).'</td>';
    }
    else{
      echo '<td align="center">0,00</td>';
      echo '<td align="center">0,00</td>';
    }

    if(!is_null($UltimaVenta)){
      echo '<td align="center">'.$UltimaVenta->format('d-m-Y').'</td>';
    }
    else{
      echo '<td align="center"> - </td>';
    }

    echo '<td>'.$unidadminima.'</td>';

    echo '<td align="center">'.$ultimoConteo.'</td>';

    if( ($Existencia==0) && (!is_null($UltimaVenta)) ){
      echo '<td align="center">'.number_format($UltimoPrecio,2,"," ,"." ).'</td>';
    }
    else{
      echo '<td align="center"> - </td>';
    }

    echo '
      </tr>
      </tbody>
    </table>';

    //Tabla Lotes con exitencia
    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Origen</th>
            <th scope="col" class="CP-sticky">Detalle</th>
            <th scope="col" class="CP-sticky">Numero<br>Referencia</th>
            <th scope="col" class="CP-sticky">Fecha de Creacion<br>Lote</th>
            <th scope="col" class="CP-sticky">Fecha de<br>Vencimiento</th>
            <th scope="col" class="CP-sticky">Numero Lote</th>
            <th scope="col" class="CP-sticky">Lote Fabricante</th>
            <th scope="col" class="CP-sticky">Almacen</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Costo Bruto Total</br>(Sin IVA) '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Costo Unitario </br> Bruto (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Precio Troquelado </br> Bruto (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Tasa Mercado</th>
            <th scope="col" class="CP-sticky">Costo Unitario </br> Bruto (Sin IVA) '.SigDolar.'</th>
            <th scope="col" class="CP-sticky">Responsable</th>    
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    $IdLotePivote = 0;
    $LoteAlmacenIdPivorte = 0;

    while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {

      if( ($row2["InvLoteId"]!=$IdLotePivote) || 
          ($row2["InvLoteId"]==$IdLotePivote && $row2["LoteAlmacenId"]!=$LoteAlmacenIdPivorte) 
       ){
        
        $fechaVencimiento = ($row2["FechaVencimiento"]!=NULL)?$row2["FechaVencimiento"]->format('d-m-Y'):'-';            

        $IdLotePivote = $row2["InvLoteId"];
        $LoteAlmacenIdPivorte = $row2["LoteAlmacenId"];

        echo '
          <td align="center"><strong>'.intval($contador).'</strong></td>            
          <td align="center">'.FG_Limpiar_Texto($row2["Origen"]).'</td>            
        ';

        if($row2["Origen"]=="COMPRA"){

          $sql4 = R10Q_Caso_Compra($row2["InvLoteId"],$IdArticulo,$row2["LoteAlmacenId"]);

          try {
            $result4 = sqlsrv_query($conn,$sql4);
            $row4 = sqlsrv_fetch_array($result4, SQLSRV_FETCH_ASSOC);

            echo '                    
              <td align="center">'.FG_Limpiar_Texto($row4["Proveedor"]).'</td>
              <td align="center">'.($row4["NumeroFactura"]).'</td>        
            ';
            
          } catch (Exception $e) {

            echo '                    
              <td align="center">'.$row2["InvLoteId"].'|'.$IdArticulo.'|'.$row2["LoteAlmacenId"].'</td>
              <td align="center">*</td>        
            ';
            
          }

        }else{
           echo '                    
            <td align="center">'.FG_Limpiar_Texto($row2["Causa"]).'</td>            
            <td align="center">'.($row2["NumeroReferencia"]).'</td>  
          ';
        }

        $TasaFecha = FG_Tasa_Fecha($connCPharma,$row2["FechaCreacionLote"]->format("Y-m-d"));
        if(!isset($TasaFecha)){
           $TasaFecha = 0;
        }
        
        echo '                  
          <td align="center">'.($row2["FechaCreacionLote"]->format('d-m-Y')).'</td>        
          <td align="center">'.($fechaVencimiento).'</td>
          <td align="center">'.($row2["NumeroLote"]).'</td>
          <td align="center">'.($row2["LoteFabricante"]).'</td>
          <td align="center">'.($row2["Almacen"]).'</td>
          <td align="center">'.intval($row2["Existencia"]).'</td>          
          <td align="center">'.number_format($row2["CostoTotal"],2,"," ,"." ).'</td>
          <td align="center">'.number_format($row2["CostoUnitario"],2,"," ,"." ).'</td>
          <td align="center">'.number_format($row2["PrecioTroquelado"],2,"," ,"." ).'</td>
          <td align="center">'.number_format($TasaFecha,2,"," ,"." ).'</td>
        ';

        if($TasaFecha!=0){
          $CostoDivisa = $row2["CostoUnitario"] / $TasaFecha;
          echo '                    
            <td align="center">'.number_format($CostoDivisa,2,"," ,"." ).'</td>
          ';
        }else{
          echo '                    
            <td align="center">0.00</td>
          ';
        }

        echo '                    
            <td align="center">'.FG_Limpiar_Texto($row2["Responsable"]).'</td>
          ';

        echo '</tr>';
        $contador++;
      }
    }
      echo '
      </tbody>
    </table>';
    //Tabla Lotes con exitencia
  /******************************************************************/
    //Tabla Lotes SIN exitencia
    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Origen</th>
            <th scope="col" class="CP-sticky">Detalle</th>
            <th scope="col" class="CP-sticky">Numero<br>Referencia</th>
            <th scope="col" class="CP-sticky">Fecha de Creacion<br>Lote</th>
            <th scope="col" class="CP-sticky">Fecha de<br>Vencimiento</th>
            <th scope="col" class="CP-sticky">Numero Lote</th>
            <th scope="col" class="CP-sticky">Lote Fabricante</th>
            <th scope="col" class="CP-sticky">Almacen</th>
            <th scope="col" class="CP-sticky">Existencia</th>
            <th scope="col" class="CP-sticky">Costo Bruto Total</br>(Sin IVA) '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Costo Unitario </br> Bruto (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Precio Troquelado </br> Bruto (Sin IVA) '.SigVe.'</th>
            <th scope="col" class="CP-sticky">Tasa Mercado</th>
            <th scope="col" class="CP-sticky">Costo Unitario </br> Bruto (Sin IVA) '.SigDolar.'</th>
            <th scope="col" class="CP-sticky">Responsable</th>    
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    $IdLotePivote = 0;
    $LoteAlmacenIdPivorte = 0;

    while($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {

      if( ($row3["InvLoteId"]!=$IdLotePivote) ||
          ($row3["InvLoteId"]==$IdLotePivote && $row3["LoteAlmacenId"]!=$LoteAlmacenIdPivorte) 
       ){
        
        $fechaVencimiento = ($row3["FechaVencimiento"]!=NULL)?$row3["FechaVencimiento"]->format('d-m-Y'):'-';            

        $IdLotePivote = $row3["InvLoteId"];
        $LoteAlmacenIdPivorte = $row3["LoteAlmacenId"];

        echo '
          <td align="center"><strong>'.intval($contador).'</strong></td>            
          <td align="center">'.FG_Limpiar_Texto($row3["Origen"]).'</td>            
        ';

        if($row3["Origen"]=="COMPRA"){

          $sql4 = R10Q_Caso_Compra($row3["InvLoteId"],$IdArticulo,$row3["LoteAlmacenId"]);

          try {
            $result4 = sqlsrv_query($conn,$sql4);
            $row4 = sqlsrv_fetch_array($result4, SQLSRV_FETCH_ASSOC);

            echo '                    
              <td align="center">'.FG_Limpiar_Texto($row4["Proveedor"]).'</td>
              <td align="center">'.($row4["NumeroFactura"]).'</td>        
            ';
            
          } catch (Exception $e) {

            echo '                    
              <td align="center">'.$row3["InvLoteId"].'|'.$IdArticulo.'|'.$row3["LoteAlmacenId"].'</td>
              <td align="center">*</td>        
            ';
            
          }

        }else{
           echo '                    
            <td align="center">'.FG_Limpiar_Texto($row3["Causa"]).'</td>            
            <td align="center">'.($row3["NumeroReferencia"]).'</td>  
          ';
        }
        
        $TasaFecha = FG_Tasa_Fecha($connCPharma,$row3["FechaCreacionLote"]->format("Y-m-d"));
        if(!isset($TasaFecha)){
           $TasaFecha = 0;
        }

        echo '                  
          <td align="center">'.($row3["FechaCreacionLote"]->format('d-m-Y')).'</td>            
          <td align="center">'.($fechaVencimiento).'</td>
          <td align="center">'.($row3["NumeroLote"]).'</td>
          <td align="center">'.($row3["LoteFabricante"]).'</td>
          <td align="center">'.($row3["Almacen"]).'</td>
          <td align="center">'.intval($row3["Existencia"]).'</td>          
          <td align="center">'.number_format($row3["CostoTotal"],2,"," ,"." ).'</td>
          <td align="center">'.number_format($row3["CostoUnitario"],2,"," ,"." ).'</td>
          <td align="center">'.number_format($row2["PrecioTroquelado"],2,"," ,"." ).'</td>
          <td align="center">'.number_format($TasaFecha,2,"," ,"." ).'</td>
        ';

        if($TasaFecha!=0){
          $CostoDivisa = $row3["CostoUnitario"] / $TasaFecha;
          echo '                    
            <td align="center">'.number_format($CostoDivisa,2,"," ,"." ).'</td>
          ';
        }else{
          echo '                    
            <td align="center">0.00</td>
          ';
        }

        echo '                    
            <td align="center">'.FG_Limpiar_Texto($row3["Responsable"]).'</td>
          ';

        echo '</tr>';
        $contador++;
      }
    }
      echo '
      </tbody>
    </table>';
    //Tabla Lotes SIN exitencia

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
    TITULO: R10Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R10Q_Detalle_Articulo($IdArticulo) {
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
    TITULO: R10Q_Analitico_Productos
    FUNCION: Armar la tabla del historico de articulos
    RETORNO: La tabla de historico del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R10Q_Analitico_Productos($IdArticulo) {
    $sql = "
      SELECT
      invlote.Auditoria_FechaCreacion, 
      InvLoteAlmacen.id as LoteAlmacenId,
      InvLoteAlmacen.InvLoteId,
      (SELECT IIF(InvMovimiento.InvCausaId = 1, 'COMPRA', 'INVENTARIO')) as Origen,
      InvMovimiento.InvCausaId,
      InvCausa.Descripcion as Causa,      
      InvMovimiento.DocumentoOrigen as NumeroReferencia,      
      CONVERT(DATE,invlote.Auditoria_FechaCreacion) as FechaCreacionLote,
      CONVERT(DATE,invlote.FechaVencimiento) as FechaVencimiento,
      InvLote.Numero as NumeroLote,
      InvAlmacen.Descripcion as Almacen,
      InvLote.LoteFabricante as LoteFabricante,
      ROUND(CAST(InvLoteAlmacen.Existencia AS DECIMAL(38,0)),2,0) AS Existencia,
      InvLote.M_PrecioCompraBruto as CostoUnitario,
      InvLote.M_PrecioTroquelado as PrecioTroquelado,
      (ROUND(CAST(InvLoteAlmacen.Existencia AS DECIMAL(38,0)),2,0) * InvLote.M_PrecioCompraBruto) as CostoTotal,
      InvLote.Auditoria_Usuario as Responsable
      FROM invlotealmacen
      LEFT JOIN InvLote ON InvLote.id = invlotealmacen.InvLoteId
      LEFT JOIN InvMovimiento ON InvMovimiento.InvLoteId = InvLote.id
      LEFT JOIN InvCausa ON  InvCausa.Id = InvMovimiento.InvCausaId
      LEFT JOIN InvAlmacen ON InvAlmacen.Id = invlotealmacen.InvAlmacenId
      WHERE 
      InvLoteAlmacen.InvArticuloId = '$IdArticulo'
      AND InvLoteAlmacen.Existencia > 0
      AND (
          (InvMovimiento.InvCausaId=1) 
          OR (InvMovimiento.InvCausaId=5)    
          OR (InvMovimiento.InvCausaId=11)
      )
      ORDER BY invlote.Auditoria_FechaCreacion asc
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R10Q_Analitico_Productos
    FUNCION: Armar la tabla del historico de articulos
    RETORNO: La tabla de historico del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R10Q_Analitico_Productos_SE($IdArticulo) {
    $sql = "
      SELECT
      invlote.Auditoria_FechaCreacion, 
      InvLoteAlmacen.id as LoteAlmacenId,
      InvLoteAlmacen.InvLoteId,
      (SELECT IIF(InvMovimiento.InvCausaId = 1, 'COMPRA', 'INVENTARIO')) as Origen,
      InvMovimiento.InvCausaId,
      InvCausa.Descripcion as Causa,      
      InvMovimiento.DocumentoOrigen as NumeroReferencia,      
      CONVERT(DATE,invlote.Auditoria_FechaCreacion) as FechaCreacionLote,
      CONVERT(DATE,invlote.FechaVencimiento) as FechaVencimiento,
      InvLote.Numero as NumeroLote,
      InvAlmacen.Descripcion as Almacen,
      InvLote.LoteFabricante as LoteFabricante,
      ROUND(CAST(InvLoteAlmacen.Existencia AS DECIMAL(38,0)),2,0) AS Existencia,
      InvLote.M_PrecioCompraBruto as CostoUnitario,
      InvLote.M_PrecioTroquelado as PrecioTroquelado,
      (ROUND(CAST(InvLoteAlmacen.Existencia AS DECIMAL(38,0)),2,0) * InvLote.M_PrecioCompraBruto) as CostoTotal,
      InvLote.Auditoria_Usuario as Responsable
      FROM invlotealmacen
      LEFT JOIN InvLote ON InvLote.id = invlotealmacen.InvLoteId
      LEFT JOIN InvMovimiento ON InvMovimiento.InvLoteId = InvLote.id
      LEFT JOIN InvCausa ON  InvCausa.Id = InvMovimiento.InvCausaId
      LEFT JOIN InvAlmacen ON InvAlmacen.Id = invlotealmacen.InvAlmacenId
      WHERE 
      InvLoteAlmacen.InvArticuloId = '$IdArticulo'
      AND InvLoteAlmacen.Existencia <= 0
      AND (
          (InvMovimiento.InvCausaId=1) 
          OR (InvMovimiento.InvCausaId=5)    
          OR (InvMovimiento.InvCausaId=11)
      )
      ORDER BY invlote.Auditoria_FechaCreacion asc
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R10Q_Caso_Compra
    FUNCION: Armar la tabla del historico de articulos
    RETORNO: La tabla de historico del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function R10Q_Caso_Compra($InvLoteId,$InvArticuloId,$InvLoteAlmacenId){
    $sql="
    SELECT
    (
      SELECT 
      GenPersona.Nombre
      FROM ComFactura
      INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
      INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
      WHERE ComProveedor.Id = (
        (SELECT ComFactura.ComProveedorId FROM ComFactura WHERE ComFactura.Id = (
          SELECT ComFacturaEntrada.ComFacturaDetalleComFacturaId FROM ComFacturaEntrada WHERE ComFacturaEntrada.ComEntradaMercanciaId = (
            SELECT TOP 1 ComEntradaMercancia.id FROM ComEntradaMercancia WHERE ComEntradaMercancia.InvLoteId = invlotealmacen.InvLoteId AND ComEntradaMercancia.InvArticuloId = InvLoteAlmacen.InvArticuloId
            )
          )
        )
      )
      AND ComFactura.id = (SELECT ComFacturaEntrada.ComFacturaDetalleComFacturaId FROM ComFacturaEntrada WHERE ComFacturaEntrada.ComEntradaMercanciaId = (
        SELECT TOP 1 ComEntradaMercancia.id FROM ComEntradaMercancia WHERE ComEntradaMercancia.InvLoteId = invlotealmacen.InvLoteId AND ComEntradaMercancia.InvArticuloId = InvLoteAlmacen.InvArticuloId
        )
      )
    ) AS Proveedor,
    (
      SELECT 
      ComFactura.NumeroFactura
      FROM ComFactura
      INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
      INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
      WHERE ComProveedor.Id = (
        (SELECT ComFactura.ComProveedorId FROM ComFactura WHERE ComFactura.Id = (
          SELECT ComFacturaEntrada.ComFacturaDetalleComFacturaId FROM ComFacturaEntrada WHERE ComFacturaEntrada.ComEntradaMercanciaId = (
            SELECT TOP 1 ComEntradaMercancia.id FROM ComEntradaMercancia WHERE ComEntradaMercancia.InvLoteId = invlotealmacen.InvLoteId AND ComEntradaMercancia.InvArticuloId = InvLoteAlmacen.InvArticuloId
            )
          )
        )
      )
      AND ComFactura.id = (SELECT ComFacturaEntrada.ComFacturaDetalleComFacturaId FROM ComFacturaEntrada WHERE ComFacturaEntrada.ComEntradaMercanciaId = (
        SELECT TOP 1 ComEntradaMercancia.id FROM ComEntradaMercancia WHERE ComEntradaMercancia.InvLoteId = invlotealmacen.InvLoteId AND ComEntradaMercancia.InvArticuloId = InvLoteAlmacen.InvArticuloId
        )
      )
    ) AS NumeroFactura
    FROM invlotealmacen
    WHERE 
    InvLoteAlmacen.InvLoteId = '$InvLoteId'
    AND InvLoteAlmacen.InvArticuloId = '$InvArticuloId'
    AND InvLoteAlmacen.id = '$InvLoteAlmacenId'
    ";
    return $sql;
  }
?>
