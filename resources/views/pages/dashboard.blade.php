@extends('layouts.model')

@section('title')
    Dashboard
@endsection

@section('content')
	<?php
		$empresas = DB::table('empresas')->count();
		$proveedores = DB::table('proveedors')->count();
		$usuarios = DB::table('users')->count();
		$dolar = DB::table('dolars')->count();

		$tasaVenta = DB::table('tasa_ventas')->where('moneda', 'Dolar')->value('updated_at');
		$Tasa = DB::table('tasa_ventas')->where('moneda', 'Dolar')->value('tasa');
		$Tasa = number_format($Tasa,2,"," ,"." );
		$tasaVenta = new DateTime($tasaVenta);
		$tasaVenta = $tasaVenta->format("d-m-Y h:i:s a");

		$FHoy = date("Y-m-d");

		$auditor =  
		DB::table('auditorias')
		->select('registro')
		->groupBy('registro')
		->orderBy(DB::raw('count(*)'),'desc')
		->where('tabla','reporte')
		->where('updated_at', '>',$FHoy)
   	->take(1)->get();
    $auditor = $auditor[0];
    $auditorias = $auditor->registro;

    $auditor =  
		DB::table('auditorias')
		->select('user')
		->groupBy('user')
		->orderBy(DB::raw('count(*)'),'desc')
		->where('updated_at', '>',$FHoy)
   	->take(1)->get();
    $auditor = $auditor[0];
    $usuarioAct = $auditor->user;
	?>

	<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  	<div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b>!!</label>
		      	Estas usando<b class="text-info"> CPharma v.4.2</b>, esta version incluye las siguientes mejoras:</label>

		        <ul style="list-style:none">
		        	<li class="card-text text-dark" style="display: inline;">
    					<br/>
						<i class="far fa-check-circle text-info" style="display: inline;"></i>
						Desde ya esta disponible el reporte: 
						<b class="text-info">Articulos devaluados</b>!!
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
		<div class="card CP-border-golden mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left CP-bg-golden">
	    		<h4>
		    		<span class="card-text text-white">
		    			<i class="fas fa-star CP-Latir"></i>
		    			<?php
							echo ''.$auditorias;
						?>						
		    		</span>
	    		</h4>
	    		<p class="card-text text-white">Reporte mas usado</p>
	  		</div>	
		</div>

		<div class="card CP-border-golden mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left CP-bg-golden">
	    		<h4>
		    		<span class="card-text text-white">
		    			<i class="fas fa-star CP-Latir"></i>
		    			<?php
							echo ''.$usuarioAct;
						?>						
		    		</span>
	    		</h4>
	    		<p class="card-text text-white">Usuario mas activo</p>
	  		</div>	
		</div>
	</div>

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
		    			15	    			
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
		<div class="card border-warning mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-warning">
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
		  	<div class="card-footer bg-transparent border-warning text-right">
		  		<a href="/usuario/" class="btn btn-outline-warning btn-sm">Visualizar</a>
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
		<!-- Tasa Venta -->
		<div class="card border-dark mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-dark">
	    		<h3 class="card-title">
		    		<span class="card-text text-white">
		    			<i class="fas fa-credit-card"></i>
		    			<?php
							echo 'Tasa Venta: '.$Tasa;
						?>						
		    		</span>
	    		</h3>
	    		<p class="card-text text-white">
					<?php 
						echo 'Ultima Actualizacion: '.$tasaVenta;
					?>
	    		</p>
	  		</div>
  		<?php
		  	if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER'){
	 	?>
		  	<div class="card-footer bg-transparent border-dark text-right">
		  		<a href="/tasaVenta/" class="btn btn-outline-dark btn-sm">Visualizar</a>
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
		    			<i class="far fa-lightbulb CP-beep"></i>
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