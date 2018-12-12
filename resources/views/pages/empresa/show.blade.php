<!-- Correccion Orotografica: Completa 12/12/2018 -->
@extends('layouts.model')

@section('title')
    Empresa
@endsection

@section('content')
	{{-- <h1 class="h5 text-info">
		<i class="far fa-eye"></i>
		Detalle de empresa
	</h1>
	<hr class="row align-items-start col-12">
	<div class="card border-success mb-3" style="max-width: 50rem;">
	  <div class="card-header bg-transparent border-success text-info">{{$empresa->nombre}}</div>
		 <div class="card-body">
		  	<p class="card-text">RIF: {{$empresa->rif}}</p>
		    <p class="card-text">Telefono: {{$empresa->telefono}}</p>
		    <p class="card-text">Direccion: {{$empresa->direccion}}</p>
		    <p class="card-text">Estatus: {{$empresa->estatus}}</p>
		    <p class="card-text">Creada: {{$empresa->created_at}}</p>
		  	<p class="card-text">Ultima Actualizacion: {{$empresa->updated_at}}</p>
		 </div>
	</div> --}}

	<!-- Modal -->
	<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="far fa-eye"></i>&nbsp;Detalle de empresa</h5>
	        {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button> --}}   
	      </div>
	      <div class="modal-body">
	  		<p class="card-text">Nombre: {{$empresa->nombre}}</p>
	    	<p class="card-text">RIF: {{$empresa->rif}}</p>
		    <p class="card-text">Teléfono: {{$empresa->telefono}}</p>
		    <p class="card-text">Dirección: {{$empresa->direccion}}</p>
		    <p class="card-text">Estatus: {{$empresa->estatus}}</p>
		    <p class="card-text">Creada: {{$empresa->created_at}}</p>
		  	<p class="card-text">Ultima Actualización: {{$empresa->updated_at}}</p>
	      </div>
	      <div class="modal-footer">
	      	{!! Form::open(['route' => 'empresa.index', 'method' => 'GET']) !!}
	      		{!! Form::submit('Aceptar', ['class' => 'btn btn-outline-success btn-md']) !!}
			{!! Form::close()!!}		
	      </div>
	    </div>
	  </div>
	</div>

	<script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();   
		});
		$('#exampleModalCenter').modal('show')
	</script>
@endsection