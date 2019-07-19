@extends('layouts.model')

@section('title')
    Reportes
@endsection

@section('content')

	<?php 
		include(app_path().'\functions\config.php'); 
		include(app_path().'\functions\Querys.php');
		include(app_path().'\functions\funciones.php');
		$MiUbicacion = MiUbicacion();
	?>

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
		    			<?php echo "".SedeFTN; ?>
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-danger text-right">
		  		<form action="/reporte/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="FTN">  
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Ver reportes</button>
				</form>
		  	</div>
		</div>

		<div class="card border-success mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-success">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			<?php echo "".SedeFLL; ?>
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-success text-right">
		  		<form action="/reporte/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="FLL">  
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Ver reportes</button>
				</form>
		  	</div>
		</div>

		<div class="card border-info mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-info">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			<?php echo "".SedeFAU; ?>
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-info text-right">
		  		<form action="/reporte/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="FAU">  
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Ver reportes</button>
				</form>
		  	</div>
		</div>
   	</div>
<!-- INICIO: Panel CPharma OFF Line -->
	<?php
/*INICIO CASO FTN (ON-LINE), FLL (OFF-LINE) FAU (OFF-LINE)*/
	if($MiUbicacion == 'FTN'){
	?>	
		<div class="card-deck">
	   		<!-- Reportes -->
			<div class="card border-success mb-3" style="width: 14rem;">	  	
		  		<div class="card-body text-left bg-success">
		    		<h5 class="card-title">
			    		<span class="card-text text-white">
			    			<?php echo "".SedeFLLOFF."<br/>".LastRestoreDB(nameFLLOFF);?>
			    		</span>
		    		</h5>	    	    
		  		</div>
			  	<div class="card-footer bg-transparent border-success text-right">
			  		<form action="/reporte/" style="display: inline;">
					    @csrf
					    <input id="SEDE" name="SEDE" type="hidden" value="FTNFLL">  
					    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Ver reportes</button>
					</form>
			  	</div>
			</div>

			<div class="card border-info mb-3" style="width: 14rem;">	  	
		  		<div class="card-body text-left bg-info">
		    		<h5 class="card-title">
			    		<span class="card-text text-white">
			    			<?php echo "".SedeFAUOFF."<br/>".LastRestoreDB(nameFAUOFF);?>
			    		</span>
		    		</h5>	    	    
		  		</div>
			  	<div class="card-footer bg-transparent border-info text-right">
			  		<form action="/reporte/" style="display: inline;">
					    @csrf
					    <input id="SEDE" name="SEDE" type="hidden" value="FTNFAU">  
					    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Ver reportes</button>
					</form>
			  	</div>
			</div>
		</div>
	<?php 
/*FIN CASO FTN (ON-LINE), FLL (OFF-LINE) FAU (OFF-LINE)*/
	}
	else if($MiUbicacion =='FLL'){
	?>
		<div class="card-deck">
	   		<!-- Reportes -->
			<div class="card border-danger mb-3" style="width: 14rem;">	  	
		  		<div class="card-body text-left bg-danger">
		    		<h5 class="card-title">
			    		<span class="card-text text-white">
			    			<?php echo "".SedeFTNOFF."<br/>".LastRestoreDB(nameFTNOFF);?>
			    		</span>
		    		</h5>	    	    
		  		</div>
			  	<div class="card-footer bg-transparent border-danger text-right">
			  		<form action="/reporte/" style="display: inline;">
					    @csrf
					    <input id="SEDE" name="SEDE" type="hidden" value="FLLFTN">  
					    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Ver reportes</button>
					</form>
			  	</div>
			</div>

			<div class="card border-info mb-3" style="width: 14rem;">	  	
		  		<div class="card-body text-left bg-info">
		    		<h5 class="card-title">
			    		<span class="card-text text-white">
			    			<?php echo "".SedeFAUOFF."<br/>".LastRestoreDB(nameFAUOFF);?>
			    		</span>
		    		</h5>	    	    
		  		</div>
			  	<div class="card-footer bg-transparent border-info text-right">
			  		<form action="/reporte/" style="display: inline;">
					    @csrf
					    <input id="SEDE" name="SEDE" type="hidden" value="FLLFAU">
					    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Ver reportes</button>
					</form>
			  	</div>
			</div>
		</div>
	<?php
	}
	else if($MiUbicacion =='FAU'){
	?>
		<div class="card-deck">
	   		<!-- Reportes -->
			<div class="card border-danger mb-3" style="width: 14rem;">	  	
		  		<div class="card-body text-left bg-danger">
		    		<h5 class="card-title">
			    		<span class="card-text text-white">
			    			<?php echo "".SedeFTNOFF."<br/>".LastRestoreDB(nameFTNOFF);?>
			    		</span>
		    		</h5>	    	    
		  		</div>
			  	<div class="card-footer bg-transparent border-danger text-right">
			  		<form action="/reporte/" style="display: inline;">
					    @csrf
					    <input id="SEDE" name="SEDE" type="hidden" value="FAUFTN"> 
					    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Ver reportes</button>
					</form>
			  	</div>
			</div>

			<div class="card border-success mb-3" style="width: 14rem;">	  	
		  		<div class="card-body text-left bg-success">
		    		<h5 class="card-title">
			    		<span class="card-text text-white">
			    			<?php echo "".SedeFLLOFF."<br/>".LastRestoreDB(nameFLLOFF);?>
			    		</span>
		    		</h5>	    	    
		  		</div>
			  	<div class="card-footer bg-transparent border-success text-right">
			  		<form action="/reporte/" style="display: inline;">
					    @csrf
					    <input id="SEDE" name="SEDE" type="hidden" value="FAUFLL">
					    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Ver reportes</button>
					</form>
			  	</div>
			</div>
		</div>
	<?php
	}
	else if($MiUbicacion == 'GP'){
	?>	
		<div class="card-deck">
	   		<!-- Reportes -->
			<div class="card border-danger mb-3" style="width: 14rem;">	  	
		  		<div class="card-body text-left bg-danger">
		    		<h5 class="card-title">
			    		<span class="card-text text-white">
			    			<?php echo "".SedeFTNOFF."<br/>".LastRestoreDB(nameFTN);?>
			    		</span>
		    		</h5>	    	    
		  		</div>
			  	<div class="card-footer bg-transparent border-danger text-right">
			  		<form action="/reporte/" style="display: inline;">
					    @csrf
					    <input id="SEDE" name="SEDE" type="hidden" value="GPFTN">  
					    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Ver reportes</button>
					</form>
			  	</div>
			</div>

			<div class="card border-success mb-3" style="width: 14rem;">	  	
		  		<div class="card-body text-left bg-success">
		    		<h5 class="card-title">
			    		<span class="card-text text-white">
			    			<?php echo "".SedeFLLOFF."<br/>".LastRestoreDB(nameFLLOFF);?>
			    		</span>
		    		</h5>	    	    
		  		</div>
			  	<div class="card-footer bg-transparent border-success text-right">
			  		<form action="/reporte/" style="display: inline;">
					    @csrf
					    <input id="SEDE" name="SEDE" type="hidden" value="GPFLL">
					    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Ver reportes</button>
					</form>
			  	</div>
			</div>

			<div class="card border-info mb-3" style="width: 14rem;">	  	
		  		<div class="card-body text-left bg-info">
		    		<h5 class="card-title">
			    		<span class="card-text text-white">
			    			<?php echo "".SedeFAUOFF."<br/>".LastRestoreDB(nameFAUOFF);?>
			    		</span>
		    		</h5>	    	    
		  		</div>
			  	<div class="card-footer bg-transparent border-info text-right">
			  		<form action="/reporte/" style="display: inline;">
					    @csrf
					    <input id="SEDE" name="SEDE" type="hidden" value="GPFAU">
					    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Ver reportes</button>
					</form>
			  	</div>
			</div>
		</div>
	<?php
	}
	?>
<!-- FIN: Panel CPharma OFF Line -->
   	<?php
	  if(Auth::user()->role == 'DEVELOPER'){
	 ?>

   	<div class="card-deck">
   		<div class="card border-warning mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-warning">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			<?php echo "".SedeDBs; ?>
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-warning text-right">	  		
		  		<form action="/reporte/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="DBs">  
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Ver reportes</button>
				</form>
		  	</div>
		</div>		
   	</div>
	
	<div class="card-deck">
   		<div class="card border-warning mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-warning">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			<?php echo "".SedeDBm; ?>
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-warning text-right">	  		
		  		<form action="/reporte/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="DBm">  
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Ver reportes</button>
				</form>
		  	</div>
		</div>		
   	</div>

   	<?php
		}
	?>
@endsection