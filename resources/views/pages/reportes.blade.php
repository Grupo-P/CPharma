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

	<?php
		include(app_path().'\functions\config.php'); 
		include(app_path().'\functions\funciones.php');

		if (isset($_GET['SEDE'])){						
			echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE']).'</h1>';
		}		
	?>

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
		  		<form action="/reporte1/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Visualizar</button>
				</form>
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
		  		<form action="/reporte2/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Visualizar</button>
				</form>
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
		  		<form action="/reporte3/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
				</form>
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
		  		<form action="/reporte4/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Visualizar</button>
				</form>
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
		  		<form action="/reporte5/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
				</form>
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
		  		<form action="/reporte6/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Visualizar</button>
				</form>
		  	</div>
		</div>
   	</div>

   	<div class="card-deck">
   		<!-- Reportes -->
		<div class="card border-danger mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-danger">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Catalogo de proveedor
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-danger text-right">
		  		<form action="/reporte7/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Visualizar</button>
				</form>
		  	</div>
		</div>
		
		<div class="card border-success mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-success">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Actualizar troquel
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-success text-right">
		  		<form action="/reporte8/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Visualizar</button>
				</form>
		  	</div>
		</div>
	
		<div class="card border-info mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-info">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Productos para surtir
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-info text-right">
		  		<form action="/reporte9/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
				</form>
		  	</div>
		</div>
   	</div>

   	<div class="card-deck">
   		<div class="card border-warning mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-warning">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Analitico de precios
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-warning text-right">
		  		<form action="/reporte10/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Visualizar</button>
				</form>
		  	</div>
		</div>
   	</div>
@endsection