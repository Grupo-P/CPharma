@extends('layouts.model')

@section('title')
    Track Imagen
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de Track Imagen
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/trackimagen/" method="POST" style="display: inline;">
	    @csrf
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row" colspan="2">Track Imagen: {{$trackimagen->id}}</th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">Codigo Barra</th>
	    	  	<td>{{$trackimagen->codigo_barra}}</td>
		    </tr>
            <tr>
		      	<th scope="row">URL App</th>
	    	  	<td>{{$trackimagen->url_app}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$trackimagen->estatus}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$trackimagen->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$trackimagen->updated_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Actualizado por</th>
	    	  	<td>{{$trackimagen->user}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection
