@extends('layouts.model')

@section('title')
  Unidad Minima
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
		<i class="fas fa-less-than-equal"></i>
    Unidad minima de expresion
	</h1>
	<hr class="row align-items-start col-12">
  <?php
  	include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');

    $ArtJson = "";
    $CodJson = "";

    $_GET['SEDE'] = "ARG";//FG_Mi_Ubicacion();
    
    $sql = R2Q_Lista_Articulos();
    $ArtJson = FG_Armar_Json($sql,$_GET['SEDE']);

    $sql1 = R2Q_Lista_Articulos_CodBarra();
    $CodJson = FG_Armar_Json($sql1,$_GET['SEDE']);

		echo '
		<form autocomplete="off" action="/unidad/create" target="">
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
    <form autocomplete="off" action="/unidad/create" target="_blank">
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
?>