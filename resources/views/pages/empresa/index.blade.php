@extends('layouts.model')

@section('title')
    Empresa
@endsection

@section('content')
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
		      <td>
					<a href="/empresa/{{$empresa->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
			      		<i class="far fa-eye"></i>			      		
			      	</a>

		      		<a href="/empresa/{{$empresa->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
			      		<i class="fas fa-edit"></i>			      		
			      	</a>
			   
		      		<a href="#" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Eliminar">
		      			<i class="fas fa-trash"></i>	      			
		      		</a>
		      </td>
		    </tr>
		@endforeach
		</tbody>
	</table>

	<script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();   
		});
	</script>

@endsection