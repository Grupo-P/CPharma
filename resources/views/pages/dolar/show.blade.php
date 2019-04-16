@extends('layouts.model')

@section('title')
    Dolar
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de la tasa
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/dolar/" method="POST" style="display: inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row">Dolar</th>
	      		<th scope="row"></th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">Tasa</th>
	    	  	<td>{{$dolar->tasa}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Fecha</th>
	    	  	<td>{{date('Y-m-d',strtotime($dolar->fecha))}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$dolar->estatus}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$dolar->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$dolar->updated_at}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection