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

<!-------------------------------------------------------------------------------->
<!-- COMPRAS -->
<?php
  if(Auth::user()->departamento == 'COMPRAS'){
?>
	<div class="card-deck">
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
	    			Analitico de precios
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-success text-right">
	  		<form action="/reporte10/" style="display: inline;">
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
	    			Compromisos
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-info text-right">
	  		<form action="/cartaCompromiso" style="display: inline;">
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
	    			Productos Por Fallar
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-warning text-right">
	  		<form action="/reporte13/" style="display: inline;">
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
	    			Productos en caida
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-secondary text-right">
	  		<form action="/reporte14/" style="display: inline;">
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
	    			Articulos devaluados
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-dark text-right">
	  		<form action="/reporte15/" style="display: inline;">
			    @csrf
			    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">			   
			    <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Visualizar</button>
				</form>
	  	</div>
		</div>
 	</div>

 	<div class="card-deck">
		<div class="card border-danger mb-3" style="width: 14rem;">	  	
			<div class="card-body text-left bg-danger">
	  		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			Detalle de movimientos
	    		</span>
	  		</h5>	    	    
			</div>
	  	<div class="card-footer bg-transparent border-danger text-right">
	  		<form action="/reporte12/" style="display: inline;">
			    @csrf
			    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
			    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Visualizar</button>
				</form>
	  	</div>
		</div>
 	</div>
<?php
  }
?>
<!-- COMPRAS -->
<!-------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------->
<!-- OPERACIONES -->
<?php
  if(Auth::user()->departamento == 'OPERACIONES'){
?>
	<div class="card-deck">
		<div class="card border-danger mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-danger">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			Historico de productos
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-danger text-right">
	  		<form action="/reporte2/" style="display: inline;">
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
	    			Detalle de movimientos
	    		</span>
	  		</h5>	    	    
			</div>
	  	<div class="card-footer bg-transparent border-info text-right">
	  		<form action="/reporte12/" style="display: inline;">
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
		<div class="card border-secondary mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-secondary">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			Compromisos
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-secondary text-right">
	  		<form action="/cartaCompromiso" style="display: inline;">
			    @csrf
			    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">				   
			    <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
				</form>
	  	</div>
		</div>
 	</div>
<?php
  }
?>
<!-- OPERACIONES -->
<!-------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------->
<!-- ALMACEN -->
<?php
  if(Auth::user()->departamento == 'ALMACEN'){
?>
	<div class="card-deck">
		<div class="card border-danger mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-danger">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			Historico de productos
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-danger text-right">
	  		<form action="/reporte2/" style="display: inline;">
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
	    			Analitico de precios
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-success text-right">
	  		<form action="/reporte10/" style="display: inline;">
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
	    			Detalle de movimientos
	    		</span>
	  		</h5>	    	    
			</div>
	  	<div class="card-footer bg-transparent border-info text-right">
	  		<form action="/reporte12/" style="display: inline;">
			    @csrf
			    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
			    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
				</form>
	  	</div>
		</div>
	</div>
<?php
  }
?>
<!-- ALMACEN -->
<!-------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------->
<!-- DEVOLUCIONES -->
<?php
  if(Auth::user()->departamento == 'DEVOLUCIONES'){
?>
	<div class="card-deck">
		<div class="card border-danger mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-danger">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			Historico de productos
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-danger text-right">
	  		<form action="/reporte2/" style="display: inline;">
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
	    			Compromisos
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-success text-right">
	  		<form action="/cartaCompromiso" style="display: inline;">
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
	    			Detalle de movimientos
	    		</span>
	  		</h5>	    	    
			</div>
	  	<div class="card-footer bg-transparent border-info text-right">
	  		<form action="/reporte12/" style="display: inline;">
			    @csrf
			    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
			    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
				</form>
	  	</div>
		</div>
	</div>
<?php
  }
?>
<!-- DEVOLUCIONES -->
<!-------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------->
<!-- SURTIDO -->
<?php
  if(Auth::user()->departamento == 'SURTIDO'){
?>
	<div class="card-deck">
		<div class="card border-danger mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-danger">
    		<h5 class="card-title">
	    		<span class="card-text text-white">
	    			Historico de productos
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-danger text-right">
	  		<form action="/reporte2/" style="display: inline;">
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
	    			Productos para surtir
	    		</span>
    		</h5>	    	    
  		</div>
	  	<div class="card-footer bg-transparent border-success text-right">
	  		<form action="/reporte9/" style="display: inline;">
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
	    			Detalle de movimientos
	    		</span>
	  		</h5>	    	    
			</div>
	  	<div class="card-footer bg-transparent border-info text-right">
	  		<form action="/reporte12/" style="display: inline;">
			    @csrf
			    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
			    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
				</form>
	  	</div>
		</div>
	</div>
<?php
  }
?>
<!-- SURTIDO -->
<!-------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------->
<!-- VENTAS -->
		<!-- ESTE DEPARTAMENTOS NO POSEE REPORTES -->
<!-- VENTAS -->
<!-------------------------------------------------------------------------------->


<?php
  if(Auth::user()->departamento == 'klk'){
?>
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
				<?php
					if(
						Auth::user()->departamento == 'COMPRAS' 
						|| Auth::user()->departamento == 'LÍDER DE TIENDA'
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
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
				<?php
					if(
						Auth::user()->departamento == 'COMPRAS'
						|| Auth::user()->departamento == 'OPERACIONES'
						|| Auth::user()->departamento == 'LÍDER DE TIENDA'
						|| Auth::user()->departamento == 'DEVOLUCIONES'  
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
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
				<?php
					if(
						Auth::user()->departamento == 'COMPRAS'
						|| Auth::user()->departamento == 'LÍDER DE TIENDA'
						|| Auth::user()->departamento == 'SURTIDO' 
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
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
				<?php
					if(
						Auth::user()->departamento == 'COMPRAS'
						|| Auth::user()->departamento == 'LÍDER DE TIENDA'
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
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
				<?php
					if(
						Auth::user()->departamento == 'COMPRAS'
						|| Auth::user()->departamento == 'LÍDER DE TIENDA'
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
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
				<?php
					if(
						Auth::user()->departamento == 'COMPRAS'						
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
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
				<?php
					if(
						Auth::user()->departamento == 'COMPRAS'						
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
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
				<?php
					if(
						Auth::user()->departamento == 'OPERACIONES'
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
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
				<?php
					if(
						Auth::user()->departamento == 'SURTIDO'
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
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
				<?php
					if(
						Auth::user()->departamento == 'COMPRAS'
						|| Auth::user()->departamento == 'LÍDER DE TIENDA'
						|| Auth::user()->departamento == 'OPERACIONES' 
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
				</form>
		  	</div>
		</div>

		<div class="card border-secondary mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-secondary">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Compromisos
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-secondary text-right">
		  		<form action="/cartaCompromiso" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				<?php
					if(
						Auth::user()->departamento == 'COMPRAS'
						|| Auth::user()->departamento == 'DEVOLUCIONES'
						|| Auth::user()->departamento == 'OPERACIONES' 
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
				</form>
		  	</div>
		</div>

		<div class="card border-dark mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-dark">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Detalle de movimientos
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-dark text-right">
		  		<form action="/reporte12/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				<?php
					if(
						Auth::user()->departamento == 'COMPRAS'
						|| Auth::user()->departamento == 'LÍDER DE TIENDA'
						|| Auth::user()->departamento == 'OPERACIONES' 
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
				</form>
		  	</div>
		</div>
   	</div>

   	<div class="card-deck">
   		<div class="card border-danger mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-danger">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Productos Por Fallar
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-danger text-right">
		  		<form action="/reporte13/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				<?php
					if(
						Auth::user()->departamento == 'COMPRAS'
						|| Auth::user()->departamento == 'LÍDER DE TIENDA'
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
				</form>
		  	</div>
		</div>

		<div class="card border-success mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-success">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Productos en caida
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-success text-right">
		  		<form action="/reporte14/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				<?php
					if(
						Auth::user()->departamento == 'COMPRAS'
						|| Auth::user()->departamento == 'LÍDER DE TIENDA'
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
				</form>
		  	</div>
		</div>

		<div class="card border-info mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-info">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Articulos devaluados
		    		</span>
	    		</h5>	    	    
	  		</div>
		  	<div class="card-footer bg-transparent border-info text-right">
		  		<form action="/reporte15/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				<?php
					if(
						Auth::user()->departamento == 'COMPRAS'
						|| Auth::user()->departamento == 'GERENCIA'
						|| Auth::user()->departamento == 'TECNOLOGIA'
					){
				?>				   
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
				<?php
					}
				?>
				</form>
		  	</div>
		</div>
   	</div>
<?php
  }
?>
@endsection