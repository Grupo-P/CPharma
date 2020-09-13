@extends('layouts.model')

@section('title')
    Categoria
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de categoria
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/categoria/" method="POST" style="display: inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row" colspan="2">{{$categoria->nombre}}</th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">Codigo</th>
	    	  	<td>{{$categoria->codigo}}</td>
		    </tr>		   		   
		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$categoria->estatus}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$categoria->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$categoria->updated_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Actualizado por</th>
	    	  	<td>{{$categoria->user}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection