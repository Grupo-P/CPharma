@extends('layouts.model')

@section('title')
    Reporte
@endsection

@section('scriptsHead')
    <script type="text/javascript" src="{{ asset('assets/js/sortTable.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/filter.js') }}">  
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/functions.js') }}"> 
    </script>
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-2.2.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.min.js') }}" ></script>
    <style>
    table, th, td, tr {
      /*border: 1px solid black;*/
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
    .barrido{
      text-decoration: none;
      transition: width 1s, height 1s, transform 1s;
    }
    .barrido:hover{
      text-decoration: none;
      transition: width 1s, height 1s, transform 1s;
      transform: translate(20px,0px);
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
  include(app_path().'\functions\querys.php');
  include(app_path().'\functions\funciones.php');

  $ArtJson = "";

  if (isset($_GET['Descrip'])){

    $InicioCarga = new DateTime("now");

    if (isset($_GET['SEDE'])){      
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
    }
    echo '<hr class="row align-items-start col-12">';

    ReportePedidoProductos($_GET['SEDE'],$_GET['Descrip'],$_GET['fechaInicio'],$_GET['fechaFin'],$_GET['pedido']);
    GuardarAuditoria('CONSULTAR','REPORTE','Pedido de productos');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else{
    if (isset($_GET['SEDE'])){      
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
    }
    echo '<hr class="row align-items-start col-12">';

    $InicioCarga = new DateTime("now");

    $sql = QListaArticulos();
    $ArtJson = armarJson($sql,$_GET['SEDE']);

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
            </td>
            <td>
            <input id="SEDE" name="SEDE" type="hidden" value="';
            print_r($_GET['SEDE']);
            echo'">
            </td>
          </tr>
          <tr>
            <td colspan="6">
              <div class="autocomplete" style="width:90%;">
                <input id="myInput" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()" required>
              </div>
            <input type="submit" value="Buscar" class="btn btn-outline-success" style="width:9%;">
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
<script type="text/javascript">
jQuery(document).on('ready',function(){
  $('#myInput').keyup(function(e) {
        if(e.keyCode == 13) {
          if ( $('#myInput').val() != "" ) {
            $('#form').submit();
          }   
        }
  });
});
</script>
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
@endsection

<?php 
  /*
    TITULO: ReportePedidoProductos
    PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
          [$Descripcion] Descripcion del articulo
          [$FInicial] Fecha inicial del rango donde se buscara
          [$FFinal] Fecha final del rango donde se buscara
    FUNCION: Arma una lista para el pedido de productos
    RETORNO: No aplica
   */
  function ReportePedidoProductos($SedeConnection,$Descripcion,$FInicial,$FFinal,$DiasPedido){

    $conn = ConectarSmartpharma($SedeConnection);

    $FFinalImpresion= $FFinal;
    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    $sql = QCleanTable('CP_QUnidadesVendidasCliente');
    sqlsrv_query($conn,$sql);
    $sql = QCleanTable('CP_QUnidadesDevueltaCliente');
    sqlsrv_query($conn,$sql);
    $sql = QCleanTable('CP_QUnidadesCompradasProveedor');
    sqlsrv_query($conn,$sql);
    $sql = QCleanTable('CP_QUnidadesReclamoProveedor');
    sqlsrv_query($conn,$sql);
    $sql = QCleanTable('CP_QIntegracionProductosVendidos');
    sqlsrv_query($conn,$sql);
    $sql = QCleanTable('CP_QIntegracionProductosFalla');
    sqlsrv_query($conn,$sql);
    $sql = QCleanTable('CP_QArticuloDescLike');
    sqlsrv_query($conn,$sql);

    $sql1 = QUnidadesVendidasCliente($FInicial,$FFinal);
    $sql2 = QUnidadesDevueltaCliente($FInicial,$FFinal);
    $sql3 = QUnidadesCompradasProveedor($FInicial,$FFinal);
    $sql4 = QUnidadesReclamoProveedor($FInicial,$FFinal);
    $sql5 = QIntegracionProductosVendidos();
    $sql6 = QIntegracionProductosFalla();
    $sql61 = QArticuloDescLike($Descripcion,0);
    $sql7 = QPedidoProductos();
    
    sqlsrv_query($conn,$sql1);
    sqlsrv_query($conn,$sql2);
    sqlsrv_query($conn,$sql3);
    sqlsrv_query($conn,$sql4);
    sqlsrv_query($conn,$sql5);
    sqlsrv_query($conn,$sql6);
    sqlsrv_query($conn,$sql61);
    $result = sqlsrv_query($conn,$sql7);

    $RangoDias = RangoDias($FInicial,$FFinal);

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
    echo'<h6 align="center">Periodo desde el '.$FInicial.' al '.$FFinalImpresion.' </h6>';

    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Codigo</th>
            <th scope="col">Codigo de Barra</th>
              <th scope="col">Descripcion</th>              
              <th scope="col">Existencia</th>
              <th scope="col">Unidades vendidas</th>
              <th scope="col">Unidades compradas</th>
              <th scope="col">Venta diaria</th>
          <th scope="col">Dias restantes</th>
              <th scope="col">Precio (Con IVA)</th>
              <th scope="col">Ultimo Lote</th>
              <th scope="col">Ultima Venta (En Rango)</th>
              <th scope="col">Ultima Venta</th>
              <th scope="col">Pedir</th>         
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["Id"];
      $IsIVA = $row["ConceptoImpuesto"];

      $sql8 = QExistenciaArticulo($IdArticulo,0);
      $result1 = sqlsrv_query($conn,$sql8);
      $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
      $Existencia = $row1["Existencia"];

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="left">'.$row["CodigoArticulo"].'</td>';

      $CodigoBarra = CodigoBarra($conn,$IdArticulo);
        echo '<td align="center">'.$CodigoBarra.'</td>';
      
      echo 
      '<td align="left" class="barrido">
      <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$row["Descripcion"].
      '</a>
      </td>';

      echo '<td align="center">'.intval($Existencia).'</td>';

      $Venta = intval($row["TotalUnidadesVendidasCliente"]);
      $VentaDiaria = VentaDiaria($Venta,$RangoDias);
      $DiasRestantes = DiasRestantes($Existencia,$VentaDiaria);

      $Precio = CalculoPrecio($conn,$IdArticulo,$IsIVA,$Existencia);

      $sql9 = QUltimoLote($IdArticulo);
      $result2 = sqlsrv_query($conn,$sql9);
      $row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
      $UltimoLote = $row2["UltimoLote"];

      $sql10 = QUltimaVentaCR($IdArticulo,$FInicial,$FFinal);
      $result3 = sqlsrv_query($conn,$sql10);
      $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
      $UltimaVentaCR = $row3["UltimaVenta"];

      $sql11 = QUltimaVentaSR($IdArticulo);
      $result4 = sqlsrv_query($conn,$sql11);
      $row4 = sqlsrv_fetch_array($result4,SQLSRV_FETCH_ASSOC);
      $UltimaVentaSR = $row4["UltimaVenta"];        

      echo
      '<td align="center" class="barrido">
      <a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
        .$Venta.
      '</a>
      </td>';

      echo
      '<td align="center" class="barrido">
      <a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
        .intval($row["TotalUnidadesCompradasProveedor"]).
      '</a>
      </td>';

      echo '<td align="center">'.round($VentaDiaria,2).'</td>';
      echo '<td align="center">'.round($DiasRestantes,2).'</td>';
      echo '<td align="center">'." ".round($Precio,2)." ".SigVe.'</td>';
      
      if(($UltimoLote)){
        echo '<td align="center">'.$UltimoLote->format('Y-m-d').'</td>';
      }
      else{
        echo '<td align="center"> - </td>';
      }

      if(($UltimaVentaCR)){
        echo '<td align="center">'.$UltimaVentaCR->format('Y-m-d').'</td>';
      }
      else{
        echo '<td align="center"> - </td>';
      }

      if(($UltimaVentaSR)){
        echo '<td align="center">'.$UltimaVentaSR->format('Y-m-d').'</td>';
      }
      else{
        echo '<td align="center"> - </td>';
      }

      $CantidadPedido = CantidadPedido($VentaDiaria,$DiasPedido,$Existencia);

        echo '<td align="center">'.intval($CantidadPedido).'</td>';

      echo '</tr>';
    $contador++;
    }
    echo '
        </tbody>
    </table>';

    $sql = QCleanTable('CP_QUnidadesVendidasCliente');
    sqlsrv_query($conn,$sql);
    $sql = QCleanTable('CP_QUnidadesDevueltaCliente');
    sqlsrv_query($conn,$sql);
    $sql = QCleanTable('CP_QUnidadesCompradasProveedor');
    sqlsrv_query($conn,$sql);
    $sql = QCleanTable('CP_QUnidadesReclamoProveedor');
    sqlsrv_query($conn,$sql);
    $sql = QCleanTable('CP_QIntegracionProductosVendidos');
    sqlsrv_query($conn,$sql);
    $sql = QCleanTable('CP_QIntegracionProductosFalla');
    sqlsrv_query($conn,$sql);
    $sql = QCleanTable('CP_QArticuloDescLike');
    sqlsrv_query($conn,$sql);

    sqlsrv_close($conn);
  }
?>