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
    Ventas cruzadas
  </h1>
  <hr class="row align-items-start col-12">
  
<?php 
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  $ArtJson = "";
  $CodJson = "";
  $_GET['SEDE'] = 'FTN';

  if (isset($_GET['SEDE'])){      
    echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
  }
  echo '<hr class="row align-items-start col-12">';

  if ((isset($_GET['Descrip']))OR(isset($_GET['CodBar']))){

    $InicioCarga = new DateTime("now");

    if((isset($_GET['Descrip']))&&(!empty($_GET['Descrip']))) {
      //R17_Tri_Tienda_Articulo($_GET['SEDE'],$_GET['Descrip'],$_GET['fechaInicio'],$_GET['fechaFin'],'Descrip');
      R19_Venta_Cruzada($_GET['SEDE'],$_GET['IdD'],$_GET['fechaInicio'],$_GET['fechaFin'],'Descrip');
    }
    else if(isset($_GET['CodBar'])&&(!empty($_GET['CodBar']))){
     // R17_Tri_Tienda_Articulo($_GET['SEDE'],$_GET['IdCB'],$_GET['fechaInicio'],$_GET['fechaFin'],'CodBar');
     R19_Venta_Cruzada($_GET['SEDE'],$_GET['IdCB'],$_GET['fechaInicio'],$_GET['fechaFin'],'CodBar');
    }
  
    //FG_Guardar_Auditoria('CONSULTAR','REPORTE','Tri Tienda Por Articulo');

    $FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  }
  else{
    $InicioCarga = new DateTime("now");

    $sql = R19Q_Lista_Articulos();
    $ArtJson = FG_Armar_Json($sql,$_GET['SEDE']);

    $sql1 = R19Q_Lista_Articulos_CodBarra();
    $CodJson = FG_Armar_Json($sql1,$_GET['SEDE']);

    echo '
    <form id="form" autocomplete="off" action="" target="_blank">
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
/**********************************************************************************/
  /*
    TITULO: R19Q_Lista_Articulos
    FUNCION: Armar una lista de articulos
    RETORNO: Lista de articulos
    DESAROLLADO POR: SERGIO COVA
  */
  function R19Q_Lista_Articulos() {
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
    TITULO: R19Q_Lista_Articulos_CodBarra
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function R19Q_Lista_Articulos_CodBarra() {
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
  /********************************************************************************/ 
  /*
    TITULO: R19_Venta_Cruzada
    FUNCION: Arma una lista para el pedido de productos
    RETORNO: No aplica
    DESAROLLADO POR: SERGIO COVA
  */
   function R19_Venta_Cruzada($SedeConnection,$IdArticulo,$FInicial,$FFinal,$Flag){

    $conn = FG_Conectar_Smartpharma($SedeConnection);
    $connCPharma = FG_Conectar_CPharma();

    $FInicialImp = date("d-m-Y", strtotime($FInicial));
    $FFinalImp= date("d-m-Y", strtotime($FFinal));

    $FFinal = date("Y-m-d",strtotime($FFinal."+ 1 days"));

    

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
    echo'
    <table class="table table-striped table-bordered col-12 sortable" id="myTable">
        <thead class="thead-dark">
          <tr>
            <th scope="col" class="CP-sticky">#</th>
            <th scope="col" class="CP-sticky">Fecha</th>
            <th scope="col" class="CP-sticky">Hora</th>
            <th scope="col" class="CP-sticky"># Factura</th>              
            <th scope="col" class="CP-sticky">Total Factura</th>
            <th scope="col" class="CP-sticky">Codigo Interno</th>
            <th scope="col" class="CP-sticky">Codido de Barra</th>
            <th scope="col" class="CP-sticky">Cantidad</th>
            <th scope="col" class="CP-sticky">Precio (En factura)</th>
            <th scope="col" class="CP-sticky">Caja</th>
            <th scope="col" class="CP-sticky">Cliente</th>     
          </tr>
        </thead>
        <tbody>
    ';
    
    echo '
      </tbody>
    </table>';
  }
?>