@extends('layouts.model')

@section('title')
    Etiqueta
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de etiqueta
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/etiqueta/" method="POST" style="display: inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row">{{$etiqueta->descripcion}}</th>
	      		<th scope="row"></th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">Codigo Interno</th>
	    	  	<td>{{$etiqueta->codigo_articulo}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Condicion</th>
	    	  	<td>{{$etiqueta->condicion}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Clasificacion</th>
	    	  	<td>{{$etiqueta->clasificacion}}</td>
		    </tr>		    
		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$etiqueta->estatus}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$etiqueta->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$etiqueta->updated_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Actualizado por</th>
	    	  	<td>{{$etiqueta->user}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection