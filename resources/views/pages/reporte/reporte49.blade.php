<?php    
    $SedeConnection = FG_Mi_Ubicacion();

	echo'<pre>';
	print_r($arrayGlobal);
	echo'<br><br>';
	print_r($arrayArticulos);
	echo'</pre>';
	die;
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
		if (isset($SedeConnection)){			
			echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($SedeConnection).'</h1>';
		}
		echo '<hr class="row align-items-start col-12">';

		R49_Reposicion_Inventario($ArrayData);
		FG_Guardar_Auditoria('CONSULTAR','REPORTE','Reposicion de Inventario');
		
        echo'Tiempo de carga: '.$Tiempo;
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
	function R49_Reposicion_Inventario($ArrayData) {
		
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
        
        <h6 align="left">Rango Anterior: '.$FInicial_RangoAnterior.' al '.$FInicial_RangoUltimo_menos.' </h6>
        <h6 align="left">Rango Ultimo: '.$FInicial_RangoUltimo.' al '.$Hoy.' </h6>

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
                <th scope="col" class="CP-sticky">Dias Restantes (Rango Anterior) ('.$FInicial_RangoAnterior.' al '.$FInicial_RangoUltimo_menos.')</th>                
                <th scope="col" class="CP-sticky">Dias Restantes (Rango Ultimo) ('.$FInicial_RangoUltimo.' al '.$Hoy.')</th>
		    </tr>
	  	</thead>
  	<tbody>
		';
		foreach ($ArrayData as $Data) {                    						
			echo '<tr>';
			echo '<td align="center"><strong>'.intval($contador).'</strong></td>';
            echo '<td align="center">'.$Data['CodigoInterno'].'</td>';
            echo '<td align="center">'.$Data['CodigoBarra'].'</td>';

            echo
			'<td align="left" class="CP-barrido">
			<a href="/reporte2?Id='.$Data['IdArticulo'].'&SEDE='.$Data['SedeConnection'].'" style="text-decoration: none; color: black;" target="_blank">'
				.FG_Limpiar_Texto($Data['Descripcion']).
			'</a>
			</td>';
			
            echo '<td align="center">'.intval($Data['Existencia']).'</td>';
			echo '<td align="center">'.$Data['UltimaVenta'].'</td>';
            echo '<td align="center">'.$Data['UltimaCompra'].'</td>';
            echo '<td align="center">'.$Data['DiasRestantesQuiebre_RangoAnterior'].'</td>';			
            echo '<td align="center">'.$Data['DiasRestantesQuiebre_RangoUltimo'].'</td>';			
			echo '</tr>';
			$contador++;
  	    }
	  	echo '
  		</tbody>
		</table>';		
	}	
?>