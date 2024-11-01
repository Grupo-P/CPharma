@php
    if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' || $_SERVER['SERVER_NAME'] == 'cpharma_old.test') {
        $layout = 'layouts.contabilidad';
    } else {
        $layout = 'layouts.model';
    }
@endphp

@extends($layout)

@section('title')
    Configuracion
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de la configuracion
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/configuracion/" method="POST" style="display: inline;">
	    @csrf
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row"></th>
	      		<th scope="row"></th>
		    </tr>
	  	</thead>
	  	<tbody>
		    <tr>
		      	<th scope="row">Variable</th>
	    	  	<td>{{$configuraciones->variable}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Descripcion</th>
	    	  	<td>{{$configuraciones->descripcion}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Valor</th>
	    	  	<td>{{$configuraciones->valor}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$configuraciones->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$configuraciones->updated_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Actualizado por</th>
	    	  	<td>{{$configuraciones->user}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection
