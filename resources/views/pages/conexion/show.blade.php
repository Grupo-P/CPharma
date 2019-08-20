@extends('layouts.model')

@section('title')
    Conexion
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de la conexion
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/conexion/" method="POST" style="display: inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row"></th>
	      		<th scope="row"></th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">Siglas</th>
	    	  	<td>{{$conexiones->siglas}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Instancia</th>
	    	  	<td>{{$conexiones->instancia}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Base de Datos</th>
	    	  	<td>{{$conexiones->base_datos}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Usuario</th>
	    	  	<td>{{$conexiones->usuario}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Clave</th>
	    	  	<td>{{$conexiones->credencial}}</td>
		    </tr>		    
		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$conexiones->estatus}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$conexiones->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$conexiones->updated_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Actualizado por</th>
	    	  	<td>{{$conexiones->user}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection