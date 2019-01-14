<!-- Correccion Orotografica: Completa 12/12/2018 -->
@extends('layouts.model')

@section('title')
    Usuario
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de usuario
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/usuario/" method="POST" style="display: inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row">{{$usuario->name}}</th>
	      		<th scope="row"></th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">Correo</th>
	    	  	<td>{{$usuario->email}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Rol</th>
	    	  	<td>{{$usuario->role}}</td>
		    </tr>		
		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$usuario->estatus}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Creado</th>
	    	  	<td>{{$usuario->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$usuario->updated_at}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection