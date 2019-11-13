@extends('layouts.etiquetas')

@section('title')
    Orden de compra
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
		Soporte de orden de compra
	</h1>
	<form action="/ordenCompra/" method="POST" style="display: inline; margin-left: 50px;">  
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
		    		<th scope="row" colspan="3" class="aumento">Soporte de orden de compra</th>
		    </tr>
  	</thead>
	  	<tbody>
		    <tr>
	      	<td colspan="4" class="alinear-der">Codigo:</td>
	      	<td colspan="3" class="alinear-izq">{{$OrdenCompra->codigo}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Fecha de orden:</td>
	      	<td colspan="3" class="alinear-izq">{{$OrdenCompra->created_at}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Destino:</td>
	      	<td colspan="3" class="alinear-izq">{{$OrdenCompra->sede_origen}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Proveedor:</td>
	      	<td colspan="3" class="alinear-izq">{{$OrdenCompra->proveedor}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Fecha Despacho:</td>
	      	<td colspan="3" class="alinear-izq">{{$OrdenCompra->fecha_estimada_despacho}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Condicion crediticia:</td>
	      	<td colspan="3" class="alinear-izq">{{$OrdenCompra->condicion_crediticia}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Dias credito:</td>
	      	<td colspan="3" class="alinear-izq">{{$OrdenCompra->dias_credito}}</td>
		    </tr>
		    <tr>
	      	<td colspan="4" class="alinear-der">Operador:</td>
	      	<td colspan="3" class="alinear-izq">{{$OrdenCompra->user}}</td>
		    </tr>
	  		<tr>
	      	<th colspan="4" class="alinear-der">Estatus:</th>
	      	<th colspan="3" class="alinear-izq">{{$OrdenCompra->estado}}</th>
		    </tr>
			<thead>
		    <tr>
		    		<th scope="row">#</th>
		    		<th scope="row">Codigo Interno</th>
		    		<th scope="row">Codigo Barra</th>
		    		<th scope="row">Descripcion</th>
		    		<th scope="row">Unidades</th>
		    		<th scope="row">Costo {{$OrdenCompra->moneda}}</th>
		    		<th scope="row">Total {{$OrdenCompra->moneda}}</th>
		    </tr>
	    </thead>
			<?php
				Imprimir_Orden_Detalle($OrdenCompra->codigo,$OrdenCompra->moneda);
			?>
		</tbody>
	</table>
	<br/>
	<span>Nota: 
	<br/>
	*La informacion aqui contenida es propiedad exclusiva de {{$OrdenCompra->sede_origen}}
	<br/>
	* Para cualquier aclaratoria llamar al 0412-1217294 o escribir al correo compras@farmacia72.com.ve 
	<br/>
	* Para cualquier informacion de pagos contactar al 0412-7646518 o escribir al correo administracion@farmacia72.com.ve
	<br/>
	* Antes de imprimir este documento piensa en tu responsabilidad con el medio ambiente</span>
@endsection

<?php
	/**********************************************************************************/
	/*
		TITULO: Imprimir_Traslado_Detalle
		FUNCION: Busca e imprime las lineas del detalle del traslado
		RETORNO: no aplica
		DESAROLLADO POR: SERGIO COVA
	 */
	function Imprimir_Orden_Detalle($codigo_orden,$moneda) {
			include(app_path().'\functions\config.php');
      include(app_path().'\functions\functions.php');
      include(app_path().'\functions\querys_mysql.php');
      include(app_path().'\functions\querys_sqlserver.php');

			$connCPharma = FG_Conectar_CPharma();
			$sql = MySQL_Buscar_Orden_Detalle($codigo_orden);
			$result = mysqli_query($connCPharma,$sql);

			$total_unidades = 0;
			$costo_total = 0;
			$cont = 1;

			while($row = $result->fetch_assoc()) {
				echo '<tr>';
				echo '<th>'.intval($cont).'</th>';
				echo '<td>'.$row['codigo_articulo'].'</td>';
				echo '<td>'.$row['codigo_barra'].'</td>';
				echo '<td>'.$row['descripcion'].'</td>';
				echo '<td>'.$row['total_unidades'].'</td>';
				echo '<td>'.number_format(floatval($row['costo_unitario']),2,"," ,"." ).'</td>';
				echo '<td>'.number_format(floatval($row['costo_total']),2,"," ,"." ).'</td>';
				echo '</tr>';

				$total_unidades += floatval($row['total_unidades']);
				$costo_total += floatval($row['costo_total']);
				$cont++;
	  	}
	  	
	  	echo '<tr>';
	  	echo '<td colspan="4" class="alinear-der"><strong>Totales</strong></td>';
	  	echo '<td><strong>'.$total_unidades.'</strong></td>';
	  	echo '<td><strong></strong></td>';
	  	echo '<td><strong>'.number_format($costo_total,2,"," ,"." ).' '.$moneda.'</strong></td>';
	  	
			mysqli_close($connCPharma);
	}
?>