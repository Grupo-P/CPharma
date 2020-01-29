@extends('layouts.model')

@section('title')
  Dashboard
@endsection

@section('content')
  <?php
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');

    $CPharmaVersion = ' CPharma v.6.1';

    //-------------------- VARIABLES COMPRAS --------------------//
    $empresas = DB::table('empresas')->count();
    $proveedores = DB::table('proveedors')->count();
    $usuarios = DB::table('users')->count();
    $dolar = DB::table('dolars')->count();

    //-------------------- VARIABLES RRHH --------------------//
    $candidatos = DB::table('rh_candidatos')->count();
    $vacantes = DB::table('rh_vacantes')->count();
    $entrevistas = DB::table('rh_entrevistas')->count();
    $pruebas = DB::table('rh_pruebas')->count();
    $entrevistas = DB::table('rh_entrevistas')->count();
    $examenesm = DB::table('rh_examenes')->count();
    $empresaReferencias = DB::table('rh_empresaRef')->count();
    $laboratorios = DB::table('rh_laboratorio')->count();
    $contactos = DB::table('rh_contactos_empresas')->count();
    $convocatoria = DB::table('rh_convocatoria')->count();
    $fases = DB::table('rh_fases')->count();
    $procesos_candidatos = DB::table('rh_candidatos')
    	->where('estatus', '<>', 'RECHAZADO')
    	->where('estatus', '<>', 'CONTRATADO')
    	->where('estatus', '<>', 'FUTURO')
    	->count();
    $practicas = DB::table('rh_practicas')->count();

    //-------------------- VARIABLES TESORERIA --------------------//
    $movimientosBs = DB::table('ts_movimientos')
    ->where('tasa_ventas_id', 1)
    ->whereNull('diferido')
    ->count();

    $diferidosBs = DB::table('ts_movimientos')
    ->where('tasa_ventas_id', 1)
    ->whereNotNull('diferido')
    ->count();

    $movimientosDs = DB::table('ts_movimientos')
    ->where('tasa_ventas_id', 2)
    ->whereNull('diferido')
    ->count();

    $diferidosDs = DB::table('ts_movimientos')
    ->where('tasa_ventas_id', 2)
    ->whereNotNull('diferido')
    ->count();

  /*TASA DOLAR VENTA*/
    $Tasa = DB::table('tasa_ventas')->where('moneda', 'Dolar')->value('tasa');

    if( (!empty($Tasa)) ) {
      $Tasa = number_format($Tasa,2,"," ,"." );
    }
    else {
      $Tasa = number_format(0.00,2,"," ,"." );
    }

    $tasaVenta = DB::table('tasa_ventas')->where('moneda', 'Dolar')->value('updated_at');

    if( (!empty($tasaVenta)) ) {
      $tasaVenta = new DateTime($tasaVenta);
      $tasaVenta = $tasaVenta->format("d-m-Y h:i:s a");
    }
    else {
      $tasaVenta = '';
    }
  /*TASA DOLAR VENTA*/

  /*TASA DOLAR MERCADO*/
    $FechaTasaMercado = 
    DB::table('dolars')
    ->select('updated_at')
    ->orderBy('fecha','desc')
    ->take(1)->get();

    if( (!empty($FechaTasaMercado[0])) ) {
      $FechaTasaMercado = ($FechaTasaMercado[0]->updated_at);
      $FechaTasaMercado = new DateTime($FechaTasaMercado);
      $FechaTasaMercado = $FechaTasaMercado->format('d-m-Y h:i:s a');
    }
    else {
      $FechaTasaMercado = '';
    }

    $TasaMercado = 
    DB::table('dolars')
    ->select('tasa')
    ->orderBy('fecha','desc')
    ->take(1)->get();

    if( (!empty($TasaMercado[0])) ) {
      $TasaMercado = ($TasaMercado[0]->tasa);
      $TasaMercado = number_format($TasaMercado,2,"," ,"." );
    }
    else {
      $TasaMercado = number_format(0.00,2,"," ,"." );
    }
  /*TASA DOLAR MERCADO*/

  /*REPORTES MAS USADOS Y REPORTES SUGERIDOS*/
    $FHoy = date("Y-m-d");
    $FAyer = date("Y-m-d",strtotime($FHoy."-1 days"));

    $auditorReporte = 
    DB::table('auditorias')
    ->select('registro')
    ->groupBy('registro')
    ->orderBy(DB::raw('count(*)'),'desc')
    ->where('tabla','reporte')
    ->where('updated_at', '>',$FAyer)
    ->take(1)->get();

    $auditorUser = 
    DB::table('auditorias')
    ->select('user')
    ->groupBy('user')
    ->orderBy(DB::raw('count(*)'),'desc')
    ->where('updated_at', '>',$FAyer)
    ->take(1)->get();

    $usuario = Auth::user()->name;
    $auditorReporteFavorito = 
    DB::table('auditorias')
    ->select('registro')
    ->groupBy('registro')
    ->orderBy(DB::raw('count(*)'),'desc')
    ->where('tabla','reporte')
    ->where('user',$usuario)
    ->take(2)->get();
  /*REPORTES MAS USADOS Y REPORTES SUGERIDOS*/
  ?>

  <h1 class="h5 text-info">
    <i class="fas fa-columns"></i>
    Dashboard
  </h1>
  <hr class="row align-items-start col-12">

<?php
	if((Auth::user()->departamento != 'VENTAS')&&(Auth::user()->departamento != 'RRHH')&&(Auth::user()->departamento != 'TESORERIA')){
?>
<!-------------------------------------------------------------------------------->
<!-- DESTACADOS -->
<?php
 	if( (!empty($auditorReporte[0])) && (!empty($auditorUser[0])) ){
 		$auditorReporte = $auditorReporte[0];
  	$auditoriasReporte = $auditorReporte->registro;
  	$auditorUser = $auditorUser[0];
  	$usuarioAct = $auditorUser->user;
  	echo'
		<div class="card-deck">
			<div class="card CP-border-golden mb-3" style="width: 14rem;">	  	
  			<div class="card-body text-left CP-bg-golden">
	    		<h4>
		    		<span class="card-text text-white">
		    			<i class="fas fa-star CP-Latir"></i>
		    			'.$auditoriasReporte.'			
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
		    			'.$usuarioAct.'	
		    		</span>
	    		</h4>
	    		<p class="card-text text-white">Usuario mas activo</p>
	  		</div>	
			</div>
		</div>
  	';
  }
 	else if( (!empty($auditorReporte[0])) && (empty($auditorUser[0])) ){
 		$auditorReporte = $auditorReporte[0];
  	$auditoriasReporte = $auditorReporte->registro;
  	echo'
		<div class="card-deck">
			<div class="card CP-border-golden mb-3" style="width: 14rem;">	  	
  			<div class="card-body text-left CP-bg-golden">
	    		<h4>
		    		<span class="card-text text-white">
		    			<i class="fas fa-star CP-Latir"></i>
		    			'.$auditoriasReporte.'			
		    		</span>
	    		</h4>
    			<p class="card-text text-white">Reporte mas usado</p>
  			</div>	
			</div>
		</div>
  	';
 	}
 	else if( (empty($auditorReporte[0])) && (!empty($auditorUser[0])) ){
  	$auditorUser = $auditorUser[0];
  	$usuarioAct = $auditorUser->user;
  	echo'
		<div class="card-deck">
			<div class="card CP-border-golden mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left CP-bg-golden">
	    		<h4>
		    		<span class="card-text text-white">
		    			<i class="fas fa-star CP-Latir"></i>
		    			'.$usuarioAct.'	
		    		</span>
	    		</h4>
	    		<p class="card-text text-white">Usuario mas activo</p>
	  		</div>	
			</div>
		</div>
  	';
  }

  if( (!empty($auditorReporteFavorito[0])) && (!empty($auditorReporteFavorito[1])) ){
  	$reporteFavorito1 = $auditorReporteFavorito[0];
  	$reporteFavorito1 = $reporteFavorito1->registro;
  	$Ruta1 = FG_Ruta_Reporte($reporteFavorito1);

  	$reporteFavorito2 = $auditorReporteFavorito[1];
  	$reporteFavorito2 = $reporteFavorito2->registro;
  	$Ruta2 = FG_Ruta_Reporte($reporteFavorito2);

  	echo'
		<div class="card-deck">
			<div class="card border-secondary mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-secondary">
	    		<h4>
		    		<span class="card-text text-white">
		    			<i class="fa fa-thumbtack"></i>
		    			'.$reporteFavorito1.'
		    		</span>
	    		</h4>
	    		<p class="card-text text-white">Reporte sugerido</p>
	  		</div>
	  		<div class="card-footer bg-transparent border-secondary text-right">
		  		<form action="'.$Ruta1.'" style="display: inline;">
				    <input id="SEDE" name="SEDE" type="hidden" value="'.FG_Mi_Ubicacion().'">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
					</form>
	  		</div>
	  	</div>
	  	<div class="card border-secondary mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-secondary">
	    		<h4>
		    		<span class="card-text text-white">
		    			<i class="fa fa-thumbtack"></i>
		    			'.$reporteFavorito2.'
		    		</span>
	    		</h4>
	    		<p class="card-text text-white">Reporte sugerido</p>
	  		</div>
	  		<div class="card-footer bg-transparent border-secondary text-right">
		  		<form action="'.$Ruta2.'" style="display: inline;">
				    <input id="SEDE" name="SEDE" type="hidden" value="'.FG_Mi_Ubicacion().'">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
					</form>
	  		</div>
	  	</div>
		</div>
		<hr class="row align-items-start col-12">
  	';
  }
  else  if( (!empty($auditorReporteFavorito[0])) && (empty($auditorReporteFavorito[1])) ){
  	$reporteFavorito1 = $auditorReporteFavorito[0];
  	$reporteFavorito1 = $reporteFavorito1->registro;
  	$Ruta1 = FG_Ruta_Reporte($reporteFavorito1);

  	echo'
		<div class="card-deck">
			<div class="card border-secondary mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-secondary">
	    		<h4>
		    		<span class="card-text text-white">
		    			<i class="fa fa-thumbtack"></i>
		    			'.$reporteFavorito1.'
		    		</span>
	    		</h4>
	    		<p class="card-text text-white">Reporte sugerido</p>
	  		</div>
	  		<div class="card-footer bg-transparent border-secondary text-right">
		  		<form action="'.$Ruta1.'" style="display: inline;">
				    <input id="SEDE" name="SEDE" type="hidden" value="'.FG_Mi_Ubicacion().'">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
					</form>
	  		</div>
	  	</div>
		</div>
		<hr class="row align-items-start col-12">
  	';
  }
  else  if( (empty($auditorReporteFavorito[0])) && (!empty($auditorReporteFavorito[1])) ){
  	$reporteFavorito2 = $auditorReporteFavorito[1];
  	$reporteFavorito2 = $reporteFavorito2->registro;
  	$Ruta2 = FG_Ruta_Reporte($reporteFavorito2);

  	echo'
		<div class="card-deck">
	  	<div class="card border-secondary mb-3" style="width: 14rem;">	  	
	  		<div class="card-body text-left bg-secondary">
	    		<h4>
		    		<span class="card-text text-white">
		    			<i class="fa fa-thumbtack"></i>
		    			'.$reporteFavorito2.'
		    		</span>
	    		</h4>
	    		<p class="card-text text-white">Reporte sugerido</p>
	  		</div>
	  		<div class="card-footer bg-transparent border-secondary text-right">
		  		<form action="'.$Ruta2.'" style="display: inline;">
				    <input id="SEDE" name="SEDE" type="hidden" value="'.FG_Mi_Ubicacion().'">
				    <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
					</form>
	  		</div>
	  	</div>
		</div>
		<hr class="row align-items-start col-12">
  	';
  }
?>
<!-- DESTACADOS -->
<!-------------------------------------------------------------------------------->
<!-- AGENDA -->
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
		    			<?php
		    				echo''.FG_Reportes_Departamento(Auth::user()->departamento);
		    			?>  			
		    		</span>
	    		</h2>
	    		<p class="card-text text-white">Reportes disponibles</p>
	  		</div>
		  	<div class="card-footer bg-transparent border-info text-right">
		  		<a href="/sedes_reporte/" class="btn btn-outline-info btn-sm">Visualizar</a>
		  	</div>
		</div>
	</div>
<?php
  }
?>
<!-- AGENDA -->
<!-------------------------------------------------------------------------------->
<!-- COMPRAS -->
<?php
  if(Auth::user()->departamento == 'COMPRAS'){
?>
	<!-- Modal COMPRAS -->
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
	      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
	      	<br/>
	      	Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el reporte: 
					<b class="text-info">Consultor de Precios</b>!!
  				</li>
  			</ul>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
	      </div>
	    </div>
  	</div>
	</div>
	<!-- Modal COMPRAS -->
	
	<!-- Dashboard Articulo Estrella-->
	<div class="card-deck">
		<div class="card border-secondary mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-light">
    		<h3 class="card-title">
	    		<span class="card-text text-dark">
	    			<i class="fas fa-credit-card"></i>
	    			Articulos Estrella					
	    		</span>
    		</h3>
    		<p class="card-text text-dark">
    			<?php
						FG_Acticulos_Estrella_Top(FG_Mi_Ubicacion());
					?>
    		</p>
  		</div>
	  	<div class="card-footer bg-transparent border-secondary text-right">
	  		<a href="/reporte16/" class="btn btn-outline-secondary btn-sm">Visualizar</a>
	  	</div>
		</div>	
	</div>
	<!-- Dashboard Articulo Estrella -->

	<!-- Dashboard COMPRAS-->
	<div class="card-deck">
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
		</div>
	</div>
	<!-- Dashboard COMPRAS-->
<?php
  }
?>
<!-- COMPRAS -->
<!-------------------------------------------------------------------------------->
<!-- OPERACIONES -->
<?php
  if(Auth::user()->departamento == 'OPERACIONES'){
?>
	<!-- Modal OPERACIONES -->
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
	      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
	      	<br/>
	      	Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el reporte: 
					<b class="text-info">Reporte de Atributos</b>!!
  				</li>
  			</ul>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el modulo: 
					<b class="text-info">Fecha de Vencimiento</b>!!
  				</li>
  			</ul>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
	      </div>
	    </div>
  	</div>
	</div>
	<!-- Modal OPERACIONES -->
	<!-- Dashboard OPERACIONES-->
	<div class="card-deck">
	<!-- Tasa Mercado -->
		<div class="card border-secondary mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-secondary">
	    		<h3 class="card-title">
		    		<span class="card-text text-white">
		    			<i class="fas fa-credit-card"></i>
		    			<?php
								echo 'Tasa Mercado: '.$TasaMercado.' '.SigVe;
							?>						
		    		</span>
	    		</h3>
	    		<p class="card-text text-white">
					<?php 
						echo 'Ultima Actualizacion: '.$FechaTasaMercado;
					?>
	    		</p>
  		</div>
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
		</div>
	</div>
	<!-- Dashboard OPERACIONES-->
<?php
  }
?>
<!-- OPERACIONES -->
<!-------------------------------------------------------------------------------->
<!-- ALMACEN -->
<?php
  if(Auth::user()->departamento == 'ALMACEN'){
?>
	<!-- Modal ALMACEN -->
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
	      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
	      	<br/>
	      	Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el modulo: 
					<b class="text-info">Traslado</b>!!
  				</li>
  			</ul>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Se actualizaron las busquedas por 
					<b class="text-info">Codigo de Barra</b>!!
  				</li>
  			</ul>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
	      </div>
	    </div>
  	</div>
	</div>
	<!-- Modal ALMACEN -->
	<!-- Dashboard ALMACEN-->
	<div class="card-deck">
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
		</div>
	</div>
	<!-- Dashboard ALMACEN-->
<?php
  }
?>
<!-- ALMACEN -->
<!-------------------------------------------------------------------------------->
<!-- DEVOLUCIONES -->
<?php
  if(Auth::user()->departamento == 'DEVOLUCIONES'){
?>
	<!-- Modal DEVOLUCIONES -->
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
	      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
	      	<br/>
	      	Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el modulo: 
					<b class="text-info">Orden de compra</b>!!
  				</li>
  			</ul>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
	      </div>
	    </div>
  	</div>
	</div>
	<!-- Modal DEVOLUCIONES -->
	<!-- Dashboard DEVOLUCIONES-->
	<div class="card-deck">
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
		</div>
	</div>
	<!-- Dashboard DEVOLUCIONES-->
<?php
  }
?>
<!-- DEVOLUCIONES -->
<!-------------------------------------------------------------------------------->
<!-- SURTIDO -->
<?php
  if(Auth::user()->departamento == 'SURTIDO'){
?>
	<!-- Modal SURTIDO -->
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
	      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
	      	<br/>
	      	Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el modulo: 
					<b class="text-info">Etiquetas</b>!!
  				</li>
  			</ul>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Se actualizaron las busquedas por 
					<b class="text-info">Codigo de Barra</b>!!
  				</li>
  			</ul>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
	      </div>
	    </div>
  	</div>
	</div>
	<!-- Modal SURTIDO -->
	<!-- Dashboard SURTIDO-->
	<div class="card-deck">
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
		</div>
	</div>
	<!-- Dashboard SURTIDO-->
<?php
  }
?>
<!-- SURTIDO -->
<!-------------------------------------------------------------------------------->
<!-- VENTAS -->
<?php
  if(Auth::user()->departamento == 'VENTAS'){
?>
	<!-- Modal VENTAS -->
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
	      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
	      	<br/>
	      	Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
	      </div>
	    </div>
  	</div>
	</div>
	<!-- Modal VENTAS -->
	<!-- Dashboard VENTAS-->
	<div class="card-deck">
	<!-- Tasa Venta -->
		<div class="card border-success mb-3" style="width: 14rem;">	  	
			<div class="card-body text-left bg-success">
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
			<div class="card-footer bg-transparent border-success text-right">
	  		<a href="/tasaVenta/" class="btn btn-outline-success btn-sm">Visualizar</a>
	  	</div>		
		</div>
	</div>
	<!-- Dashboard VENTAS-->
<?php
  }
?>
<!-- VENTAS -->
<!-------------------------------------------------------------------------------->
<!-- TESORERIA -->
<?php
  if(Auth::user()->departamento == 'TESORERIA'){
?>
	<!-- Modal TESORERIA -->
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
	      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
	      	<br/>
	      	Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya estan disponibles los modulos: 
					<b class="text-info">
            <ul>
  						<li>Movimientos en bolivares</li>
  						<li>Movimientos en dolares</li>
  						<li>Diferidos en bolivares</li>
  						<li>Diferidos en dolares</li>
            </ul>
          </b>
  				</li>
  			</ul>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
	      </div>
	    </div>
  	</div>
	</div>
	<!-- Modal TESORERIA -->

	<!-- Dashboard TESORERIA-->
	<div class="card-deck">
    <div class="card border-danger mb-3" style="width: 14rem;">      
      <div class="card-body text-left bg-danger">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-balance-scale-left"></i>
            <?php
            $saldo_actualBs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 1)
              ->whereNull('diferido')
              ->orderBy('id', 'desc')
              ->first();
            
            echo 'Saldo disponible: '. number_format(DB::table('configuracions')
            ->where('id', 7)
            ->value('valor'), 2, ',', '.') . " " . SigVe;
          ?>            
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          if(empty($saldo_actualBs)) {
            $ultimoMovimientoBs = '';
          }
          else {
            $ultimoMovimientoBs = $saldo_actualBs->updated_at;
          }

          echo 'Movimientos en bolivares registrados: ' . $movimientosBs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo movimiento: ' . date("d-m-Y h:i:s a", strtotime($ultimoMovimientoBs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-danger text-right">
        <a href="/movimientos?tasa_ventas_id=1" class="btn btn-outline-danger btn-sm">Visualizar</a>
      </div>    
    </div>

    <div class="card border-success mb-3" style="width: 14rem;">      
      <div class="card-body text-left bg-success">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-balance-scale"></i>
            <?php	
            $saldo_actualDs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 2)
              ->whereNull('diferido')
              ->orderBy('id', 'desc')
              ->first();

              echo 'Saldo disponible: ' . number_format(DB::table('configuracions')
            ->where('id', 8)
            ->value('valor'), 2, ',', '.') . " " . SigDolar;
          ?>            
          </span>
        </h3>
        <p class="card-text text-white">
        <?php 
          if(empty($saldo_actualDs)) {
            $ultimoMovimientoDs = '';
          }
          else {
            $ultimoMovimientoDs = $saldo_actualDs->updated_at;
          }

          echo 'Movimientos en dolares registrados: ' . $movimientosDs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo movimiento: ' . date("d-m-Y h:i:s a", strtotime($ultimoMovimientoDs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-success text-right">
        <a href="/movimientos?tasa_ventas_id=2" class="btn btn-outline-success btn-sm">Visualizar</a>
      </div>    
    </div>

    <!-- Tasa Venta -->
		<div class="card border-info mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-info">
    		<h3 class="card-title">
	    		<span class="card-text text-white">
	    			<i class="fas fa-credit-card"></i>
	    			<?php
							echo 'Tasa Venta: '.$Tasa.' '.SigVe;
						?>						
	    		</span>
    		</h3>
    		<p class="card-text text-white">
				<?php 
					echo 'Ultima Actualizacion: '.$tasaVenta;
				?>
    		</p>
  		</div>
	  	<div class="card-footer bg-transparent border-info text-right">
	  		<a href="/tasaVenta/" class="btn btn-outline-info btn-sm">Visualizar</a>
	  	</div>
		</div>
  </div>

  <!-- DIFERIDOS -->
  <div class="card-deck">
    <div class="card border-warning mb-3" style="width: 14rem;">      
      <div class="card-body text-left bg-warning">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-lock"></i>
            <?php
            $diferido_actualBs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 1)
              ->whereNotNull('diferido')
              ->orderBy('updated_at', 'desc')
              ->first();
            
            if(empty($diferido_actualBs)) {
                echo 'Diferido actual: '. number_format(0, 2, ',', '.') . " " . SigVe;
              }
              else {
                echo 'Diferido actual: '. number_format($diferido_actualBs->diferido_actual, 2, ',', '.') . " " . SigVe;
              }
          ?>            
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          if(empty($diferido_actualBs)) {
            $ultimoDiferidoBs = '';
          }
          else {
            $ultimoDiferidoBs = $diferido_actualBs->updated_at;
          }

          echo 'Diferidos en bolivares registrados: ' . $diferidosBs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo diferido: ' . date("d-m-Y h:i:s a", strtotime($ultimoDiferidoBs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-warning text-right">
        <a href="/diferidos?tasa_ventas_id=1" class="btn btn-outline-warning btn-sm">Visualizar</a>
      </div>    
    </div>

    <div class="card border-secondary mb-3" style="width: 14rem;">      
      <div class="card-body text-left bg-secondary">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-lock"></i>
            <?php 
            $diferido_actualDs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 2)
              ->whereNotNull('diferido')
              ->orderBy('updated_at', 'desc')
              ->first();

              if(empty($diferido_actualDs)) {
                echo 'Diferido actual: ' . number_format(0, 2, ',', '.') . " " . SigDolar;
              }
              else {
                echo 'Diferido actual: ' . number_format($diferido_actualDs->diferido_actual, 2, ',', '.') . " " . SigDolar;
              }
          ?>            
          </span>
        </h3>
        <p class="card-text text-white">
        <?php 
          if(empty($diferido_actualDs)) {
            $ultimoDiferidoDs = '';
          }
          else {
            $ultimoDiferidoDs = $diferido_actualDs->updated_at;
          }

          echo 'Diferidos en dolares registrados: ' . $diferidosDs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo diferido: ' . date("d-m-Y h:i:s a", strtotime($ultimoDiferidoDs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-secondary text-right">
        <a href="/diferidos?tasa_ventas_id=2" class="btn btn-outline-secondary btn-sm">Visualizar</a>
      </div>    
    </div>
  </div>
	<!-- Dashboard TESORERIA-->
<?php
  }
?>
<!-- TESORERIA -->
<!-------------------------------------------------------------------------------->
<!-- RRHH -->
<?php
  if(Auth::user()->departamento == 'RRHH'){
?>
	<!-- Modal RRHH -->
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
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b><br/><br/>
	      	<ul style="list-style:none">
            <li class="card-text text-dark" style="display: inline;">
              <i class="far fa-check-circle text-info" style="display: inline;"></i>
              Desde ya esta disponible el modulo: 
              <b class="text-info">Candidatos</b>!!
            </li>
          </ul>
  				<ul style="list-style:none">
            <li class="card-text text-dark" style="display: inline;">
              <i class="far fa-check-circle text-info" style="display: inline;"></i>
              Desde ya esta disponible el modulo: 
              <b class="text-info">Pruebas</b>!!
            </li>
          </ul>
        </div>
        <div class="modal-footer">
	        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
	      </div>
	    </div>
  	</div>
	</div>
	<!-- Modal RRHH -->
	<!-- Dashboard Metricas-->
	<div class="card-deck">
		<!-- Pie Chart FTN-->
    <div class="col-xl-3 col-lg-5">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-info">FARMACIA TIERRA NEGRA, C.A</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="chart-pie pt-4 pb-2">
            <canvas id="ChartFTN"></canvas>
          </div>
          <div class="mt-4 text-center small">
            <span class="mr-2">
              <i class="fas fa-circle text-primary"></i> Activos:
              <label id="ActFTN"></label>
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-success"></i> Ingresos:
              <label id="IngFTN"></label>
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-danger"></i> Egresos:
              <label id="EgrFTN"></label>
            </span>
          </div>
          <span class="mt-4 text-center small">Desde el <label id="FInicioFTN"></label> hasta el <label id="FFinFTN"></label></span>
        </div>
      </div>
    </div>
    <!-- Pie Chart FLL-->
    <div class="col-xl-3 col-lg-5">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-info">FARMACIA LA LAGO, C.A</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="chart-pie pt-4 pb-2">
            <canvas id="ChartFLL"></canvas>
          </div>
          <div class="mt-4 text-center small">
            <span class="mr-2">
              <i class="fas fa-circle text-primary"></i> Activos:
              <label id="ActFLL"></label>
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-success"></i> Ingresos:
              <label id="IngFLL"></label>
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-danger"></i> Egresos:
              <label id="EgrFLL"></label>
            </span>
          </div>
          <span class="mt-4 text-center small">Desde el <label id="FInicioFLL"></label> hasta el <label id="FFinFLL"></label></span>
        </div>
      </div>
    </div>
    <!-- Pie Chart FAU-->
    <div class="col-xl-3 col-lg-5">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-info">FARMACIA AV. UNIVERSIDAD, C.A</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="chart-pie pt-4 pb-2">
            <canvas id="ChartFAU"></canvas>
          </div>
          <div class="mt-4 text-center small">
            <span class="mr-2">
              <i class="fas fa-circle text-primary"></i> Activos:
              <label id="ActFAU"></label>
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-success"></i> Ingresos:
              <label id="IngFAU"></label>
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-danger"></i> Egresos:
              <label id="EgrFAU"></label>
            </span>
          </div>
          <span class="mt-4 text-center small">Desde el <label id="FInicioFAU"></label> hasta el <label id="FFinFAU"></label></span>
        </div>
      </div>
    </div>
    <!-- Pie Chart MC-->
    <div class="col-xl-3 col-lg-5">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-info">MULATA CAFE, C.A.</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="chart-pie pt-4 pb-2">
            <canvas id="ChartMC"></canvas>
          </div>
          <div class="mt-4 text-center small">
            <span class="mr-2">
              <i class="fas fa-circle text-primary"></i> Activos:
              <label id="ActMC"></label>
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-success"></i> Ingresos:
              <label id="IngMC"></label>
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-danger"></i> Egresos:
              <label id="EgrMC"></label>
            </span>
          </div>
          <span class="mt-4 text-center small">Desde el <label id="FInicioMC"></label> hasta el <label id="FFinMC"></label></span>
        </div>
      </div>
    </div>
	</div>
	<!-- Dashboard Metricas-->
	<!-- Dashboard RRHH-->
	<div class="card-deck">
    <div class="card border-dark mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-dark">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-cogs"></i>
            <?php
            echo ''.$procesos_candidatos;
          ?>            
          </span>
        </h2>
        <p class="card-text text-white">
        <?php 
          echo 'Fases y procesos en tránsito';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-dark text-right">
        <a href="/procesos_candidatos" class="btn btn-outline-dark btn-sm">Visualizar</a>
      </div>    
    </div>
  </div>

	<div class="card-deck">
    <div class="card border-danger mb-3" style="width: 14rem;">      
      <div class="card-body text-left bg-danger">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-user-plus"></i>
            <?php
            echo ''.$vacantes;
          ?>            
          </span>
        </h3>
        <p class="card-text text-white">
        <?php 
          echo 'Vacantes registradas';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-danger text-right">
        <a href="/vacantes" class="btn btn-outline-danger btn-sm">Visualizar</a>
      </div>    
    </div>

    <div class="card border-success mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-success">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-user-check"></i>
            <?php
            echo ''.$candidatos;
          ?>            
          </span>
        </h2>
        <p class="card-text text-white">
        <?php 
          echo 'Candidatos registrados';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-success text-right">
        <a href="/candidatos" class="btn btn-outline-success btn-sm">Visualizar</a>
      </div>    
    </div>

    <div class="card border-info mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-info">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-sort-amount-up-alt"></i>
            <?php
            echo ''.$fases;
          ?>            
          </span>
        </h2>
        <p class="card-text text-white">
        <?php 
          echo 'Fases registradas';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-info text-right">
        <a href="/fases" class="btn btn-outline-info btn-sm">Visualizar</a>
      </div>    
    </div>
  </div>

  <div class="card-deck">
    <div class="card border-warning mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-warning">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-tasks"></i>
            <?php
            echo ''.$pruebas;
          ?>            
          </span>
        </h2>
        <p class="card-text text-white">
        <?php 
          echo 'Pruebas registradas';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-warning text-right">
        <a href="/pruebas" class="btn btn-outline-warning btn-sm">Visualizar</a>
      </div>    
    </div>

    <div class="card border-secondary mb-3" style="width: 14rem;">      
      <div class="card-body text-left bg-secondary">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-vials"></i>
            <?php
            echo ''.$laboratorios;
          ?>            
          </span>
        </h3>
        <p class="card-text text-white">
        <?php 
          echo 'Laboratorios registrados';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-secondary text-right">
        <a href="/laboratorio" class="btn btn-outline-secondary btn-sm">Visualizar</a>
      </div>    
    </div>

    <div class="card border-dark mb-3" style="width: 14rem;">      
      <div class="card-body text-left bg-dark">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-user-edit"></i>
            <?php
            echo ''.$convocatoria;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php 
          echo 'Convocatorias registradas';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-dark text-right">
        <a href="/convocatoria" class="btn btn-outline-dark btn-sm">Visualizar</a>
      </div>    
    </div>
  </div>

  <div class="card-deck">
    <div class="card border-danger mb-3" style="width: 14rem;">      
      <div class="card-body text-left bg-danger">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-users"></i>
            <?php
            echo ''.$entrevistas;
          ?>            
          </span>
        </h3>
        <p class="card-text text-white">
        <?php 
          echo 'Entrevistas registradas';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-danger text-right">
        <a href="/entrevistas" class="btn btn-outline-danger btn-sm">Visualizar</a>
      </div>    
    </div>

    <div class="card border-success mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-success">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-users-cog"></i>
            <?php
            echo ''.$practicas;
          ?>            
          </span>
        </h2>
        <p class="card-text text-white">
        <?php 
          echo 'Prácticas registradas';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-success text-right">
        <a href="/practicas" class="btn btn-outline-success btn-sm">Visualizar</a>
      </div>    
    </div>

    <div class="card border-info mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-info">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="far fa-address-card"></i>
            <?php
            echo ''.$empresaReferencias;
          ?>            
          </span>
        </h2>
        <p class="card-text text-white">
        <?php 
          echo 'Empresas de referencias laborales registradas';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-info text-right">
        <a href="/empresaReferencias" class="btn btn-outline-info btn-sm">Visualizar</a>
      </div>    
    </div>
  </div>

  <div class="card-deck">
  	<div class="card border-warning mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-warning">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-phone"></i>
            <?php
            echo ''.$contactos;
          ?>            
          </span>
        </h2>
        <p class="card-text text-white">
        <?php 
          echo 'Contactos de empresas registrados';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-warning text-right">
        <a href="/contactos" class="btn btn-outline-warning btn-sm">Visualizar</a>
      </div>    
    </div>

    <div class="card border-secondary mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-secondary">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-user-md"></i>
            <?php
            echo ''.$examenesm;
          ?>            
          </span>
        </h2>
        <p class="card-text text-white">
        <?php 
          echo 'Examenes médicos registrados';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-secondary text-right">
        <a href="/examenesm" class="btn btn-outline-secondary btn-sm">Visualizar</a>
      </div>    
    </div>
  </div>
	<!-- Dashboard RRHH-->
<?php
  }
?>
<!-- RRHH -->
<!-------------------------------------------------------------------------------->
<!-- RECEPCION -->
<?php
  if(Auth::user()->departamento == 'RECEPCION'){
?>
	<!-- Modal RECEPCION -->
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
	      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
	      	<br/>
	      	Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el modulo: 
					<b class="text-info">Orden de compra</b>!!
  				</li>
  			</ul>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
	      </div>
	    </div>
  	</div>
	</div>
	<!-- Modal RECEPCION -->
	<!-- Dashboard RECEPCION-->
	<!-- Dashboard RECEPCION-->
<?php
  }
?>
<!-- RECEPCION -->
<!-------------------------------------------------------------------------------->
<!-- ADMINISTRACION -->
<?php
  if(Auth::user()->departamento == 'ADMINISTRACION'){
?>
	<!-- Modal ADMINISTRACION -->
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
	      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
	      	<br/>
	      	Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el modulo: 
					<b class="text-info">Orden de compra</b>!!
  				</li>
  			</ul>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
	      </div>
	    </div>
  	</div>
	</div>
	<!-- Modal ADMINISTRACION -->
	<!-- Dashboard ADMINISTRACION-->
	<div class="card-deck">
    <div class="card border-warning mb-3" style="width: 14rem;">      
      <div class="card-body text-left bg-warning">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-balance-scale-left"></i>
            <?php
            $saldo_actualBs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 1)
              ->orderBy('id', 'desc')
              ->first();
            
            if(empty($saldo_actualBs)) {
                echo 'Saldo actual: '. number_format(0, 2, ',', '.') . " " . SigVe;
              }
              else {
                echo 'Saldo actual: '. number_format($saldo_actualBs->saldo_actual, 2, ',', '.') . " " . SigVe;
              }
          ?>            
          </span>
        </h3>
        <p class="card-text text-white">
        <?php 
          echo 'Movimientos en bolivares registrados: ' . $movimientosBs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a", time());
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-warning text-right">
        <a href="/movimientos?tasa_ventas_id=1" class="btn btn-outline-warning btn-sm">Visualizar</a>
      </div>    
    </div>

    <div class="card border-secondary mb-3" style="width: 14rem;">      
      <div class="card-body text-left bg-secondary">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-balance-scale"></i>
            <?php 
            $saldo_actualDs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 2)
              ->orderBy('id', 'desc')
              ->first();

              if(empty($saldo_actualDs)) {
                echo 'Saldo actual: ' . number_format(0, 2, ',', '.') . " " . SigDolar;
              }
              else {
                echo 'Saldo actual: ' . number_format($saldo_actualDs->saldo_actual, 2, ',', '.') . " " . SigDolar;
              }
          ?>            
          </span>
        </h3>
        <p class="card-text text-white">
        <?php 
          echo 'Movimientos en dolares registrados: ' . $movimientosDs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a", time());
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-secondary text-right">
        <a href="/movimientos?tasa_ventas_id=2" class="btn btn-outline-secondary btn-sm">Visualizar</a>
      </div>    
    </div>

		<div class="card border-dark mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-dark">
	    		<h3 class="card-title">
		    		<span class="card-text text-white">
		    			<i class="fas fa-credit-card"></i>
		    			<?php
								echo 'Tasa Mercado: '.$TasaMercado.' '.SigVe;
							?>						
		    		</span>
	    		</h3>
	    		<p class="card-text text-white">
					<?php 
						echo 'Ultima Actualizacion: '.$FechaTasaMercado;
					?>
	    		</p>
  		</div>
	  	<div class="card-footer bg-transparent border-dark text-right">
	  		<a href="/dolar/" class="btn btn-outline-dark btn-sm">Visualizar</a>
	  	</div>	
		</div>
	</div>
	<!-- Dashboard ADMINISTRACION-->
<?php
  }
?>
<!-- ADMINISTRACION -->
<!-------------------------------------------------------------------------------->
<!-- LÍDER DE TIENDA -->
<?php
  if(Auth::user()->departamento == 'LÍDER DE TIENDA'){
?>
	<!-- Modal LÍDER DE TIENDA -->
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
	      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
	      	<br/>
	      	Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el reporte: 
					<b class="text-info">Articulos Estrella</b>!!
  				</li>
  			</ul>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
	      </div>
	    </div>
  	</div>
	</div>
	<!-- Modal LÍDER DE TIENDA -->
	<!-- Dashboard LÍDER DE TIENDA-->
	<div class="card-deck">
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
		</div>
	</div>
	<!-- Dashboard LÍDER DE TIENDA-->
<?php
  }
?>
<!-- LÍDER DE TIENDA -->
<!-------------------------------------------------------------------------------->
<!-- GERENCIA -->
<?php
  if(Auth::user()->departamento == 'GERENCIA'){
?>
	<!-- Modal GERENCIA -->
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
	      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
	      	<br/>
	      	Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el reporte: 
					<b class="text-info">Consultor de Precios</b>!!
  				</li>
  			</ul>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el reporte: 
					<b class="text-info">Reporte de Atributos</b>!!
  				</li>
  			</ul>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el modulo: 
					<b class="text-info">Fecha de Vencimiento</b>!!
  				</li>
  			</ul>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
	      </div>
	    </div>
  	</div>
	</div>
	<!-- Modal GERENCIA -->
	<!-- Dashboard GERENCIA-->
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
	  	<div class="card-footer bg-transparent border-warning text-right">
	  		<a href="/usuario/" class="btn btn-outline-warning btn-sm">Visualizar</a>
	  	</div>
		</div>
		<!-- Dolar -->
		<div class="card border-secondary mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-secondary">
	    		<h3 class="card-title">
		    		<span class="card-text text-white">
		    			<i class="fas fa-credit-card"></i>
		    			<?php
								echo 'Tasa Mercado: '.$TasaMercado.' '.SigVe;
							?>						
		    		</span>
	    		</h3>
	    		<p class="card-text text-white">
					<?php 
						echo 'Ultima Actualizacion: '.$FechaTasaMercado;
					?>
	    		</p>
  		</div>
	  	<div class="card-footer bg-transparent border-secondary text-right">
	  		<a href="/dolar/" class="btn btn-outline-secondary btn-sm">Visualizar</a>
	  	</div>	
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
	  	<div class="card-footer bg-transparent border-dark text-right">
	  		<a href="/tasaVenta/" class="btn btn-outline-dark btn-sm">Visualizar</a>
	  	</div>
		</div>	
	</div>

  <div class="card-deck">
    <div class="card border-danger mb-3" style="width: 14rem;">      
      <div class="card-body text-left bg-danger">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-balance-scale-left"></i>
            <?php
            $saldo_actualBs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 1)
              ->orderBy('id', 'desc')
              ->first();
            
            if(empty($saldo_actualBs)) {
                echo 'Saldo actual: '. number_format(0, 2, ',', '.') . " " . SigVe;
              }
              else {
                echo 'Saldo actual: '. number_format($saldo_actualBs->saldo_actual, 2, ',', '.') . " " . SigVe;
              }
          ?>            
          </span>
        </h3>
        <p class="card-text text-white">
        <?php 
          echo 'Movimientos en bolivares registrados: ' . $movimientosBs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a", time());
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-danger text-right">
        <a href="/movimientos?tasa_ventas_id=1" class="btn btn-outline-danger btn-sm">Visualizar</a>
      </div>    
    </div>

    <div class="card border-success mb-3" style="width: 14rem;">      
      <div class="card-body text-left bg-success">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-balance-scale"></i>
            <?php 
            $saldo_actualDs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 2)
              ->orderBy('id', 'desc')
              ->first();

              if(empty($saldo_actualDs)) {
                echo 'Saldo actual: ' . number_format(0, 2, ',', '.') . " " . SigDolar;
              }
              else {
                echo 'Saldo actual: ' . number_format($saldo_actualDs->saldo_actual, 2, ',', '.') . " " . SigDolar;
              }
          ?>            
          </span>
        </h3>
        <p class="card-text text-white">
        <?php 
          echo 'Movimientos en dolares registrados: ' . $movimientosDs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a", time());
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-success text-right">
        <a href="/movimientos?tasa_ventas_id=2" class="btn btn-outline-success btn-sm">Visualizar</a>
      </div>    
    </div>
  </div>
	<!-- Dashboard GERENCIA-->
<?php
  }
?>
<!-- GERENCIA -->
<!-------------------------------------------------------------------------------->
<!-- TECNOLOGIA -->
<?php
  if(Auth::user()->departamento == 'TECNOLOGIA'){
?>
	<!-- Modal TECNOLOGIA -->
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
	      	<label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
	      	<br/>
	      	Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el reporte: 
					<b class="text-info">Consultor de Precios</b>!!
  				</li>
  			</ul>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el reporte: 
					<b class="text-info">Reporte de Atributos</b>!!
  				</li>
  			</ul>
  			<ul style="list-style:none">
        	<li class="card-text text-dark" style="display: inline;">
					<i class="far fa-check-circle text-info" style="display: inline;"></i>
					Desde ya esta disponible el modulo: 
					<b class="text-info">Fecha de Vencimiento</b>!!
  				</li>
  			</ul>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
	      </div>
	    </div>
  	</div>
	</div>
	<!-- Modal TECNOLOGIA -->
	
	<!-- Dashboard Articulo Estrella-->
	<!-- <div class="card-deck">		
		<div class="card border-secondary mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-light">
    		<h3 class="card-title">
	    		<span class="card-text text-dark">
	    			<i class="fas fa-credit-card"></i>
	    			Articulos Estrella					
	    		</span>
    		</h3>
    		<p class="card-text text-dark">
    			<?php
						//FG_Acticulos_Estrella_Top('FTN');
					?>
    		</p>
  		</div>
	  	<div class="card-footer bg-transparent border-secondary text-right">
	  		<a href="/reporte16/" class="btn btn-outline-secondary btn-sm">Visualizar</a>
	  	</div>
		</div>	
	</div> -->
	<!-- Dashboard Articulo Estrella -->

	<!-- Dashboard TECNOLOGIA-->
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
	  	<div class="card-footer bg-transparent border-warning text-right">
	  		<a href="/usuario/" class="btn btn-outline-warning btn-sm">Visualizar</a>
	  	</div>
		</div>
		<!-- Dolar -->
		<div class="card border-secondary mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-secondary">
	    		<h3 class="card-title">
		    		<span class="card-text text-white">
		    			<i class="fas fa-credit-card"></i>
		    			<?php
								echo 'Tasa Mercado: '.$TasaMercado.' '.SigVe;
							?>						
		    		</span>
	    		</h3>
	    		<p class="card-text text-white">
					<?php 
						echo 'Ultima Actualizacion: '.$FechaTasaMercado;
					?>
	    		</p>
  		</div>
	  	<div class="card-footer bg-transparent border-secondary text-right">
	  		<a href="/dolar/" class="btn btn-outline-secondary btn-sm">Visualizar</a>
	  	</div>	
		</div>
		<!-- Tasa Venta -->
		<div class="card border-dark mb-3" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-dark">
    		<h3 class="card-title">
	    		<span class="card-text text-white">
	    			<i class="fas fa-credit-card"></i>
	    			<?php
							echo 'Tasa Venta: '.$Tasa.' '.SigVe;
						?>						
	    		</span>
    		</h3>
    		<p class="card-text text-white">
				<?php 
					echo 'Ultima Actualizacion: '.$tasaVenta;
				?>
    		</p>
  		</div>
	  	<div class="card-footer bg-transparent border-dark text-right">
	  		<a href="/tasaVenta/" class="btn btn-outline-dark btn-sm">Visualizar</a>
	  	</div>
		</div>	
	</div>
	<!-- Dashboard TECNOLOGIA-->
<?php
  }
?>
<!-- TECNOLOGIA -->
<!-------------------------------------------------------------------------------->
<!-- CONTACTO -->
<hr class="row align-items-start col-12">
	<div class="card-deck">
		<div class="card border-info" style="width: 14rem;">	  	
  		<div class="card-body text-left bg-info">
    		<h2 class="card-title">	 		
	    		<span class="card-text text-warning">
	    			<i class="far fa-lightbulb CP-beep"></i>
	    		</span>
	    		<span class="card-text text-white">
	    			Tienes una idea.?
	    		</span>
    		</h2>
    		<div class="text-center">
    			<div class="text-center" style="display: inline-block; vertical-align: middle;">
  					<h3 class="card-text text-white"><i class="far fa-keyboard"></i></h3>
  					<h5 class="card-text text-white">Redacta tu idea</h5>
    			</div>
    			<div class="text-center" style="display: inline-block; vertical-align: middle;">
  					<h3 class="card-text text-white"><i class="fas fa-angle-double-right"><br/><br/></i>
  					</h3>
    			</div>
    			<div class="text-center" style="display: inline-block; vertical-align: middle;">
  					<h3 class="card-text text-white"><i class="far fa-envelope"></i></h3>
  					<h5 class="card-text text-white">Enviala a scova@farmacia72.com.ve</h5>
    			</div>
    			<div class="text-center" style="display: inline-block; vertical-align: middle;">
  					<h3 class="card-text text-white"><i class="fas fa-angle-double-right"><br/><br/></i></h3>
    			</div>
					<div class="text-center" style="display: inline-block; vertical-align: middle;">
  					<h3 class="card-text text-white"><i class="far fa-clock"></i></h3>
  					<h5 class="card-text text-white">Espera nuestro contacto</h5>
    			</div>
    		</div>  
			</div>
		</div>
	</div>
<!-- CONTACTO -->
<!-------------------------------------------------------------------------------->
	<script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();   
		});
		$('#exampleModalCenter').modal('show')
	</script>
@endsection