@extends('layouts.model')

@section('title')
    Orden de compra
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
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i> Orden de compra: {{ session('Saved') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Orden de compra generada con exito, codigo: {{ session('Saved') }}</h4>
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
		        <h4 class="h6">Orden de compra actualizada con exito</h4>
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
		        <h4 class="h6">Orden de compra actualizada con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>	
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<!-- Modal OrdenActiva -->
	@if (session('OrdenActiva'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-danger" id="exampleModalCenterTitle"><i class="fas fa-info text-danger"></i> Orden de compra</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Ya usted posee una orden de compra activa, codigo: {{ session('OrdenActiva') }}</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<h1 class="h5 text-info">
		<i class="far fa-file-alt"></i>
		Orden de compra
	</h1>

	<hr class="row align-items-start col-12">
	<table style="width:100%;">
	    <tr>
	        <td style="width:10%;" align="center">	        	
				<a href="{{ url('/ordenCompra/create') }}" role="button" class="btn btn-outline-info btn-sm" 
				style="display: inline; text-align: left;">
				<i class="fa fa-plus"></i>
					Agregar		      		
				</a>
	        </td>
	        <td style="width:90%;">
	        	<div class="input-group md-form form-sm form-1 pl-0">
				  <div class="input-group-prepend">
				    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
				        aria-hidden="true"></i></span>
				  </div>
				  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
				</div>
	        </td>
	    </tr>
	</table>
	<br/>

	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col" class="stickyCP">#</th>
		      	<th scope="col" class="stickyCP">Orden compra</th>
		      	<th scope="col" class="stickyCP">Proveedor</th>
		      	<th scope="col" class="stickyCP">Fecha de la orden</th>
		      	<th scope="col" class="stickyCP">Fecha estimada de despacho</th>
		      	<th scope="col" class="stickyCP">Dias transcurridos</th>
		      	<th scope="col" class="stickyCP">Monto total Bs</th>
		      	<th scope="col" class="stickyCP">Monto total $</th>
		      	<th scope="col" class="stickyCP">Unidades</th>
		      	<th scope="col" class="stickyCP">Condicion crediticia</th>
		      	<th scope="col" class="stickyCP">Dias de credito</th>
		      	<th scope="col" class="stickyCP">Estatus</th>
		      	<th scope="col" class="stickyCP">Operador</th>
		      	<th scope="col" class="stickyCP">Monto real</th>
		      	<th scope="col" class="stickyCP">Fecha de aprobacion</th>
		      	<th scope="col" class="stickyCP">Calificacion</th>
		      	<th scope="col" class="stickyCP">Fecha de recepcion</th>
		      	<th scope="col" class="stickyCP">Fecha de ingreso</th>
		      	<th scope="col" class="stickyCP">Acciones</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($OrdenCompra as $ordenCompra)
			<?php 
				if($ordenCompra->estado=='CERRADA'){
					$Dias = FG_Rango_Dias($ordenCompra->created_at,$ordenCompra->updated_at);
				}
				else{
					$Dias = FG_Rango_Dias($ordenCompra->created_at,date('Y-m-d H:i:s'));
				}
			?>

		    <tr>
		      <th>{{$ordenCompra->id}}</th>
		      <td>{{$ordenCompra->codigo}}</td>
		      <td>{{$ordenCompra->proveedor}}</td>
		      <td>{{$ordenCompra->created_at}}</td>
		      <td>{{$ordenCompra->fecha_estimada_despacho}}</td>
		      <td>{{$Dias}}</td>
		      <td>{{$ordenCompra->montoTotalBs}}</td>
		      <td>{{$ordenCompra->montoTotalUsd}}</td>
		      <td>{{$ordenCompra->totalUnidades}}</td>
		      <td>{{$ordenCompra->condicion_crediticia}}</td>
		      <td>{{$ordenCompra->dias_credito}}</td>
		      <td>{{$ordenCompra->estado}}</td>
		      <td>{{$ordenCompra->user}}</td>
		      <td>{{$ordenCompra->montoTotalReal}}</td>
		      <td>{{$ordenCompra->fecha_aprobacion}}</td>
		      <td>{{$ordenCompra->calificacion}}</td>
		      <td>{{$ordenCompra->fecha_recepcion}}</td>
		      <td>{{$ordenCompra->fecha_ingreso}}</td>

		    <!-- Inicio Validacion de ROLES -->
		      <td style="width:140px;">
				
				<a href="/ordenCompra/{{$ordenCompra->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
    			<i class="far fa-eye"></i>			      		
    		</a>

				<?php
				if( ($ordenCompra->estado=='EN PROCESO')
						&&
						( ($ordenCompra->user==Auth::user()->name)
					 		|| (Auth::user()->departamento == 'TECNOLOGIA')
					 		|| (Auth::user()->departamento == 'GERENCIA')
				 		)
					){
				?>
					<a href="/ordenCompra/{{$ordenCompra->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
      			<i class="fas fa-edit"></i>			      		
	      	</a>

					<?php
					if($ordenCompra->estatus == 'ACTIVO'){
					?>  
		      	<form action="/ordenCompra/{{$ordenCompra->id}}" method="POST" style="display: inline;">
				    @method('DELETE')
				    @csrf					    
				    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Pausar"><i class="fa fa-pause"></i></button>
						</form>
					<?php
					}
					else if($ordenCompra->estatus == 'EN ESPERA'){
					?>  
		      	<form action="/ordenCompra/{{$ordenCompra->id}}" method="POST" style="display: inline;">
				    @method('DELETE')
				    @csrf					    
				    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Ignorar"><i class="fa fa-square"></i></button>
						</form>
					<?php
					}
					else if($ordenCompra->estatus == 'IGNORAR'){
					?>  
		      	<form action="/ordenCompra/{{$ordenCompra->id}}" method="POST" style="display: inline;">
				    @method('DELETE')
				    @csrf					    
				    <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Activar"><i class="fa fa-play"></i></button>
						</form>
					<?php
					}
					?>

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