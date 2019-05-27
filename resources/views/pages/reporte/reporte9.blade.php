@extends('layouts.model')

@section('title')
    Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Productos para surtir
	</h1>
	<hr class="row align-items-start col-12">
	
	<?php
		include(app_path().'\functions\config.php');
		include(app_path().'\functions\querys.php');
		include(app_path().'\functions\funciones.php');
		include(app_path().'\functions\reportes.php');

		if(isset($_GET['fechaInicio'])) {
			$InicioCarga = new DateTime("now");

			if(isset($_GET['SEDE'])) {
				echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
			}

		    echo '<hr class="row align-items-start col-12">';

		    ReporteProductosParaSurtir($_GET['SEDE'],$_GET['fechaInicio'],$_GET['fechaFin']);

		    $FinCarga = new DateTime("now");
		    $IntervalCarga = $InicioCarga->diff($FinCarga);
		    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
		}
		else {
		    if(isset($_GET['SEDE'])) {
		    	echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
		    }
		    echo '<hr class="row align-items-start col-12">';

			echo '
				<form autocomplete="off" action="" target="_blank">
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

    /*the container must be positioned relative:*/
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