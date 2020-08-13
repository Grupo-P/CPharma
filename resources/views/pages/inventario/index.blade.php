@extends('layouts.model')

@section('title')
    Inventario
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
		        <h4 class="h6">Inventario almacenada con exito</h4>
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
		        <h4 class="h6">Inventario modificada con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	@if (session('Contar'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Contar') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Debe cargar la informacion del conteo</h4>
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
		        <h4 class="h6">Inventario actualizada con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>	
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<h1 class="h5 text-info">
		<i class="fas fa-boxes"></i>
		Inventario
	</h1>

	<hr class="row align-items-start col-12">
	
	<table class="table table-striped table-borderless col-12 sortable">
  	<thead class="thead-dark">
	    <tr>
	      	<th scope="col" colspan="4" style="text-align: center;">CLASIFICACION</th>
	    </tr>
		</thead>
		<tbody>
	  	<tr>

  		<td style="width:11%;" align="center"> 		
				<form action="/inventario" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-dark btn-sm" value="3">TODOS</button>
				</form>
	    </td>

	  	<td style="width:11%;" align="center">	  		
				<form action="/inventario" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-info btn-sm" value="0">GENERADO</button>
				</form>
	    </td>

	  	<td style="width:11%;" align="center">
				<form action="/inventario" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-success btn-sm" value="1">REVISADO</button>
				</form>
	    </td>

	    <td style="width:11%;" align="center">    	
				<form action="/inventario" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-danger btn-sm" value="2">ANULADO</button>
					</form>	
			</td>

	    </tr>
		</tbody>
	</table>
	<br>

	<table style="width:100%;" class="CP-stickyBar">
	    <tr>
        <td style="width:8%;" align="center">	
					<a href="{{ url('/inventarioProveedor') }}" role="button" class="btn btn-outline-success btn-sm" 
					style="display: inline; text-align: left;">
					<i class="fa fa-plus"></i>
						Por Proveedor
					</a>
        </td>
        <td style="width:8%;" align="center">	
					<a href="{{ url('/inventarioDescripcion') }}" role="button" class="btn btn-outline-info btn-sm" 
					style="display: inline; text-align: left;">
					<i class="fa fa-plus"></i>
						Por Descripcion
					</a>
        </td>
        <td style="width:6%;" align="center">
					<a href="{{ url('/inventarioAleatorio') }}" role="button" class="btn btn-outline-warning btn-sm" 
					style="display: inline; text-align: left;">
					<i class="fa fa-plus"></i>
						Aleatorio
					</a>
        </td>
	        <td style="width:70%;">
	        	<div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
				  <div class="input-group-prepend">
				    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
				        aria-hidden="true"></i></span>
				  </div>
				  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
				</div>
	        </td>
	    </tr>
	</table>
	<br/>
	
	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col" class="CP-sticky">#</th>
		      	<th scope="col" class="CP-sticky">Codigo</th>
		      	<th scope="col" class="CP-sticky">Fecha</th>
		      	<th scope="col" class="CP-sticky">Hora</th>
		      	<th scope="col" class="CP-sticky">Origen</th>
		      	<th scope="col" class="CP-sticky">Motivo</th>
		      	<th scope="col" class="CP-sticky">Cantidad SKU</th>
		      	<th scope="col" class="CP-sticky">Unidades</th>
		      	<th scope="col" class="CP-sticky">Operador</th>	
		      	<th scope="col" class="CP-sticky">Estatus</th>
		      	<th scope="col" class="CP-sticky">Acciones</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($inventarios as $inventario)
				<?php
					$fecha = new DateTime($inventario->fecha_generado);
					$fecha = $fecha->format('d-m-Y');

					$hora = new DateTime($inventario->fecha_generado);
					$hora = $hora->format('h:i A');
				?>
		    <tr>
		      <th>{{$inventario->id}}</th>
		      <td>{{$inventario->codigo}}</td>
		      <td>{{$fecha}}</td>
		      <td>{{$hora}}</td>
		      <td>{{$inventario->origen_conteo}}</td>
		      <td>{{$inventario->motivo_conteo}}</td>
		      <td>{{$inventario->cantidades_conteo}}</td>
		      <td>{{$inventario->unidades_conteo}}</td>
		      <td>{{$inventario->operador_generado}}</td>		
		      <td>{{$inventario->estatus}}</td>
		      
		    <!-- Inicio Validacion de ROLES -->
		      <td style="width:200px;">
				
				<?php
				if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER'){
				?>

					<?php
					if($inventario->estatus == 'GENERADO'){
					?>
						<a href="/inventario/{{$inventario->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
			      			<i class="far fa-eye"></i>			      		
			      		</a>

			      		<a href="/inventario/{{$inventario->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
			      			<i class="fas fa-edit"></i>		      		
				      	</a>

			      	<form action="/inventario/{{$inventario->id}}" method="POST" style="display: inline;">
						    @method('DELETE')
						    @csrf
						    <input type="hidden" name="action" value="Revisar">			    
						    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Aprobar"><i class="fa fa-check"></i></button>
				 			</form>

			      	<form action="/inventario/{{$inventario->id}}" method="POST" style="display: inline;">
						    @method('DELETE')
						    @csrf
						    <input type="hidden" name="action" value="Anular">				    
						    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Anular"><i class="fa fa-ban"></i></button>
							</form>
					<?php
					}
					else if($inventario->estatus == 'ANULADO' || $inventario->estatus == 'REVISADO'){ 
					?>		
			      	<a href="/inventario/{{$inventario->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
			      			<i class="far fa-eye"></i>			      		
		      		</a>			      		
					</form>
					<?php
					}					
					?>

				<?php	
				} else if(Auth::user()->role == 'SUPERVISOR' || Auth::user()->role == 'ADMINISTRADOR' || Auth::user()->role == 'SUPERVISOR CAJA'){ 
				?>
					
					<?php
					if($inventario->estatus == 'GENERADO'){
					?>
						<a href="/inventario/{{$inventario->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
			      			<i class="far fa-eye"></i>			      		
			      		</a>

			      		<a href="/inventario/{{$inventario->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
			      			<i class="fas fa-edit"></i>		      		
				      	</a>

			      	<form action="/inventario/{{$inventario->id}}" method="POST" style="display: inline;">
						    @method('DELETE')
						    @csrf
						    <input type="hidden" name="action" value="Revisar">			    
						    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Aprobar"><i class="fa fa-check"></i></button>
				 			</form>

			      	<form action="/inventario/{{$inventario->id}}" method="POST" style="display: inline;">
						    @method('DELETE')
						    @csrf
						    <input type="hidden" name="action" value="Anular">				    
						    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Anular"><i class="fa fa-ban"></i></button>
							</form>
					<?php
					}
					else if($inventario->estatus == 'ANULADO' || $inventario->estatus == 'REVISADO'){
					?>		
			      	<a href="/inventario/{{$inventario->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
			      			<i class="far fa-eye"></i>			      		
		      		</a>			      		
					</form>
					<?php
					}					
					?>

				<?php
				} else if(Auth::user()->role == 'USUARIO'){
				?>
					<a href="/inventario/{{$inventario->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
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