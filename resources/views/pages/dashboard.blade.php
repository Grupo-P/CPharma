@extends('layouts.model')

@section('title')
    Dashboard
@endsection

@section('content')

	<?php
		$usuarios = DB::table('users')->count();
		$empresas = DB::table('empresas')->count();
		$proveedores = DB::table('proveedors')->count();
		$dolar = DB::table('dolars')->count();
	?>

	<h1 class="h5 text-info">
		<i class="fas fa-chart-pie"></i>
		Dashboard
	</h1>

	<hr class="row align-items-start col-12"> 
	
	<div class="card-deck">
		<!-- Empresas -->
	 	<div class="card border-danger mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-danger">
	    		<h2 class="card-title">
		    		<span class="card-text text-white">
		    			<i class="fas fa-industry"></i>
		    			<?php
							echo ''.$empresas;
						?>						
		    		</span>
	    		</h2>
	    		<p class="card-text text-white">Empresas registradas</p>
	  		</div>
		  	<div class="card-footer bg-transparent border-danger text-right">
		  		<a href="/empresa/" class="btn btn-outline-danger btn-sm">Visualizar</a>
		  	</div>
		</div>
		<!-- Provedores -->
		<div class="card border-success mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-success">
	    		<h2 class="card-title">
		    		<span class="card-text text-white">
		    			<i class="fas fa-dolly"></i>
		    			<?php
							echo ''.$proveedores;
						?>						
		    		</span>
	    		</h2>
	    		<p class="card-text text-white">Proveedores registrados</p>
	  		</div>
		  	<div class="card-footer bg-transparent border-success text-right">
		  		<a href="/proveedor/" class="btn btn-outline-success btn-sm">Visualizar</a>
		  	</div>
		</div>
		<!-- Reportes -->
		<div class="card border-info mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-info">
	    		<h2 class="card-title">
		    		<span class="card-text text-white">
		    			<i class="fas fa-file-invoice"></i>
		    			06		    			
		    		</span>
	    		</h2>
	    		<p class="card-text text-white">Reportes disponibles</p>
	  		</div>
		  	<div class="card-footer bg-transparent border-info text-right">
		  		<a href="/sede/" class="btn btn-outline-info btn-sm">Visualizar</a>
		  	</div>
		</div>
	</div>
	
	<div class="card-deck">		
		<?php
		  if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER'){
		 ?>
		<!-- Usuario -->
		<div class="card border-dark mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-dark">
	    		<h2 class="card-title">
		    		<span class="card-text text-white">	
		    		<i class="fas fa-user"></i>	    			
		    			<?php
							echo ''.$usuarios;
						?>						
		    		</span>
	    		</h2>
	    		<p class="card-text text-white">Usuarios registrados</p>
	  		</div>
		  	<div class="card-footer bg-transparent border-dark text-right">
		  		<a href="/usuario/" class="btn btn-outline-dark btn-sm">Visualizar</a>
		  	</div>
		</div>
		<!-- Dolar -->
		<div class="card border-secondary mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-secondary">
	    		<h2 class="card-title">
		    		<span class="card-text text-white">
		    			<i class="fas fa-money-bill-alt"></i>
		    			<?php
							echo ''.$dolar;
						?>						
		    		</span>
	    		</h2>
	    		<p class="card-text text-white">Tasas registradas</p>
	  		</div>
		  	<div class="card-footer bg-transparent border-secondary text-right">
		  		<a href="/dolar/" class="btn btn-outline-secondary btn-sm">Visualizar</a>
		  	</div>
		</div>
		<?php
			}
		?>
	</div>

	<div class="card-deck">
		{{-- NEWS --}}
		<div class="card border-warning  mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-warning">
	    		<h2 class="card-title">
		    		<span class="card-text text-white">
		    			<i class="fas fa-bell"></i>
		    			Actualizaciones
		    		</span>
	    		</h2>
	    		<p class="card-text text-white">
	    			<ul>
	    				<li class="card-text text-white ">
	    					Ya puedes ver los reportes para <b>Farmacia Tierra Negra</b> y <b>Farmacia La Lago</b>
	    				</li>
	    			</ul>
	    		</p>
	  		</div>  
		</div>
		{{-- CONTACTO --}}
		<div class="card border-primary  mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-primary">
	    		<h2 class="card-title">	    			
		    		<span class="card-text text-white">
		    			<i class="fas fa-info"></i>
		    			Tienes una idea.?
		    		</span>
	    		</h2>
	    		<p class="card-text text-white">
	    			<ol>
	    				<li class="card-text text-white ">
	    					Sintetiza tu idea
	    				</li>
	    				<li class="card-text text-white ">
	    					Envia tu idea al correo scova@farmacia72.com.ve
	    				</li>
	    				<li class="card-text text-white ">
	    					Espera nuestro contacto para hablar de tu idea
	    				</li>
	    			</ol>
	    		</p>
	  		</div>  
		</div>
	</div>
@endsection