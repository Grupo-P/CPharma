@extends('layouts.model')

@section('title')
    Dashboard
@endsection

@section('content')

	<?php
		$usuarios = DB::table('users')->count();
		$empresas = DB::table('empresas')->count();
		$proveedores = DB::table('proveedors')->count();
	?>

	<h1 class="h5 text-info">
		<i class="fas fa-chart-pie"></i>
		Dashboard
	</h1>

	<hr class="row align-items-start col-12"> 
	<div class="card-deck">
		
		<?php
		  if(Auth::user()->role == 'MASTER'){
		 ?>
		<!-- Usuario -->
		<div class="card border-danger mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-danger">
	    		<h2 class="card-title">
		    		<span class="card-text text-white">
		    			<?php
							echo ''.$usuarios;
						?>
		    		</span>
	    		</h2>
	    		<p class="card-text text-white">Usuarios registrados</p>
	  		</div>
			  	<div class="card-footer bg-transparent border-danger text-right">
			  		<a href="/usuario/" class="btn btn-outline-danger btn-sm">Visualizar</a>
			  	</div>
		</div>
		<?php
			}
		?>
		<!-- Empresas -->
	 	<div class="card border-success mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-success">
	    		<h2 class="card-title">
		    		<span class="card-text text-white">
		    			<?php
							echo ''.$empresas;
						?>
		    		</span>
	    		</h2>
	    		<p class="card-text text-white">Empresas registradas</p>
	  		</div>
		  	<div class="card-footer bg-transparent border-success text-right">
		  		<a href="/empresa/" class="btn btn-outline-success btn-sm">Visualizar</a>
		  	</div>
		</div>
	
		<!-- Provedores -->
		<div class="card border-info mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-info">
	    		<h2 class="card-title">
		    		<span class="card-text text-white">
		    			<?php
							echo ''.$proveedores;
						?>
		    		</span>
	    		</h2>
	    		<p class="card-text text-white">Proveedores registrados</p>
	  		</div>
		  	<div class="card-footer bg-transparent border-info text-right">
		  		<a href="/proveedor/" class="btn btn-outline-info btn-sm">Visualizar</a>
		  	</div>
		</div>
	</div>

	<div class="card-deck">
	{{-- NEWS --}}
		<div class="card border-warning  mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-warning">
	    		<h2 class="card-title">
		    		<span class="card-text text-black">
		    			Novedades
		    		</span>
	    		</h2>
	    		<p class="card-text text-black">
	    			<ul>
	    				<li class="card-text text-black ">
	    					Se actualizo el reporte <b>pedido de productos</b>, ya puedes hacer tus busquedas de manera mas sencilla
	    				</li>
	    			</ul>
	    		</p>
	  		</div>  
		</div>
	{{-- CONTACTO --}}
		<div class="card border-success mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-success">
	    		<h2 class="card-title">
		    		<span class="card-text text-white">
		    			Tienes una idea.?
		    		</span>
	    		</h2>
	    		<p class="card-text text-white">
	    			
	    		</p>
	  		</div>
		</div>
	</div>
@endsection