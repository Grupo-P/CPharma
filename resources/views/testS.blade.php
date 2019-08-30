@extends('layouts.modelUser')

@section('title')
  Tasas de venta
@endsection

@section('content')
  	<?php 
		include(app_path().'\functions\config.php');
		include(app_path().'\functions\querys.php');
		include(app_path().'\functions\funciones.php');
		include(app_path().'\functions\reportes.php');
		
		Pesca();
	?>
@endsection