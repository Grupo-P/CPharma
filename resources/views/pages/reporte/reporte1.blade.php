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

		$InicioCarga = new DateTime("now");

		if (isset($_GET['SEDE'])){			
			echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
		}
		echo '<hr class="row align-items-start col-12">';

		R1_Activacion_Proveedores($_GET['SEDE']);
		FG_Guardar_Auditoria('CONSULTAR','REPORTE','Activacion de proveedores');

		$FinCarga = new DateTime("now");
    $IntervalCarga = $InicioCarga->diff($FinCarga);
    echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
	?>
@endsection

<?php
	/**********************************************************************************/
	/*
		TITULO: Reporte1_Activacion_Proveedores
		FUNCION: Arma el reporte de activacion de proveedores
		RETORNO: Lista de activacio de proveedores
		DESAROLLADO POR: SERGIO COVA
 	*/
	function R1_Activacion_Proveedores($SedeConnection) {
		$conn = FG_Conectar_Smartpharma($SedeConnection);
		$sql = R1Q_Activacion_Proveedores();
		$result = sqlsrv_query($conn,$sql);
		$contador = 1;

		echo '
		<div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
		  <div class="input-group-prepend">
		    <span class="input-group-text purple lighten-3" id="basic-text1">
		    	<i class="fas fa-search text-white"
		        aria-hidden="true"></i>
		    </span>
		  </div>
		  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
		</div>
		<br/>
		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		    	<th scope="col" class="CP-sticky">#</th>
	      	<th scope="col" class="CP-sticky">Proveedor</th>
	      	<th scope="col" class="CP-sticky">Ultimo registro</th>
	      	<th scope="col" class="CP-sticky">Dias sin facturar</th>
		    </tr>
	  	</thead>
  	<tbody>
		';
		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			$IdProveedor = $row['Id'];
			$NombreProveedor = FG_Limpiar_Texto($row['Nombre']);
			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
			echo 
			'<td align="left" class="CP-barrido">
			<a href="/reporte7?Nombre='.$NombreProveedor.'&Id='.$IdProveedor.'&SEDE='.$SedeConnection.'" target="_blank" style="text-decoration: none; color: black;">'
				.$NombreProveedor.
			'</a>
			</td>';
			echo '<td align="center">'.($row['FechaRegistro'])->format('d-m-Y').'</td>';
			echo '<td align="center">'.intval($row['RangoDias']).'</td>';
			echo '</tr>';
			$contador++;
  	}
	  	echo '
  		</tbody>
		</table>';
		sqlsrv_close($conn);
	}
	/**********************************************************************************/
	/*
		TITULO: R1Q_Activacion_Proveedores
		FUNCION: Query que genera la lista de los proveedores con la diferencia en dias desde el ultimo despacho de mercancia. 
		RETORNO: Lista de proveedores con diferencia en dias respecto al dia actual
		DESAROLLADO POR: SERGIO COVA
	 */
	function R1Q_Activacion_Proveedores() {
		$sql = " 
			SELECT
			ComProveedor.Id,
			GenPersona.Nombre,
			(SELECT TOP 1
				CONVERT(DATE,ComFactura.FechaRegistro)
				FROM ComFactura
				WHERE ComFactura.ComProveedorId= ComProveedor.Id
				ORDER BY FechaRegistro DESC) AS FechaRegistro,
			DATEDIFF(DAY,CONVERT(DATE,
				(SELECT TOP 1	CONVERT(DATE,ComFactura.FechaRegistro)
				FROM ComFactura 
				WHERE ComFactura.ComProveedorId= ComProveedor.Id 
				ORDER BY FechaRegistro DESC))
				,GETDATE()) As RangoDias
			FROM ComProveedor
			INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
			INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
			GROUP BY ComProveedor.Id, GenPersona.Nombre
			ORDER BY RangoDias ASC
		";
		return $sql;
	}
?>