<?php
	use Illuminate\Http\Request;
	use compras\Subcategoria;
	use compras\Categoria;
?>

@extends('layouts.model')

@section('title')
    Subcategoria
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de subcategoria
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/subcategoria/" method="POST" style="display: inline;">
	    @csrf
	    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
	</form>

	<br>
	<br>

	<table class="table table-borderless table-striped">
		<thead class="thead-dark">
		    <tr>
	      		<th scope="row" colspan="2">{{$subcategoria->nombre}}</th>
		    </tr>
	  	</thead>
	  	<?php
				$i = 0;
				$categoria = Categoria::where('codigo',$subcategoria->codigo_categoria)->get();
			?>
	  	<tbody>
            <tr>
		      	<th scope="row">Codigo App</th>
	    	  	<td>{{$subcategoria->codigo_app}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Codigo de Subcategoria</th>
	    	  	<td>{{$subcategoria->codigo}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Codigo de Categoria</th>
	    	  	<td>{{$subcategoria->codigo_categoria}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Categoria</th>
	    	  	<td>{{$categoria[0]->nombre}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$subcategoria->estatus}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$subcategoria->created_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$subcategoria->updated_at}}</td>
		    </tr>
		    <tr>
		      	<th scope="row">Actualizado por</th>
	    	  	<td>{{$subcategoria->user}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection
