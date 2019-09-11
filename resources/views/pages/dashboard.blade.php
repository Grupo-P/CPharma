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

<style> 
  	.beep{
	  -webkit-animation: tiembla 0.5s infinite;
	}
	@-webkit-keyframes tiembla{
	  0%  { -webkit-transform:rotateZ(0deg);}
	  13% { -webkit-transform:rotateZ(5deg);}
	  26%{ -webkit-transform:rotateZ(10deg);}
	  39% { -webkit-transform:rotateZ(5deg)}
	  52% { -webkit-transform:rotateZ(0deg)}
	  65% { -webkit-transform:rotateZ(-5deg)}
	  78%{ -webkit-transform:rotateZ(-10deg);}
	  91% { -webkit-transform:rotateZ(-5deg)}
	}
</style>

	<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  	<div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info beep"></i> Novedades</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b>!!</label>
		      	Estas usando<b class="text-info"> CPharma v.3.9.3</b>, esta version incluye las siguientes mejoras:</label>

		        <ul style="list-style:none">
		        	<li class="card-text text-dark" style="display: inline;">
    					<br/>
						<i class="far fa-check-circle text-info" style="display: inline;"></i>
						Desde ya esta disponible el reporte: 
						<b class="text-info">Productos Por Fallar</b>!!
    				</li>
		        	<li class="card-text text-dark" style="display: inline;">
    					<br/><br/>
						<i class="far fa-check-circle text-info" style="display: inline;"></i>
						Desde ya esta disponible el reporte: 
						<b class="text-info">Detalle de movimientos</b>!!
    				</li>
    				<li class="card-text text-dark" style="display: inline;">
    					<br/><br/>
						<i class="far fa-check-circle text-info" style="display: inline;"></i>
						En Histórico de Productos: Se agregaron los campos
						<b class="text-info"> Codigo de Barra </b>,
						<b class="text-info"> Gravado? </b> y
						<b class="text-info"> Costo Bruto (Sin IVA) HOY</b>.
    				</li>
    				<li class="card-text text-dark" style="display: inline;">
    					<br/><br/>
						<i class="far fa-check-circle text-info" style="display: inline;"></i>
						En Artículos más Vendidos: Se agregaron los campos
						<b class="text-info"> Tipo </b> y
						<b class="text-info"> Total Ventas</b>.
    				</li>
    				<li class="card-text text-dark" style="display: inline;">
    					<br/><br/>
						<i class="far fa-check-circle text-info" style="display: inline;"></i>
						En Catálogo de Proveedor: Se agregaron los campos
						<b class="text-info"> Codigo de Barra </b> y
						<b class="text-info"> Ultima Venta</b>.
    				</li>
    				<li class="card-text text-dark" style="display: inline;">
    					<br/><br/>
						<i class="far fa-check-circle text-info" style="display: inline;"></i>
						En Pedido de Productos: Se agregaron los campos
						<b class="text-info"> Codigo de Barra </b> y
						<b class="text-info"> Ultima Venta</b>, adicional ahora debes colocar la cantidad de dias a pedir.
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
	  	<?php
	  		if( 
	  			Auth::user()->departamento == 'COMPRAS'
			    || Auth::user()->departamento == 'DEVOLUCIONES'
			    || Auth::user()->departamento == 'OPERACIONES' 
			    || Auth::user()->departamento == 'GERENCIA'
			    || Auth::user()->departamento == 'TECNOLOGIA'
	  		){
	 	?>
		  	<div class="card-footer bg-transparent border-danger text-right">
		  		<a href="/empresa/" class="btn btn-outline-danger btn-sm">Visualizar</a>
		  	</div>
		 <?php
			}
		?>
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
  		<?php
	  		if( 
	  			Auth::user()->departamento == 'COMPRAS'
			    || Auth::user()->departamento == 'DEVOLUCIONES'
			    || Auth::user()->departamento == 'OPERACIONES' 
			    || Auth::user()->departamento == 'GERENCIA'
			    || Auth::user()->departamento == 'TECNOLOGIA'
	  		){
	 	?>
		  	<div class="card-footer bg-transparent border-success text-right">
		  		<a href="/proveedor/" class="btn btn-outline-success btn-sm">Visualizar</a>
		  	</div>
		<?php
			}
		?>
		</div>
		<!-- Reportes -->
		<div class="card border-info mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-info">
	    		<h2 class="card-title">
		    		<span class="card-text text-white">
		    			<i class="fas fa-file-invoice"></i>
		    			13	    			
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
  		<?php
		  if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER'){
	 	?>
		  	<div class="card-footer bg-transparent border-dark text-right">
		  		<a href="/usuario/" class="btn btn-outline-dark btn-sm">Visualizar</a>
		  	</div>
		<?php
			}
		?>
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
  		<?php
		  	if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER'){
	 	?>
		  	<div class="card-footer bg-transparent border-secondary text-right">
		  		<a href="/dolar/" class="btn btn-outline-secondary btn-sm">Visualizar</a>
		  	</div>
		<?php
			}
		?>		
		</div>		
	</div>

	<div class="card-deck">
		{{-- CONTACTO --}}
		<div class="card border-info  mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-info">
	    		<h2 class="card-title">	 		
		    		<span class="card-text text-warning">
		    			<i class="far fa-lightbulb beep"></i>
		    		</span>
		    		<span class="card-text text-white">
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