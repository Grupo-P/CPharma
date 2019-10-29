@extends('layouts.model')

@section('title')
    Orden de compra
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de orden de compra
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/ordenCompra/" method="POST" style="display: inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row" colspan="2">{{$OrdenCompra->nombre}}</th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">Codigo</th>
	    	  	<td>{{$OrdenCompra->codigo}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Operador</th>
	    	  	<td>{{$OrdenCompra->user}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection