@extends('layouts.model')

@section('title')
    Carta de compromiso
@endsection

@section('content')
 	<h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de los compromisos
	</h1>

	<hr class="row align-items-start col-12">

	<form action="/cartaCompromiso/" method="POST" style="display:inline;">  
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
	      		<th scope="row" colspan="2">Carta de compromiso</th>
		    </tr>
	  	</thead>

	  	<tbody>
	  		<tr>
		      	<th scope="row">Proveedor</th>
	    	  	<td>{{$cartaCompromiso->proveedor}}</td>
		    </tr>

		    <tr>
		      	<th scope="row">Art&iacute;culo</th>
	    	  	<td>{{$cartaCompromiso->articulo}}</td>
		    </tr>

		    <tr>
		      	<th scope="row">Lote</th>
		      	<td>{{$cartaCompromiso->lote}}</td>
		    </tr>

		    <tr>
		      	<th scope="row">Fecha de factura</th>
	    	  	<td>{{date('d-m-Y',strtotime($cartaCompromiso->fecha_documento))}}</td>
		    </tr>

		    <tr>
		      	<th scope="row">Fecha de recepci&oacute;n (Art&iacute;culo)</th>
	    	  	<td>{{date('d-m-Y',strtotime($cartaCompromiso->fecha_recepcion))}}</td>
		    </tr>

		    <tr>
		      	<th scope="row">Fecha de vencimiento (Art&iacute;culo)</th>
	    	  	<td>
	    	  		@if($cartaCompromiso->fecha_vencimiento != '0000-00-00')
			      		{{date('d-m-Y',strtotime($cartaCompromiso->fecha_vencimiento))}}
			      	@endif

			      	@if($cartaCompromiso->fecha_vencimiento == '0000-00-00')
			      		<?php echo '00-00-0000'; ?>
			      	@endif
	    	  	</td>
		    </tr>

		    <tr>
		      	<th scope="row">Fecha tope (carta compromiso)</th>
	    	  	<td>{{date('d-m-Y',strtotime($cartaCompromiso->fecha_tope))}}</td>
		    </tr>

		    <tr>
		      	<th scope="row">Causa</th>
	    	  	<td>{{$cartaCompromiso->causa}}</td>
		    </tr>

		    <tr>
		      	<th scope="row">Nota</th>
	    	  	<td>{{$cartaCompromiso->nota}}</td>
		    </tr>

		    <tr>
		      	<th scope="row">Estatus</th>
	    	  	<td>{{$cartaCompromiso->estatus}}</td>
		    </tr>

		    <tr>
		      	<th scope="row">Usuario</th>
	    	  	<td>{{$cartaCompromiso->user}}</td>
		    </tr>

		    <tr>
		      	<th scope="row">Creada</th>
	    	  	<td>{{$cartaCompromiso->created_at}}</td>
		    </tr>

		    <tr>
		      	<th scope="row">Ultima Actualización</th>
	    	  	<td>{{$cartaCompromiso->updated_at}}</td>
		    </tr>
	  	</tbody>
	</table>
@endsection