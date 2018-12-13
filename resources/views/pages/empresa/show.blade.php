<!-- Correccion Orotografica: Completa 12/12/2018 -->
@extends('layouts.model')

@section('title')
    Empresa
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de empresa
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/empresa/" method="POST" style="display: inline;">  
	    @csrf					    
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row">{{$empresa->nombre}}</th>
	      		<th scope="row"></th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">RIF</th>
	    	  	<td>{{$empresa->rif}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Teléfono</th>
	    	  	<td>{{$empresa->telefono}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Dirección</th>
	    	  	<td>{{$empresa->direccion}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$empresa->estatus}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$empresa->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$empresa->updated_at}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection