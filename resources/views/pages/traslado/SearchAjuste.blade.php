@extends('layouts.model')

@section('title')
  Traslado
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
    <i class="fas fa-people-carry"></i>
    Traslado
  </h1>
	<hr class="row align-items-start col-12">
  <?php	
  	include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');

    $ArtJson = "";

    $_GET['SEDE'] = MiUbicacion();

    if (isset($_GET['SEDE'])) {     
      echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
      }
      echo '<hr class="row align-items-start col-12">';
  	
  	if (isset($_GET['Id'])) {
      $InicioCarga = new DateTime("now");

      $FinCarga = new DateTime("now");
      $IntervalCarga = $InicioCarga->diff($FinCarga);
      echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
  	} 
  	else {
      $InicioCarga = new DateTime("now");

      $sql = QTraslado_Lista_Ajuste();
      $ArtJson = FG_Armar_Json($sql,$_GET['SEDE']);

  		echo '
  		<form autocomplete="off" action="/traslado/create" target="_blank">
  	    <div class="autocomplete" style="width:90%;">
          <input id="myInput" type="text" name="Ajuste" placeholder="Ingrese el numero del ajuste" onkeyup="conteo()">
  	      <input id="myId" name="Id" type="hidden">
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
@endsection

<?php
  /**********************************************************************************/
  /*
    TITULO: QTraslado_Lista_Ajuste
    FUNCION: Armar una lista de todos los ajustes por salida
    RETORNO: Lista de numeros de ajuste por salida con id
    DESAROLLADO POR: SERGIO COVA
  */
  function QTraslado_Lista_Ajuste() {
    $sql = "
      SELECT
      InvAjuste.NumeroAjuste,
      InvAjuste.Id
      FROM InvAjuste
      WHERE InvAjuste.M_TotalCostoAjuste < 0
      ORDER BY InvAjuste.NumeroAjuste ASC
    ";
    return $sql;
  }
?>