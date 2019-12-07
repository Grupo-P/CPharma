@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Activacion de proveedores
	</h1>
	<hr class="row align-items-start col-12">

	<?php	
		include(app_path().'\functions\config.php');
		include(app_path().'\functions\functions.php');
		include(app_path().'\functions\querys_mysql.php');
		include(app_path().'\functions\querys_sqlserver.php');

		$_GET['SEDE'] = 'FTN';

		$InicioCarga = new DateTime("now");

		if (isset($_GET['SEDE'])){			
			echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
		}
		echo '<hr class="row align-items-start col-12">';

		R16_Acticulos_Estrella($_GET['SEDE']);
		FG_Guardar_Auditoria('CONSULTAR','REPORTE','Articulos Estrella');

		$FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
	?>
@endsection

<?php
	/**********************************************************************************/
	/*
		TITULO: R16_Acticulos_Estrella
		FUNCION: Arma el reporte de articulos estrellas
		RETORNO: Lista de articulos estrella y su comportamiento
		DESAROLLADO POR: SERGIO COVA
 	*/
	function R16_Acticulos_Estrella($SedeConnection) {
		echo 'Hola soy el reporte 16, articulos estrella';
	}
?>