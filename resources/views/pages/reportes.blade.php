<!-- Correccion Orotografica: Completa 12/12/2018 -->
@extends('layouts.model')

@section('title')
    Reportes
@endsection

@section('content')

	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Reportes
	</h1>

	<hr class="row align-items-start col-12"> 
	
   	<div class="card-deck">
   		<!-- Reportes -->
		<div class="card border-danger mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-danger">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Activacion de proveedores
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-danger text-right">
		  		<a href="{{ url('/reporte1') }}" class="btn btn-outline-danger btn-sm">Visualizar</a>
		  	</div>
		</div>

		<div class="card border-success mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-success">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Historico de productos
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-success text-right">
		  		<a href="{{ url('/reporte2') }}" class="btn btn-outline-success btn-sm">Visualizar</a>
		  	</div>
		</div>

		<div class="card border-info mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-info">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Productos mas vendidos
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-info text-right">
		  		<a href="{{ url('/reporte3') }}" class="btn btn-outline-info btn-sm">Visualizar</a>
		  	</div>
		</div>

   	</div>

   	<div class="card-deck">
   		<div class="card border-warning mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-warning">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Productos menos vendidos
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-warning text-right">
		  		<a href="{{ url('/reporte4') }}" class="btn btn-outline-warning btn-sm">Visualizar</a>
		  	</div>
		</div>
		
   		<div class="card border-secondary mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-secondary">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Productos en falla
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-secondary text-right">
		  		<a href="{{ url('/reporte5') }}" class="btn btn-outline-secondary btn-sm">Visualizar</a>
		  	</div>
		</div>

		<div class="card border-dark mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-dark">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Pedido de productos
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-dark text-right">
		  		<a href="{{ url('/reporte6') }}" class="btn btn-outline-dark btn-sm">Visualizar</a>
		  	</div>
		</div>
   	</div>
@endsection