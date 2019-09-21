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
		Productos mas vendidos
	</h1>
	<hr class="row align-items-start col-12">

  <?php	
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\querys.php');
  include(app_path().'\functions\funciones.php');
  include(app_path().'\functions\reportes.php');

	if (isset($_GET['top']))
	{
    $InicioCarga = new DateTime("now");

    if (isset($_GET['SEDE'])){      
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
    }
    echo '<hr class="row align-items-start col-12">';

    R3_Productos_MasVendidos($_GET['SEDE'],$_GET['top'],$_GET['fechaInicio'],$_GET['fechaFin']);
    GuardarAuditoria('CONSULTAR','REPORTE','Productos mas vendidos');
    
    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
	} 
	else{
    if (isset($_GET['SEDE'])){      
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
    }
    echo '<hr class="row align-items-start col-12">';

		echo '
		<form autocomplete="off" action="" target="_blank">
        <table style="width:100%;">
          <tr>
            <td align="center">
              TOP:
            </td>
            <td align="right" style="width:20%;">
              <select name="top" class="form-control">
                <option>5</option>
                <option>10</option>
                <option>15</option>
                <option>20</option>
                <option>50</option>
                <option>100</option>
                <option>200</option>
                <option>500</option>
                <option>1000</option>
              </select>
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
  /*
    TITULO: R3_Productos_MasVendidos
    PARAMETROS: [$SedeConnection] Siglas de la sede para la conexion
          [$Top] Top de articulos a buscar
          [$FInicial] Fecha inicial del rango donde se buscara
          [$FFinal] Fecha final del rango donde se buscara
    FUNCION: Arma una tabla con los productos mas vendidos
    RETORNO: No aplica
   */
  function R3_Productos_MasVendidos($SedeConnection,$Top,$FInicial,$FFinal){

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

    $sql1 = QUnidadesVendidasCliente($FInicial,$FFinal);
    $sql2 = QUnidadesDevueltaCliente($FInicial,$FFinal);
    $sql3 = QUnidadesCompradasProveedor($FInicial,$FFinal);
    $sql4 = QUnidadesReclamoProveedor($FInicial,$FFinal);
    $sql5 = QIntegracionProductosVendidos();
    $sql6 = QProductoMasVendido($Top);
    
    sqlsrv_query($conn,$sql1);
    sqlsrv_query($conn,$sql2);
    sqlsrv_query($conn,$sql3);
    sqlsrv_query($conn,$sql4);
    sqlsrv_query($conn,$sql5);
    $result = sqlsrv_query($conn,$sql6);

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

    echo'<h6 align="center">Periodo desde el '.$FInicial.' al '.$FFinalImpresion.' </h6>';
    
    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Codigo</th>
              <th scope="col">Descripcion</th>
              <th scope="col">Existencia</th>
              <th scope="col">Tipo</th>
              <th scope="col">Total de Venta</th>
              <th scope="col">Veces Facturado</th>            
              <th scope="col">Unidades vendidas</th>              
              <th scope="col">Unidades Compradas</th>                     
              <th scope="col">Venta diaria</th>
              <th scope="col">Dias restantes</th>
          </tr>
        </thead>
        <tbody>
    ';
    $contador = 1;
    while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
      $IdArticulo = $row["Id"];

      $sql7 = QExistenciaArticulo($IdArticulo,0);
      $result1 = sqlsrv_query($conn,$sql7);
      $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
      $Existencia = $row1["Existencia"];

      $Tipo = ProductoMedicina($conn,$IdArticulo);

      $sql = QCleanTable('CP_QVentasParcial');
      sqlsrv_query($conn,$sql);
      $sql = QCleanTable('CP_QDevolucionParcial');
      sqlsrv_query($conn,$sql);

      $sql8 = QVentasParcial($FInicial,$FFinal,$IdArticulo);
      sqlsrv_query($conn,$sql8);
      $sql9 = QDevolucionParcial($FInicial,$FFinal,$IdArticulo);
      sqlsrv_query($conn,$sql9);

      $sql10 = QIntegracionVentasParcial();
      $result2 = sqlsrv_query($conn,$sql10);
      $row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
      $TotalVenta = $row2["TotalVenta"];

      $sql11 = QIntegracionDevolucionParcial();
      $result3 = sqlsrv_query($conn,$sql11);
      $row3 = sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC);
      $TotalDevolucion = $row3["TotalDevolucion"];

      $TotalVenta = $TotalVenta - $TotalDevolucion;

      echo '<tr>';
      echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
      echo '<td align="left">'.$row["CodigoArticulo"].'</td>';

      echo 
      '<td align="left" class="barrido">
      <a href="/reporte2?Id='.$IdArticulo.'&SEDE='.$SedeConnection.'" style="text-decoration: none; color: black;" target="_blank">'
        .$row["Descripcion"].
      '</a>
      </td>';

      echo '<td align="center">'.intval($Existencia).'</td>';
      echo '<td align="center">'.$Tipo.'</td>';
      echo '<td align="center">'." ".intval(round($TotalVenta,2))." ".SigVe.'</td>';
      
      echo 
      '<td align="center" class="barrido">
      <a href="reporte12?fechaInicio='.$FInicial.'&fechaFin='.$FFinalImpresion.'&SEDE='.$SedeConnection.'&Descrip='.$row["Descripcion"].'&Id='.$IdArticulo.'" style="text-decoration: none; color: black;" target="_blank">'
        .$row["TotalVecesVendidasCliente"].
      '</a>
      </td>';

      $Venta = intval($row["TotalUnidadesVendidasCliente"]);
      $VentaDiaria = VentaDiaria($Venta,$RangoDias);
      $DiasRestantes = DiasRestantes($Existencia,$VentaDiaria);

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
    $sql = QCleanTable('CP_QVentasParcial');
    sqlsrv_query($conn,$sql);
    $sql = QCleanTable('CP_QDevolucionParcial');
    sqlsrv_query($conn,$sql);

    sqlsrv_close($conn);
  }
?>