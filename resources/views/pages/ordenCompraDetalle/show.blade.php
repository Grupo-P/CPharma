@extends('layouts.model')

@section('title')
  Detalle de orden de compra
@endsection

<?php
	use Illuminate\Http\Request;
	use compras\OrdenCompra;
?>

@section('content')

	<!-- Modal Guardar -->
	@if (session('Saved'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Saved') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Articulo agregado con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif
	
	<!-- Modal Editar -->
	@if (session('Updated'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Updated') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Articulo modificado con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<!-- Modal Eliminar -->
	@if (session('Deleted'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Deleted') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Articulo actualizado con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>	
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<h1 class="h5 text-info">
		<i class="far fa-file-alt"></i>
		Detalle de orden de compra
	</h1>

	<hr class="row align-items-start col-12">
	<table style="width:100%;">
		<tr>
				<td style="width:10%;">
					<form action="/ordenCompraDetalle/" method="POST">                  
			        <button type="submit" role="button" class="btn btn-outline-success btn-sm"data-placement="top" style="display: inline;"><i class="fa fa-reply">&nbsp;Regresar</i></button>
			    </form>
				</td>
	        <td style="width:90%;">
	        	<div class="input-group md-form form-sm form-1 pl-0">
				  <div class="input-group-prepend">
				    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
				        aria-hidden="true"></i></span>
				  </div>
				  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
				</div>
	        </td>
	    </tr>
	</table>
	<br/>
	
	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col" class="stickyCP">#</th>
		      	<th scope="col" class="stickyCP">Codigo Orden</th>
		      	<th scope="col" class="stickyCP">Estatus Orden</th>
		      	<th scope="col" class="stickyCP">Fecha de la orden</th>
		      	<th scope="col" class="stickyCP">Operador Orden</th>
		      	<th scope="col" class="stickyCP">Proveedor Orden</th>
		      	<th scope="col" class="stickyCP">Codigo Interno</th>	
		      	<th scope="col" class="stickyCP">Codigo Barra</th>	
		      	<th scope="col" class="stickyCP">Descripcion</th>		      		
		      	<th scope="col" class="stickyCP">Cantidad FTN</th>	
		      	<th scope="col" class="stickyCP">Cantidad FLL</th>	
		      	<th scope="col" class="stickyCP">Cantidad FAU</th>		
		      	<th scope="col" class="stickyCP">Cantidad MC</th>	
		      	<th scope="col" class="stickyCP">Total Unidades</th>
		      	<th scope="col" class="stickyCP">Costo Unitario</th>	
		      	<th scope="col" class="stickyCP">Costo Total</th>
		      	<th scope="col" class="stickyCP">Existencia (Origen)</th>		
		      	<th scope="col" class="stickyCP">Dias Restantes (Origen)</th>	
		      	<th scope="col" class="stickyCP">Reporte (Origen)</th>	
		      	<th scope="col" class="stickyCP">Rango (Origen)</th>	
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($ordenCompraDetalles as $ordenCompraDetalle)
	    <tr>
	      <th>{{$ordenCompraDetalle->id}}</th>
	      <td>{{$ordenCompraDetalle->codigo_orden}}</td>
	      <td>{{$ordenCompraDetalle->estatus}}</td>
				
				<?php
					$OrdenCompra = 
				  OrdenCompra::where('codigo',$ordenCompraDetalle->codigo_orden)
				  ->get();
				  $i = 0;

				  echo'<td>'.$OrdenCompra[$i]->created_at.'</td>';
				  echo'<td>'.$OrdenCompra[$i]->user.'</td>';
				  echo'<td>'.$OrdenCompra[$i]->proveedor.'</td>';
				?>
				
	      <td>{{$ordenCompraDetalle->codigo_articulo}}</td>
	      <td>{{$ordenCompraDetalle->codigo_barra}}</td>
	      <td>{{$ordenCompraDetalle->descripcion}}</td>
	      <td>{{$ordenCompraDetalle->sede1}}</td>
	      <td>{{$ordenCompraDetalle->sede2}}</td>
	      <td>{{$ordenCompraDetalle->sede3}}</td>
	      <td>{{$ordenCompraDetalle->sede4}}</td>
	      <td>{{$ordenCompraDetalle->total_unidades}}</td>
	      <td>{{$ordenCompraDetalle->costo_unitario}}</td>
	      <td>{{$ordenCompraDetalle->costo_total}}</td>
	      <td>{{$ordenCompraDetalle->existencia_rpt}}</td>
	      <td>{{$ordenCompraDetalle->dias_restantes_rpt}}</td>
	      <td>{{$ordenCompraDetalle->origen_rpt}}</td>
	      <td>{{$ordenCompraDetalle->rango_rpt}}</td>
	    </tr>
	    <?php $i++; ?>
		@endforeach
		</tbody>
	</table>

	<script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();   
		});
		$('#exampleModalCenter').modal('show')
	</script>

@endsection