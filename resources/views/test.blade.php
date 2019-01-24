@extends('layouts.model')

@section('title')
    Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Historico de Articulos
	</h1>
	<hr class="row align-items-start col-12">

  <?php	
	include(app_path().'\functions\config.php');
	include(app_path().'\functions\Functions.php');

	$sql_01 = 'Select InvArticulo.Descripcion from InvArticulo';
    $articulos = ConsultaDB ( $sql_01 );
    $temp_art = array_flatten_recursive($articulos);
    $ArtJson = json_encode($temp_art);
	
	if (isset($_GET['Historico'])  )
	{
	  TableHistoricoArticulos(
	  	$_GET['IdArticulo'],
	  	$_GET['CodigoArticulo'],
	  	$_GET['DescripcionArticulo']
	  );
	} 
	else{
		//TableArticulos();
		echo '
		<form autocomplete="off" action="/action_page.php">
		    <div class="autocomplete" style="width:300px;">
		      <input id="myInput" type="text" name="Articulo" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()">
		    </div>
		    <input type="submit">
	  	</form>
	  	';
	} 
?>
@endsection

@section('scriptsHead')
    <script type="text/javascript" src="{{ asset('assets/js/sortTable.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/filter.js') }}">	
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/functions.js') }}">	
    </script>
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-2.2.2.min.j') }}"></script>
  	<script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.min.js') }}" ></script>

  	<style>
    * {
      box-sizing: border-box;
    }

    body {
      font: 16px Arial;  
    }

    /*the container must be positioned relative:*/
    .autocomplete {
      position: relative;
      display: inline-block;
    }

    input {
      border: 1px solid transparent;
      background-color: #f1f1f1;
      padding: 10px;
      font-size: 16px;
    }

    input[type=text] {
      background-color: #f1f1f1;
      width: 100%;
    }

    input[type=submit] {
      background-color: DodgerBlue;
      color: #fff;
      cursor: pointer;
    }

    .autocomplete-items {
      position: absolute;
      border: 1px solid #d4d4d4;
      border-bottom: none;
      border-top: none;
      z-index: 99;
      /*position the autocomplete items to be the same width as the container:*/
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

    /*when hovering an item:*/
    .autocomplete-items div:hover {
      background-color: #e9e9e9; 
    }

    /*when navigating through the items using the arrow keys:*/
    .autocomplete-active {
      background-color: DodgerBlue !important; 
      color: #ffffff; 
    }
    </style>
@endsection

@section('scriptsFoot')
	<script type="text/javascript">
		var ArrJs = eval(<?php echo $ArtJson ?>);
		autocomplete(document.getElementById("myInput"), ArrJs); 
	</script> 	
@endsection

