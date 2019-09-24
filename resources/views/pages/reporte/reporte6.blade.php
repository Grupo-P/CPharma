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
  include(app_path().'\functions\reportes.php');

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