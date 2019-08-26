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

	<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  	<div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info"></i> Novedades</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b></label>
		      	<label>tenemos algunas novedades para ti.!</label>
		        <ul style="list-style:none">
    				<li class="card-text text-dark" style="display: inline;">
    					Ya puedes ir de un reporte a otro de manera mas sencilla,
    					solo debes hacer <b class="text-info">click</b> sobre el nombre de los <b class="text-info">productos</b> o <b class="text-info">proveedores</b>. Los enlaces son los siguientes:
    					<br/><br/>
		
						<i class="far fa-check-circle text-info" style="display: inline;"></i>
						Activacion de proveedores
						<i class="fas fa-arrow-right text-info"></i>
						Catalogo de proveedor
						<br/>
						<i class="far fa-check-circle text-info" style="display: inline;"></i>
						Historico de productos
						<i class="fas fa-arrow-right text-info"></i>
						Pedido de productos
						<br/>
						<i class="far fa-check-circle text-info" style="display: inline;"></i>
						Productos mas vendidos
						<i class="fas fa-arrow-right text-info"></i>
						Historico de productos
						<br/>
						<i class="far fa-check-circle text-info" style="display: inline;"></i>
						Productos menos vendidos
						<i class="fas fa-arrow-right text-info"></i>
						Historico de productos
						<br/>
						<i class="far fa-check-circle text-info" style="display: inline;"></i>
						Productos en falla
						<i class="fas fa-arrow-right text-info"></i>
						Historico de productos
						<br/>
						<i class="far fa-check-circle text-info" style="display: inline;"></i>
						Pedido de productos
						<i class="fas fa-arrow-right text-info"></i>
						Historico de productos
						<br/>
						<i class="far fa-check-circle text-info" style="display: inline;"></i>
						Catalogo de proveedor
						<i class="fas fa-arrow-right text-info"></i>
						Historico de productos
    				</li>		
    			</ul>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
	  	</div>
	</div>


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
		    			11	    			
		    		</span>
	    		</h2>
	    		<p class="card-text text-white">Reportes disponibles</p>
	  		</div>
		  	<div class="card-footer bg-transparent border-info text-right">
		  		<a href="/sedes_reporte/" class="btn btn-outline-info btn-sm">Visualizar</a>
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
		{{-- CONTACTO --}}
		<div class="card border-info  mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-info">
	    		<h2 class="card-title">	    			
		    		<span class="card-text text-white">
		    			<i class="far fa-lightbulb"></i>
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

	<script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();   
		});
		$('#exampleModalCenter').modal('show')
	</script>
@endsection