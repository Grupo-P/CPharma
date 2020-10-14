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
        Corrida de Precios
    </h1>
    <hr class="row align-items-start col-12">

<?php
  use compras\Configuracion;
	include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

  $_GET['SEDE'] = FG_Mi_Ubicacion();

	if( (Auth::user()->departamento == 'GERENCIA') 
   	&& 
   	((Auth::user()->email == 'giordany@farmacia72.com')     
   	|| (Auth::user()->email == 'giancarlos@farmacia72.com'))
	)  {

    $configuracion = Configuracion::where('variable','DolarCalculo')->get();

    if (isset($_GET['tipoCorrida'])) {

      echo($_GET['tipoCorrida']);

    }else{

  		echo '
        <form id="form" autocomplete="off" action="" style="margin-bottom:50px;">
          <table style="width:100%;">
            <tr>
              <td align="center">Tasa de calculo:</td>
              <td>
                <input id="tasaCalculo" type="text" name="tasaCalculo" disabled style="width:auto;" value="'.number_format($configuracion[0]->valor,2,"," ,"." ).'">
              </td>

              <td align="center">Fecha:</td>
              <td>
                <input id="fecha" name="fecha" value="'.$configuracion[0]->updated_at->format("d/m/Y").'" type="text" disabled style="width:auto;">                          
              </td>

              <td align="center">Tipo de Corrida:</td>
              <td>
                <input type="radio" id="subida" name="tipoCorrida" value="subida" checked>
                <label for="subida">Subida</label><br>
                <input type="radio" id="subida/bajada" name="tipoCorrida" value="bajada">
                <label for="subida/bajada">Subida/Bajada</label><br>
              </td>

              <td>
                <input id="SEDE" name="SEDE" type="hidden" value="';echo($_GET['SEDE']);echo'">
              <input type="submit" value="Ejecutar" class="btn btn-outline-success" style="width:80%;">
              </td>
            </tr>          
          </table>                    
        </form>
      ';
    }
        
	}else{
		echo '
      <h6 align="center" style="margin-top:50px; margin-bottom:150px;">Usted NO tiene permisos para acceder a este modulo</h6>';
	}
?>
@endsection