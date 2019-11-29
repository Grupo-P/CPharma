@extends('layouts.etiquetas')

@section('title')
    Etiqueta
@endsection

@section('content')
<style>	
	table{
		display: inline;
		margin:-2px;
	}
	thead{
		border-top: 1px solid black;
		border-right: 1px solid black;
		border-left: 1px solid black;
		border-radius: 0px;
	}
	tbody{
		border-bottom: 1px solid black;
		border-right: 1px solid black;
		border-left: 1px solid black;
		border-radius: 0px;
	}
	.rowCenter{
		width: 8cm;
	}
	.rowIzqA{
		width: 5cm;
	}
	.rowDerA{
		width: 3cm;
	}
	.titulo{
		height: 0.5cm;
		font-size: 0.8em;
	}
	.descripcion{
		height: 1.5cm;
	}
	.rowDer{
		height: 1cm;
	}
	.rowIzq{
		height: 1cm;
	}
	.centrado{
		text-align: center;
		text-transform: uppercase;
	}
	.derecha{
		text-align: right;
		text-transform: uppercase;
	}
	.izquierda{
		text-align: left;
		text-transform: uppercase;
	}
	.aumento{
		font-size: 1.2em;
	}
</style>
<?php
	include(app_path().'\functions\config.php');
	include(app_path().'\functions\querys.php');
	include(app_path().'\functions\funciones.php');
	//include(app_path().'\functions\reportes.php');
	$InicioCarga = new DateTime("now");

	$clasificacion = $_GET['clasificacion'];
	$tipo = $_GET['tipo'];
	$dia = $_GET['dia'];

	echo'
	<h1 class="h5 text-info" style="display: inline;">
		<i class="fas fa-tag"></i>
		'.$clasificacion.': '.$tipo.' ('.$dia.')
	</h1>
	<input type="button" name="imprimir" value="Imprimir" class="btn btn-outline-info btn-sm" onclick="window.print();" style="display: inline; margin-left: 50px;">
	<hr class="row align-items-start col-12">
	';

	FG_Generer_Etiquetas($clasificacion,$tipo,$dia);
	$FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
?>		
@endsection

@section('scriptsHead')
    <script src="{{ asset('assets/js/sortTable.js') }}">	
    </script>
    <script src="{{ asset('assets/js/filter.js') }}">	
    </script>
@endsection