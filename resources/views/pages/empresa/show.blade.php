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
	</div>

@endsection