@php
    if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' || $_SERVER['SERVER_NAME'] == 'cpharmagp.com') {
        $layout = 'layouts.contabilidad';
    } else {
        $layout = 'layouts.model';
    }
@endphp

@extends($layout)

@section('title')
    Configuracion
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
		        <h4 class="h6">Configuracion almacenada con exito</h4>
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
		        <h4 class="h6">Configuracion modificada con exito</h4>
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
		        <h4 class="h6">Configuracion actualizada con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<h1 class="h5 text-info">
		<i class="fas fa-cogs"></i>
		Configuracion
	</h1>

	<hr class="row align-items-start col-12">
	<table style="width:100%;" class="CP-stickyBar">
	    <tr>
	        <td style="width:10%;" align="center">
				<a href="{{ url('/configuracion/create') }}" role="button" class="btn btn-outline-info btn-sm"
				style="display: inline; text-align: left;">
				<i class="fas fa-plus"></i>
					Agregar
				</a>
	        </td>
	        <td style="width:90%;">
	        	<div class="input-group md-form form-sm form-1 pl-0">
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
		      	<th scope="col" class="CP-sticky">Variable</th>
		      	<th scope="col" class="CP-sticky">Descripcion</th>
		      	<th scope="col" class="CP-sticky">Valor</th>
		      	<th scope="col" class="CP-sticky">Estatus</th>
		      	<th scope="col" class="CP-sticky">Acciones</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($configuraciones as $configuracion)
		    <tr>
		      <th>{{$configuracion->id}}</th>
		      <td>{{$configuracion->variable}}</td>
		      <td>{{$configuracion->descripcion}}</td>
		      <td>{{$configuracion->valor}}</td>
		      <td>{{$configuracion->estatus}}</td>

		    <!-- Inicio Validacion de ROLES -->
		      <td style="width:140px;">

				<?php
				if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER'){
				?>

					<?php
					if($configuracion->estatus == 'ACTIVO'){
					?>
						<a href="/configuracion/{{$configuracion->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
			      			<i class="far fa-eye"></i>
			      		</a>

			      		<a href="/configuracion/{{$configuracion->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
			      			<i class="fas fa-edit"></i>
				      	</a>

				      	<form action="/configuracion/{{$configuracion->id}}" method="POST" style="display: inline;">
						    @method('DELETE')
						    @csrf
						    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Desincorporar"><i class="fa fa-reply"></i></button>
						</form>
					<?php
					}
					else if($configuracion->estatus == 'INACTIVO'){
					?>
			      	<form action="/configuracion/{{$configuracion->id}}" method="POST" style="display: inline;">
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
					<a href="/configuracion/{{$configuracion->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
		      			<i class="far fa-eye"></i>
		      		</a>

		      		<a href="/configuracion/{{$configuracion->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
		      			<i class="fas fa-edit"></i>
	      			</a>
				<?php
				} else if(Auth::user()->role == 'USUARIO'){
				?>
					<a href="/configuracion/{{$configuracion->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
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
