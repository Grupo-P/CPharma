@extends('layouts.model')

@section('title')
    Traslado
@endsection

<?php
	include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');
?>

@section('content')

	<!-- Modal Guardar -->
	@if (session('Saved'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Saved') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Traslado almacenado con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif
	
	<!-- Modal Editar -->
	@if (session('Updated'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Updated') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Traslado modificado con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<!-- Modal Eliminar -->
	@if (session('Deleted'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Deleted') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Traslado actualizado con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>	
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<h1 class="h5 text-info">
		<i class="fas fa-people-carry"></i>
		Traslado
	</h1>
	
	<hr class="row align-items-start col-12">
	<br/>
	<table style="width:100%;" class="CP-stickyBar">
	    <tr>
	        <td style="width:10%;" align="center">        	
				<a href="{{ url('/SearchAjuste') }}" role="button" class="btn btn-outline-info btn-sm" 
				style="display: inline; text-align: left;">
				<i class="fas fa-plus"></i>
					Agregar		      		
				</a>
	        </td>
	        <td style="width:90%;">
	        	<div class="input-group md-form form-sm form-1 pl-0">
				  <div class="input-group-prepend">
				    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
				        aria-hidden="true"></i></span>
				  </div>
				  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
				</div>
	        </td>
	    </tr>
	</table>
	<br/>
	<table class="table table-striped table-borderless col-12 sortable">
  	<thead class="thead-dark">
	    <tr>
	      	<th scope="col" colspan="5" style="text-align: center;">CLASIFICACION</th>
	    </tr>
		</thead>
		<tbody>
	  	<tr>
	  	<td style="width:20%;" align="center">
	  		<?php
	  			$tipo = 3;
	  		?>
				<form action="/traslado?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-success btn-sm" value="3">TODO</button>
				</form>
	    </td>

	  	<td style="width:20%;" align="center">
	  		<?php
	  			$tipo = 0;
	  		?>
				<form action="/traslado?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-dark btn-sm" value="0">PROCESADO</button>
				</form>
	    </td>

	    <td style="width:20%;" align="center">
	      	<?php
	  			$tipo = 1;
	  		?>
				<form action="/traslado?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-danger btn-sm" value="1">EMBALADO</button>
					</form>	
			</td>

	    <td style="width:20%;" align="center">
	      	<?php
	  			$tipo = 2;
	  		?>
				<form action="/traslado?Tipo={{$tipo}}" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-info btn-sm" value="2">ENTREGADO</button>
				</form>						
	    </td>
	    </tr>
		</tbody>
	</table>
	<br/>
	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col" class="CP-sticky">#</th>
		      	<th scope="col" class="CP-sticky">Ajuste</th>
		      	<th scope="col" class="CP-sticky">Fecha Ajuste</th>
		      	<th scope="col" class="CP-sticky">Fecha Traslado</th>	
		      	<th scope="col" class="CP-sticky">Sede Destino</th>
		      	<th scope="col" class="CP-sticky">Unidades</th>
		      	<th scope="col" class="CP-sticky">Bultos</th>
		      	<th scope="col" class="CP-sticky">Total Bs.S</th>
		      	<th scope="col" class="CP-sticky">Total $</th>
		      	<th scope="col" class="CP-sticky">Estatus</th>
		      	<th scope="col" class="CP-sticky">Dias en traslado</th>
		      	<th scope="col" class="CP-sticky">Acciones</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($traslados as $traslado)
			<?php 
				$connCPharma = FG_Conectar_CPharma();
				$sql = MySQL_Buscar_Traslado_Detalle($traslado->numero_ajuste);
				$result = mysqli_query($connCPharma,$sql);

				$Total_Cantidad = 0;
				$Total_Impuesto_Bs = 0;
				$Total_Impuesto_Usd = 0;
				$Total_Bs = 0;
				$Total_Usd = 0;

				while($row = $result->fetch_assoc()) {
					$Total_Cantidad += floatval($row['cantidad']);
					$Total_Impuesto_Bs += floatval($row['total_imp_bs']);
					$Total_Impuesto_Usd += floatval($row['total_imp_usd']);
					$Total_Bs += floatval($row['total_bs']);
					$Total_Usd += floatval($row['total_usd']);
				}
				mysqli_close($connCPharma);

				$Total_Bs = number_format ($Total_Bs,2,"," ,"." );
				$Total_Usd = number_format ($Total_Usd,2,"," ,"." );

				if($traslado->estatus=='ENTREGADO'){
					$Dias = FG_Rango_Dias($traslado->fecha_traslado,$traslado->updated_at);
				}
				else{
					$Dias = FG_Rango_Dias($traslado->fecha_traslado,date('Y-m-d H:i:s'));
				}
			?>
		    <tr>
		    	<th>{{$traslado->id}}</th>
		      <td>{{$traslado->numero_ajuste}}</td>
		      <td>{{$traslado->fecha_ajuste}}</td>
		      <td>{{$traslado->fecha_traslado}}</td>
		      <td>{{$traslado->sede_destino}}</td>
		      <td>{{$Total_Cantidad}}</td>
		      <td>{{$traslado->bultos}}</td>
		      <td>{{$Total_Bs}}</td>
		      <td>{{$Total_Usd}}</td>
		      <td>{{$traslado->estatus}}</td>
		      <td>{{$Dias}}</td>
		      
		    <!-- Inicio Validacion de ROLES -->
		      <td style="width:170px;">
					<?php
					if(($traslado->estatus=='PROCESADO'||$traslado->estatus=='EMBALADO'||$traslado->estatus=='ENTREGADO') && 
						(Auth::user()->departamento == 'OPERACIONES'
						|| Auth::user()->departamento == 'LÍDER DE TIENDA' 
						|| Auth::user()->departamento == 'INVENTARIO'
				    || Auth::user()->departamento == 'GERENCIA'
				    || Auth::user()->departamento == 'TECNOLOGIA')
						){
					?>
						<a href="/traslado/{{$traslado->id}}" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Soporte Traslado" style="width: auto">
	      			<i class="fas fa-print"></i>		      		
	      		</a>
					<?php
					}
					?>						
		     
					<?php
					if(($traslado->estatus=='PROCESADO'||$traslado->estatus=='EMBALADO') && 
						(Auth::user()->departamento == 'ALMACEN'
						|| Auth::user()->departamento == 'LÍDER DE TIENDA'
				    || Auth::user()->departamento == 'GERENCIA'
				    || Auth::user()->departamento == 'TECNOLOGIA')
						){
					?>
						<a href="/traslado/{{$traslado->id}}/edit" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Embalar" style="width: auto">
	      			<i class="fas fa-box-open"></i>      		
	      		</a>
					<?php
					}
					?>						

		      <?php
					if(($traslado->estatus=='EMBALADO') && 
						(Auth::user()->departamento == 'ALMACEN'
						|| Auth::user()->departamento == 'LÍDER DE TIENDA'
				    || Auth::user()->departamento == 'GERENCIA'
				    || Auth::user()->departamento == 'TECNOLOGIA')
						){
					?>
						<a href="/GuiaEnvio?Ajuste={{$traslado->numero_ajuste}}" role="button" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Guia de envio y etiquetas" style="width: auto">
	      			<i class="fas fa-tag"></i>     		
	      		</a>
					<?php
					}
					?>

					<?php
					if(($traslado->estatus=='EMBALADO') && 
						(Auth::user()->departamento == 'ADMINISTRACION'
				    || Auth::user()->departamento == 'GERENCIA'
				    || Auth::user()->departamento == 'TECNOLOGIA'
				    || (Auth::user()->departamento == 'SURTIDO' && Auth::user()->role == 'SUPERVISOR')
				  )
						){
					?>
						<form action="/traslado/{{$traslado->id}}" method="POST" style="display: inline;">
					    @method('DELETE')
					    @csrf					    
					    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Finalizar"><i class="fa fa-check"></i></button>
						</form>
					<?php
					}
					?>						
		      </td>
		    <!-- Fin Validacion de ROLES -->

		    </tr>
		@endforeach
		</tbody>
	</table>

	<script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();   
		});
		$('#exampleModalCenter').modal('show')
	</script>

@endsection