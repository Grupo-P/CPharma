@extends('layouts.default')

@section('title')
    Etiqueta
@endsection

@section('content')

	<h1 class="h5 text-info" style="display: inline;">
		<i class="fas fa-tag"></i>
		Obligatoriamente Etiquetar
	</h1>
	<input type="button" name="imprimir" value="Imprimir" class="btn btn-outline-info btn-sm" onclick="window.print();" style="display: inline; margin-left: 50px;">
	<hr class="row align-items-start col-12">

<style>	
	table{
		display: inline;
		/*padding: 0.2cm;*/
		margin:-2px;
	}
	thead{
		border: 1px solid black;
		border-radius: 0px;
	}
	tbody{
		border: 1px solid black;
		border-radius: 0px;
		/*margin-bottom: 2px;*/
	}
	td{
		width: 4cm;
	}
	.titulo{
		height: 1cm;
		font-size: 1em;
	}
	.descripcion{
		height: 1cm;
		font-size: 0.8em;
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
</style>
<?php
	include(app_path().'\functions\config.php');
	include(app_path().'\functions\querys.php');
	include(app_path().'\functions\funciones.php');
	include(app_path().'\functions\reportes.php');

	GenererEtiquetas('OBLIGATORIO ETIQUETAR');
?>		
@endsection

@section('scriptsHead')
    <script src="{{ asset('assets/js/sortTable.js') }}">	
    </script>
    <script src="{{ asset('assets/js/filter.js') }}">	
    </script>
@endsection