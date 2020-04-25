@extends('layouts.model')

@section('title')
    Unidad Minima
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de unidad Minima
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/unidad/" method="POST" style="display: inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row" colspan="2">{{$unidad->articulo}}</th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">Codigo interno</th>
	    	  	<td>{{$unidad->codigo_interno}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Codigo de barra</th>
	    	  	<td>{{$unidad->codigo_barra}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Divisor</th>
	    	  	<td>{{$unidad->divisor}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Unidad minima</th>
	    	  	<td>{{$unidad->unidad_minima}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$unidad->estatus}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$unidad->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$unidad->updated_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Actualizado por</th>
	    	  	<td>{{$unidad->user}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection