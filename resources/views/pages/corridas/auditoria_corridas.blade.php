@extends('layouts.model')

@section('title')
    Auditoria Corridas
@endsection

@section('content')

<?php
	include(app_path().'\functions\config.php');
	include(app_path().'\functions\functions.php');
  	include(app_path().'\functions\querys_mysql.php');
  	include(app_path().'\functions\querys_sqlserver.php');

	$connCPharma = FG_Conectar_CPharma();
	$sqlCPharma = "SELECT * FROM auditoria_corridas order by id desc";
	$result = mysqli_query($connCPharma,$sqlCPharma);
	FG_Guardar_Auditoria('CONSULTAR','CORRIDA DE PRECIOS','CPHARMA');
?>

	<h1 class="h5 text-info">
		<i class="fas fa-search-dollar"></i>
		Auditoria Corridas
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
          <th scope="col" class="CP-sticky">Tipo de corrida</th>
          <th scope="col" class="CP-sticky">Operador</th>
          <th scope="col" class="CP-sticky">Fecha</th>
          <th scope="col" class="CP-sticky">Hora</th>
          <th scope="col" class="CP-sticky">Nota</th>
          <th scope="col" class="CP-sticky">Articulos evaluados</th>
          <th scope="col" class="CP-sticky">Articulos procesados exito</th>
          <th scope="col" class="CP-sticky">Articulos con fallas</th>
          <th scope="col" class="CP-sticky">Articulos que cambiaron</th>
          <th scope="col" class="CP-sticky">Articulos sin cambios</th>
      	</tr>
	  	</thead>
	  	<tbody>
	  	<?php
		while($row = $result->fetch_assoc()) {
			$tipocorrida = $row['tipocorrida'];
      $tipocorrida = ($tipocorrida=='bajada')?'Subida/Bajada':$tipocorrida;
			echo '<tr>';
			echo '<td align="center"><strong>'.$row['id'].'</strong></td>';
      echo '<td align="center">'.$tipocorrida.'</td>';
      echo '<td align="center">'.$row['operador'].'</td>';
      echo '<td align="center">'.$row['fecha'].'</td>';
      echo '<td align="center">'.$row['hora'].'</td>';
      echo '<td align="center">'.$row['observacion'].'</td>';
      echo '<td align="center">'.$row['evaluados'].'</td>';
      echo '<td align="center">'.$row['exitos'].'</td>';
      echo '<td align="center">'.$row['fallas'].'</td>';
      echo '<td align="center">'.$row['cambios'].'</td>';
      echo '<td align="center">'.$row['nocambio'].'</td>';
			echo '</tr>';
	  	}
			mysqli_close($connCPharma);
		?>
		</tbody>
	</table>
@endsection