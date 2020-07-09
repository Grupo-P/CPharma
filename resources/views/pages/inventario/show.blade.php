@extends('layouts.model')

@section('title')
    Inventario
@endsection

<?php
	use compras\InventarioDetalle;
	include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');
?>

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
		padding: 10px;
	}
	th{
		border: 1px solid black;
		border-radius: 0px;
		padding: 10px;
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

 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de inventario
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/inventario/" method="POST" style="display: inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>
	<input type="button" name="imprimir" value="Imprimir" class="btn btn-outline-success btn-sm" onclick="window.print();" style="display: inline; margin-left: 10px;">

	<br>
	<br>

	<table>
		<thead>
				<tr>
					<th scope="row" colspan="4">
	    			<span class="navbar-brand text-info CP-title-NavBar">
	    				<b><i class="fas fa-syringe text-success"></i>CPharma</b>
						</span>
	    		</th>
					<th colspan="9">DATOS GENERALES DEL INVENTARIO</th>
				</tr>
		    <tr>
      		<th scope="row" colspan="1">#</th>
      		<th scope="row" colspan="1">Codigo</th>      		
      		<th scope="row" colspan="1">Origen</th>
      		<th scope="row" colspan="1">Motivo</th>
      		<th scope="row" colspan="1">Cantidad SKU</th>
      		<th scope="row" colspan="1">Unidades</th>
      		<th scope="row" colspan="1">Operador Generado</th>
      		<th scope="row" colspan="1">Fecha Generado</th>
      		<th scope="row" colspan="1">Operador Revisado</th>
      		<th scope="row" colspan="1">Fecha Revisado</th>
      		<th scope="row" colspan="1">Operador Anulado</th>
      		<th scope="row" colspan="1">Fecha Anulado</th>
      		<th scope="row" colspan="1">Comentario</th>
		    </tr>
	  	</thead>
			
			<?php
				$fechaGenerado = new DateTime($inventario->fecha_generado);
				$fechaGenerado = $fechaGenerado->format('d-m-Y h:i:m A');

				if($inventario->fecha_revisado!=""){
					$fechaRevisado = new DateTime($inventario->fecha_revisado);
					$fechaRevisado = $fechaRevisado->format('d-m-Y h:i:m A');	
				}
				else{
					$fechaRevisado = "";
				}
				

				if($inventario->fecha_anulado!=""){
					$fechaAnulado = new DateTime($inventario->fecha_anulado);
					$fechaAnulado = $fechaAnulado->format('d-m-Y h:i:m A');
				}	
				else{
					$fechaAnulado = "";
				}						
			?>

	  	<tbody>
		    <tr>
	      	<th scope="row" colspan="1">{{$inventario->id}}</th>
      		<td scope="row" colspan="1">{{$inventario->codigo}}</td>
      		<td scope="row" colspan="1">{{$inventario->origen_conteo}}</td>
      		<td scope="row" colspan="1">{{$inventario->motivo_conteo}}</td>
      		<td scope="row" colspan="1">{{$inventario->cantidades_conteo}}</td>
      		<td scope="row" colspan="1">{{$inventario->unidades_conteo}}</td>	
    			<td scope="row" colspan="1">{{$inventario->operador_generado}}</td>
    			<td scope="row" colspan="1">{{$fechaGenerado}}</td>	
    			<td scope="row" colspan="1">{{$inventario->operador_revisado}}</td>
    			<td scope="row" colspan="1">{{$fechaRevisado}}</td>
    			<td scope="row" colspan="1">{{$inventario->operador_anulado}}</td>
    			<td scope="row" colspan="1">{{$fechaAnulado}}</td>
    			<td scope="row" colspan="1">{{$inventario->comentario}}</td>
		    </tr>
	  	</tbody>
	</table>

	<br><br>

	<table>
		<thead>
				<tr>
					<th scope="row" colspan="4">
	    			<span class="navbar-brand text-info CP-title-NavBar">
	    				<b><i class="fas fa-syringe text-success"></i>CPharma</b>
						</span>
	    		</th>
					<th colspan="12">DETALLES DEL INVENTARIO</th>
				</tr>
		    <tr>
      		<th scope="row" colspan="1">#</th>
      		<th scope="row" colspan="1">Codigo de inventario</th>      		
      		<th scope="row" colspan="1">Codigo interno</th>
      		<th scope="row" colspan="1">Codigo de barra</th>
      		<th scope="row" colspan="1">Descripcion</th>
      		<th scope="col">Precio</br>(Con IVA) {{SigVe}}</td>
      		<th scope="row" colspan="1">Ultimo lote</th>
      		<th scope="row" colspan="1">Ultima venta</th>
      		<th scope="row" colspan="1">Conteo anterior</th>
      		<th scope="row" colspan="1">Existencia Sistema<br>(A conteo)</th>
      		<th scope="row" colspan="1">Conteo</th> 
      		<th scope="row" colspan="1">Operador Conteo</th> 
      		<th scope="row" colspan="1">Fecha Conteo</th> 
      		<th scope="row" colspan="1">Reconteo</th>
      		<th scope="row" colspan="1">Operador Reconteo</th> 
      		<th scope="row" colspan="1">Fecha Reconteo</th> 
		    </tr>
	  	</thead>

			<?php
				$inventarioDetalles = 
          InventarioDetalle::orderBy('id','asc')
          ->where('codigo_conteo',$inventario->codigo)
          ->get();
			?>
	  	<tbody>
			
				@foreach($inventarioDetalles as $inventarioDetalle)
					
					<?php
						$conn = FG_Conectar_Smartpharma(FG_Mi_Ubicacion());
				    $connCPharma = FG_Conectar_CPharma();

				    $sql = SQG_Detalle_Articulo($inventarioDetalle->id_articulo);
				    $result = sqlsrv_query($conn,$sql);
				    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

				    $Existencia = $row["Existencia"];
				    $ExistenciaAlmacen1 = $row["ExistenciaAlmacen1"];
				    $ExistenciaAlmacen2 = $row["ExistenciaAlmacen2"];
				    $IsTroquelado = $row["Troquelado"];
				    $IsIVA = $row["Impuesto"];
				    $UtilidadArticulo = $row["UtilidadArticulo"];
				    $UtilidadCategoria = $row["UtilidadCategoria"];
				    $TroquelAlmacen1 = $row["TroquelAlmacen1"];
				    $PrecioCompraBrutoAlmacen1 = $row["PrecioCompraBrutoAlmacen1"];
				    $TroquelAlmacen2 = $row["TroquelAlmacen2"];
				    $PrecioCompraBrutoAlmacen2 = $row["PrecioCompraBrutoAlmacen2"];
				    $PrecioCompraBruto = $row["PrecioCompraBruto"];
				    $Dolarizado = $row["Dolarizado"];
				    $CondicionExistencia = 'CON_EXISTENCIA';
				    $UltimaVenta = $row["UltimaVenta"];
				    $UltimoLote = $row["UltimoLote"];

				    $UltimaVenta = $UltimaVenta->format('d-m-Y');
				    $UltimoLote = $UltimoLote->format('d-m-Y');

				    $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

			    $ResultCPharma = mysqli_query($connCPharma,"SELECT fecha_conteo FROM inventario_detalles WHERE id_articulo = '$inventarioDetalle->id_articulo' ORDER BY fecha_conteo DESC LIMIT 1");
			    $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
			    $ultimoConteo = $RowCPharma['fecha_conteo'];			    
					?>

			    <tr>
		      	<th scope="row" colspan="1">{{$inventarioDetalle->id}}</th>
	      		<td scope="row" colspan="1">{{$inventarioDetalle->codigo_conteo}}</td>
	      		<td scope="row" colspan="1">{{$inventarioDetalle->codigo_articulo}}</td>
	      		<td scope="row" colspan="1">{{$inventarioDetalle->codigo_barra}}</td>
	      		<td scope="row" colspan="1">{{$inventarioDetalle->descripcion}}</td>
	      		<td scope="row" colspan="1">{{number_format($Precio,2,"," ,"." )}}</td>
	      		<td scope="row" colspan="1">{{$UltimoLote}}</td>
	      		<td scope="row" colspan="1">{{$UltimaVenta}}</td>
	      		<td scope="row" colspan="1">{{$ultimoConteo}}</td>
	      		<td scope="row" colspan="1">{{$inventarioDetalle->existencia_actual}}</td>
	      		<td scope="row" colspan="1">{{$inventarioDetalle->conteo}}</td>
						<td scope="row" colspan="1">{{$inventarioDetalle->operador_conteo}}</td>
						<td scope="row" colspan="1">{{$inventarioDetalle->fecha_conteo}}</td>
	      		<td scope="row" colspan="1">{{$inventarioDetalle->re_conteo}}</td>
	      		<td scope="row" colspan="1">{{$inventarioDetalle->operador_reconteo}}</td>
	      		<td scope="row" colspan="1">{{$inventarioDetalle->fecha_reconteo}}</td>
			    </tr>
				@endforeach

	  	</tbody>
	</table>

@endsection