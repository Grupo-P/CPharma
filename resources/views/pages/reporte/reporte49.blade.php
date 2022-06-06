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
					<td align="center" style="border: 1px solid black;"><h5>Primer Rango: '.$arrayGlobal['FInicialPrimerRango'].' al '.$arrayGlobal['FInicialSegundoRangoMenos'].'</h5></th>
					<td align="center" style="border: 1px solid black;"><h5>Segundo Rango: '.$arrayGlobal['FInicialSegundoRango'].' al '.$arrayGlobal['Hoy'].'</h5></th>
				</tr>
				<tr>
					<td colspan="2" align="justify" style="border: 1px solid black;">
						<h5 style="margin-left:10px;">LEYENDA</h5>						
						<ul>
							<li>
								Este reporte evalua la rotacion de todos los articulos con existencia en la sede en dos partes los ultimos 15 dias desde hoy hacia atras y 15 dias mas atras para un total de 30 dias.<br>
							</li>						
							<li>
								La idea es comparar ambos rangos para ver la tendencia y definir si viene estable, subiendo o bajando.</br>
							</li>
							<li>
								Los articulos recien llegados no se evaluan para tener un juicio justo.</br>
							</li>
							<li>
								El status evalua el articulo segun su segundo rango y es una evaluacion de los dias restantes.</br>
							</li>
							<li>
								El comportamiento evalua el movimiento de articulo en ambos rangos y determina lo que sucedio.</br>
							</li>																		
						</ul>
						
					</th>
				</tr>				
			</thead>
			<tbody>
				<tr>
					<td align="justify" style="border: 1px solid black;">
						<h5 style="margin-left:10px;">STATUS</h5>
						<ul>
						<li>
							<strong>Indeterminable:</strong> articulos con el ultimo rango en 0.</br>
						</li>
						<li>
							<strong>Critico:</strong> articulos con el ultimo rango menor a 20.</br>
						</li>
						<li>
							<strong>Bien:</strong> articulos con el ultimo rango entre 20 y 45.</br>
						</li>
						<li>
							<strong>Excedido:</strong> articulos con el ultimo rango mayor a 45.</br>
						</li>
						<li>
							<strong>N/D:</strong> articulos con disponibilidad en el rango menor a 10 dias.</br>
						</li>
						</ul>
					</td>
					<td align="justify" style="border: 1px solid black;">
						<h5 style="margin-left:10px;">COMPORTAMIENTO</h5>
						<ul>
						<li>
							<strong>N/D:</strong> articulos con disponibilidad en dias muy bajos.</br>							
						</li>
						<li>
							<strong>Peligro:</strong> articulo sin rotacion en ambos rangos.</br>
						</li>
						<li>
							<strong>Estable:</strong> articulos con la rotacion en ambos rangos iguales o con una tolerancia del 10%.</br>
						</li>
						<li>
							<strong>Llegando:</strong> articulos con el primer o segundo rango sin disponibilidad.</br>
						</li>
						<li>
							<strong>Cayo:</strong> articulos con venta en el primer rango y sin venta en el ultimo rango.</br>
						</li>
						<li>
							<strong>Decrecio:</strong> primer rango menor que el segundo.</br>
						</li>
						<li>
							<strong>Crecio:</strong> primer rango mayor que el segundo.</br>
						</li>
						<li>
							<strong>Intacto:</strong> son los articulos que no variaron en ambos rangos: se mantuvo igual.<br>
						</li>
						<li>
						<strong>Atencion:</strong> Articulos primer rango N/D y segundo rango en 0.
						</li>
						</ul>
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
				<th colspan="11"></th>				
				<th>
					<select class="filterText" style="display:inline-block; border-radius: 5px;" onchange="filterText(this)">
						<option disabled selected>Filtro</option>						
						<option value="BIEN">Bien</option>
						<option value="CRITICO">Critico</option>
						<option value="EXCEDIDO">Excedido</option>
						<option value="INDETERMINABLE">Indeterminable</option>
						<option value="N/D">N/D</option>
						<option value="all">TODOS</option>
					</select>
				</th>
				<th>
					<select class="filterText" style="display:inline-block; border-radius: 5px;" onchange="filterText(this)">
						<option disabled selected>Filtro</option>	
						<option value="INTACTO">INTACTO</option>
						<option value="ESTABLE">Estable</option>						
						<option value="LLEGANDO">Llegando</option>
						<option value="CRECIO">Crecio</option>
						<option value="DECRECIO">Decrecio</option>
						<option value="CAYÓ">Cayó</option>
						<option value="PELIGRO">Peligro</option>																		
						<option value="INDETERMINABLE">Indeterminable</option>
						<option value="N/D">N/D</option>						
						<option value="all">TODOS</option>
					</select>
				</th>
			</tr>
		    <tr>
		    	<th scope="col" class="CP-sticky">#</th>
	      	    <th scope="col" class="CP-sticky">Codigo Interno</th>
                <th scope="col" class="CP-sticky">Codigo Barra</th>
                <th scope="col" class="CP-sticky">Descripcion</th>
                <th scope="col" class="CP-sticky">Existencia</th>
				<th scope="col" class="CP-sticky">Precio</br>(Con IVA) '.SigVe.'</td>
                <th scope="col" class="CP-sticky">Ultima Venta</th>
                <th scope="col" class="CP-sticky">Ultima Compra</th>
                <th scope="col" class="CP-sticky">Dias Restantes (Primer Rango) <br>('.$arrayGlobal['FInicialPrimerRango'].' al '.$arrayGlobal['FInicialSegundoRangoMenos'].')</th>                
                <th scope="col" class="CP-sticky">Dias Restantes (Segundo Rango) <br>('.$arrayGlobal['FInicialSegundoRango'].' al '.$arrayGlobal['Hoy'].')</th>
				<th scope="col" class="CP-sticky">Variacion (%)</th>
				<th scope="col" class="CP-sticky">Status</th>
				<th scope="col" class="CP-sticky">Comportamiento</th>
		    </tr>			
	  	</thead>
  	<tbody>
		';
		foreach ($arrayArticulos as $articulo) {
			echo '<tr class="content">';
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
			echo '<td class="precio" align="center">'.number_format($articulo['Precio'],2,"," ,"." ).'</td>';
			echo '<td align="center">'.$articulo['UltimaVenta'].'</td>';
            echo '<td align="center">'.$articulo['UltimaCompra'].'</td>';

			$DiasRestantesPrimerRango = $articulo['DiasRestantesPrimerRango'];
			$DiasRestantesSegundoRango = $articulo['DiasRestantesSegundoRango'];

			if( $DiasRestantesPrimerRango < 0 ){
				echo '<td align="center">'.$articulo['DiasRestantesPrimerRango'].'</td>';
				echo '<td align="center">'.$articulo['DiasRestantesSegundoRango'].'</td>';
				echo '<td align="center">'.$articulo['Variacion'].'</td>';
				echo '<td align="center">'.$articulo['Status'].'</td>';
				echo '<td align="center"></td>';
			}
			else if( $DiasRestantesSegundoRango < 0 ){
				echo '<td align="center">'.$articulo['DiasRestantesPrimerRango'].'</td>';
				echo '<td align="center">'.$articulo['DiasRestantesSegundoRango'].'</td>';
				echo '<td align="center">'.$articulo['Variacion'].'</td>';
				echo '<td align="center"></td>';
				echo '<td align="center"></td>';
			}
			else { 
				echo '<td align="center">'.$articulo['DiasRestantesPrimerRango'].'</td>';
				echo '<td align="center">'.$articulo['DiasRestantesSegundoRango'].'</td>';
				echo '<td align="center">'.$articulo['Variacion'].'</td>';
				echo '<td align="center">'.$articulo['Status'].'</td>';
				echo '<td align="center">'.$articulo['Comportamiento'].'</td>';
			}            
			echo '</tr>';
			$contador++;
  	    }
	  	echo '
  		</tbody>
		</table>';		
	}	
?>

<script>
	function filterText(select) {  
    	var rex = new RegExp($(select).val());
		if(rex =="/all/"){
			clearFilter();
		}else{			
			$('.content:visible').filter(function() {
			return !rex.test($(this).text());
			}).hide();
		}
    }

 	function clearFilter() {
    	$('.filterText').val('');
    	$('.content').show();
   	}
 </script>