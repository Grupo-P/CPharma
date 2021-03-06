@extends('layouts.model')

@section('title')
    Productos en Caida
@endsection

@section('content')

<?php
	include(app_path().'\functions\config.php');
	include(app_path().'\functions\functions.php');
	include(app_path().'\functions\querys_mysql.php');
	include(app_path().'\functions\querys_sqlserver.php');

	$connCPharma = FG_Conectar_CPharma();
	$sqlCPharma = "SELECT * FROM captura_caida order by fecha_captura desc";
	$result = mysqli_query($connCPharma,$sqlCPharma);
	FG_Guardar_Auditoria('CONSULTAR','CAPTURA','PRODUCTOS EN CAIDA');
?>

	<h1 class="h5 text-info">
		<i class="fas fa-chart-line"></i>
		Productos en Caida
	</h1>

	<hr class="row align-items-start col-12">
	<table style="width:100%;" class="CP-stickyBar">
	    <tr>
	        <td style="width:90%;">
	        	<div class="input-group md-form form-sm form-1 pl-0">
				  <div class="input-group-prepend">
				    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
				        aria-hidden="true"></i></span>
				  </div>
				  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
				</div>
	        </td>
	    </tr>
	</table>
	<br/>
	
	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col" class="CP-sticky">#</th>
		      	<th scope="col" class="CP-sticky">Total de Registros</th>
		      	<th scope="col" class="CP-sticky">Fecha de Captura</th>
		      	<th scope="col" class="CP-sticky">Fecha de Creacion</th>
		      	<th scope="col" class="CP-sticky">Fecha de Actualizacion</th>
		    </tr>
	  	</thead>
	  	<tbody>
	  	<?php
		while($row = $result->fetch_assoc()) {
			echo '<tr>';
			echo '<td align="center"><strong>'.$row['id'].'</strong></td>';
			echo '<td align="center">'.$row['total_registros'].'</td>';
			echo '<td align="center">'.$row['fecha_captura'].'</td>';
			echo '<td align="center">'.$row['created_at'].'</td>';
			echo '<td align="center">'.$row['updated_at'].'</td>';
			echo '</tr>';
	  	}
			mysqli_close($connCPharma);
		?>
		</tbody>
	</table>
@endsection