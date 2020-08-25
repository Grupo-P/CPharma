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

	<br><br>

	<table>
		<thead>
				<tr>
					<th scope="row" colspan="4" width="20%">
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

	<br>

	<table>
		<thead>
				<tr>
					<th scope="row" colspan="4" width="20%">
	    			<span class="navbar-brand text-info CP-title-NavBar">
	    				<b><i class="fas fa-syringe text-success"></i>CPharma</b>
						</span>
	    		</th>
					<th colspan="16">DETALLES DEL INVENTARIO</th>
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
      		<th scope="col" colspan="1">Diferencia Conteo</th> 
      		<th scope="row" colspan="1">Operador Conteo</th> 
      		<th scope="row" colspan="1">Fecha Conteo</th> 
      		<th scope="row" colspan="1">Reconteo</th>
      		<th scope="col" colspan="1">Diferencia Reconteo</th>
      		<th scope="row" colspan="1">Operador Reconteo</th> 
      		<th scope="row" colspan="1">Fecha Reconteo</th> 
      		<th scope="row" colspan="1">Diferencia<br>(Generado-Conteo)<br>dias</th> 
      		<th scope="row" colspan="1">Diferencia<br>(Generado-Reconteo)<br>dias</th> 
		    </tr>
	  	</thead>

			<?php
				$inventarioDetalles = 
          InventarioDetalle::orderBy('id','asc')
          ->where('codigo_conteo',$inventario->codigo)
          ->get();

          $totalExistencia = $totalConteo = $totalDifConteo = $totalReConteo = $totalDifReConteo =0;
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

				    ($UltimaVenta) ? $UltimaVenta = $UltimaVenta->format('d-m-Y') : $UltimaVenta = "-";
				    ($UltimoLote) ? $UltimoLote = $UltimoLote->format('d-m-Y') : $UltimoLote = "-";

				    $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
    $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

				   $sqlUltimoConteo = "
				   	SELECT fecha_conteo FROM inventario_detalles WHERE id_articulo = '$inventarioDetalle->id_articulo' 
						AND fecha_conteo <> (SELECT fecha_conteo FROM inventario_detalles WHERE id_articulo = '$inventarioDetalle->id_articulo' ORDER BY fecha_conteo DESC LIMIT 1)
						ORDER BY fecha_conteo DESC LIMIT 1;
				   ";

			    $ResultCPharma = mysqli_query($connCPharma,$sqlUltimoConteo);
			    $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
			    $ultimoConteo = $RowCPharma['fecha_conteo'];	

			    $generado_conteo = FG_Rango_Dias($inventario->fecha_generado,$inventarioDetalle->fecha_conteo);

			    $generado_reconteo = FG_Rango_Dias($inventario->fecha_generado,$inventarioDetalle->fecha_reconteo);

			    $totalExistencia += $inventarioDetalle->existencia_actual;
			    $totalConteo += $inventarioDetalle->conteo;

			    if($inventarioDetalle->conteo!=""){
			    	$totalDifConteo += $inventarioDetalle->conteo - $inventarioDetalle->existencia_actual;
			    }

			    $totalReConteo += $inventarioDetalle->re_conteo;

			    if($inventarioDetalle->re_conteo!=""){
			    	$totalDifReConteo += $inventarioDetalle->re_conteo - $inventarioDetalle->existencia_actual;
			    }			    

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
						
						@if($inventarioDetalle->conteo!="")
							<td scope="row" colspan="1">{{$inventarioDetalle->conteo - $inventarioDetalle->existencia_actual}}</td>
		      	@else
							<td scope="row" colspan="1"></td>
		      	@endif

						<td scope="row" colspan="1">{{$inventarioDetalle->operador_conteo}}</td>
						<td scope="row" colspan="1">{{$inventarioDetalle->fecha_conteo}}</td>
	      		<td scope="row" colspan="1">{{$inventarioDetalle->re_conteo}}</td>

	      		@if($inventarioDetalle->re_conteo!="")
							<td scope="row" colspan="1">{{$inventarioDetalle->re_conteo - $inventarioDetalle->existencia_actual}}</td>
		      	@else
							<td scope="row" colspan="1"></td>
		      	@endif

	      		<td scope="row" colspan="1">{{$inventarioDetalle->operador_reconteo}}</td>
	      		<td scope="row" colspan="1">{{$inventarioDetalle->fecha_reconteo}}</td>	      		
	      		<td scope="row" colspan="1">{{$generado_conteo}}</td>
	      		<td scope="row" colspan="1">{{$generado_reconteo}}</td>
			    </tr>
				@endforeach

	  	<tr>
		 <th colspan="9" class="text-right">Totales</th>
		 <th><?php echo($totalExistencia) ?></th>
		 <th><?php echo($totalConteo) ?></th>
		 <th><?php echo($totalDifConteo) ?></th>
		 <th colspan="2"></th>
		 <th><?php echo($totalReConteo) ?></th>
		 <th><?php echo($totalDifReConteo) ?></th>
		 <th colspan="4"></th>
		</tr>
		</tbody>
	</table>

	<br>

		<table class="col-md-6">
			<thead>
				<tr>
					<th scope="row" colspan="1">
		    			<span class="navbar-brand text-info CP-title-NavBar">
		    				<b><i class="fas fa-syringe text-success"></i>CPharma</b>
							</span>
		    		</th>
					<th scope="row" colspan="1" width="70%">ALMACEN (CONTEO)</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>Fecha</th>
					<td></td>
				</tr>
				<tr>
					<th>Hora de inicio</th>
					<td></td>
				</tr>
				<tr>
					<th>Hora de finalizacion</th>
					<td></td>
				</tr>
				<tr>
					<th>Operador</th>
					<td></td>
				</tr>
			</tbody>
		</table>
	
	<br>		

		<table class="col-md-6">
			<thead>
				<tr>
					<th scope="row" colspan="1">
		    			<span class="navbar-brand text-info CP-title-NavBar">
		    				<b><i class="fas fa-syringe text-success"></i>CPharma</b>
							</span>
		    		</th>
					<th scope="row" colspan="1" width="70%">ALMACEN (RECONTEO)</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>Fecha</th>
					<td></td>
				</tr>
				<tr>
					<th>Hora de inicio</th>
					<td></td>
				</tr>
				<tr>
					<th>Hora de finalizacion</th>
					<td></td>
				</tr>
				<tr>
					<th>Operador</th>
					<td></td>
				</tr>
			</tbody>
		</table>

	<br>

		<table class="col-md-6">
			<thead>
				<tr>
					<th scope="row" colspan="1">
		    			<span class="navbar-brand text-info CP-title-NavBar">
		    				<b><i class="fas fa-syringe text-success"></i>CPharma</b>
							</span>
		    		</th>
					<th scope="row" colspan="1" width="70%">OPERACIONES</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>Fecha</th>
					<td></td>
				</tr>
				<tr>
					<th>Hora</th>
					<td></td>
				</tr>
				<tr>
					<th>Operador</th>
					<td></td>
				</tr>
				<tr>
					<th>Comentarios</th>
					<td>
						<br>
						<br>
						<br>
					</td>
				</tr>
			</tbody>
		</table>
	
	</div></div>

	<p>
		<ul>
			<li><strong>Todas las anotaciones deben hacerce en boligrafo. Esta prohibido el uso del lapiz y las enmiendas o tachaduras</strong></li>
			<li>Los articulos que no se encuentren por favor dejarlos en blanco, colocar un cero en señal de que el fisico no existe</li>
			<li>Usar los codigos de barra como guia en los articulos que sean parecidos para evitar confusiones de conteo entre articulos parecidos</li>
			<li>Adjuntar con este formato el soporte de ajuste y guardar todo en la carpeta respectiva</li>
			<li>Los articulos deben bucarse en todas las ubicaciones posibles: recepcion, piso de venta, gaveta, almacen, alta rotacion, vencidos, etc.</li>
			<li>Es valido anotar la sumatoria a medida que vayan encontrando, ejemplo: en vez de anotar 12 unidades, se puede colocar 8 + 4 segun como se iba encontrando</li>
			<li>Los conteos deben tener prioridad para evitar defases entre los productos contados y la venta que se esta dando en simultaneo</li>
			<li>El re-conteo debe hacerlo una persona distinta al que realizo en conteo original</li>
			<li>El re-conteo lo pedira el departamento de operaciones despues de analizar el primer conteo y no necesariamente sera para todas las referencias</li>
			<li>Todas las anotaciones de numeros en el re-conteo deben ser las cantidades contadas y no los diferenciales. Ejemplos: 12 (Correcto), -2 (Incorrecto)</li>
		</ul>
	</p>

@endsection