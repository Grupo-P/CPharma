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
		use Illuminate\Http\Request;
		use compras\Departamento;

		include(app_path().'\functions\config.php');
		include(app_path().'\functions\functions.php');

		$departamento =
		DB::table('departamentos')
		->select('descripcion')
    	->where('nombre', '=', Auth::user()->departamento )
        ->get();

        if( (!empty($departamento[0])) ) {
        	$descripcion = ($departamento[0]->descripcion);
        	$reportes = explode(",", $descripcion);
	    }

		if (isset($_GET['SEDE'])){
			echo '<h1 class="h5 text-success"  align="left"> <i class="fas fa-prescription"></i> '.FG_Nombre_Sede($_GET['SEDE']).'</h1>';
		}
	?>
	<hr class="row align-items-start col-12">

<!-------------------------------------------------------------------------------->

	<div class="card-deck">
		<?php
		  if(in_array(1,$reportes)){
		?>
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
		<?php
			}
		?>

		<?php
		  if(in_array(2,$reportes)){
		?>
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
		<?php
			}
		?>

		<?php
		  if(in_array(3,$reportes)){
		?>
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
		<?php
			}
		?>
	</div>

	<div class="card-deck">
		<?php
		  if(in_array(4,$reportes)){
		?>
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
		<?php
			}
		?>

		<?php
		  if(in_array(5,$reportes)){
		?>
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
		<?php
			}
		?>

		<?php
		  if(in_array(6,$reportes)){
		?>
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
		<?php
			}
		?>
 	</div>

 	<div class="card-deck">
 		<?php
		  if(in_array(7,$reportes)){
		?>
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
		<?php
			}
		?>

		<?php
		  if(in_array(10,$reportes)){
		?>
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
		<?php
			}
		?>

		<?php
		  if(in_array(12,$reportes)){
		?>
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
		<?php
			}
		?>
 	</div>

	<div class="card-deck">
		<?php
		  if(in_array(13,$reportes)){
		?>
			<div class="card border-warning mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-warning">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Productos por fallar
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
		<?php
			}
		?>

		<?php
		  if(in_array(14,$reportes)){
		?>
			<?php
				$FlagSede = FG_Sede_OnLine($_GET['SEDE']);
				if($FlagSede==TRUE){
			?>
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
			<?php
				}
			?>
		<?php
			}
		?>

		<?php
		  if(in_array(15,$reportes)){
		?>
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
	 	<?php
			}
		?>
	</div>

	<div class="card-deck">
		<?php
		  if(in_array(9,$reportes)){
		?>
			<div class="card border-danger mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-danger">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Productos para surtir
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-danger text-right">
		  		<form action="/reporte9/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(16,$reportes)){
		?>
			<div class="card border-success mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-success">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Articulos estrella
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-success text-right">
		  		<form action="/reporte16/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(17,$reportes)){
		?>
			<div class="card border-info mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-info">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Tri tienda por articulo
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-info text-right">
		  		<form action="/reporte17/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>
 	</div>

	<div class="card-deck">
		<?php
		  if(in_array(18,$reportes)){
		?>
			<div class="card border-warning mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-warning">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Consulta compras
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-warning text-right">
		  		<form action="/reporte18/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(19,$reportes)){
		?>
			<div class="card border-secondary mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-secondary">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Ventas cruzadas
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-secondary text-right">
		  		<form action="/reporte19/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(20,$reportes)){
		?>
			<div class="card border-dark mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-dark">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Tri tienda por proveedor
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-dark text-right">
		  		<form action="/reporte20/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>
	</div>

	<div class="card-deck">
		<?php
		  if(in_array(21,$reportes)){
		?>
			<div class="card border-danger mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-danger">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Consultor de Precio
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-danger text-right">
		  		<form action="/reporte21/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(22,$reportes)){
		?>
			<div class="card border-success mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-success">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Reporte de Atributos
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-success text-right">
		  		<form action="/reporte22/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(24,$reportes)){
		?>
			<div class="card border-info mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-info">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Artículos Nuevos
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-info text-right">
		  		<form action="/reporte24/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>
	</div>

	<div class="card-deck">
		<?php
		  if(in_array(99,$reportes)){
		?>
			<div class="card border-warning mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-warning">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Registro de Fallas
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-warning text-right">
		  		<form action="/reporteFalla" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(25,$reportes)){
		?>
			<div class="card border-secondary mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-secondary">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Articulos en Cero
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-secondary text-right">
		  		<form action="/reporte25/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(26,$reportes)){
		?>
			<div class="card border-dark mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-dark">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Ultimas Entradas en Cero
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-dark text-right">
		  		<form action="/reporte26/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

	</div>

	<div class="card-deck">

		<?php
		  if(in_array(27,$reportes)){
		?>
			<div class="card border-danger mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-danger">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Artículos por Vencer
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-danger text-right">
		  		<form action="/reporte27/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(28,$reportes)){
		?>
			<div class="card border-success mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-success">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Artículos sin fecha de vencimiento
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-success text-right">
		  		<form action="/reporte28/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(29,$reportes)){
		?>
			<div class="card border-info mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-info">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Compra por Marca
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-info text-right">
		  		<form action="/reporte29/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

	</div>

	<div class="card-deck">

		<?php
		  if(in_array(30,$reportes)){
		?>
			<div class="card border-warning mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-warning">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Registro de Compras
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-warning text-right">
		  		<form action="/reporte30/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(31,$reportes)){
		?>
			<div class="card border-secondary mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-secondary">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Monitoreo de Inventarios
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-secondary text-right">
		  		<form action="/reporte31/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(32,$reportes)){
		?>
			<div class="card border-dark mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-dark">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
					Seguimiento de Tienda
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-dark text-right">
		  		<form action="/reporte32/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

	</div>

	<div class="card-deck">
		<?php
		  if(in_array(33,$reportes)){
		?>
			<div class="card border-danger mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-danger">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Devoluciones de clientes
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-danger text-right">
		  		<form action="/reporte33/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(34,$reportes)){
		?>
			<div class="card border-success mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-success">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Articulos estancados en tienda
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-success text-right">
		  		<form action="/reporte34/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(35,$reportes)){
		?>
			<div class="card border-info mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-info">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Articulos sin ventas
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-info text-right">
		  		<form action="/reporte35/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>
	</div>

	<div class="card-deck">
		<?php
		  if(in_array(36,$reportes)){
		?>
			<div class="card border-warning mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-warning">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Lista de precios
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-warning text-right">
		  		<form action="/reporte36/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(37,$reportes)){
		?>
			<div class="card border-secondary mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-secondary">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Traslados entre tiendas
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-secondary text-right">
		  		<form action="/reporte37/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

		<?php
		  if(in_array(38,$reportes)){
		?>
			<div class="card border-dark mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-dark">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Registro de reclamos
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-dark text-right">
		  		<form action="/reporte38/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>
	</div>

	<div class="card-deck">
		<?php
		  if(in_array(39,$reportes)){
		?>
			<div class="card border-danger mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-danger">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
		    			Revisión de inventarios físicos
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-danger text-right">
		  		<form action="/reporte39/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

        <?php
          if(in_array(40,$reportes)){
        ?>
            <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Surtido de gavetas
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <form action="/reporte40/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Visualizar</button>
                    </form>
            </div>
            </div>
        <?php
            }
        ?>

        <?php
          if(in_array(41,$reportes)){
        ?>
            <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Artículos competidos
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <form action="/reporte41/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
                    </form>
            </div>
            </div>
        <?php
            }
        ?>
	</div>

    <div class="card-deck">
        <?php
          if(in_array(42,$reportes)){
        ?>
            <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Ajustes de inventario
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <form action="/reporte42/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Visualizar</button>
                    </form>
            </div>
            </div>
        <?php
            }
        ?>

        <?php
          if(in_array(43,$reportes)){
        ?>
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Traslados
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <form action="/reporte43" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
                    </form>
            </div>
            </div>
        <?php
            }
        ?>

        <?php
          if(in_array(44,$reportes)){
        ?>
            <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Traslados por llegar
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <form action="/reporte44" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Visualizar</button>
                    </form>
            </div>
            </div>
        <?php
            }
        ?>
    </div>

    <div class="card-deck">
    <?php
		  if(in_array(45,$reportes)){
		?>
			<div class="card border-danger mb-3" style="width: 14rem;">
	  		<div class="card-body text-left bg-danger">
	    		<h5 class="card-title">
		    		<span class="card-text text-white">
                        Articulos sin imagen
		    		</span>
	    		</h5>
	  		</div>
		  	<div class="card-footer bg-transparent border-danger text-right">
		  		<form action="/reporte45/" style="display: inline;">
				    @csrf
				    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Visualizar</button>
					</form>
		  	</div>
			</div>
		<?php
			}
		?>

        <?php
          if(in_array(46,$reportes)){
        ?>
            <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Compras por archivo
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <form action="/reporte46/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Visualizar</button>
                    </form>
            </div>
            </div>
        <?php
            }
        ?>

        <?php
          if(in_array(47,$reportes)){
        ?>
            <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Cruce de aplicación de consultas
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <form action="/reporte47/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Visualizar</button>
                    </form>
            </div>
            </div>
        <?php
            }
        ?>
    </div>

	<div class="card-deck">
		<?php
          if(in_array(48,$reportes)){
        ?>
            <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Cambio de precios
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <form action="/reporte48/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Visualizar</button>
                    </form>
            </div>
            </div>
        <?php
            }
        ?>

        <?php
          if(in_array(49,$reportes)){
        ?>
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Reposicion de Inventario
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <form action="/reporte49/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
                    </form>
            </div>
            </div>
        <?php
            }
        ?>


        <?php
          if(in_array(50,$reportes)){
        ?>
            <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Catálogo de droguerías
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <form action="/reporte50/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Visualizar</button>
                    </form>
            </div>
            </div>
        <?php
            }
        ?>
    </div>


    <div class="card-deck">
        <?php
          if(in_array(51,$reportes)){
        ?>
            <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Ventas por cajas/cajeros
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <form action="/reporte51" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Visualizar</button>
                    </form>
            </div>
            </div>
        <?php
            }
        ?>

        <?php
        if(in_array(52,$reportes)){
        ?>
        <div class="card border-success mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-success">
            <h5 class="card-title">
                <span class="card-text text-white">
                    Lotes a la baja
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-success text-right">
            <form action="/reporte52" style="display: inline;">
                @csrf
                <input id="SEDE" name="SEDE" type="hidden" value="<?php print_r($_GET['SEDE']); ?>">
                <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Visualizar</button>
                </form>
        </div>
        </div>
        <?php
        }
        ?>
    </div>


    </div>
<!-------------------------------------------------------------------------------->
@endsection
