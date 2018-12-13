<!-- Correccion Orotografica: Completa 12/12/2018 -->
@extends('layouts.model')

@section('title')
    Dashboard
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-chart-pie"></i>
		Dashboard
	</h1>

	<hr class="row align-items-start col-12"> 

	 <div class="card border-success mb-3" style="width: 14rem;">	  	
	  	<div class="card-body text-left bg-success">
	    	<h2 class="card-title">
	    		<span class="card-text text-white">26</span>
	    	</h2>
	    	<p class="card-text text-white">Empresas registradas!</p>
	  	</div>
	  	<div class="card-footer bg-transparent border-success text-right">
	  		<a href="/empresa/" class="btn btn-outline-success btn-sm">Visualizar</a>
	  	</div>
	</div>

	</div>
   
@endsection