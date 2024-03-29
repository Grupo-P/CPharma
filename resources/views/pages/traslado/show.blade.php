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

    body {
        margin-left: -45px;
    }
</style>

 	<h1 class="h5 text-info" style="display: inline;">
		<i class="fas fa-print"></i>
        {{ strpos($traslado->numero_ajuste, 'R') !== false ? 'Soporte de traslado de reclamo' : 'Soporte de traslado interno' }}
	</h1>
	<form action="/traslado/" method="POST" style="display: inline">
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>
	<input type="button" name="imprimir" value="Imprimir" class="btn btn-outline-success btn-sm" onclick="window.print();" style="display: inline">

	<hr class="row align-items-start col-12">
	<table class="principal">
		<thead>
		    <tr>
		    		<th scope="row" colspan="6">
		    			<span class="navbar-brand text-info CP-title-NavBar">
		    				<b><i class="fas fa-syringe text-success"></i>CPharma</b>
  						</span>
		    		</th>
		    		<th scope="row" colspan="10" class="aumento">
                        {{ strpos($traslado->numero_ajuste, 'R') !== false ? 'Soporte de traslado de reclamo' : 'Soporte de traslado interno' }}
                    </th>
		    </tr>
  	</thead>
	  	<tbody>
		    <tr>
	      	<td colspan="6" class="alinear-der"># de Ajuste:</td>
	      	<td colspan="10" class="alinear-izq">{{$traslado->numero_ajuste}}</td>
		    </tr>
		    <tr>
	      	<td colspan="6" class="alinear-der">Fecha de Ajuste:</td>
	      	<td colspan="10" class="alinear-izq">{{$traslado->fecha_ajuste}}</td>
		    </tr>
		    <tr>
	      	<td colspan="6" class="alinear-der">Fecha de Traslado:</td>
	      	<td colspan="10" class="alinear-izq">{{$traslado->fecha_traslado}}</td>
		    </tr>
		    <tr>
	      	<td colspan="6" class="alinear-der">Sede Emisora:</td>
	      	<td colspan="10" class="alinear-izq">{{$traslado->sede_emisora}}</td>
		    </tr>
		    <tr>
	      	<td colspan="6" class="alinear-der">Sede Destino:</td>
	      	<td colspan="10" class="alinear-izq">{{$traslado->sede_destino}}</td>
		    </tr>
            <tr>
            <td colspan="6" class="alinear-der">Estatus:</td>
            <td colspan="10" class="alinear-izq">{{$traslado->estatus}}</td>
            </tr>
		    <tr>
	      	<td colspan="6" class="alinear-der">Operador emisor del ajuste:</td>
	      	<td colspan="10" class="alinear-izq">{{$traslado->operador_ajuste}}</td>
		    </tr>
		    <tr>
	      	<td colspan="6" class="alinear-der">Operador emisor del traslado:</td>
	      	<td colspan="10" class="alinear-izq">{{$traslado->operador_traslado}}</td>
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
		    		<th scope="row">Cant.</th>
		    		<th scope="row">Total imp. Bs.</th>
		    		<th scope="row">Total imp. $.</th>
		    		<th scope="row">Total Bs.</th>
		    		<th scope="row">Total $.</th>		    		
		    		<th scope="row">Sede Origen</th>
                    <th scope="row">Exist. al impr.</th>
		    		<th scope="row">Sede Destino</th>
		    </tr>
		    </thead>
					<?php
						Imprimir_Traslado_Detalle($traslado->numero_ajuste);
					?>
	  	</tbody>
	</table>
	<?php $tasa = number_format(floatval($traslado->tasa),2,"," ,"." ); ?>

    <br>

    <strong>Notas:</strong>

    <li>La tasa usada para el calculo fue: <strong>{{$tasa}}</strong> tasa valida para la fecha: <strong>{{$traslado->fecha_tasa}}</strong>.</li>
    <li>Fecha y hora de impresión de este soporte: <strong>{{ date('d/m/Y H:i A') }}</strong>.</li>

	<br><br><br>
	<label><strong>Sede Origen - Comentarios / Apuntes</strong></label>
	<br>		
	<div style="width:100%;height:90px;border:1px solid black;"></div>
	<br><br>
	<label><strong>Sede Destino - Comentarios / Apuntes</strong></label>
	<br>		
	<div style="width:100%;height:90px;border:1px solid black;"></div>	
	<br><br>
	<table>
		<tr>
			<th style="width:350px;">Sede Origen</th>
			<th style="width:350px;">Sede Destino</th>
		</tr>
		<tr>
			<th class="text-left">Preparado Por</th>
			<th class="text-left">Recibido Por</th>
		</tr>
		<tr>
			<td class="text-left">Nombre:</td>
			<td class="text-left">Nombre:</td>
		</tr>
		<tr>
			<td class="text-left">Apellido:</td>
			<td class="text-left">Apellido:</td>
		</tr>
		<tr>
			<td class="text-left">Fecha:</td>
			<td class="text-left">Fecha:</td>
		</tr>
		<tr>
			<td class="text-left">Hora:</td>
			<td class="text-left">Hora:</td>
		</tr>
		<tr>
			<td class="text-left">Firma:</td>
			<td class="text-left">Firma:</td>
		</tr>
	</table>
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
                $existencia = Existencia($row['id_articulo']) ? Existencia($row['id_articulo']) : 0;

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
				echo '<td><input type="checkbox"></td>';				
				echo '<td>'.$existencia.'</td>';
                echo '<td><input type="checkbox"></td>';
				echo '</tr>';

                if (strpos($numero_ajuste, 'R') !== false) {
                    echo '<tr>';
                    echo '<th colspan="2">Causa</th>';
                    echo '<td colspan="9">'.$row['causa'].'</td>';
                    echo '</tr>';
                }

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
	  	echo '<td colspan="3"></td>';
	  	echo '</tr>';

			mysqli_close($connCPharma);


	}

    function existencia($IdArticulo)
    {
        $sede = FG_Mi_Ubicacion();
        $conn = FG_Conectar_Smartpharma($sede);

        $sql = SQG_Detalle_Articulo($IdArticulo);
        $result = sqlsrv_query($conn,$sql);
        $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

        return $row['Existencia'];
    }
?>
