@extends('layouts.model')

@section('title')
    Empresa
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
		        <h4 class="h6">Empresa guardada con exito</h4>
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
		        <h4 class="h6">Empresa modificada con exito</h4>
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
		        <h4 class="h6">Empresa actualizada con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>	
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<h1 class="h5 text-info">
		<i class="fas fa-list"></i>
		Lista de empresas
	</h1>
	<hr class="row align-items-start col-12">
	
	<div>
		<a href="{{ url('/empresa/create') }}" role="button" class="btn btn-outline-success btn-sm" 
		style="display: inline; text-align: left;">
      		<i class="fas fa-plus"></i>	
      		Agregar		      		
      	</a>
	</div>
	<br>

	<table class="table table-striped col-12">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col">#</th>
			      <th scope="col">Nombre</th>
			      <th scope="col">RIF</th>
			      <th scope="col">Telefono</th>
			      <th scope="col">Direccion</th>
			      <th scope="col">Estatus</th>
			      <th scope="col">Acciones</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($empresas as $empresa)
		    <tr>
		      <th scope="row">{{$empresa->id}}</th>
		      <td>{{$empresa->nombre}}</td>
		      <td>{{$empresa->rif}}</td>
		      <td>{{$empresa->telefono}}</td>
		      <td>{{$empresa->direccion}}</td>
		      <td>{{$empresa->estatus}}</td>
		      <td>
				
				<?php
					if($empresa->estatus == 'ACTIVO'){
				?>
					<a href="/empresa/{{$empresa->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
			      		<i class="far fa-eye"></i>			      		
			      	</a>

		      		<a href="/empresa/{{$empresa->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
			      		<i class="fas fa-edit"></i>			      		
			      	</a>
			 					  
			      	<form action="/empresa/{{$empresa->id}}" method="POST" style="display: inline;">
					    @method('DELETE')
					    @csrf					    
					    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Desincorporar"><i class="fa fa-reply"></i></button>
					</form>	
				<?php
					}
					else if($empresa->estatus == 'INACTIVO'){
				?>		
			      	<form action="/empresa/{{$empresa->id}}" method="POST" style="display: inline;">
					    @method('DELETE')
					    @csrf					    
					    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Reincorporar"><i class="fa fa-share"></i></button>
					</form>
				<?php
					}					
				?>
		      </td>
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