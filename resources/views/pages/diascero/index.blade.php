@extends('layouts.model')

@section('title')
    Dias en cero
@endsection

@section('scriptsHead')
    <script src="{{ asset('assets/js/sortTable.js') }}">	
    </script>
    <script src="{{ asset('assets/js/filter.js') }}">	
    </script>
@endsection

@section('content')

<?php
	include(app_path().'\functions\config.php');
	include(app_path().'\functions\querys.php');
	include(app_path().'\functions\funciones.php');
	include(app_path().'\functions\reportes.php');

	$connCPharma = ConectarXampp();
	$sqlCPharma = QVerCapturaDiaria();
	$result = mysqli_query($connCPharma,$sqlCPharma);
?>

	<h1 class="h5 text-info">
		<i class="fas fa-search"></i>
		Dias en cero
	</h1>

	<hr class="row align-items-start col-12">
	<table style="width:100%;">
	    <tr>
	        <td style="width:90%;">
	        	<div class="input-group md-form form-sm form-1 pl-0">
				  <div class="input-group-prepend">
				    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
				        aria-hidden="true"></i></span>
				  </div>
				  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterFirsTable()">
				</div>
	        </td>
	    </tr>
	</table>
	<br/>
	
	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col">#</th>
		      	<th scope="col">Total de Registros</th>
		      	<th scope="col">Fecha de Captura</th>
		      	<th scope="col">Fecha de Creacion</th>
		      	<th scope="col">Fecha de Actualizacion</th>
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