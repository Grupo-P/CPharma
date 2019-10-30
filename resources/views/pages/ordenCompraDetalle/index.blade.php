@extends('layouts.model')

@section('title')
  Detalle de orden de compra
@endsection

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
				<td style="width:7%;">
					<form action="/ordenCompra" method="PRE">
			        @csrf                       
			        <button type="submit" name="Regresar" role="button" class="btn btn-outline-success btn-sm"data-placement="top" style="display: inline;"><i class="fa fa-reply">&nbsp;Regresar</i></button>
			    </form>
				</td>
        <td style="width:7%;">	        	
					<a href="{{ url('/ordenCompraDetalle/create') }}" role="button" class="btn btn-outline-info btn-sm" 
					style="display: inline;">
					<i class="fas fa-plus"></i>
					Agregar		      		
				</a>
	        </td>
	        <td style="width:86%;">
	        	<div class="input-group md-form form-sm form-1 pl-0">
				  <div class="input-group-prepend">
				    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
				        aria-hidden="true"></i></span>
				  </div>
				  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterFirsTable()">
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
		      	<th scope="col" class="stickyCP">Codigo Interno</th>	
		      	<th scope="col" class="stickyCP">Codigo Barra</th>	
		      	<th scope="col" class="stickyCP">Descripcion</th>		      	
		      	<th scope="col" class="stickyCP">Existecia Actual</th>	
		      	<th scope="col" class="stickyCP">Cantidad FTN</th>	
		      	<th scope="col" class="stickyCP">Cantidad FLL</th>	
		      	<th scope="col" class="stickyCP">Cantidad FAU</th>		
		      	<th scope="col" class="stickyCP">Cantidad MC</th>	
		      	<th scope="col" class="stickyCP">Total Unidades</th>	
		      	<th scope="col" class="stickyCP">Costo Total</th>
		      	<th scope="col" class="stickyCP">Existencia (Origen)</th>		
		      	<th scope="col" class="stickyCP">Dias Restantes (Origen)</th>	
		      	<th scope="col" class="stickyCP">Reporte (Origen)</th>	
		      	<th scope="col" class="stickyCP">Rango (Origen)</th>	
		      	<th scope="col" class="stickyCP">Estatus</th>
		      	<th scope="col" class="stickyCP">Acciones</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($ordenCompraDetalles as $ordenCompraDetalle)
		    <tr>
		      <th>{{$ordenCompraDetalle->id}}</th>
		      <th>{{$ordenCompraDetalle->codigo_orden}}</th>
		      <th>{{$ordenCompraDetalle->codigo_articulo}}</th>
		      <th>{{$ordenCompraDetalle->codigo_barra}}</th>
		      <th>{{$ordenCompraDetalle->descripcion}}</th>
		      <th>{{$ordenCompraDetalle->existencia_actual}}</th>
		      <th>{{$ordenCompraDetalle->sede1}}</th>
		      <th>{{$ordenCompraDetalle->sede2}}</th>
		      <th>{{$ordenCompraDetalle->sede3}}</th>
		      <th>{{$ordenCompraDetalle->sede4}}</th>
		      <th>{{$ordenCompraDetalle->total_unidades}}</th>
		      <th>{{$ordenCompraDetalle->costo_unitario}}</th>
		      <th>{{$ordenCompraDetalle->costo_total}}</th>
		      <th>{{$ordenCompraDetalle->existencia_rpt}}</th>
		      <th>{{$ordenCompraDetalle->dias_restantes_rpt}}</th>
		      <th>{{$ordenCompraDetalle->origen_rpt}}</th>
		      <th>{{$ordenCompraDetalle->rango_rpt}}</th>
		      <th>{{$ordenCompraDetalle->estatus}}</th>
		      
		    <!-- Inicio Validacion de ROLES -->
		      <td style="width:140px;">
				
				<?php
				if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER'){
				?>

					<?php
					if($ordenCompraDetalle->estatus == 'ACTIVO'){
					?>
						<a href="/ordenCompraDetalle/{{$ordenCompraDetalle->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
			      			<i class="far fa-eye"></i>			      		
			      		</a>

			      		<a href="/ordenCompraDetalle/{{$ordenCompraDetalle->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
			      			<i class="fas fa-edit"></i>			      		
				      	</a>
				 					  
				      	<form action="/ordenCompraDetalle/{{$ordenCompraDetalle->id}}" method="POST" style="display: inline;">
						    @method('DELETE')
						    @csrf					    
						    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Desincorporar"><i class="fa fa-reply"></i></button>
						</form>
					<?php
					}
					else if($ordenCompraDetalle->estatus == 'INACTIVO'){
					?>		
			      	<form action="/ordenCompraDetalle/{{$ordenCompraDetalle->id}}" method="POST" style="display: inline;">
					    @method('DELETE')
					    @csrf					    
					    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Reincorporar"><i class="fa fa-share"></i></button>
					</form>
					<?php
					}					
					?>
				<?php	
				} else if(Auth::user()->role == 'SUPERVISOR' || Auth::user()->role == 'ADMINISTRADOR' || Auth::user()->role == 'SUPERVISOR CAJA'){ 
				?>
					<a href="/ordenCompraDetalle/{{$ordenCompraDetalle->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
		      			<i class="far fa-eye"></i>			      		
		      		</a>

		      		<a href="/ordenCompraDetalle/{{$ordenCompraDetalle->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
		      			<i class="fas fa-edit"></i>
	      			</a>
				<?php
				} else if(Auth::user()->role == 'USUARIO'){
				?>
					<a href="/ordenCompraDetalle/{{$ordenCompraDetalle->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
		      			<i class="far fa-eye"></i>			      		
		      		</a>		
				<?php
				}
				?>
										
		      </td>
		    <!-- Fin Validacion de ROLES -->

		    </tr>
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