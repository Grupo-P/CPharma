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
	include(app_path().'\functions\querys.php');
	include(app_path().'\functions\funciones.php');
	//include(app_path().'\functions\reportes.php');

	$InicioCarga = new DateTime("now");
	
	if (isset($_GET['SEDE'])){			
		echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
	}
	echo '<hr class="row align-items-start col-12">';
	
	ReporteActivacionProveedores($_GET['SEDE']);
	GuardarAuditoria('CONSULTAR','REPORTE','Activacion de proveedores');

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

<?php
	function ReporteActivacionProveedores($SedeConnection) {
		$conn = ConectarSmartpharma($SedeConnection);

		$sql = QCleanTable('CP_QFRegProveedor');
		sqlsrv_query($conn,$sql);

		$sql1 = QFRegProveedor();
		sqlsrv_query($conn,$sql1);

		$sql2 = QFRegRangoProveedor();
		$result = sqlsrv_query($conn,$sql2);

		echo '
		<div class="input-group md-form form-sm form-1 pl-0">
		  <div class="input-group-prepend">
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
		  </div>
		  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
		</div>
		<br/>
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
		  	<thead class="thead-dark">
			    <tr>
			    	<th scope="col">#</th>
			      	<th scope="col">Proveedor</th>
			      	<th scope="col">Ultimo registro</th>
			      	<th scope="col">Dias sin facturar</th>
			    </tr>
		  	</thead>
		  	<tbody>
		';
		$contador = 1;
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdProveedor = $row['Id'];
			$NombreProveedor = utf8_encode(addslashes($row['Nombre']));

			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
			echo 
			'<td align="left" class="barrido">
			<a href="/reporte7?Nombre='.$NombreProveedor.'&Id='.$IdProveedor.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
				.$NombreProveedor.
			'</a>
			</td>';

			echo '<td align="center">'.($row['FechaRegistro'])->format('Y-m-d').'</td>';
			echo '<td align="center">'.$row['RangoDias'].'</td>';
			echo '</tr>';
			$contador++;
	  	}
	  	echo '
	  		</tbody>
		</table>';

		$sql3 = QCleanTable('CP_QFRegProveedor');
		sqlsrv_query($conn,$sql3);
		
		sqlsrv_close($conn);
	}
?>