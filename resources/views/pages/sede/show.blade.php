@extends('layouts.model')

@section('title')
    Sede
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de la sede
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/sede/" method="POST" style="display: inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row">{{$sedes->razon_social}}</th>
	      		<th scope="row"></th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">RIF</th>
	    	  	<td>{{$sedes->rif}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Siglas</th>
	    	  	<td>{{$sedes->siglas}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Dirección</th>
	    	  	<td>{{$sedes->direccion}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$sedes->estatus}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$sedes->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$sedes->updated_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Actualizado por</th>
	    	  	<td>{{$sedes->user}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection