@extends('layouts.etiquetas')

@section('title')
    Traslado
@endsection

@section('content')
<style>
	table{
		border-collapse: collapse;
		text-align: center;
	}
	thead{
		border: 1px solid black;
		border-radius: 0px;
		background-color: #e3e3e3;
	}
	tbody{
		border: 1px solid black;
		border-radius: 0px;
	}
	td{
		border: 1px solid black;
		border-radius: 0px;
	}
	th{
		border: 1px solid black;
		border-radius: 0px;
	}
	.alinear-der{
		text-align: right;
		padding-right: 20px;
	}
	.alinear-izq{
		text-align: left;
		padding-left: 20px;
	}
	.aumento{
		font-size: 1.2em;
		text-transform: uppercase;
	}
</style>

 	<h1 class="h5 text-info" style="display: inline;">
		<i class="fas fa-print"></i>
		Soporte de traslado interno
	</h1>
	<form action="/traslado/" method="POST" style="display: inline; margin-left: 50px;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>
	<input type="button" name="imprimir" value="Imprimir" class="btn btn-outline-success btn-sm" onclick="window.print();" style="display: inline; margin-left: 10px;">

	<hr class="row align-items-start col-12">
	<table>
		<thead>
		    <tr>
		    		<th scope="row" colspan="4">
		    			<span class="navbar-brand text-info CP-title-NavBar">
		    				<b><i class="fas fa-syringe text-success"></i>CPharma</b>
  						</span>
		    		</th>
		    		<th scope="row" colspan="9" class="aumento">Soporte de Traslado interno</th>
		    </tr>
  	</thead>
	  	<tbody>
		    <tr>
	      	<td colspan="4" class="alinear-der"># de Ajuste:</td>
	      	<td colspan="9" class="alinear-izq">{{$traslado->numero_ajuste}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Fecha de Ajuste:</td>
	      	<td colspan="9" class="alinear-izq">{{$traslado->fecha_ajuste}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Fecha de Traslado:</td>
	      	<td colspan="9" class="alinear-izq">{{$traslado->fecha_traslado}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Sede Emisora:</td>
	      	<td colspan="9" class="alinear-izq">{{$traslado->sede_emisora}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Sede Destino:</td>
	      	<td colspan="9" class="alinear-izq">{{$traslado->sede_destino}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Operador emisor del ajuste:</td>
	      	<td colspan="9" class="alinear-izq">{{$traslado->operador_ajuste}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Operador emisor del traslado:</td>
	      	<td colspan="9" class="alinear-izq">{{$traslado->operador_traslado}}</td>
		    </tr>
		    <thead>
		    <tr>
		    		<th scope="row">#</th>
		    		<th scope="row">Codigo Interno</th>
		    		<th scope="row">Codigo de Barra</th>
		    		<th scope="row">Descripcion</th>
		    		<th scope="row">Gravado?</th>
		    		<th scope="row">Dolarizado?</th>
		    		<th scope="row">Costo Unit Bs. S/IVA</th>
		    		<th scope="row">Costo Unit $. S/IVA</th>
		    		<th scope="row">Cantidad</th>
		    		<th scope="row">Total Impuesto Bs.</th>
		    		<th scope="row">Total Impuesto $.</th>
		    		<th scope="row">Total Bs.</th>
		    		<th scope="row">Total $.</th>
		    </tr>
		    </thead>
					<?php
						Imprimir_Traslado_Detalle($traslado->numero_ajuste);
					?>
	  	</tbody>
	</table>
	<?php $tasa = number_format(floatval($traslado->tasa),2,"," ,"." ); ?>
	<span>Nota: La tasa usada para el calculo fue: <strong>{{$tasa}}</strong> tasa valida para la fecha: <strong>{{$traslado->fecha_tasa}}</strong>.</span>
@endsection

<?php
	/**********************************************************************************/
	/*
		TITULO: Imprimir_Traslado_Detalle
		FUNCION: Busca e imprime las lineas del detalle del traslado
		RETORNO: no aplica
		DESAROLLADO POR: SERGIO COVA
	 */
	function Imprimir_Traslado_Detalle($numero_ajuste) {
			include(app_path().'\functions\config.php');
      include(app_path().'\functions\functions.php');
      include(app_path().'\functions\querys_mysql.php');
      include(app_path().'\functions\querys_sqlserver.php');

			$connCPharma = FG_Conectar_CPharma();
			$sql = MySQL_Buscar_Traslado_Detalle($numero_ajuste);
			$result = mysqli_query($connCPharma,$sql);

			$Total_Cantidad = 0;
			$Total_Impuesto_Bs = 0;
			$Total_Impuesto_Usd = 0;
			$Total_Bs = 0;
			$Total_Usd = 0;

			$cont = 1;
			while($row = $result->fetch_assoc()) {
				echo '<tr>';
				echo '<th>'.intval($cont).'</th>';
				echo '<td>'.$row['codigo_interno'].'</td>';
				echo '<td>'.$row['codigo_barra'].'</td>';
				echo '<td>'.$row['descripcion'].'</td>';
				echo '<td>'.$row['gravado'].'</td>';
				echo '<td>'.$row['dolarizado'].'</td>';
				echo '<td>'.number_format(floatval($row['costo_unit_bs_sin_iva']),2,"," ,"." ).'</td>';
				echo '<td>'.number_format(floatval($row['costo_unit_usd_sin_iva']),2,"," ,"." ).'</td>';
				echo '<td>'.$row['cantidad'].'</td>';
				echo '<td>'.number_format(floatval($row['total_imp_bs']),2,"," ,"." ).'</td>';
				echo '<td>'.number_format(floatval($row['total_imp_usd']),2,"," ,"." ).'</td>';
				echo '<td>'.number_format(floatval($row['total_bs']),2,"," ,"." ).'</td>';
				echo '<td>'.number_format(floatval($row['total_usd']),2,"," ,"." ).'</td>';
				echo '</tr>';

				$Total_Cantidad += floatval($row['cantidad']);
				$Total_Impuesto_Bs += floatval($row['total_imp_bs']);
				$Total_Impuesto_Usd += floatval($row['total_imp_usd']);
				$Total_Bs += floatval($row['total_bs']);
				$Total_Usd += floatval($row['total_usd']);
				$cont++;
	  	}

	  	echo '<tr>';
	  	echo '<td colspan="8" class="alinear-der"><strong>Totales</strong></td>';
	  	echo '<td><strong>'.$Total_Cantidad.'</strong></td>';
	  	echo '<td><strong>'.number_format($Total_Impuesto_Bs,2,"," ,"." ).'</strong></td>';
	  	echo '<td><strong>'.number_format($Total_Impuesto_Usd,2,"," ,"." ).'</strong></td>';
	  	echo '<td><strong>'.number_format($Total_Bs,2,"," ,"." ).'</strong></td>';
	  	echo '<td><strong>'.number_format($Total_Usd,2,"," ,"." ).'</strong></td>';
	  	echo '</tr>';

			mysqli_close($connCPharma);
	}
?>