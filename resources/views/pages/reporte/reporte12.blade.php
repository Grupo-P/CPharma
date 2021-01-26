@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
  <style>
    * {
      box-sizing:border-box;
    }
    .autocomplete {
      position:relative; 
      display:inline-block;
    }
    input {
      border:1px solid transparent;
      background-color:#f1f1f1;
      border-radius:5px;
      padding:10px;
      font-size:16px;
    }
    input[type=text] {
      background-color:#f1f1f1; 
      width:100%;
    }
    .autocomplete-items {
      position:absolute;
      border:1px solid #d4d4d4;
      border-bottom:none;
      border-top:none;
      z-index:99;
      top:100%;
      left:0;
      right:0;
    }
    .autocomplete-items div {
      padding:10px;
      cursor:pointer;
      background-color:#fff; 
      border-bottom:1px solid #d4d4d4; 
    }
    .autocomplete-items div:hover {
      background-color:#e9e9e9;
    }
    .autocomplete-active {
      background-color:DodgerBlue !important; 
      color:#fff;
    }
  </style>
@endsection

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-file-invoice"></i>
    Detalle de movimientos
  </h1>
  <hr class="row align-items-start col-12">
  
<?php
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  $ArtJson = "";
  $CodJson = "";

  if(isset($_GET['SEDE'])) {
    echo '
      <h1 class="h5 text-success"  align="left">
        <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE'])
      .'</h1>
    ';
  }
  echo '<hr class="row align-items-start col-12">';
  
  if(isset($_GET['Id'])) {
    $InicioCarga = new DateTime("now");

    R12_Detalle_Movimientos($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin'],$_GET['Id']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Detalle de movimientos');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else if(isset($_GET['IdCB'])) {
    $InicioCarga = new DateTime("now");

    R12_Detalle_Movimientos($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin'],$_GET['IdCB']);
    FG_Guardar_Auditoria('CONSULTAR','REPORTE','Detalle de movimientos');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else {
    $InicioCarga = new DateTime("now");

    $sql = R12Q_Lista_Articulos();
    $ArtJson = FG_Armar_Json($sql,$_GET['SEDE']);

    $sql1 = R12Q_Lista_Articulos_CodBarra();
    $CodJson = FG_Armar_Json($sql1,$_GET['SEDE']);

    echo '
      <form id="form" autocomplete="off" action="" target="_blank">
        <table style="width:100%;">
          <tr>
            <td align="center">Fecha Inicio:</td>
            <td>
              <input id="fechaInicio" type="date" name="fechaInicio" required style="width:100%;">
            </td>

            <td align="center">Fecha Fin:</td>
            <td align="right">
              <input id="fechaFin" name="fechaFin" type="date" required style="width:100%;">
              <input id="SEDE" name="SEDE" type="hidden" value="'; print_r($_GET['SEDE']);
              echo'">
            </td>
          </tr>

          <tr>
            <td colspan="4">&nbsp;</td>
          </tr>

          <tr>
            <td colspan="4">
              <div class="autocomplete" style="width:90%;">
                <input id="myInput" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()">
              </div>

              <input id="myId" name="Id" type="hidden">

              <input type="submit" value="Buscar" class="btn btn-outline-success" style="width:9%;">
            </td>
          </tr>

          <tr>
            <td colspan="4">&nbsp;</td>
          </tr>
        </table>
      
        <div class="autocomplete" style="width:90%;">
          <input id="myInputCB" type="text" name="CodBar" placeholder="Ingrese el codigo de barra del articulo " onkeyup="conteoCB()">
          <input id="myIdCB" name="IdCB" type="hidden">
        </div>
        <input id="SEDE" name="SEDE" type="hidden" value="'; 
          print_r($_GET['SEDE']);
          echo'">
        <input type="submit" value="Buscar" class="btn btn-outline-success" style="width:9%;">
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
    TITULO: R12Q_Lista_Articulos
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA  
   */
  function R12Q_Lista_Articulos() {
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
    TITULO: R12Q_Lista_Articulos_CodBarra
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12Q_Lista_Articulos_CodBarra() {
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
    TITULO: R12_Detalle_Movimientos
    FUNCION: arma la lista del troquel segun el articulo
    RETORNO: no aplica
    DESAROLLADO POR: SERGIO COVA 
  */
  function R12_Detalle_Movimientos($SedeConnection,$FInicial,$FFinal,$IdArticulo) {
    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();
     
    $sqlCPharma = SQL_Etiqueta_Articulo($IdArticulo);
    $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
    $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
    $clasificacion = $RowCPharma['clasificacion'];
    $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";

    $sql = R12Q_Detalle_Articulo($IdArticulo);
    $result = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

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

    $Gravado = FG_Producto_Gravado($IsIVA);
    $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
    $TasaActual = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
    $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

    $FFinalImpresion = $FFinal;
    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    $sql1 = R12Q_Total_Venta($IdArticulo,$FInicial,$FFinal);
    $result1 = sqlsrv_query($conn,$sql1);
    $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

    $RangoDias = FG_Rango_Dias($FInicial,$FFinal);
    $Venta = $row1["TotalUnidadesVendidas"];
    $VentaDiaria = FG_Venta_Diaria($Venta,$RangoDias);
    $DiasRestantes = FG_Dias_Restantes($Existencia,$VentaDiaria);

    echo '
      <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
        <div class="input-group-prepend">
          <span class="input-group-text purple lighten-3" id="basic-text1">
            <i class="fas fa-search text-white" aria-hidden="true"></i>
          </span>
        </div>
        <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
      </div>
      <br/>
    ';

    echo '
    <h6 align="center">Periodo desde el '.date("d-m-Y",strtotime($FInicial)).' al '.date("d-m-Y",strtotime($FFinalImpresion)).'</h6>

      <table class="table table-striped table-bordered col-12 sortable">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Codigo</th>
            <th scope="col">Codigo de barra</td>
            <th scope="col">Descripcion</th>
            <th scope="col">Existencia</th>
            <th scope="col">Unidades vendidas</th>
            <th scope="col">Dias restantes</th>
            <th scope="col">Precio</br>(Con IVA) '.SigVe.'</th>
            <th scope="col">Dolarizado</th>
            <th scope="col">Gravado?</td>
            <th scope="col">Clasificacion</td>
            <th scope="col">Tasa actual '.SigVe.'</th>
            <th scope="col">Precio en divisa</br>(Con IVA) '.SigDolar.'</th>
            <th scope="col">Ultima Venta</th>
          </tr>
        </thead>

        <tbody>
    ';

    echo '
      <tr>
        <td>'.$CodigoArticulo.'</td>
        <td align="center">'.$CodigoBarra.'</td>
        <td align="left" class="CP-barrido">
          <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
            .$Descripcion
          .'</a>
        </td>
        <td align="center">'.intval($Existencia).'</td>
        <td align="center">'.$Venta.'</td>
        <td align="center">'.$DiasRestantes.'</td>
        <td align="center">'.number_format($Precio,2,"," ,"." ).'</td>
        <td align="center">'.$Dolarizado.'</td>
        <td align="center">'.$Gravado.'</td>
        <td align="center">'.$clasificacion.'</td>
    ';

    if($TasaActual != 0) {

      $PrecioDolar = $Precio / $TasaActual;

      echo '
        <td align="center">'.number_format($TasaActual,2,"," ,"." ).'</td>
        <td align="center">'.number_format($PrecioDolar,2,"," ,"." ).'</td>
      ';
    }
    else{
      echo '
        <td align="center">0,00</td>
        <td align="center">0,00</td>
      ';
    }

    if(!is_null($UltimaVenta)){
      echo '<td align="center">'.$UltimaVenta->format('d-m-Y').'</td>';
    }
    else{
      echo '<td align="center"> - </td>';
    }

    echo '
          </tr>
        </tbody>
      </table>
    ';

    $sql = R12Q_seccion1($FInicial,$FFinal,$IdArticulo);
    $result = sqlsrv_query($conn,$sql);

    $sql2 = R12Q_seccion2($FInicial,$FFinal,$IdArticulo);
    $result2 = sqlsrv_query($conn,$sql2);  

    $sql3 = R12Q_seccion3($FInicial,$FFinal,$IdArticulo);
    $result3 = sqlsrv_query($conn,$sql3);

    echo '    
      <table class="table table-striped table-bordered col-12 sortable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Tipo de movimiento</td> 
            <th scope="col" class="CP-sticky">Efecto</th> 
            <th scope="col" class="CP-sticky">Cantidad</th>             
          </tr>
        </thead>
        <tbody>
    ';

    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {      
        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';        
        echo '<td align="center">'.FG_Limpiar_Texto($row['TipoMovimiento']).'</td>';
        echo '<td align="center">'.$row['Efecto'].'</td>';      
        echo '<td align="center">'.$row['Cantidad'].'</td>';
        echo '</tr>';
        $contador++;
      }

    echo '
        </tbody>
      </table>
    ';

    echo '
      <table class="table table-striped table-bordered col-12 sortable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Fecha</td>
            <th scope="col" class="CP-sticky">Tipo de movimiento</td>
            <th scope="col" class="CP-sticky">Efecto</th>
            <th scope="col" class="CP-sticky">Cantidad</th>
            <th scope="col" class="CP-sticky">Saldo</th>
          </tr>
        </thead>

        <tbody>
    ';

    $contador = 1;
    $saldo = 0;
    while($row2 = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC)) {      
        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';        
        echo '<td align="center">'.$row2['Fecha']->format('d-m-Y').'</td>';
        echo '<td align="center">'.FG_Limpiar_Texto($row2['TipoMovimiento']).'</td>';
        echo '<td align="center">'.$row2['Efecto'].'</td>';      
        echo '<td align="center">'.$row2['Cantidad'].'</td>';        

        if($row2['Efecto']=="+"){
          $saldo = $saldo + $row2['Cantidad'];
        }else if($row2['Efecto']=="-"){
          $saldo = $saldo + ((-1)*$row2['Cantidad']);
        }

        echo '<td align="center"><strong>'.$saldo.'</strong></td>';
        echo '</tr>';

        $contador++;
      }

    echo '
        </tbody>
      </table>
    ';

    echo '
      <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Fecha</td>
            <th scope="col" class="CP-sticky">Hora</td>
            <th scope="col" class="CP-sticky">Tipo de movimiento</td>
            <th scope="col" class="CP-sticky">Efecto</th>
            <th scope="col" class="CP-sticky">Cantidad</th>
            <th scope="col" class="CP-sticky">Saldo</th>
            <th scope="col" class="CP-sticky">Almacen</th>
            <th scope="col" class="CP-sticky">Lote</th>
            <th scope="col" class="CP-sticky">Numero Referencia</th>            
            <th scope="col" class="CP-sticky">Titular</th>
            <th scope="col" class="CP-sticky">Comentario</th>
            <th scope="col" class="CP-sticky">Operador</th>
            <th scope="col" class="CP-sticky">Monto Total'.SigVe.'</th>
            <th scope="col" class="CP-sticky">Monto Unitario'.SigVe.'</th>
            <th scope="col" class="CP-sticky">Tasa Mercado</th>
            <th scope="col" class="CP-sticky">Monto Unitario'.SigDolar.'</th>            
          </tr>
        </thead>
        <tbody>
    ';

    $contador = 1;
    $saldo = 0;
    while($row3 = sqlsrv_fetch_array($result3, SQLSRV_FETCH_ASSOC)) {      
        echo '<tr>';
        echo '<td align="center"><strong>'.intval($contador).'</strong></td>';        
        echo '<td align="center">'.$row3['Fecha']->format('d-m-Y').'</td>';
        echo '<td align="center">'.$row3['Fecha']->format('h:i A').'</td>';
        echo '<td align="center">'.FG_Limpiar_Texto($row3['TipoMovimiento']).'</td>';
        echo '<td align="center">'.$row3['Efecto'].'</td>';      
        echo '<td align="center">'.$row3['Cantidad'].'</td>';        

        if($row3['Efecto']=="+"){
          $saldo = $saldo + $row3['Cantidad'];
        }else if($row3['Efecto']=="-"){
          $saldo = $saldo + ((-1)*$row3['Cantidad']);
        }

        echo '<td align="center">'.$saldo.'</td>';
        echo '<td align="center">'.$row3['Almacen'].'</td>';
        echo '<td align="center">'.$row3['Lote'].'</td>';

        switch ($row3['idCausa']) {
          case '1':            
            $sql = R12_Causa_Compra($row3['NumeroReferencia']);
            $result = sqlsrv_query($conn,$sql);
            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            echo '<td align="center">'.$row['NumeroFactura'].'</td>';
            echo '<td align="center">'.FG_Limpiar_Texto($row['Nombre']).'</td>';
            echo '<td align="center">-</td>';
            
            echo '<td align="center">'.$row3['Operador'].'</td>';
            echo '<td align="center">'.number_format($row3['MontoTotal'],2,"," ,"." ).'</td>';
            echo '<td align="center">'.number_format($row3['MontoUnitario'],2,"," ,"." ).'</td>';

            $Fecha = $row3['Fecha']->format('Y-m-d');
            $Tasa = FG_Tasa_Fecha($connCPharma,$Fecha);

            if($Tasa!=0){
              $costoDol = ($row3['MontoUnitario'])/$Tasa;
              echo '<td align="center">'.number_format($Tasa,2,"," ,"." ).'</td>';
              echo '<td align="center">'.number_format($costoDol,2,"," ,"." ).'</td>';
            }else{
              echo '<td align="center">0,00</td>';
              echo '<td align="center">-</td>';
            }
            echo '</tr>';
          break; 
          case '2':            
            $sql = R12_Causa_DevCompra($row3['NumeroReferencia']);
            $reechosult = sqlsrv_query($conn,$sql);
            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            echo '<td align="center">'.$row3['NumeroReferencia'].'</td>';
            echo '<td align="center">'.FG_Limpiar_Texto($row['Nombre']).'</td>';
            echo '<td align="center">-</td>';

            echo '<td align="center">'.$row3['Operador'].'</td>';
            echo '<td align="center">'.number_format($row3['MontoTotal'],2,"," ,"." ).'</td>';
            echo '<td align="center">'.number_format($row3['MontoUnitario'],2,"," ,"." ).'</td>';

            $Fecha = $row3['Fecha']->format('Y-m-d');
            $Tasa = FG_Tasa_Fecha($connCPharma,$Fecha);

            if($Tasa!=0){
              $costoDol = ($row3['MontoUnitario'])/$Tasa;
              echo '<td align="center">'.number_format($Tasa,2,"," ,"." ).'</td>';
              echo '<td align="center">'.number_format($costoDol,2,"," ,"." ).'</td>';
            }else{
              echo '<td align="center">0,00</td>';
              echo '<td align="center">-</td>';
            }

            echo '</tr>';
          break;
          case '3':            
            $sql = R12_Causa_Venta($row3['NumeroReferencia'],$IdArticulo);
            $result = sqlsrv_query($conn,$sql);
            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            echo '<td align="center">'.$row3['NumeroReferencia'].'</td>';
            echo '<td align="center">'.FG_Limpiar_Texto($row['Nombre']).' '.FG_Limpiar_Texto($row['Apellido']).'</td>';
            echo '<td align="center">-</td>';

            echo '<td align="center">'.$row3['Operador'].'</td>';
            echo '<td align="center">'.number_format( ($row['PrecioBruto']*$row3['Cantidad']) ,2,"," ,"." ).'</td>';
            echo '<td align="center">'.number_format($row['PrecioBruto'],2,"," ,"." ).'</td>';

            $Fecha = $row3['Fecha']->format('Y-m-d');
            $Tasa = FG_Tasa_Fecha($connCPharma,$Fecha);

            if($Tasa!=0){
              $costoDol = ($row['PrecioBruto'])/$Tasa;
              echo '<td align="center">'.number_format($Tasa,2,"," ,"." ).'</td>';
              echo '<td align="center">'.number_format($costoDol,2,"," ,"." ).'</td>';
            }else{
              echo '<td align="center">0,00</td>';
              echo '<td align="center">-</td>';
            }
            echo '</tr>';
          break; 
          case '4':           
            $sql = R12_Causa_DevVenta($row3['NumeroReferencia'],$IdArticulo);
            $result = sqlsrv_query($conn,$sql);
            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            echo '<td align="center">'.$row3['NumeroReferencia'].'</td>';
            echo '<td align="center">'.FG_Limpiar_Texto($row['Nombre']).' '.FG_Limpiar_Texto($row['Apellido']).'</td>';

            $sql = R12_Coment_DevVenta($row3['NumeroReferencia']);
            $result = sqlsrv_query($conn,$sql);
            $rowA = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);            
            echo '<td align="center">'.$rowA['DescripcionOperacion'].'</td>';

            echo '<td align="center">'.$row3['Operador'].'</td>';
            echo '<td align="center">'.number_format( ($row['PrecioBruto']*$row3['Cantidad']) ,2,"," ,"." ).'</td>';
            echo '<td align="center">'.number_format($row['PrecioBruto'],2,"," ,"." ).'</td>';

            $Fecha = $row3['Fecha']->format('Y-m-d');
            $Tasa = FG_Tasa_Fecha($connCPharma,$Fecha);

            if($Tasa!=0){
              $costoDol = ($row['PrecioBruto'])/$Tasa;
              echo '<td align="center">'.number_format($Tasa,2,"," ,"." ).'</td>';
              echo '<td align="center">'.number_format($costoDol,2,"," ,"." ).'</td>';
            }else{
              echo '<td align="center">0,00</td>';
              echo '<td align="center">-</td>';
            }

            echo '</tr>';
          break;
          case '5':           
            $sql = R12_Coment_Trasferencia($row3['NumeroReferencia']);
            $result = sqlsrv_query($conn,$sql);
            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            echo '<td align="center">'.$row3['NumeroReferencia'].'</td>';
            echo '<td align="center">-</td>';                  
            echo '<td align="center">'.FG_Limpiar_Texto($row['Observaciones']).'</td>';

            echo '<td align="center">'.$row3['Operador'].'</td>';
            echo '<td align="center">'.number_format($row3['MontoTotal'],2,"," ,"." ).'</td>';
            echo '<td align="center">'.number_format($row3['MontoUnitario'],2,"," ,"." ).'</td>';

            $Fecha = $row3['Fecha']->format('Y-m-d');
            $Tasa = FG_Tasa_Fecha($connCPharma,$Fecha);

            if($Tasa!=0){
              $costoDol = ($row3['MontoUnitario'])/$Tasa;
              echo '<td align="center">'.number_format($Tasa,2,"," ,"." ).'</td>';
              echo '<td align="center">'.number_format($costoDol,2,"," ,"." ).'</td>';
            }else{
              echo '<td align="center">0,00</td>';
              echo '<td align="center">-</td>';
            }

            echo '</tr>';
          break;
          case '6':           
            $sql = R12_Coment_Trasferencia($row3['NumeroReferencia']);
            $result = sqlsrv_query($conn,$sql);
            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            echo '<td align="center">'.$row3['NumeroReferencia'].'</td>';
            echo '<td align="center">-</td>';                  
            echo '<td align="center">'.FG_Limpiar_Texto($row['Observaciones']).'</td>';

            echo '<td align="center">'.$row3['Operador'].'</td>';
            echo '<td align="center">'.number_format($row3['MontoTotal'],2,"," ,"." ).'</td>';
            echo '<td align="center">'.number_format($row3['MontoUnitario'],2,"," ,"." ).'</td>';

            $Fecha = $row3['Fecha']->format('Y-m-d');
            $Tasa = FG_Tasa_Fecha($connCPharma,$Fecha);

            if($Tasa!=0){
              $costoDol = ($row3['MontoUnitario'])/$Tasa;
              echo '<td align="center">'.number_format($Tasa,2,"," ,"." ).'</td>';
              echo '<td align="center">'.number_format($costoDol,2,"," ,"." ).'</td>';
            }else{
              echo '<td align="center">0,00</td>';
              echo '<td align="center">-</td>';
            }

            echo '</tr>';
          break;
          case '14':           
            $sql = R12_Coment_Ajuste($row3['NumeroReferencia']);
            $result = sqlsrv_query($conn,$sql);
            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            echo '<td align="center">'.$row3['NumeroReferencia'].'</td>';
            echo '<td align="center">-</td>';                  
            echo '<td align="center">'.FG_Limpiar_Texto($row['Comentario']).'</td>';

            echo '<td align="center">'.$row3['Operador'].'</td>';
            echo '<td align="center">'.number_format($row3['MontoTotal'],2,"," ,"." ).'</td>';
            echo '<td align="center">'.number_format($row3['MontoUnitario'],2,"," ,"." ).'</td>';

            $Fecha = $row3['Fecha']->format('Y-m-d');
            $Tasa = FG_Tasa_Fecha($connCPharma,$Fecha);

            if($Tasa!=0){
              $costoDol = ($row3['MontoUnitario'])/$Tasa;
              echo '<td align="center">'.number_format($Tasa,2,"," ,"." ).'</td>';
              echo '<td align="center">'.number_format($costoDol,2,"," ,"." ).'</td>';
            }else{
              echo '<td align="center">0,00</td>';
              echo '<td align="center">-</td>';
            }

            echo '</tr>';
          break;  
          case '15':          
            $sql = R12_Coment_Ajuste($row3['NumeroReferencia']);
            $result = sqlsrv_query($conn,$sql);
            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            echo '<td align="center">'.$row3['NumeroReferencia'].'</td>';
            echo '<td align="center">-</td>';                  
            echo '<td align="center">'.FG_Limpiar_Texto($row['Comentario']).'</td>';

            echo '<td align="center">'.$row3['Operador'].'</td>';
            echo '<td align="center">'.number_format($row3['MontoTotal'],2,"," ,"." ).'</td>';
            echo '<td align="center">'.number_format($row3['MontoUnitario'],2,"," ,"." ).'</td>';

            $Fecha = $row3['Fecha']->format('Y-m-d');
            $Tasa = FG_Tasa_Fecha($connCPharma,$Fecha);

            if($Tasa!=0){
              $costoDol = ($row3['MontoUnitario'])/$Tasa;
              echo '<td align="center">'.number_format($Tasa,2,"," ,"." ).'</td>';
              echo '<td align="center">'.number_format($costoDol,2,"," ,"." ).'</td>';
            }else{
              echo '<td align="center">0,00</td>';
              echo '<td align="center">-</td>';
            }

            echo '</tr>';
          break;        
          default:
            echo '<td align="center">'.$row3['NumeroReferencia'].'</td>';
            echo '<td align="center">-</td>'; 
            echo '<td align="center">-</td>';

            echo '<td align="center">'.$row3['Operador'].'</td>';
            echo '<td align="center">'.number_format($row3['MontoTotal'],2,"," ,"." ).'</td>';
            echo '<td align="center">'.number_format($row3['MontoUnitario'],2,"," ,"." ).'</td>';

            $Fecha = $row3['Fecha']->format('Y-m-d');
            $Tasa = FG_Tasa_Fecha($connCPharma,$Fecha);

            if($Tasa!=0){
              $costoDol = ($row3['MontoUnitario'])/$Tasa;
              echo '<td align="center">'.number_format($Tasa,2,"," ,"." ).'</td>';
              echo '<td align="center">'.number_format($costoDol,2,"," ,"." ).'</td>';
            }else{
              echo '<td align="center">0,00</td>';
              echo '<td align="center">-</td>';
            }

            echo '</tr>';
          break;
        }
      
        /*
        echo '<td align="center">'.$row3['Operador'].'</td>';
        echo '<td align="center">'.number_format($row3['MontoTotal'],2,"," ,"." ).'</td>';
        echo '<td align="center">'.number_format($row3['MontoUnitario'],2,"," ,"." ).'</td>';

        $Fecha = $row3['Fecha']->format('Y-m-d');
        $Tasa = FG_Tasa_Fecha($connCPharma,$Fecha);

        if($Tasa!=0){
          $costoDol = ($row3['MontoUnitario'])/$Tasa;
          echo '<td align="center">'.$Tasa.'</td>';
          echo '<td align="center">'.number_format($costoDol,2,"," ,"." ).'</td>';
        }else{
          echo '<td align="center">0,00</td>';
          echo '<td align="center">-</td>';
        }

        echo '</tr>';*/

        $contador++;
      }

    echo '
        </tbody>
      </table>
    ';

    sqlsrv_close($conn);
  }
/**********************************************************************************/
  /*
    TITULO: R12Q_seccion1
    FUNCION: 
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12Q_seccion1($FInicial,$FFinal,$IdArticulo) {
    $sql = "
      SELECT 
      InvCausa.id as idCausa, 
      InvCausa.Descripcion AS TipoMovimiento,
      (SELECT IIF(InvCausa.EsPositiva = 1, '+', '-')) as Efecto,
      ROUND(CAST(SUM(InvMovimiento.Cantidad) AS DECIMAL(38,0)),2,0) AS Cantidad
      FROM InvMovimiento
      LEFT JOIN InvCausa ON InvCausa.Id = InvMovimiento.InvCausaId
      WHERE InvMovimiento.InvArticuloId='$IdArticulo'
      AND(CONVERT(DATE,InvMovimiento.FechaMovimiento) >= '$FInicial' AND CONVERT(DATE,InvMovimiento.FechaMovimiento) < '$FFinal')
      AND (
        (InvMovimiento.InvCausaId=1) OR (InvMovimiento.InvCausaId=2) OR (InvMovimiento.InvCausaId=3) OR (InvMovimiento.InvCausaId=4)
        OR (InvMovimiento.InvCausaId=5) OR (InvMovimiento.InvCausaId=6) OR (InvMovimiento.InvCausaId=11) OR (InvMovimiento.InvCausaId=12)
        OR (InvMovimiento.InvCausaId=14)OR (InvMovimiento.InvCausaId=15)
      )
      GROUP BY InvCausa.id,InvCausa.Descripcion,InvCausa.EsPositiva
      ORDER BY InvCausa.id asc
    ";
    return $sql;
  }
/**********************************************************************************/
  /*
    TITULO: R12Q_seccion2
    FUNCION: 
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12Q_seccion2($FInicial,$FFinal,$IdArticulo) {
    $sql = "
      SELECT 
      InvCausa.id as idCausa,
      CONVERT(DATE,InvMovimiento.FechaMovimiento) AS Fecha, 
      InvCausa.Descripcion AS TipoMovimiento,
      (SELECT IIF(InvCausa.EsPositiva = 1, '+', '-')) AS Efecto,
      ROUND(CAST(SUM(InvMovimiento.Cantidad) AS DECIMAL(38,0)),2,0) AS Cantidad
      FROM InvMovimiento
      LEFT JOIN InvCausa ON InvCausa.Id = InvMovimiento.InvCausaId
      WHERE InvMovimiento.InvArticuloId='$IdArticulo'
      AND(CONVERT(DATE,InvMovimiento.FechaMovimiento) >= '$FInicial' AND CONVERT(DATE,InvMovimiento.FechaMovimiento) < '$FFinal')
      AND (
        (InvMovimiento.InvCausaId=1) OR (InvMovimiento.InvCausaId=2) OR (InvMovimiento.InvCausaId=3) OR (InvMovimiento.InvCausaId=4)
        OR (InvMovimiento.InvCausaId=5) OR (InvMovimiento.InvCausaId=6) OR (InvMovimiento.InvCausaId=11) OR (InvMovimiento.InvCausaId=12)
        OR (InvMovimiento.InvCausaId=14)OR (InvMovimiento.InvCausaId=15)
      )
      GROUP BY InvCausa.id,InvCausa.Descripcion,InvCausa.EsPositiva,CONVERT(DATE,InvMovimiento.FechaMovimiento)
      ORDER BY CONVERT(DATE,InvMovimiento.FechaMovimiento) asc
    ";
    return $sql;
  }
/**********************************************************************************/
  /*
    TITULO: R12Q_seccion3
    FUNCION: 
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12Q_seccion3($FInicial,$FFinal,$IdArticulo) {
    $sql = "    
      SELECT
      InvMovimiento.Id AS idMovimiento, 
      InvCausa.id as idCausa,
      InvMovimiento.FechaMovimiento AS Fecha,
      InvCausa.Descripcion AS TipoMovimiento,
      (SELECT IIF(InvCausa.EsPositiva = 1, '+', '-')) AS Efecto,
      ROUND(CAST((InvMovimiento.Cantidad) AS DECIMAL(38,0)),2,0) AS Cantidad,
      InvAlmacen.Descripcion as Almacen,
      InvMovimiento.DocumentoOrigen as NumeroReferencia,
      InvLote.Numero as Lote,
      InvMovimiento.Auditoria_Usuario as Operador,
      InvMovimiento.M_CostoTotal as MontoTotal,
      InvMovimiento.M_CostoUnitario as MontoUnitario
      FROM InvMovimiento
      LEFT JOIN InvCausa ON InvCausa.Id = InvMovimiento.InvCausaId
      LEFT JOIN InvAlmacen ON InvAlmacen.id = InvMovimiento.InvAlmacenId
      LEFT JOIN InvLote ON InvLote.Id = InvMovimiento.InvLoteId
      WHERE InvMovimiento.InvArticuloId='$IdArticulo'
      AND(CONVERT(DATE,InvMovimiento.FechaMovimiento) >= '$FInicial' AND CONVERT(DATE,InvMovimiento.FechaMovimiento) < '$FFinal')
      AND (
        (InvMovimiento.InvCausaId=1) OR (InvMovimiento.InvCausaId=2) OR (InvMovimiento.InvCausaId=3) OR (InvMovimiento.InvCausaId=4)
        OR (InvMovimiento.InvCausaId=5) OR (InvMovimiento.InvCausaId=6) OR (InvMovimiento.InvCausaId=11) OR (InvMovimiento.InvCausaId=12)
        OR (InvMovimiento.InvCausaId=14)OR (InvMovimiento.InvCausaId=15)
      )
      ORDER BY CONVERT(DATE,InvMovimiento.FechaMovimiento) asc
    ";
    return $sql;
  }
/**********************************************************************************/
  /*
    TITULO: R12Q_seccion3
    FUNCION: 
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12_Causa_Compra($NumeroReferencia) {
    $sql = "    
      SELECT GenPersona.Nombre,ComFactura.NumeroFactura
      FROM ComEntradaMercancia
      LEFT JOIN ComProveedor ON ComProveedor.Id = ComEntradaMercancia.ComProveedorId
      LEFT JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
      LEFT JOIN ComFacturaEntrada ON ComFacturaEntrada.ComEntradaMercanciaId = ComEntradaMercancia.Id
      LEFT JOIN ComFactura ON ComFactura.id = ComFacturaEntrada.ComFacturaDetalleComFacturaId
      WHERE ComEntradaMercancia.NumeroEntrada = '$NumeroReferencia'
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R12Q_seccion3
    FUNCION: 
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12_Causa_DevCompra($NumeroReferencia) {
    $sql = "    
      SELECT GenPersona.Nombre
      FROM ComSalidaMercancia
      LEFT JOIN ComProveedor ON ComProveedor.Id = ComSalidaMercancia.ComProveedorId
      LEFT JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId 
      WHERE ComSalidaMercancia.NumeroSalida = '$NumeroReferencia'
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R12Q_seccion3
    FUNCION: 
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12_Causa_Venta($NumeroReferencia,$IdArticulo) {
    $sql = "    
    SELECT GenPersona.Nombre, GenPersona.Apellido, VenVentaDetalle.PrecioBruto
    FROM VenVenta
    LEFT JOIN VenCliente ON VenCliente.Id = VenVenta.VenClienteId
    LEFT JOIN GenPersona ON GenPersona.Id = VenCliente.GenPersonaId
    LEFT JOIN VenVentaDetalle ON VenVentaDetalle.VenVentaId = VenVenta.Id
    WHERE  VenVenta.ConsecutivoVentaSistema = '$NumeroReferencia'
    AND VenVentaDetalle.InvArticuloId = '$IdArticulo'
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R12Q_seccion3
    FUNCION: 
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12_Causa_DevVenta($NumeroReferencia,$IdArticulo) {
    $sql = "    
      SELECT GenPersona.Nombre, GenPersona.Apellido, VenDevolucionDetalle.PrecioBruto
      FROM VenDevolucion
      LEFT JOIN VenFactura ON VenFactura.id = VenDevolucion.VenFacturaId
      LEFT JOIN VenCliente ON VenCliente.Id = VenFactura.VenClienteId
      LEFT JOIN GenPersona ON GenPersona.Id = VenCliente.GenPersonaId
      LEFT JOIN VenDevolucionDetalle ON VenDevolucionDetalle.VenDevolucionId = VenDevolucion.Id
      WHERE  VenDevolucion.ConsecutivoDevolucionSistema = '$NumeroReferencia'
      AND VenDevolucionDetalle.InvArticuloId = '$IdArticulo'
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R12Q_seccion3
    FUNCION: 
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12_Coment_DevVenta($NumeroReferencia) {
    $sql = "    
      SELECT TOP 1 VenCausaOperacionDevolucion.DescripcionOperacion 
      FROM VenDevolucion
      LEFT JOIN VenDevolucionDetalle ON VenDevolucionDetalle.VenDevolucionId = VenDevolucion.Id
      LEFT JOIN VenCausaOperacionDevolucion ON VenCausaOperacionDevolucion.id = VenDevolucionDetalle.VenCausaOperacionId
      WHERE VenDevolucion.ConsecutivoDevolucionSistema = '$NumeroReferencia'
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R12Q_seccion3
    FUNCION: 
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12_Coment_Trasferencia($NumeroReferencia) {
    $sql = "    
      SELECT InvTransferenciaAlmacen.Observaciones FROM InvTransferenciaAlmacen WHERE InvTransferenciaAlmacen.NumeroTransferencia = '$NumeroReferencia'
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R12Q_seccion3
    FUNCION: 
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA    
   */
  function R12_Coment_Ajuste($NumeroReferencia) {
    $sql = "    
      SELECT InvAjuste.Comentario FROM InvAjuste WHERE InvAjuste.NumeroAjuste = '$NumeroReferencia'
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R12Q_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    AUTOR: Ing. Manuel Henriquez
   */
  function R12Q_Detalle_Articulo($IdArticulo) {
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
        WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS UtilidadArticulo,
--UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad 
  FROM VenCondicionVenta 
  WHERE VenCondicionVenta.id = (
    SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id 
    FROM VenCondicionVenta_VenCondicionVentaCategoria 
    WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS UtilidadCategoria,
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
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
--Ordanamiento
    ORDER BY InvArticulo.Id ASC 
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: R12Q_Total_Venta
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
    DESAROLLADO POR: SERGIO COVA
   */
  function R12Q_Total_Venta($IdArticulo,$FInicial,$FFinal) {
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