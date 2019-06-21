@extends('layouts.model')

@section('title')
    Tasa venta
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de la tasa de venta
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/tasaVenta/" method="POST" style="display:inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
	    	<i class="fa fa-reply">&nbsp;Regresar</i>
	    </button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row">Moneda {{$tasaVenta->moneda}}</th>
	      		<th scope="row"></th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">Tasa</th>
	    	  	<td>{{$tasaVenta->tasa}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Fecha</th>
	    	  	<td>{{date('Y-m-d',strtotime($tasaVenta->fecha))}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$tasaVenta->estatus}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Usuario</th>
	    	  	<td>{{$tasaVenta->user}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$tasaVenta->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$tasaVenta->updated_at}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection