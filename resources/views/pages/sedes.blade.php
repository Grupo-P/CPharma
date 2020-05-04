@extends('layouts.model')

@section('title')
  Reportes
@endsection

@section('content')

	<?php
		include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');
		$SedeConnection = FG_Mi_Ubicacion();
		//$SedeConnection = 'FTN';
	?>

	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		Reportes
	</h1>
	<hr class="row align-items-start col-12"> 

<!-------------------------------------------------------------------------------->
<!-- CPHARMA ON LINE -->
<!-------------------------------------------------------------------------------->
<!-- CASO GP -->
<?php
	if($SedeConnection == 'GP'){
?>
	<?php
		if(Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.'){
	?>
	<div class="card-deck">
		<div class="card border-danger mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-danger">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			<?php echo "".SedeFTN; ?>
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-danger text-right"> 
		    <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
	  	</div>
		</div>
	</div>
	<?php
	}
		if(Auth::user()->sede == 'FARMACIA LA LAGO,C.A.'){
	?>
	<div class="card-deck">
		<div class="card border-success mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-success">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			<?php echo "".SedeFLL; ?>
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-success text-right">
			  <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
	  	</div>
		</div>
	</div>
	<?php
	}
		if(Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.'){
	?> 
	<div class="card-deck">
		<div class="card border-info mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-info">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			<?php echo "".SedeFAU; ?>
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-info text-right">
		   	<a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
	  	</div>
   	</div>
  </div>
  <?php
	}
		if(Auth::user()->sede == 'GRUPO P, C.A'){
	?> 
		<div class="card-deck">
			<div class="card border-danger mb-3" style="width: 14rem;">	  	
				<div class="card-body text-left bg-danger">
		  		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			<?php echo "".SedeFTN; ?>
		    		</span>
		  		</h5>	    	    
				</div>
		  	<div class="card-footer bg-transparent border-danger text-right"> 
			    <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
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
				  <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
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
			   	<a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
		  	</div>
	   	</div>
	  </div>
	<?php
		}
	?>
<!-- CASO GP -->
<!-------------------------------------------------------------------------------->
<!-- CASO FTN -->
<?php
}
	if($SedeConnection == 'FTN'){
?>
	<?php
		if(Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.'){
	?>
	<div class="card-deck">
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
	</div>
	<?php
	}
		if(Auth::user()->sede == 'FARMACIA LA LAGO,C.A.'){
	?>
	<div class="card-deck">
		<div class="card border-success mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-success">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			<?php echo "".SedeFLL; ?>
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-success text-right">
			  <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
	  	</div>
		</div>
	</div>
	<?php
	}
		if(Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.'){
	?> 
	<div class="card-deck">
		<div class="card border-info mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-info">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			<?php echo "".SedeFAU; ?>
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-info text-right">
		   	<a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
	  	</div>
   	</div>
  </div>
  <?php
	}
		if(Auth::user()->sede == 'GRUPO P, C.A'){
	?> 
		<div class="card-deck">
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
				  <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
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
			   	<a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
		  	</div>
	   	</div>
	  </div>
	<?php
		}
	?>
<!-- CASO FTN -->
<!-------------------------------------------------------------------------------->
<!-- CASO FLL -->
<?php
}
	if($SedeConnection == 'FLL'){
?>
	<?php
		if(Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.'){
	?>
	<div class="card-deck">
		<div class="card border-danger mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-danger">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			<?php echo "".SedeFTN; ?>
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-danger text-right"> 
		    <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
	  	</div>
		</div>
	</div>
	<?php
	}
		if(Auth::user()->sede == 'FARMACIA LA LAGO,C.A.'){
	?>
	<div class="card-deck">
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
	</div>
	<?php
	}
		if(Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.'){
	?> 
	<div class="card-deck">
		<div class="card border-info mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-info">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			<?php echo "".SedeFAU; ?>
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-info text-right">
		   	<a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
	  	</div>
   	</div>
  </div>
  <?php
	}
		if(Auth::user()->sede == 'GRUPO P, C.A'){
	?> 
		<div class="card-deck">
			<div class="card border-danger mb-3" style="width: 14rem;">	  	
				<div class="card-body text-left bg-danger">
		  		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			<?php echo "".SedeFTN; ?>
		    		</span>
		  		</h5>	    	    
				</div>
		  	<div class="card-footer bg-transparent border-danger text-right"> 
			    <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
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
			   	<a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
		  	</div>
	   	</div>
	  </div>
	<?php
		}
	?>
<!-- CASO FLL -->
<!-------------------------------------------------------------------------------->
<!-- CASO FAU -->
<?php
}
	if($SedeConnection == 'FAU'){
?>
	<?php
		if(Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.'){
	?>
	<div class="card-deck">
		<div class="card border-danger mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-danger">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			<?php echo "".SedeFTN; ?>
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-danger text-right"> 
		    <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
	  	</div>
		</div>
	</div>
	<?php
	}
		if(Auth::user()->sede == 'FARMACIA LA LAGO,C.A.'){
	?>
	<div class="card-deck">
		<div class="card border-success mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-success">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			<?php echo "".SedeFLL; ?>
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-success text-right">
			  <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
	  	</div>
		</div>
	</div>
	<?php
	}
		if(Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.'){
	?> 
	<div class="card-deck">
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
  <?php
	}
		if(Auth::user()->sede == 'GRUPO P, C.A'){
	?> 
		<div class="card-deck">
			<div class="card border-danger mb-3" style="width: 14rem;">	  	
				<div class="card-body text-left bg-danger">
		  		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			<?php echo "".SedeFTN; ?>
		    		</span>
		  		</h5>	    	    
				</div>
		  	<div class="card-footer bg-transparent border-danger text-right"> 
			    <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
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
				  <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
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
	<?php
		}
	?>
<!-- CASO FAU -->
<!-------------------------------------------------------------------------------->
<!-- CPHARMA ON LINE -->
<!-------------------------------------------------------------------------------->
<!-- CPHARMA OFF LINE -->
<!-------------------------------------------------------------------------------->
<?php
}
	if(Auth::user()->sede == 'GRUPO P, C.A') {
?>
	<?php
		if($SedeConnection == 'GP') {
	?>	
	<!-- CASO GP -->
		<div class="card-deck">
			<div class="card border-danger mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-danger">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			<?php echo "".SedeFTNOFF."<br/>".FG_LastRestoreDB(nameFTNOFF,$SedeConnection);?>
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
		    			<?php echo "".SedeFLLOFF."<br/>".FG_LastRestoreDB(nameFLLOFF,$SedeConnection);?>
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
		    			<?php echo "".SedeFAUOFF."<br/>".FG_LastRestoreDB(nameFAUOFF,$SedeConnection);?>
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
	<!-- CASO GP -->
	<?php
		}
		if($SedeConnection == 'FTN') {
	?>	
	<!-- CASO FTN -->
		<div class="card-deck">
			<div class="card border-success mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-success">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			<?php echo "".SedeFLLOFF."<br/>".FG_LastRestoreDB(nameFLLOFF,$SedeConnection);?>
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
		    			<?php echo "".SedeFAUOFF."<br/>".FG_LastRestoreDB(nameFAUOFF,$SedeConnection);?>
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
	<!-- CASO FTN -->
	<?php
		}
		if($SedeConnection == 'FLL') {
	?>	
	<!-- CASO FLL -->
		<div class="card-deck">
			<div class="card border-danger mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-danger">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			<?php echo "".SedeFTNOFF."<br/>".FG_LastRestoreDB(nameFTNOFF,$SedeConnection);?>
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
		    			<?php echo "".SedeFAUOFF."<br/>".FG_LastRestoreDB(nameFAUOFF,$SedeConnection);?>
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
	<!-- CASO FLL -->
	<?php
		}
		if($SedeConnection == 'FAU') {
	?>	
	<!-- CASO FAU -->
		<div class="card-deck">
			<div class="card border-danger mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-danger">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			<?php echo "".SedeFTNOFF."<br/>".FG_LastRestoreDB(nameFTNOFF,$SedeConnection);?>
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
		    			<?php echo "".SedeFLLOFF."<br/>".FG_LastRestoreDB(nameFLLOFF,$SedeConnection);?>
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
	<!-- CASO FAU -->
	<?php
		}
	  if(Auth::user()->role == 'DEVELOPER'){
 	?>
	<!-- CASO USER DEVELOPER -->
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
		<div class="card border-warning mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-warning">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			<?php echo "Servidor de Desarrollo Rubmary"; ?>
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
 	<!-- CASO USER DEVELOPER -->
 	<?php
	}
?>
<!-------------------------------------------------------------------------------->
<!-- CPHARMA OFF LINE -->
<!-------------------------------------------------------------------------------->

<!-------------------------------------------------------------------------------->
<!-- CASO FTN -->
<?php
}
	if($SedeConnection == 'ARG'){
?>
	<?php
		if(Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.'){
	?>
	<div class="card-deck">
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
			    <input id="SEDE" name="SEDE" type="hidden" value="ARG">
			    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Ver reportes</button>
				</form>
	  	</div>
		</div>
	</div>
	<?php
	}
		if(Auth::user()->sede == 'FARMACIA LA LAGO,C.A.'){
	?>
	<div class="card-deck">
		<div class="card border-success mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-success">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			<?php echo "".SedeFLL; ?>
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-success text-right">
			  <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
	  	</div>
		</div>
	</div>
	<?php
	}
		if(Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.'){
	?> 
	<div class="card-deck">
		<div class="card border-info mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-info">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			<?php echo "".SedeFAU; ?>
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-info text-right">
		   	<a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
	  	</div>
   	</div>
  </div>
  <?php
	}
		if(Auth::user()->sede == 'GRUPO P, C.A'){
	?> 
		<div class="card-deck">
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
				  <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
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
			   	<a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
		  	</div>
	   	</div>
	  </div>
	<?php
		}
	}
	?>
<!-- CASO FTN -->
<!-------------------------------------------------------------------------------->
@endsection