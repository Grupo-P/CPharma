@extends('layouts.modelUser')

@section('title')
  Tasas de venta
@endsection

@section('content')
  	<?php 
		include(app_path().'\functions\config.php'); 
		include(app_path().'\functions\funciones.php');
		//$MiUbicacion = MiUbicacion();		

		echo('Sede: '.SedeFLL.'<br />');

		$serverName = serverFLL;
		$connectionInfo = array( "Database"=>nameFLL, "UID"=>userFLL, "PWD"=>passFLL);
		$conn = sqlsrv_connect( $serverName, $connectionInfo);

		if( $conn ) {
		     echo "Conexión establecida.<br />";
		}else{
		     echo "Conexión no se pudo establecer.<br />";
		     die( print_r( sqlsrv_errors(), true));
		}
	?>
@endsection