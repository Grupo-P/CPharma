@extends('layouts.model')

@section('title')
    Departamento
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle del departamento
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/departamento/" method="POST" style="display: inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row">{{$departamentos->nombre}}</th>
	      		<th scope="row"></th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">Descripcion</th>
	    	  	<td>{{$departamentos->descripcion}}</td>
		    </tr>		 
		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$departamentos->estatus}}</td>
		    </tr>		
		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$departamentos->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$departamentos->updated_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Actualizado por</th>
	    	  	<td>{{$departamentos->user}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection