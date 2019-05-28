@extends('layouts.model')

@section('title')
    Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Anal&iacute;tico de precios
	</h1>
	<hr class="row align-items-start col-12">
	
	<?php
		include(app_path().'\functions\config.php');
		include(app_path().'\functions\querys.php');
		include(app_path().'\functions\funciones.php');
		include(app_path().'\functions\reportes.php');
	?>
@endsection