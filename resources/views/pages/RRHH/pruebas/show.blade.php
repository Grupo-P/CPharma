@extends('layouts.model')

@section('title')
    Configuracion
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de la configuracion
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/pruebas/" method="POST" style="display: inline;">  
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
		      	<th scope="row">Tipo de Prueba</th>
	    	  	<td>{{$pruebas->tipo_prueba}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Nombre de prueba</th>
	    	  	<td>{{$pruebas->nombre_prueba}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$pruebas->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$pruebas->updated_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Actualizado por</th>
	    	  	<td>{{$pruebas->user}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection