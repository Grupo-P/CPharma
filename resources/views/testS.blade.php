@extends('layouts.modelUser')

@section('title')
  Tasas de venta
@endsection

@section('content')
  	<?php 
		include(app_path().'\functions\config.php'); 
		include(app_path().'\functions\funciones.php');
		
		$MiUbicacion = MiUbicacion();
		ValidarConectividad($MiUbicacion);
	?>
@endsection