@extends('layouts.model')

@section('title')
    Traslado
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="fas fa-people-carry"></i>
		Traslado
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/traslado/" method="POST" style="display: inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row">{{$traslado->numero_ajuste}}</th>
	      		<th scope="row"></th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">operador_traslado</th>
	    	  	<td>{{$traslado->operador_traslado}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection