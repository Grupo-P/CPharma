@extends('layouts.model')

@section('title')
  GALAC
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Conexion con Galac
	</h1>
	<hr class="row align-items-start col-12">

	<?php	
		include(app_path().'\functions\config.php');
		include(app_path().'\functions\functions.php');
		include(app_path().'\functions\querys_mysql.php');
		include(app_path().'\functions\querys_sqlserver.php');

		$InicioCarga = new DateTime("now");

		if (isset($_GET['SEDE'])){			
			echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
		}
		echo '<hr class="row align-items-start col-12">';

		Galac_Nomina('NOMINA');
		Galac_SAW('SAW');

		$FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
	?>
@endsection

<?php
	/**********************************************************************************/
	/*
		TITULO: Galac_Nomina
		DESAROLLADO POR: SERGIO COVA
 	*/
	function Galac_Nomina($SedeConnection) {
		$conn = FG_Conectar_GALAC($SedeConnection);
		$sql = "SELECT * FROM IGNom_Trabajador WHERE IGNom_Trabajador.Apellidos LIKE 'COVA BARRIOS'";
		$result = sqlsrv_query($conn,$sql);
		$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		echo'* * * Esta data viene de GALAC NOMINA * * *';
		echo'<br><br>Nombres: '.$row['Nombres'];
		echo'<br>Apellidos: '.$row['Apellidos'];
		echo'<br>Cedula: '.$row['NumeroDeRIF'];
		echo'<br><br>';
		sqlsrv_close($conn);
	}
	/**********************************************************************************/
	/*
		TITULO: Galac_SAW
		DESAROLLADO POR: SERGIO COVA
 	*/
	function Galac_SAW($SedeConnection) {
		$conn = FG_Conectar_GALAC($SedeConnection);
		$sql = "SELECT TOP 1 * FROM [SAWDB].[dbo].[factura] ORDER BY Fecha DESC";
		$result = sqlsrv_query($conn,$sql);
		$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		echo'* * * Esta data viene de GALAC SAW * * *';
		echo'<br><br>Numero: '.$row['Numero'];
		echo'<br>Fecha: '.$row['Fecha']->format('Y-m-d');
		echo'<br>Moneda: '.$row['Moneda'];
		echo'<br><br>';
		sqlsrv_close($conn);
	}
	
?>