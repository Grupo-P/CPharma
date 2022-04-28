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
		<table style="border: 1px solid black; border-collapse: collapse; margin-left:20%;">
			<thead>
				<tr>
					<td align="center" style="border: 1px solid black;"><h5>Rango Anterior: '.$arrayGlobal['FInicialRangoAnterior'].' al '.$arrayGlobal['FInicialRangoUltimoMenos'].'</h5></th>
					<td align="center" style="border: 1px solid black;"><h5>Rango Ultimo: '.$arrayGlobal['FInicialRangoUltimo'].' al '.$arrayGlobal['Hoy'].'</h5></th>
				</tr>
				<tr>
					<td colspan="2" align="justify" style="border: 1px solid black;">
						<h5>LEYENDA</h5>
						<p>Este reporte evalua la rotacion de todos los articulos con existencia en la sede en dos partes los ultimos 15 dias desde hoy hacia atras y 15 dias mas atras para un total de 30.<br>
						La idea es comparar ambos rangos para ver la tendencia y definir si viene estable, subiendo o bajando.</br>
						Los articulos recien llegados no se evaluan para tener un juicio justo.</p>
					</th>
				</tr>				
			</thead>
			<tbody>
				<tr>
					<td align="justify" style="border: 1px solid black;">
						<h5>STATUS</h5>
						<p>
							Indeterminable, articulos con el ultimo rango en 0.</br>
							N/D, articulos con disponibilidad en el rango menor a 10 dias.</br>
							Critico, articulos con el ultimo rango menor a 20.</br>
							Bien, articulos con el ultimo rango entre 20 y 45.</br>
							Excedido, articulos con el ultimo rango mayor a 45.</br>
						</p>
					</td>
					<td align="justify" style="border: 1px solid black;">
						<h5>COMPORTAMIENTO</h5>
						<p>
							Indeterminable, articulos con disponibilidad en dias muy bajos.</br>
							Peligro, articulo sin rotacion en ambos rangos.</br>
							Estable, articulos con la rotacion en ambos rangos iguales o con una tolerancia del 10%.</br>
							Llegando, articulos con el primer rango sin disponibilidad.</br>
							Cayo, articulos con venta en el primer rango y sin venta en el ultimo rango.</br>
							Decrecio, primer rango menor que el segundo.</br>
							Crecio, primer rango mayor que el segundo.</br>
						</p>
					</td>
				</tr>				
			</tbody>
		</table>	
		
		</br>

        <form id="myFormulario" method="GET" action="/reporte49">
			<button type="submit" name="generarExcel" value="SI" class="btn btn-outline-success">Generar Excel</button>
		</form>
		
		</br>

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