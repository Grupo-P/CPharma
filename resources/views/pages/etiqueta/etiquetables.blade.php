@extends('layouts.modelUser')

@section('title')
    Etiqueta
@endsection

@section('content')

	<h1 class="h5 text-info">
		<i class="fas fa-tag"></i>
		Etiquetables
	</h1>
	<hr class="row align-items-start col-12">

<?php	
	include(app_path().'\functions\config.php');
	include(app_path().'\functions\querys.php');
	include(app_path().'\functions\funciones.php');
	include(app_path().'\functions\reportes.php');
?>

<style>
	.etiqueta{
		border-radius: 0px;
		border: 1px solid;
		height: 6.5cm;
		width: 5cm;
		display: inline;
	}
</style>

	<div class="card-deck">

		<div class="card border-dark mb-3 etiqueta">	  	
	  		<div class="card-body text-left bg-white">
	    		<p class="card-text text-dark">Prueba</p>
	  		</div>
		</div>
		
		<div class="card border-dark mb-3 etiqueta">	  	
	  		<div class="card-body text-left bg-white">
	    		<p class="card-text text-dark">Prueba</p>
	  		</div>
		</div>

		<div class="card border-dark mb-3 etiqueta">	  	
	  		<div class="card-body text-left bg-white">
	    		<p class="card-text text-dark">Prueba</p>
	  		</div>
		</div>
		
	</div>

				
@endsection

@section('scriptsHead')
    <script src="{{ asset('assets/js/sortTable.js') }}">	
    </script>
    <script src="{{ asset('assets/js/filter.js') }}">	
    </script>
@endsection

