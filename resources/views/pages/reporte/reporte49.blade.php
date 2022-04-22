<?php 
/*
	echo "<pre>";	
	print_r($arrayGlobal);
	print_r($arrayArticulos);
	echo "</pre>";
	die();
*/
?>

@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Reposicion de Inventario
	</h1>
	<hr class="row align-items-start col-12">

	<?php					
		if (isset($arrayGlobal['SedeConnection'])){			
			echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($arrayGlobal['SedeConnection']).'</h1>';
		}
		echo '<hr class="row align-items-start col-12">';

		R49_Reposicion_Inventario($arrayGlobal,$arrayArticulos);
		FG_Guardar_Auditoria('CONSULTAR','REPORTE','Reposicion de Inventario');
		
        echo'Tiempo de carga: '.$arrayGlobal['Tiempo'];
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
	function R49_Reposicion_Inventario($arrayGlobal,$arrayArticulos) {
		
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
        
        <h6 align="left">Rango Anterior: '.$arrayGlobal['FInicialRangoAnterior'].' al '.$arrayGlobal['FInicialRangoUltimoMenos'].' </h6>
        <h6 align="left">Rango Ultimo: '.$arrayGlobal['FInicialRangoUltimo'].' al '.$arrayGlobal['Hoy'].' </h6>

		<table class="table table-striped table-bordered col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		    	<th scope="col" class="CP-sticky">#</th>
	      	    <th scope="col" class="CP-sticky">Codigo Interno</th>
                <th scope="col" class="CP-sticky">Codigo Barra</th>
                <th scope="col" class="CP-sticky">Descripcion</th>
                <th scope="col" class="CP-sticky">Existencia</th>
                <th scope="col" class="CP-sticky">Ultima Venta</th>
                <th scope="col" class="CP-sticky">Ultima Compra</th>
                <th scope="col" class="CP-sticky">Dias Restantes (Rango Anterior) ('.$arrayGlobal['FInicialRangoAnterior'].' al '.$arrayGlobal['FInicialRangoUltimoMenos'].')</th>                
                <th scope="col" class="CP-sticky">Dias Restantes (Rango Ultimo) ('.$arrayGlobal['FInicialRangoUltimo'].' al '.$arrayGlobal['Hoy'].')</th>
				<th scope="col" class="CP-sticky">Variacion (%)</th>
				<th scope="col" class="CP-sticky">Status</th>
				<th scope="col" class="CP-sticky">Comportamiento</th>
		    </tr>
	  	</thead>
  	<tbody>
		';
		foreach ($arrayArticulos as $articulo) {
			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
            echo '<td align="center">'.$articulo['CodigoInterno'].'</td>';
            echo '<td align="center">'.$articulo['CodigoBarra'].'</td>';

            echo
			'<td align="left" class="CP-barrido">
			<a href="/reporte2?Id='.$articulo['IdArticulo'].'&SEDE='.$arrayGlobal['SedeConnection'].'" style="text-decoration: none; color: black;" target="_blank">'
				.FG_Limpiar_Texto($articulo['Descripcion']).
			'</a>
			</td>';
			
            echo '<td align="center">'.intval($articulo['Existencia']).'</td>';
			echo '<td align="center">'.$articulo['UltimaVenta'].'</td>';
            echo '<td align="center">'.$articulo['UltimaCompra'].'</td>';
            echo '<td align="center">'.$articulo['DiasRestantesAnterior'].'</td>';
            echo '<td align="center">'.$articulo['DiasRestantesUltimo'].'</td>';
			echo '<td align="center">'.$articulo['Variacion'].'</td>';
			echo '<td align="center">'.$articulo['Status'].'</td>';
			echo '<td align="center">'.$articulo['Comportamiento'].'</td>';
			echo '</tr>';
			$contador++;
  	    }
	  	echo '
  		</tbody>
		</table>';		
	}	
?>