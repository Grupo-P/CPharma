@extends('layouts.model')

@section('title')
    Rol
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle del rol
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/rol/" method="POST" style="display: inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row">{{$rol->nombre}}</th>
	      		<th scope="row"></th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">Descripcion</th>
	    	  	<td>{{$rol->descripcion}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">¿Lee Informacion?</th>
	    	  	<td>{{$rol->read}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">¿Agrega Informacion?</th>
	    	  	<td>{{$rol->create}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">¿Modifica Informacion?</th>
	    	  	<td>{{$rol->update}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">¿Elimina Informacion? </th>
	    	  	<td>{{$rol->delete}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$rol->estatus}}</td>
		    </tr>		
		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$rol->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$rol->updated_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Actualizado por</th>
	    	  	<td>{{$rol->user}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection