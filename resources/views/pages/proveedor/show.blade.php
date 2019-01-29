<!-- Correccion Orotografica: Completa 12/12/2018 -->
@extends('layouts.model')

@section('title')
    Proveedor
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de proveedor
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/proveedor/" method="POST" style="display: inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row">{{$proveedor->nombre}}&nbsp;{{$proveedor->apellido}}</th>
	      		<th scope="row"></th>
		    </tr>
	  	</thead>
	  	<tbody>		
		    <tr>
		      	<th scope="row">Teléfono</th>
	    	  	<td>{{$proveedor->telefono}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Correo</th>
	    	  	<td>{{$proveedor->correo}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Cargo</th>
	    	  	<td>{{$proveedor->cargo}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Empresa</th>
	    	  	<td>{{$proveedor->empresa}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Observaciones</th>
	    	  	<td>{{$proveedor->observacion}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$proveedor->estatus}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Creado</th>
	    	  	<td>{{$proveedor->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$proveedor->updated_at}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection